<?= $this->extend('layouts/support_layout') ?>

<?= $this->section('title') ?>Profile - NEXUS Support<?= $this->endSection() ?>

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
<?php
// Ambil inisial dari nama
function getInitials($name)
{
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    return substr($initials, 0, 2);
}

$userName = $user_details['full_name'] ?? 'Support Agent';
$userEmail = $user_details['email'] ?? 'agent@nexus.com';
$userRole = $user_details['role_name'] ?? 'Support Agent';
$department = $user_details['department_name'] ?? 'Support Department';
$phone = $user_details['phone'] ?? '09:00 - 18:00 (Mon-Fri)';
$initials = getInitials($userName);

// Format angka dengan koma
function formatNumber($num)
{
    return number_format($num);
}
?>
<div class="mt-[77px] p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-[32px] font-semibold mb-2 text-text-dark">Support Agent Profile</h1>
                <p class="text-[15px] font-light text-[#666]">Manage your support agent account and performance</p>
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-2">
        <!-- Profile Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <!-- Profile Header -->
                <div class="flex flex-col md:flex-row md:items-center gap-6 mb-8">
                    <!-- Avatar -->
                    <div class="relative">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-secondary to-[#8A84C6] rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            <?= $initials ?>
                        </div>
                        <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 border-2 border-white rounded-full"
                            id="statusIndicator"></div>
                    </div>

                    <!-- Support Agent Info -->
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <h2 class="text-2xl font-bold text-gray-800"><?= esc($userName) ?></h2>
                            <span class="px-3 py-1 bg-secondary/10 text-secondary text-sm rounded-full">
                                <?= esc($userRole) ?>
                            </span>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                                <span class="text-gray-700"><?= esc($userEmail) ?></span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-id-badge text-gray-400"></i>
                                <span class="text-gray-700">ID: <?= $support_id ?? 'SUP-2025-001' ?></span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                                <span class="text-gray-700">Joined: <?= $join_date ?? 'January 10, 2025' ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agent Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-tie text-secondary"></i>
                        Agent Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Full Name</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800"><?= esc($userName) ?></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Email Address</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800"><?= esc($userEmail) ?></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Role</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800"><?= esc($userRole) ?></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Department</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800"><?= esc($department) ?></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Phone Number</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800"><?= esc($phone) ?></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Status</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800"
                                    id="currentStatusText"><?= $current_status ?? 'Available' ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Button -->
                <div class="border-t border-gray-200 pt-6">
                    <button id="editProfileBtn"
                        class="w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-edit"></i>
                        Edit Profile Information
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="space-y-6">
            <!-- Performance Stats -->
            <div class="bg-gradient-to-br from-secondary to-[#8A84C6] rounded-2xl p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Performance Statistics</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Total Tickets</p>
                                <p class="text-2xl font-bold"><?= formatNumber($stats['total_tickets'] ?? 158) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Avg. Response</p>
                                <p class="text-2xl font-bold"><?= $stats['avg_response_time'] ?? '12m' ?></p>
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
                                <p class="text-2xl font-bold"><?= formatNumber($stats['resolved_tickets'] ?? 142) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Satisfaction</p>
                                <p class="text-2xl font-bold"><?= $stats['satisfaction_rate'] ?? 94 ?>%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity & Availability -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Activity & Availability</h3>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sign-in-alt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Last Login</p>
                            <p class="text-xs text-gray-600" id="lastLoginTime">
                                <?= $last_login ?? 'Today, ' . date('g:i A') ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Member Since</p>
                            <p class="text-xs text-gray-600"><?= $join_date ?? 'January 10, 2025' ?></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-clock text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Current Status</p>
                            <p class="text-xs text-green-600 font-medium cursor-pointer" id="statusText"
                                onclick="toggleStatus()">
                                ● <?= $current_status ?? 'Available' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="-mt-6 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-chart-line text-secondary"></i>
                            Performance Metrics (Last 30 Days)
                        </h3>
                        <span class="px-3 py-1 bg-secondary/10 text-secondary text-sm rounded-full">
                            Updated: <?= date('F j') ?>
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            <p class="text-gray-600 text-sm mb-2">Tickets Handled</p>
                            <p class="text-3xl font-bold text-gray-800">
                                <?= $metrics['tickets_handled'] ?? 42 ?>
                            </p>
                            <p
                                class="text-xs <?= ($metrics['ticket_change_percent'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' ?> mt-1">
                                <i
                                    class="fas fa-arrow-<?= ($metrics['ticket_change_percent'] ?? 0) >= 0 ? 'up' : 'down' ?> mr-1"></i>
                                <?= abs($metrics['ticket_change_percent'] ?? 8) ?>%
                                <?= ($metrics['ticket_change_percent'] ?? 0) >= 0 ? 'increase' : 'decrease' ?>
                            </p>
                        </div>

                        <div class="bg-blue-50 rounded-xl p-4 text-center">
                            <p class="text-blue-600 text-sm mb-2">Avg. Response Time</p>
                            <p class="text-3xl font-bold text-blue-700">
                                <?= $metrics['avg_response_time'] ?? '12m' ?>
                            </p>
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-arrow-down mr-1"></i>2m faster
                            </p>
                        </div>

                        <div class="bg-yellow-50 rounded-xl p-4 text-center">
                            <p class="text-yellow-600 text-sm mb-2">First Contact Resolution</p>
                            <p class="text-3xl font-bold text-yellow-700">
                                <?= $metrics['first_contact_rate'] ?? 78 ?>%
                            </p>
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-arrow-up mr-1"></i>5% increase
                            </p>
                        </div>

                        <div class="bg-green-50 rounded-xl p-4 text-center">
                            <p class="text-green-600 text-sm mb-2">Customer Satisfaction</p>
                            <p class="text-3xl font-bold text-green-700">
                                <?= $metrics['satisfaction_rate'] ?? 94 ?>%
                            </p>
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-arrow-up mr-1"></i>
                                <?= $metrics['satisfaction_change_percent'] ?? 3 ?>% increase
                            </p>
                        </div>
                    </div>

                    <!-- Performance Breakdown -->
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-700 font-medium">Ticket Resolution Rate</span>
                                <span class="text-gray-600"><?= $stats['resolution_rate'] ?? 90 ?>%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full progress-bar"
                                    data-width="<?= $stats['resolution_rate'] ?? 90 ?>%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-700 font-medium">SLA Compliance</span>
                                <span class="text-gray-600"><?= $stats['sla_compliance'] ?? 96 ?>%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full progress-bar"
                                    data-width="<?= $stats['sla_compliance'] ?? 96 ?>%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-700 font-medium">Quality Score</span>
                                <span class="text-gray-600"><?= $stats['quality_score'] ?? 88 ?>%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-purple-500 rounded-full progress-bar"
                                    data-width="<?= $stats['quality_score'] ?? 88 ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block"></div>
        </div>
    </div>
</div>

<style>
    /* Animations for progress bars */
    @keyframes fillProgress {
        from {
            width: 0;
        }

        to {
            width: var(--target-width);
        }
    }

    .progress-bar-animated {
        animation: fillProgress 1.5s ease-out forwards;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Logout confirmation
        window.confirmLogout = function () {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '<?= base_url('logout') ?>';
            }
        };

        // Animate progress bars on load
        setTimeout(() => {
            document.querySelectorAll('.progress-bar').forEach(bar => {
                const width = bar.getAttribute('data-width');
                bar.style.setProperty('--target-width', width);
                bar.style.width = '0';
                bar.classList.add('progress-bar-animated');
            });
        }, 500);

        // Status toggle functionality
        window.toggleStatus = function () {
            const statusElement = document.getElementById('statusText');
            const indicatorElement = document.getElementById('statusIndicator');
            const statusTextElement = document.getElementById('currentStatusText');

            const currentStatus = statusElement.textContent.includes('Available') ? 'Available' : 'Away';
            const newStatus = currentStatus === 'Available' ? 'Away' : 'Available';
            const newColor = newStatus === 'Available' ? 'green' : 'yellow';

            // Update status text
            statusElement.textContent = `● ${newStatus}`;
            statusElement.className = `text-xs text-${newColor}-600 font-medium cursor-pointer`;

            // Update status indicator
            indicatorElement.className = `absolute bottom-2 right-2 w-6 h-6 bg-${newColor}-500 border-2 border-white rounded-full`;

            // Update current status in agent info
            if (statusTextElement) {
                statusTextElement.textContent = newStatus;
            }

            // Update status via AJAX
            updateAgentStatus(newStatus);

            showToast(`Status changed to ${newStatus}`, 'info');
        };

        // Edit profile button
        document.getElementById('editProfileBtn').addEventListener('click', function () {
            showEditProfileModal();
        });

        // Initialize with current status
        const currentStatus = '<?= $current_status ?? "Available" ?>';
        if (currentStatus === 'Away') {
            toggleStatus(); // Switch to Away if needed
        }
    });

    function updateAgentStatus(status) {
        fetch('<?= base_url("support/update_status") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                status: status,
                csrf_token: '<?= csrf_hash() ?>'
            })
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to update status:', data.message);
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
            });
    }

    function showEditProfileModal() {
        // Create modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl w-full max-w-md animate-fadeInUp">
                <form id="editProfileForm" action="<?= base_url('support/update_profile') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-800">Edit Profile</h3>
                            <button type="button" onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Full Name</label>
                                <input type="text" name="full_name" value="<?= esc($userName) ?>" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" required>
                            </div>
                            
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Email Address</label>
                                <input type="email" name="email" value="<?= esc($userEmail) ?>" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" required>
                            </div>
                            
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Phone Number</label>
                                <input type="text" name="phone" value="<?= esc($phone) ?>" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            </div>
                            
                            <?php
                            // Get departments for dropdown
                            $db = db_connect();
                            $departments = $db->table('departments')
                                ->select('department_id, department_name')
                                ->get()
                                ->getResultArray();
                            ?>
                            
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Department</label>
                                <select name="department_id" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                                    <option value="">Select Department</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= $dept['department_id'] ?>" <?= ($dept['department_name'] === $department) ? 'selected' : '' ?>>
                                            <?= esc($dept['department_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        <button type="button" onclick="this.closest('.fixed').remove()" class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2]">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        `;

        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';

        // Handle form submission
        modal.querySelector('#editProfileForm').addEventListener('submit', function (e) {
            e.preventDefault();
            saveProfileChanges(this);
        });
    }

    function saveProfileChanges(form) {
        const saveBtn = form.querySelector('button[type="submit"]');
        const originalText = saveBtn.textContent;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        saveBtn.disabled = true;

        // Submit form data
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(html => {
                // Close modal
                document.querySelector('.fixed.inset-0').remove();
                document.body.style.overflow = 'auto';

                // Show success message
                showToast('Profile updated successfully!', 'success');

                // Reload page to see changes
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error updating profile. Please try again.', 'error');
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
    }

    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-24 right-6 p-4 rounded-xl shadow-xl z-[9999] max-w-sm animate-fadeInUp ${type === 'error' ? 'bg-red-500 text-white' : type === 'success' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white'}`;
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas ${type === 'error' ? 'fa-exclamation-circle text-xl' : type === 'success' ? 'fa-check-circle text-xl' : 'fa-info-circle text-xl'}"></i>
                <div class="flex-1">
                    <p class="font-medium">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
    }

    // Add CSS animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.5s ease-out;
        }
    `;
    document.head.appendChild(style);
</script>
<?= $this->endSection() ?>