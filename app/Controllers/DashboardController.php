<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect ke login jika belum punya role
        return redirect()->to('/login');
    }
    
    public function admin()
    {
        // Cek session/login status di sini (untuk real app)
        // Untuk demo langsung tampilkan dashboard
        
        $data = [
            'title' => 'Admin Dashboard',
            'user' => [
                'name' => 'Administrator',
                'role' => 'Admin',
                'email' => 'admin@nexus.com'
            ],
            'stats' => [
                'total_users' => 50,
                'total_tickets' => 120,
                'open_tickets' => 15,
                'resolved_tickets' => 85
            ]
        ];
        
        return view('Admin/dashboard', $data);
    }
    
    public function customer()
    {
        $data = [
            'title' => 'Customer Dashboard',
            'user' => [
                'name' => 'John Customer',
                'role' => 'Customer',
                'email' => 'customer@nexus.com'
            ],
            'stats' => [
                'my_tickets' => 5,
                'open_tickets' => 2,
                'resolved_tickets' => 3,
                'new_messages' => 1
            ]
        ];
        
        return view('Customer/dashboard', $data);
    }
    
    public function support()
    {
        $data = [
            'title' => 'Support Dashboard',
            'user' => [
                'name' => 'Jane Support',
                'role' => 'Support',
                'email' => 'support@nexus.com'
            ],
            'stats' => [
                'assigned_tickets' => 8,
                'incoming_tickets' => 3,
                'in_progress' => 5,
                'resolved_today' => 2
            ]
        ];
        
        return view('Support/dashboard', $data);
    }
    
    public function department($dept = 'it-support')
    {
        $departmentNames = [
            'it-support' => 'IT Support',
            'technical-support' => 'Technical Support',
            'uiux-support' => 'UI/UX Support',
            'feature-request' => 'Feature Request'
        ];
        
        $viewPath = "Department/" . str_replace('-', '_', ucfirst($dept)) . "/dashboard";
        
        $data = [
            'title' => $departmentNames[$dept] . ' Dashboard',
            'user' => [
                'name' => $departmentNames[$dept] . ' Staff',
                'role' => 'Department',
                'department' => $departmentNames[$dept],
                'email' => $dept . '@nexus.com'
            ],
            'stats' => [
                'assigned_tickets' => 12,
                'pending_review' => 4,
                'completed_today' => 3
            ]
        ];
        
        return view($viewPath, $data);
    }
}