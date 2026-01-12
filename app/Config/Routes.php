<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\CustomerController;
use App\Controllers\SupportController;
use App\Controllers\DepartmentController;
use App\Controllers\HomeController;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', [HomeController::class, 'index']);

// Auth Routes
$routes->get('login', [AuthController::class, 'login']);
$routes->post('process_login', [AuthController::class, 'processLogin']);
$routes->get('logout', [AuthController::class, 'logout']);
$routes->get('auth/forgot_password', [AuthController::class, 'forgotPassword']);
$routes->post('auth/process_forgot_password', [AuthController::class, 'processForgotPassword']);
$routes->get('auth/reset_password/(:segment)', 'AuthController::resetPassword/$1');
$routes->post('auth/reset_password/(:segment)', 'AuthController::processResetPassword/$1');
$routes->get('logout', [AuthController::class, 'logout']);

// Admin Routes
$routes->group('admin', function ($routes) {
    $routes->get('dashboard', [AdminController::class, 'dashboard']);

    // Manage Users
    $routes->get('users', [AdminController::class, 'manageUsers']);
    $routes->post('users/add', [AdminController::class, 'addUser']);
    $routes->post('users/edit/(:num)', [AdminController::class, 'editUser/$1']);
    $routes->post('users/reset-password/(:num)', [AdminController::class, 'resetPassword/$1']);
    $routes->post('users/change-status/(:num)', [AdminController::class, 'changeStatus/$1']);
    $routes->post('users/delete/(:num)', [AdminController::class, 'deleteUser/$1']);

    $routes->get('roles', [AdminController::class, 'manageRoles']);
    $routes->get('departments', [AdminController::class, 'manageDepartments']);
    $routes->get('tickets', [AdminController::class, 'viewTickets']);
    $routes->get('settings', [AdminController::class, 'systemSettings']);
    $routes->get('logout', [AuthController::class, 'logout']);
});

// Customer Routes
$routes->group('customer', function ($routes) {
    $routes->get('dashboard', [CustomerController::class, 'dashboard']);
    $routes->get('my_tickets', [CustomerController::class, 'myTickets']);
    $routes->get('project_detail/(:num)', [CustomerController::class, 'projectDetail/$1']);
    $routes->get('create_ticket', [CustomerController::class, 'createTicket']);
    $routes->post('create_ticket', [CustomerController::class, 'processCreateTicket']);
    $routes->get('ticket_detail/(:num)', [CustomerController::class, 'ticketDetail/$1']);
    $routes->get('profile', [CustomerController::class, 'profile']);
    $routes->post('profile/update', 'CustomerController::profile');
    $routes->get('notifications', [CustomerController::class, 'notifications']);
    $routes->get('logout', [AuthController::class, 'logout']);
});

// Support Routes
$routes->group('support', function ($routes) {
    $routes->get('dashboard', [SupportController::class, 'dashboard']);
    $routes->get('incoming', [SupportController::class, 'incomingTickets']);
    $routes->get('ticket_detail/(:num)', [SupportController::class, 'ticketDetail/$1']);
    $routes->get('ticket_summary/(:num)', [SupportController::class, 'ticketSummary/$1']);
    $routes->get('ticket_in_progress', [SupportController::class, 'ticketInProgress']);
    $routes->get('department_conversation/(:num)', [SupportController::class, 'departmentConversation/$1']);
    $routes->get('notifications', [SupportController::class, 'notifications']);
    $routes->get('profile', [SupportController::class, 'profile']);
    $routes->get('logout', [AuthController::class, 'logout']);

    // POST routes
    $routes->post('notifications/mark_read', [SupportController::class, 'markNotificationsRead']);
    $routes->post('ticket/assign/(:num)', [SupportController::class, 'assignTicket/$1']);
    $routes->post('ticket/forward/(:num)', [SupportController::class, 'forwardTicket/$1']);
    $routes->post('ticket/mark_resolved/(:num)', [SupportController::class, 'markTicketResolved/$1']);
});

// Department Routes
$routes->group('department', function ($routes) {
    // IT Support Department
    $routes->group('it-support', function ($routes) {
        $routes->get('dashboard', [DepartmentController::class, 'dashboard']);
        $routes->get('assigned_tickets', [DepartmentController::class, 'assignedTickets']);
        $routes->get('ticket_detail/(:num)', [DepartmentController::class, 'ticketDetail/$1']);
        $routes->get('profile', [DepartmentController::class, 'profile']);
        $routes->get('logout', [AuthController::class, 'logout']);
    });

    // Technical Support Department
    $routes->group('technical-support', function ($routes) {
        $routes->get('dashboard', [DepartmentController::class, 'dashboard']);
        $routes->get('assigned_tickets', [DepartmentController::class, 'assignedTickets']);
        $routes->get('ticket_detail/(:num)', [DepartmentController::class, 'ticketDetail/$1']);
        $routes->get('profile', [DepartmentController::class, 'profile']);
        $routes->get('logout', [AuthController::class, 'logout']);
    });

    // UI/UX Support Department
    $routes->group('uiux-support', function ($routes) {
        $routes->get('dashboard', [DepartmentController::class, 'dashboard']);
        $routes->get('assigned_tickets', [DepartmentController::class, 'assignedTickets']);
        $routes->get('ticket_detail/(:num)', [DepartmentController::class, 'ticketDetail/$1']);
        $routes->get('profile', [DepartmentController::class, 'profile']);
        $routes->get('logout', [AuthController::class, 'logout']);
    });

    // Feature Request Department
    $routes->group('feature-request', function ($routes) {
        $routes->get('dashboard', [DepartmentController::class, 'dashboard']);
        $routes->get('assigned_tickets', [DepartmentController::class, 'assignedTickets']);
        $routes->get('ticket_detail/(:num)', [DepartmentController::class, 'ticketDetail/$1']);
        $routes->get('profile', [DepartmentController::class, 'profile']);
        $routes->get('logout', [AuthController::class, 'logout']);
    });
});