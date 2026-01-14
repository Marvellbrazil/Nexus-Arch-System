<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table            = 'tickets';
    protected $primaryKey       = 'ticket_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'subject',
        'description',
        'project_id',
        'department_id',
        'assigned_to',
        'category_id',
        'priority_id',
        'status_id',
        'due_date'
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

    public function getTotalTickets($userId)
    {
        return $this->where('customer_id', $userId)->countAllResults();
    }

    public function getTicketsPerWeek($userId)
    {
        return $this->where('customer_id', $userId)
            ->where('created_at >=', date('Y-m-d', strtotime('-1 week')))
            ->countAllResults();
    }

    public function getTicketCountByStatus($userId, $statusName)
    {
        return $this->builder('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.customer_id', $userId)
            ->where('s.status_name', $statusName)
            ->countAllResults();
    }

    public function getTicketsThisMonth($userId)
    {
        return $this->where('customer_id', $userId)
            ->where('created_at >=', date('Y-m-01'))
            ->countAllResults();
    }

    public function getRecentTickets($userId, $limit = 5)
    {
        return $this->builder('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->where('t.customer_id', $userId)
            ->orderBy('t.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    private function applyTicketFilters($builder, $filters, $userId)
    {
        $builder->where('t.customer_id', $userId);

        // Apply search filter if exists
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('t.ticket_number', $filters['search'])
                ->orLike('t.subject', $filters['search'])
                ->orLike('proj.project_name', $filters['search'])
                ->groupEnd();
        }

        // Apply status filter
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $builder->where('s.status_name', $filters['status']);
        }

        // Apply priority filter
        if (!empty($filters['priority']) && $filters['priority'] !== 'all') {
            $builder->where('p.priority_name', $filters['priority']);
        }

        // Apply project filter
        if (!empty($filters['project']) && $filters['project'] !== 'all') {
            $builder->where('proj.project_id', $filters['project']);
        }
    }

    public function getPaginatedTickets($userId, $filters, $perPage, $offset)
    {
        $builder = $this->builder('tickets t')
            ->select('t.*, p.priority_name, s.status_name, 
                cat.category_name, proj.project_name, 
                d.department_name,
                t.ticket_number as display_id,
                t.ticket_id as id_num,
                t.created_at as timestamp,
                t.subject,
                proj.project_code as project_key')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left');
        
        $this->applyTicketFilters($builder, $filters, $userId);

        // Apply sorting
        $sortBy = $filters['sort'] ?? 'date-desc';
        $sortParts = explode('-', $sortBy);
        $sortColumn = $sortParts[0] ?? 'date';
        $sortDirection = $sortParts[1] ?? 'desc';

        $sortMap = [
            'id' => 't.ticket_number',
            'subject' => 't.subject',
            'project' => 'proj.project_name',
            'priority' => 'p.priority_id',
            'date' => 't.created_at',
            'status' => 's.status_id'
        ];

        $orderColumn = $sortMap[$sortColumn] ?? 't.created_at';
        $builder->orderBy($orderColumn, strtoupper($sortDirection));

        // Apply pagination
        $builder->limit($perPage, $offset);

        return $builder->get()->getResultArray();
    }

    public function countPaginatedTickets($userId, $filters)
    {
        $builder = $this->builder('tickets t')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left');

        $this->applyTicketFilters($builder, $filters, $userId);

        return $builder->countAllResults();
    }

    public function getTicketDetails($ticketId, $userId)
    {
        return $this->builder('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, proj.project_name, d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.ticket_id', $ticketId)
            ->where('t.customer_id', $userId)
            ->get()
            ->getRowArray();
    }

    public function getTicketsByProject($projectId, $userId)
    {
        return $this->builder('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.project_id', $projectId)
            ->where('t.customer_id', $userId)
            ->orderBy('t.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function countTicketsInProject($projectId)
    {
        return $this->where('project_id', $projectId)->countAllResults();
    }

    public function createTicket($data)
    {
        $this->insert($data);
        return $this->insertID();
    }

    public function countTicketsByProject($projectId)
    {
        return $this->where('project_id', $projectId)->countAllResults();
    }
}
