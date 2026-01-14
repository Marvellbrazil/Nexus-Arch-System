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

    /**
     * Get ticket statistics for dashboard
     */
    public function getTicketStatistics(): array
    {
        $db = db_connect();

        // Total tickets
        $totalTickets = $db->table('tickets')->countAll();
        
        // Open tickets (status_id 1 = Open, 2 = In Progress)
        $openTickets = $db->table('tickets')
            ->where('status_id', 1)
            ->orWhere('status_id', 2)
            ->countAllResults();

        // Ticket trend
        $trend = $this->getTicketTrend();

        return [
            'total_tickets' => $totalTickets,
            'open_tickets' => $openTickets,
            'ticket_trend' => $trend['trend'] ?? '0%'
        ];
    }

    /**
     * Get ticket trend data
     */
    public function getTicketTrend(): array
    {
        $db = db_connect();
        
        $lastWeek = date('Y-m-d', strtotime('-7 days'));
        
        $currentWeekTickets = $db->table('tickets')
            ->where('created_at >=', $lastWeek)
            ->countAllResults();

        $previousWeekTickets = $db->table('tickets')
            ->where('created_at >=', date('Y-m-d', strtotime('-14 days')))
            ->where('created_at <', $lastWeek)
            ->countAllResults();

        return [
            'current' => $currentWeekTickets,
            'previous' => $previousWeekTickets,
            'trend' => $this->calculateTrend($currentWeekTickets, $previousWeekTickets)
        ];
    }

    /**
     * Get ticket status breakdown for dashboard
     */
    public function getTicketStatusData(): array
    {
        $db = db_connect();

        // Check if color column exists
        $statusTableInfo = $db->query("
            SELECT column_name 
            FROM information_schema.columns 
            WHERE table_name = 'statuses' 
            AND table_schema = DATABASE()
        ")->getResultArray();

        $statusColumns = array_column($statusTableInfo, 'column_name');
        $hasColorColumn = in_array('color', $statusColumns);

        // Get ticket status breakdown
        if ($hasColorColumn) {
            $ticketStatus = $db->table('tickets t')
                ->select('s.status_id, s.status_name, s.color, COUNT(t.ticket_id) as count')
                ->join('statuses s', 's.status_id = t.status_id')
                ->groupBy('s.status_id, s.status_name, s.color')
                ->orderBy('s.status_id')
                ->get()
                ->getResultArray();
        } else {
            $ticketStatus = $db->table('tickets t')
                ->select('s.status_id, s.status_name, COUNT(t.ticket_id) as count')
                ->join('statuses s', 's.status_id = t.status_id')
                ->groupBy('s.status_id, s.status_name')
                ->orderBy('s.status_id')
                ->get()
                ->getResultArray();

            // Add default colors
            $defaultColors = [
                1 => '#635A91', // Open
                2 => '#AFB9D4', // In Progress
                3 => '#EDE1C7', // Need Info
                4 => '#89A6CE', // Resolved
                5 => '#BDB7D9'  // Closed
            ];

            foreach ($ticketStatus as &$status) {
                $statusId = $status['status_id'];
                $status['color'] = $defaultColors[$statusId] ?? '#6B7280';
            }
        }

        // Calculate percentages
        $totalTicketsForPercentage = array_sum(array_column($ticketStatus, 'count'));
        foreach ($ticketStatus as &$status) {
            $status['percentage'] = $totalTicketsForPercentage > 0
                ? round(($status['count'] / $totalTicketsForPercentage) * 100, 1)
                : 0;
        }

        return [
            'data' => $ticketStatus,
            'total' => $totalTicketsForPercentage
        ];
    }

    /**
     * Get recent activities from tickets
     */
    public function getRecentActivities(int $limit = 5): array
    {
        $db = db_connect();

        $recentTickets = $db->table('tickets t')
            ->select('t.ticket_id, t.ticket_number, t.title, t.created_at, 
                     u.username, u.full_name, r.role_name,
                     s.status_name, p.project_name')
            ->join('users u', 'u.user_id = t.created_by')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('projects p', 'p.project_id = t.project_id', 'left')
            ->orderBy('t.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return $recentTickets;
    }

    /**
     * Get high priority tickets count
     */
    public function getHighPriorityTicketsCount(): int
    {
        return $this->where('priority_id', 1)
            ->whereIn('status_id', [1, 2])
            ->countAllResults();
    }

    /**
     * Get SLA violations count
     */
    public function getSLAViolationsCount(int $slaHours = 72): int
    {
        $db = db_connect();
        $slaTime = date('Y-m-d H:i:s', strtotime("-{$slaHours} hours"));

        return $db->table('tickets')
            ->where('created_at <=', $slaTime)
            ->whereIn('status_id', [1, 2])
            ->countAllResults();
    }

    /**
     * Calculate trend percentage
     */
    private function calculateTrend($current, $previous): string
    {
        if ($previous == 0) {
            return $current > 0 ? '+100%' : '0%';
        }

        $trend = (($current - $previous) / $previous) * 100;
        return ($trend >= 0 ? '+' : '') . round($trend, 1) . '%';
    }

    /**
     * Get ticket statistics for dashboard
     */
    public function getDashboardStatistics(): array
    {
        $db = db_connect();

        // Total tickets
        $totalTickets = $db->table('tickets')->countAll();
        
        // Open tickets (status_id 1 = Open, 2 = In Progress)
        $openTickets = $db->table('tickets')
            ->where('status_id', 1)
            ->orWhere('status_id', 2)
            ->countAllResults();

        // Ticket trend
        $ticketTrend = $this->getTicketTrend();

        // Ticket status data
        $ticketStatusData = $this->getTicketStatusData();

        // Recent activities
        $recentActivities = $this->getRecentActivities(5);

        return [
            'total_tickets' => $totalTickets,
            'open_tickets' => $openTickets,
            'ticket_trend' => $ticketTrend['trend'] ?? '0%',
            'ticket_status_data' => $ticketStatusData,
            'recent_activities_raw' => $recentActivities
        ];
    }

    /**
     * Format recent activities for display
     */
    public function formatRecentActivities(array $activities, UserModel $userModel): array
    {
        $formatted = [];
        
        foreach ($activities as $ticket) {
            $formatted[] = [
                'time' => $this->formatTimeAgo($ticket['created_at']),
                'user' => $ticket['full_name'] ?: $ticket['username'],
                'role' => $ticket['role_name'] ?? 'Customer',
                'action' => "created ticket #{$ticket['ticket_number']}",
                'description' => $ticket['title'],
                'project' => $ticket['project_name'] ?? null,
                'avatar_color' => $userModel->getAvatarColor($ticket['role_name'] ?? 'customer')
            ];
        }

        return $formatted;
    }

    /**
     * Format time ago (utility method)
     */
    public function formatTimeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60)
            return 'Just now';
        if ($diff < 3600)
            return floor($diff / 60) . ' minutes ago';
        if ($diff < 86400)
            return floor($diff / 3600) . ' hours ago';
        if ($diff < 604800)
            return floor($diff / 86400) . ' days ago';

        return date('M d, Y', $time);
    }
}
