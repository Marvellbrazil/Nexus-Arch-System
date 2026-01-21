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

// ==================== LANDING PAGE ====================
$routes->get('/', [HomeController::class, 'index']); // Landing Page

// ==================== AUTHENTICATION ====================
$routes->get('login', [AuthController::class, 'loginCustomer']); // Customer login page
$routes->post('process_login', [AuthController::class, 'processLogin']);

// Auth Routes - Login untuk setiap role
$routes->get('admin/login', [AuthController::class, 'loginAdmin']);
$routes->get('support/login', [AuthController::class, 'loginSupport']);
$routes->get('department/login', [AuthController::class, 'loginDepartment']);

$routes->get('logout', [AuthController::class, 'logout']);
$routes->get('auth/forgot_password', [AuthController::class, 'forgotPassword']);
$routes->post('auth/process_forgot_password', [AuthController::class, 'processForgotPassword']);
$routes->get('auth/proceed_otp', [AuthController::class, 'proceedOtp']);
$routes->post('auth/process_otp', [AuthController::class, 'processOtp']);
$routes->get('auth/reset_password/(:any)', [AuthController::class, 'resetPassword/$1']);
$routes->post('auth/process_reset_password', [AuthController::class, 'processResetPassword']);

// ==================== ADMIN ROUTES ====================
$routes->group('admin', function ($routes) {
    // Dashboard
    $routes->get('dashboard', [AdminController::class, 'dashboard']);

    // ==================== USER MANAGEMENT ====================
    $routes->group('users', function ($routes) {
        $routes->get('/', [AdminController::class, 'manageUsers']);
        $routes->post('ajax', 'AdminController::ajaxManageUsers');
        $routes->post('ajax-add', [AdminController::class, 'ajaxAddUser']);
        $routes->post('add', [AdminController::class, 'addUser']);
        $routes->get('ajax-list', 'AdminController::ajaxGetUsers');
        $routes->get('ajax-details/(:num)', 'AdminController::ajaxGetUserDetails/$1');
        $routes->post('edit/(:num)', 'AdminController::editUser/$1');
        $routes->post('delete/(:num)', 'AdminController::deleteUser/$1');
        $routes->post('change-status/(:num)', 'AdminController::changeStatus/$1');
        $routes->post('reset-password/(:num)', 'AdminController::resetPassword/$1');
        $routes->get('details/(:num)', 'AdminController::getUserDetails/$1');
        $routes->get('details', [AdminController::class, 'getUserDetails']);
        $routes->get('export', [AdminController::class, 'exportUsers']);
        $routes->post('update', [AdminController::class, 'updateUser']);
    });

    // ==================== ROLE MANAGEMENT ====================
    $routes->group('roles', function ($routes) {
        $routes->get('/', [AdminController::class, 'manageRoles']);
        $routes->post('save', [AdminController::class, 'saveRole']);
        $routes->post('update-permissions', [AdminController::class, 'updateRolePermissions']);
        $routes->post('delete', [AdminController::class, 'deleteRole']);
        $routes->post('duplicate', [AdminController::class, 'duplicateRole']);
        $routes->post('reset', [AdminController::class, 'resetRole']);
        $routes->post('copy-permissions', [AdminController::class, 'copyPermissions']);
        $routes->get('details/(:num)', 'AdminController::getRoleDetails/$1');
        $routes->get('details', [AdminController::class, 'getRoleDetails']);
        $routes->get('permissions/(:num)', 'AdminController::getRolePermissions/$1');
        $routes->get('permissions', [AdminController::class, 'getRolePermissions']);
    });

    // ==================== DEPARTMENT MANAGEMENT ====================
    $routes->group('departments', function ($routes) {
        $routes->get('/', [AdminController::class, 'manageDepartments']);
        $routes->post('add', [AdminController::class, 'addDepartment']);
        $routes->post('edit', [AdminController::class, 'editDepartment']);
        $routes->post('delete', [AdminController::class, 'deleteDepartment']);
        $routes->post('bulk-assign', [AdminController::class, 'bulkAssignUsersToDepartment']);
        $routes->post('remove-users', [AdminController::class, 'removeUsersFromDepartment']);
        $routes->get('details/(:num)', 'AdminController::getDepartmentDetails/$1');
        $routes->get('details', [AdminController::class, 'getDepartmentDetails']);
        $routes->get('statistics', [AdminController::class, 'getDepartmentStatistics']);
        $routes->get('users/(:num)', 'AdminController::getDepartmentUsers/$1');
        $routes->get('export', [AdminController::class, 'exportDepartments']);
        $routes->get('dropdown', [AdminController::class, 'getDepartmentDropdown']);
    });

    // ==================== TICKET MANAGEMENT ====================
    $routes->group('tickets', function ($routes) {
        $routes->get('/', [AdminController::class, 'viewTickets']);
        $routes->get('details/(:num)', 'AdminController::getTicketDetails/$1');
        $routes->get('details', [AdminController::class, 'getTicketDetails']);
        $routes->get('statistics', [AdminController::class, 'getTicketStatistics']);
        $routes->get('export', [AdminController::class, 'exportTickets']);
    });

    // ==================== PROJECT MANAGEMENT ====================
    $routes->group('projects', function ($routes) {
        // Main pages - GET
        $routes->get('/', [AdminController::class, 'manageProjects']);
        $routes->get('details/(:num)', 'AdminController::getProjectDetails/$1');
        $routes->get('details', [AdminController::class, 'getProjectDetails']);
        $routes->post('ajax-manage', [AdminController::class, 'ajaxManageProjects']);
        $routes->post('ajax-get-users-for-assignment', [AdminController::class, 'ajaxGetUsersForAssignment']);

        // ==================== STANDARD FORM SUBMISSIONS ====================
        // Untuk non-AJAX submissions (fallback)
        $routes->post('add', [AdminController::class, 'addProject']);
        $routes->post('edit', [AdminController::class, 'editProject']);
        $routes->post('delete', [AdminController::class, 'deleteProject']);
        $routes->post('change-status', [AdminController::class, 'changeProjectStatus']);
        $routes->post('import', [AdminController::class, 'importProjects']); // Process import
    });

    $routes->group('assignments', function ($routes) {
        $routes->get('/', [AdminController::class, 'viewAssignments']);
        $routes->get('export', [AdminController::class, 'exportAssignments']);
    });

    $routes->get('settings', [AdminController::class, 'systemSettings']);
    $routes->get('logout', [AuthController::class, 'logout']);
});

