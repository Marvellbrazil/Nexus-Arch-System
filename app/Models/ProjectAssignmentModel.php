<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectAssignmentModel extends Model
{
    protected $table            = 'project_assignments';
    protected $primaryKey       = 'assignment_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'project_id'];

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

    // ==================== METHOD BARU UNTUK PROJECT ASSIGNMENT MANAGEMENT ====================

    /**
     * Get project assignments with user details
     */
    public function getProjectAssignments(): array
    {
        $db = db_connect();
        $assignments = [];

        $results = $db->table('project_assignments pa')
            ->select('pa.project_id, pa.user_id, u.full_name, u.email, r.role_name')
            ->join('users u', 'u.user_id = pa.user_id')
            ->where('u.is_active', true)
            ->orderBy('pa.project_id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($results as $assignment) {
            if (!isset($assignments[$assignment['project_id']])) {
                $assignments[$assignment['project_id']] = [];
            }
            $assignments[$assignment['project_id']][] = [
                'user_id' => $assignment['user_id'],
                'full_name' => $assignment['full_name'],
                'email' => $assignment['email'],
                'role_name' => $assignment['role_name']
            ];
        }

        return $assignments;
    }

    /**
     * Get assignments grouped by project ID (for easy lookup)
     */
    public function getAssignmentsGroupedByProject(): array
    {
        $db = db_connect();
        $assignments = [];

        $results = $db->table('project_assignments pa')
            ->select('pa.project_id, pa.user_id, u.full_name')
            ->join('users u', 'u.user_id = pa.user_id')
            ->where('u.is_active', true)
            ->get()
            ->getResultArray();

        foreach ($results as $assignment) {
            if (!isset($assignments[$assignment['project_id']])) {
                $assignments[$assignment['project_id']] = [];
            }
            $assignments[$assignment['project_id']][] = $assignment['user_id'];
        }

        return $assignments;
    }

    /**
     * Get assigned users for a specific project
     */
    public function getAssignedUsersForProject(int $projectId): array
    {
        $db = db_connect();

        return $db->table('project_assignments pa')
            ->select('u.user_id, u.username, u.full_name, u.email, r.role_name')
            ->join('users u', 'u.user_id = pa.user_id')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('pa.project_id', $projectId)
            ->where('u.is_active', true)
            ->orderBy('u.full_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get projects assigned to a specific user
     */
    public function getProjectsForUser(int $userId): array
    {
        $db = db_connect();

        return $db->table('project_assignments pa')
            ->select('p.project_id, p.project_code, p.project_name, p.description, p.is_active')
            ->join('projects p', 'p.project_id = pa.project_id')
            ->where('pa.user_id', $userId)
            ->where('p.is_active', true)
            ->orderBy('p.project_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Assign users to a project
     */
    public function assignUsersToProject(int $projectId, array $userIds, int $assignedBy): array
    {
        $db = db_connect();

        $db->transStart();

        try {
            // Delete existing assignments for this project
            $db->table('project_assignments')->where('project_id', $projectId)->delete();

            // Insert new assignments
            $assignmentData = [];
            foreach ($userIds as $userId) {
                $assignmentData[] = [
                    'project_id' => $projectId,
                    'user_id' => $userId,
                    'assigned_by' => $assignedBy,
                    'assigned_at' => date('Y-m-d H:i:s')
                ];
            }

            if (!empty($assignmentData)) {
                $db->table('project_assignments')->insertBatch($assignmentData);
            }

            $db->transComplete();

            if ($db->transStatus()) {
                log_message('info', "Project assignments updated: Project ID {$projectId} with " . count($userIds) . " users by user {$assignedBy}");

                return [
                    'success' => true,
                    'message' => 'Project users updated successfully',
                    'assigned_count' => count($userIds)
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to update project assignments'
            ];
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Assign users to project error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Remove user from project
     */
    public function removeUserFromProject(int $projectId, int $userId): array
    {
        $db = db_connect();

        $result = $db->table('project_assignments')
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->delete();

        if ($result) {
            log_message('info', "User {$userId} removed from project {$projectId}");

            return [
                'success' => true,
                'message' => 'User removed from project successfully'
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to remove user from project'
        ];
    }

    /**
     * Bulk assign users to multiple projects
     */
    public function bulkAssignUsers(array $projectIds, array $userIds, int $assignedBy): array
    {
        $db = db_connect();
        $totalAssignments = 0;

        $db->transStart();

        try {
            foreach ($projectIds as $projectId) {
                foreach ($userIds as $userId) {
                    // Check if assignment already exists
                    $exists = $db->table('project_assignments')
                        ->where('project_id', $projectId)
                        ->where('user_id', $userId)
                        ->countAllResults();

                    if (!$exists) {
                        $db->table('project_assignments')->insert([
                            'project_id' => $projectId,
                            'user_id' => $userId,
                            'assigned_by' => $assignedBy,
                            'assigned_at' => date('Y-m-d H:i:s')
                        ]);
                        $totalAssignments++;
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus()) {
                log_message('info', "Bulk assignment completed: {$totalAssignments} assignments made by user {$assignedBy}");

                return [
                    'success' => true,
                    'message' => "Successfully assigned {$totalAssignments} users to selected projects",
                    'total_assignments' => $totalAssignments
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to process bulk assignment'
            ];
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Bulk assign users error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
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
     * Get assignment statistics
     */
    public function getAssignmentStatistics(): array
    {
        $db = db_connect();

        $totalAssignments = $db->table('project_assignments')->countAllResults();

        $projectsWithAssignments = $db->table('project_assignments')
            ->select('COUNT(DISTINCT project_id) as count')
            ->get()
            ->getRowArray();

        $usersWithAssignments = $db->table('project_assignments')
            ->select('COUNT(DISTINCT user_id) as count')
            ->get()
            ->getRowArray();

        // Average assignments per project
        $avgAssignmentsPerProject = $totalAssignments > 0 && $projectsWithAssignments['count'] > 0
            ? round($totalAssignments / $projectsWithAssignments['count'], 1)
            : 0;

        return [
            'total_assignments' => $totalAssignments,
            'projects_with_assignments' => $projectsWithAssignments['count'] ?? 0,
            'users_with_assignments' => $usersWithAssignments['count'] ?? 0,
            'avg_assignments_per_project' => $avgAssignmentsPerProject
        ];
    }

    /**
     * Get users not assigned to a project
     */
    public function getUnassignedUsers(int $projectId): array
    {
        $db = db_connect();

        $subQuery = $db->table('project_assignments')
            ->select('user_id')
            ->where('project_id', $projectId);

        return $db->table('users u')
            ->select('u.user_id, u.username, u.full_name, u.email, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('u.is_active', true)
            ->whereNotIn('u.user_id', $subQuery)
            ->orderBy('u.full_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get projects without assignments
     */
    public function getProjectsWithoutAssignments(): array
    {
        $db = db_connect();

        $subQuery = $db->table('project_assignments')
            ->select('project_id')
            ->distinct();

        return $db->table('projects p')
            ->select('p.*')
            ->where('p.is_active', true)
            ->whereNotIn('p.project_id', $subQuery)
            ->orderBy('p.project_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get user assignment count
     */
    public function getUserAssignmentCount(int $userId): int
    {
        return $this->where('user_id', $userId)
            ->countAllResults();
    }

    /**
     * Get project assignment count
     */
    public function getProjectAssignmentCount(int $projectId): int
    {
        return $this->where('project_id', $projectId)
            ->countAllResults();
    }

    /**
     * Export assignments to CSV
     */
    public function exportAssignments(): array
    {
        $db = db_connect();

        return $db->table('project_assignments pa')
            ->select('pa.assignment_id, pa.assigned_at, 
                     p.project_id, p.project_code, p.project_name,
                     u.user_id, u.username, u.full_name, u.email,
                     a.full_name as assigned_by_name')
            ->join('projects p', 'p.project_id = pa.project_id')
            ->join('users u', 'u.user_id = pa.user_id')
            ->join('users a', 'a.user_id = pa.assigned_by', 'left')
            ->orderBy('pa.assigned_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Search assignments by project or user
     */
    public function searchAssignments(string $keyword): array
    {
        $db = db_connect();

        return $db->table('project_assignments pa')
            ->select('pa.*, p.project_name, u.full_name as user_full_name')
            ->join('projects p', 'p.project_id = pa.project_id')
            ->join('users u', 'u.user_id = pa.user_id')
            ->groupStart()
            ->like('p.project_name', $keyword)
            ->orLike('u.full_name', $keyword)
            ->orLike('u.email', $keyword)
            ->groupEnd()
            ->orderBy('pa.assigned_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get recent assignments
     */
    public function getRecentAssignments(int $limit = 10): array
    {
        $db = db_connect();

        return $db->table('project_assignments pa')
            ->select('pa.*, p.project_name, u.full_name as user_full_name, 
                     a.full_name as assigned_by_name')
            ->join('projects p', 'p.project_id = pa.project_id')
            ->join('users u', 'u.user_id = pa.user_id')
            ->join('users a', 'a.user_id = pa.assigned_by', 'left')
            ->orderBy('pa.assigned_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Clear all assignments for a project
     */
    public function clearProjectAssignments(int $projectId): array
    {
        $db = db_connect();

        $result = $db->table('project_assignments')
            ->where('project_id', $projectId)
            ->delete();

        if ($result) {
            log_message('info', "All assignments cleared for project {$projectId}");

            return [
                'success' => true,
                'message' => 'All project assignments cleared successfully',
                'cleared_count' => $result
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to clear project assignments'
        ];
    }

    /**
     * Get users with most project assignments
     */
    public function getTopAssignedUsers(int $limit = 10): array
    {
        $db = db_connect();

        return $db->table('project_assignments pa')
            ->select('u.user_id, u.username, u.full_name, u.email, 
                     COUNT(pa.assignment_id) as assignment_count')
            ->join('users u', 'u.user_id = pa.user_id')
            ->groupBy('u.user_id, u.username, u.full_name, u.email')
            ->orderBy('assignment_count', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }



    /**
     * Bulk assign users to multiple projects with validation
     */
    public function bulkAssignUsersToProjects(array $projectIds, array $userIds, int $assignedBy)
    {
        $db = db_connect();
        $totalAssignments = 0;
        $errors = [];

        $db->transStart();

        try {
            foreach ($projectIds as $projectId) {
                // Validate project exists and is active
                $projectExists = $db->table('projects')
                    ->where('project_id', $projectId)
                    ->where('is_active', true)
                    ->countAllResults() > 0;

                if (!$projectExists) {
                    $errors[] = "Project ID {$projectId} not found or inactive";
                    continue;
                }

                foreach ($userIds as $userId) {
                    // Validate user exists and is active
                    $userExists = $db->table('users')
                        ->where('user_id', $userId)
                        ->where('is_active', true)
                        ->countAllResults() > 0;

                    if (!$userExists) {
                        $errors[] = "User ID {$userId} not found or inactive for project {$projectId}";
                        continue;
                    }

                    // Check if assignment already exists
                    $exists = $db->table('project_assignments')
                        ->where('project_id', $projectId)
                        ->where('user_id', $userId)
                        ->countAllResults();

                    if (!$exists) {
                        $db->table('project_assignments')->insert([
                            'project_id' => $projectId,
                            'user_id' => $userId,
                            'assigned_by' => $assignedBy,
                            'assigned_at' => date('Y-m-d H:i:s'),
                        ]);
                        $totalAssignments++;
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus()) {
                log_message('info', "Bulk assignment completed: {$totalAssignments} assignments made by user {$assignedBy}");

                $result = [
                    'success' => true,
                    'message' => "Successfully assigned {$totalAssignments} users to selected projects",
                    'total_assignments' => $totalAssignments,
                    'assigned_by' => $assignedBy
                ];

                if (!empty($errors)) {
                    $result['warning'] = 'Some assignments were skipped due to errors';
                    $result['errors'] = $errors;
                }

                return $result;
            }

            // return [
            //     'success' => false,
            //     'message' => 'Transaction failed'
            // ];
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Bulk assign users to projects error: ' . $e->getMessage());

            return [    
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
    }
}
