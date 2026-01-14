<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\DepartmentModel;
use Config\Services;

class AuthController extends BaseController
{
    public $userModel;
    public $roleModel;
    public $departmentModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->departmentModel = new DepartmentModel();
    }
    public function loginCustomer()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard();
        }

        return view('Auth/login_customer');
    }

    // Halaman login untuk Admin
    public function loginAdmin()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard();
        }

        return view('Auth/login_admin');
    }

    // Halaman login untuk Support
    public function loginSupport()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard();
        }

        return view('Auth/login_support');
    }

    // Halaman login untuk Department
    public function loginDepartment()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard();
        }

        return view('Auth/login_department');
    }

    public function processLogin()
    {
        $validation = Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required',
            'login_type' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $loginType = $this->request->getPost('login_type'); // 'admin', 'support', 'customer', 'department'

        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password');
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password');
        }

        // Check if user is active
        if (!$user['is_active']) {
            return redirect()->back()->withInput()->with('error', 'Account is inactive. Please contact administrator.');
        }

        // Get role name
        $role = $this->roleModel->getRoleName($user['role_id']);
        $department = null;

        // If user has department, get department info
        if ($user['department_id']) {
            $department = $this->departmentModel->getDepartmentByID($user['department_id']);
        }

        // Validasi role sesuai dengan halaman login
        $roleName = $role ? $role['role_name'] : 'Unknown';

        // Validasi ketat berdasarkan halaman login
        $isValidLogin = false;
        switch ($loginType) {
            case 'admin':
                if ($roleName === 'Admin') $isValidLogin = true;
                break;
            case 'support':
                if ($roleName === 'Support') $isValidLogin = true;
                break;
            case 'customer':
                if ($roleName === 'Customer') $isValidLogin = true;
                break;
            case 'department':
                if ($roleName === 'Department') $isValidLogin = true;
                break;
        }

        if (!$isValidLogin) {
            // Tentukan halaman login yang benar berdasarkan role
            $correctLoginPages = [
                'Admin' => '/admin/login',
                'Support' => '/support/login',
                'Customer' => '/login',
                'Department' => '/department/login'
            ];

            $correctPage = $correctLoginPages[$roleName] ?? '/login';

            // Kembalikan dengan pesan error yang spesifik
            $errorMessage = "Access denied. You are a <strong>{$roleName}</strong>. Please login through the ";

            if ($roleName === 'Customer') {
                $errorMessage .= "<a href='{$correctPage}' class='underline'>Customer Login Portal</a>";
            } elseif ($roleName === 'Admin') {
                $errorMessage .= "<a href='{$correctPage}' class='underline'>Admin Login Portal</a>";
            } elseif ($roleName === 'Support') {
                $errorMessage .= "<a href='{$correctPage}' class='underline'>Support Login Portal</a>";
            } elseif ($roleName === 'Department') {
                $errorMessage .= "<a href='{$correctPage}' class='underline'>Department Login Portal</a>";
            }

            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        // Set session data
        $sessionData = [
            'user_id' => $user['user_id'],
            'role_name' => $roleName,
            'department_name' => $department ? $department['department_name'] : null,
            'isLoggedIn' => true,
        ];

        session()->set($sessionData);

        // Update last login
        $this->userModel->updateLastLogin($user['user_id']);

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
                if (!$departmentName) {
                    session()->destroy();
                    return redirect()->to('/department/login')->with('error', 'Department assignment missing. Please contact administrator.');
                }

                $deptUrls = [
                    'IT Support' => 'it-support',
                    'UI/UX Support' => 'uiux-support',
                    'Technical Support' => 'technical-support',
                    'Feature Request' => 'feature-request'
                ];

                if (isset($deptUrls[$departmentName])) {
                    return redirect()->to('/department/' . $deptUrls[$departmentName] . '/dashboard');
                }

                return redirect()->to('/department/login')->with('error', 'Invalid department assignment');

            default:
                return redirect()->to('/login')->with('error', 'Invalid role assignment');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Logged out successfully');
    }

    public function forgotPassword()
    {
        return view('Auth/forgot_password');
    }

    public function processForgotPassword()
    {
        $email = $this->request->getPost('email');
        $user = $this->userModel->getUserByEmail($email);

        if ($user) {
            $resetToken = bin2hex(random_bytes(16));
            $this->userModel->updateUserResetToken($user['user_id'], $resetToken);

            // send email

            return redirect()->to('/login')->with('success', 'Password reset instructions sent to your email');
        }

        return redirect()->to('/auth/forgot_password')->with('error', 'Email not found');
    }
}