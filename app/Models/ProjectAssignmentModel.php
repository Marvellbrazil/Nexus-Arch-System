<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectAssignmentModel extends Model
{
    protected $table = 'project_assignments';
    protected $primaryKey = 'assignment_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'user_id',
        'project_id',
        'assigned_at'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'user_id' => 'required|integer',
        'project_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be an integer'
        ],
        'project_id' => [
            'required' => 'Project ID is required',
            'integer' => 'Project ID must be an integer'
        ]
    ];

    // ==================== CUSTOM METHODS ====================

    /**
     * Assign users to project
     */
    public function assignUsersToProject(int $projectId, array $userIds): bool
    {
        // Check for existing assignments
        $existingAssignments = $this->where('project_id', $projectId)->findAll();
        $existingUserIds = array_column($existingAssignments, 'user_id');

        // Users to add
        $usersToAdd = array_diff($userIds, $existingUserIds);

        // Users to remove
        $usersToRemove = array_diff($existingUserIds, $userIds);

        // Start transaction
        $db = db_connect();
        $db->transStart();

        try {
            // Remove unassigned users
            if (!empty($usersToRemove)) {
                $this->where('project_id', $projectId)
                    ->whereIn('user_id', $usersToRemove)
                    ->delete();
            }

            // Add new assignments
            if (!empty($usersToAdd)) {
                $assignmentData = [];
                foreach ($usersToAdd as $userId) {
                    $assignmentData[] = [
                        'project_id' => $projectId,
                        'user_id' => $userId,
                        'assigned_at' => date('Y-m-d H:i:s')
                    ];
                }

                if (!empty($assignmentData)) {
                    $this->insertBatch($assignmentData);
                }
            }

            $db->transCommit();
            return true;

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Failed to assign users to project: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get project assignments with user details
     */
    public function getProjectAssignments(int $projectId): array
    {
        $db = db_connect();

        $assignments = $db->table('project_assignments pa')
            ->select('pa.*, u.username, u.full_name, u.email, r.role_name')
            ->join('users u', 'u.user_id = pa.user_id')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('pa.project_id', $projectId)
            ->orderBy('u.full_name', 'ASC')
            ->get()
            ->getResultArray();

        return $assignments;
    }

    /**
     * Get user's assigned projects
     */
    public function getUserProjects(int $userId): array
    {
        $db = db_connect();

        $projects = $db->table('project_assignments pa')
            ->select('p.*')
            ->join('projects p', 'p.project_id = pa.project_id')
            ->where('pa.user_id', $userId)
            ->where('p.is_active', true)
            ->orderBy('p.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $projects;
    }

    /**
     * Check if user is assigned to project
     */
    public function isUserAssignedToProject(int $userId, int $projectId): bool
    {
        return $this->where('user_id', $userId)
            ->where('project_id', $projectId)
            ->countAllResults() > 0;
    }

    /**
     * Get assigned user IDs for project
     */
    public function getAssignedUserIds(int $projectId): array
    {
        $assignments = $this->where('project_id', $projectId)->findAll();
        return array_column($assignments, 'user_id');
    }

    /**
     * Remove user from project
     */
    public function removeUserFromProject(int $userId, int $projectId): bool
    {
        return $this->where('user_id', $userId)
            ->where('project_id', $projectId)
            ->delete() !== false;
    }

    /**
     * Get assignment statistics
     */
    public function getAssignmentStatistics(): array
    {
        $db = db_connect();

        $stats = $db->query("
            SELECT 
                COUNT(*) as total_assignments,
                COUNT(DISTINCT user_id) as unique_users_assigned,
                COUNT(DISTINCT project_id) as unique_projects_with_assignments
            FROM project_assignments
        ")->getRowArray();

        return $stats ?: [
            'total_assignments' => 0,
            'unique_users_assigned' => 0,
            'unique_projects_with_assignments' => 0
        ];
    }

    /**
     * Bulk assign users to projects
     */
    public function bulkAssign(array $assignments): bool
    {
        if (empty($assignments)) {
            return true;
        }

        return $this->insertBatch($assignments) !== false;
    }
}