<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Profile - NEXUS<?= $this->endSection() ?>

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

<?= $this->section('styles') ?>
<style>
    /* Profile specific styles */
    .profile-avatar {
        transition: all 0.3s ease;
    }

    .profile-avatar:hover {
        transform: scale(1.05);
    }

    .form-input {
        transition: all 0.3s ease;
    }

    .form-input:focus {
        border-color: #756EA4;
        box-shadow: 0 0 0 3px rgba(117, 110, 164, 0.1);
    }

    .stats-card {
        transition: transform 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .project-card {
        transition: all 0.3s ease;
    }

    .project-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Modal styles */
    .modal-overlay {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .modal-content {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Password toggle */
    .password-toggle {
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .password-toggle:hover {
        color: #756EA4;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-[77px] p-[30px] relative z-10">
    <!-- Success/Error Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg animate-slide-in">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg animate-slide-in">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-[32px] font-semibold mb-2 text-text-dark">My Profile</h1>
                <p class="text-[15px] font-light text-[#666]">Manage your account information and settings</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button onclick="confirmLogout()"
                    class="px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </div>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Profile Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 h-full flex flex-col">
                <!-- Profile Header - KURANGI MARGIN -->
                <div class="flex flex-col md:flex-row md:items-center gap-6 p-8 pb-6">
                    <!-- Avatar - PROPORTIONAL -->
                    <div class="relative" style="flex-shrink: 0;">
                        <?php if (!empty($data['user_details']['photo_profile'])): ?>
                            <img src="<?= base_url($data['user_details']['photo_profile']) ?>" alt="Profile Photo"
                                class="w-[180px] h-[180px] rounded-full object-cover border-[6px] border-white shadow-2xl profile-avatar">
                        <?php else: ?>
                            <div
                                class="w-[180px] h-[180px] bg-gradient-to-br from-secondary to-[#8A84C6] rounded-full flex items-center justify-center text-white text-[72px] font-bold border-[6px] border-white shadow-2xl profile-avatar">
                                <?= strtoupper(substr($data['user_details']['full_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>

                        <!-- Status indicator -->
                        <div
                            class="absolute bottom-5 right-5 w-10 h-10 bg-green-500 border-[3px] border-white rounded-full shadow-md">
                        </div>

                        <!-- Edit button -->
                        <button onclick="openEditModal()"
                            class="absolute bottom-0 left-0 w-12 h-12 bg-secondary text-white rounded-full flex items-center justify-center hover:bg-[#665C9E] transition-colors shadow-lg border-[3px] border-white">
                            <i class="fas fa-camera text-base"></i>
                        </button>
                    </div>

                    <!-- User Info - TAMBAHKAN PADDING -->
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <h2 class="text-3xl font-bold text-gray-800"><?= esc($data['user_details']['full_name']) ?>
                            </h2>
                            <div class="flex items-center gap-2">
                                <span
                                    class="px-4 py-1.5 bg-secondary/10 text-secondary text-sm font-semibold rounded-full">
                                    <?= $data['user_details']['role_name'] ?>
                                </span>
                                <span
                                    class="px-4 py-1.5 <?= $data['user_details']['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> text-sm font-semibold rounded-full">
                                    <?= $data['user_details']['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-base">
                                <i class="fas fa-envelope text-gray-400 text-lg"></i>
                                <span
                                    class="text-gray-700 font-medium"><?= esc($data['user_details']['email']) ?></span>
                            </div>
                            <div class="flex items-center gap-3 text-base">
                                <i class="fas fa-phone text-gray-400 text-lg"></i>
                                <span class="text-gray-700 font-medium">
                                    <?= !empty($data['user_details']['phone_number']) ? esc($data['user_details']['phone_number']) : 'Not Set' ?>
                                </span>
                            </div>
                            <div class="flex items-center gap-3 text-base">
                                <i class="fas fa-calendar-alt text-gray-400 text-lg"></i>
                                <span class="text-gray-700 font-medium">Joined
                                    <?= date('F d, Y', strtotime($data['user_details']['created_at'])) ?>
                                </span>
                            </div>
                            <?php if (!empty($data['user_details']['department_name'])): ?>
                                <div class="flex items-center gap-3 text-base">
                                    <i class="fas fa-building text-gray-400 text-lg"></i>
                                    <span
                                        class="text-gray-700 font-medium"><?= esc($data['user_details']['department_name']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Personal Information - ATUR SPACING -->
                <div class="px-8 pb-8 flex-grow">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-user text-secondary text-lg"></i>
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-gray-600 text-sm font-medium">Full Name</label>
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <span
                                    class="text-gray-800 text-base"><?= esc($data['user_details']['full_name']) ?></span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-gray-600 text-sm font-medium">Username</label>
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <span
                                    class="text-gray-800 text-base"><?= esc($data['user_details']['username']) ?></span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-gray-600 text-sm font-medium">Email Address</label>
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <span class="text-gray-800 text-base"><?= esc($data['user_details']['email']) ?></span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-gray-600 text-sm font-medium">Phone Number</label>
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <span class="text-gray-800 text-base">
                                    <?= !empty($data['user_details']['phone_number']) ? esc($data['user_details']['phone_number']) : 'Not Set' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Button - TARUH DI BAWAH -->
                <div class="px-8 pb-8 pt-6 border-t border-gray-100">
                    <button onclick="openEditModal()"
                        class="w-full py-4 bg-gradient-to-r from-secondary to-[#8A84C6] text-white rounded-xl hover:opacity-90 transition-all font-semibold text-base flex items-center justify-center gap-3 shadow-lg shadow-secondary/30">
                        <i class="fas fa-edit text-lg"></i>
                        Edit Profile Information
                    </button>
                </div>
            </div>
        </div>
        <!-- Sidebar Stats -->
        <div class="space-y-6">
            <!-- Account Stats -->
            <div class="bg-gradient-to-br from-secondary to-[#8A84C6] rounded-2xl p-6 text-white stats-card">
                <h3 class="text-lg font-semibold mb-4">Account Statistics</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Total Tickets</p>
                                <p class="text-2xl font-bold"><?= $data['stats']['total_tickets'] ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Open</p>
                                <p class="text-2xl font-bold"><?= $data['stats']['open_tickets'] ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Resolved</p>
                                <p class="text-2xl font-bold"><?= $data['stats']['resolved_tickets'] ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">This Month</p>
                                <p class="text-2xl font-bold"><?= $data['stats']['tickets_this_month'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Login -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Activity</h3>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sign-in-alt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Last Login</p>
                            <p class="text-xs text-gray-600"><?= $data['last_login_text'] ?></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Member Since</p>
                            <p class="text-xs text-gray-600">
                                <?= date('F d, Y', strtotime($data['user_details']['created_at'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Section -->
    <!-- <div class="mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-project-diagram text-secondary"></i>
                    My Projects
                </h3>
                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">
                    2 Projects
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-1">Nexus ERP - NXS-ERP</h4>
                            <p class="text-gray-600 text-sm">Enterprise resource planning system for internal operations
                                and reporting.</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cogs text-blue-600"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-ticket-alt text-gray-500"></i>
                            <span class="text-gray-700">12 tickets</span>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                            Active
                        </span>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-1">Nova E-Commerce - NVC-ECOM</h4>
                            <p class="text-gray-600 text-sm">Online shopping platform with payment gateway integration
                                and order management.</p>
                        </div>
                        <div class="w-10 h-10 bg-purple-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-purple-600"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-ticket-alt text-gray-500"></i>
                            <span class="text-gray-700">8 tickets</span>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                            Active
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Ticket Statistics -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Ticket Statistics</h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 rounded-xl p-4 text-center stats-card">
                <p class="text-gray-600 text-sm mb-2">Total Tickets</p>
                <p class="text-3xl font-bold text-gray-800"><?= $data['stats']['total_tickets'] ?></p>
            </div>

            <div class="bg-blue-50 rounded-xl p-4 text-center stats-card">
                <p class="text-blue-600 text-sm mb-2">Open</p>
                <p class="text-3xl font-bold text-blue-700"><?= $data['stats']['open_tickets'] ?></p>
            </div>

            <div class="bg-yellow-50 rounded-xl p-4 text-center stats-card">
                <p class="text-yellow-600 text-sm mb-2">In Progress</p>
                <p class="text-3xl font-bold text-yellow-700"><?= $data['stats']['in_progress_tickets'] ?></p>
            </div>

            <div class="bg-green-50 rounded-xl p-4 text-center stats-card">
                <p class="text-green-600 text-sm mb-2">Resolved</p>
                <p class="text-3xl font-bold text-green-700"><?= $data['stats']['resolved_tickets'] ?></p>
            </div>
        </div>

        <!-- Progress Bars -->
        <?php if ($data['stats']['total_tickets'] > 0): ?>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700">Open Tickets</span>
                        <span class="text-gray-600"><?= $data['stats']['open_tickets'] ?>
                            (<?= $data['ticket_percentages']['open'] ?>%)</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full"
                            style="width: <?= $data['ticket_percentages']['open'] ?>%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700">In Progress</span>
                        <span class="text-gray-600"><?= $data['stats']['in_progress_tickets'] ?>
                            (<?= $data['ticket_percentages']['in_progress'] ?>%)</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-yellow-500 rounded-full"
                            style="width: <?= $data['ticket_percentages']['in_progress'] ?>%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700">Resolved</span>
                        <span class="text-gray-600"><?= $data['stats']['resolved_tickets'] ?>
                            (<?= $data['ticket_percentages']['resolved'] ?>%)</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full"
                            style="width: <?= $data['ticket_percentages']['resolved'] ?>%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700">Closed</span>
                        <span class="text-gray-600"><?= $data['stats']['cancelled_tickets'] ?>
                            (<?= $data['ticket_percentages']['closed'] ?>%)</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gray-400 rounded-full"
                            style="width: <?= $data['ticket_percentages']['closed'] ?>%"></div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <i class="fas fa-ticket-alt text-3xl text-gray-300 mb-3"></i>
                <p class="text-gray-600">No tickets yet. Create your first ticket!</p>
                <a href="<?= base_url('customer/create_ticket') ?>"
                    class="inline-block mt-3 px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors">
                    Create Ticket
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="fixed inset-0 z-50 overflow-y-auto hidden modal-overlay">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl modal-content">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-3xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Edit Profile</h3>
                        <p class="text-gray-600 text-sm mt-1">Update your personal information and settings</p>
                    </div>
                    <button onclick="closeEditModal()"
                        class="text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Form -->
            <form id="editProfileForm" action="<?= base_url('customer/profile/update') ?>" method="POST"
                enctype="multipart/form-data" class="p-6">
                <?= csrf_field() ?>

                <div class="space-y-6">
                    <!-- Profile Photo Section -->
                    <div class="flex flex-col items-center text-center mb-8">
                        <div class="relative group mb-4">
                            <div id="avatarPreview"
                                class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-xl">
                                <?php if (!empty($data['user_details']['photo_profile'])): ?>
                                    <img src="<?= base_url($data['user_details']['photo_profile']) ?>" alt="Profile"
                                        class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-secondary to-[#8A84C6] flex items-center justify-center text-white text-4xl font-bold">
                                        <?= strtoupper(substr($data['user_details']['full_name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <label for="photoInput"
                                class="absolute inset-0 bg-black/30 rounded-full opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-opacity cursor-pointer">
                                <i class="fas fa-camera text-2xl text-white mb-2"></i>
                                <span class="text-white text-sm font-medium">Change Photo</span>
                            </label>
                            <input type="file" id="photoInput" name="photo_profile" accept="image/*" class="hidden"
                                onchange="previewImage(event)">
                        </div>
                        <p class="text-gray-500 text-sm">Upload a clear photo of yourself. JPG, PNG or GIF. Max 5MB.</p>
                    </div>

                    <!-- Personal Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="space-y-2">
                            <label for="full_name" class="block text-gray-700 text-sm font-semibold">
                                Full Name *
                            </label>
                            <input type="text" id="full_name" name="full_name"
                                value="<?= esc($data['user_details']['full_name']) ?>"
                                class="w-full px-4 py-3.5 bg-gray-50 border border-gray-300 rounded-xl form-input focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary"
                                placeholder="Enter your full name" required>
                        </div>

                        <!-- Username (Read Only) -->
                        <div class="space-y-2">
                            <label class="block text-gray-700 text-sm font-semibold">
                                Username
                            </label>
                            <div class="px-4 py-3.5 bg-gray-100 border border-gray-300 rounded-xl">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-gray-800 font-medium"><?= esc($data['user_details']['username']) ?></span>
                                    <span class="text-xs text-gray-500">(Cannot be changed)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Email (Read Only) -->
                        <div class="space-y-2">
                            <label class="block text-gray-700 text-sm font-semibold">
                                Email Address
                            </label>
                            <div class="px-4 py-3.5 bg-gray-100 border border-gray-300 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-gray-800 font-medium">
                                            <?= esc($data['user_details']['email']) ?>
                                        </span>
                                        <span class="text-gray-500 text-xs mt-0.5">
                                            (Contact admin to change)
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="space-y-2">
                            <label for="phone_number" class="block text-gray-700 text-sm font-semibold">
                                Phone Number
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <input type="tel" id="phone_number" name="phone_number"
                                    value="<?= !empty($data['user_details']['phone_number']) ? esc($data['user_details']['phone_number']) : '' ?>"
                                    class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-300 rounded-xl form-input focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary"
                                    placeholder="081234567890">
                            </div>
                        </div>

                        <!-- Role (Read Only) -->
                        <div class="space-y-2">
                            <label class="block text-gray-700 text-sm font-semibold">
                                Role
                            </label>
                            <div class="px-4 py-3.5 bg-gray-100 border border-gray-300 rounded-xl">
                                <span class="text-gray-800 font-medium"><?= $data['user_details']['role_name'] ?></span>
                            </div>
                        </div>

                        <!-- Status (Read Only) -->
                        <div class="space-y-2">
                            <label class="block text-gray-700 text-sm font-semibold">
                                Status
                            </label>
                            <div class="px-4 py-3.5 bg-gray-100 border border-gray-300 rounded-xl">
                                <span
                                    class="<?= $data['user_details']['is_active'] ? 'text-green-600' : 'text-red-600' ?> font-medium">
                                    <?= $data['user_details']['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <div class="pt-6 mt-6 border-t border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-key text-secondary"></i>
                            Change Password (Optional)
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Current Password -->
                            <div class="space-y-2">
                                <label for="current_password" class="block text-gray-700 text-sm font-semibold">
                                    Current Password
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <input type="password" id="current_password" name="current_password"
                                        class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border border-gray-300 rounded-xl form-input focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary"
                                        placeholder="Enter current password">
                                    <button type="button"
                                        onclick="togglePassword('current_password', 'currentPasswordToggle')"
                                        id="currentPasswordToggle"
                                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 password-toggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="space-y-2">
                                <label for="new_password" class="block text-gray-700 text-sm font-semibold">
                                    New Password
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <input type="password" id="new_password" name="new_password"
                                        class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border border-gray-300 rounded-xl form-input focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary"
                                        placeholder="Enter new password">
                                    <button type="button" onclick="togglePassword('new_password', 'newPasswordToggle')"
                                        id="newPasswordToggle"
                                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 password-toggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <p class="text-gray-500 text-xs mt-1">Minimum 6 characters</p>
                            </div>

                            <!-- Confirm Password -->
                            <div class="md:col-span-2 space-y-2">
                                <label for="confirm_password" class="block text-gray-700 text-sm font-semibold">
                                    Confirm New Password
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <input type="password" id="confirm_password" name="confirm_password"
                                        class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border border-gray-300 rounded-xl form-input focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary"
                                        placeholder="Confirm new password">
                                    <button type="button"
                                        onclick="togglePassword('confirm_password', 'confirmPasswordToggle')"
                                        id="confirmPasswordToggle"
                                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 password-toggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Password Tips -->
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                            <p class="text-blue-700 text-sm font-medium mb-2">Password Tips:</p>
                            <ul class="text-blue-600 text-xs space-y-1">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-xs"></i>
                                    <span>Use at least 6 characters</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-xs"></i>
                                    <span>Include numbers and special characters</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-xs"></i>
                                    <span>Don't use common passwords</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 mt-8 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()"
                        class="px-6 py-3.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-semibold text-center">
                        Cancel
                    </button>
                    <button type="submit" id="updateProfileBtn"
                        class="flex-1 px-6 py-3.5 bg-gradient-to-r from-secondary to-[#8A84C6] text-white rounded-xl hover:opacity-90 transition-all font-semibold flex items-center justify-center gap-3 shadow-lg shadow-secondary/30">
                        <i class="fas fa-save"></i>
                        Save All Changes
                        <div id="updateSpinner"
                            class="hidden w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin">
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Logout confirmation
        window.confirmLogout = function () {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '<?= base_url('/logout') ?>';
            }
        };

        // Animate progress bars on load
        setTimeout(() => {
            document.querySelectorAll('.h-2.bg-gray-200 > div').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.transition = 'width 1s ease-in-out';
                    bar.style.width = width;
                }, 100);
            });
        }, 500);

        // Form submission
        const editForm = document.getElementById('editProfileForm');
        if (editForm) {
            editForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const submitBtn = document.getElementById('updateProfileBtn');
                const spinner = document.getElementById('updateSpinner');

                // Validate passwords if provided
                const currentPassword = document.getElementById('current_password').value;
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('confirm_password').value;

                if (newPassword || confirmPassword || currentPassword) {
                    // All password fields must be filled
                    if (!currentPassword) {
                        alert('Please enter your current password to change password.');
                        return;
                    }

                    if (!newPassword) {
                        alert('Please enter a new password.');
                        return;
                    }

                    if (newPassword !== confirmPassword) {
                        alert('New password and confirmation do not match.');
                        return;
                    }

                    if (newPassword.length < 6) {
                        alert('New password must be at least 6 characters.');
                        return;
                    }
                }

                // Show loading
                submitBtn.disabled = true;
                spinner.classList.remove('hidden');

                // Submit form
                this.submit();
            });
        }
    });

    // Modal functions
    function openEditModal() {
        document.getElementById('editProfileModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editProfileModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Image preview
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('avatarPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Password toggle
    function togglePassword(passwordFieldId, toggleButtonId) {
        const passwordField = document.getElementById(passwordFieldId);
        const toggleButton = document.getElementById(toggleButtonId);
        const icon = toggleButton.querySelector('i');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Close modal when clicking outside
    document.getElementById('editProfileModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    // Modal functions
    function openEditModal() {
        const modal = document.getElementById('editProfileModal');
        const navbar = document.querySelector('.liquid-glass-navbar');

        // Tampilkan modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Sembunyikan navbar
        if (navbar) {
            navbar.style.opacity = '0';
            navbar.style.visibility = 'hidden';
            navbar.style.pointerEvents = 'none';
        }
    }

    function closeEditModal() {
        const modal = document.getElementById('editProfileModal');
        const navbar = document.querySelector('.liquid-glass-navbar');

        // Sembunyikan modal
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';

        // Tampilkan kembali navbar
        if (navbar) {
            navbar.style.opacity = '';
            navbar.style.visibility = '';
            navbar.style.pointerEvents = '';
        }
    }
</script>
<?= $this->endSection() ?>