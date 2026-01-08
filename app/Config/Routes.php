<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', function() {
    return redirect()->to('login');
});

// Auth Routes
$routes->group('login', function($routes) {
    $routes->get('', 'UserController::loginForm');
    $routes->post('post', 'UserController::login');
    $routes->get('otp', 'UserController::otpForm');
    $routes->post('otp/post', 'UserController::otp');
});

$routes->post('auth/process_login', function() {
    return redirect()->to('/dashboard')->with('success', 'Login berhasil!');
});

// Dashboard Route - Mengarah ke folder Customer
$routes->get('dashboard', function() {
    return view('Customer/dashboard');
});

// Route untuk dashboard actions
$routes->get('dashboard/create_ticket', function() {
    return redirect()->to('/dashboard')->with('message', 'Create ticket form would open here');
});

$routes->get('dashboard/my_tickets', function() {
    return redirect()->to('/dashboard')->with('message', 'My tickets page would open here');
});

$routes->get('dashboard/view_messages', function() {
    return redirect()->to('/dashboard')->with('message', 'View messages page would open here');
});

$routes->get('dashboard/logout', function() {
    return redirect()->to('/login')->with('success', 'Logged out successfully');
});

$routes->post('dashboard/search_tickets', function() {
    $request = service('request');
    $search_term = $request->getPost('search_term');
    return redirect()->to('/dashboard')->with('search_message', "Search results for: $search_term");
});