<?php

namespace App\Controllers;
use App\Models\UserModel;

class CustomerController extends BaseController
{
    public function __construct()
    {
        // Check if user is customer
        $this->checkRole(['Customer']);
    }

    public function dashboard()
    {
        $data = $this->loadCommonData();
        
        // Get customer statistics
        $userId = session()->get('user_id');
        $db = db_connect();
        
        // Count tickets
        $totalTickets = $db->table('tickets')
            ->where('customer_id', $userId)
            ->countAllResults();
        
        $totalTicketsPerWeek = $db->table('tickets t')
            ->where('t.customer_id', $userId)
            ->where('t.created_at >=', date('Y-m-d', strtotime('-1 week')))
            ->countAllResults();
        
        $activeTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.customer_id', $userId)
            ->where('s.status_name', 'Open')
            ->countAllResults();

        $inProgressTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.customer_id', $userId)
            ->where('s.status_name', 'In Progress')
            ->countAllResults();

        $resolvedTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.customer_id', $userId)
            ->where('s.status_name', 'Resolved')
            ->countAllResults();

        $cancelledTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.customer_id', $userId)
            ->where('s.status_name', 'Cancelled')
            ->countAllResults();
            
        $data['stats'] = [
            'total_tickets' => $totalTickets,
            'total_tickets_per_week' => $totalTicketsPerWeek,
            'active_tickets' => $activeTickets,
            'in_progress_tickets' => $inProgressTickets,
            'resolved_tickets' => $resolvedTickets,
            'cancelled_tickets' => $cancelledTickets,
        ];

        // Get recent tickets
        $data['recent_tickets'] = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->where('t.customer_id', $userId)
            ->orderBy('t.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $userId = session()->get('user_id');
        $model = new UserModel();
        $data['user'] = [
            'full_name' => $model->where('user_id', $userId)->get()->getRowArray()['full_name'],
            'email' => $model->where('user_id', $userId)->get()->getRowArray()['email'],
            'role' => session()->get('role_name'),
        ];

        // Greeting untuk customer
        $hour = date('H');

        if ($hour >= 5 && $hour < 12) {
            $time = "Morning";
        } elseif ($hour >= 12 && $hour < 17) {
            $time = "Afternoon";
        } elseif ($hour >= 17 && $hour < 21) {
            $time = "Evening";
        } else {
            $time = "Night";
        }

        $data['current_time'] = $time;

        return view('Customer/dashboard', ['data' => $data]);
    }

    public function createTicket()
    {
        $data = $this->loadCommonData();
        
        $db = db_connect();
        
        // Get projects assigned to customer
        $userId = session()->get('user_id');
        $data['projects'] = $db->table('project_assignments pa')
            ->select('p.*')
            ->join('projects p', 'p.project_id = pa.project_id')
            ->where('pa.user_id', $userId)
            ->get()
            ->getResultArray();
            
        // Get categories
        $data['categories'] = $db->table('categories')->get()->getResultArray();
        
        // Get priorities
        $data['priorities'] = $db->table('priorities')->get()->getResultArray();
        
        return view('Customer/create_ticket', $data);
    }

    public function myTickets()
    {
        $data = $this->loadCommonData();
        
        $userId = session()->get('user_id');
        $db = db_connect();
        
        // Get all tickets for this customer
        $data['tickets'] = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, proj.project_name, d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.customer_id', $userId)
            ->orderBy('t.created_at', 'DESC')
            ->get()
            ->getResultArray();
            
        return view('Customer/my_tickets', $data);
    }

    public function ticketDetail($id)
    {
        $data = $this->loadCommonData();
        
        $userId = session()->get('user_id');
        $db = db_connect();
        
        // Get ticket details
        $ticket = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, proj.project_name, d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.ticket_id', $id)
            ->where('t.customer_id', $userId)
            ->get()
            ->getRowArray();
            
        if (!$ticket) {
            return redirect()->to('/customer/my_tickets')->with('error', 'Ticket not found');
        }
        
        $data['ticket'] = $ticket;
        
        // Get ticket messages
        $data['messages'] = $db->table('ticket_messages tm')
            ->select('tm.*, u.full_name, u.photo_profile')
            ->join('users u', 'u.user_id = tm.sender_id')
            ->where('tm.ticket_id', $id)
            ->orderBy('tm.created_at', 'ASC')
            ->get()
            ->getResultArray();
            
        // Get attachments
        $data['attachments'] = $db->table('ticket_attachments ta')
            ->select('ta.*, u.full_name')
            ->join('users u', 'u.user_id = ta.uploaded_by')
            ->where('ta.ticket_id', $id)
            ->get()
            ->getResultArray();
            
        return view('Customer/ticket_detail', $data);
    }
public function projectDetail($projectId)
{
    $data = $this->loadCommonData();
    
    $userId = session()->get('user_id');
    $db = db_connect();
    
    // Get project details - FIX: Use single quotes for string literals
    $data['project'] = $db->table('projects p')
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
        
    if (!$data['project']) {
        return redirect()->to('/customer/dashboard')->with('error', 'Project not found or access denied');
    }
    
    // Get all tickets in this project
    $data['tickets'] = $db->table('tickets t')
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
        
    // Get recent activity
    $data['recent_activity'] = $db->table('ticket_messages tm')
        ->select('tm.*, t.subject, u.full_name, u.photo_profile')
        ->join('tickets t', 't.ticket_id = tm.ticket_id')
        ->join('users u', 'u.user_id = tm.sender_id')
        ->where('t.project_id', $projectId)
        ->where('t.customer_id', $userId)
        ->orderBy('tm.created_at', 'DESC')
        ->limit(5)
        ->get()
        ->getResultArray();
        
    // Get project team members
    $data['team_members'] = $db->table('project_assignments pa')
        ->select('u.user_id, u.full_name, u.email, r.role_name, u.photo_profile')
        ->join('users u', 'u.user_id = pa.user_id')
        ->join('roles r', 'r.role_id = u.role_id')
        ->where('pa.project_id', $projectId)
        ->where('u.user_id !=', $userId)
        ->get()
        ->getResultArray();
    
    return view('Customer/project_detail', $data);
}
    public function profile()
    {
        $data = $this->loadCommonData();
        
        // Get customer statistics
        $userId = session()->get('user_id');
        $db = db_connect();
        
        // Count tickets
        $totalTickets = $db->table('tickets')
            ->where('customer_id', $userId)
            ->countAllResults();
        
        $activeTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.customer_id', $userId)
            ->where('s.status_name', 'Open')
            ->countAllResults();

        $inProgressTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.customer_id', $userId)
            ->where('s.status_name', 'In Progress')
            ->countAllResults();

        $resolvedTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.customer_id', $userId)
            ->where('s.status_name', 'Resolved')
            ->countAllResults();

        $cancelledTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.customer_id', $userId)
            ->where('s.status_name', 'Cancelled')
            ->countAllResults();

        $data['stats'] = [
            'total_tickets' => $totalTickets,
            'active_tickets' => $activeTickets,
            'in_progress_tickets' => $inProgressTickets,
            'resolved_tickets' => $resolvedTickets,
            'cancelled_tickets' => $cancelledTickets,
        ];
        
        // Get user details
        $data['user_details'] = $db->table('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('u.user_id', $userId)
            ->get()
            ->getRowArray();
            
        return view('Customer/profile_customer', ['data' => $data]);
    }
    public function notifications()
    {
        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

        // Get notifications for this customer (simulated data for now)
        // In real app, you would query from notifications table
        $data['notifications'] = [
            [
                'id' => 1,
                'title' => 'New message from support',
                'message' => 'Your ticket #10421 has been updated with a new response',
                'time' => '2 mins ago',
                'type' => 'message',
                'is_read' => false,
                'ticket_id' => 10421
            ],
            [
                'id' => 2,
                'title' => 'Ticket resolved',
                'message' => 'Your ticket #10422 has been marked as resolved',
                'time' => '1 hour ago',
                'type' => 'success',
                'is_read' => true,
                'ticket_id' => 10422
            ],
            [
                'id' => 3,
                'title' => 'Welcome to NEXUS',
                'message' => 'Thank you for joining our support system',
                'time' => '3 days ago',
                'type' => 'info',
                'is_read' => true,
                'ticket_id' => null
            ]
        ];

        // Mark all notifications as read when viewing (simulated)
        // In real app: $db->table('notifications')->where('user_id', $userId)->update(['is_read' => 1]);

        return view('Customer/notifications', $data);
    }
}