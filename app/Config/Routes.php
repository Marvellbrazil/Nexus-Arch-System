<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Auth;
use App\Controllers\Admin;
use App\Controllers\Customer;
use App\Controllers\Support;
use App\Controllers\Department;
use App\Controllers\Home;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', [Home::class, 'index']);

// Auth Routes
$routes->get('login', [Auth::class, 'login']);
$routes->post('process_login', [Auth::class, 'processLogin']);
$routes->get('logout', [Auth::class, 'logout']);
$routes->get('auth/forgot_password', [Auth::class, 'forgotPassword']);
$routes->post('auth/process_forgot_password', [Auth::class, 'processForgotPassword']);

// Admin Routes
$routes->group('admin', function($routes) {
    $routes->get('dashboard', [Admin::class, 'dashboard']);
    
    // Manage Users
    $routes->get('users', [Admin::class, 'manageUsers']);
    $routes->post('users/add', [Admin::class, 'addUser']);
    $routes->post('users/edit/(:num)', [Admin::class, 'editUser/$1']);
    $routes->post('users/reset-password/(:num)', [Admin::class, 'resetPassword/$1']);
    $routes->post('users/change-status/(:num)', [Admin::class, 'changeStatus/$1']);
    $routes->post('users/delete/(:num)', [Admin::class, 'deleteUser/$1']);
    
    $routes->get('roles', [Admin::class, 'manageRoles']);
    $routes->get('departments', [Admin::class, 'manageDepartments']);
    $routes->get('tickets', [Admin::class, 'viewTickets']);
    $routes->get('settings', [Admin::class, 'systemSettings']);
});

// Customer Routes
$routes->group('customer', function($routes) {
    $routes->get('dashboard', [Customer::class, 'dashboard']);
    $routes->get('my_tickets', [Customer::class, 'myTickets']);
    $routes->get('project_detail/(:num)', [Customer::class, 'projectDetail/$1']);
    $routes->get('create_ticket', [Customer::class, 'createTicket']);
    $routes->get('ticket_detail/(:num)', [Customer::class, 'ticketDetail/$1']);
    $routes->get('profile', [Customer::class, 'profile']);
    $routes->get('notifications', [Customer::class, 'notifications']);
});

// Support Routes
$routes->group('support', function($routes) {
    $routes->get('dashboard', [Support::class, 'dashboard']);
    $routes->get('incoming', [Support::class, 'incomingTickets']);
    $routes->get('ticket_detail/(:num)', [Support::class, 'ticketDetail/$1']);
    $routes->get('ticket_summary/(:num)', [Support::class, 'ticketSummary/$1']);
    $routes->get('ticket_in_progress', [Support::class, 'ticketInProgress']);
    $routes->get('department_conversation/(:num)', [Support::class, 'departmentConversation/$1']);
    $routes->get('notifications', [Support::class, 'notifications']);
    $routes->get('profile', [Support::class, 'profile']);
    
    // POST routes
    $routes->post('notifications/mark_read', [Support::class, 'markNotificationsRead']);
    $routes->post('ticket/assign/(:num)', [Support::class, 'assignTicket/$1']);
    $routes->post('ticket/forward/(:num)', [Support::class, 'forwardTicket/$1']);
    $routes->post('ticket/mark_resolved/(:num)', [Support::class, 'markTicketResolved/$1']);
});

// Department Routes
$routes->group('department', function($routes) {
    // IT Support Department
    $routes->group('it-support', function($routes) {
        $routes->get('dashboard', [Department::class, 'dashboard']);
        $routes->get('assigned_tickets', [Department::class, 'assignedTickets']);
        $routes->get('ticket_detail/(:num)', [Department::class, 'ticketDetail/$1']);
        $routes->get('profile', [Department::class, 'profile']);
    });
    
    // Technical Support Department
    $routes->group('technical-support', function($routes) {
        $routes->get('dashboard', [Department::class, 'dashboard']);
        $routes->get('assigned_tickets', [Department::class, 'assignedTickets']);
        $routes->get('ticket_detail/(:num)', [Department::class, 'ticketDetail/$1']);
        $routes->get('profile', [Department::class, 'profile']);
    });
    
    // UI/UX Support Department
    $routes->group('uiux-support', function($routes) {
        $routes->get('dashboard', [Department::class, 'dashboard']);
        $routes->get('assigned_tickets', [Department::class, 'assignedTickets']);
        $routes->get('ticket_detail/(:num)', [Department::class, 'ticketDetail/$1']);
        $routes->get('profile', [Department::class, 'profile']);
    });
    
    // Feature Request Department
    $routes->group('feature-request', function($routes) {
        $routes->get('dashboard', [Department::class, 'dashboard']);
        $routes->get('assigned_tickets', [Department::class, 'assignedTickets']);
        $routes->get('ticket_detail/(:num)', [Department::class, 'ticketDetail/$1']);
        $routes->get('profile', [Department::class, 'profile']);
    });
});