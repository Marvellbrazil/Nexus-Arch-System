<?= $this->extend('layouts/feature_request_layout') ?>

<?= $this->section('title') ?>Request Summary #<?= $request_id ?? 'FR-2342' ?> - Feature
Request<?= $this->endSection() ?>

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
            <a href="<?= base_url('department/feature-request/dashboard') ?>"
                class="hover:text-secondary transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="<?= base_url('department/feature-request/assigned_tickets') ?>"
                class="hover:text-secondary transition-colors">Pending Requests</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-secondary font-medium">Request Summary</span>
        </div>

        <!-- Main Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Feature Request Summary</h1>
                    <div class="px-3 py-1 bg-secondary/10 text-secondary text-sm font-semibold rounded-full">
                        #FR<?= $request_id ?? '2342' ?>
                    </div>
                </div>
                <p class="text-gray-600">Business analysis summary for feature request</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="<?= base_url('department/feature-request/ticket_detail/' . ($request_id ?? 'FR-2342')) ?>"
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
                    <i class="fas fa-lightbulb text-blue-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Feature</p>
                    <p class="text-gray-800 font-bold">Dark Mode</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-purple-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Requested By</p>
                    <p class="text-gray-800 font-bold">Marketing Team</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-flag text-orange-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Priority</p>
                    <p class="text-orange-600 font-bold">High</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">ROI</p>
                    <p class="text-green-600 font-bold">28%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Left Column - Business Analysis -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Business Analysis Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-chart-pie text-secondary"></i>
                            Business Analysis Details
                        </h2>
                        <span class="text-gray-500 text-sm">
                            <i class="far fa-calendar mr-1"></i>
                            Analyzed: Today, 9:30 AM
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Subject -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Feature Summary</h3>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-gray-800 font-medium">Dark Mode implementation for user dashboard interface
                            </p>
                        </div>
                    </div>

                    <!-- Analysis Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Business Analysis</h3>
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <div class="text-gray-700 leading-relaxed space-y-4">
                                <p><strong>Feature Description:</strong> Implementation of dark mode theme option across
                                    the user dashboard interface to improve accessibility and reduce eye strain for
                                    users working in low-light environments.</p>

                                <p><strong>Market Research:</strong> User survey conducted with 500+ participants shows
                                    78% of users would enable dark mode if available. Industry analysis indicates dark
                                    mode features can improve user engagement by 25-35% in applications with frequent
                                    nighttime usage.</p>

                                <p><strong>Key Benefits:</strong></p>
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>Improved accessibility for users with visual sensitivities</li>
                                    <li>Reduced eye strain during extended usage periods</li>
                                    <li>Battery life improvement for mobile device users</li>
                                    <li>Competitive alignment with industry standards</li>
                                    <li>Enhanced user satisfaction and retention</li>
                                </ul>

                                <p><strong>Expected Impact:</strong></p>
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>28% increase in user engagement metrics</li>
                                    <li>15% reduction in support tickets related to eye strain</li>
                                    <li>Improved App Store ratings (estimated +0.5 stars)</li>
                                    <li>Extended user session duration by average 8 minutes</li>
                                </ul>

                                <p><strong>Implementation Scope:</strong> Requires UI/UX design phase (2 weeks) followed
                                    by frontend development (3-4 weeks). Backend changes minimal, primarily theme
                                    configuration management.</p>

                                <p><strong>Cost-Benefit Analysis:</strong> Estimated development cost $15,000 with
                                    expected annual ROI of $45,000 through improved user retention and reduced support
                                    costs.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stakeholder Feedback -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-secondary/10 to-secondary/5">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-comments text-secondary"></i>
                        Stakeholder Feedback Summary
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Product Team -->
                        <div class="flex items-start gap-3">
                            <div
                                class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-briefcase text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-800">Product Team</span>
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded">Approved</span>
                                </div>
                                <p class="text-gray-600 text-sm">"Strong user demand makes this a priority. ROI looks
                                    promising. Recommend moving forward."</p>
                            </div>
                        </div>

                        <!-- Engineering -->
                        <div class="flex items-start gap-3">
                            <div
                                class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-code text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-800">Engineering Team</span>
                                    <span class="px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">Feasible</span>
                                </div>
                                <p class="text-gray-600 text-sm">"Implementation straightforward with our design system.
                                    Estimate 3-4 weeks for 2 frontend developers."</p>
                            </div>
                        </div>

                        <!-- UI/UX Team -->
                        <div class="flex items-start gap-3">
                            <div
                                class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-paint-brush text-purple-600"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-800">UI/UX Team</span>
                                    <span class="px-2 py-0.5 bg-purple-50 text-purple-700 text-xs rounded">Design
                                        Ready</span>
                                </div>
                                <p class="text-gray-600 text-sm">"Design system supports dark mode. Wireframes
                                    completed. Accessibility considerations incorporated."</p>
                            </div>
                        </div>

                        <!-- Support Team -->
                        <div class="flex items-start gap-3">
                            <div
                                class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-headset text-yellow-600"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-800">Support Team</span>
                                    <span
                                        class="px-2 py-0.5 bg-yellow-50 text-yellow-700 text-xs rounded">Supportive</span>
                                </div>
                                <p class="text-gray-600 text-sm">"Frequent user requests for dark mode. Expect reduction
                                    in eye strain related support tickets."</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Project Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-project-diagram text-blue-600"></i>
                        Project Information
                    </h2>
                </div>

                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                            DM
                        </div>
                        <div>
                            <h3 class="text-gray-800 font-bold">Dark Mode Project</h3>
                            <p class="text-gray-600 text-sm">User Dashboard Enhancement</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-users text-gray-400 w-5"></i>
                            <span class="text-gray-700">Primary Users: All dashboard users</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-mobile-alt text-gray-400 w-5"></i>
                            <span class="text-gray-700">Platforms: Web & Mobile</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-gray-400 w-5"></i>
                            <span class="text-gray-700">Timeline: 5-6 weeks</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-dollar-sign text-gray-400 w-5"></i>
                            <span class="text-gray-700">Budget: $15,000</span>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-gray-600">Analysis Progress</span>
                                <span class="text-blue-600 font-medium">60%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full w-[60%]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Metrics Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-green-600"></i>
                        Business Metrics
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-gray-800">78%</div>
                                <div class="text-gray-600 text-xs">User Demand</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">28%</div>
                                <div class="text-gray-600 text-xs">Expected ROI</div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Priority</span>
                                <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">High</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Status</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Analysis in
                                    Progress</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Category</span>
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">User
                                    Experience</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Estimated Timeline</span>
                                <span class="font-medium">5-6 weeks</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Last Updated</span>
                                <span class="font-medium">Today, 11:15 AM</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Approval Status</span>
                                <span class="text-yellow-600 font-medium">Awaiting Approval</span>
                            </div>
                            <div class="text-gray-500 text-xs mt-1">Stakeholder review meeting scheduled</div>
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
                            class="block w-full px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors font-medium text-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            Approve Request
                        </a>

                        <a href="#"
                            class="block w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-center">
                            <i class="fas fa-comments mr-2"></i>
                            Schedule Review
                        </a>

                        <a href="#"
                            class="block w-full px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors font-medium text-center">
                            <i class="fas fa-file-download mr-2"></i>
                            Export Analysis
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
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Business Analysis Complete</h3>
                <p class="text-gray-600">Review complete details or return to pending requests</p>
            </div>

            <div class="flex gap-3">
                <a href="<?= base_url('department/feature-request/assigned_tickets') ?>"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Back to Pending
                </a>
                <a href="<?= base_url('department/feature-request/ticket_detail/' . ($request_id ?? 'FR-2342')) ?>"
                    class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center gap-2">
                    <i class="fas fa-comments"></i>
                    Open Discussion
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
        const approveBtn = document.querySelector('a:contains("Approve Request")');
        if (approveBtn) {
            approveBtn.addEventListener('click', function (e) {
                e.preventDefault();

                if (confirm('Approve this feature request for development?')) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Approving...';
                    this.disabled = true;

                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-check mr-2"></i> Approved';
                        this.classList.remove('bg-blue-50', 'text-blue-700');
                        this.classList.add('bg-green-500', 'text-white');

                        showToast('Feature request approved for development', 'success');

                        // Update status in metrics card
                        const statusElement = document.querySelector('.bg-blue-100.text-blue-800');
                        if (statusElement) {
                            statusElement.textContent = 'Approved';
                            statusElement.className = 'px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full';
                        }

                        // Update approval status
                        const approvalElement = document.querySelector('.text-yellow-600.font-medium');
                        if (approvalElement) {
                            approvalElement.textContent = '✓ Approved';
                            approvalElement.className = 'text-green-600 font-medium';
                        }

                    }, 1500);
                }
            });
        }

        // Schedule review button
        const scheduleBtn = document.querySelector('a:contains("Schedule Review")');
        if (scheduleBtn) {
            scheduleBtn.addEventListener('click', function (e) {
                e.preventDefault();

                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
                modal.innerHTML = `
                <div class="bg-white rounded-2xl w-full max-w-md animate-fadeInUp">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800">Schedule Review Meeting</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 text-sm mb-2">Meeting Date</label>
                                <input type="date" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm mb-2">Time</label>
                                <input type="time" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm mb-2">Duration</label>
                                <select class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                                    <option>30 minutes</option>
                                    <option>45 minutes</option>
                                    <option>60 minutes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        <button onclick="this.closest('.fixed').remove()" class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                            Cancel
                        </button>
                        <button onclick="scheduleMeeting()" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2]">
                            Schedule
                        </button>
                    </div>
                </div>
            `;

                document.body.appendChild(modal);
                document.body.style.overflow = 'hidden';
            });
        }

        // Export analysis button
        const exportBtn = document.querySelector('a:contains("Export Analysis")');
        if (exportBtn) {
            exportBtn.addEventListener('click', function (e) {
                e.preventDefault();

                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Exporting...';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check mr-2"></i> Exported';
                    showToast('Business analysis exported successfully', 'success');

                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-file-download mr-2"></i> Export Analysis';
                    }, 2000);
                }, 1500);
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

        // Feedback hover effects
        document.querySelectorAll('.flex.items-start.gap-3').forEach(feedback => {
            feedback.addEventListener('mouseenter', function () {
                this.classList.add('bg-gray-50', 'rounded-lg', 'p-3', '-m-3');
            });

            feedback.addEventListener('mouseleave', function () {
                this.classList.remove('bg-gray-50', 'rounded-lg', 'p-3', '-m-3');
            });
        });
    });

    function scheduleMeeting() {
        const modal = document.querySelector('.fixed.inset-0');
        const scheduleBtn = modal.querySelector('button:contains("Schedule")');

        scheduleBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Scheduling...';
        scheduleBtn.disabled = true;

        setTimeout(() => {
            modal.remove();
            document.body.style.overflow = 'auto';
            showToast('Review meeting scheduled successfully', 'success');
        }, 1500);
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