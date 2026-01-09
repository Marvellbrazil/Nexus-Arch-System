<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard();
        }
        
        // Jika belum login, redirect ke login page
        return redirect()->to('/login');
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
}