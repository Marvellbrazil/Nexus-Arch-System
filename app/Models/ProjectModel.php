<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'project_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'project_code',
        'project_name',
        'description',
        'is_active',
        'user_id'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'project_code' => 'required|min_length[3]|max_length[20]|is_unique[projects.project_code,project_id,{project_id}]',
        'project_name' => 'required|min_length[3]|max_length[100]',
        'user_id' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'project_code' => [
            'required' => 'Project code is required',
            'is_unique' => 'This project code already exists'
        ],
        'project_name' => [
            'required' => 'Project name is required'
        ]
    ];

    // ==================== CUSTOM METHODS ====================

    /**
     * Get all active projects
     */
    public function getActiveProjects(): array
    {
        return $this->where('is_active', true)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get project by ID with user info
     */
    public function getProjectWithUser(int $projectId): ?array
    {
        $project = $this->find($projectId);

        if (!$project) {
            return null;
        }

        // Load user info if user_id exists
        if ($project['user_id']) {
            $db = db_connect();
            $user = $db->table('users')
                ->select('user_id, username, full_name, email')
                ->where('user_id', $project['user_id'])
                ->get()
                ->getRowArray();

            $project['user'] = $user;
        }

        return $project;
    }

    /**
     * Get projects with ticket counts (FIXED VERSION)
     */
    public function getProjectsWithTicketCounts(): array
    {
        $db = db_connect();

        // PERBAIKAN: Gunakan boolean true daripada integer 1 untuk PostgreSQL
        $projects = $db->table('projects p')
            ->select('p.*, 
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id) as total_tickets,
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)) as open_tickets')
            ->where('p.is_active', true)  // PERBAIKAN: gunakan boolean true
            ->orderBy('p.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $projects;
    }

    /**
     * Search projects by name or code
     */
    public function searchProjects(string $keyword, bool $activeOnly = true): array
    {
        $builder = $this->builder();

        if ($activeOnly) {
            $builder->where('is_active', true);  // PERBAIKAN: boolean true
        }

        $builder->groupStart();
        $builder->like('project_name', $keyword);
        $builder->orLike('project_code', $keyword);
        $builder->orLike('description', $keyword);
        $builder->groupEnd();

        $builder->orderBy('created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get project statistics (FIXED VERSION)
     */
    public function getProjectStatistics(): array
    {
        $db = db_connect();

        $stats = $db->query("
            SELECT 
                COUNT(*) as total_projects,
                COUNT(CASE WHEN is_active THEN 1 END) as active_projects,  -- PERBAIKAN: tanpa = true
                COUNT(CASE WHEN NOT is_active THEN 1 END) as inactive_projects,  -- PERBAIKAN: tanpa = false
                COUNT(DISTINCT user_id) as unique_project_managers
            FROM projects
        ")->getRowArray();

        return $stats ?: [
            'total_projects' => 0,
            'active_projects' => 0,
            'inactive_projects' => 0,
            'unique_project_managers' => 0
        ];
    }

    /**
     * Check if project code exists
     */
    public function projectCodeExists(string $projectCode, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where('project_code', $projectCode);

        if ($excludeId) {
            $builder->where('project_id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Change project status (FIXED VERSION)
     */
    public function changeStatus(int $projectId, bool $isActive): bool
    {
        // PERBAIKAN: PostgreSQL butuh boolean, bukan integer
        return $this->update($projectId, ['is_active' => $isActive]);
    }

    /**
     * Get project by code
     */
    public function getProjectByCode(string $projectCode): ?array
    {
        return $this->where('project_code', $projectCode)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get recent projects
     */
    public function getRecentProjects(int $limit = 5): array
    {
        return $this->where('is_active', true)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}