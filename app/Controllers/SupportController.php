<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use App\Models\UserModel;
use App\Models\RoleModel;
use Exception;

class SupportController extends BaseController
{
    public function __construct()
    {
        // Check if user is logged in
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        // Check if user has support role
        $userRole = session()->get('role');
        if (!in_array($userRole, ['Support', 'Admin'])) {
            throw PageNotFoundException::forPageNotFound();
        }
    }

    protected function loadCommonData()
    {
        $userId = session()->get('user_id');
        $db = db_connect();

        // Get user details
        $user = [];
        if ($userId) {
            $user = $db->table('users u')
                ->select('u.*, r.role_name, d.department_name')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->join('departments d', 'd.department_id = u.department_id', 'left')
                ->where('u.user_id', $userId)
                ->get()
                ->getRowArray();
        }

        // Get notification count
        $notificationCount = 0;
        if ($db->tableExists('notifications')) {
            try {
                $fields = $db->getFieldNames('notifications');
                $readColumn = 'is_read';
                if (in_array('read_status', $fields)) {
                    $readColumn = 'read_status';
                }

                $notificationCount = $db->table('notifications')
                    ->where('user_id', $userId)
                    ->where($readColumn, 0)
                    ->countAllResults();
            } catch (Exception $e) {
                $notificationCount = 5; // Default fallback
            }
        }

        return [
            'title' => 'Support Dashboard',
            'user' => $user,
            'notification_count' => $notificationCount,
            'current_url' => current_url()
        ];
    }