// ==================== CUSTOMER ROUTES ====================
$routes->group('customer', function ($routes) {
    $routes->get('', function () {
        return redirect()->to('customer/dashboard');
    });

    // Dashboard & Profile
    $routes->get('dashboard', [CustomerController::class, 'dashboard']);
    $routes->get('profile', [CustomerController::class, 'profile']);
    $routes->post('profile/update', [CustomerController::class, 'updateProfile']);

    // Tickets
    $routes->get('my_tickets', [CustomerController::class, 'myTickets']);
    $routes->get('ticket_detail/(:num)', 'CustomerController::ticketDetail/$1');
    $routes->get('create_ticket', [CustomerController::class, 'createTicket']);
    $routes->post('process_create_ticket', [CustomerController::class, 'processCreateTicket']);

    // Projects
    $routes->get('project_detail/(:num)', 'CustomerController::projectDetail/$1');

    // Notifications
    $routes->get('notifications', [CustomerController::class, 'notifications']);
    $routes->get('notifications/mark_all_read', [CustomerController::class, 'markAllRead']);

    // CHAT ROUTES - DITAMBAHKAN DISINI
    $routes->post('chat/send', 'CustomerController::sendMessage');
    $routes->get('chat/get-new', 'CustomerController::getNewMessages');
    $routes->post('chat/upload-attachment', 'CustomerController::uploadAttachment');

    $routes->get('logout', [AuthController::class, 'logout']);
});

