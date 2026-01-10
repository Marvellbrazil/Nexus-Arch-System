<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;
use App\Models\UserModel;

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
        $userId = session()->get('user_id');
        $model = new UserModel();
        
        // Get user details 
        $data['user'] = [
            'username' => $model->where('user_id', $userId)->get()->getRowArray()['username'],
            'full_name' => $model->where('user_id', $userId)->get()->getRowArray()['full_name'],
            'email' => $model->where('user_id', $userId)->get()->getRowArray()['email'],
            'role_name' => $this->session->get('role_name'),
            'department_name' => $this->session->get('department_name'),
            'photo_profile' => $model->where('user_id', $userId)->get()->getRowArray()['photo_profile'],
        ];
        
        return $data;
    }
}