    public function dashboard()
    {
        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

        // Load models
        $userModel = new UserModel();
        $roleModel = new RoleModel();

        // Get user details dengan departments
        $data['user_details'] = $db->table('users u')
            ->select('u.*, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('u.user_id', $userId)
            ->get()
            ->getRowArray();

        // Hitung active duration (logika sederhana)
        $activeDuration = "8h 24m";

        // Get user departments
        if ($data['user_details'] && $data['user_details']['department_id']) {
            $department = $db->table('departments')
                ->select('department_name')
                ->where('department_id', $data['user_details']['department_id'])
                ->get()
                ->getRowArray();

            if ($department) {
                $data['user_details']['department_name'] = $department['department_name'];
            }
        }

        // ==================== STATISTIK DINAMIS ====================

        // 1. Tickets in Progress
        $ticketsInProgress = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', $userId)
            ->whereIn('s.status_name', ['In Progress', 'Processing'])
            ->countAllResults();

        // Tickets need attention (priority tinggi)
        $needsAttention = $db->table('tickets t')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', $userId)
            ->whereIn('p.priority_name', ['Urgent', 'High'])
            ->whereIn('s.status_name', ['Open', 'In Progress'])
            ->countAllResults();

        // 2. Waiting Customer Reply
        $waitingCustomerReply = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', $userId)
            ->where('s.status_name', 'Waiting Customer Reply')
            ->countAllResults();

        // 3. Incoming Tickets (belum diassign)
        $incomingTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->countAllResults();

        // New today
        $newToday = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->where('DATE(t.created_at)', date('Y-m-d'))
            ->countAllResults();

        // ==================== TEAM UPDATES ====================

        // Agents online
        $agentsOnline = 0;
        try {
            $agentsOnlineQuery = $db->table('users u')
                ->join('roles r', 'r.role_id = u.role_id')
                ->where('r.role_name', 'Support');

            if ($db->fieldExists('is_active', 'users')) {
                $agentsOnlineQuery->where('u.is_active', true);
            }

            $agentsOnline = $agentsOnlineQuery->countAllResults();
        } catch (Exception $e) {
            $agentsOnline = 8;
        }

        // Agents in meeting
        $agentsInMeeting = 0;
        try {
            if ($db->fieldExists('in_meeting', 'users')) {
                $agentsInMeeting = $db->table('users u')
                    ->join('roles r', 'r.role_id = u.role_id')
                    ->where('r.role_name', 'Support')
                    ->where('u.in_meeting', true)
                    ->countAllResults();
            }
        } catch (Exception $e) {
            $agentsInMeeting = 2;
        }

        // ==================== RECENT TICKETS ====================

        $data['recent_tickets'] = $db->table('tickets t')
            ->select('t.ticket_id, t.ticket_number, t.subject,
                 p.priority_name,
                 s.status_name,
                 c.category_name,
                 u.full_name as customer_name,
                 proj.project_name,
                 t.created_at')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('categories c', 'c.category_id = t.category_id', 'left')
            ->join('users u', 'u.user_id = t.customer_id', 'left')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->orderBy('t.created_at', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();

        // ==================== RECENT NOTIFICATIONS ====================

        $data['recent_notifications'] = [];
        if ($db->tableExists('notifications')) {
            try {
                $data['recent_notifications'] = $db->table('notifications n')
                    ->select('n.*')
                    ->where('n.user_id', $userId)
                    ->where('n.read_status', 0)
                    ->orderBy('n.created_at', 'DESC')
                    ->limit(3)
                    ->get()
                    ->getResultArray();
            } catch (Exception $e) {
                $data['recent_notifications'] = [
                    ['title' => 'New ticket assigned #10425', 'created_at' => date('Y-m-d H:i:s', strtotime('-10 minutes')), 'type' => 'assignment'],
                    ['title' => 'Ticket #10422 needs follow up', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')), 'type' => 'warning'],
                    ['title' => 'Customer replied to #10421', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')), 'type' => 'message']
                ];
            }
        }

        // ==================== PASS DATA KE VIEW ====================
        $data['stats'] = [
            'tickets_in_progress' => $ticketsInProgress,
            'needs_attention' => $needsAttention,
            'waiting_customer_reply' => $waitingCustomerReply,
            'incoming_tickets' => $incomingTickets,
            'new_today' => $newToday,
            'agents_online' => $agentsOnline,
            'agents_in_meeting' => $agentsInMeeting,
            'active_duration' => $activeDuration
        ];

        return view('Support/dashboard', $data);
    }

    public function incomingTickets()
    {
        $data = $this->loadCommonData();

        $db = db_connect();

        // ==================== STATISTIK DINAMIS ====================

        // Total incoming (semua ticket yang belum diassign)
        $totalIncoming = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->countAllResults();

        // Pending review (yang masih Open)
        $pendingReview = $totalIncoming; // atau logika khusus jika ada

        // Forwarded today
        $forwardedToday = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('s.status_name', 'Forwarded')
            ->where('DATE(t.updated_at)', date('Y-m-d'))
            ->countAllResults();

        // High priority (Urgent/High)
        $highPriority = $db->table('tickets t')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->whereIn('p.priority_name', ['Urgent', 'High'])
            ->countAllResults();

        $data['stats'] = [
            'total_incoming' => $totalIncoming,
            'pending_review' => $pendingReview,
            'forwarded_today' => $forwardedToday,
            'high_priority' => $highPriority
        ];

        // ==================== TIKET DINAMIS ====================

        // Get all incoming tickets dengan semua relasi yang diperlukan
        $data['tickets'] = $db->table('tickets t')
            ->select('t.*, 
                p.priority_name, p.priority_id,
                s.status_name, 
                cat.category_name, 
                u.full_name as customer_name, u.email as customer_email,
                proj.project_name, proj.project_id,
                d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->orderBy('p.priority_id', 'DESC') // Urgent/High first
            ->orderBy('t.created_at', 'ASC') // Oldest first
            ->get()
            ->getResultArray();

        return view('Support/incoming_tickets', $data);
    }

    public function assignTicket($ticketId)
    {
        $userId = session()->get('user_id');
        $db = db_connect();

        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update([
                'assigned_to' => $userId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return redirect()->back()->with('success', 'Ticket assigned to you');
    }

    public function notifications()
    {
        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

        // Cek apakah tabel notifications ada
        if (!$db->tableExists('notifications')) {
            $data['notifications'] = [];
            $data['stats'] = [
                'total' => 0,
                'unread' => 0,
                'this_week' => 0
            ];
            return view('Support/notifications', $data);
        }

        try {
            // Get notifications dengan filter dari request
            $filter = $this->request->getGet('filter');
            $priority = $this->request->getGet('priority');
            $search = $this->request->getGet('search');

            $query = $db->table('notifications n')
                ->select('n.*, 
                t.ticket_number, 
                t.subject as ticket_subject,
                p.priority_name,
                p.priority_id')
                ->join('tickets t', 't.ticket_id = n.ticket_id', 'left')
                ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
                ->where('n.user_id', $userId);

            // Apply filters
            if ($filter && $filter !== 'all') {
                $query->where('n.notification_type', $filter);
            }

            if ($priority && $priority !== 'all') {
                if ($priority === 'urgent') {
                    $query->where('p.priority_name', 'Urgent');
                } elseif ($priority === 'high') {
                    $query->where('p.priority_name', 'High');
                } elseif ($priority === 'medium') {
                    $query->where('p.priority_name', 'Medium');
                } elseif ($priority === 'low') {
                    $query->where('p.priority_name', 'Low');
                }
            }

            if ($search) {
                $query->groupStart()
                    ->like('n.title', $search)
                    ->orLike('n.message', $search)
                    ->orLike('t.ticket_number', $search)
                    ->orLike('t.subject', $search)
                    ->groupEnd();
            }

            // Order by unread first, then newest
            $data['notifications'] = $query->orderBy('n.is_read', 'ASC')
                ->orderBy('n.created_at', 'DESC')
                ->get()
                ->getResultArray();

            // Get stats dinamis
            $currentWeekStart = date('Y-m-d', strtotime('monday this week'));
            $currentWeekEnd = date('Y-m-d', strtotime('sunday this week'));

            $data['stats'] = [
                'total' => $db->table('notifications')
                    ->where('user_id', $userId)
                    ->countAllResults(),

                'unread' => $db->table('notifications')
                    ->where('user_id', $userId)
                    ->where('is_read', false)
                    ->countAllResults(),

                'this_week' => $db->table('notifications')
                    ->where('user_id', $userId)
                    ->where("DATE(created_at) >= '{$currentWeekStart}'")
                    ->where("DATE(created_at) <= '{$currentWeekEnd}'")
                    ->countAllResults()
            ];

            // Get notification types count for filter
            $data['notification_types'] = $db->table('notifications')
                ->select('notification_type as type, COUNT(*) as count')
                ->where('user_id', $userId)
                ->groupBy('notification_type')
                ->get()
                ->getResultArray();

            // Debug log
            log_message('info', 'Loaded ' . count($data['notifications']) . ' notifications for user ' . $userId);

        } catch (Exception $e) {
            // Fallback data untuk development
            log_message('error', 'Error fetching notifications: ' . $e->getMessage());
            $data['notifications'] = $this->getSampleNotifications();
            $data['stats'] = [
                'total' => 24,
                'unread' => 3,
                'this_week' => 12
            ];
            $data['notification_types'] = [
                ['type' => 'assignment', 'count' => 8],
                ['type' => 'message', 'count' => 6],
                ['type' => 'warning', 'count' => 4],
                ['type' => 'resolved', 'count' => 3],
                ['type' => 'system', 'count' => 3]
            ];
        }

        return view('Support/notifications', $data);
    }

    // Tambahkan method ini di SupportController.php

    public function loadMoreNotifications()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $userId = session()->get('user_id');
        $page = $this->request->getGet('page') ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $db = db_connect();

        if (!$db->tableExists('notifications')) {
            return $this->response->setJSON([
                'success' => false,
                'html' => ''
            ]);
        }

        try {
            // Apply the same filters as main notifications page
            $filter = $this->request->getGet('filter');
            $priority = $this->request->getGet('priority');
            $search = $this->request->getGet('search');

            $query = $db->table('notifications n')
                ->select('n.*, 
                t.ticket_number, 
                t.subject as ticket_subject,
                p.priority_name,
                p.priority_id')
                ->join('tickets t', 't.ticket_id = n.ticket_id', 'left')
                ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
                ->where('n.user_id', $userId);

            // Apply filters
            if ($filter && $filter !== 'all') {
                $query->where('n.notification_type', $filter);
            }

            if ($priority && $priority !== 'all') {
                if ($priority === 'urgent') {
                    $query->where('p.priority_name', 'Urgent');
                } elseif ($priority === 'high') {
                    $query->where('p.priority_name', 'High');
                } elseif ($priority === 'medium') {
                    $query->where('p.priority_name', 'Medium');
                } elseif ($priority === 'low') {
                    $query->where('p.priority_name', 'Low');
                }
            }

            if ($search) {
                $query->groupStart()
                    ->like('n.title', $search)
                    ->orLike('n.message', $search)
                    ->orLike('t.ticket_number', $search)
                    ->orLike('t.subject', $search)
                    ->groupEnd();
            }

            // Get notifications for this page
            $notifications = $query->orderBy('n.is_read', 'ASC')
                ->orderBy('n.created_at', 'DESC')
                ->limit($limit, $offset)
                ->get()
                ->getResultArray();

            if (empty($notifications)) {
                return $this->response->setJSON([
                    'success' => true,
                    'html' => '',
                    'has_more' => false
                ]);
            }

            // Generate HTML for notifications
            $html = '';
            foreach ($notifications as $notification) {
                // Determine priority
                $priority = strtolower($notification['priority_name'] ?? 'medium');
                $priorityClass = '';
                $priorityText = '';

                switch ($priority) {
                    case 'urgent':
                        $priorityClass = 'bg-[#E16D7F] text-white';
                        $priorityText = 'Urgent';
                        break;
                    case 'high':
                        $priorityClass = 'bg-[#FFD2D2] text-red-800';
                        $priorityText = 'High';
                        break;
                    case 'medium':
                        $priorityClass = 'bg-[#FED7AA] text-orange-800';
                        $priorityText = 'Medium';
                        break;
                    case 'low':
                        $priorityClass = 'bg-[#C7D2FE] text-blue-800';
                        $priorityText = 'Low';
                        break;
                    default:
                        $priorityClass = 'bg-gray-200 text-gray-800';
                        $priorityText = 'Normal';
                }

                // Determine type
                $type = strtolower($notification['notification_type'] ?? 'system');

                // Format time
                function formatNotificationTime($datetime)
                {
                    $time = strtotime($datetime);
                    $now = time();
                    $diff = $now - $time;

                    if ($diff < 60)
                        return 'Just now';
                    elseif ($diff < 3600)
                        return floor($diff / 60) . ' minutes ago';
                    elseif ($diff < 86400)
                        return floor($diff / 3600) . ' hours ago';
                    elseif ($diff < 604800)
                        return floor($diff / 86400) . ' days ago';
                    else
                        return date('M d, Y', $time);
                }

                $timeAgo = formatNotificationTime($notification['created_at']);

                // Get ticket link if exists
                $ticketLink = $notification['ticket_id'] ?
                    base_url('support/ticket_detail/' . $notification['ticket_id']) :
                    '#';

                $html .= '<div class="notification-item bg-white/10 rounded-xl p-4 md:p-5 hover:bg-white/15 transition-colors ' .
                    (!$notification['is_read'] ? 'notification-unread' : '') .
                    '" data-priority="' . $priority .
                    '" data-type="' . $type .
                    '" data-id="' . $notification['notification_id'] . '">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                    <h3 class="text-base md:text-lg font-bold text-white">' .
                    esc($notification['title']) .
                    '</h3>
                                    <span class="text-xs md:text-sm text-white/60">' . $timeAgo . '</span>
                                </div>
                                <p class="text-sm md:text-[14px] text-white/80 mb-3">' .
                    esc($notification['message']);

                if ($notification['ticket_number']) {
                    $html .= '<br>
                         <span class="font-medium">Ticket: ' . esc($notification['ticket_number']) . ' - ' .
                        esc($notification['ticket_subject'] ?? '') . '</span>';
                }

                $html .= '</p>
                                <div class="flex items-center gap-2">
                                    <div class="priority-badge px-3 py-1 rounded-lg ' . $priorityClass . '">
                                        <span class="text-xs font-bold">' . $priorityText . '</span>
                                    </div>
                                    <span class="text-xs text-white/60 px-2 py-1 bg-white/10 rounded">
                                        ' . ucfirst($type) . '
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-3">';

                if (!$notification['is_read']) {
                    $html .= '<div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center unread-indicator">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                          </div>';
                } else {
                    $html .= '<div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center">
                            <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                          </div>';
                }

                if ($notification['ticket_id']) {
                    $html .= '<a href="' . $ticketLink . '" 
                           class="text-white/60 hover:text-white transition-colors text-sm px-3 py-1 bg-white/10 rounded-lg">
                            View Ticket
                          </a>';
                }

                $html .= '</div>
                        </div>
                    </div>';
            }

            return $this->response->setJSON([
                'success' => true,
                'html' => $html,
                'has_more' => count($notifications) >= $limit
            ]);

        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'html' => '',
                'message' => $e->getMessage()
            ]);
        }
    }
    private function getSampleNotifications()
    {
        return [
            [
                'notification_id' => 1,
                'title' => 'New ticket assigned to you',
                'message' => 'Ticket #2341 – Database Connection Error',
                'type' => 'assignment',
                'priority' => 'urgent',
                'ticket_id' => 2341,
                'read_status' => false,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 minutes')),
                'ticket_number' => '#2341',
                'ticket_subject' => 'Database Connection Error'
            ],
            [
                'notification_id' => 2,
                'title' => 'Customer replied to your ticket',
                'message' => 'Ticket #2338 – UI broken on mobile',
                'type' => 'message',
                'priority' => 'high',
                'ticket_id' => 2338,
                'read_status' => false,
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
                'ticket_number' => '#2338',
                'ticket_subject' => 'UI broken on mobile'
            ],
            [
                'notification_id' => 3,
                'title' => 'Customer sent a follow-up message',
                'message' => 'Ticket #2342 – Email notification not sent',
                'type' => 'message',
                'priority' => 'medium',
                'ticket_id' => 2342,
                'read_status' => true,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'ticket_number' => '#2342',
                'ticket_subject' => 'Email notification not sent'
            ]
        ];
    }





    // Helper method untuk membuat notifikasi otomatis
    public static function createNotification($userId, $title, $message, $type = 'system', $priority = 'medium', $ticketId = null)
    {
        $db = db_connect();

        if (!$db->tableExists('notifications')) {
            return false;
        }

        try {
            $db->table('notifications')->insert([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'priority' => $priority,
                'ticket_id' => $ticketId,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error creating notification: ' . $e->getMessage());
            return false;
        }
    }

    // Tambahkan method baru di SupportController.php

    public function getUnreadCount()
    {
        $userId = session()->get('user_id');
        $db = db_connect();

        try {
            $count = $db->table('notifications')
                ->where('user_id', $userId)
                ->where('is_read', false)
                ->countAllResults();

            return $this->response->setJSON([
                'success' => true,
                'count' => $count
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'count' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getNotifications()
    {
        $userId = session()->get('user_id');
        $limit = $this->request->getGet('limit') ?? 8;
        $db = db_connect();

        try {
            $notifications = $db->table('notifications n')
                ->select('n.*, 
                t.ticket_number, 
                t.subject as ticket_subject,
                p.priority_name')
                ->join('tickets t', 't.ticket_id = n.ticket_id', 'left')
                ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
                ->where('n.user_id', $userId)
                ->orderBy('n.created_at', 'DESC')
                ->limit($limit)
                ->get()
                ->getResultArray();

            // Format time ago
            function timeAgo($datetime)
            {
                $time = strtotime($datetime);
                $now = time();
                $diff = $now - $time;

                if ($diff < 60)
                    return 'Just now';
                elseif ($diff < 3600)
                    return floor($diff / 60) . ' mins ago';
                elseif ($diff < 86400)
                    return floor($diff / 3600) . ' hours ago';
                elseif ($diff < 604800)
                    return floor($diff / 86400) . ' days ago';
                else
                    return date('M d', $time);
            }

            // Generate HTML
            $html = '';
            if (empty($notifications)) {
                $html = '<div class="p-6 text-center text-gray-500">
                        <i class="fas fa-bell-slash text-2xl md:text-3xl mb-3 text-gray-300"></i>
                        <p class="text-sm">No notifications</p>
                    </div>';
            } else {
                foreach ($notifications as $notif) {
                    $type = $notif['notification_type'] ?? 'system';
                    $icon_class = 'fas fa-bell';
                    $bg_class = 'bg-gray-100';
                    $text_class = 'text-gray-600';

                    if ($type == 'assignment') {
                        $icon_class = 'fas fa-user-plus';
                        $bg_class = 'bg-purple-100';
                        $text_class = 'text-purple-600';
                    } elseif ($type == 'warning' || $type == 'escalated') {
                        $icon_class = 'fas fa-exclamation-triangle';
                        $bg_class = 'bg-yellow-100';
                        $text_class = 'text-yellow-600';
                    } elseif ($type == 'alert') {
                        $icon_class = 'fas fa-bell';
                        $bg_class = 'bg-red-100';
                        $text_class = 'text-red-600';
                    } elseif ($type == 'message') {
                        $icon_class = 'fas fa-comment';
                        $bg_class = 'bg-blue-100';
                        $text_class = 'text-blue-600';
                    } elseif ($type == 'resolved') {
                        $icon_class = 'fas fa-check-circle';
                        $bg_class = 'bg-green-100';
                        $text_class = 'text-green-600';
                    }

                    $html .= '<a href="#" class="notification-item block p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors ' .
                        (!$notif['is_read'] ? 'notification-unread' : '') .
                        '" data-id="' . $notif['notification_id'] .
                        '" onclick="markAsRead(' . $notif['notification_id'] . ')">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 ' . $bg_class . ' rounded-xl flex items-center justify-center">
                                        <i class="' . $icon_class . ' ' . $text_class . '"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 text-sm truncate">' .
                        esc($notif['title']) .
                        '</p>
                                    <p class="text-gray-600 text-xs mt-1 truncate">' .
                        esc($notif['message']) .
                        ($notif['ticket_number'] ?
                            ' <span class="text-secondary font-medium">' . $notif['ticket_number'] . '</span>' :
                            '') .
                        '</p>
                                    <p class="text-gray-500 text-xs mt-2">' .
                        timeAgo($notif['created_at']) .
                        '</p>
                                </div>' .
                        (!$notif['is_read'] ?
                            '<div class="flex-shrink-0 mt-1">
                                    <span class="w-2 h-2 bg-secondary rounded-full unread-dot"></span>
                                </div>' : '') .
                        '</div>
                        </a>';
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'html' => $html
            ]);

        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'html' => '<div class="p-6 text-center text-gray-500">
                         <i class="fas fa-exclamation-triangle text-2xl mb-3 text-gray-300"></i>
                         <p class="text-sm">Error loading notifications</p>
                       </div>',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function markNotificationRead()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $notificationId = $this->request->getPost('notification_id');
        $userId = session()->get('user_id');
        $db = db_connect();

        try {
            $db->table('notifications')
                ->where('notification_id', $notificationId)
                ->where('user_id', $userId)
                ->update(['is_read' => true]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating notification: ' . $e->getMessage()
            ]);
        }
    }

    public function profile()
    {
        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

        // Get user details dengan semua relasi
        $data['user_details'] = $db->table('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('u.user_id', $userId)
            ->get()
            ->getRowArray();

        // Jika user_details tidak ada, gunakan data default
        if (!$data['user_details']) {
            $data['user_details'] = [
                'full_name' => 'Support Agent',
                'email' => 'agent@nexus.com',
                'role_name' => 'Support Agent',
                'department_name' => 'Support Department',
                'created_at' => '2025-01-10 00:00:00'
            ];
        }

        // Format tanggal join
        $joinDate = !empty($data['user_details']['created_at']) ?
            date('F j, Y', strtotime($data['user_details']['created_at'])) :
            'January 10, 2025';

        // Generate Support ID
        $supportId = 'SUP-' . date('Y') . '-' . str_pad($userId, 3, '0', STR_PAD_LEFT);

        // Hitung statistik performa
        $stats = $this->calculateAgentStats($userId);

        // Hitung metrics 30 hari terakhir
        $metrics = $this->calculateLast30DaysMetrics($userId);

        // Tambahkan data ke view
        $data['support_id'] = $supportId;
        $data['join_date'] = $joinDate;
        $data['stats'] = $stats;
        $data['metrics'] = $metrics;
        $data['last_login'] = $this->getLastLoginTime();
        $data['current_status'] = 'Available';

        return view('Support/profile_support', $data);
    }

    private function calculateAgentStats($userId)
    {
        $db = db_connect();
        $now = date('Y-m-d H:i:s');
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

        // Total tickets yang ditangani
        $totalTickets = $db->table('tickets')
            ->where('assigned_to', $userId)
            ->countAllResults();

        // Tickets resolved
        $resolvedTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', $userId)
            ->whereIn('s.status_name', ['Resolved', 'Closed', 'Completed'])
            ->countAllResults();

        // Hitung rata-rata response time (dalam menit)
        $avgResponseTime = '12m'; // Default, bisa dihitung dari ticket_messages

        // Hitung customer satisfaction
        $satisfactionRate = 94; // Default, bisa dihitung dari ratings jika ada

        // Hitung metrics 30 hari terakhir
        $ticketsLast30Days = $db->table('tickets')
            ->where('assigned_to', $userId)
            ->where('created_at >=', $thirtyDaysAgo)
            ->countAllResults();

        $resolvedLast30Days = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', $userId)
            ->whereIn('s.status_name', ['Resolved', 'Closed', 'Completed'])
            ->where('t.resolved_at >=', $thirtyDaysAgo)
            ->countAllResults();

        // Hitung first contact resolution rate
        $firstContactResolutions = 0;
        $totalContacts = 0;
        $firstContactRate = 78; // Default percentage

        return [
            'total_tickets' => $totalTickets,
            'resolved_tickets' => $resolvedTickets,
            'avg_response_time' => $avgResponseTime,
            'satisfaction_rate' => $satisfactionRate,
            'tickets_last_30_days' => $ticketsLast30Days,
            'resolved_last_30_days' => $resolvedLast30Days,
            'first_contact_rate' => $firstContactRate,
            'resolution_rate' => $totalTickets > 0 ? round(($resolvedTickets / $totalTickets) * 100) : 0,
            'sla_compliance' => 96, // Default, bisa dihitung dari SLA logs
            'quality_score' => 88 // Default, bisa dihitung dari quality metrics
        ];
    }

    private function calculateLast30DaysMetrics($userId)
    {
        $db = db_connect();
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

        // Tickets handled in last 30 days
        $ticketsHandled = $db->table('tickets')
            ->where('assigned_to', $userId)
            ->where('created_at >=', $thirtyDaysAgo)
            ->countAllResults();

        // Tickets handled in previous 30 days (for comparison)
        $sixtyDaysAgo = date('Y-m-d H:i:s', strtotime('-60 days'));
        $prevTicketsHandled = $db->table('tickets')
            ->where('assigned_to', $userId)
            ->where('created_at >=', $sixtyDaysAgo)
            ->where('created_at <', $thirtyDaysAgo)
            ->countAllResults();

        // Calculate percentage change
        $ticketChangePercent = $prevTicketsHandled > 0 ?
            round((($ticketsHandled - $prevTicketsHandled) / $prevTicketsHandled) * 100) : 0;

        return [
            'tickets_handled' => $ticketsHandled,
            'ticket_change_percent' => $ticketChangePercent,
            'avg_response_time' => '12m',
            'first_contact_rate' => 78,
            'satisfaction_rate' => 94,
            'satisfaction_change_percent' => 3
        ];
    }

    private function getLastLoginTime()
    {
        $userId = session()->get('user_id');
        $db = db_connect();

        // Cari last login dari session atau database
        if (session()->has('last_login')) {
            $lastLogin = session()->get('last_login');
        } else {
            // Coba ambil dari database jika ada kolom last_login
            if ($db->fieldExists('last_login', 'users')) {
                $user = $db->table('users')
                    ->select('last_login')
                    ->where('user_id', $userId)
                    ->get()
                    ->getRowArray();
                $lastLogin = $user ? $user['last_login'] : null;
            } else {
                $lastLogin = null;
            }
        }

        // Format last login time
        if ($lastLogin) {
            $time = strtotime($lastLogin);
            $now = time();
            $diff = $now - $time;

            if ($diff < 60)
                return 'Just now';
            elseif ($diff < 3600)
                return floor($diff / 60) . ' minutes ago';
            elseif ($diff < 86400)
                return floor($diff / 3600) . ' hours ago';
            elseif ($diff < 604800)
                return floor($diff / 86400) . ' days ago';
            else
                return date('F j, Y, g:i A', $time);
        }

        return 'Today, ' . date('g:i A');
    }

    // Tambahkan method untuk update profile
    public function updateProfile()
    {
        $userId = session()->get('user_id');
        $db = db_connect();

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Jika ada department yang dipilih
        if ($this->request->getPost('department_id')) {
            $data['department_id'] = $this->request->getPost('department_id');
        }

        try {
            $db->table('users')
                ->where('user_id', $userId)
                ->update($data);

            return redirect()->to('/support/profile')->with('success', 'Profile updated successfully');
        } catch (Exception $e) {
            return redirect()->to('/support/profile')->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    public function updateStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $status = $this->request->getPost('status');
        $userId = session()->get('user_id');

        // Simpan status ke session (atau database jika ada kolom status)
        session()->set('agent_status', $status);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function ticketDetail($ticketId)
    {
        $data = $this->loadCommonData();

        $db = db_connect();

        // Get ticket details
        $ticket = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                 u.full_name as customer_name, u.email as customer_email,
                 proj.project_name, proj.project_id,
                 a.full_name as assigned_to_name, a.email as assigned_to_email')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('categories cat', 'cat.category_id = t.category_id', 'left')
            ->join('users u', 'u.user_id = t.customer_id', 'left')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('users a', 'a.user_id = t.assigned_to', 'left')
            ->where('t.ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        if (!$ticket) {
            // Jika tidak ada data di database, gunakan data dummy untuk testing
            $ticket = [
                'ticket_id' => $ticketId,
                'subject' => 'Login issue causing error message',
                'priority_name' => 'High',
                'status_name' => 'Open',
                'category_name' => 'Technical Issue',
                'customer_name' => 'John Smith',
                'customer_email' => 'john.smith@example.com',
                'project_name' => 'Project Alpha',
                'project_id' => 1,
                'assigned_to_name' => null,
                'assigned_to_email' => null,
                'created_at' => '2026-02-19 11:00:00',
                'updated_at' => '2026-02-20 10:30:00'
            ];
        }

        $data['ticket'] = $ticket;
        $data['ticket_id'] = $ticketId;

        try {
            // Get conversation messages
            // PERBAIKAN: Cek apakah tabel ticket_messages ada
            if (!$db->tableExists('ticket_messages')) {
                throw new Exception('Table ticket_messages does not exist');
            }

            // PERBAIKAN: Cek struktur tabel ticket_messages
            $fields = $db->getFieldNames('ticket_messages');

            // Tentukan kolom yang sesuai untuk join
            $userColumn = 'user_id';
            if (!in_array('user_id', $fields)) {
                // Coba cari kolom lain yang mungkin digunakan
                if (in_array('sender_id', $fields)) {
                    $userColumn = 'sender_id';
                } elseif (in_array('created_by', $fields)) {
                    $userColumn = 'created_by';
                } else {
                    // Jika tidak ada kolom user, gunakan data dummy
                    throw new Exception('No user column found in ticket_messages');
                }
            }

            $data['messages'] = $db->table('ticket_messages tm')
                ->select("tm.*, u.full_name, u.role_id, r.role_name")
                ->join('users u', "u.user_id = tm.{$userColumn}", 'left')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->where('tm.ticket_id', $ticketId)
                ->orderBy('tm.created_at', 'ASC')
                ->get()
                ->getResultArray();

        } catch (Exception $e) {
            // Jika ada error, gunakan data dummy
            $data['messages'] = [];
        }

        // Jika tidak ada messages di database, gunakan data dummy
        if (empty($data['messages'])) {
            $data['messages'] = [
                [
                    'message_id' => 1,
                    'message' => 'Hi team, I\'m having trouble accessing the ProjectX dashboard. Every time I try to log in, I receive an error message that says "Access Denied". I\'ve tried clearing my cache and using different browsers, but the issue persists.',
                    'full_name' => 'John Smith',
                    'role_name' => 'Customer',
                    'created_at' => '2026-02-19 11:00:00'
                ],
                [
                    'message_id' => 2,
                    'message' => 'Thank you for reporting this issue, John. We\'ve received your ticket and will look into it immediately. Could you please provide your browser version and operating system?',
                    'full_name' => 'Sarah Johnson',
                    'role_name' => 'Support Agent',
                    'created_at' => '2026-02-19 11:30:00'
                ],
                [
                    'message_id' => 3,
                    'message' => 'Thanks for the quick response! I\'m using Chrome version 120.0.6099.130 on Windows 11.',
                    'full_name' => 'John Smith',
                    'role_name' => 'Customer',
                    'created_at' => '2026-02-20 09:15:00'
                ],
                [
                    'message_id' => 4,
                    'message' => 'Issue identified and resolved. There was a permission configuration issue on our end. The dashboard should now be accessible.',
                    'full_name' => 'Support Team',
                    'role_name' => 'Support Lead',
                    'created_at' => '2026-02-20 10:30:00'
                ]
            ];
        }

        // Get attachments if any
        try {
            if ($db->tableExists('ticket_attachments')) {
                $data['attachments'] = $db->table('ticket_attachments')
                    ->where('ticket_id', $ticketId)
                    ->get()
                    ->getResultArray();
            } else {
                $data['attachments'] = [];
            }
        } catch (Exception $e) {
            $data['attachments'] = [];
        }

        return view('Support/ticket_detail', $data);
    }
    public function departmentConversation($ticketId)
    {
        $data = $this->loadCommonData();

        $db = db_connect();

        // Get ticket details
        $ticket = [];
        try {
            $ticket = $db->table('tickets t')
                ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                     u.full_name as customer_name, u.email as customer_email,
                     proj.project_name, proj.project_id,
                     d.department_name, d.department_id,
                     a.full_name as assigned_to_name, a.email as assigned_to_email')
                ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
                ->join('statuses s', 's.status_id = t.status_id', 'left')
                ->join('categories cat', 'cat.category_id = t.category_id', 'left')
                ->join('users u', 'u.user_id = t.customer_id', 'left')
                ->join('projects proj', 'proj.project_id = t.project_id', 'left')
                ->join('departments d', 'd.department_id = t.department_id', 'left')
                ->join('users a', 'a.user_id = t.assigned_to', 'left')
                ->where('t.ticket_id', $ticketId)
                ->get()
                ->getRowArray();
        } catch (Exception $e) {
            log_message('error', 'Error fetching ticket: ' . $e->getMessage());
        }

        // Gunakan data dummy jika query gagal
        if (!$ticket) {
            $ticket = [
                'ticket_id' => $ticketId,
                'subject' => 'Database Connection Error',
                'priority_name' => 'Urgent',
                'status_name' => 'In Progress',
                'category_name' => 'Technical Issue',
                'customer_name' => 'John Smith',
                'customer_email' => 'john.smith@example.com',
                'project_name' => 'Project Alpha',
                'project_id' => 1,
                'department_name' => 'Technical Support',
                'department_id' => 1,
                'assigned_to_name' => 'Tech Team',
                'assigned_to_email' => 'tech@example.com',
                'created_at' => '2026-02-19 11:00:00',
                'updated_at' => '2026-02-20 10:30:00'
            ];
        }

        $data['ticket'] = $ticket;
        $data['ticket_id'] = $ticketId;

        // Get conversation messages antara Support dan Department
        $data['messages'] = $this->getDepartmentConversationMessages($ticketId);

        return view('Support/department_conversation', $data);
    }

    private function getDepartmentConversationMessages($ticketId)
    {
        // Data dummy untuk conversation antara Support dan Department
        return [
            [
                'message_id' => 1,
                'message' => 'Hi Technical Support team, I\'ve forwarded ticket #' . $ticketId . ' to you. The customer is experiencing database connection errors. Can you please investigate?',
                'sender_name' => 'Support Agent',
                'sender_role' => 'Support',
                'sender_type' => 'support',
                'created_at' => '2026-02-20 09:00:00'
            ],
            [
                'message_id' => 2,
                'message' => 'Received the ticket. We\'ll check the database connection parameters and server logs. Can you provide the database credentials or ask the customer for access?',
                'sender_name' => 'Technical Lead',
                'sender_role' => 'Technical Support',
                'sender_type' => 'department',
                'created_at' => '2026-02-20 09:15:00'
            ],
            [
                'message_id' => 3,
                'message' => 'The customer has provided database access. Here are the credentials: server=db.example.com, port=5432, database=project_alpha. Let me know if you need anything else.',
                'sender_name' => 'Support Agent',
                'sender_role' => 'Support',
                'sender_type' => 'support',
                'created_at' => '2026-02-20 09:30:00'
            ],
            [
                'message_id' => 4,
                'message' => 'Thanks! We found the issue - the database connection pool was exhausted. We\'ve increased the max_connections from 100 to 200. Should be resolved now.',
                'sender_name' => 'Database Admin',
                'sender_role' => 'Technical Support',
                'sender_type' => 'department',
                'created_at' => '2026-02-20 10:00:00'
            ],
            [
                'message_id' => 5,
                'message' => 'Great! Can you test and confirm the fix is working? Also, please update the ticket status to "In Progress" while testing.',
                'sender_name' => 'Support Agent',
                'sender_role' => 'Support',
                'sender_type' => 'support',
                'created_at' => '2026-02-20 10:15:00'
            ],
            [
                'message_id' => 6,
                'message' => 'Testing completed successfully. The database connections are stable now. We\'ll monitor for the next 24 hours. Ticket status updated to "Testing".',
                'sender_name' => 'Technical Lead',
                'sender_role' => 'Technical Support',
                'sender_type' => 'department',
                'created_at' => '2026-02-20 10:30:00'
            ]
        ];
    }

    public function ticketSummary($ticketId)
    {
        $data = $this->loadCommonData();

        $db = db_connect();

        // Get ticket details for summary
        $ticket = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                     u.full_name as customer_name, u.email as customer_email,
                     proj.project_name, proj.project_id,
                     d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('categories cat', 'cat.category_id = t.category_id', 'left')
            ->join('users u', 'u.user_id = t.customer_id', 'left')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        if (!$ticket) {
            // Jika tidak ada data di database, gunakan data dummy untuk testing
            $ticket = [
                'ticket_id' => $ticketId,
                'subject' => 'Login issue causing error message',
                'priority_name' => 'High',
                'status_name' => 'Pending Review',
                'category_name' => 'Login Issue',
                'customer_name' => 'John Smith',
                'customer_email' => 'john.smith@gmail.com',
                'project_name' => 'Project Alpha',
                'project_id' => 1,
                'department_name' => null,
                'created_at' => '2026-01-02 10:00:00',
                'updated_at' => '2026-01-02 10:30:00'
            ];
        }

        $data['ticket'] = $ticket;
        $data['ticket_id'] = $ticketId;

        return view('Support/ticket_summary', $data);
    }

    public function ticketInProgress()
    {
        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

        // ==================== STATISTIK DINAMIS ====================

        // Get current date for PostgreSQL
        $currentDate = date('Y-m-d');
        $firstDayOfWeek = date('Y-m-d', strtotime('monday this week'));
        $lastDayOfWeek = date('Y-m-d', strtotime('sunday this week'));

        // Tickets in progress
        $inProgressCount = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to IS NOT NULL')
            ->whereIn('s.status_name', ['In Progress', 'Processing'])
            ->countAllResults();

        // Waiting for customer
        $waitingCount = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to IS NOT NULL')
            ->where('s.status_name', 'Waiting Customer Reply')
            ->countAllResults();

        // Resolved (this week) - FIXED for PostgreSQL
        $resolvedCount = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to IS NOT NULL')
            ->where('s.status_name', 'Resolved')
            ->where("DATE(t.resolved_at) >= '{$firstDayOfWeek}'")
            ->where("DATE(t.resolved_at) <= '{$lastDayOfWeek}'")
            ->countAllResults();

        $data['stats'] = [
            'in_progress' => $inProgressCount,
            'waiting_customer' => $waitingCount,
            'resolved_week' => $resolvedCount
        ];

        // ==================== TICKETS DINAMIS ====================

        // Get tickets in progress dengan semua relasi
        $data['tickets'] = $db->table('tickets t')
            ->select('t.*, 
                p.priority_name, p.priority_id,
                s.status_name, 
                cat.category_name, 
                u.full_name as customer_name, u.email as customer_email,
                proj.project_name, proj.project_id,
                d.department_name, d.department_id,
                a.full_name as assigned_to_name, a.email as assigned_to_email')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('categories cat', 'cat.category_id = t.category_id', 'left')
            ->join('users u', 'u.user_id = t.customer_id', 'left')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->join('users a', 'a.user_id = t.assigned_to', 'left')
            ->where('t.assigned_to IS NOT NULL')
            ->whereIn('s.status_name', ['In Progress', 'Processing', 'Waiting Customer Reply', 'Pending', 'Forwarded'])
            ->orderBy('p.priority_id', 'DESC') // Priority first
            ->orderBy('t.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // ==================== DEPARTMENT PERFORMANCE ====================

        // Get department statistics - FIXED for PostgreSQL
        $departmentStats = $db->table('tickets t')
            ->select('d.department_name,
                COUNT(t.ticket_id) as total_tickets,
                SUM(CASE WHEN s.status_name IN (\'In Progress\', \'Processing\') THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN s.status_name = \'Resolved\' THEN 1 ELSE 0 END) as resolved,
                EXTRACT(EPOCH FROM AVG(t.resolved_at - t.created_at)) / 3600 as avg_time_hours')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->where('t.assigned_to IS NOT NULL')
            ->where('t.department_id IS NOT NULL')
            ->whereIn('s.status_name', ['In Progress', 'Processing', 'Resolved', 'Waiting Customer Reply'])
            ->groupBy('d.department_id, d.department_name')
            ->orderBy('total_tickets', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $data['department_stats'] = $departmentStats;

        // ==================== RECENT ACTIVITIES ====================

        // Get recent ticket activities
        try {
            if ($db->tableExists('ticket_activities')) {
                $data['recent_activities'] = $db->table('ticket_activities ta')
                    ->select('ta.*, t.ticket_id, t.subject, 
                         u.full_name as user_name,
                         d.department_name')
                    ->join('tickets t', 't.ticket_id = ta.ticket_id')
                    ->join('users u', 'u.user_id = ta.user_id')
                    ->join('departments d', 'd.department_id = ta.department_id', 'left')
                    ->orderBy('ta.created_at', 'DESC')
                    ->limit(10)
                    ->get()
                    ->getResultArray();
            } else {
                // Fallback jika tabel tidak ada
                $data['recent_activities'] = [];
            }
        } catch (Exception $e) {
            $data['recent_activities'] = [];
        }

        return view('Support/ticket_in_progress', $data);
    }

    // Add this method to SupportController.php
    public function loadMoreTickets()
    {
        $db = db_connect();

        // Get filter parameters
        $search = $this->request->getPost('search');
        $priority = $this->request->getPost('priority');
        $status = $this->request->getPost('status');
        $department = $this->request->getPost('department');
        $offset = $this->request->getPost('offset') ?? 0;

        $query = $db->table('tickets t')
            ->select('t.*, 
                p.priority_name,
                s.status_name,
                u.full_name as customer_name,
                proj.project_name,
                d.department_name,
                a.full_name as assigned_to_name')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('users u', 'u.user_id = t.customer_id', 'left')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->join('users a', 'a.user_id = t.assigned_to', 'left')
            ->where('t.assigned_to IS NOT NULL')
            ->limit(10, $offset);

        // Apply filters
        if ($priority !== 'all' && $priority) {
            $query->where('p.priority_name', ucfirst($priority));
        }

        if ($status !== 'all' && $status) {
            $status = str_replace('-', ' ', $status);
            $query->where('s.status_name', ucfirst($status));
        }

        if ($department !== 'all' && $department) {
            $department = str_replace('-', ' ', $department);
            $query->where('d.department_name', ucfirst($department));
        }

        if ($search) {
            $query->groupStart()
                ->like('t.subject', $search)
                ->orLike('u.full_name', $search)
                ->orLike('d.department_name', $search)
                ->orLike('proj.project_name', $search)
                ->groupEnd();
        }

        $tickets = $query->get()->getResultArray();

        // Format tickets for frontend
        $formattedTickets = [];
        foreach ($tickets as $ticket) {
            // Calculate time ago
            $created = new DateTime($ticket['created_at']);
            $now = new DateTime();
            $interval = $now->diff($created);

            if ($interval->days == 0 && $interval->h == 0 && $interval->i < 1) {
                $timeAgo = 'Just now';
            } elseif ($interval->days == 0 && $interval->h == 0) {
                $timeAgo = $interval->i . ' minutes ago';
            } elseif ($interval->days == 0 && $interval->h == 1) {
                $timeAgo = '1 hour ago';
            } elseif ($interval->days == 0) {
                $timeAgo = $interval->h . ' hours ago';
            } elseif ($interval->days == 1) {
                $timeAgo = 'Yesterday';
            } else {
                $timeAgo = $created->format('M d');
            }

            $formattedTickets[] = [
                'ticket_id' => $ticket['ticket_id'],
                'subject' => $ticket['subject'],
                'priority' => strtolower($ticket['priority_name'] ?? 'medium'),
                'status' => strtolower(str_replace(' ', '-', $ticket['status_name'] ?? 'in-progress')),
                'customer_name' => $ticket['customer_name'],
                'project_name' => $ticket['project_name'],
                'department_name' => $ticket['department_name'],
                'assigned_to_name' => $ticket['assigned_to_name'],
                'department_id' => $ticket['department_id'],
                'time_ago' => $timeAgo
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'tickets' => $formattedTickets
        ]);
    }

    public function forwardTicket($ticketId)
    {
        $departmentId = $this->request->getPost('department_id');
        $notes = $this->request->getPost('notes');

        $db = db_connect();

        $result = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update([
                'department_id' => $departmentId,
                'status_id' => 2, // In Progress status
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        // Add activity log
        if ($db->tableExists('ticket_activities')) {
            $db->table('ticket_activities')->insert([
                'ticket_id' => $ticketId,
                'user_id' => session()->get('user_id'),
                'activity_type' => 'department_assigned',
                'description' => 'Ticket assigned to department',
                'notes' => $notes,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Return JSON for AJAX requests
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Ticket forwarded to department'
            ]);
        }

        return redirect()->to(site_url('support/ticket_in_progress'))->with('success', 'Ticket forwarded to department');
    }

    public function markTicketResolved($ticketId)
    {
        $db = db_connect();

        // Cari status_id untuk "Resolved"
        $status = $db->table('statuses')
            ->where('status_name', 'Resolved')
            ->orWhere('status_name', 'Closed')
            ->get()
            ->getRowArray();

        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update([
                'status_id' => $status ? $status['status_id'] : 3,
                'updated_at' => date('Y-m-d H:i:s'),
                'resolved_at' => date('Y-m-d H:i:s')
            ]);

        return redirect()->to(site_url('support/ticket_detail/' . $ticketId))->with('success', 'Ticket marked as resolved');
    }
}