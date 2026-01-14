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
    protected $useTimestamps = true;
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
     * Get project statistics for dashboard
     */
    public function getProjectStatistics(): array
    {
        $db = db_connect();

        // Total active projects
        $totalProjects = $db->table('projects')
            ->where('is_active', true)
            ->countAllResults();

        // Recent projects with ticket counts
        $recentProjects = $this->getRecentProjects(5);

        return [
            'total_projects' => $totalProjects,
            'recent_projects' => $recentProjects
        ];
    }

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
     * Get projects with ticket counts
     */
    public function getProjectsWithTicketCounts(): array
    {
        $db = db_connect();

        return $db->table('projects p')
            ->select('p.*, 
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id) as total_tickets,
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)) as open_tickets')
            ->orderBy('p.created_at', 'DESC')
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
}
