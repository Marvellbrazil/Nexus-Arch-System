<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'username',
        'full_name',
        'email',
        'password',
        'role_id',
        'department_id',
        'phone_number',
        'photo_profile',
        'otp',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,user_id,{user_id}]',
        'full_name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email,user_id,{user_id}]',
        'password' => 'permit_empty|min_length[6]',
        'role_id' => 'required|integer',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'is_unique' => 'Username already exists'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email',
            'is_unique' => 'Email already registered'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Get users with role and department information
     */
    /**
     * Create new user with validation
     */
    public function createUser(array $data): array
    {
        try {
            // Validasi input
            if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
                return ['success' => false, 'message' => 'Required fields are missing'];
            }

            // Cek apakah username sudah ada
            $existingUsername = $this->where('username', $data['username'])->first();
            if ($existingUsername) {
                return ['success' => false, 'message' => 'Username already exists'];
            }

            // Cek apakah email sudah ada
            $existingEmail = $this->where('email', $data['email'])->first();
            if ($existingEmail) {
                return ['success' => false, 'message' => 'Email already registered'];
            }

            // Persiapkan data untuk disimpan
            $userData = [
                'username' => trim($data['username']),
                'full_name' => trim($data['full_name']),
                'email' => trim($data['email']),
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'role_id' => (int)$data['role_id'],
                'is_active' => isset($data['is_active']) && $data['is_active'] == '1' ? true : false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Tambahkan optional fields
            if (!empty($data['department_id'])) {
                $userData['department_id'] = (int)$data['department_id'];
            }

            if (!empty($data['phone_number'])) {
                $userData['phone_number'] = trim($data['phone_number']);
            }

            // Simpan ke database
            $inserted = $this->insert($userData);

            if ($inserted) {
                $userId = $this->getInsertID();
                return [
                    'success' => true,
                    'message' => 'User created successfully',
                    'user_id' => $userId,
                    'data' => $userData
                ];
            } else {
                return ['success' => false, 'message' => 'Failed to save user to database'];
            }
        } catch (\Exception $e) {
            log_message('error', 'Create user error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function getUsersWithRole(array $filters = [], int $limit = 10, int $offset = 0): array
    {
        $builder = $this->db->table('users u');

        $builder->select('u.*, r.role_name, r.role_id, d.department_name, d.department_id')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left');

        // Apply filters
        $this->applyUserFilters($builder, $filters);

        // Sorting
        $sortField = $filters['sort'] ?? 'u.created_at';
        $sortOrder = $filters['order'] ?? 'DESC';
        $builder->orderBy($sortField, $sortOrder);

        // Pagination
        $builder->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    /**
     * Count users with filters
     */
    public function countFilteredUsers(array $filters = []): int
    {
        $builder = $this->db->table('users u');

        $builder->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left');

        // Apply filters
        $this->applyUserFilters($builder, $filters);

        return $builder->countAllResults();
    }

    public function getUserStatistics(): array
    {
        $db = db_connect();

        // Total users
        $totalUsers = $db->table('users')->countAll();

        // Active/inactive users
        $activeUsers = $db->table('users')->where('is_active', true)->countAllResults();
        $inactiveUsers = $db->table('users')->where('is_active', false)->countAllResults();

        // Users by role
        $usersByRole = $db->table('users u')
            ->select('r.role_name, COUNT(*) as count')
            ->join('roles r', 'r.role_id = u.role_id')
            ->groupBy('r.role_name')
            ->get()
            ->getResultArray();

        // Recent users
        $recentUsers = $db->table('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->orderBy('u.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers,
            'users_by_role' => $usersByRole,
            'recent_users' => $recentUsers
        ];
    }

    /**
     * Get user trend data
     */
    public function getUserTrend(): array
    {
        $db = db_connect();

        $lastWeek = date('Y-m-d', strtotime('-7 days'));

        $currentWeekUsers = $db->table('users')
            ->where('created_at >=', $lastWeek)
            ->countAllResults();

        $previousWeekUsers = $db->table('users')
            ->where('created_at >=', date('Y-m-d', strtotime('-14 days')))
            ->where('created_at <', $lastWeek)
            ->countAllResults();

        return [
            'current' => $currentWeekUsers,
            'previous' => $previousWeekUsers,
            'trend' => $this->calculateTrend($currentWeekUsers, $previousWeekUsers)
        ];
    }

    /**
     * Calculate trend percentage
     */
    private function calculateTrend($current, $previous): string
    {
        if ($previous == 0) {
            return $current > 0 ? '+100%' : '0%';
        }

        $trend = (($current - $previous) / $previous) * 100;
        return ($trend >= 0 ? '+' : '') . round($trend, 1) . '%';
    }

    /**
     * Export users to CSV
     */
    public function exportUsers(array $filters = []): array
    {
        $db = db_connect();

        $query = $db->table('users u')
            ->select('u.user_id, u.username, u.full_name, u.email, 
                     r.role_name, d.department_name, 
                     u.is_active, u.created_at, u.last_login')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left');

        // Apply filters
        $this->applyUserFilters($query, $filters);

        $query->orderBy('u.created_at', 'DESC');

        return $query->get()->getResultArray();
    }

    /**
     * Apply filters to query
     */
    private function applyUserFilters(&$query, $filters): void
    {
        if (!empty($filters['search'])) {
            $query->groupStart()
                ->like('u.username', $filters['search'])
                ->orLike('u.full_name', $filters['search'])
                ->orLike('u.email', $filters['search'])
                ->groupEnd();
        }

        if (!empty($filters['role_id'])) {
            $query->where('u.role_id', $filters['role_id']);
        }

        if (!empty($filters['department_id'])) {
            $query->where('u.department_id', $filters['department_id']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('u.is_active', $filters['is_active']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('DATE(u.created_at) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('DATE(u.created_at) <=', $filters['date_to']);
        }
    }

    /**
     * Get users by department
     */
    public function getUsersByDepartment(int $departmentId): array
    {
        // PostgreSQL boolean literal
        return $this->where('department_id', $departmentId)
            ->where('is_active', true)
            ->findAll();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(int $roleId): array
    {
        // PostgreSQL boolean literal
        return $this->where('role_id', $roleId)
            ->where('is_active', true)
            ->findAll();
    }

    /**
     * Update user last login
     */
    public function updateLastLogin(int $userId): bool
    {
        return $this->update($userId, [
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get recent users
     */
    public function getRecentUsers(int $limit = 5): array
    {
        return $this->select('user_id, username, full_name, email, created_at, is_active')
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Change user status (PostgreSQL boolean handling)
     */
    public function changeStatus(int $userId, bool $isActive): bool
    {
        return $this->update($userId, ['is_active' => $isActive]);
    }

    /**
     * Bulk update users
     */
    public function bulkUpdate(array $userIds, array $data): bool
    {
        return $this->whereIn('user_id', $userIds)
            ->set($data)
            ->update();
    }

    /**
     * Hash password callback
     */
    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Verify user credentials
     */
    public function verifyCredentials(string $email, string $password): ?array
    {
        $user = $this->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }

        return $user;
    }

    /**
     * Get user projects for details
     */
    public function getUserProjects(int $userId): array
    {
        $sql = "
            SELECT p.project_name 
            FROM projects p
            INNER JOIN project_assignments pa ON p.project_id = pa.project_id
            WHERE pa.user_id = ?
            AND p.is_active = true
            ORDER BY p.project_name
        ";

        return $this->db->query($sql, [$userId])->getResultArray();
    }

    /**
     * Check if email exists (excluding current user)
     */
    public function emailExists(string $email, ?int $excludeUserId = null): bool
    {
        $builder = $this->builder();
        $builder->where('email', $email);

        if ($excludeUserId) {
            $builder->where('user_id !=', $excludeUserId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Check if username exists (excluding current user)
     */
    public function usernameExists(string $username, ?int $excludeUserId = null): bool
    {
        $builder = $this->builder();
        $builder->where('username', $username);

        if ($excludeUserId) {
            $builder->where('user_id !=', $excludeUserId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Get active users with their roles for project assignment
     */
    public function getActiveUsersWithRoles(): array
    {
        $db = db_connect();

        return $db->table('users u')
            ->select('u.user_id, u.username, u.full_name, u.email, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('u.is_active', true)
            ->orderBy('u.full_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Search active users
     */
    public function searchActiveUsers(string $keyword): array
    {
        return $this->builder()
            ->select('user_id, username, full_name, email')
            ->where('is_active', true)
            ->groupStart()
            ->like('full_name', $keyword)
            ->orLike('username', $keyword)
            ->orLike('email', $keyword)
            ->groupEnd()
            ->orderBy('full_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get user statistics for dashboard
     */
    public function getDashboardStatistics(): array
    {
        $db = db_connect();

        // Total users
        $totalUsers = $db->table('users')->countAll();

        // Active/inactive users
        $activeUsers = $db->table('users')->where('is_active', true)->countAllResults();
        $inactiveUsers = $db->table('users')->where('is_active', false)->countAllResults();

        // Users by role
        $usersByRole = $db->table('users u')
            ->select('r.role_name, COUNT(*) as count')
            ->join('roles r', 'r.role_id = u.role_id')
            ->groupBy('r.role_name')
            ->get()
            ->getResultArray();

        // Recent users
        $recentUsers = $db->table('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->orderBy('u.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // User trend
        $userTrend = $this->getUserTrend();

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers,
            'users_by_role' => $usersByRole,
            'recent_users' => $recentUsers,
            'user_trend' => $userTrend['trend'] ?? '0%'
        ];
    }

    /**
     * Get avatar initials from full name
     */
    public function getAvatarInitials(string $fullName): string
    {
        $initials = '';
        $names = explode(' ', $fullName);

        foreach ($names as $name) {
            if (strlen($initials) < 2) {
                $initials .= strtoupper(substr($name, 0, 1));
            }
        }

        return $initials;
    }

    /**
     * Get avatar color based on role
     */
    public function getAvatarColor(string $role): string
    {
        $colors = [
            'Admin' => '#F3E8FF',
            'Support' => '#DBEAFE',
            'Developer' => '#E0E7FF',
            'Customer' => '#FEF3C7'
        ];

        return $colors[$role] ?? '#F3F4F6';
    }

    /**
     * Get CSS class for role badge
     */
    public function getRoleClass(string $roleName): string
    {
        $roleClasses = [
            'Admin' => 'role-admin',
            'Support' => 'role-support',
            'Developer' => 'role-developer',
            'Customer' => 'role-customer',
            'Manager' => 'role-manager',
            'Supervisor' => 'role-supervisor',
            'Staff' => 'role-staff'
        ];

        return $roleClasses[$roleName] ?? 'role-default';
    }

    /**
     * Get user by email
     */
    public function getUserByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Update a user record
     */
    public function updateUser(int $id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Update user password
     */
    public function updateUserPassword(int $id, string $password)
    {
        return $this->update($id, ['password' => $password]);
    }

    /**
     * Update user reset token
     */
    public function updateUserResetToken(int $id, $otp)
    {
        return $this->update($id, ['otp' => $otp]);
    }

    /**
     * Get basic user details
     */
    public function getBasicUserDetails(int $userId)
    {
        return $this->select('username, full_name, email, photo_profile')
            ->where('user_id', $userId)
            ->first();
    }

    public function getProjectTeamMembers(int $projectId, int $excludeUserId): array
    {
        return $this->builder('users u')
            ->select('u.user_id, u.full_name, u.email, r.role_name, u.photo_profile')
            ->join('tickets t', 'u.user_id = t.assigned_to')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('t.project_id', $projectId)
            ->where('u.user_id !=', $excludeUserId)
            ->distinct()
            ->get()
            ->getResultArray();
    }

    public function getUserDetails($userId)
    {
        return $this->builder('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('u.user_id', $userId)
            ->get()
            ->getRowArray();
    }

    public function getUserWithRole($userId)
    {
        return $this->builder('users u')
            ->select('u.*, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('u.user_id', $userId)
            ->get()
            ->getRowArray();
    }

    // UserModel.php - tambahkan method ini

    /**
     * Search users with filters (for AJAX)
     */
    public function searchUsers(array $filters = [], int $limit = 10, int $offset = 0): array
    {
        $builder = $this->db->table('users u');

        $builder->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left');

        // Apply filters
        if (!empty($filters['search'])) {
            $searchTerm = trim($filters['search']);
            $builder->groupStart()
                ->like('u.username', $searchTerm)
                ->orLike('u.full_name', $searchTerm)
                ->orLike('u.email', $searchTerm)
                ->orLike('r.role_name', $searchTerm)
                ->orLike('d.department_name', $searchTerm)
                ->groupEnd();
        }

        if (!empty($filters['role_id'])) {
            $builder->where('u.role_id', $filters['role_id']);
        }

        if (!empty($filters['department_id'])) {
            $builder->where('u.department_id', $filters['department_id']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $builder->where('u.is_active', $filters['is_active'] == '1' ? true : false);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('DATE(u.created_at) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('DATE(u.created_at) <=', $filters['date_to']);
        }

        $builder->orderBy('u.created_at', 'DESC')
            ->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    /**
     * Count filtered users
     */
    public function countFiltered(array $filters = []): int
    {
        $builder = $this->db->table('users u')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left');

        // Apply filters
        if (!empty($filters['search'])) {
            $searchTerm = trim($filters['search']);
            $builder->groupStart()
                ->like('u.username', $searchTerm)
                ->orLike('u.full_name', $searchTerm)
                ->orLike('u.email', $searchTerm)
                ->orLike('r.role_name', $searchTerm)
                ->orLike('d.department_name', $searchTerm)
                ->groupEnd();
        }

        if (!empty($filters['role_id'])) {
            $builder->where('u.role_id', $filters['role_id']);
        }

        if (!empty($filters['department_id'])) {
            $builder->where('u.department_id', $filters['department_id']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $builder->where('u.is_active', $filters['is_active'] == '1' ? true : false);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('DATE(u.created_at) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('DATE(u.created_at) <=', $filters['date_to']);
        }

        return $builder->countAllResults();
    }
    
// Tambahkan method di UserModel.php

    /**
     * Get role priority
     */
    public function getRolePriority(string $roleName): int
    {
        $priorityMap = [
            'Admin' => 1,
            'Department' => 2,
            'Support' => 3,
            'Customer' => 4
        ];

        // Untuk department custom
        if (strpos($roleName, 'Department') !== false) {
            return 2;
        }

        return $priorityMap[$roleName] ?? 5;
    }

    /**
     * Sort users by role priority
     */
    public function sortUsersByRolePriority(array &$users): void
    {
        usort($users, function ($a, $b) {
            $priorityA = $this->getRolePriority($a['role_name'] ?? '');
            $priorityB = $this->getRolePriority($b['role_name'] ?? '');

            if ($priorityA == $priorityB) {
                // Jika sama priority, urutkan berdasarkan:
                // 1. Department name (untuk Department role)
                // 2. Created date (user baru di bawah)
                $deptA = strtolower($a['department_name'] ?? '');
                $deptB = strtolower($b['department_name'] ?? '');

                if ($deptA != $deptB) {
                    return strcmp($deptA, $deptB);
                }

                return strtotime($a['created_at'] ?? '') <=> strtotime($b['created_at'] ?? '');
            }

            return $priorityA <=> $priorityB;
        });
    }

    // Tambahkan di UserModel.php

    /**
     * Update user dengan handling PostgreSQL boolean
     */
    public function updateUserWithPostgres(int $id, array $data): bool
    {
        $db = db_connect();

        // Handle boolean untuk PostgreSQL
        if (isset($data['is_active'])) {
            $data['is_active'] = $data['is_active'] ? 't' : 'f';
        }

        // Handle nullable fields
        $fieldsToNull = ['department_id', 'phone_number'];
        foreach ($fieldsToNull as $field) {
            if (isset($data[$field]) && ($data[$field] === '' || $data[$field] === null)) {
                $data[$field] = null;
            }
        }

        $builder = $db->table($this->table);
        $builder->where($this->primaryKey, $id);

        return $builder->update($data);
    }

    /**
     * Get user dengan role dan department
     */
    public function getUserWithDetails(int $userId): ?array
    {
        $db = db_connect();

        $user = $db->table('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('u.user_id', $userId)
            ->get()
            ->getRowArray();

        if ($user) {
            // Konversi boolean dari PostgreSQL
            $user['is_active'] = ($user['is_active'] === 't' || $user['is_active'] === true);
        }

        return $user;
    }
}
