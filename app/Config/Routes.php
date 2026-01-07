<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Home::index');

// Auth Routes
$routes->get('login', function() {
    return view('Auth/login');
});

$routes->post('auth/process_login', function() {
    return redirect()->to('/dashboard')->with('success', 'Login berhasil!');
});

// Dashboard Routes
$routes->get('dashboard', function() {
    return view('Customer/dashboard');
});

// Add this route
$routes->get('dashboard/ticket_detail/(:num)', function($id) {
    // You can pass ticket ID to the view if needed
    $data['ticket_id'] = $id;
    return view('Customer/ticket_detail', $data);
});
// Add profile route
$routes->get('dashboard/profile', function() {
    return view('Customer/profile_customer');
});

// Add create ticket routes
$routes->get('dashboard/create_ticket', function() {
    return view('Customer/create_ticket');
});

$routes->post('dashboard/tickets/create', function() {
    // Process ticket creation here
    // You can access form data via $request->getPost()
    $request = service('request');
    
    // Simulate processing
    return redirect()->to('/dashboard/my_tickets')->with('success', 'Ticket created successfully!');
});

// Add notification routes
$routes->get('dashboard/notifications', function() {
    return view('Customer/notifications');
});

$routes->get('dashboard/notifications/(:num)', function($id) {
    // Simulate viewing a specific notification
    return redirect()->to('/dashboard/notifications')->with('message', 'Viewing notification #' . $id);
});

$routes->post('dashboard/notifications/mark_read', function() {
    // Process marking notifications as read
    return redirect()->to('/dashboard/notifications')->with('success', 'Notifications marked as read');
});

$routes->post('dashboard/notifications/clear_all', function() {
    // Process clearing all notifications
    return redirect()->to('/dashboard/notifications')->with('success', 'All notifications cleared');
});
$routes->get('dashboard/create_ticket', function() {
    return view('Customer/create_ticket');
});

$routes->get('dashboard/my_tickets', function() {
    return view('Customer/my_tickets');
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
    return redirect()->to('/dashboard/my_tickets')->with('search_message', "Search results for: $search_term");
});