// ==================== SUPPORT ROUTES ====================
$routes->group('support', function ($routes) {
    // GET routes
    $routes->get('dashboard', [SupportController::class, 'dashboard']);
    $routes->get('incoming', [SupportController::class, 'incomingTickets']);
    $routes->get('ticket_detail/(:num)', [SupportController::class, 'ticketDetail/$1']);
    $routes->get('ticket_summary/(:num)', [SupportController::class, 'ticketSummary/$1']);
    $routes->get('ticket_in_progress', [SupportController::class, 'ticketInProgress']);
    $routes->get('department_conversation/(:num)', [SupportController::class, 'departmentConversation/$1']);
    $routes->get('profile', [SupportController::class, 'profile']);

    // PERBAIKAN: Gunakan SupportController (yang sudah ada) bukan DepartmentTicketController
    $routes->get('department_ticket_detail/(:num)', [SupportController::class, 'departmentTicketDetail/$1']);
    $routes->post('department_chat/send/(:num)', [SupportController::class, 'sendDepartmentMessage/$1']);
    $routes->get('department_chat/get_new/(:num)', [SupportController::class, 'getNewDepartmentMessages/$1']);
    $routes->post('department_ticket/update_status/(:num)', [SupportController::class, 'updateDepartmentTicketStatus/$1']);
    // Internal Status Management
    $routes->post('update_internal_status/(:num)', [SupportController::class, 'updateInternalStatus/$1']);
    $routes->get('internal_status_info/(:num)', [SupportController::class, 'getInternalStatusInfo/$1']);

        // ==================== INTERNAL DEPARTMENT CHAT ====================
    $routes->group('internal_chat', function ($routes) {
        $routes->post('send/(:num)', [SupportController::class, 'sendInternalMessage/$1']);
        $routes->get('messages/(:num)', [SupportController::class, 'getInternalMessages/$1']);
        $routes->get('get_new/(:num)', [SupportController::class, 'getNewInternalMessages/$1']);
        $routes->post('upload_attachment/(:num)', [SupportController::class, 'uploadInternalAttachment/$1']);
    });

    // Notifications
    $routes->get('notifications', [SupportController::class, 'notifications']);
    $routes->post('notifications/mark_all_read', [SupportController::class, 'markAllRead']);
    $routes->post('notifications/mark_read', [SupportController::class, 'markNotificationRead']);
    $routes->post('notifications/load_more', [SupportController::class, 'loadMoreNotifications']);
    $routes->get('notifications/get', [SupportController::class, 'getNotifications']);
    $routes->get('notifications/unread_count', [SupportController::class, 'getUnreadCount']);

    // Ticket actions
    $routes->post('ticket/assign/(:num)', [SupportController::class, 'assignTicket/$1']);
    $routes->post('ticket/forward/(:num)', [SupportController::class, 'forwardTicket/$1']);
    $routes->post('ticket/mark_resolved/(:num)', [SupportController::class, 'markTicketResolved/$1']);
    $routes->post('tickets/load_more', [SupportController::class, 'loadMoreTickets']);

    // Profile
    $routes->post('update_profile', [SupportController::class, 'updateProfile']);
    $routes->post('update_status', [SupportController::class, 'updateStatus']);

    // CHAT ROUTES - DITAMBAHKAN DISINI
    $routes->post('chat/send', [SupportController::class, 'sendMessage']);
    $routes->get('chat/get-new', [SupportController::class, 'getNewMessages']);
    $routes->post('chat/upload-attachment', [SupportController::class, 'uploadAttachment']);
    
    // PERBAIKAN: Hapus route DepartmentChatController yang tidak ada
    // $routes->post('department_chat/send', [DepartmentChatController::class, 'sendDepartmentMessage']);
    // $routes->get('department_chat/messages', [DepartmentChatController::class, 'getDepartmentMessages']);
    // $routes->get('department_chat/participants', [DepartmentChatController::class, 'getDepartmentParticipants']);
    // $routes->get('department_chat/recent', [DepartmentChatController::class, 'getRecentConversations']);

    // AJAX
    $routes->group('ajax', function ($routes) {
        $routes->post('update_agent_status', [SupportController::class, 'updateAgentStatus']);
        $routes->get('get_agent_stats', [SupportController::class, 'getAgentStats']);
    });

    $routes->get('logout', [AuthController::class, 'logout']);
});

