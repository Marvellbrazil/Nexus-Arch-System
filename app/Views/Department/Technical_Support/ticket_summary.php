<?= $this->extend('layouts/technical_support_layout') ?>

<?= $this->section('title') ?>Ticket Summary #<?= $ticket_id ?? 'TS-2341' ?> - Technical
Support<?= $this->endSection() ?>

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
            <a href="<?= base_url('department/technical-support/dashboard') ?>"
                class="hover:text-secondary transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="<?= base_url('department/technical-support/assigned_tickets') ?>"
                class="hover:text-secondary transition-colors">Assigned Tickets</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-secondary font-medium">Ticket Summary</span>
        </div>

        <!-- Main Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Support Ticket Summary</h1>
                    <div class="px-3 py-1 bg-secondary/10 text-secondary text-sm font-semibold rounded-full">
                        #TS<?= $ticket_id ?? '2341' ?>
                    </div>
                </div>
                <p class="text-gray-600">Support summary for customer login issue</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="<?= base_url('department/technical-support/ticket_detail/' . ($ticket_id ?? 'TS-2341')) ?>"
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
                    <i class="fas fa-user-tie text-blue-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Customer</p>
                    <p class="text-gray-800 font-bold">Alpha Corp</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-key text-purple-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Issue Type</p>
                    <p class="text-gray-800 font-bold">Login/Auth</p>
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
                    <p class="text-red-600 font-bold">Critical</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-headset text-green-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Agent</p>
                    <p class="text-green-600 font-bold">David Wilson</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Left Column - Issue Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Issue Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-secondary"></i>
                            Customer Issue Details
                        </h2>
                        <span class="text-gray-500 text-sm">
                            <i class="far fa-calendar mr-1"></i>
                            Reported: Today, 9:15 AM
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Subject -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Issue Summary</h3>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-gray-800 font-medium">Critical: All users unable to login to Alpha Corp
                                account</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Customer Report & Analysis</h3>
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <div class="text-gray-700 leading-relaxed space-y-4">
                                <p><strong>Customer Report:</strong> Alpha Corp reports complete login failure for all
                                    users across their organization. Users receiving "Invalid credentials" error despite
                                    using correct passwords. This is affecting morning business operations.</p>

                                <p><strong>Initial Diagnosis:</strong> Issue appears to be related to authentication
                                    service rather than individual user accounts. Multiple simultaneous login failures
                                    suggest system-wide authentication problem.</p>

                                <p><strong>Affected Users:</strong></p>
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>All Alpha Corp employees (250+ users)</li>
                                    <li>Business operations completely halted</li>
                                    <li>Billing department unable to process transactions</li>
                                    <li>Customer support team offline</li>
                                </ul>

                                <p><strong>Business Impact:</strong></p>
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>Critical business operations affected</li>
                                    <li>Financial transactions cannot be processed</li>
                                    <li>Customer service unavailable</li>
                                    <li>Estimated loss: $15,000/hour</li>
                                </ul>

                                <p><strong>Support Response:</strong></p>
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>First response within 15 minutes (within SLA)</li>
                                    <li>Issue escalated to IT Support for infrastructure investigation</li>
                                    <li>Authentication service restart initiated</li>
                                    <li>Password resets for affected users in progress</li>
                                </ul>

                                <p><strong>Current Status:</strong> IT Support investigating authentication service
                                    memory leak. Temporary fix applied (service restart). Monitoring ongoing with
                                    customer.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Communication Timeline -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-secondary/10 to-secondary/5">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-comment-dots text-secondary"></i>
                        Communication Timeline
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Timeline Entry 1 -->
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-800">Customer Report</span>
                                    <span class="text-gray-500 text-sm">9:15 AM</span>
                                    <span class="px-2 py-0.5 bg-red-50 text-red-700 text-xs rounded">Critical</span>
                                </div>
                                <p class="text-gray-600 text-sm">Alpha Corp reports complete login failure. Business
                                    operations affected.</p>
                            </div>
                        </div>

                        <!-- Timeline Entry 2 -->
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-headset text-green-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-800">Support Response</span>
                                    <span class="text-gray-500 text-sm">9:30 AM</span>
                                    <span class="px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">First
                                        Response</span>
                                </div>
                                <p class="text-gray-600 text-sm">Initial diagnosis completed. Issue escalated to IT
                                    Support.</p>
                            </div>
                        </div>

                        <!-- Timeline Entry 3 -->
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-server text-purple-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-800">IT Support Investigation</span>
                                    <span class="text-gray-500 text-sm">9:45 AM</span>
                                    <span
                                        class="px-2 py-0.5 bg-purple-50 text-purple-700 text-xs rounded">Escalated</span>
                                </div>
                                <p class="text-gray-600 text-sm">Authentication service memory leak identified. Service
                                    restart initiated.</p>
                            </div>
                        </div>

                        <!-- Timeline Entry 4 -->
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-sync-alt text-yellow-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-800">Status Update</span>
                                    <span class="text-gray-500 text-sm">10:30 AM</span>
                                    <span
                                        class="px-2 py-0.5 bg-yellow-50 text-yellow-700 text-xs rounded">Investigating</span>
                                </div>
                                <p class="text-gray-600 text-sm">Authentication service restarted. Some users can login.
                                    Password resets in progress.</p>
                            </div>
                        </div>

                        <!-- Timeline Entry 5 -->
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-800">Current Status</span>
                                    <span class="text-gray-500 text-sm">11:15 AM</span>
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded">In
                                        Progress</span>
                                </div>
                                <p class="text-gray-600 text-sm">80% of users can login. Remaining users receiving
                                    password resets. Monitoring ongoing.</p>
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
                        <i class="fas fa-building text-blue-600"></i>
                        Customer Information
                    </h2>
                </div>

                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            AC
                        </div>
                        <div>
                            <h3 class="text-gray-800 font-bold">Alpha Corp</h3>
                            <p class="text-gray-600 text-sm">Enterprise Client</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-industry text-gray-400 w-5"></i>
                            <span class="text-gray-700">Financial Services</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-users text-gray-400 w-5"></i>
                            <span class="text-gray-700">250+ Active Users</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-star text-gray-400 w-5"></i>
                            <span class="text-gray-700">Tier: Enterprise</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-shield-alt text-gray-400 w-5"></i>
                            <span class="text-gray-700">SLA: Critical (4h)</span>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-gray-600">Account Health</span>
                                <span class="text-yellow-600 font-medium">85%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-500 rounded-full w-[85%]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SLA & Priority Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-red-100">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-clock text-red-600"></i>
                        SLA & Priority
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">15m</div>
                                <div class="text-gray-600 text-xs">First Response</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-red-600">2h 45m</div>
                                <div class="text-gray-600 text-xs">Remaining</div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Priority</span>
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Critical</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Status</span>
                                <span
                                    class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Investigating</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Category</span>
                                <span
                                    class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">Authentication</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">SLA Type</span>
                                <span class="font-medium">Critical (4 hours)</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Support Agent</span>
                                <span class="font-medium">David Wilson</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">SLA Status</span>
                                <span class="text-green-600 font-medium">✓ On Track</span>
                            </div>
                            <div class="text-gray-500 text-xs mt-1">First response within SLA, resolution in progress
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-secondary/10 to-secondary/5">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-bolt text-secondary"></i>
                        Quick Actions
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <a href="#"
                            class="block w-full px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors font-medium text-center">
                            <i class="fas fa-arrow-up mr-2"></i>
                            Escalate to IT
                        </a>

                        <a href="#"
                            class="block w-full px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors font-medium text-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            Mark Resolved
                        </a>

                        <a href="#"
                            class="block w-full px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors font-medium text-center">
                            <i class="fas fa-phone-alt mr-2"></i>
                            Call Customer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Support Summary Complete</h3>
                <p class="text-gray-600">Review complete details or return to assigned tickets</p>
            </div>

            <div class="flex gap-3">
                <a href="<?= base_url('department/technical-support/assigned_tickets') ?>"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Back to Assigned
                </a>
                <a href="<?= base_url('department/technical-support/ticket_detail/' . ($ticket_id ?? 'TS-2341')) ?>"
                    class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center gap-2">
                    <i class="fas fa-comments"></i>
                    Open Conversation
                </a>
            </div>
        </div>
    </div>
