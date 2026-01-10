<?= $this->extend('layouts/feature_request_layout') ?>

<?= $this->section('title') ?>Request #<?= $request_id ?? 'FR-2342' ?> Detail - Feature
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
<div class="mt-4 md:mt-[77px] p-4 md:p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl md:text-[32px] font-semibold text-text-dark">Request
                        #<?= $request_id ?? 'FR-2342' ?></h1>
                    <div class="px-3 py-1 bg-secondary text-white text-sm font-semibold rounded-full">Feature Request
                    </div>
                </div>
                <p class="text-sm md:text-[15px] font-light text-[#666]">Dark Mode Implementation • User Dashboard</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <a href="<?= base_url('department/feature-request/assigned_tickets') ?>"
                    class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Requests
                </a>
                <button id="focusViewBtn"
                    class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-expand-alt"></i>
                    Focus View
                </button>
                <button
                    class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-download"></i>
                    Export Analysis
                </button>
            </div>
        </div>
    </div>

    <!-- Request Status -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Request Info Card -->
        <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-xl p-6 text-white">
            <h3 class="text-lg font-semibold mb-4">Request Information</h3>
            <div class="space-y-4">
                <div>
                    <div class="text-white/80 text-sm mb-1">Status</div>
                    <div class="flex items-center gap-3">
                        <div class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold">ANALYSIS IN PROGRESS</div>
                        <div class="text-lg font-bold">Under Review</div>
                    </div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Priority</div>
                    <div class="px-3 py-1 bg-orange-500/20 rounded-full text-sm font-semibold inline-block">HIGH</div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Category</div>
                    <div class="text-lg font-semibold">User Experience</div>
                </div>
            </div>
        </div>

        <!-- Progress Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Analysis Progress</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Analysis Completion</span>
                        <span>45%</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-secondary rounded-full w-[45%]"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="far fa-clock text-gray-400"></i>
                        <span>Time spent: 2h 30m</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-user text-gray-400"></i>
                        <span>Analyst: Sarah Johnson</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Impact Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Business Impact</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Estimated ROI</span>
                        <span class="text-green-600 font-medium">High</span>
                    </div>
                    <div class="text-xs text-gray-500">Expected: 28% increase in engagement</div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Development Effort</span>
                        <span class="text-yellow-600 font-medium">Medium (3-4 weeks)</span>
                    </div>
                    <div class="text-xs text-gray-500">Team: 2 Frontend Developers</div>
                </div>
                <div class="pt-3 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Requested By</span>
                        <span class="text-blue-600 font-medium">Marketing Team</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Left Column - Analysis Conversation -->
        <div class="lg:col-span-2">
            <!-- Conversation Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
                <!-- Section Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">Feature Analysis Conversation</h2>
                        <div class="text-gray-600 text-sm">
                            <i class="far fa-comments mr-1"></i>
                            8 messages
                        </div>
                    </div>
                </div>

                <!-- Conversation Container -->
                <div id="conversationContainer" class="p-6 h-[500px] overflow-y-auto">
                    <!-- Conversation Timeline -->
                    <div class="space-y-6">
                        <!-- Date Header - 3 Days Ago -->
                        <div class="text-center">
                            <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">3 Days Ago</span>
                        </div>

                        <!-- Message 1 - Support Team (Forwarded) -->
                        <div class="flex gap-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-headset text-green-600"></i>
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div>
                                        <span class="text-gray-800 font-semibold">Support Team</span>
                                        <span
                                            class="ml-2 px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">Support
                                            Agent</span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        10:30 AM
                                    </div>
                                </div>

                                <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                                    <p class="text-gray-700 mb-3">
                                        <span class="font-semibold">Feature request forwarded to Feature Request
                                            Department</span><br>
                                        Customer feedback indicates strong demand for dark mode. Please analyze
                                        feasibility and business impact.
                                    </p>

                                    <!-- Forward Info -->
                                    <div
                                        class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-green-200">
                                        <i class="fas fa-share-alt text-green-600"></i>
                                        <span class="text-green-700 text-sm font-medium">Forwarded from: Technical
                                            Support</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message 2 - Feature Request Analyst -->
                        <div class="flex gap-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-white"></i>
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div>
                                        <span class="text-gray-800 font-semibold">Feature Request Team</span>
                                        <span
                                            class="ml-2 px-2 py-0.5 bg-secondary/10 text-secondary text-xs rounded">Product
                                            Analyst</span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        11:45 AM
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-gray-700 mb-3">
                                        Received the request. I've started the analysis. Initial research shows 78% of
                                        users would enable dark mode if available.
                                        Need to coordinate with UI/UX team for design specifications.
                                    </p>

                                    <!-- Attachment -->
                                    <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                        <div class="w-8 h-8 bg-secondary/10 rounded flex items-center justify-center">
                                            <i class="fas fa-chart-bar text-secondary"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-gray-800 text-sm font-medium">user_survey_results.pdf</div>
                                            <div class="text-gray-500 text-xs">452 KB • Survey Report</div>
                                        </div>
                                        <button class="text-gray-400 hover:text-secondary download-btn">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Header - Yesterday -->
                        <div class="text-center mt-8 pt-8 border-t border-gray-200">
                            <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">Yesterday</span>
                        </div>

                        <!-- Message 3 - UI/UX Team -->
                        <div class="flex gap-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-paint-brush text-blue-600"></i>
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div>
                                        <span class="text-gray-800 font-semibold">UI/UX Team</span>
                                        <span class="ml-2 px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded">UI
                                            Designer</span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        2:15 PM
                                    </div>
                                </div>

                                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                                    <p class="text-gray-700 mb-3">
                                        I've reviewed the dark mode request. We can implement using CSS variables and a
                                        color system.
                                        Estimated design time: 2 weeks. Need to consider accessibility standards.
                                    </p>

                                    <!-- Design Specs -->
                                    <div
                                        class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-blue-200">
                                        <i class="fas fa-palette text-blue-600"></i>
                                        <span class="text-blue-700 text-sm font-medium">Design specifications
                                            ready</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message 4 - Feature Request Analyst -->
                        <div class="flex gap-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-white"></i>
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div>
                                        <span class="text-gray-800 font-semibold">Sarah Johnson</span>
                                        <span
                                            class="ml-2 px-2 py-0.5 bg-secondary/10 text-secondary text-xs rounded">Senior
                                            Analyst</span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        9:30 AM • 2 hours ago
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-gray-700 mb-3">
                                        Based on the design specs and user research, I'm preparing the business case.
                                        Preliminary ROI looks promising. Need to estimate development costs with
                                        engineering.
                                    </p>

                                    <!-- Status Update -->
                                    <div
                                        class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-purple-200">
                                        <i class="fas fa-sync-alt text-purple-600"></i>
                                        <span class="text-purple-700 text-sm font-medium">Analysis updated to: In
                                            Progress</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message 5 - Engineering Team -->
                        <div class="flex gap-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-code text-green-600"></i>
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div>
                                        <span class="text-gray-800 font-semibold">Engineering Team</span>
                                        <span class="ml-2 px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">Tech
                                            Lead</span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        10:45 AM • 45 min ago
                                    </div>
                                </div>

                                <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                                    <p class="text-gray-700 mb-3">
                                        We've reviewed the requirements. Estimated development time: 3-4 weeks for 2
                                        frontend developers.
                                        The design system will make implementation straightforward.
                                    </p>

                                    <!-- Technical Feasibility -->
                                    <div class="text-xs text-gray-600 mt-2">
                                        <i class="fas fa-check-circle text-green-600 mr-1"></i>
                                        Technical feasibility: High
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message 6 - Feature Request (Latest) -->
                        <div id="latestMessage" class="flex gap-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-white"></i>
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div>
                                        <span class="text-gray-800 font-semibold">Feature Request Team</span>
                                        <span
                                            class="ml-2 px-2 py-0.5 bg-secondary/10 text-secondary text-xs rounded">Lead
                                            Analyst</span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        11:15 AM • 15 min ago
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-gray-700 mb-3">
                                        Analysis is 45% complete. ROI calculation shows 28% expected increase in user
                                        engagement.
                                        Will present to stakeholders for approval tomorrow.
                                    </p>

                                    <!-- Progress Update -->
                                    <div
                                        class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-green-200">
                                        <i class="fas fa-chart-line text-green-600"></i>
                                        <span class="text-green-700 text-sm font-medium">ROI: 28% • Cost-benefit
                                            analysis complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analysis Notes Section -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Analysis Notes</h3>

                <div class="space-y-4">
                    <!-- Analysis Input -->
                    <div>
                        <textarea
                            placeholder="Add analysis notes, stakeholder feedback, or requirement clarifications..."
                            class="w-full h-32 p-4 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 resize-none text-gray-700"
                            rows="4" id="analysisInput"></textarea>
                        <div class="text-gray-500 text-xs mt-1">
                            Analysis notes are visible to Feature Request, UI/UX, and Engineering teams
                        </div>
                    </div>

                    <!-- File Attachment -->
                    <div class="flex items-center gap-4">
                        <button id="attachFileBtn"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium flex items-center gap-2">
                            <i class="fas fa-paperclip"></i>
                            Attach Analysis
                        </button>
                        <div id="fileInfo" class="text-gray-500 text-sm">
                            No files attached
                        </div>
                    </div>

                    <!-- Analysis Options -->
                    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="updateStatus" class="rounded text-secondary focus:ring-secondary"
                                checked>
                            <label for="updateStatus" class="text-gray-700 text-sm">Update analysis status</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="notifyStakeholders"
                                class="rounded text-secondary focus:ring-secondary" checked>
                            <label for="notifyStakeholders" class="text-gray-700 text-sm">Notify stakeholders</label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button id="sendAnalysisBtn"
                            class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-secondary/90 transition-colors font-medium flex items-center gap-2 flex-1 justify-center">
                            <i class="fas fa-chart-pie"></i>
                            Save Analysis Notes
                        </button>
                        <button id="cancelBtn"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium flex-1">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Analysis Actions & Info -->
        <div class="space-y-6">
            <!-- Analysis Actions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-secondary/10 to-secondary/5">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-chart-line text-secondary"></i>
                        Analysis Actions
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <!-- Update Analysis Status -->
                        <div class="space-y-3">
                            <label class="text-gray-700 text-sm font-medium">Update Analysis Status</label>

                            <!-- Status Options -->
                            <div class="space-y-2">
                                <!-- In Progress Option -->
                                <div class="status-option flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50"
                                    data-status="in-progress">
                                    <div class="w-4 h-4 bg-secondary rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-white text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">Analysis In Progress</p>
                                        <p class="text-gray-500 text-xs">Currently analyzing requirements</p>
                                    </div>
                                </div>

                                <!-- Waiting for Requirements -->
                                <div class="status-option flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50"
                                    data-status="waiting-reqs">
                                    <div class="w-4 h-4 bg-secondary rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-white text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">Awaiting Requirements</p>
                                        <p class="text-gray-500 text-xs">Need more details from stakeholders</p>
                                    </div>
                                </div>

                                <!-- Ready for Review -->
                                <div class="status-option flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50"
                                    data-status="ready-review">
                                    <div class="w-4 h-4 bg-secondary rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-white text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">Ready for Stakeholder Review</p>
                                        <p class="text-gray-500 text-xs">Analysis complete, ready for approval</p>
                                    </div>
                                </div>

                                <!-- Approved -->
                                <div class="status-option flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50"
                                    data-status="approved">
                                    <div class="w-4 h-4 bg-secondary rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-white text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">Approved for Development</p>
                                        <p class="text-gray-500 text-xs">Feature approved, ready for engineering</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-4">
                            <button id="updateStatusBtn"
                                class="w-full px-4 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                                Update Analysis Status
                            </button>

                            <button id="approveRequestBtn"
                                class="w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                Approve for Development
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Case Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-secondary/10 to-secondary/5">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-briefcase text-secondary"></i>
                        Business Case
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Metrics -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-800">78%</div>
                                <div class="text-xs text-gray-600">User Demand</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">28%</div>
                                <div class="text-xs text-gray-600">Expected ROI</div>
                            </div>
                        </div>

                        <!-- Development Timeline -->
                        <div>
                            <h4 class="text-gray-700 text-sm font-medium mb-2">Development Timeline</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs">
                                    <span>Design Phase</span>
                                    <span class="font-medium">2 weeks</span>
                                </div>
                                <div class="h-1 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full w-[40%]"></div>
                                </div>

                                <div class="flex justify-between text-xs">
                                    <span>Development</span>
                                    <span class="font-medium">3-4 weeks</span>
                                </div>
                                <div class="h-1 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-green-500 rounded-full w-[60%]"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="text-gray-700 text-sm font-medium mb-2">Quick Links</h4>
                            <div class="space-y-2">
                                <a href="#"
                                    class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded text-sm text-gray-700">
                                    <i class="fas fa-file-alt text-secondary"></i>
                                    <span>View Detailed Analysis</span>
                                </a>
                                <a href="#"
                                    class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded text-sm text-gray-700">
                                    <i class="fas fa-users text-secondary"></i>
                                    <span>Stakeholder Feedback</span>
                                </a>
                                <a href="#"
                                    class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded text-sm text-gray-700">
                                    <i class="fas fa-code text-secondary"></i>
                                    <span>Technical Specifications</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS Styles (same as IT Support) -->