// ==================== DEPARTMENT ROUTES ====================
$routes->group('department', function ($routes) {
    // General Department Chat Routes
    $routes->post('chat/send/(:num)', [DepartmentController::class, 'sendChatMessage/$1']);
    $routes->get('chat/messages/(:num)', [DepartmentController::class, 'getChatMessages/$1']);
    $routes->get('chat/get_new/(:num)', [DepartmentController::class, 'getNewChatMessages/$1']);
    
    // IT Support Department - TAMBAHKAN ROUTE UNTUK STATUS CHECKING
    $routes->group('it-support', function ($routes) {
        $routes->get('dashboard', [DepartmentController::class, 'dashboard']);
        $routes->post('ticket/assign/(:num)', [DepartmentController::class, 'assignTicketToMe/$1']);
        $routes->post('ticket/update_status/(:num)', [DepartmentController::class, 'updateTicketStatus/$1']);
        
        // Resolution routes
        $routes->post('ticket/mark_resolved/(:num)', [DepartmentController::class, 'markAsResolved/$1']);
        
        // TAMBAHKAN ROUTE UNTUK REAL-TIME STATUS CHECKING
        $routes->get('ticket/status_info/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->getTicketStatusInfo($ticketId);
        });
        $routes->get('ticket/status/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->getTicketStatus($ticketId);
        });
        $routes->get('ticket/check_status/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->checkTicketStatus($ticketId);
        });
        
        $routes->get('assigned_tickets', [DepartmentController::class, 'assignedTickets']);
        $routes->get('ticket_detail/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->ticketDetail($ticketId, 'it-support');
        });
        $routes->get('ticket_summary/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->ticketSummary($ticketId, 'it-support');
        });
        $routes->get('profile', [DepartmentController::class, 'profile']);
        $routes->get('notifications', [DepartmentController::class, 'notifications']);
        $routes->get('logout', [AuthController::class, 'logout']);
        
        // Chat routes - Department ↔ Support
        $routes->post('chat/send/(:num)', [DepartmentController::class, 'sendChatMessage/$1']);
        $routes->get('chat/messages/(:num)', [DepartmentController::class, 'getChatMessages/$1']);
        $routes->get('chat/get_new/(:num)', [DepartmentController::class, 'getNewChatMessages/$1']);
    });
    
    // Technical Support Department - TAMBAHKAN JUGA
    $routes->group('technical-support', function ($routes) {
        $routes->get('dashboard', [DepartmentController::class, 'dashboard']);
        $routes->post('ticket/assign/(:num)', [DepartmentController::class, 'assignTicketToMe/$1']);
        $routes->post('ticket/update_status/(:num)', [DepartmentController::class, 'updateTicketStatus/$1']);
        $routes->post('ticket/mark_resolved/(:num)', [DepartmentController::class, 'markAsResolved/$1']);
        
        // Status checking routes
        $routes->get('ticket/status_info/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->getTicketStatusInfo($ticketId);
        });
        $routes->get('ticket/status/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->getTicketStatus($ticketId);
        });
        
        $routes->get('assigned_tickets', [DepartmentController::class, 'assignedTickets']);
        $routes->get('ticket_detail/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->ticketDetail($ticketId, 'technical-support');
        });
        $routes->get('profile', [DepartmentController::class, 'profile']);
        $routes->get('notifications', [DepartmentController::class, 'notifications']);
        $routes->get('logout', [AuthController::class, 'logout']);
        
        // Chat routes
        $routes->post('chat/send/(:num)', [DepartmentController::class, 'sendChatMessage/$1']);
        $routes->get('chat/messages/(:num)', [DepartmentController::class, 'getChatMessages/$1']);
        $routes->get('chat/get_new/(:num)', [DepartmentController::class, 'getNewChatMessages/$1']);
    });

    // UI/UX Support Department - TAMBAHKAN JUGA
    $routes->group('uiux-support', function ($routes) {
        $routes->get('dashboard', [DepartmentController::class, 'dashboard']);
        $routes->post('ticket/assign/(:num)', [DepartmentController::class, 'assignTicketToMe/$1']);
        $routes->post('ticket/update_status/(:num)', [DepartmentController::class, 'updateTicketStatus/$1']);
        $routes->post('ticket/mark_resolved/(:num)', [DepartmentController::class, 'markAsResolved/$1']);
        
        // Status checking routes
        $routes->get('ticket/status_info/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->getTicketStatusInfo($ticketId);
        });
        $routes->get('ticket/status/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->getTicketStatus($ticketId);
        });
        
        $routes->get('assigned_tickets', [DepartmentController::class, 'assignedTickets']);
        $routes->get('ticket_detail/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->ticketDetail($ticketId, 'uiux-support');
        });
        $routes->get('profile', [DepartmentController::class, 'profile']);
        $routes->get('notifications', [DepartmentController::class, 'notifications']);
        $routes->get('logout', [AuthController::class, 'logout']);
        
        // Chat routes
        $routes->post('chat/send/(:num)', [DepartmentController::class, 'sendChatMessage/$1']);
        $routes->get('chat/messages/(:num)', [DepartmentController::class, 'getChatMessages/$1']);
        $routes->get('chat/get_new/(:num)', [DepartmentController::class, 'getNewChatMessages/$1']);
    });

    // Feature Request Department - TAMBAHKAN JUGA
    $routes->group('feature-request', function ($routes) {
        $routes->get('dashboard', [DepartmentController::class, 'dashboard']);
        $routes->post('ticket/assign/(:num)', [DepartmentController::class, 'assignTicketToMe/$1']);
        $routes->post('ticket/update_status/(:num)', [DepartmentController::class, 'updateTicketStatus/$1']);
        $routes->post('ticket/mark_resolved/(:num)', [DepartmentController::class, 'markAsResolved/$1']);
        
        // Status checking routes
        $routes->get('ticket/status_info/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->getTicketStatusInfo($ticketId);
        });
        $routes->get('ticket/status/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->getTicketStatus($ticketId);
        });
        
        $routes->get('assigned_tickets', [DepartmentController::class, 'assignedTickets']);
        $routes->get('ticket_detail/(:num)', function($ticketId) {
            $controller = new DepartmentController();
            return $controller->ticketDetail($ticketId, 'feature-request');
        });
        $routes->get('profile', [DepartmentController::class, 'profile']);
        $routes->get('notifications', [DepartmentController::class, 'notifications']);
        $routes->get('logout', [AuthController::class, 'logout']);
        
        // Chat routes
        $routes->post('chat/send/(:num)', [DepartmentController::class, 'sendChatMessage/$1']);
        $routes->get('chat/messages/(:num)', [DepartmentController::class, 'getChatMessages/$1']);
        $routes->get('chat/get_new/(:num)', [DepartmentController::class, 'getNewChatMessages/$1']);
    });
});