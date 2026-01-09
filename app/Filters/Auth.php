<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        // Check role if specified in arguments
        if (!empty($arguments)) {
            $userRole = session()->get('role_name');
            
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/login')->with('error', 'Unauthorized access');
            }
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here after controller execution
        return $response;
    }
}