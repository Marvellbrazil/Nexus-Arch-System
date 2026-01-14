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
        'reset_token'
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
     * Get users with role and department information (PostgreSQL compatible)
     */
    public function getUsersWithRole(array $filters = [], int $limit = 10, int $offset = 0): array
    {
        $builder = $this->db->table('users u');

        // PostgreSQL specific - use proper boolean handling
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
     * Count users with filters (PostgreSQL compatible)
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

    /**
     * Apply filters to query builder (PostgreSQL compatible)
     */
    private function applyUserFilters(object $builder, array $filters): void
    {
        // Search filter
        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $builder->groupStart();
            $builder->like('u.full_name', $searchTerm);
            $builder->orLike('u.email', $searchTerm);
            $builder->orLike('u.username', $searchTerm);
            $builder->orLike('r.role_name', $searchTerm);
            $builder->orLike('d.department_name', $searchTerm);
            $builder->groupEnd();
        }

        // Role filter
        if (!empty($filters['role_id'])) {
            $builder->where('u.role_id', $filters['role_id']);
        }

        // Department filter
        if (!empty($filters['department_id'])) {
            $builder->where('u.department_id', $filters['department_id']);
        }

        // Status filter - PostgreSQL boolean handling
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            // Convert string to boolean for PostgreSQL
            $isActive = filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN);
            $builder->where('u.is_active', $isActive);
        }

        // Date range filter
        if (!empty($filters['date_from'])) {
            $builder->where('DATE(u.created_at) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $builder->where('DATE(u.created_at) <=', $filters['date_to']);
        }
    }

    /**
     * Get user by ID with full details (PostgreSQL compatible)
     */
    public function getUserWithDetails(int $userId): ?array
    {
        $builder = $this->db->table('users u');

        // PostgreSQL specific - use subqueries with proper column aliasing
        $builder->select("u.*, r.role_name, d.department_name, 
                (SELECT COUNT(*) FROM tickets t WHERE t.customer_id = u.user_id) as total_tickets,
                (SELECT COUNT(*) FROM project_assignments pa WHERE pa.user_id = u.user_id) as total_projects")
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('u.user_id', $userId);

        $result = $builder->get()->getRowArray();
        return $result ?: null;
    }

    /**
     * Get user statistics (PostgreSQL compatible)
     */
    public function getUserStatistics(): array
    {
        $sql = "
            SELECT 
                COUNT(*) as total_users,
                COUNT(CASE WHEN is_active THEN 1 END) as active_users,
                COUNT(CASE WHEN NOT is_active THEN 1 END) as inactive_users,
                COUNT(DISTINCT role_id) as unique_roles,
                COUNT(DISTINCT department_id) as unique_departments,
                AVG(EXTRACT(EPOCH FROM (NOW() - created_at))/86400) as avg_account_age_days
            FROM users
        ";

        $result = $this->db->query($sql)->getRowArray();

        return $result ?: [
            'total_users' => 0,
            'active_users' => 0,
            'inactive_users' => 0,
            'unique_roles' => 0,
            'unique_departments' => 0,
            'avg_account_age_days' => 0
        ];
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
}