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
}
