<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;
use App\Models\UserModel;

class BaseController extends Controller
{
    protected $helpers = ['url', 'form', 'session', 'my'];
    protected $session;
    protected $userModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Load session and models
        $this->session = Services::session();
        $this->userModel = new UserModel();
    }

    protected function checkRole($allowedRoles)
    {
        $userRole = session()->get('role_name');

        if (!in_array($userRole, (array) $allowedRoles)) {
            // Log this attempt
            log_message('warning', "Unauthorized role access attempt by user_id: {session('user_id')} with role: {$userRole}");
            return redirect()->to('/login')->with('error', 'You do not have permission to access this page.');
        }
    }

    protected function checkDepartment($allowedDepartments)
    {
        $userDepartment = session()->get('department_name');

        if (!in_array($userDepartment, (array) $allowedDepartments)) {
            // Log this attempt
            log_message('warning', "Unauthorized department access attempt by user_id: {session('user_id')} for department: {$userDepartment}");
            return redirect()->to('/login')->with('error', 'You do not have access to this department section.');
        }
    }

    protected function loadCommonData()
    {
        $userId = $this->session->get('user_id');
        $data = [];

        if ($userId) {
            $userDetails = $this->userModel->getBasicUserDetails($userId);
            if ($userDetails) {
                $data['user'] = [
                    'username' => $userDetails['username'],
                    'full_name' => $userDetails['full_name'],
                    'email' => $userDetails['email'],
                    'photo_profile' => $userDetails['photo_profile'],
                    'role_name' => $this->session->get('role_name'),
                    'department_name' => $this->session->get('department_name'),
                ];
            }
        }

        // Ensure user key is set to avoid errors in views
        if (!isset($data['user'])) {
            $data['user'] = [
                'username' => 'Guest',
                'full_name' => 'Guest',
                'email' => '',
                'photo_profile' => '/uploads/profile/default.png',
                'role_name' => 'Guest',
                'department_name' => null,
            ];
        }

        return $data;
    }
}
