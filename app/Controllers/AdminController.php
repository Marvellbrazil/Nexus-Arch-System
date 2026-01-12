<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProjectModel;
use App\Models\ProjectAssignmentModel;
use App\Models\TicketModel;

class AdminController extends BaseController
{
    protected $userModel;
    protected $projectModel;
    protected $projectAssignmentModel;
    protected $ticketModel;
    
public function __construct()
{
    // Untuk sementara, bypass semua model instantiation
    // dengan langsung menggunakan database
    
    // Atau jika UserModel sudah ada, tapi ada error di constructor-nya
    try {
        $this->userModel = new UserModel();
    } catch (\Throwable $e) {
        // Jika error, set null dan handle di masing-masing method
        $this->userModel = null;
    }
    
    // Untuk sementara, set null untuk model lain
    $this->projectModel = null;
    $this->projectAssignmentModel = null;
    $this->ticketModel = null;
}

    public function dashboard()
    {
        $data = $this->loadCommonData();
        
        // Get statistics for dashboard
        $db = db_connect();
        
        // Total users count
        $totalUsers = $db->table('users')->countAll();
        
        // Total projects count
        $totalProjects = $db->table('projects')->where('is_active', 1)->countAll();
        
        // Total tickets count
        $totalTickets = $db->table('tickets')->countAll();
        
        // Open tickets count
        $openTickets = $db->table('tickets')->whereIn('status_id', [1, 2])->countAll(); // Open and In Progress
        
        // Users by role
        $usersByRole = $this->userModel->countUsersByRole();
        
        // Recent users
        $recentUsers = $db->table('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->orderBy('u.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
            
        // Recent projects
        $recentProjects = $db->table('projects')
            ->select('projects.*, 
                (SELECT COUNT(*) FROM tickets WHERE tickets.project_id = projects.project_id) as total_tickets,
                (SELECT COUNT(*) FROM tickets WHERE tickets.project_id = projects.project_id AND tickets.status_id IN (1,2)) as open_tickets')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $data['stats'] = [
            'total_users' => $totalUsers,
            'total_projects' => $totalProjects,
            'total_tickets' => $totalTickets,
            'open_tickets' => $openTickets,
            'users_by_role' => $usersByRole,
            'recent_users' => $recentUsers,
            'recent_projects' => $recentProjects
        ];

        return view('Admin/dashboard', $data);
    }

    public function manageUsers()
    {
        $data = $this->loadCommonData();
        
        // Get all users with roles and departments
        $data['users'] = $this->userModel->getUsersWithRole();
        
        // Get roles and departments for filters
        $db = db_connect();
        $data['roles'] = $db->table('roles')->get()->getResultArray();
        $data['departments'] = $db->table('departments')->get()->getResultArray();
        
        return view('Admin/manage_users', $data);
    }

    public function addUser()
    {
        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
                'full_name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'role_id' => 'required|integer',
                'department_id' => 'permit_empty|integer'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $userData = [
                'username' => $this->request->getPost('username'),
                'full_name' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'role_id' => $this->request->getPost('role_id'),
                'department_id' => $this->request->getPost('department_id') ?: null,
                'phone_number' => $this->request->getPost('phone_number'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            if ($this->userModel->save($userData)) {
                return redirect()->to('/admin/users')->with('success', 'User added successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to add user');
            }
        }

        return redirect()->to('/admin/users');
    }

    public function editUser($id)
    {
        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,user_id,$id]",
                'full_name' => 'required|min_length[3]|max_length[100]',
                'email' => "required|valid_email|is_unique[users.email,user_id,$id]",
                'role_id' => 'required|integer',
                'department_id' => 'permit_empty|integer'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $userData = [
                'user_id' => $id,
                'username' => $this->request->getPost('username'),
                'full_name' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email'),
                'role_id' => $this->request->getPost('role_id'),
                'department_id' => $this->request->getPost('department_id') ?: null,
                'phone_number' => $this->request->getPost('phone_number'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            // Only update password if provided
            if ($this->request->getPost('password')) {
                $userData['password'] = $this->request->getPost('password');
            }

            if ($this->userModel->save($userData)) {
                return redirect()->to('/admin/users')->with('success', 'User updated successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to update user');
            }
        }

        return redirect()->to('/admin/users');
    }

    public function resetPassword($id)
    {
        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'new_password' => 'required|min_length[6]',
                'confirm_password' => 'required|matches[new_password]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $newPassword = $this->request->getPost('new_password');
            
            if ($this->userModel->update($id, ['password' => $newPassword])) {
                return redirect()->to('/admin/users')->with('success', 'Password reset successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to reset password');
            }
        }

        return redirect()->to('/admin/users');
    }

    public function changeStatus($id)
    {
        $status = $this->request->getPost('status') === 'active' ? 1 : 0;
        
        if ($this->userModel->update($id, ['is_active' => $status])) {
            return redirect()->to('/admin/users')->with('success', "User status updated successfully!");
        } else {
            return redirect()->back()->with('error', 'Failed to update status');
        }
    }

    public function deleteUser($id)
    {
        // Prevent deleting yourself
        if ($id == session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Cannot delete your own account');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'User deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete user');
        }
    }

    public function manageRoles()
    {
        $data = $this->loadCommonData();
        
        $db = db_connect();
        $data['roles'] = $db->table('roles')->get()->getResultArray();
        
        return view('Admin/manage_roles', $data);
    }

    public function manageDepartments()
    {
        $data = $this->loadCommonData();
        
        $db = db_connect();
        $data['departments'] = $db->table('departments')->get()->getResultArray();
        
        return view('Admin/manage_departments', $data);
    }

    public function viewTickets()
    {
        $data = $this->loadCommonData();
        return view('Admin/view_tickets', $data);
    }

    public function systemSettings()
    {
        $data = $this->loadCommonData();
        return view('Admin/system_settings', $data);
    }
    
    // ==================== PROJECT MANAGEMENT METHODS ====================
    
    public function manageProjects()
    {
        $data = $this->loadCommonData();
        
        // Get all projects with ticket counts
        $db = db_connect();
        
        $data['projects'] = $db->table('projects p')
            ->select('p.*, 
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id) as total_tickets,
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)) as open_tickets,
                (SELECT COUNT(*) FROM project_assignments pa WHERE pa.project_id = p.project_id) as assigned_users')
            ->orderBy('p.created_at', 'DESC')
            ->get()
            ->getResultArray();
            
