<?php

namespace App\Controllers;

use App\Models\UserModel;

class AdminController extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        
        // Check if user is admin
        $this->checkRole(['Admin']);
    }

    public function dashboard()
    {
        $data = $this->loadCommonData();
        
        // Get statistics for dashboard
        $db = db_connect();
        
        // Total users count
        $totalUsers = $db->table('users')->countAll();
        
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

        $data['stats'] = [
            'total_users' => $totalUsers,
            'users_by_role' => $usersByRole,
            'recent_users' => $recentUsers
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
}