<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class BaseController extends Controller
{
    protected $helpers = ['url', 'form', 'session'];

    protected $session;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Load session
        $this->session = Services::session();
    }



    protected function checkRole($allowedRoles)
    {
        $userRole = session()->get('role_name');
        
        if (!in_array($userRole, $allowedRoles)) {
            return redirect()->to('/login')->with('error', 'Unauthorized access');
        }
    }

    protected function checkDepartment($allowedDepartments)
    {
        $userDepartment = session()->get('department_name');
        
        if (!in_array($userDepartment, $allowedDepartments)) {
            return redirect()->to('/login')->with('error', 'Unauthorized department access');
        }
    }

    protected function loadCommonData()
    {
        $data['user'] = [
            'full_name' => session()->get('full_name'),
            'email' => session()->get('email'),
            'role_name' => session()->get('role_name'),
            'department_name' => session()->get('department_name'),
            'photo_profile' => session()->get('photo_profile')
        ];
        
        return $data;
    }
}