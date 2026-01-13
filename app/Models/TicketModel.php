<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'ticket_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'ticket_number',
        'subject',
        'description',
        'customer_id',
        'project_id',
        'department_id',
        'assigned_to',
        'category_id',
        'priority_id',
        'status_id',
        'due_date',
        'first_response_at',
        'resolved_at',
        'closed_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'ticket_number' => 'required|max_length[20]|is_unique[tickets.ticket_number,ticket_id,{ticket_id}]',
        'subject' => 'required|max_length[255]',
        'description' => 'required',
        'customer_id' => 'required|integer',
        'project_id' => 'required|integer',
        'category_id' => 'required|integer',
        'priority_id' => 'required|integer',
        'status_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'ticket_number' => [
            'required' => 'Ticket number is required',
            'is_unique' => 'Ticket number already exists'
        ],
        'subject' => [
            'required' => 'Subject is required'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['generateTicketNumber'];
    protected $beforeUpdate = ['updateTimestamps'];

    // ==================== CALLBACK METHODS ====================

    protected function generateTicketNumber(array $data): array
    {
        if (!isset($data['data']['ticket_number']) || empty($data['data']['ticket_number'])) {
            $data['data']['ticket_number'] = $this->generateUniqueTicketNumber();
        }
        return $data;
    }

    protected function updateTimestamps(array $data): array
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    // ==================== CUSTOM METHODS ====================

    /**
     * Generate unique ticket number
     */
    private function generateUniqueTicketNumber(): string
    {
        $prefix = 'TICKET-';
        $date = date('Ymd');

        do {
            $random = strtoupper(substr(md5(uniqid()), 0, 6));
            $ticketNumber = $prefix . $date . '-' . $random;
        } while ($this->ticketNumberExists($ticketNumber));

        return $ticketNumber;
    }

    /**
     * Check if ticket number exists
     */
    public function ticketNumberExists(string $ticketNumber, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where('ticket_number', $ticketNumber);

        if ($excludeId) {
            $builder->where('ticket_id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Get tickets with all related data
     */
    public function getTicketsWithDetails(array $filters = [], int $limit = null, int $offset = 0): array
    {
        $db = db_connect();

        $builder = $db->table('tickets t')
            ->select('t.*, 
                c.first_name as customer_first_name, c.last_name as customer_last_name, c.email as customer_email,
                p.project_name, p.project_code,
                d.department_name,
                u.username as assigned_username, u.full_name as assigned_full_name,
                cat.category_name,
                pr.priority_name, pr.color as priority_color,
                s.status_name, s.color as status_color')
            ->join('users c', 'c.user_id = t.customer_id', 'left')
            ->join('projects p', 'p.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->join('users u', 'u.user_id = t.assigned_to', 'left')
            ->join('categories cat', 'cat.category_id = t.category_id', 'left')
            ->join('priorities pr', 'pr.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left');

        // Apply filters
        if (!empty($filters)) {
            $this->applyFilters($builder, $filters);
        }

        $builder->orderBy('t.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Apply filters to query builder
     */
    private function applyFilters(object $builder, array $filters): void
    {
        foreach ($filters as $key => $value) {
            if ($value !== null && $value !== '') {
                switch ($key) {
                    case 'project_id':
                    case 'department_id':
                    case 'assigned_to':
                    case 'category_id':
                    case 'priority_id':
                    case 'status_id':
                        $builder->where("t.$key", $value);
                        break;

                    case 'customer_id':
                        $builder->where('t.customer_id', $value);
                        break;

                    case 'search':
                        $builder->groupStart();
                        $builder->like('t.ticket_number', $value);
                        $builder->orLike('t.subject', $value);
                        $builder->orLike('t.description', $value);
                        $builder->groupEnd();
                        break;

                    case 'date_from':
                        $builder->where('t.created_at >=', $value . ' 00:00:00');
                        break;

                    case 'date_to':
                        $builder->where('t.created_at <=', $value . ' 23:59:59');
                        break;

                    case 'status_ids':
                        if (is_array($value) && !empty($value)) {
                            $builder->whereIn('t.status_id', $value);
                        }
                        break;
                }
            }
        }
    }

    /**
     * Get ticket statistics
     */
    public function getTicketStatistics(): array
    {
        $db = db_connect();

        $stats = $db->query("
            SELECT 
                COUNT(*) as total_tickets,
                SUM(CASE WHEN status_id IN (1,2) THEN 1 ELSE 0 END) as open_tickets,
                SUM(CASE WHEN status_id = 3 THEN 1 ELSE 0 END) as pending_tickets,
                SUM(CASE WHEN status_id = 4 THEN 1 ELSE 0 END) as resolved_tickets,
                SUM(CASE WHEN status_id = 5 THEN 1 ELSE 0 END) as closed_tickets,
                COUNT(DISTINCT customer_id) as unique_customers,
                COUNT(DISTINCT project_id) as unique_projects,
                AVG(EXTRACT(EPOCH FROM (COALESCE(resolved_at, NOW()) - created_at))/3600) as avg_resolution_hours
            FROM tickets
        ")->getRowArray();

        return $stats ?: [
            'total_tickets' => 0,
            'open_tickets' => 0,
            'pending_tickets' => 0,
            'resolved_tickets' => 0,
            'closed_tickets' => 0,
            'unique_customers' => 0,
            'unique_projects' => 0,
            'avg_resolution_hours' => 0
        ];
    }

    /**
     * Get tickets by status
     */
    public function getTicketsByStatus(int $statusId): array
    {
        return $this->where('status_id', $statusId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get tickets assigned to user
     */
    public function getTicketsAssignedToUser(int $userId, array $statusIds = []): array
    {
        $builder = $this->where('assigned_to', $userId);

        if (!empty($statusIds)) {
            $builder->whereIn('status_id', $statusIds);
        }

        return $builder->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get tickets by customer
     */
    public function getTicketsByCustomer(int $customerId): array
    {
        return $this->where('customer_id', $customerId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get tickets by project
     */
    public function getTicketsByProject(int $projectId, array $statusIds = []): array
    {
        $builder = $this->where('project_id', $projectId);

        if (!empty($statusIds)) {
            $builder->whereIn('status_id', $statusIds);
        }

        return $builder->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Update ticket status
     */
    public function updateTicketStatus(int $ticketId, int $statusId, ?int $userId = null): bool
    {
        $data = ['status_id' => $statusId];

        // Set resolved_at if status is resolved
        if ($statusId == 4) { // Assuming 4 = Resolved
            $data['resolved_at'] = date('Y-m-d H:i:s');
            $data['assigned_to'] = $userId;
        }

        // Set closed_at if status is closed
        if ($statusId == 5) { // Assuming 5 = Closed
            $data['closed_at'] = date('Y-m-d H:i:s');
        }

        return $this->update($ticketId, $data);
    }

    /**
     * Assign ticket to user
     */
    public function assignTicket(int $ticketId, int $userId): bool
    {
        return $this->update($ticketId, [
            'assigned_to' => $userId,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get overdue tickets
     */
    public function getOverdueTickets(): array
    {
        $today = date('Y-m-d H:i:s');

        return $this->where('due_date <', $today)
            ->whereIn('status_id', [1, 2, 3]) // Open, In Progress, Pending
            ->orderBy('due_date', 'ASC')
            ->findAll();
    }

    /**
     * Get tickets created in date range
     */
    public function getTicketsByDateRange(string $startDate, string $endDate): array
    {
        return $this->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get ticket counts by status
     */
    public function getTicketCountsByStatus(): array
    {
        $db = db_connect();

        return $db->table('tickets t')
            ->select('s.status_id, s.status_name, s.color, COUNT(t.ticket_id) as count')
            ->join('statuses s', 's.status_id = t.status_id')
            ->groupBy('s.status_id, s.status_name, s.color')
            ->orderBy('s.status_id')
            ->get()
            ->getResultArray();
    }
}