</div>

<!-- CSS Styles -->
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

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add hover effects to cards
        document.querySelectorAll('.bg-white.rounded-2xl, .bg-gradient-to-r').forEach(card => {
            card.classList.add('card-hover');
        });

        // Quick action buttons
        const escalateBtn = document.querySelector('a:contains("Escalate to IT")');
        if (escalateBtn) {
            escalateBtn.addEventListener('click', function (e) {
                e.preventDefault();

                if (confirm('Escalate this ticket to IT Support for further investigation?')) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Escalating...';
                    this.disabled = true;

                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-check mr-2"></i> Escalated';
                        this.classList.remove('bg-red-50', 'text-red-700');
                        this.classList.add('bg-orange-500', 'text-white');

                        showToast('Ticket escalated to IT Support', 'success');

                        // Update status in metrics
                        const statusElement = document.querySelector('.bg-blue-100.text-blue-800');
                        if (statusElement) {
                            statusElement.textContent = 'Escalated';
                            statusElement.className = 'px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full';
                        }

                        // Update timeline
                        const timeline = document.querySelector('.space-y-6');
                        if (timeline) {
                            const escalationEntry = document.createElement('div');
                            escalationEntry.className = 'flex items-start gap-3 animate-fadeInUp';
                            escalationEntry.innerHTML = `
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-arrow-up text-orange-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-800">Ticket Escalated</span>
                                    <span class="text-gray-500 text-sm">Just now</span>
                                    <span class="px-2 py-0.5 bg-orange-50 text-orange-700 text-xs rounded">Escalated</span>
                                </div>
                                <p class="text-gray-600 text-sm">Ticket escalated to IT Support for infrastructure investigation.</p>
                            </div>
                        `;
                            timeline.appendChild(escalationEntry);
                        }

                    }, 1500);
                }
            });
        }

        // Mark resolved button
        const resolveBtn = document.querySelector('a:contains("Mark Resolved")');
        if (resolveBtn) {
            resolveBtn.addEventListener('click', function (e) {
                e.preventDefault();

                if (confirm('Mark this ticket as resolved? This will notify the customer.')) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Resolving...';
                    this.disabled = true;

                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-check mr-2"></i> Resolved';
                        this.classList.remove('bg-green-50', 'text-green-700');
                        this.classList.add('bg-green-500', 'text-white');

                        showToast('Ticket marked as resolved', 'success');

                        // Update status in metrics
                        const statusElement = document.querySelector('.bg-blue-100.text-blue-800');
                        if (statusElement) {
                            statusElement.textContent = 'Resolved';
                            statusElement.className = 'px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full';
                        }

                        // Update SLA status
                        const slaElement = document.querySelector('.text-green-600.font-medium');
                        if (slaElement) {
                            slaElement.textContent = '✓ Resolved';
                        }

                        // Update timeline
                        const timeline = document.querySelector('.space-y-6');
                        if (timeline) {
                            const resolutionEntry = document.createElement('div');
                            resolutionEntry.className = 'flex items-start gap-3 animate-fadeInUp';
                            resolutionEntry.innerHTML = `
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-800">Ticket Resolved</span>
                                    <span class="text-gray-500 text-sm">Just now</span>
                                    <span class="px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">Resolved</span>
                                </div>
                                <p class="text-gray-600 text-sm">Issue resolved. All users can now login. Customer notified.</p>
                            </div>
                        `;
                            timeline.appendChild(resolutionEntry);
                        }

                    }, 1500);
                }
            });
        }

        // Call customer button
        const callBtn = document.querySelector('a:contains("Call Customer")');
        if (callBtn) {
            callBtn.addEventListener('click', function (e) {
                e.preventDefault();

                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Calling...';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-phone-alt mr-2"></i> Call Customer';
                    showToast('Calling Alpha Corp main contact...', 'info');

                    // Simulate opening call interface
                    setTimeout(() => {
                        showToast('Connected to customer service manager', 'success');
                    }, 2000);
                }, 1000);
            });
        }

        // Print button functionality
        const printBtn = document.querySelector('button:contains("Print")');
        if (printBtn) {
            printBtn.addEventListener('click', function () {
                showToast('Preparing print preview...', 'info');
                setTimeout(() => {
                    showToast('Print preview ready', 'success');
                }, 1000);
            });
        }

        // Timeline hover effects
        document.querySelectorAll('.flex.items-start.gap-3').forEach(timeline => {
            timeline.addEventListener('mouseenter', function () {
                this.classList.add('bg-gray-50', 'rounded-lg', 'p-3', '-m-3');
            });

            timeline.addEventListener('mouseleave', function () {
                this.classList.remove('bg-gray-50', 'rounded-lg', 'p-3', '-m-3');
            });
        });
    });

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