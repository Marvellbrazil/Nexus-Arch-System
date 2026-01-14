<?= $this->extend('layouts/uiux_support_layout') ?>

<?= $this->section('title') ?>Ticket Summary #<?= $ticket_id ?? '10842' ?> - UI/UX Support<?= $this->endSection() ?>

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
            <a href="<?= base_url('department/ui-ux-support/dashboard') ?>"
                class="hover:text-secondary transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="<?= base_url('department/ui-ux-support/assigned_tickets') ?>"
                class="hover:text-secondary transition-colors">Assigned Tickets</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-secondary font-medium">Ticket Summary</span>
        </div>

        <!-- Main Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Design Ticket Summary</h1>
                    <div class="px-3 py-1 bg-secondary/10 text-secondary text-sm font-semibold rounded-full">
                        #T<?= $ticket_id ?? '10842' ?>
                    </div>
                </div>
                <p class="text-gray-600">Design summary for dashboard redesign ticket</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="<?= base_url('department/ui-ux-support/ticket_detail/' . ($ticket_id ?? '10842')) ?>"
                    class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all font-medium flex items-center gap-2 text-sm">
                    <i class="fas fa-external-link-alt"></i>
                    Open Design Details
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
                    <i class="fas fa-palette text-blue-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Design Phase</p>
                    <p class="text-gray-800 font-bold">Mockups</p>
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
                    <p class="text-gray-800 font-bold">Project Alpha</p>
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
                    <p class="text-red-600 font-bold">High</p>
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
                    <p class="text-green-600 font-bold">In Progress</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Left Column - Design Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Design Description Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-palette text-secondary"></i>
                            Design Request Details
                        </h2>
                        <span class="text-gray-500 text-sm">
                            <i class="far fa-calendar mr-1"></i>
                            Assigned: Today, 10:15 AM
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Subject -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Design Summary</h3>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-gray-800 font-medium">Dashboard redesign for improved user experience and
                                data visualization</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Design Analysis</h3>
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <div class="text-gray-700 leading-relaxed space-y-4">
                                <p><strong>Design Request:</strong> Complete dashboard UI/UX redesign for Project Alpha
                                </p>
                                <p><strong>User Research Findings:</strong> Analysis of user interviews and usage data
                                    revealed key pain points in the current dashboard design. Users reported difficulty
                                    in locating critical metrics, confusing navigation flows, and poor mobile
                                    responsiveness. The average task completion time increased by 45% compared to
                                    industry benchmarks.</p>
                                <p><strong>Primary Design Goals:</strong></p>
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>Improve information architecture for better content discoverability</li>
                                    <li>Enhance data visualization with intuitive charts and graphs</li>
                                    <li>Implement responsive design for mobile and tablet users</li>
                                    <li>Create cohesive design system with consistent UI patterns</li>
                                    <li>Reduce cognitive load through simplified navigation</li>
                                </ul>
                                <p><strong>Current Design Status:</strong></p>
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>Wireframes completed and approved by stakeholders</li>
                                    <li>High-fidelity mockups 75% complete</li>
                                    <li>Color scheme and typography system implemented</li>
                                    <li>Interactive prototype in development</li>
                                    <li>User testing sessions scheduled for tomorrow</li>
                                </ul>
                                <p><strong>Key Design Decisions:</strong> Adopted a card-based layout for modular
                                    content, implemented dark mode support, created custom data visualization
                                    components, and established a comprehensive design token system for future
                                    scalability.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Design Components Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-secondary/10 to-secondary/5">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-shapes text-secondary"></i>
                        Design Components
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-xl p-4">
                            <h4 class="text-blue-800 font-semibold mb-2 flex items-center gap-2">
                                <i class="fas fa-paint-brush"></i>
                                Visual Design
                            </h4>
                            <ul class="text-gray-700 text-sm space-y-1">
                                <li>• Color palette system</li>
                                <li>• Typography scale</li>
                                <li>• Icon library</li>
                                <li>• Illustration style</li>
                            </ul>
                        </div>

                        <div class="bg-green-50 rounded-xl p-4">
                            <h4 class="text-green-800 font-semibold mb-2 flex items-center gap-2">
                                <i class="fas fa-mouse-pointer"></i>
                                Interaction Design
                            </h4>
                            <ul class="text-gray-700 text-sm space-y-1">
                                <li>• Navigation flows</li>
                                <li>• Animation library</li>
                                <li>• Micro-interactions</li>
                                <li>• State transitions</li>
                            </ul>
                        </div>

                        <div class="bg-purple-50 rounded-xl p-4">
                            <h4 class="text-purple-800 font-semibold mb-2 flex items-center gap-2">
                                <i class="fas fa-chart-bar"></i>
                                Data Visualization
                            </h4>
                            <ul class="text-gray-700 text-sm space-y-1">
                                <li>• Custom chart components</li>
                                <li>• Data filtering controls</li>
                                <li>• Real-time updates</li>
                                <li>• Export functionality</li>
                            </ul>
                        </div>

                        <div class="bg-yellow-50 rounded-xl p-4">
                            <h4 class="text-yellow-800 font-semibold mb-2 flex items-center gap-2">
                                <i class="fas fa-mobile-alt"></i>
                                Responsive Design
                            </h4>
                            <ul class="text-gray-700 text-sm space-y-1">
                                <li>• Mobile-first approach</li>
                                <li>• Breakpoint system</li>
                                <li>• Touch optimization</li>
                                <li>• Accessibility features</li>
                            </ul>
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
                            class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            PA
                        </div>
                        <div>
                            <h3 class="text-gray-800 font-bold">Project Alpha</h3>
                            <p class="text-gray-600 text-sm">Dashboard Redesign</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user-friends text-gray-400 w-5"></i>
                            <span class="text-gray-700">Primary Stakeholder: Product Team</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-gray-400 w-5"></i>
                            <span class="text-gray-700">Timeline: 2 Weeks</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-users text-gray-400 w-5"></i>
                            <span class="text-gray-700">Target Users: 500+</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-tools text-gray-400 w-5"></i>
                            <span class="text-gray-700">Tools: Figma, Miro, UserTesting</span>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-gray-600">Design Progress</span>
                                <span class="text-green-600 font-medium">75%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full w-[75%]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Design Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-secondary/10 to-secondary/5">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-info-circle text-secondary"></i>
                        Design Details
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Design Phase</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">Mockups</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">In Progress</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Priority</span>
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full">High</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Category</span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full">UI
                                Redesign</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Assigned</span>
                            <span class="font-medium">Today, 10:15 AM</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Last Updated</span>
                            <span class="font-medium">Today, 1:45 PM</span>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">SLA Status</span>
                                <span class="text-green-600 font-medium">✓ On Track</span>
                            </div>
                            <div class="text-gray-500 text-xs mt-1">8h remaining for design completion</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Design Team Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-users text-green-600"></i>
                        Design Team
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-secondary to-[#8A84C6] rounded-full flex items-center justify-center text-white text-xs font-bold">
                                SW
                            </div>
                            <div>
                                <p class="text-gray-800 text-sm font-medium">Sarah Williams</p>
                                <p class="text-gray-500 text-xs">Lead UI/UX Designer</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                MJ
                            </div>
                            <div>
                                <p class="text-gray-800 text-sm font-medium">Michael Johnson</p>
                                <p class="text-gray-500 text-xs">UX Researcher</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                ER
                            </div>
                            <div>
                                <p class="text-gray-800 text-sm font-medium">Emily Rodriguez</p>
                                <p class="text-gray-500 text-xs">UI Designer</p>
                            </div>
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
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Design Summary Complete</h3>
                <p class="text-gray-600">Review complete design details or return to assigned tickets</p>
            </div>

            <div class="flex gap-3">
                <a href="<?= base_url('department/ui-ux-support/assigned_tickets') ?>"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Back to Assigned
                </a>
                <a href="<?= base_url('department/ui-ux-support/ticket_detail/' . ($ticket_id ?? '10842')) ?>"
                    class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center gap-2">
                    <i class="fas fa-comments"></i>
                    Open Design Conversation
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

    /* Timeline styling */
    .relative.pl-8.border-l-2::before {
        content: '';
        position: absolute;
        left: -2px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #10b981, #3b82f6, #8b5cf6, #f59e0b);
    }

    /* Service status dots animation */
    @keyframes servicePulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }
    }

    .bg-red-500.rounded-full {
        animation: servicePulse 2s ease-in-out infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add hover effects to cards
        document.querySelectorAll('.bg-white.rounded-2xl, .bg-gradient-to-r').forEach(card => {
            card.classList.add('card-hover');
        });

        // Update current time for timeline
        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            const currentTimeElements = document.querySelectorAll('.text-gray-500.text-xs:contains("Current")');
            currentTimeElements.forEach(el => {
                if (el.textContent.includes('Current')) {
                    el.textContent = `Today, ${timeString} • Current`;
                }
            });
        }

        // Update every minute
        updateCurrentTime();
        setInterval(updateCurrentTime, 60000);

        // Design component click handlers
        document.querySelectorAll('.bg-blue-50, .bg-green-50, .bg-purple-50, .bg-yellow-50').forEach(component => {
            component.addEventListener('click', function () {
                const componentName = this.querySelector('.font-semibold').textContent;
                const componentType = componentName.includes('Visual') ? 'visual design' :
                    componentName.includes('Interaction') ? 'interaction design' :
                        componentName.includes('Data') ? 'data visualization' : 'responsive design';

                showToast(`Viewing ${componentType} details`, 'info');

                // Simulate opening component details
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
                modal.innerHTML = `
                <div class="bg-white rounded-2xl w-full max-w-md animate-fadeInUp">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-800">${componentName} Details</h3>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-4">
                            <h4 class="text-gray-800 font-medium mb-2">Status</h4>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Completed</span>
                                <span class="text-gray-500 text-sm">Updated: Today</span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="text-gray-800 font-medium mb-2">Files</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-gray-700 text-sm">${componentType}_specs.fig</span>
                                    <span class="text-gray-500 text-xs">2.1 MB</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-gray-700 text-sm">${componentType}_guide.pdf</span>
                                    <span class="text-gray-500 text-xs">1.5 MB</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-gray-800 font-medium mb-2">Design Notes</h4>
                            <p class="text-gray-600 text-sm">This component follows the established design system guidelines and has been reviewed for accessibility compliance.</p>
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        <button onclick="this.closest('.fixed').remove()" class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                            Close
                        </button>
                        <button onclick="showToast('Opening design files...', 'info')" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2]">
                            Open Files
                        </button>
                    </div>
                </div>
            `;

                document.body.appendChild(modal);
                document.body.style.overflow = 'hidden';
            });
        });

        // Print button functionality
        const printBtn = document.querySelector('button:contains("Print")');
        if (printBtn) {
            printBtn.addEventListener('click', function () {
                // Show print preview
                showToast('Preparing design summary for print...', 'info');

                setTimeout(() => {
                    // In a real app, this would open the print dialog
                    // window.print();
                    showToast('Design summary ready for printing', 'success');
                }, 1000);
            });
        }

        // Design team member click handlers
        document.querySelectorAll('.bg-white.border.border-gray-200.rounded-lg').forEach(member => {
            member.addEventListener('click', function () {
                const personName = this.querySelector('.font-medium').textContent;
                const role = this.querySelector('.text-gray-500').textContent;

                showToast(`Designer: ${personName} (${role})`, 'info');

                // Simulate opening contact details
                if (personName.includes('Sarah Williams')) {
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
                    modal.innerHTML = `
                    <div class="bg-white rounded-2xl w-full max-w-md animate-fadeInUp">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-gray-800">Designer Details</h3>
                                <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-secondary to-[#8A84C6] rounded-full flex items-center justify-center text-white text-xl font-bold">
                                    SW
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-800">Sarah Williams</h4>
                                    <p class="text-gray-600">Lead UI/UX Designer</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                    <span class="text-gray-700">sarah.williams@company.com</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-phone text-gray-400"></i>
                                    <span class="text-gray-700">+1 (555) 987-6543</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-figma text-gray-400"></i>
                                    <span class="text-gray-700">@sarahw-design</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-tasks text-gray-400"></i>
                                    <span class="text-gray-700">Specialization: Design Systems</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 border-t border-gray-200 flex gap-3">
                            <button onclick="this.closest('.fixed').remove()" class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                Close
                            </button>
                            <button onclick="window.location.href='mailto:sarah.williams@company.com'" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2]">
                                Send Message
                            </button>
                        </div>
                    </div>
                `;

                    document.body.appendChild(modal);
                    document.body.style.overflow = 'hidden';
                }
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