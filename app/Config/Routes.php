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

// Default route (Customer Login)
$routes->get('/', [HomeController::class, 'index']);
$routes->get('login', [AuthController::class, 'loginCustomer']); // Customer login page
$routes->post('process_login', [AuthController::class, 'processLogin']);

// Auth Routes - Login untuk setiap role
$routes->get('admin/login', [AuthController::class, 'loginAdmin']);
$routes->get('support/login', [AuthController::class, 'loginSupport']);
$routes->get('department/login', [AuthController::class, 'loginDepartment']);

$routes->get('logout', [AuthController::class, 'logout']);
$routes->get('auth/forgot_password', [AuthController::class, 'forgotPassword']);
$routes->post('auth/process_forgot_password', [AuthController::class, 'processForgotPassword']);

// Admin Routes
$routes->group('admin', function ($routes) {
    $routes->get('dashboard', [AdminController::class, 'dashboard']);

    // Manage Users
    $routes->get('users', [AdminController::class, 'manageUsers']);
    $routes->post('users', [AdminController::class, 'manageUsers']); // AJAX endpoint for DataTable
    $routes->post('users/details', [AdminController::class, 'getUserDetails']); // AJAX endpoint for user details
    $routes->post('users/add', [AdminController::class, 'addUser']);
    $routes->post('users/edit/(:num)', 'AdminController::editUser/$1');
    $routes->post('users/reset-password/(:num)', 'AdminController::resetPassword/$1');
    $routes->post('users/change-status/(:num)', 'AdminController::changeStatus/$1');
    $routes->post('users/delete/(:num)', 'AdminController::deleteUser/$1');
    $routes->get('users/export', [AdminController::class, 'exportUsers']);

    // Manage Roles - AJAX Endpoints
    $routes->get('roles', [AdminController::class, 'manageRoles']);
    $routes->post('roles/ajax', [AdminController::class, 'manageRoles']); // AJAX handler
    $routes->post('roles/details', [AdminController::class, 'getRoleDetails']);
    $routes->post('roles/save', [AdminController::class, 'saveRole']);
    $routes->post('roles/update-permissions', [AdminController::class, 'updateRolePermissions']);
    $routes->post('roles/delete', [AdminController::class, 'deleteRole']);
    $routes->post('roles/reset', [AdminController::class, 'resetRole']);
    $routes->post('roles/duplicate', [AdminController::class, 'duplicateRole']);
    $routes->post('roles/copy-permissions', [AdminController::class, 'copyPermissions']);

    // Manage Departments - AJAX Endpoints
    $routes->get('departments', [AdminController::class, 'manageDepartments']);
    $routes->post('departments/ajax', [AdminController::class, 'manageDepartments']); // Main AJAX handler
    $routes->post('departments/data', [AdminController::class, 'getDepartmentsData']); // For DataTable
    $routes->post('departments/details', [AdminController::class, 'getDepartmentDetails']);
    $routes->post('departments/add', [AdminController::class, 'addDepartment']);
    $routes->post('departments/edit', [AdminController::class, 'editDepartment']);
    $routes->post('departments/delete', [AdminController::class, 'deleteDepartment']);
    $routes->post('departments/statistics', [AdminController::class, 'getDepartmentStatistics']);
    $routes->post('departments/check-status', [AdminController::class, 'checkDepartmentStatus']);
    $routes->post('departments/reassign-users', [AdminController::class, 'reassignDepartmentUsers']);
    $routes->post('departments/get-users', [AdminController::class, 'getDepartmentUsers']);
    $routes->post('departments/bulk-assign', [AdminController::class, 'bulkAssignUsersToDepartment']);
    $routes->post('departments/remove-users', [AdminController::class, 'removeUsersFromDepartment']);
    $routes->get('departments/export', [AdminController::class, 'exportDepartments']);
    $routes->get('departments/dropdown', [AdminController::class, 'getDepartmentDropdown']);
    $routes->post('departments/get-categories', [AdminController::class, 'getCategories']);

    $routes->get('tickets', [AdminController::class, 'viewTickets']);

    // Manage Projects - AJAX Endpoints
    $routes->get('projects', [AdminController::class, 'manageProjects']);
    $routes->post('projects/ajax', [AdminController::class, 'manageProjects']); // Main AJAX handler
    $routes->post('projects/add', [AdminController::class, 'addProject']);
    $routes->post('projects/edit/(:num)', 'AdminController::editProject/$1');
    $routes->post('projects/delete/(:num)', 'AdminController::deleteProject/$1');
    $routes->post('projects/assign-users/(:num)', 'AdminController::assignUsersToProject/$1');
    $routes->post('projects/change-status/(:num)', 'AdminController::changeProjectStatus/$1');
    $routes->post('projects/details', [AdminController::class, 'getProjectDetails']);
    $routes->post('projects/table-data', [AdminController::class, 'getProjectsTableAjax']);
    $routes->post('projects/unassigned-users', [AdminController::class, 'getUnassignedUsers']);
    $routes->post('projects/bulk-assign', [AdminController::class, 'bulkAssignProjects']);
    $routes->post('projects/assignment-stats', [AdminController::class, 'getAssignmentStatistics']);
    $routes->post('projects/remove-user', [AdminController::class, 'removeUserFromProject']);
    $routes->post('projects/clear-assignments', [AdminController::class, 'clearProjectAssignments']);
    $routes->post('projects/get-for-bulk', [AdminController::class, 'getProjectsForBulkAssign']);
    $routes->get('projects/assignments', [AdminController::class, 'viewAssignments']);
    $routes->get('projects/export-assignments', [AdminController::class, 'exportAssignments']);

    // Project Assignments
    $routes->get('assignments', [AdminController::class, 'viewAssignments']);
    $routes->get('assignments/export', [AdminController::class, 'exportAssignments']);

    // Common AJAX endpoints
    $routes->post('get-users-dropdown', [AdminController::class, 'getUsersDropdown']);
    $routes->post('get-roles-dropdown', [AdminController::class, 'getRolesDropdown']);
    $routes->post('get-projects-dropdown', [AdminController::class, 'getProjectsDropdown']);
    $routes->post('get-departments-dropdown', [AdminController::class, 'getDepartmentsDropdown']);

    $routes->get('settings', [AdminController::class, 'systemSettings']);
    $routes->get('logout', [AuthController::class, 'logout']);
});

