<?php

namespace App\Controllers;

class Support extends BaseController
{
    public function __construct()
    {
        // Check if user is logged in
        if (!session()->get('is_logged_in')) {
            return redirect()->to('login');
        }
        
        // Check if user has support role
        $userRole = session()->get('role');
        if (!in_array($userRole, ['Support', 'Admin'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
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
            } catch (\Exception $e) {
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
        
        // Get support statistics
        $assignedTickets = $db->table('tickets')
            ->where('assigned_to', $userId)
            ->countAllResults();
            
        $openTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', $userId)
            ->where('s.status_name', 'Open')
            ->countAllResults();
            
        $incomingTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', null)
            ->where('s.status_name', 'Open')
            ->countAllResults();

        $data['stats'] = [
            'assigned_tickets' => $assignedTickets,
            'open_tickets' => $openTickets,
            'incoming_tickets' => $incomingTickets
        ];

        // Get incoming tickets
        $data['incoming_tickets'] = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, u.full_name as customer_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->where('t.assigned_to', null)
            ->where('s.status_name', 'Open')
            ->orderBy('t.created_at', 'ASC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // Get assigned tickets
        $data['assigned_tickets'] = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, u.full_name as customer_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->where('t.assigned_to', $userId)
            ->orderBy('t.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        return view('Support/dashboard', $data);
    }

    public function incomingTickets()
    {
        $data = $this->loadCommonData();
        
        $db = db_connect();
        
        // Get all incoming tickets
        $data['tickets'] = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, u.full_name as customer_name, proj.project_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->where('t.assigned_to', null)
            ->where('s.status_name', 'Open')
            ->orderBy('t.created_at', 'ASC')
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
            return view('Support/notifications', $data);
        }
        
        try {
            // Cek apakah kolom read_status ada
            $fields = $db->getFieldNames('notifications');
            
            if (in_array('read_status', $fields)) {
                // Jika kolom read_status ada
                $data['notifications'] = $db->table('notifications n')
                    ->select('n.*')
                    ->where('n.user_id', $userId)
                    ->where('n.read_status', 0)
                    ->orderBy('n.created_at', 'DESC')
                    ->get()
                    ->getResultArray();
            } else {
                // Jika kolom read_status tidak ada, gunakan kolom lain atau semua notifikasi
                $data['notifications'] = $db->table('notifications n')
                    ->select('n.*')
                    ->where('n.user_id', $userId)
                    ->orderBy('n.created_at', 'DESC')
                    ->limit(20)
                    ->get()
                    ->getResultArray();
            }
            
        } catch (\Exception $e) {
            // Fallback jika ada error
            $data['notifications'] = [];
        }
            
        return view('Support/notifications', $data);
    }

    public function markNotificationsRead()
    {
        $userId = session()->get('user_id');
        $db = db_connect();
        
        if (!$db->tableExists('notifications')) {
            return redirect()->to(site_url('support/notifications'))->with('error', 'Notifications table not found');
        }
        
        try {
            // Cek apakah kolom read_status ada
            $fields = $db->getFieldNames('notifications');
            
            if (in_array('read_status', $fields)) {
                $db->table('notifications')
                    ->where('user_id', $userId)
                    ->where('read_status', 0)
                    ->update(['read_status' => 1]);
            }
            
            return redirect()->to(site_url('support/notifications'))->with('success', 'All notifications marked as read');
            
        } catch (\Exception $e) {
            return redirect()->to(site_url('support/notifications'))->with('error', 'Error updating notifications');
        }
    }

    public function profile()
    {
        $data = $this->loadCommonData();
        
        $userId = session()->get('user_id');
        $db = db_connect();
        
        // Get user details
        $data['user_details'] = $db->table('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('u.user_id', $userId)
            ->get()
            ->getRowArray();
            
        return view('Support/profile_support', $data);
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
            throw new \Exception('Table ticket_messages does not exist');
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
                throw new \Exception('No user column found in ticket_messages');
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
        
    } catch (\Exception $e) {
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
    } catch (\Exception $e) {
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
    } catch (\Exception $e) {
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
        
        // Get tickets in progress
        $data['tickets'] = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                     u.full_name as customer_name, proj.project_name,
                     d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('categories cat', 'cat.category_id = t.category_id', 'left')
            ->join('users u', 'u.user_id = t.customer_id', 'left')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.assigned_to IS NOT NULL')
            ->whereIn('s.status_name', ['In Progress', 'Pending', 'Processing'])
            ->orderBy('t.created_at', 'DESC')
            ->get()
            ->getResultArray();
            
        return view('Support/ticket_in_progress', $data);
    }

    public function forwardTicket($ticketId)
    {
        $departmentId = $this->request->getPost('department_id');
        
        $db = db_connect();
        
        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update([
                'department_id' => $departmentId,
                'status_id' => 2, // In Progress status
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        
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