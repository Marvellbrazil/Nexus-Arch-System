<?= $this->extend('layouts/support_layout') ?>

<?= $this->section('title') ?>Ticket Summary #<?= $ticket_id ?> - NEXUS Support<?= $this->endSection() ?>

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
<div class="mt-16 md:mt-[77px] p-4 md:p-6 relative z-10">
    <!-- Page Header -->
    <div class="mb-6 md:mb-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
            <a href="<?= base_url('support/dashboard') ?>" class="hover:text-secondary transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="<?= base_url('support/incoming') ?>" class="hover:text-secondary transition-colors">Incoming
                Tickets</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-secondary font-medium">Ticket Summary</span>
        </div>

        <!-- Main Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Ticket Summary</h1>
                    <div class="px-3 py-1 bg-secondary/10 text-secondary text-sm font-semibold rounded-full">
                        #T<?= $ticket_id ?>
                    </div>
                </div>
                <p class="text-gray-600">Review ticket details before forwarding to department</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <!-- Link ke ticket_detail -->
                <a href="<?= base_url('support/ticket_detail/' . $ticket_id) ?>"
                    class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all font-medium flex items-center gap-2 text-sm">
                    <i class="fas fa-external-link-alt"></i>
                    Open Full View
                </a>
                <button onclick="window.print()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all font-medium flex items-center gap-2 text-sm">
                    <i class="fas fa-print"></i>
                    Print Summary
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user text-blue-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Customer</p>
                    <p class="text-gray-800 font-bold"><?= $ticket['customer_name'] ?? 'John Smith' ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-project-diagram text-purple-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Project</p>
                    <p class="text-gray-800 font-bold"><?= $ticket['project_name'] ?? 'Project Alpha' ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-flag text-red-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Priority</p>
                    <p class="text-red-600 font-bold"><?= $ticket['priority_name'] ?? 'High' ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-green-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Status</p>
                    <p class="text-green-600 font-bold"><?= $ticket['status_name'] ?? 'Pending Review' ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Left Column - Problem Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Problem Description Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-bug text-secondary"></i>
                            Problem Description
                        </h2>
                        <span class="text-gray-500 text-sm">
                            <i class="far fa-calendar mr-1"></i>
                            Created: <?= date('M d, Y', strtotime($ticket['created_at'] ?? 'now')) ?>
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Subject -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Subject</h3>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-gray-800 font-medium"><?= $ticket['subject'] ?? 'No Subject' ?></p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Detailed Description</h3>
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <div class="text-gray-700 leading-relaxed space-y-4">
                                <p>Hi Nexus Team,</p>
                                <p>I'm having an issue logging in to <?= $ticket['project_name'] ?? 'Project Alpha' ?>.
                                    Each time I enter my email and password, the system displays an "Incorrect
                                    credentials" error message, even though I'm confident the credentials are correct.
                                </p>
                                <p>This issue started recently and persists despite trying basic troubleshooting steps
                                    such as:</p>
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>Clearing browser cache and cookies</li>
                                    <li>Using different browsers (Chrome, Firefox, Safari)</li>
                                    <li>Accessing from different devices</li>
                                    <li>Resetting password multiple times</li>
                                </ul>
                                <p>As a result, I'm unable to continue my work and would appreciate your assistance in
                                    resolving this issue as soon as possible.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-user-circle text-blue-600"></i>
                        Customer Information
                    </h2>
                </div>

                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            <?= substr($ticket['customer_name'] ?? 'JS', 0, 2) ?>
                        </div>
                        <div>
                            <h3 class="text-gray-800 font-bold"><?= $ticket['customer_name'] ?? 'John Smith' ?></h3>
                            <p class="text-gray-600 text-sm">Premium Customer</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-envelope text-gray-400 w-5"></i>
                            <span
                                class="text-gray-700"><?= $ticket['customer_email'] ?? 'john.smith@gmail.com' ?></span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-building text-gray-400 w-5"></i>
                            <span class="text-gray-700">Tech Global Inc.</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-history text-gray-400 w-5"></i>
                            <span class="text-gray-700">8 previous tickets</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-star text-gray-400 w-5"></i>
                            <span class="text-gray-700">94% satisfaction rate</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-info-circle text-secondary"></i>
                        Ticket Details
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Category</span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full">
                                <?= $ticket['category_name'] ?? 'Login Issue' ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status</span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full">
                                <?= $ticket['status_name'] ?? 'Pending Review' ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Priority</span>
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full">
                                <?= $ticket['priority_name'] ?? 'High' ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Created</span>
                            <span
                                class="font-medium"><?= date('M d, Y', strtotime($ticket['created_at'] ?? 'now')) ?></span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Last Updated</span>
                            <span
                                class="font-medium"><?= date('M d, Y h:i A', strtotime($ticket['updated_at'] ?? 'now')) ?></span>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Response SLA</span>
                                <span class="text-green-600 font-medium">✓ Within Target</span>
                            </div>
                            <div class="text-gray-500 text-xs mt-1">2h 15m remaining for initial response</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Review Complete?</h3>
                <p class="text-gray-600">Forward this ticket to the appropriate department or return to incoming tickets
                </p>
            </div>

            <div class="flex gap-3">
                <a href="<?= base_url('support/incoming') ?>"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Back to Incoming
                </a>
                <a href="<?= base_url('support/ticket_detail/' . $ticket_id) ?>"
                    class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center gap-2">
                    <i class="fas fa-comments"></i>
                    Open Conversation
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Smooth animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Card hover effects */
    .card-hover {
        transition: all 0.2s ease;
    }

    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Department option selected */
    .department-option.selected {
        border-color: #756EA4;
        background-color: rgba(117, 110, 164, 0.05);
    }

    /* Status badge pulse */
    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    .pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Department selection
        document.querySelectorAll('.department-option').forEach(option => {
            option.addEventListener('click', function () {
                // Remove selection from all
                document.querySelectorAll('.department-option').forEach(opt => {
                    opt.classList.remove('selected');
                    const icon = opt.querySelector('.w-10.h-10');
                    if (icon) {
                        icon.classList.remove('bg-secondary');
                        icon.classList.remove('text-white');
                        // Reset to original colors based on department
                        const dept = opt.dataset.department;
                        if (dept === 'technical') {
                            icon.classList.add('bg-blue-100', 'text-blue-600');
                        } else if (dept === 'it') {
                            icon.classList.add('bg-green-100', 'text-green-600');
                        } else if (dept === 'development') {
                            icon.classList.add('bg-purple-100', 'text-purple-600');
                        } else if (dept === 'qa') {
                            icon.classList.add('bg-yellow-100', 'text-yellow-600');
                        }
                    }
                });

                // Select this option
                this.classList.add('selected');
                const icon = this.querySelector('.w-10.h-10');
                if (icon) {
                    // Change to selected style
                    icon.classList.remove('bg-blue-100', 'bg-green-100', 'bg-purple-100', 'bg-yellow-100');
                    icon.classList.remove('text-blue-600', 'text-green-600', 'text-purple-600', 'text-yellow-600');
                    icon.classList.add('bg-secondary', 'text-white');
                }

                // Update button text
                const deptName = this.querySelector('.font-semibold').textContent;
                document.getElementById('assignDepartmentBtn').innerHTML = `Forward to ${deptName}`;
            });
        });

        // Assign to Department button
        const assignBtn = document.getElementById('assignDepartmentBtn');
        if (assignBtn) {
            assignBtn.addEventListener('click', function () {
                const selectedOption = document.querySelector('.department-option.selected');
                if (!selectedOption) {
                    showToast('Please select a department first', 'error');
                    return;
                }

                const deptName = selectedOption.querySelector('.font-semibold').textContent;
                const deptCode = selectedOption.dataset.department;

                if (confirm(`Forward this ticket to ${deptName}?`)) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Forwarding...';
                    this.disabled = true;

                    // Simulate API call
                    setTimeout(() => {
                        this.innerHTML = `<i class="fas fa-check"></i> Forwarded to ${deptName}`;
                        this.classList.remove('bg-secondary', 'hover:bg-[#817CB2]');
                        this.classList.add('bg-green-500', 'hover:bg-green-600');

                        // Update status
                        const statusBadges = document.querySelectorAll('.text-green-600.font-bold, .bg-yellow-100');
                        statusBadges.forEach(badge => {
                            if (badge.textContent === 'Pending Review') {
                                badge.textContent = 'Forwarded';
                                badge.classList.remove('text-green-600', 'bg-yellow-100', 'text-yellow-800');
                                badge.classList.add('text-blue-600', 'bg-blue-100');
                            }
                        });

                        showToast(`Ticket forwarded to ${deptName} successfully!`, 'success');
                    }, 1500);
                }
            });
        }

        // Add hover effects to cards
        document.querySelectorAll('.bg-white.rounded-2xl').forEach(card => {
            card.classList.add('card-hover');
        });
    });

    // Quick Forward function
    function quickForward(department) {
        const departments = {
            'technical': { name: 'Technical Support', color: 'blue' },
            'it': { name: 'IT Infrastructure', color: 'green' },
            'development': { name: 'Development', color: 'purple' }
        };

        const dept = departments[department];
        if (!dept) return;

        if (confirm(`Quick forward to ${dept.name}?`)) {
            const btn = document.getElementById('assignDepartmentBtn');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Forwarding...';
                btn.disabled = true;

                // Simulate API call
                setTimeout(() => {
                    btn.innerHTML = `<i class="fas fa-check"></i> Forwarded to ${dept.name}`;
                    btn.classList.remove('bg-secondary', 'hover:bg-[#817CB2]');
                    btn.classList.add(`bg-${dept.color}-500`, `hover:bg-${dept.color}-600`);

                    // Update status
                    const statusBadges = document.querySelectorAll('.text-green-600.font-bold, .bg-yellow-100');
                    statusBadges.forEach(badge => {
                        if (badge.textContent === 'Pending Review') {
                            badge.textContent = 'Forwarded';
                            badge.classList.remove('text-green-600', 'bg-yellow-100', 'text-yellow-800');
                            badge.classList.add(`text-${dept.color}-600`, `bg-${dept.color}-100`);
                        }
                    });

                    showToast(`Ticket forwarded to ${dept.name}!`, 'success');
                }, 1000);
            }
        }
    }

    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-24 right-6 p-4 rounded-xl shadow-xl z-[9999] max-w-sm animate-fadeInUp ${type === 'error' ? 'bg-red-500 text-white border-l-4 border-red-600' :
                type === 'success' ? 'bg-green-500 text-white border-l-4 border-green-600' :
                    'bg-blue-500 text-white border-l-4 border-blue-600'
            }`;
        toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${type === 'error' ? 'fa-exclamation-circle text-xl' :
                type === 'success' ? 'fa-check-circle text-xl' :
                    'fa-info-circle text-xl'
            }"></i>
            <div class="flex-1">
                <p class="font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

        document.body.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
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