<?php

namespace App\Controllers;
use App\Models\UserModel;
use Config\Database;

class CustomerController extends BaseController
{
    private $db;
    private $userId;
    private $userModel;

    public function __construct()
    {
        $this->checkRole(['Customer']);
        $this->db = Database::connect();
        $this->userId = session()->get('user_id');
        $this->userModel = new UserModel();
    }

    private function getTicketStats()
    {
        return [
            'total_tickets' => $this->db->table('tickets')
                ->where('customer_id', $this->userId)
                ->countAllResults(),
            'total_tickets_per_week' => $this->db->table('tickets')
                ->where('customer_id', $this->userId)
                ->where('created_at >=', date('Y-m-d', strtotime('-1 week')))
                ->countAllResults(),
            'open_tickets' => $this->getTicketCountByStatus('Open'),
            'in_progress_tickets' => $this->getTicketCountByStatus('In Progress'),
            'resolved_tickets' => $this->getTicketCountByStatus('Resolved'),
            'cancelled_tickets' => $this->getTicketCountByStatus('Cancelled'),
            'tickets_this_month' => $this->db->table('tickets')
                ->where('customer_id', $this->userId)
                ->where('created_at >=', date('Y-m-01'))
                ->countAllResults(),
        ];
    }

    private function getTicketCountByStatus($statusName)
    {
        return $this->db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.customer_id', $this->userId)
            ->where('s.status_name', $statusName)
            ->countAllResults();
    }

    public function dashboard()
    {
        $data = $this->loadCommonData();
        $data['stats'] = $this->getTicketStats();

        $data['recent_tickets'] = $this->db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->where('t.customer_id', $this->userId)
            ->orderBy('t.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $data['projects'] = $this->db->table('project_assignments pa')
            ->select('p.*')
            ->join('projects p', 'p.project_id = pa.project_id')
            ->where('pa.user_id', $this->userId)
            ->get()
            ->getResultArray();

        $data['notifications'] = $this->db->table('notifications')
            ->where('user_id', $this->userId)
            ->get()
            ->getResultArray();

        $user = $this->userModel->find($this->userId);
        $data['user'] = [
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'role' => session()->get('role_name'),
        ];

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
        
        $data['projects'] = $this->db->table('project_assignments pa')
            ->select('p.*')
            ->join('projects p', 'p.project_id = pa.project_id')
            ->where('pa.user_id', $this->userId)
            ->get()
            ->getResultArray();
            
        $data['categories'] = $this->db->table('categories')->get()->getResultArray();
        $data['priorities'] = $this->db->table('priorities')->get()->getResultArray();
        
        return view('Customer/create_ticket', $data);
    }

    public function myTickets()
    {
        $data = $this->loadCommonData();
        $data['stats'] = $this->getTicketStats();
        
        $data['tickets'] = $this->db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, proj.project_name, d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.customer_id', $this->userId)
            ->orderBy('t.created_at', 'DESC')
            ->get()
            ->getResultArray();
            
        return view('Customer/my_tickets', ['data' => $data]);
    }

    public function ticketDetail($id)
    {
        $data = $this->loadCommonData();
        
        $ticket = $this->db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, proj.project_name, d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.ticket_id', $id)
            ->where('t.customer_id', $this->userId)
            ->get()
            ->getRowArray();
            
        if (!$ticket) {
            return redirect()->to('/customer/my_tickets')->with('error', 'Ticket not found');
        }
        
        $data['ticket'] = $ticket;
        
        $data['messages'] = $this->db->table('ticket_messages tm')
            ->select('tm.*, u.full_name, u.photo_profile')
            ->join('users u', 'u.user_id = tm.sender_id')
            ->where('tm.ticket_id', $id)
            ->orderBy('tm.created_at', 'ASC')
            ->get()
            ->getResultArray();
            
        $data['attachments'] = $this->db->table('ticket_attachments ta')
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
        
        $data['project'] = $this->db->table('projects p')
            ->select('p.*, 
                COUNT(DISTINCT t.ticket_id) as total_tickets,
                SUM(CASE WHEN s.status_name = \'Open\' THEN 1 ELSE 0 END) as open_tickets,
                SUM(CASE WHEN s.status_name = \'In Progress\' THEN 1 ELSE 0 END) as in_progress_tickets,
                SUM(CASE WHEN s.status_name = \'Resolved\' THEN 1 ELSE 0 END) as resolved_tickets,
                SUM(CASE WHEN s.status_name = \'Closed\' THEN 1 ELSE 0 END) as closed_tickets')
            ->join('tickets t', 't.project_id = p.project_id AND t.customer_id = ' . $this->userId, 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->where('p.project_id', $projectId)
            ->groupBy('p.project_id')
            ->get()
            ->getRowArray();
            
        if (!$data['project']) {
            return redirect()->to('/customer/dashboard')->with('error', 'Project not found');
        }
        
        $data['tickets'] = $this->db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.project_id', $projectId)
            ->where('t.customer_id', $this->userId)
            ->orderBy('t.created_at', 'DESC')
            ->get()
            ->getResultArray();
            
        $data['recent_activity'] = $this->db->table('ticket_messages tm')
            ->select('tm.*, t.subject, u.full_name, u.photo_profile')
            ->join('tickets t', 't.ticket_id = tm.ticket_id')
            ->join('users u', 'u.user_id = tm.sender_id')
            ->where('t.project_id', $projectId)
            ->where('t.customer_id', $this->userId)
            ->orderBy('tm.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
            
        $data['team_members'] = $this->db->table('project_assignments pa')
            ->select('u.user_id, u.full_name, u.email, r.role_name, u.photo_profile')
            ->join('users u', 'u.user_id = pa.user_id')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('pa.project_id', $projectId)
            ->where('u.user_id !=', $this->userId)
            ->get()
            ->getResultArray();
        
        return view('Customer/project_detail', $data);
    }

    public function profile()
    {
        $data = $this->loadCommonData();
        $data['stats'] = $this->getTicketStats();
        
        $data['user_details'] = $this->db->table('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('u.user_id', $this->userId)
            ->get()
            ->getRowArray();
            
        return view('Customer/profile_customer', ['data' => $data]);
    }

    public function notifications()
    {
        $data = $this->loadCommonData();

        $notifications = $this->db->table('notifications')
            ->where('user_id', $this->userId)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data['notifications'] = [];
        foreach ($notifications as $notification) {
            $data['notifications'][] = [
                'id' => $notification['notification_id'],
                'title' => $notification['title'],
                'message' => $notification['message'],
                'time' => $notification['created_at'],
                'type' => $notification['notification_type'],
                'is_read' => $notification['is_read'],
                'ticket_id' => $notification['ticket_id'],
            ];
        }

        $data['stats'] = [
            'total_notifications' => $this->db->table('notifications')->where('user_id', $this->userId)->countAllResults(),
            'unread_notifications' => $this->db->table('notifications')->where(['user_id' => $this->userId, 'is_read' => false])->countAllResults(),
            'this_week_notifications' => $this->db->table('notifications')
                ->where('user_id', $this->userId)
                ->where('created_at >=', date('Y-m-d', strtotime('-1 week')))
                ->countAllResults(),
        ];

        return view('Customer/notifications', ['data' => $data]);
    }
}