<style>
    /* Custom styles */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    /* Scrollbar styling */
    #conversationContainer::-webkit-scrollbar {
        width: 8px;
    }

    #conversationContainer::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    #conversationContainer::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    #conversationContainer::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Message hover effects */
    .message-item:hover {
        background-color: #f8fafc;
    }

    /* Status badge animations */
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Card hover effects */
    .card-hover {
        transition: all 0.2s ease;
    }

    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    /* Focus View Modal */
    .focus-view-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        z-index: 1000;
        display: flex;
        flex-direction: column;
        animation: fadeIn 0.3s ease-out;
    }

    .focus-view-content {
        flex: 1;
        overflow: hidden;
    }

    .focus-view-header {
        background: #3D3C5E;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .focus-view-body {
        height: calc(100vh - 60px);
        overflow-y: auto;
        padding: 2rem;
    }

    /* Smooth transitions */
    .transition-all {
        transition: all 0.2s ease;
    }

    .transition-colors {
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    }

    /* Status option selected state */
    .status-option.selected {
        border-color: #756EA4;
        background-color: rgba(117, 110, 164, 0.05);
    }
</style>

<!-- JavaScript (adapted for Feature Request) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto-scroll to latest message
        const conversationContainer = document.getElementById('conversationContainer');
        if (conversationContainer) {
            setTimeout(() => {
                conversationContainer.scrollTop = conversationContainer.scrollHeight;
            }, 100);
        }

        // Textarea auto-resize
        const analysisInput = document.getElementById('analysisInput');
        if (analysisInput) {
            analysisInput.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }

        // File attachment
        const attachBtn = document.getElementById('attachFileBtn');
        const fileInfo = document.getElementById('fileInfo');

        if (attachBtn && fileInfo) {
            attachBtn.addEventListener('click', function () {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = '.pdf,.doc,.docx,.xlsx,.txt';
                input.onchange = function (e) {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        const fileSize = (file.size / (1024 * 1024)).toFixed(2);

                        if (fileSize > 50) {
                            alert('File size exceeds 50MB limit');
                            return;
                        }

                        fileInfo.innerHTML = `
                        <div class="flex items-center gap-2 animate-fadeIn">
                            <i class="fas fa-file text-secondary"></i>
                            <span class="text-gray-700">${file.name} (${fileSize} MB)</span>
                            <button class="ml-2 text-red-500 hover:text-red-700 remove-file-btn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                        // Add remove file button handler
                        const removeBtn = fileInfo.querySelector('.remove-file-btn');
                        if (removeBtn) {
                            removeBtn.addEventListener('click', function () {
                                fileInfo.innerHTML = 'No files attached';
                            });
                        }
                    }
                };
                input.click();
            });
        }

        // Status selection
        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function () {
                // Remove selection from all
                document.querySelectorAll('.status-option').forEach(opt => {
                    opt.classList.remove('selected');
                    const icon = opt.querySelector('.w-4.h-4');
                    if (icon.querySelector('.fa-check')) {
                        icon.classList.remove('bg-purple-500');
                        icon.classList.add('bg-secondary');
                        icon.innerHTML = '<i class="fas fa-plus text-white text-xs"></i>';
                    }
                });

                // Select this option
                this.classList.add('selected');
                const icon = this.querySelector('.w-4.h-4');
                icon.classList.remove('bg-secondary');
                icon.classList.add('bg-purple-500');
                icon.innerHTML = '<i class="fas fa-check text-white text-xs"></i>';

                // Update button text
                const statusName = this.querySelector('.font-medium').textContent;
                document.getElementById('updateStatusBtn').innerHTML = `Update to ${statusName}`;
            });
        });

        // Set default selected status
        const inProgressOption = document.querySelector('.status-option[data-status="in-progress"]');
        if (inProgressOption) {
            inProgressOption.click();
        }

        // Update Analysis Status
        const updateStatusBtn = document.getElementById('updateStatusBtn');
        if (updateStatusBtn) {
            updateStatusBtn.addEventListener('click', function () {
                const selectedOption = document.querySelector('.status-option.selected');
                if (!selectedOption) {
                    showToast('Please select an analysis status first', 'error');
                    return;
                }

                const newStatus = selectedOption.dataset.status;
                const statusName = selectedOption.querySelector('.font-medium').textContent;

                if (confirm(`Update analysis status to "${statusName}"?`)) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                    this.disabled = true;

                    setTimeout(() => {
                        this.innerHTML = 'Update Analysis Status';
                        this.disabled = false;

                        // Update UI
                        const statusBadge = document.querySelector('.px-3.py-1.bg-white\\/20');
                        const statusText = document.querySelector('.text-lg.font-bold');

                        if (statusBadge) {
                            statusBadge.textContent = statusName.toUpperCase();

                            // Change badge color based on status
                            statusBadge.className = 'px-3 py-1 rounded-full text-sm font-semibold inline-block';
                            if (newStatus === 'approved') {
                                statusBadge.classList.add('bg-green-500', 'text-white');
                            } else if (newStatus === 'in-progress') {
                                statusBadge.classList.add('bg-blue-500', 'text-white');
                            } else if (newStatus === 'ready-review') {
                                statusBadge.classList.add('bg-yellow-500', 'text-white');
                            } else if (newStatus === 'waiting-reqs') {
                                statusBadge.classList.add('bg-orange-500', 'text-white');
                            } else {
                                statusBadge.classList.add('bg-white/20', 'text-white');
                            }
                        }

                        if (statusText) {
                            statusText.textContent = statusName;
                        }

                        showToast(`Analysis status updated to ${statusName}`, 'success');

                        // Add timeline entry
                        addAnalysisNote(`Analysis status updated to ${statusName}`);

                    }, 1000);
                }
            });
        }

        // Approve Request
        const approveRequestBtn = document.getElementById('approveRequestBtn');
        if (approveRequestBtn) {
            approveRequestBtn.addEventListener('click', function () {
                if (confirm('Approve this feature request for development? This will notify the Engineering team.')) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Approving...';
                    this.disabled = true;

                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-check-circle"></i> Approved';
                        this.classList.remove('bg-green-500', 'hover:bg-green-600');
                        this.classList.add('bg-green-600', 'hover:bg-green-700');

                        // Update status selection
                        const approvedOption = document.querySelector('.status-option[data-status="approved"]');
                        if (approvedOption) {
                            approvedOption.click();
                        }

                        // Update UI
                        const statusBadge = document.querySelector('.px-3.py-1.bg-white\\/20');
                        const statusText = document.querySelector('.text-lg.font-bold');

                        if (statusBadge) {
                            statusBadge.textContent = 'APPROVED';
                            statusBadge.classList.remove('bg-white/20');
                            statusBadge.classList.add('bg-green-500', 'text-white');
                        }

                        if (statusText) {
                            statusText.textContent = 'Approved for Development';
                        }

                        // Update progress bar
                        const progressBar = document.querySelector('.h-full.bg-secondary.rounded-full');
                        if (progressBar) {
                            progressBar.style.width = '100%';
                        }

                        showToast('Feature request approved for development', 'success');

                        // Add approval message to conversation
                        addApprovalMessage();

                    }, 1000);
                }
            });
        }

        // Send Analysis Notes
        const sendAnalysisBtn = document.getElementById('sendAnalysisBtn');
        if (sendAnalysisBtn) {
            sendAnalysisBtn.addEventListener('click', function () {
                const message = analysisInput.value.trim();
                if (!message) {
                    alert('Please write analysis notes before saving');
                    return;
                }

                const updateStatus = document.getElementById('updateStatus').checked;
                const notifyStakeholders = document.getElementById('notifyStakeholders').checked;

                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                this.disabled = true;

                // Simulate saving
                setTimeout(() => {
                    // Reset button
                    this.innerHTML = originalText;
                    this.disabled = false;

                    // Add analysis note to conversation
                    addAnalysisNote(message, notifyStakeholders);

                    // Clear form
                    analysisInput.value = '';
                    analysisInput.style.height = 'auto';
                    if (fileInfo) fileInfo.innerHTML = 'No files attached';

                    // Show success message
                    showToast('Analysis notes saved successfully', 'success');

                }, 1500);
            });
        }

        // Cancel button
        const cancelBtn = document.getElementById('cancelBtn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                analysisInput.value = '';
                analysisInput.style.height = 'auto';
                if (fileInfo) fileInfo.innerHTML = 'No files attached';
                document.getElementById('updateStatus').checked = true;
                document.getElementById('notifyStakeholders').checked = true;
            });
        }

        // Download buttons
        document.querySelectorAll('.download-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const fileName = this.closest('.bg-white')?.querySelector('.text-gray-800')?.textContent || 'file';
                alert(`Downloading ${fileName}...`);
            });
        });

        // Focus View Button
        const focusViewBtn = document.getElementById('focusViewBtn');
        if (focusViewBtn) {
            focusViewBtn.addEventListener('click', function () {
                openFocusView();
            });
        }

        // Quick Links click handlers
        document.querySelectorAll('.flex.items-center.gap-2.p-2').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const linkText = this.querySelector('span').textContent;
                showToast(`Opening ${linkText}...`, 'info');
            });
        });

        // Utility functions
        function addAnalysisNote(text, notifyStakeholders = true) {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            const newMessage = document.createElement('div');
            newMessage.className = 'flex gap-4 animate-fadeIn';
            newMessage.innerHTML = `
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center">
                    <i class="fas fa-lightbulb text-white"></i>
                </div>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div>
                        <span class="text-gray-800 font-semibold">Feature Request Team</span>
                        <span class="ml-2 px-2 py-0.5 bg-secondary/10 text-secondary text-xs rounded">Analysis Note</span>
                    </div>
                    <div class="text-gray-500 text-sm ml-auto">
                        <i class="far fa-clock mr-1"></i>
                        ${timeString} • Just now
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-gray-700">${text}</p>
                    ${notifyStakeholders ?
                    '<div class="text-xs text-gray-600 mt-2"><i class="fas fa-bell mr-1"></i>Stakeholders notified</div>' :
                    ''
                }
                </div>
            </div>
        `;

            // Append to conversation
            const conversationTimeline = document.querySelector('#conversationContainer .space-y-6');
            if (conversationTimeline) {
                conversationTimeline.appendChild(newMessage);

                // Scroll to new message
                setTimeout(() => {
                    if (conversationContainer) {
                        conversationContainer.scrollTop = conversationContainer.scrollHeight;
                    }
                }, 100);
            }
        }

        function addApprovalMessage() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            const approvalMessage = document.createElement('div');
            approvalMessage.className = 'flex gap-4 animate-fadeIn';
            approvalMessage.innerHTML = `
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white"></i>
                </div>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div>
                        <span class="text-gray-800 font-semibold">Feature Request Team</span>
                        <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded">Approval</span>
                    </div>
                    <div class="text-gray-500 text-sm ml-auto">
                        <i class="far fa-clock mr-1"></i>
                        ${timeString} • Just now
                    </div>
                </div>
                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                    <p class="text-gray-700 mb-3">
                        <strong>Feature request approved for development.</strong> Business case approved by stakeholders. Engineering team has been notified to begin development.
                    </p>
                    <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-green-300">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span class="text-green-700 text-sm font-medium">Feature approved • Development scheduled</span>
                    </div>
                </div>
            </div>
        `;

            // Append to conversation
            const conversationTimeline = document.querySelector('#conversationContainer .space-y-6');
            if (conversationTimeline) {
                conversationTimeline.appendChild(approvalMessage);

                // Scroll to new message
                setTimeout(() => {
                    if (conversationContainer) {
                        conversationContainer.scrollTop = conversationContainer.scrollHeight;
                    }
                }, 100);
            }
        }

        function openFocusView() {
            // Create modal
            const modal = document.createElement('div');
            modal.className = 'focus-view-modal';
            modal.innerHTML = `
            <div class="focus-view-header">
                <div class="flex items-center gap-3">
                    <h2 class="text-white text-xl font-bold">Focus View - Request #<?= $request_id ?? 'FR-2342' ?></h2>
                    <span class="px-2 py-1 bg-secondary text-white text-xs rounded">Feature Request</span>
                </div>
                <button id="closeFocusView" class="text-white hover:text-gray-300 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="focus-view-body bg-gray-900">
                <div class="max-w-6xl mx-auto">
                    <div class="bg-gray-800 rounded-xl p-6 mb-6">
                        <h3 class="text-white text-lg font-bold mb-2">Dark Mode Implementation</h3>
                        <p class="text-gray-300">User Dashboard • Category: User Experience</p>
                    </div>
                    
                    <!-- Business Analysis Details -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h4 class="text-white font-bold mb-3">Business Impact</h4>
                            <div class="space-y-2 text-gray-300 text-sm">
                                <div class="flex justify-between">
                                    <span>Expected ROI:</span>
                                    <span class="font-medium text-green-400">28%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>User Demand:</span>
                                    <span class="font-medium">78%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Priority:</span>
                                    <span class="text-yellow-400 font-medium">High</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h4 class="text-white font-bold mb-3">Analysis Status</h4>
                            <div class="space-y-2 text-gray-300 text-sm">
                                <div class="flex justify-between">
                                    <span>Status:</span>
                                    <span class="text-yellow-400 font-medium">In Progress</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Progress:</span>
                                    <span class="font-medium">45%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Estimated Timeline:</span>
                                    <span class="font-medium">5-6 weeks</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h4 class="text-white font-bold mb-3">Quick Actions</h4>
                            <div class="space-y-3">
                                <button class="w-full bg-secondary text-white py-2 rounded-lg text-sm">Update Analysis</button>
                                <button class="w-full bg-green-600 text-white py-2 rounded-lg text-sm">Approve Request</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Analysis Conversation in focus view -->
                    <div class="bg-gray-800 rounded-xl p-6">
                        <h4 class="text-white font-bold mb-4">Recent Analysis Discussion</h4>
                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-4">
                            ${conversationContainer ? conversationContainer.innerHTML : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;

            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';

            // Close button
            const closeBtn = modal.querySelector('#closeFocusView');
            closeBtn.addEventListener('click', function () {
                document.body.removeChild(modal);
                document.body.style.overflow = 'auto';
            });

            // Close on escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && document.body.contains(modal)) {
                    document.body.removeChild(modal);
                    document.body.style.overflow = 'auto';
                }
            });
        }
    });

    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-24 right-6 p-4 rounded-xl shadow-xl z-[9999] max-w-sm animate-fadeInUp ${type === 'error' ? 'bg-red-500 text-white border-l-4 border-red-600' : type === 'success' ? 'bg-green-500 text-white border-l-4 border-green-600' : 'bg-blue-500 text-white border-l-4 border-blue-600'}`;
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

    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
`;
    document.head.appendChild(style);
</script>
<?= $this->endSection() ?>