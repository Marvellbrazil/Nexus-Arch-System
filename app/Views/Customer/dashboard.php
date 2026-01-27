<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Customer Dashboard - NEXUS<?= $this->endSection() ?>

<?= $this->section('head') ?>
<!-- Tambahkan script untuk WebSocket -->
<script src="https://cdn.socket.io/4.5.0/socket.io.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div
    class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0">
</div>
<div
    class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0">
</div>
<div
    class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0">
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-4 md:mt-[10px] p-4 md:p-[30px] relative z-10">
    <!-- WebSocket Status Indicator -->
    <div id="wsStatus" class="fixed top-4 right-4 z-50 hidden">
        <div class="px-3 py-1 rounded-full text-xs font-medium bg-gray-800 text-white shadow-md flex items-center gap-2">
            <span class="status-dot w-2 h-2 rounded-full"></span>
            <span class="status-text">Connecting...</span>
        </div>
    </div>

    <!-- Notification Toast Container -->
    <div id="notificationContainer" class="fixed top-20 right-4 z-50 space-y-2"></div>

    <!-- Page Header -->
    <div class="mb-6 md:mb-[25px] relative">
        <div class="flex flex-col">
            <h1 class="text-2xl md:text-[35px] font-semibold mb-1 md:mb-[5px] text-text-dark">Good
                <?= $data['current_time'] ?>, <?= $data['user']['full_name'] ?>!</h1>
            <p class="text-sm md:text-[15px] font-light text-[#666]">Welcome Back to the Dashboard Area</p>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 md:mt-0 md:absolute md:right-0 md:top-0 flex flex-col sm:flex-row gap-3 md:gap-[15px]">
            <a href="<?= base_url('customer/create_ticket') ?>"
                class="px-4 md:px-[20px] py-2 md:py-[10px] rounded-lg bg-secondary text-white text-sm md:text-[14px] font-medium cursor-pointer flex items-center justify-center gap-2 transition-all duration-300 hover:bg-[#817CB2] hover:shadow-md no-underline">
                <i class="fas fa-plus"></i>
                <span>Create New Ticket</span>
            </a>
            <a href="<?= base_url('customer/my_tickets') ?>"
                class="px-4 md:px-[20px] py-2 md:py-[10px] rounded-lg bg-secondary text-white text-sm md:text-[14px] font-medium cursor-pointer flex items-center justify-center gap-2 transition-all duration-300 hover:bg-[#817CB2] hover:shadow-md no-underline">
                <i class="fas fa-ticket-alt"></i>
                <span>My Tickets</span>
            </a>
        </div>
    </div>

    <!-- Dashboard Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4 md:gap-[20px] mb-6 md:mb-[30px]">
        <!-- User Profile Card -->
        <div class="lg:col-span-3 bg-white rounded-xl p-4 md:p-[25px] shadow-sm border border-gray-200">
            <div class="flex items-center gap-3 md:gap-[15px] mb-4 md:mb-[20px]">
                <div
                    class="w-12 h-12 md:w-[50px] md:h-[50px] bg-secondary rounded-full flex items-center justify-center text-white text-lg md:text-[20px] font-bold">
                    <?= strtoupper(substr($data['user']['full_name'], 0, 1)) ?>
                </div>
                <div>
                    <h2 class="text-text-dark text-base md:text-[18px] font-semibold"><?= $data['user']['full_name'] ?>
                    </h2>
                    <div class="text-text-muted text-xs md:text-[12px]"><?= $data['user']['role'] ?></div>
                </div>
            </div>

            <div class="text-text-muted text-sm md:text-[14px] mb-4 md:mb-[20px]">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-envelope text-gray-400"></i>
                    <span class="truncate"><?= $data['user']['email'] ?></span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 md:gap-[12px]">
                <a href="<?= base_url('customer/profile') ?>"
                    class="px-4 md:px-[16px] py-2 md:py-[8px] rounded-lg bg-gray-100 text-gray-700 text-xs md:text-[12px] font-medium cursor-pointer text-center transition-colors duration-300 hover:bg-gray-200 no-underline">
                    Update Profile
                </a>
                <a href="<?= base_url('customer/logout') ?>"
                    class="px-4 md:px-[16px] py-2 md:py-[8px] rounded-lg bg-secondary text-white text-xs md:text-[12px] font-medium cursor-pointer text-center transition-colors duration-300 hover:bg-[#656099] no-underline">
                    Logout
                </a>
            </div>
        </div>

        <!-- Stat Cards -->
        <!-- Total Tickets -->
        <div
            class="lg:col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div class="text-4xl md:text-5xl font-bold mb-2"><?= $data['stats']['total_tickets'] ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-3">Total Tickets</div>
            <div class="text-white/60 text-xs md:text-[13px] flex items-center justify-center mb-4">
                <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                <span><?= $data['stats']['total_tickets_per_week'] ?> new this week</span>
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-full flex items-center justify-center mt-2">
                <i class="fas fa-ticket-alt text-lg md:text-xl"></i>
            </div>
        </div>

        <!-- Active Tickets -->
        <div
            class="lg:col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div class="text-4xl md:text-5xl font-bold mb-2"><?= $data['stats']['open_tickets'] ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-3">Open Tickets</div>
            <div class="text-white/60 text-xs md:text-[13px] mb-4">
                <?= $data['stats']['in_progress_tickets'] ?> tickets In Progress
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-full flex items-center justify-center mt-2">
                <i class="fas fa-clock text-lg md:text-xl"></i>
            </div>
        </div>

        <!-- Resolved Tickets -->
        <div
            class="lg:col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div class="text-4xl md:text-5xl font-bold mb-2"><?= $data['stats']['resolved_tickets'] ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-3">Resolved Tickets</div>
            <div class="text-white/60 text-xs md:text-[13px] mb-4">
                <?= $data['stats']['cancelled_tickets'] ?> tickets cancelled
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-full flex items-center justify-center mt-2">
                <i class="fas fa-check-circle text-lg md:text-xl"></i>
            </div>
        </div>

        <!-- Support Messages Card -->
        <div class="md:col-span-2 lg:col-span-3 bg-white rounded-xl p-4 md:p-[25px] shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-[15px]">
                <h3 class="text-text-dark text-base md:text-[18px] font-semibold">Support Messages</h3>
                <div class="w-6 h-6 bg-secondary rounded-full flex items-center justify-center">
                    <i class="fas fa-comment text-white text-xs"></i>
                </div>
            </div>
            <div class="text-gray-500 text-sm md:text-[14px] mb-4" id="supportMessageCount">No new messages</div>
            <a href="<?= base_url('customer/my_tickets') ?>"
                class="w-full px-4 md:px-[16px] py-2 md:py-[10px] rounded-lg bg-secondary text-white text-xs md:text-[12px] font-medium cursor-pointer text-center transition-colors duration-300 hover:bg-[#656099] no-underline block">
                View Tickets
            </a>
        </div>

        <!-- My Projects Section -->
        <div class="md:col-span-2 lg:col-span-9 bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4 md:mb-6">
                <h3 class="text-text-dark text-lg md:text-[20px] font-semibold">My Projects</h3>
                <span class="text-gray-500 text-sm md:text-[14px]">Click a project to view details or create
                    ticket</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                <?php if (!empty($data['projects'])): ?>
                    <?php foreach ($data['projects'] as $project): ?>
                        <div class="relative group">
                            <!-- Project Card -->
                            <div
                                class="bg-white border-2 border-gray-300 rounded-xl p-4 md:p-5 hover:border-secondary hover:shadow-lg transition-all duration-300 h-full flex flex-col">
                                <!-- Header -->
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-2 md:gap-3">
                                        <div
                                            class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                            <i class="fas fa-sheet-plastic text-white text-sm md:text-base"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center mb-1">
                                                <span class="text-xs md:text-sm font-medium px-2 py-0.5">
                                                    <?= ucfirst($project['project_name']) ?>
                                                </span>
                                                <span
                                                    class="text-xs md:text-sm font-medium px-2 py-0.5 rounded-full <?= ($project['is_active'] == 't') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                    <?= ($project['is_active'] == 't') ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </div>
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">
                                                <?= $project['ticket_count'] ?> tickets
                                            </span>
                                        </div>
                                    </div>
                                    <button class="text-gray-400 hover:text-gray-600 p-1">
                                        <i class="fas fa-ellipsis-v text-xs"></i>
                                    </button>
                                </div>

                                <!-- Project Name & Description -->
                                <div class="mb-3 flex-1">
                                    <h4 class="text-text-dark text-base md:text-lg font-semibold mb-1 line-clamp-1">
                                        <?= $project['project_name'] ?>
                                    </h4>
                                    <p class="text-gray-600 text-xs md:text-sm mb-2 line-clamp-2">
                                        <?= $project['description'] ?>
                                    </p>
                                    <div class="flex items-center text-gray-500 text-xs mt-2">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        <span>Created: <?= date('F d, Y H:i', strtotime($project['created_at'])) ?></span>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="mb-4">
                                    <div class="flex items-center justify-between text-xs mb-2">
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <span class="text-gray-500">Open:</span>
                                            <span class="font-medium text-gray-700"><?= $project['open_tickets'] ?? 0 ?></span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                            <span class="text-gray-500">Resolved:</span>
                                            <span
                                                class="font-medium text-gray-700"><?= $project['resolved_tickets'] ?? 0 ?></span>
                                        </div>
                                    </div>
                                    <!-- Progress Bar -->
                                    <?php
                                    $total = $project['ticket_count'] ?? 0;
                                    $resolved = $project['resolved_tickets'] ?? 0;
                                    $progress = $total > 0 ? round(($resolved / $total) * 100) : 0;
                                    ?>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-secondary h-1.5 rounded-full" style="width: <?= $progress ?>%"></div>
                                    </div>
                                    <div class="flex justify-between text-gray-500 text-xs mt-1">
                                        <span>Progress</span>
                                        <span><?= $progress ?>%</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2 pt-3 border-t border-gray-200">
                                    <a href="<?= base_url('customer/project_detail/' . $project['project_id']) ?>"
                                        class="flex-1 px-3 py-1.5 bg-gray-100 text-gray-700 text-xs md:text-sm rounded-lg hover:bg-gray-200 transition-colors font-medium text-center flex items-center justify-center gap-1 no-underline">
                                        <i class="fas fa-eye text-xs"></i>
                                        <span>View</span>
                                    </a>
                                    <a href="<?= base_url('customer/create_ticket?project=' . $project['project_id']) ?>"
                                        class="flex-1 px-3 py-1.5 bg-secondary text-white text-xs md:text-sm rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-center flex items-center justify-center gap-1 no-underline">
                                        <i class="fas fa-plus text-xs"></i>
                                        <span>Ticket</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Quick Info Hover Card -->
                            <div
                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white rounded-lg p-3 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 pointer-events-none">
                                <div class="text-xs mb-2 font-medium text-gray-300">Quick Info</div>
                                <div class="space-y-1.5 text-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-400">Code:</span>
                                        <span class="font-medium"><?= $project['project_code'] ?></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-400">Created At:</span>
                                        <span
                                            class="font-medium"><?= date('d-m-Y H:i:s', strtotime($project['created_at'])) ?></span>
                                    </div>
                                </div>
                                <div
                                    class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-gray-900">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                        <div class="bg-gray-100 p-4 rounded-full mb-4">
                            <i class="fas fa-list-check text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 text-lg">There is no project yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Search Tickets Card -->
        <div
            class="md:col-span-2 lg:col-span-3 bg-gradient-to-r from-secondary to-[#8A84C6] rounded-xl p-4 md:p-[25px]">
            <h3 class="text-white text-base md:text-[18px] font-semibold text-center mb-4 md:mb-6">Search Your Tickets
            </h3>
            <form id="searchForm" action="<?= base_url('customer/search_tickets') ?>" method="POST" class="w-full">
                <?= csrf_field() ?>
                <div class="relative mb-4 md:mb-6">
                    <input type="text" name="search_term"
                        class="w-full h-12 md:h-[50px] bg-white/10 border border-white/20 rounded-lg px-4 pl-12 text-white placeholder-white/60 focus:outline-none focus:border-white/40 text-sm md:text-base"
                        placeholder="Find your tickets...">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-white/60"></i>
                </div>
                <button type="submit"
                    class="w-full px-4 md:px-[13px] py-2 md:py-[10px] rounded-lg bg-white text-secondary text-sm md:text-[14px] font-semibold transition-colors duration-300 hover:bg-gray-100">
                    Search Tickets
                </button>
            </form>
        </div>

        <!-- Notifications Card -->
        <div class="md:col-span-2 lg:col-span-9 bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4 md:mb-6">
                <h3 class="text-text-dark text-lg md:text-[20px] font-semibold">Recent Notifications</h3>
                <a href="<?= base_url('customer/notifications') ?>"
                    class="text-secondary text-sm md:text-[14px] font-medium hover:text-[#665C9E]">
                    View All
                </a>
            </div>
            <div id="notificationsList" class="space-y-3 md:space-y-4">
                <?php if (!empty($data['notifications'])): ?>
                    <?php foreach ($data['notifications'] as $notification): ?>
                        <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors notification-item" 
                             data-notification-id="<?= $notification['notification_id'] ?>"
                             data-is-read="<?= $notification['is_read'] ? 'true' : 'false' ?>">
                            <div class="flex-shrink-0 mt-1">
                                <?php if (!$notification['is_read']): ?>
                                    <div class="w-2 h-2 bg-secondary rounded-full unread-indicator"></div>
                                <?php else: ?>
                                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-text-dark text-sm md:text-[14px] font-medium <?= !$notification['is_read'] ? 'font-semibold' : '' ?> truncate">
                                    <?= $notification['title'] ?>
                                </p>
                                <p class="text-gray-500 text-xs md:text-[12px] mt-1">
                                    <?= date('F d, Y H:i', strtotime($notification['created_at'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-6 md:py-8">
                        <div
                            class="w-12 h-12 md:w-16 md:h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 md:mb-4">
                            <i class="fas fa-bell text-gray-400 text-xl md:text-2xl"></i>
                        </div>
                        <div class="text-gray-500 text-sm md:text-[16px]">No recent notifications</div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-4 md:mt-6 pt-4 md:pt-6 border-t border-gray-200">
                <button id="markAllReadBtn"
                    class="w-full py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm md:text-[14px] font-medium">
                    Mark All as Read
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* WebSocket Status Styles */
    #wsStatus {
        transition: all 0.3s ease;
    }
    
    #wsStatus.connected .status-dot {
        background-color: #10B981;
        box-shadow: 0 0 10px #10B981;
        animation: pulse 2s infinite;
    }
    
    #wsStatus.disconnected .status-dot {
        background-color: #EF4444;
        box-shadow: 0 0 10px #EF4444;
    }
    
    #wsStatus.connecting .status-dot {
        background-color: #F59E0B;
        box-shadow: 0 0 10px #F59E0B;
        animation: pulse 1s infinite;
    }
    
    /* Notification Toast Styles */
    .notification-toast {
        animation: slideInRight 0.3s ease-out;
        max-width: 400px;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    /* Unread indicator animation */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.6;
            transform: scale(1.1);
        }
    }

    .unread-indicator {
        animation: pulse 2s ease-in-out infinite;
    }

    /* Line clamp for multi-line text */
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
    }

    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    /* Hover card arrow */
    .hover-card-arrow::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translate(-50%, 50%) rotate(45deg);
        width: 8px;
        height: 8px;
        background-color: #1f2937;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ==================== WEBSOCKET INITIALIZATION ====================
    const wsStatus = document.getElementById('wsStatus');
    let socket = null;
    let reconnectAttempts = 0;
    const maxReconnectAttempts = 5;
    const reconnectDelay = 3000;
    
    // Get user data from PHP session
    const userId = '<?= session()->get('user_id') ?>';
    const userRole = '<?= session()->get('role') ?? 'Customer' ?>';
    const departmentId = '<?= session()->get('department_id') ?? null ?>';
    
    function initializeWebSocket() {
        if (!userId) {
            console.warn('User ID not found, skipping WebSocket connection');
            return;
        }
        
        // WebSocket server URL - adjust based on your environment
        const socketUrl = '<?= env('WS_SERVER_URL', 'http://localhost:3000') ?>';
        
        // Initialize Socket.IO
        socket = io(socketUrl, {
            transports: ['websocket', 'polling'],
            reconnection: true,
            reconnectionAttempts: maxReconnectAttempts,
            reconnectionDelay: 1000,
            reconnectionDelayMax: 5000,
            timeout: 20000,
            auth: {
                user_id: userId,
                role: userRole,
                department_id: departmentId
            }
        });
        
        // Connection established
        socket.on('connect', () => {
            console.log('✅ WebSocket connected with ID:', socket.id);
            updateConnectionStatus('connected', 'Connected');
            reconnectAttempts = 0;
            
            // Send authentication data
            socket.emit('authenticate', {
                user_id: userId,
                role: userRole,
                department_id: departmentId
            });
        });
        
        // Authentication successful
        socket.on('authenticated', (data) => {
            console.log('✅ Authenticated:', data);
        });
        
        // Authentication failed
        socket.on('unauthorized', (error) => {
            console.error('❌ Authentication failed:', error);
            updateConnectionStatus('disconnected', 'Auth Failed');
        });
        
        // New notification received
        socket.on('new_notification', (notification) => {
            console.log('📢 New notification:', notification);
            handleNewNotification(notification);
            updateNotificationBadge();
        });
        
        // Ticket status updated
        socket.on('ticket_status_changed', (data) => {
            console.log('🔄 Ticket status changed:', data);
            showTicketStatusUpdate(data);
        });
        
        // New chat message (if you're in a ticket room)
        socket.on('new_message', (message) => {
            console.log('💬 New message:', message);
            // This would be handled in ticket_detail page
        });
        
        // Connection error
        socket.on('connect_error', (error) => {
            console.error('❌ Connection error:', error);
            updateConnectionStatus('disconnected', 'Connection Error');
        });
        
        // Disconnected
        socket.on('disconnect', (reason) => {
            console.log('🔌 Disconnected:', reason);
            updateConnectionStatus('disconnected', 'Disconnected');
            
            // Attempt to reconnect
            if (reason === 'io server disconnect') {
                setTimeout(() => {
                    if (reconnectAttempts < maxReconnectAttempts) {
                        reconnectAttempts++;
                        console.log(`🔄 Attempting to reconnect (${reconnectAttempts}/${maxReconnectAttempts})...`);
                        socket.connect();
                    }
                }, reconnectDelay);
            }
        });
        
        // Reconnecting
        socket.on('reconnecting', (attemptNumber) => {
            console.log(`🔄 Reconnecting (${attemptNumber}/${maxReconnectAttempts})...`);
            updateConnectionStatus('connecting', `Reconnecting (${attemptNumber})`);
        });
        
        // Reconnect failed
        socket.on('reconnect_failed', () => {
            console.error('❌ Reconnection failed');
            updateConnectionStatus('disconnected', 'Connection Lost');
        });
        
        // Show status after 1 second
        setTimeout(() => {
            wsStatus.classList.remove('hidden');
        }, 1000);
    }
    
    function updateConnectionStatus(status, text) {
        // Remove all status classes
        wsStatus.classList.remove('connected', 'disconnected', 'connecting');
        
        // Add current status class
        wsStatus.classList.add(status);
        
        // Update text
        const statusText = wsStatus.querySelector('.status-text');
        if (statusText) {
            statusText.textContent = text;
        }
    }
    
    // ==================== NOTIFICATION HANDLING ====================
    function handleNewNotification(notification) {
        // Show toast notification
        showNotificationToast(notification);
        
        // Update notification list if on notifications page
        updateNotificationList(notification);
        
        // Play notification sound
        playNotificationSound();
        
        // Update browser notification if permission granted
        if (Notification.permission === 'granted') {
            showBrowserNotification(notification);
        }
    }
    
    function showNotificationToast(notification) {
        const container = document.getElementById('notificationContainer');
        if (!container) return;
        
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = 'notification-toast bg-white rounded-lg shadow-lg border border-gray-200 p-4';
        toast.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-1">
                    <div class="w-3 h-3 bg-secondary rounded-full"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-semibold text-gray-800 truncate">${notification.title}</h4>
                        <button onclick="closeToast('${toastId}')" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-600 mb-2">${notification.message}</p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>${formatTimeAgo(new Date(notification.created_at))}</span>
                        ${notification.ticket_id ? 
                            `<a href="${baseUrl}/customer/ticket_detail/${notification.ticket_id}" class="text-secondary hover:underline">
                                View Ticket
                            </a>` : ''
                        }
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(toast);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            closeToast(toastId);
        }, 5000);
    }
    
    function closeToast(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }
    
    function updateNotificationList(notification) {
        const notificationsList = document.getElementById('notificationsList');
        if (!notificationsList) return;
        
        // Create new notification item
        const notificationItem = document.createElement('div');
        notificationItem.className = 'flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors notification-item';
        notificationItem.setAttribute('data-notification-id', notification.notification_id);
        notificationItem.setAttribute('data-is-read', 'false');
        
        notificationItem.innerHTML = `
            <div class="flex-shrink-0 mt-1">
                <div class="w-2 h-2 bg-secondary rounded-full unread-indicator"></div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-text-dark text-sm md:text-[14px] font-semibold truncate">
                    ${notification.title}
                </p>
                <p class="text-gray-500 text-xs md:text-[12px] mt-1">
                    ${formatTimeAgo(new Date(notification.created_at))}
                </p>
            </div>
        `;
        
        // Insert at the beginning
        notificationsList.insertBefore(notificationItem, notificationsList.firstChild);
        
        // Update notification count
        updateNotificationCount();
    }
    
    function updateNotificationCount() {
        const unreadCount = document.querySelectorAll('.notification-item[data-is-read="false"]').length;
        const messageCountElement = document.getElementById('supportMessageCount');
        
        if (messageCountElement) {
            if (unreadCount > 0) {
                messageCountElement.textContent = `${unreadCount} unread notification${unreadCount > 1 ? 's' : ''}`;
                messageCountElement.classList.remove('text-gray-500');
                messageCountElement.classList.add('text-secondary', 'font-medium');
            } else {
                messageCountElement.textContent = 'No new messages';
                messageCountElement.classList.remove('text-secondary', 'font-medium');
                messageCountElement.classList.add('text-gray-500');
            }
        }
    }
    
    function playNotificationSound() {
        // Create notification sound
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 800;
            oscillator.type = 'sine';
            
            gainNode.gain.setValueAtTime(0, audioContext.currentTime);
            gainNode.gain.linearRampToValueAtTime(0.1, audioContext.currentTime + 0.01);
            gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.2);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.2);
        } catch (error) {
            console.log('Audio context not supported');
        }
    }
    
    function showBrowserNotification(notification) {
        if (!('Notification' in window)) return;
        
        const options = {
            body: notification.message,
            icon: '/favicon.ico',
            badge: '/favicon.ico',
            tag: 'nexus-notification',
            requireInteraction: false,
            silent: false
        };
        
        new Notification(notification.title, options);
    }
    
    function showTicketStatusUpdate(data) {
        // Show status update toast
        const container = document.getElementById('notificationContainer');
        if (!container) return;
        
        const toastId = 'status-toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = 'notification-toast bg-blue-50 border border-blue-200 rounded-lg shadow-lg p-4';
        toast.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-1">
                    <i class="fas fa-sync-alt text-blue-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-semibold text-blue-800">Ticket Status Updated</h4>
                        <button onclick="closeToast('${toastId}')" class="text-blue-400 hover:text-blue-600">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <p class="text-xs text-blue-700 mb-2">
                        Ticket #${data.ticket_id} is now <span class="font-semibold">${data.status}</span>
                    </p>
                    <div class="text-xs text-blue-600">
                        ${formatTimeAgo(new Date(data.updated_at))}
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            closeToast(toastId);
        }, 5000);
    }
    
    // ==================== HELPER FUNCTIONS ====================
    function formatTimeAgo(date) {
        const now = new Date();
        const diffMs = now - date;
        const diffSec = Math.floor(diffMs / 1000);
        const diffMin = Math.floor(diffSec / 60);
        const diffHour = Math.floor(diffMin / 60);
        const diffDay = Math.floor(diffHour / 24);
        
        if (diffSec < 60) return 'Just now';
        if (diffMin < 60) return `${diffMin} minute${diffMin > 1 ? 's' : ''} ago`;
        if (diffHour < 24) return `${diffHour} hour${diffHour > 1 ? 's' : ''} ago`;
        if (diffDay < 7) return `${diffDay} day${diffDay > 1 ? 's' : ''} ago`;
        return date.toLocaleDateString();
    }
    
    // Base URL helper
    const baseUrl = '<?= base_url() ?>';
    
    // Make closeToast globally available
    window.closeToast = closeToast;
    
    // ==================== EVENT LISTENERS ====================
    // Mark all notifications as read
    document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
        fetch('<?= base_url('customer/notifications/mark_all_read') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.setAttribute('data-is-read', 'true');
                    const indicator = item.querySelector('.unread-indicator');
                    if (indicator) {
                        indicator.style.animation = 'none';
                        indicator.style.backgroundColor = '#D1D5DB';
                    }
                    
                    const title = item.querySelector('.font-semibold');
                    if (title) {
                        title.classList.remove('font-semibold');
                        title.classList.add('font-medium');
                    }
                });
                
                updateNotificationCount();
                
                // Show success message
                showToast('All notifications marked as read', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to mark notifications as read', 'error');
        });
    });
    
    // Notification click handler
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-notification-id');
            const isRead = this.getAttribute('data-is-read') === 'true';
            
            if (!isRead) {
                // Mark as read via AJAX
                fetch(`<?= base_url('customer/notifications/mark_read/') ?>${notificationId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.setAttribute('data-is-read', 'true');
                        const indicator = this.querySelector('.unread-indicator');
                        if (indicator) {
                            indicator.style.animation = 'none';
                            indicator.style.backgroundColor = '#D1D5DB';
                        }
                        
                        const title = this.querySelector('.font-semibold');
                        if (title) {
                            title.classList.remove('font-semibold');
                            title.classList.add('font-medium');
                        }
                        
                        updateNotificationCount();
                    }
                });
            }
        });
    });
    
    // Toast function
    function showToast(message, type = 'info') {
        const container = document.getElementById('notificationContainer');
        if (!container) return;
        
        const toastId = 'alert-toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        
        toast.className = `notification-toast ${colors[type]} rounded-lg shadow-lg p-4`;
        toast.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                                  type === 'error' ? 'fa-exclamation-circle' : 
                                  type === 'warning' ? 'fa-exclamation-triangle' : 
                                  'fa-info-circle'}"></i>
                    <span class="text-sm font-medium">${message}</span>
                </div>
                <button onclick="closeToast('${toastId}')" class="text-white/80 hover:text-white">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            closeToast(toastId);
        }, 3000);
    }
    
    // Request notification permission
    if ('Notification' in window && Notification.permission === 'default') {
        // Optionally request permission on user interaction
        document.addEventListener('click', function requestPermission() {
            Notification.requestPermission().then(permission => {
                console.log('Notification permission:', permission);
            });
            document.removeEventListener('click', requestPermission);
        }, { once: true });
    }
    
    // Search form validation
    document.getElementById('searchForm')?.addEventListener('submit', function (e) {
        const searchInput = this.querySelector('input[name="search_term"]');
        if (!searchInput.value.trim()) {
            e.preventDefault();
            showToast('Please enter a search term.', 'warning');
            searchInput.focus();
        }
    });
    
    // Initialize WebSocket connection
    initializeWebSocket();
    
    // Initialize notification count
    updateNotificationCount();
    
    // Handle page visibility change
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && socket && !socket.connected) {
            console.log('Page visible, reconnecting WebSocket...');
            socket.connect();
        }
    });
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (socket) {
            socket.disconnect();
        }
    });
});
</script>
<?= $this->endSection() ?>