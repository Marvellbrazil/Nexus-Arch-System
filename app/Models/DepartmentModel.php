<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table            = 'departments';
    protected $primaryKey       = 'department_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['department_name', 'description'];

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

    public function getDepartments()
    {
        return $this->findAll();
    }

public function getDepartmentByID(int $departmentId): ?array
    {
        return $this->find($departmentId);
    }

    public function getAllDepartments()
    {
        return $this->get()->getResultArray();
    }

    public function findByName($departmentName)
    {
        return $this->where('department_name', $departmentName)->first();
    }

    public function getDepartmentTicketsStatistics($departmentId)
    {
        $totalTickets = $this->db->table('tickets')
            ->where('department_id', $departmentId)
            ->countAllResults();

        $openTickets = $this->db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.department_id', $departmentId)
            ->where('s.status_name', 'Open')
            ->countAllResults();

        return [
            'total_tickets' => $totalTickets,
            'open_tickets' => $openTickets
        ];
    }

    public function getDepartmentTickets($departmentId, $limit = 10)
    {
        return $this->db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, u.full_name as customer_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->where('t.department_id', $departmentId)
            ->orderBy('t.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getAssignedTicketsForUser($departmentId, $userId, $limit = 10)
    {
        return $this->db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, u.full_name as customer_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->where('t.department_id', $departmentId)
            ->where('t.assigned_to', $userId)
            ->orderBy('t.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    // ==================== METHOD BARU UNTUK DEPARTMENT MANAGEMENT ====================

    /**
     * Get departments with user count
     */
    public function getDepartmentsWithUserCount(): array
    {
        $db = db_connect();

        return $db->table('departments d')
            ->select('d.*, COUNT(u.user_id) as user_count')
            ->join('users u', 'u.department_id = d.department_id', 'left')
            ->groupBy('d.department_id')
            ->orderBy('d.department_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get department by ID with user count
     */
    public function getDepartmentWithDetails(int $departmentId): ?array
    {
        $db = db_connect();

        $department = $db->table('departments d')
            ->select('d.*, COUNT(u.user_id) as user_count, 
                GROUP_CONCAT(DISTINCT u.full_name ORDER BY u.full_name SEPARATOR ", ") as user_names')
            ->join('users u', 'u.department_id = d.department_id', 'left')
            ->where('d.department_id', $departmentId)
            ->groupBy('d.department_id')
            ->get()
            ->getRowArray();

        if (!$department) {
            return null;
        }

        // Get department statistics
        $department['ticket_count'] = $this->getDepartmentTicketCount($departmentId);
        $department['active_users'] = $this->getDepartmentActiveUsersCount($departmentId);

        return $department;
    }

    /**
     * Get department statistics
     */
    public function getDepartmentStatistics(): array
    {
        $db = db_connect();

        $totalDepartments = $db->table('departments')->countAll();
        
        $departmentsWithUsers = $db->table('departments d')
            ->select('COUNT(DISTINCT d.department_id) as count')
            ->join('users u', 'u.department_id = d.department_id')
            ->where('u.is_active', true)
            ->get()
            ->getRowArray();

        $totalUsersInDepartments = $db->table('users')
            ->where('department_id IS NOT NULL')
            ->where('is_active', true)
            ->countAllResults();

        return [
            'total_departments' => $totalDepartments,
            'active_departments' => $departmentsWithUsers['count'] ?? 0,
            'total_users_in_departments' => $totalUsersInDepartments
        ];
    }

    /**
     * Get ticket count for a department
     */
    public function getDepartmentTicketCount(int $departmentId): int
    {
        $db = db_connect();

        return $db->table('tickets')
            ->where('department_id', $departmentId)
            ->countAllResults();
    }

    /**
     * Get active users count for a department
     */
    public function getDepartmentActiveUsersCount(int $departmentId): int
    {
        $db = db_connect();

        return $db->table('users')
            ->where('department_id', $departmentId)
            ->where('is_active', true)
            ->countAllResults();
    }

    /**
     * Get users in a department
     */
    public function getDepartmentUsers(int $departmentId): array
    {
        $db = db_connect();

        return $db->table('users u')
            ->select('u.user_id, u.username, u.full_name, u.email, u.is_active, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('u.department_id', $departmentId)
            ->where('u.is_active', true)
            ->orderBy('u.full_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Add new department
     */
    public function addDepartment(array $departmentData): array
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'department_name' => 'required|min_length[2]|max_length[100]|is_unique[departments.department_name]',
            'description' => 'permit_empty|max_length[500]'
        ]);

        if (!$validation->run($departmentData)) {
            return [
                'success' => false,
                'errors' => $validation->getErrors()
            ];
        }

        $data = [
            'department_name' => $departmentData['department_name'],
            'description' => $departmentData['description'] ?? null
        ];

        if ($this->insert($data)) {
            $departmentId = $this->getInsertID();
            
            return [
                'success' => true,
                'message' => 'Department added successfully',
                'department_id' => $departmentId
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to add department'
        ];
    }

    /**
     * Update department
     */
    public function updateDepartment(int $departmentId, array $departmentData): array
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'department_name' => "required|min_length[2]|max_length[100]|is_unique[departments.department_name,department_id,{$departmentId}]",
            'description' => 'permit_empty|max_length[500]'
        ]);

        if (!$validation->run($departmentData)) {
            return [
                'success' => false,
                'errors' => $validation->getErrors()
            ];
        }

        $data = [
            'department_name' => $departmentData['department_name'],
            'description' => $departmentData['description'] ?? null
        ];

        if ($this->update($departmentId, $data)) {
            return [
                'success' => true,
                'message' => 'Department updated successfully'
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to update department'
        ];
    }

    /**
     * Delete department
     */
    public function deleteDepartment(int $departmentId): array
    {
        // Check if department has users
        $userCount = $this->getDepartmentActiveUsersCount($departmentId);

        if ($userCount > 0) {
            return [
                'success' => false,
                'message' => "Cannot delete department with {$userCount} active users. Please reassign users first."
            ];
        }

        // Check if department has tickets
        $ticketCount = $this->getDepartmentTicketCount($departmentId);

        if ($ticketCount > 0) {
            return [
                'success' => false,
                'message' => "Cannot delete department with {$ticketCount} tickets. Please reassign tickets first."
            ];
        }

        if ($this->delete($departmentId)) {
            return [
                'success' => true,
                'message' => 'Department deleted successfully'
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to delete department'
        ];
    }

    /**
     * Bulk assign users to department
     */
    public function bulkAssignUsers(array $userIds, int $departmentId): array
    {
        $db = db_connect();
        $successCount = 0;
        $failedCount = 0;

        $db->transStart();

        foreach ($userIds as $userId) {
            $result = $db->table('users')
                ->where('user_id', $userId)
                ->update(['department_id' => $departmentId]);

            if ($result) {
                $successCount++;
            } else {
                $failedCount++;
            }
        }

        $db->transComplete();

        if ($db->transStatus()) {
            return [
                'success' => true,
                'message' => "Assigned {$successCount} users to department",
                'success_count' => $successCount,
                'failed_count' => $failedCount
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to assign users to department'
        ];
    }

    /**
     * Remove users from department
     */
    public function removeUsersFromDepartment(array $userIds): array
    {
        $db = db_connect();
        $successCount = 0;

        $db->transStart();

        foreach ($userIds as $userId) {
            $result = $db->table('users')
                ->where('user_id', $userId)
                ->update(['department_id' => null]);

            if ($result) {
                $successCount++;
            }
        }

        $db->transComplete();

        if ($db->transStatus()) {
            return [
                'success' => true,
                'message' => "Removed {$successCount} users from their departments",
                'success_count' => $successCount
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to remove users from departments'
        ];
    }

    /**
     * Export departments to CSV
     */
    public function exportDepartments(): array
    {
        $db = db_connect();

        return $db->table('departments d')
            ->select('d.*, 
                COUNT(u.user_id) as user_count,
                COUNT(t.ticket_id) as ticket_count')
            ->join('users u', 'u.department_id = d.department_id', 'left')
            ->join('tickets t', 't.department_id = d.department_id', 'left')
            ->groupBy('d.department_id')
            ->orderBy('d.department_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Search departments
     */
    public function searchDepartments(string $keyword): array
    {
        return $this->builder()
            ->select('department_id, department_name, description')
            ->groupStart()
            ->like('department_name', $keyword)
            ->orLike('description', $keyword)
            ->groupEnd()
            ->orderBy('department_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get department dropdown options
     */
    public function getDepartmentDropdown(): array
    {
        $departments = $this->select('department_id, department_name')
            ->orderBy('department_name', 'ASC')
            ->findAll();

        $options = ['' => '-- Select Department --'];
        
        foreach ($departments as $dept) {
            $options[$dept['department_id']] = $dept['department_name'];
        }

        return $options;
    }

    /**
     * Get empty departments (no users assigned)
     */
    public function getEmptyDepartments(): array
    {
        $db = db_connect();

        return $db->table('departments d')
            ->select('d.*')
            ->join('users u', 'u.department_id = d.department_id', 'left')
            ->groupBy('d.department_id')
            ->having('COUNT(u.user_id)', 0)
            ->get()
            ->getResultArray();
    }

    /**
     * Get most active departments (by ticket count)
     */
    public function getMostActiveDepartments(int $limit = 5): array
    {
        $db = db_connect();

        return $db->table('departments d')
            ->select('d.*, COUNT(t.ticket_id) as ticket_count')
            ->join('tickets t', 't.department_id = d.department_id', 'left')
            ->groupBy('d.department_id')
            ->orderBy('ticket_count', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}