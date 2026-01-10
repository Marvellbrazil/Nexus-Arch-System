<?php

namespace App\Controllers;

use App\Models\UserModel;
use Config\Services;

class AuthController extends BaseController
{
    public function login()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard();
        }

        return view('Auth/login');
    }

public function processLogin()
{
    // DEBUG: Tampilkan informasi request
    // echo "=== DEBUG LOGIN ===";
    // echo "<br>Method: " . $this->request->getMethod();
    // echo "<br>POST Data: ";
    // print_r($this->request->getPost());
    // echo "<br>Session sebelum login: ";
    // print_r(session()->get());
    // echo "<br>=== END DEBUG ===";
    
    $validation = Services::validation();
    $validation->setRules([
        'email' => 'required|valid_email',
        'password' => 'required'
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return redirect()->to('/login')->withInput()->with('errors', $validation->getErrors());
    }

    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $userModel = new UserModel();
    $user = $userModel->where('email', $email)->first();

    if (!$user) {
        return redirect()->to('/login')->withInput()->with('error', 'Invalid email or password');
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        return redirect()->to('/login')->withInput()->with('error', 'Invalid email or password');
    }

    // Check if user is active
    if (!$user['is_active']) {
        return redirect()->to('/login')->withInput()->with('error', 'Account is inactive. Please contact administrator.');
    }

    // Get role name
    $db = db_connect();
    $role = $db->table('roles')->where('role_id', $user['role_id'])->get()->getRowArray();
    $department = null;

    // If user has department, get department info
    if ($user['department_id']) {
        $department = $db->table('departments')->where('department_id', $user['department_id'])->get()->getRowArray();
    }

    // Set session data
    $sessionData = [
        'user_id' => $user['user_id'],
        'username' => $user['username'],
        'full_name' => $user['full_name'],
        'email' => $user['email'],
        'role_id' => $user['role_id'],
        'role_name' => $role ? $role['role_name'] : 'Unknown',
        'department_id' => $user['department_id'],
        'department_name' => $department ? $department['department_name'] : null,
        'photo_profile' => $user['photo_profile'],
        'isLoggedIn' => true,
    ];

    session()->set($sessionData);
    
    // DEBUG: Tampilkan session setelah login
    // echo "<br>=== SESSION SET ===";
    // echo "<br>Session setelah login: ";
    // print_r(session()->get());

    // Update last login
    $userModel->update($user['user_id'], ['last_login' => date('Y-m-d H:i:s')]);

    return $this->redirectToDashboard();
}
    private function redirectToDashboard()
    {
        $roleName = session()->get('role_name');
        $departmentName = session()->get('department_name');

        switch ($roleName) {
            case 'Admin':
                return redirect()->to('/admin/dashboard');
                
            case 'Customer':
                return redirect()->to('/customer/dashboard');
                
            case 'Support':
                return redirect()->to('/support/dashboard');
                
            case 'Department':
                // Redirect based on department
                switch ($departmentName) {
                    case 'IT Support':
                        return redirect()->to('/department/it-support/dashboard');
                        
                    case 'UI/UX Support':
                        return redirect()->to('/department/uiux-support/dashboard');
                        
                    case 'Technical Support':
                        return redirect()->to('/department/technical-support/dashboard');
                        
                    case 'Feature Request':
                        return redirect()->to('/department/feature-request/dashboard');
                        
                    default:
                        return redirect()->to('/login')->with('error', 'Invalid department assignment');
                }
                
            default:
                return redirect()->to('/login')->with('error', 'Invalid role assignment');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully');
    }

    public function forgotPassword()
    {
        return view('Auth/forgot_password');
    }

    public function processForgotPassword()
    {
        $email = $this->request->getPost('email');
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            // Generate reset token and send email
            // Implement reset password logic here
            return redirect()->to('/login')->with('success', 'Password reset instructions sent to your email');
        }

        return redirect()->to('/auth/forgot_password')->with('error', 'Email not found');
    }
}