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

    public function getDepartmentStatistics($departmentId)
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
}