// Customer Routes
$routes->group('customer', function ($routes) {
    $routes->get('', function () { return redirect()->to('customer/dashboard'); });
    $routes->get('dashboard', [CustomerController::class, 'dashboard']);
    $routes->get('my_tickets', [CustomerController::class, 'myTickets']);
    $routes->get('project_detail/(:num)', 'CustomerController::projectDetail/$1');
    $routes->get('create_ticket', [CustomerController::class, 'createTicket']);
    $routes->post('process_create_ticket', [CustomerController::class, 'processCreateTicket']);
    $routes->get('ticket_detail/(:num)', 'CustomerController::ticketDetail/$1');
    $routes->get('profile', [CustomerController::class, 'profile']);
    $routes->post('profile/update', [CustomerController::class, 'updateProfile']);
    $routes->get('notifications', [CustomerController::class, 'notifications']);
    $routes->get('notifications/mark_all_read', [CustomerController::class, 'markAllRead']);
    $routes->get('logout', [AuthController::class, 'logout']);
});

// Support Routes
$routes->group('support', function ($routes) {
    // GET routes
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
    $routes->post('notifications/mark_all_read', [SupportController::class, 'markAllRead']); // NEW: untuk mark all
    $routes->post('ticket/assign/(:num)', [SupportController::class, 'assignTicket/$1']);
    $routes->post('ticket/forward/(:num)', [SupportController::class, 'forwardTicket/$1']);
    $routes->post('ticket/mark_resolved/(:num)', [SupportController::class, 'markTicketResolved/$1']);
    $routes->post('tickets/load_more', [SupportController::class, 'loadMoreTickets']); // For tickets in progress
    $routes->post('update_profile', [SupportController::class, 'updateProfile']); // NEW: update profile
    $routes->post('update_status', [SupportController::class, 'updateStatus']); // NEW: update agent status

    $routes->group('notifications', function ($routes) {
        $routes->post('mark_read', [SupportController::class, 'markNotificationRead']);
        $routes->post('load_more', [SupportController::class, 'loadMoreNotifications']);
        $routes->get('get', [SupportController::class, 'getNotifications']);
        $routes->get('unread_count', [SupportController::class, 'getUnreadCount']);
    });

    $routes->group('ajax', function ($routes) {
        $routes->post('update_agent_status', [SupportController::class, 'updateAgentStatus']);
        $routes->get('get_agent_stats', [SupportController::class, 'getAgentStats']);
    });
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