        // Get all users for assignment
        $data['all_users'] = $db->table('users u')
            ->select('u.user_id, u.username, u.full_name, u.email, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('u.is_active', true)
            ->orderBy('u.full_name', 'ASC')
            ->get()
            ->getResultArray();
            
        // Get project assignments
        $data['assignments'] = [];
        $assignments = $db->table('project_assignments pa')
            ->select('pa.project_id, pa.user_id, u.full_name')
            ->join('users u', 'u.user_id = pa.user_id')
            ->get()
            ->getResultArray();
            
        foreach ($assignments as $assignment) {
            if (!isset($data['assignments'][$assignment['project_id']])) {
                $data['assignments'][$assignment['project_id']] = [];
            }
            $data['assignments'][$assignment['project_id']][] = $assignment['user_id'];
        }

        return view('Admin/manage_projects', $data);
    }

    public function addProject()
    {
        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'project_code' => 'required|min_length[3]|max_length[20]|is_unique[projects.project_code]',
                'project_name' => 'required|min_length[3]|max_length[100]',
                'description' => 'permit_empty'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $projectData = [
                'project_code' => strtoupper($this->request->getPost('project_code')),
                'project_name' => $this->request->getPost('project_name'),
                'description' => $this->request->getPost('description'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->projectModel->save($projectData)) {
                return redirect()->to('/admin/projects')->with('success', 'Project added successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to add project');
            }
        }

        return redirect()->to('/admin/projects');
    }

    public function editProject($id)
    {
        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'project_code' => "required|min_length[3]|max_length[20]|is_unique[projects.project_code,project_id,$id]",
                'project_name' => 'required|min_length[3]|max_length[100]',
                'description' => 'permit_empty'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $projectData = [
                'project_id' => $id,
                'project_code' => strtoupper($this->request->getPost('project_code')),
                'project_name' => $this->request->getPost('project_name'),
                'description' => $this->request->getPost('description'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->projectModel->save($projectData)) {
                return redirect()->to('/admin/projects')->with('success', 'Project updated successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to update project');
            }
        }

        return redirect()->to('/admin/projects');
    }

    public function deleteProject($id)
    {
        // Check if project has tickets
        $hasTickets = $this->ticketModel->where('project_id', $id)->countAllResults();
        
        if ($hasTickets > 0) {
            return redirect()->to('/admin/projects')->with('error', 'Cannot delete project with existing tickets. Please reassign tickets first.');
        }

        // Delete project assignments first
        $this->projectAssignmentModel->where('project_id', $id)->delete();
        
        if ($this->projectModel->delete($id)) {
            return redirect()->to('/admin/projects')->with('success', 'Project deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete project');
        }
    }

    public function assignUsersToProject($projectId)
    {
        if ($this->request->getMethod() === 'post') {
            $userIds = $this->request->getPost('user_ids') ?: [];
            
            // Delete existing assignments
            $this->projectAssignmentModel->where('project_id', $projectId)->delete();
            
            // Add new assignments
            if (!empty($userIds)) {
                $assignmentData = [];
                foreach ($userIds as $userId) {
                    $assignmentData[] = [
                        'project_id' => $projectId,
                        'user_id' => $userId,
                        'assigned_at' => date('Y-m-d H:i:s')
                    ];
                }
                
                if (!empty($assignmentData)) {
                    $this->projectAssignmentModel->insertBatch($assignmentData);
                }
            }
            
            return redirect()->to('/admin/projects')->with('success', 'Users assigned to project successfully!');
        }

        return redirect()->to('/admin/projects');
    }

    public function changeProjectStatus($id)
    {
        $status = $this->request->getPost('status') === 'active' ? 1 : 0;
        
        if ($this->projectModel->update($id, ['is_active' => $status])) {
            return redirect()->to('/admin/projects')->with('success', "Project status updated successfully!");
        } else {
            return redirect()->back()->with('error', 'Failed to update project status');
        }
    }
    
    // ==================== HELPER METHODS ====================
    
protected function loadCommonData()
{
    $data = [
        'title' => 'NEXUS Admin',
        'user_id' => session()->get('user_id'),
        'full_name' => session()->get('full_name'),
        'username' => session()->get('username'),
        'email' => session()->get('email'),
        'role_name' => session()->get('role_name'),
        'is_admin' => session()->get('is_admin')
    ];
    
    return $data;
}
}