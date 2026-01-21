<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table            = 'projects';
    protected $primaryKey       = 'project_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'project_code',
        'project_name',
        'description',
        'is_active'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get recent projects with ticket counts
     */
    public function getRecentProjects(int $limit = 5): array
    {
        $db = db_connect();

        $recentProjects = $db->table('projects')
            ->select('projects.*')
            ->where('is_active', true)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        // Add ticket counts to recent projects
        foreach ($recentProjects as &$project) {
            $project['total_tickets'] = $db->table('tickets')
                ->where('project_id', $project['project_id'])
                ->countAllResults();

            $project['open_tickets'] = $db->table('tickets')
                ->where('project_id', $project['project_id'])
                ->groupStart()
                ->where('status_id', 1)
                ->orWhere('status_id', 2)
                ->groupEnd()
                ->countAllResults();
        }

        return $recentProjects;
    }

    /**
     * Get project overview with ticket counts
     */
    public function getProjectOverview(int $limit = 5): array
    {
        $db = db_connect();

        $projects = $db->table('projects p')
            ->select('p.*, 
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id) as total_tickets,
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)) as open_tickets')
            ->where('p.is_active', 1)
            ->orderBy('p.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return $projects;
    }

    /**
     * Check if project code exists
     */
    public function projectCodeExists(string $projectCode, ?int $excludeProjectId = null): bool
    {
        $builder = $this->builder();
        $builder->where('project_code', strtoupper($projectCode));

        if ($excludeProjectId) {
            $builder->where('project_id !=', $excludeProjectId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Get project with user details
     */
    public function getProjectWithUser(int $projectId): ?array
    {
        $db = db_connect();

        $result = $db->table('projects p')
            ->select('p.*, u.full_name as user_full_name, u.email as user_email')
            ->join('users u', 'u.user_id = p.user_id', 'left')
            ->where('p.project_id', $projectId)
            ->get()
            ->getRowArray();

        return $result ?: null;
    }

    /**
 * Get projects with ticket counts (UPDATED WITH CONSISTENT ORDERING)
 */
public function getProjectsWithTicketCounts(): array
{
    $db = db_connect();

    return $db->table('projects p')
        ->select('p.*, 
            COALESCE((SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id), 0) as total_tickets,
            COALESCE((SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)), 0) as open_tickets,
            COALESCE((SELECT COUNT(*) FROM project_assignments pa WHERE pa.project_id = p.project_id), 0) as assigned_users')
        ->orderBy('p.project_id', 'ASC') // Default order by project_id ASC untuk konsistensi
        ->get()
        ->getResultArray();
}

    /**
     * Change project status
     */
    public function changeStatus(int $projectId, bool $isActive): bool
    {
        return $this->update($projectId, ['is_active' => $isActive]);
    }

    /**
     * Get project statistics for dashboard
     */
    public function getDashboardStatistics(): array
    {
        $db = db_connect();

        // Total active projects
        $totalProjects = $db->table('projects')
            ->where('is_active', true)
            ->countAllResults();

        // Recent projects with ticket counts
        $recentProjects = $this->getRecentProjectsWithTicketCounts(5);

        return [
            'total_projects' => $totalProjects,
            'recent_projects' => $recentProjects
        ];
    }

    /**
     * Get recent projects with ticket counts
     */
    public function getRecentProjectsWithTicketCounts(int $limit = 5): array
    {
        $db = db_connect();

        $recentProjects = $db->table('projects')
            ->select('projects.*')
            ->where('is_active', true)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        // Add ticket counts to recent projects
        foreach ($recentProjects as &$project) {
            $project['total_tickets'] = $db->table('tickets')
                ->where('project_id', $project['project_id'])
                ->countAllResults();

            $project['open_tickets'] = $db->table('tickets')
                ->where('project_id', $project['project_id'])
                ->groupStart()
                ->where('status_id', 1)
                ->orWhere('status_id', 2)
                ->groupEnd()
                ->countAllResults();
        }

        return $recentProjects;
    }

    /**
     * Get quick links for dashboard
     */
    public function getQuickLinks(): array
    {
        return [
            [
                'title' => 'Manage Users',
                'icon' => 'fas fa-users',
                'url' => '/admin/users',
                'description' => 'Add, edit or remove users',
                'color' => 'bg-blue-100 text-blue-600'
            ],
            [
                'title' => 'Manage Roles',
                'icon' => 'fas fa-user-tag',
                'url' => '/admin/roles',
                'description' => 'Configure user permissions',
                'color' => 'bg-purple-100 text-purple-600'
            ],
            [
                'title' => 'Manage Projects',
                'icon' => 'fas fa-project-diagram',
                'url' => '/admin/projects',
                'description' => 'Create and manage projects',
                'color' => 'bg-green-100 text-green-600'
            ],
            [
                'title' => 'System Settings',
                'icon' => 'fas fa-cogs',
                'url' => '/admin/settings',
                'description' => 'Configure system settings',
                'color' => 'bg-yellow-100 text-yellow-600'
            ]
        ];
    }

    public function getProjectsForCustomerDashboard($userId)
    {
        return $this->builder('projects p')
            ->select('
                p.*, 
                COUNT(t.ticket_id) as ticket_count, 
                SUM(CASE WHEN t.status_id = 1 THEN 1 ELSE 0 END) as open_tickets, 
                SUM(CASE WHEN t.status_id = 3 THEN 1 ELSE 0 END) as resolved_tickets
            ')
            ->join('tickets t', 't.project_id = p.project_id', 'left')
            ->join('project_assignments pa', 'pa.project_id = p.project_id', 'left')
            ->where('pa.user_id', $userId)
            ->groupBy('p.project_id')
            ->orderBy('p.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getActiveProjectsForCustomer($userId)
    {
        return $this->builder('projects p')
            ->select('p.project_id, p.project_name, p.project_code')
            ->join('project_assignments pa', 'pa.project_id = p.project_id', 'left')
            ->where('pa.user_id', $userId)
            ->where('p.is_active', true)
            ->get()
            ->getResultArray();
    }

    public function getProjectDetailsForCustomer($projectId, $userId)
    {
        return $this->builder('projects p')
            ->select('p.*, 
                COUNT(DISTINCT t.ticket_id) as total_tickets,
                SUM(CASE WHEN s.status_name = \'Open\' THEN 1 ELSE 0 END) as open_tickets,
                SUM(CASE WHEN s.status_name = \'In Progress\' THEN 1 ELSE 0 END) as in_progress_tickets,
                SUM(CASE WHEN s.status_name = \'Resolved\' THEN 1 ELSE 0 END) as resolved_tickets,
                SUM(CASE WHEN s.status_name = \'Closed\' THEN 1 ELSE 0 END) as closed_tickets')
            ->join('tickets t', 't.project_id = p.project_id AND t.customer_id = ' . $userId, 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->where('p.project_id', $projectId)
            ->groupBy('p.project_id')
            ->get()
            ->getRowArray();
    }

    public function getAssignedProjectsWithTicketCount($userId)
    {
        return $this->builder('projects p')
            ->select('
                p.project_id, 
                p.project_code, 
                p.project_name, 
                p.description, 
                p.created_at,
                COUNT(t.ticket_id) as ticket_count,
                SUM(CASE WHEN t.status_id = 1 THEN 1 ELSE 0 END) as open_tickets,
                SUM(CASE WHEN t.status_id = 2 THEN 1 ELSE 0 END) as in_progress_tickets,
                SUM(CASE WHEN t.status_id = 3 THEN 1 ELSE 0 END) as resolved_tickets
            ')
            ->join('tickets t', 't.project_id = p.project_id', 'left')
            ->join('project_assignments pa', 'pa.project_id = p.project_id', 'left')
            ->where('pa.user_id', $userId)
            ->where('p.is_active', true)
            ->groupBy('p.project_id, p.project_code, p.project_name, p.description, p.created_at')
            ->orderBy('p.project_id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function findProjectForCustomer($projectId, $userId)
    {
        return $this->builder('projects p')
            ->join('project_assignments pa', 'pa.project_id = p.project_id', 'left')
            ->where('p.project_id', $projectId)
            ->where('pa.user_id', $userId)
            ->get()
            ->getRowArray();
    }

    public function getProjectForCustomer($projectId, $userId)
    {
        return $this->builder('projects p')
            ->select('p.*, pa.*')
            ->join('project_assignments pa', 'pa.project_id = p.project_id', 'left')
            ->where('p.project_id', $projectId)
            ->where('pa.user_id', $userId)
            ->get()
            ->getRowArray();
    }

    /**
     * Export projects to CSV with filters
     */
    public function exportProjects(array $filters = []): array
    {
        $db = db_connect();

        $query = $db->table('projects p')
            ->select('p.*,
            (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id) as total_tickets,
            (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)) as open_tickets,
            (SELECT COUNT(*) FROM project_assignments pa WHERE pa.project_id = p.project_id) as assigned_users')
            ->orderBy('p.created_at', 'DESC');

        // Apply filters
        if (!empty($filters['search'])) {
            $query->groupStart()
                ->like('p.project_name', $filters['search'])
                ->orLike('p.project_code', $filters['search'])
                ->orLike('p.description', $filters['search'])
                ->groupEnd();
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $query->where('p.is_active', true);
            } elseif ($filters['status'] === 'inactive') {
                $query->where('p.is_active', false);
            }
        }

        if (!empty($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'name_asc':
                    $query->orderBy('p.project_name', 'ASC');
                    break;
                case 'name_desc':
                    $query->orderBy('p.project_name', 'DESC');
                    break;
                case 'tickets_desc':
                    $query->orderBy('total_tickets', 'DESC');
                    break;
                case 'tickets_asc':
                    $query->orderBy('total_tickets', 'ASC');
                    break;
            }
        }

        return $query->get()->getResultArray();
    }

    /**
     * Get all active users for project assignment
     */
    public function getAllActiveUsers(): array
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
     * Get projects for bulk assignment
     */
    public function getProjectsForBulkAssign(string $search = ''): array
    {
        $db = db_connect();

        $query = $db->table('projects p')
            ->select('p.project_id, p.project_code, p.project_name, p.is_active')
            ->where('p.is_active', true);

        if (!empty($search)) {
            $query->groupStart()
                ->like('p.project_name', $search)
                ->orLike('p.project_code', $search)
                ->groupEnd();
        }

        $query->orderBy('p.project_name', 'ASC');

        return $query->get()->getResultArray();
    }

    /**
     * Search projects
     */
    public function searchProjects(string $keyword, int $limit = 10): array
    {
        $db = db_connect();

        return $db->table('projects p')
            ->select('p.*, 
            (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id) as total_tickets,
            (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)) as open_tickets')
            ->groupStart()
            ->like('p.project_name', $keyword)
            ->orLike('p.project_code', $keyword)
            ->orLike('p.description', $keyword)
            ->groupEnd()
            ->where('p.is_active', true)
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get project statistics
     */
    public function getProjectStatistics(): array
    {
        $db = db_connect();

        $stats = [
            'total_projects' => $db->table('projects')->countAllResults(),
            'active_projects' => $db->table('projects')->where('is_active', true)->countAllResults(),
            'projects_with_tickets' => $db->table('projects p')
                ->join('tickets t', 't.project_id = p.project_id')
                ->groupBy('p.project_id')
                ->countAllResults(),
            'projects_without_tickets' => $db->table('projects p')
                ->select('p.project_id')
                ->join('tickets t', 't.project_id = p.project_id', 'left')
                ->where('t.ticket_id IS NULL')
                ->countAllResults(),
            'avg_tickets_per_project' => 0
        ];

        // Calculate average tickets per project
        $totalTickets = $db->table('tickets')->countAllResults();
        $totalProjects = $stats['total_projects'];

        if ($totalProjects > 0) {
            $stats['avg_tickets_per_project'] = round($totalTickets / $totalProjects, 1);
        }

        return $stats;
    }

    /**
     * Import projects from CSV
     */
    public function importProjectsFromCSV($file, $userId): array
    {
        $importedCount = 0;
        $errorCount = 0;
        $errors = [];

        // Move file to writable directory
        $filePath = WRITEPATH . 'uploads/' . $file->getName();
        $file->move(WRITEPATH . 'uploads/', $file->getName());

        // Read CSV file
        $handle = fopen($filePath, 'r');
        $headers = fgetcsv($handle); // Read headers

        // Required columns
        $requiredColumns = ['project_name', 'project_code'];

        // Validate headers
        foreach ($requiredColumns as $column) {
            if (!in_array($column, $headers)) {
                return [
                    'success' => false,
                    'message' => "Missing required column: {$column}"
                ];
            }
        }

        $rowNumber = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            // Map row data to associative array
            $data = array_combine($headers, $row);

            // Validate required fields
            if (empty($data['project_name']) || empty($data['project_code'])) {
                $errorCount++;
                $errors[] = [
                    'row' => $rowNumber,
                    'error' => 'Project name and code are required'
                ];
                continue;
            }

            // Prepare project data
            $projectData = [
                'project_name' => trim($data['project_name']),
                'project_code' => strtoupper(trim($data['project_code'])),
                'description' => $data['description'] ?? null,
                'is_active' => isset($data['is_active']) ?
                    (strtolower($data['is_active']) === 'true' || $data['is_active'] === '1') : true,
                'user_id' => $userId,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Check if project code already exists
            if ($this->projectCodeExists($projectData['project_code'])) {
                $errorCount++;
                $errors[] = [
                    'row' => $rowNumber,
                    'error' => 'Project code already exists'
                ];
                continue;
            }

            // Save project
            try {
                if ($this->insert($projectData)) {
                    $importedCount++;
                } else {
                    $errorCount++;
                    $errors[] = [
                        'row' => $rowNumber,
                        'error' => 'Failed to save project'
                    ];
                }
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = [
                    'row' => $rowNumber,
                    'error' => $e->getMessage()
                ];
            }
        }

        fclose($handle);

        // Clean up - delete temporary file
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        return [
            'success' => true,
            'message' => "Imported {$importedCount} projects successfully" .
                ($errorCount > 0 ? " with {$errorCount} errors" : ""),
            'imported_count' => $importedCount,
            'error_count' => $errorCount,
            'errors' => $errors
        ];
    }

    // Tambahkan di bagian akhir class ProjectModel sebelum tutup }

    /**
     * Bulk import projects from array data
     */
    public function bulkImportProjects(array $projectsData, int $userId): array
    {
        $db = db_connect();
        $importedCount = 0;
        $errorCount = 0;
        $errors = [];

        $db->transStart();

        try {
            foreach ($projectsData as $index => $projectData) {
                $rowNumber = $index + 2; // +2 karena header row + 1-based index

                // Validate required fields
                if (empty($projectData['project_name']) || empty($projectData['project_code'])) {
                    $errorCount++;
                    $errors[] = [
                        'row' => $rowNumber,
                        'error' => 'Project name and code are required'
                    ];
                    continue;
                }

                // Check if project code already exists
                $projectCode = strtoupper(trim($projectData['project_code']));
                if ($this->projectCodeExists($projectCode)) {
                    $errorCount++;
                    $errors[] = [
                        'row' => $rowNumber,
                        'error' => "Project code '{$projectCode}' already exists"
                    ];
                    continue;
                }

                // Prepare data
                $data = [
                    'project_name' => trim($projectData['project_name']),
                    'project_code' => $projectCode,
                    'description' => $projectData['description'] ?? null,
                    'is_active' => isset($projectData['is_active']) ?
                        (filter_var($projectData['is_active'], FILTER_VALIDATE_BOOLEAN) || $projectData['is_active'] === '1') : true,
                    'user_id' => $userId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Insert project
                if ($this->insert($data)) {
                    $importedCount++;
                } else {
                    $errorCount++;
                    $errors[] = [
                        'row' => $rowNumber,
                        'error' => 'Failed to save project'
                    ];
                }
            }

            $db->transComplete();

            if ($db->transStatus()) {
                return [
                    'success' => true,
                    'message' => "Successfully imported {$importedCount} projects" .
                        ($errorCount > 0 ? " with {$errorCount} errors" : ""),
                    'imported_count' => $importedCount,
                    'error_count' => $errorCount,
                    'errors' => $errors
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Transaction failed'
                ];
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Bulk import projects error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create a single project
     */
    public function createProject(array $data, int $userId): array
    {
        try {
            $data['user_id'] = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');

            // Ensure project code is uppercase
            if (isset($data['project_code'])) {
                $data['project_code'] = strtoupper(trim($data['project_code']));
            }

            // Check if project code exists
            if (isset($data['project_code']) && $this->projectCodeExists($data['project_code'])) {
                return [
                    'success' => false,
                    'message' => 'Project code already exists'
                ];
            }

            if ($this->insert($data)) {
                return [
                    'success' => true,
                    'message' => 'Project created successfully',
                    'project_id' => $this->getInsertID()
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to create project'
            ];
        } catch (\Exception $e) {
            log_message('error', 'Create project error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update existing project
     */
    public function updateProject(int $projectId, array $data): array
    {
        try {
            // Ensure project code is uppercase if provided
            if (isset($data['project_code'])) {
                $data['project_code'] = strtoupper(trim($data['project_code']));

                // Check if project code exists (excluding current project)
                if ($this->projectCodeExists($data['project_code'], $projectId)) {
                    return [
                        'success' => false,
                        'message' => 'Project code already exists'
                    ];
                }
            }

            $data['updated_at'] = date('Y-m-d H:i:s');

            if ($this->update($projectId, $data)) {
                return [
                    'success' => true,
                    'message' => 'Project updated successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to update project'
            ];
        } catch (\Exception $e) {
            log_message('error', 'Update project error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get all active projects for bulk assignment (with search)
     */
    public function getProjectsForBulkAssignment(string $search = ''): array
    {
        $db = db_connect();

        $query = $db->table('projects p')
            ->select('p.project_id, p.project_code, p.project_name, p.description, p.is_active')
            ->where('p.is_active', true);

        if (!empty($search)) {
            $query->groupStart()
                ->like('p.project_name', $search)
                ->orLike('p.project_code', $search)
                ->orLike('p.description', $search)
                ->groupEnd();
        }

        $query->orderBy('p.project_name', 'ASC');

        return $query->get()->getResultArray();
    }

    /**
 * Search projects with filters for AJAX table (UPDATED FOR POSTGRESQL)
 */
public function searchProjectsForTable(array $filters = [], int $start = 0, int $length = 10): array
{
    $db = db_connect();

    $builder = $db->table('projects p')
        ->select('p.*, 
            COALESCE((SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id), 0) as total_tickets,
            COALESCE((SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)), 0) as open_tickets,
            COALESCE((SELECT COUNT(*) FROM project_assignments pa WHERE pa.project_id = p.project_id), 0) as assigned_users');

    // Apply search dengan ILIKE untuk PostgreSQL
    if (!empty($filters['search'])) {
        $builder->groupStart()
            ->like('p.project_name', $filters['search'], 'both', null, true)  // Parameter ke-5 = true untuk ILIKE
            ->orLike('p.project_code', $filters['search'], 'both', null, true)
            ->orLike('p.description', $filters['search'], 'both', null, true)
            ->groupEnd();
    }

    // Apply status filter
    if (!empty($filters['status'])) {
        if ($filters['status'] === 'active') {
            $builder->where('p.is_active', true);
        } elseif ($filters['status'] === 'inactive') {
            $builder->where('p.is_active', false);
        }
        // Add other status filters if needed
    }

    // Apply sorting
    if (!empty($filters['sort_by'])) {
        switch ($filters['sort_by']) {
            case 'name':
                $builder->orderBy('p.project_name', $filters['sort_order'] ?? 'asc');
                break;
            case 'id':
                $builder->orderBy('p.project_id', $filters['sort_order'] ?? 'asc');
                break;
            case 'tickets':
                $builder->orderBy('total_tickets', $filters['sort_order'] ?? 'desc');
                break;
            default:
                $builder->orderBy('p.created_at', 'DESC');
        }
    } else {
        $builder->orderBy('p.created_at', 'DESC');
    }

    // Apply pagination
    $builder->limit($length, $start);

    return $builder->get()->getResultArray();
}

/**
 * Count filtered projects for pagination
 */
public function countFilteredProjects(array $filters = []): int
{
    $db = db_connect();

    $builder = $db->table('projects p');

    // Apply search
    if (!empty($filters['search'])) {
        $builder->groupStart()
            ->like('p.project_name', $filters['search'])
            ->orLike('p.project_code', $filters['search'])
            ->orLike('p.description', $filters['search'])
            ->groupEnd();
    }

    // Apply status filter
    if (!empty($filters['status'])) {
        if ($filters['status'] === 'active') {
            $builder->where('p.is_active', true);
        } elseif ($filters['status'] === 'inactive') {
            $builder->where('p.is_active', false);
        }
    }

    return $builder->countAllResults();
}

/**
 * Count all projects
 */
public function countAll(): int
{
    return $this->builder()->countAllResults();
}
}
