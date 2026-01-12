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

        $data['projects'] = $this->db->table('projects p')
            ->select('p.*, COUNT(t.ticket_id) as ticket_count')
            ->join('tickets t', 't.project_id = p.project_id', 'left') // left untuk project tanpa ticket
            ->where('p.user_id', $this->userId)
            ->groupBy('p.project_id')
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

        $data['team_members'] = $this->db->table('tickets t')
            ->select('u.user_id, u.full_name, u.email, r.role_name, u.photo_profile')
            ->join('users u', 'u.user_id = t.assigned_to') // Ambil staf yang ditugaskan ke tiket
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('t.project_id', $projectId)
            ->where('u.user_id !=', $this->userId) // Jangan masukkan diri sendiri
            ->distinct() // Penting agar satu orang tidak muncul double jika pegang banyak tiket
            ->get()
            ->getResultArray();

        return view('Customer/project_detail', $data);
    }

    public function updateProfile()
    {
        // Get form data
        $fullName = $this->request->getPost('full_name');
        $phoneNumber = $this->request->getPost('phone_number');
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validation rules
        $validationRules = [
            'full_name' => 'required|min_length[3]|max_length[100]',
            'phone_number' => 'permit_empty|min_length[10]|max_length[20]',
        ];

        // Validate basic fields
        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get current user data
        $userData = $this->userModel->find($this->userId);

        // Prepare update data
        $updateData = [
            'full_name' => $fullName,
            'phone_number' => $phoneNumber ?: null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle password change if provided
        if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
            // Validate password fields
            if (empty($currentPassword)) {
                return redirect()->back()->with('error', 'Current password is required to change password');
            }

            if (empty($newPassword)) {
                return redirect()->back()->with('error', 'New password is required');
            }

            if ($newPassword !== $confirmPassword) {
                return redirect()->back()->with('error', 'New password and confirmation do not match');
            }

            if (strlen($newPassword) < 6) {
                return redirect()->back()->with('error', 'New password must be at least 6 characters');
            }

            // Verify current password
            if (!password_verify($currentPassword, $userData['password'])) {
                return redirect()->back()->with('error', 'Current password is incorrect');
            }

            // Update password
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // Handle file upload (profile photo)
        $photo = $this->request->getFile('photo_profile');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            // Delete old photo if exists
            if (!empty($userData['photo_profile'])) {
                $oldPhotoPath = WRITEPATH . 'uploads/profile/' . basename($userData['photo_profile']);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            // Upload new photo
            $newName = $photo->getRandomName();
            $photo->move(WRITEPATH . 'uploads/profile', $newName);

            $updateData['photo_profile'] = 'uploads/profile/' . $newName;
        }

        // Update user in database
        if ($this->userModel->update($this->userId, $updateData)) {
            // Update session data
            $updatedUser = $this->userModel->find($this->userId);
            session()->set([
                'full_name' => $updatedUser['full_name'],
                'photo_profile' => $updatedUser['photo_profile']
            ]);

            return redirect()->to('/customer/profile')->with('success', 'Profile updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update profile');
        }
    }

    // Update method profile() untuk menambahkan project data:
    public function profile()
    {
        $data = $this->loadCommonData();
        $data['stats'] = $this->getTicketStats();

        // Get user details
        $data['user_details'] = $this->db->table('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('u.user_id', $this->userId)
            ->get()
            ->getRowArray();

        // Get assigned projects with ticket counts
        $data['assigned_projects'] = $this->db->table('projects p')
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
            ->join('tickets t', 't.project_id = p.project_id AND t.customer_id = p.user_id', 'left')
            ->where('p.user_id', $this->userId)
            ->where('p.is_active', true)
            ->groupBy('p.project_id, p.project_code, p.project_name, p.description, p.created_at')
            ->orderBy('p.project_name', 'ASC')
            ->get()
            ->getResultArray();

        // Calculate ticket statistics for progress bars
        $totalTickets = $data['stats']['total_tickets'];
        $data['ticket_percentages'] = [
            'open' => $totalTickets > 0 ? round(($data['stats']['open_tickets'] / $totalTickets) * 100, 1) : 0,
            'in_progress' => $totalTickets > 0 ? round(($data['stats']['in_progress_tickets'] / $totalTickets) * 100, 1) : 0,
            'resolved' => $totalTickets > 0 ? round(($data['stats']['resolved_tickets'] / $totalTickets) * 100, 1) : 0,
            'closed' => $totalTickets > 0 ? round(($data['stats']['cancelled_tickets'] / $totalTickets) * 100, 1) : 0,
        ];

        // Format last login time
        if (!empty($data['user_details']['last_login'])) {
            $lastLogin = new \DateTime($data['user_details']['last_login']);
            $now = new \DateTime();
            $interval = $lastLogin->diff($now);

            if ($interval->days == 0) {
                $data['last_login_text'] = 'Today, ' . $lastLogin->format('h:i A');
            } elseif ($interval->days == 1) {
                $data['last_login_text'] = 'Yesterday, ' . $lastLogin->format('h:i A');
            } else {
                $data['last_login_text'] = $lastLogin->format('M d, Y, h:i A');
            }
        } else {
            $data['last_login_text'] = 'Never logged in';
        }

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

    // Di dalam CustomerController.php, tambahkan method ini:

    private function getAssignedProjects()
    {
        return $this->db->table('projects p')
            ->select('p.project_id, p.project_code, p.project_name, p.description, p.created_at, 
                COUNT(t.ticket_id) as ticket_count')
            ->join('tickets t', 't.project_id = p.project_id', 'left') 
            ->where('p.user_id', $this->userId) // Sekarang langsung filter ke kolom user_id di tabel projects
            ->where('p.is_active', value: true)
            ->groupBy('p.project_id, p.project_code, p.project_name, p.description, p.created_at')
            ->orderBy('p.project_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Update method createTicket():
    public function createTicket()
    {
        $data = $this->loadCommonData();

        // Get projects assigned to this customer
        $data['projects'] = $this->getAssignedProjects();

        // Get selected project from query parameter or default to first project
        $selectedProjectId = $this->request->getGet('project');
        if ($selectedProjectId) {
            $data['selected_project'] = $this->db->table('projects')
                ->where('project_id', $selectedProjectId)
                ->where('is_active', true)
                ->get()
                ->getRowArray();
        }

        // If no selected project or project not found, use first assigned project
        if (empty($data['selected_project']) && !empty($data['projects'])) {
            $data['selected_project'] = $data['projects'][0];
            $selectedProjectId = $data['projects'][0]['project_id'];
        }

        $data['categories'] = $this->db->table('categories')->get()->getResultArray();
        $data['priorities'] = $this->db->table('priorities')->get()->getResultArray();
        $data['departments'] = $this->db->table('departments')->get()->getResultArray();

        return view('Customer/create_ticket', ['data' => $data]);
    }

    // Tambahkan method untuk menangani form submission:
    public function processCreateTicket()
    {
        // Ambil data sesuai atribut 'name' di view create_ticket.php
        $projectId  = $this->request->getPost('project_id'); // Sesuai view
        $title      = $this->request->getPost('title');
        $description = $this->request->getPost('description');
        $priorityId = $this->request->getPost('priority_id'); // Sesuai view
        $categoryId = $this->request->getPost('category_id'); // Sesuai view
        
        // Validasi
        if (empty($projectId) || empty($title) || empty($description) || empty($priorityId) || empty($categoryId)) {
            return redirect()->back()->withInput()->with('error', 'All required fields must be filled');
        }
        
        // Cek project (Gunakan tabel 'projects' sesuai instruksi sebelumnya)
        $project = $this->db->table('projects')
            ->where('project_id', $projectId)
            ->where('user_id', $this->userId)
            ->get()
            ->getRowArray();
        
        if (!$project) {
            return redirect()->back()->with('error', 'Project not found or access denied');
        }
        
        // Nomor Tiket & Mapping Departemen
        $ticketCount = $this->db->table('tickets')->where('project_id', $projectId)->countAllResults();
        $ticketNumber = $project['project_code'] . '-' . str_pad($ticketCount + 1, 3, '0', STR_PAD_LEFT);
        
        $departmentMapping = $this->db->table('category_department_mapping')->where('category_id', $categoryId)->get()->getRowArray();
        $departmentId = $departmentMapping ? $departmentMapping['department_id'] : null;
        
        $ticketData = [
            'ticket_number' => $ticketNumber,
            'project_id'    => $projectId,
            'customer_id'   => $this->userId,
            'category_id'   => $categoryId,
            'priority_id'   => $priorityId,
            'status_id'     => 1, // Open
            'department_id' => $departmentId,
            'subject'       => $title,
            'description'   => $description,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];
        
        $this->db->table('tickets')->insert($ticketData);
        $ticketId = $this->db->insertID();
        
        $this->handleAttachments($ticketId);
        // $this->createTicketNotification($ticketId);
        
        // Sekarang redirect akan bekerja dengan benar karena dikirim via Form HTML, bukan AJAX
        return redirect()->to('/customer/my_tickets')->with('success', 'Ticket created successfully!');
    }

    private function handleAttachments($ticketId)
    {
        $files = $this->request->getFiles();

        if (!empty($files['attachments'])) {
            foreach ($files['attachments'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(WRITEPATH . 'uploads/tickets', $newName);

                    $attachmentData = [
                        'ticket_id' => $ticketId,
                        'uploaded_by' => $this->userId,
                        'file_name' => $file->getName(),
                        'file_path' => 'uploads/tickets/' . $newName,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    $this->db->table('ticket_attachments')->insert($attachmentData);
                }
            }
        }
    }

    private function createTicketNotification($ticketId)
    {
        $ticket = $this->db->table('tickets')
            ->select('t.*, p.project_name, u.full_name as customer_name')
            ->join('projects p', 'p.project_id = t.project_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->where('t.ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        // Create notification for support team
        $notificationData = [
            'user_id' => 3, // Support team user ID (adjust as needed)
            'ticket_id' => $ticketId,
            'title' => 'New Ticket Created',
            'message' => $ticket['customer_name'] . ' created a new ticket in ' . $ticket['project_name'],
            'notification_type' => 'ticket_created',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('notifications')->insert($notificationData);
    }

}