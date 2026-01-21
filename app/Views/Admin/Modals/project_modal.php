    <?php
    /**
     * PROJECT MODAL COMPONENT
     * Contains all modal dialogs for project management
     * Used in: manage_projects.php
     */
    ?>

    <!-- ADD PROJECT MODAL -->
    <div id="addProjectModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-md">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Add New Project</h3>
                    <button type="button" class="close-modal text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-gray-500 text-sm mt-1">Create a new project for ticket management</p>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="addProjectForm" class="space-y-4">
                    <?= csrf_field() ?>

                    <!-- Project Name -->
                    <div>
                        <label for="projectName" class="block text-gray-700 text-sm font-medium mb-2">
                            Project Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="projectName" name="project_name" required
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"
                            placeholder="e.g., Website Development"
                            maxlength="100">
                        <div class="text-xs text-gray-500 mt-1">Maximum 100 characters</div>
                    </div>

                    <!-- Project Code -->
                    <div>
                        <label for="projectCode" class="block text-gray-700 text-sm font-medium mb-2">
                            Project Code <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="projectCode" name="project_code" required
                                class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all uppercase"
                                placeholder="e.g., PROJ001"
                                maxlength="20">
                            <div id="projectCodeStatus" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">Unique identifier for the project (max 20 chars)</div>
                        <div id="projectCodeError" class="text-xs text-red-500 mt-1 hidden"></div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="projectDescription" class="block text-gray-700 text-sm font-medium mb-2">
                            Description
                        </label>
                        <textarea id="projectDescription" name="description" rows="3"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"
                            placeholder="Brief description of the project..."
                            maxlength="500"></textarea>
                        <div class="text-xs text-gray-500 mt-1">Maximum 500 characters</div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="projectStatus" class="block text-gray-700 text-sm font-medium mb-2">
                            Status
                        </label>
                        <select id="projectStatus" name="is_active"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- Auto-generate Ticket Numbers -->
                    <div class="pt-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="autoGenerateNumbers" name="auto_generate_numbers" checked
                                class="w-4 h-4 text-secondary border-gray-300 rounded focus:ring-secondary focus:ring-offset-0">
                            <label for="autoGenerateNumbers" class="ml-2 text-sm text-gray-700">
                                Auto-generate ticket numbers for this project
                            </label>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            Format: <span id="ticketFormatPreview" class="font-mono">PROJ001-001</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button type="button" class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </button>
                <button type="button" id="confirmAddProject"
                    class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                    <i class="fas fa-plus mr-2"></i>Create Project
                </button>
            </div>
        </div>
    </div>

    <!-- EDIT PROJECT MODAL -->
    <div id="editProjectModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-md">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Edit Project</h3>
                    <button type="button" class="close-edit-modal text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-gray-500 text-sm mt-1" id="editProjectSubtitle">Edit project details</p>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="editProjectForm" class="space-y-4">
                    <?= csrf_field() ?>
                    <input type="hidden" id="editProjectId" name="project_id">

                    <!-- Project Name -->
                    <div>
                        <label for="editProjectName" class="block text-gray-700 text-sm font-medium mb-2">
                            Project Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="editProjectName" name="project_name" required
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"
                            placeholder="e.g., Website Development"
                            maxlength="100">
                    </div>

                    <!-- Project Code -->
                    <div>
                        <label for="editProjectCode" class="block text-gray-700 text-sm font-medium mb-2">
                            Project Code <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="editProjectCode" name="project_code" required
                                class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all uppercase"
                                maxlength="20">
                            <div id="editProjectCodeStatus" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                        <div id="editProjectCodeError" class="text-xs text-red-500 mt-1 hidden"></div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="editProjectDescription" class="block text-gray-700 text-sm font-medium mb-2">
                            Description
                        </label>
                        <textarea id="editProjectDescription" name="description" rows="3"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"
                            maxlength="500"></textarea>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="editProjectStatus" class="block text-gray-700 text-sm font-medium mb-2">
                            Status
                        </label>
                        <select id="editProjectStatus" name="is_active"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                            <option value="2">Completed</option>
                            <option value="3">On Hold</option>
                        </select>
                    </div>

                    <!-- Project Info -->
                    <div class="pt-2">
                        <div class="text-sm text-gray-600 space-y-1">
                            <div class="flex justify-between">
                                <span>Created:</span>
                                <span id="editProjectCreated" class="font-medium">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Tickets:</span>
                                <span id="editProjectTickets" class="font-medium">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Assigned Users:</span>
                                <span id="editProjectUsers" class="font-medium">0</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button type="button" class="close-edit-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </button>
                <button type="button" id="confirmEditProject"
                    class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- DELETE PROJECT CONFIRMATION MODAL -->
    <div id="deleteProjectModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-md">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Delete Project</h3>
                    <button type="button" class="close-delete-modal text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-800 mb-2">Are you sure?</h4>
                    <p class="text-gray-600 mb-4" id="deleteProjectMessage">
                        You are about to delete the project "<span id="deleteProjectName" class="font-semibold"></span>".
                    </p>

                    <!-- Warning Messages -->
                    <div id="deleteProjectWarnings" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4 hidden">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                            <div class="text-sm text-red-700">
                                <div id="warningTickets" class="hidden mb-1">
                                    This project has <span id="ticketCount" class="font-semibold">0</span> ticket(s).
                                </div>
                                <div id="warningAssignments" class="hidden">
                                    There are <span id="assignmentCount" class="font-semibold">0</span> user assignment(s).
                                </div>
                                <div class="mt-2 font-medium">Deleting this project will permanently remove all associated data.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Force Delete Option (if has tickets/assignments) -->
                    <div id="forceDeleteOption" class="hidden mb-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="forceDelete" name="force_delete"
                                class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-600">
                            <label for="forceDelete" class="ml-2 text-sm text-gray-700">
                                Force delete project and all associated data
                            </label>
                        </div>
                    </div>

                    <!-- Confirmation Input -->
                    <div class="mt-4">
                        <label class="block text-gray-700 text-sm mb-2">
                            Type "<span class="font-mono font-bold">DELETE</span>" to confirm:
                        </label>
                        <input type="text" id="deleteConfirmInput"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-center text-gray-800 font-mono focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Type DELETE here">
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button type="button" class="close-delete-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteProject" disabled
                    class="flex-1 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-trash mr-2"></i>Delete Project
                </button>
            </div>
        </div>
    </div>

    <!-- BULK ASSIGN USERS MODAL -->
    <div id="bulkAssignModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Bulk Assign Users</h3>
                    <button type="button" class="close-bulk-modal text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-gray-500 text-sm mt-1">Assign multiple users to multiple projects at once</p>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[60vh]">
                <!-- Step Indicators -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex-1 flex items-center">
                        <div class="step-indicator active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Select Projects</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step-indicator" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Select Users</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step-indicator" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Confirm</div>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Select Projects -->
                <div id="bulkStep1" class="bulk-modal-step space-y-6">
                    <div>
                        <h4 class="text-lg font-medium text-text-dark mb-4">Select Projects</h4>
                        <!-- Search -->
                        <div class="relative mb-4">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                            <input type="text" id="bulkProjectSearch" placeholder="Search projects by name or code..."
                                class="w-full h-12 pl-12 pr-4 bg-gray-50 border border-gray-300 rounded-lg text-text-dark text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all">
                        </div>

                        <!-- Projects List -->
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Available Projects</span>
                                    <div class="flex items-center gap-2">
                                        <button type="button" id="selectAllProjects" class="text-xs text-secondary hover:text-[#817CB2]">
                                            Select All
                                        </button>
                                        <span class="text-gray-400">|</span>
                                        <button type="button" id="deselectAllProjects" class="text-xs text-gray-500 hover:text-gray-700">
                                            Deselect All
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="bulkProjectsList" class="max-h-64 overflow-y-auto p-2 space-y-2">
                                <!-- Projects will be dynamically loaded here -->
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-project-diagram text-3xl mb-2 opacity-50"></i>
                                    <p>Loading projects...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Projects Summary -->
                        <div id="selectedProjectsSummary" class="hidden mt-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-blue-700">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span id="selectedProjectsCount">0</span> project(s) selected
                                    </span>
                                    <button type="button" id="viewSelectedProjects" class="text-xs text-blue-600 hover:text-blue-800">
                                        View List
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Select Users -->
                <div id="bulkStep2" class="bulk-modal-step space-y-6 hidden">
                    <div>
                        <h4 class="text-lg font-medium text-text-dark mb-4">Select Users</h4>
                        <!-- Search -->
                        <div class="relative mb-4">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                            <input type="text" id="bulkUserSearch" placeholder="Search users by name, email or role..."
                                class="w-full h-12 pl-12 pr-4 bg-gray-50 border border-gray-300 rounded-lg text-text-dark text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all">
                        </div>

                        <!-- Role Filter -->
                        <div class="mb-4">
                            <div class="text-sm font-medium text-gray-700 mb-2">Filter by Role</div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="role-filter-btn active px-3 py-1.5 text-xs bg-secondary text-white rounded-full" data-role="all">
                                    All Roles
                                </button>
                                <button type="button" class="role-filter-btn px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200" data-role="2">
                                    Customer
                                </button>
                                <button type="button" class="role-filter-btn px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200" data-role="3">
                                    Support
                                </button>
                                <button type="button" class="role-filter-btn px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200" data-role="4">
                                    Department
                                </button>
                            </div>
                        </div>

                        <!-- Users List -->
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Available Users</span>
                                    <div class="flex items-center gap-2">
                                        <button type="button" id="selectAllUsers" class="text-xs text-secondary hover:text-[#817CB2]">
                                            Select All
                                        </button>
                                        <span class="text-gray-400">|</span>
                                        <button type="button" id="deselectAllUsers" class="text-xs text-gray-500 hover:text-gray-700">
                                            Deselect All
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="bulkUsersList" class="max-h-64 overflow-y-auto p-2 space-y-2">
                                <!-- Users will be dynamically loaded here -->
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-users text-3xl mb-2 opacity-50"></i>
                                    <p>Loading users...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Users Summary -->
                        <div id="selectedUsersSummary" class="hidden mt-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-blue-700">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span id="selectedUsersCount">0</span> user(s) selected
                                    </span>
                                    <button type="button" id="viewSelectedUsers" class="text-xs text-blue-600 hover:text-blue-800">
                                        View List
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Confirm Assignment -->
                <div id="bulkStep3" class="bulk-modal-step space-y-6 hidden">
                    <div>
                        <h4 class="text-lg font-medium text-text-dark mb-4">Confirm Bulk Assignment</h4>

                        <!-- Summary -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fas fa-clipboard-check text-green-600"></i>
                                <span class="text-sm font-medium text-green-700">Assignment Summary</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm text-green-600">
                                <div>
                                    <div class="font-medium">Projects</div>
                                    <div id="summaryProjects" class="text-2xl font-bold text-green-700">0</div>
                                </div>
                                <div>
                                    <div class="font-medium">Users</div>
                                    <div id="summaryUsers" class="text-2xl font-bold text-green-700">0</div>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-green-200">
                                <div class="text-sm text-green-700">
                                    <i class="fas fa-calculator mr-2"></i>
                                    Total assignments: <span id="summaryTotalAssignments" class="font-bold">0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Details -->
                        <div class="space-y-4">
                            <div>
                                <h5 class="text-sm font-medium text-gray-700 mb-2">Selected Projects:</h5>
                                <div id="summaryProjectList" class="flex flex-wrap gap-2 max-h-32 overflow-y-auto p-2 bg-gray-50 rounded-lg">
                                    <!-- Project chips will be added here -->
                                </div>
                            </div>

                            <div>
                                <h5 class="text-sm font-medium text-gray-700 mb-2">Selected Users:</h5>
                                <div id="summaryUserList" class="flex flex-wrap gap-2 max-h-32 overflow-y-auto p-2 bg-gray-50 rounded-lg">
                                    <!-- User chips will be added here -->
                                </div>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="mt-6">
                            <div class="flex items-center mb-3">
                                <input type="checkbox" id="sendNotifications" name="send_notifications" checked
                                    class="w-4 h-4 text-secondary border-gray-300 rounded focus:ring-secondary">
                                <label for="sendNotifications" class="ml-2 text-sm text-gray-700">
                                    Send notification emails to users
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="overwriteAssignments" name="overwrite_assignments"
                                    class="w-4 h-4 text-secondary border-gray-300 rounded focus:ring-secondary">
                                <label for="overwriteAssignments" class="ml-2 text-sm text-gray-700">
                                    Overwrite existing assignments
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-200 flex justify-between items-center">
                <button type="button" id="bulkPrevStep" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium hidden">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </button>

                <div class="flex gap-3 ml-auto">
                    <button type="button" class="close-bulk-modal px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="button" id="bulkNextStep" class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                        Next <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    <button type="button" id="bulkConfirmAssign" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium hidden">
                        <i class="fas fa-user-check mr-2"></i>Confirm Assignment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- IMPORT PROJECTS MODAL -->
    <div id="importProjectsModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-lg">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Import Projects</h3>
                    <button type="button" class="close-import-modal text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-gray-500 text-sm mt-1">Bulk import projects from CSV file</p>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="space-y-6">
                    <!-- File Upload Area -->
                    <div id="importDropZone" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-secondary transition-colors cursor-pointer">
                        <div class="mb-4">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                        </div>
                        <h4 class="text-lg font-medium text-text-dark mb-2">Upload CSV File</h4>
                        <p class="text-gray-500 text-sm mb-4">Drag & drop or click to browse</p>
                        <p class="text-xs text-gray-400 mb-4">Supports .csv files only (Max 5MB)</p>

                        <div class="relative">
                            <input type="file" id="importFile" accept=".csv" class="hidden">
                            <button type="button" id="importBrowseBtn" class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                                <i class="fas fa-upload mr-2"></i>Choose File
                            </button>
                        </div>
                        <p id="fileName" class="text-sm text-gray-500 mt-3 hidden"></p>
                    </div>

                    <!-- File Requirements -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            <span class="text-sm font-medium text-blue-700">CSV Format Requirements</span>
                        </div>
                        <ul class="text-sm text-blue-600 space-y-1">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                                Required columns: <span class="font-mono font-bold">project_name, project_code</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                                Optional columns: <span class="font-mono">description, is_active</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                                <span class="font-mono">is_active</span> values: <span class="font-mono">true/false</span> or <span class="font-mono">1/0</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                                First row must contain column headers
                            </li>
                        </ul>
                    </div>

                    <!-- Template Download -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-download text-gray-600"></i>
                            <span class="text-sm font-medium text-gray-700">Download Template</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Use our template to ensure correct format</p>
                        <button type="button" id="downloadTemplate" class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-colors text-sm">
                            <i class="fas fa-file-csv mr-2"></i>Download Template CSV
                        </button>
                    </div>

                    <!-- Import Progress -->
                    <div id="importProgress" class="hidden space-y-4">
                        <div>
                            <div class="mb-2 flex justify-between">
                                <span class="text-sm font-medium text-text-dark">Importing Projects...</span>
                                <span id="importProgressPercent" class="text-sm text-secondary font-medium">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="importProgressBar" class="bg-secondary h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>

                        <div id="importDetails" class="text-sm text-gray-600 space-y-1">
                            <div class="flex justify-between">
                                <span>Processing:</span>
                                <span id="importCurrentRow">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Successful:</span>
                                <span id="importSuccessCount" class="text-green-600 font-medium">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Failed:</span>
                                <span id="importErrorCount" class="text-red-600 font-medium">0</span>
                            </div>
                        </div>

                        <!-- Error Display -->
                        <div id="importErrors" class="hidden">
                            <div class="text-sm font-medium text-red-700 mb-2">Import Errors:</div>
                            <div id="importErrorList" class="max-h-32 overflow-y-auto text-xs text-red-600 space-y-1">
                                <!-- Errors will be listed here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button type="button" class="close-import-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </button>
                <button type="button" id="confirmImport" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium" disabled>
                    <i class="fas fa-file-import mr-2"></i>Import Projects
                </button>
            </div>
        </div>
    </div>

    <!-- PROJECT ACTIONS MENU (Dropdown) -->
    <div id="projectActionsMenu" class="absolute bg-white rounded-xl shadow-xl border border-gray-200 z-50 w-48 hidden">
        <div class="py-2">
            <button type="button" class="menu-item view-details w-full text-left px-4 py-2.5 hover:bg-gray-50 transition-colors flex items-center gap-3">
                <i class="fas fa-eye text-gray-600 text-sm w-5"></i>
                <span class="text-sm text-gray-700">View Details</span>
            </button>
            <button type="button" class="menu-item edit-project w-full text-left px-4 py-2.5 hover:bg-gray-50 transition-colors flex items-center gap-3">
                <i class="fas fa-edit text-secondary text-sm w-5"></i>
                <span class="text-sm text-gray-700">Edit Project</span>
            </button>
            <button type="button" class="menu-item manage-users w-full text-left px-4 py-2.5 hover:bg-gray-50 transition-colors flex items-center gap-3">
                <i class="fas fa-users text-blue-600 text-sm w-5"></i>
                <span class="text-sm text-gray-700">Manage Users</span>
            </button>
            <button type="button" class="menu-item export-tickets w-full text-left px-4 py-2.5 hover:bg-gray-50 transition-colors flex items-center gap-3">
                <i class="fas fa-file-export text-green-600 text-sm w-5"></i>
                <span class="text-sm text-gray-700">Export Tickets</span>
            </button>
            <div class="border-t border-gray-200 my-1"></div>
            <button type="button" class="menu-item delete-project w-full text-left px-4 py-2.5 hover:bg-gray-50 transition-colors flex items-center gap-3">
                <i class="fas fa-trash text-red-600 text-sm w-5"></i>
                <span class="text-sm text-gray-700">Delete Project</span>
            </button>
        </div>
    </div>

    <!-- CONFIRM CHANGE STATUS MODAL -->
    <div id="changeStatusModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800" id="statusModalTitle">Change Project Status</h3>
                    <button type="button" class="close-status-modal text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <p class="text-gray-600 mb-4" id="statusModalMessage"></p>

                <div class="space-y-3">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Select New Status:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" class="status-option active-status py-3 border border-green-200 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors"
                            data-status="active">
                            <i class="fas fa-play-circle mr-2"></i>Active
                        </button>
                        <button type="button" class="status-option inactive-status py-3 border border-red-200 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors"
                            data-status="inactive">
                            <i class="fas fa-pause-circle mr-2"></i>Inactive
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button type="button" class="close-status-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </button>
                <button type="button" id="confirmChangeStatus" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                    Update Status
                </button>
            </div>
        </div>
    </div>

    <!-- EXPORT PROJECTS MODAL -->
    <div id="exportProjectsModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Export Projects</h3>
                    <button type="button" class="close-export-modal text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-gray-500 text-sm mt-1">Export project data in various formats</p>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Format</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" class="format-option active py-3 border-2 border-secondary bg-secondary/10 text-secondary rounded-lg"
                                data-format="csv">
                                <i class="fas fa-file-csv text-lg mb-1"></i>
                                <div class="text-xs">CSV</div>
                            </button>
                            <button type="button" class="format-option py-3 border border-gray-200 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100"
                                data-format="excel">
                                <i class="fas fa-file-excel text-lg mb-1"></i>
                                <div class="text-xs">Excel</div>
                            </button>
                            <button type="button" class="format-option py-3 border border-gray-200 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100"
                                data-format="pdf">
                                <i class="fas fa-file-pdf text-lg mb-1"></i>
                                <div class="text-xs">PDF</div>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Include</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="exportProjects" name="export_projects" checked
                                    class="w-4 h-4 text-secondary border-gray-300 rounded focus:ring-secondary">
                                <label for="exportProjects" class="ml-2 text-sm text-gray-700">Project Details</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="exportTickets" name="export_tickets"
                                    class="w-4 h-4 text-secondary border-gray-300 rounded focus:ring-secondary">
                                <label for="exportTickets" class="ml-2 text-sm text-gray-700">Ticket Statistics</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="exportUsers" name="export_users"
                                    class="w-4 h-4 text-secondary border-gray-300 rounded focus:ring-secondary">
                                <label for="exportUsers" class="ml-2 text-sm text-gray-700">User Assignments</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Date Range</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input type="date" id="exportStartDate"
                                    class="w-full p-2 bg-gray-50 border border-gray-300 rounded text-sm">
                            </div>
                            <div>
                                <input type="date" id="exportEndDate"
                                    class="w-full p-2 bg-gray-50 border border-gray-300 rounded text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button type="button" class="close-export-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </button>
                <button type="button" id="confirmExport" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Modal Styles */
        .fixed {
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Step Indicators */
        .step-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }

        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            border: 2px solid #E5E7EB;
            background: white;
            color: #9CA3AF;
            transition: all 0.3s ease;
        }

        .step-indicator.active .step-number {
            border-color: #665C9E;
            background: #665C9E;
            color: white;
        }

        .step-label {
            font-size: 12px;
            color: #9CA3AF;
            font-weight: 500;
        }

        .step-indicator.active .step-label {
            color: #665C9E;
            font-weight: 600;
        }

        .step-connector {
            flex: 1;
            height: 2px;
            background: #E5E7EB;
            margin-top: 15px;
            margin-left: -8px;
            margin-right: -8px;
        }

        /* Selection Items */
        .bulk-selection-item {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .bulk-selection-item:hover {
            background: #F9FAFB;
            border-color: #E5E7EB;
        }

        .bulk-selection-item.selected {
            background: #EEF2FF;
            border-color: #665C9E;
        }

        /* Checkbox Custom Style */
        .custom-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid #D1D5DB;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .custom-checkbox.checked {
            background: #665C9E;
            border-color: #665C9E;
        }

        .custom-checkbox.checked i {
            color: white;
            font-size: 12px;
        }

        /* Status Badges */
        .status-badge-modal {
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Progress Bar Animation */
        .progress-bar {
            transition: width 0.3s ease;
        }

        /* File Upload Dropzone */
        #importDropZone.dragover {
            border-color: #665C9E;
            background: #F8F7FC;
        }

        /* Action Menu */
        .menu-item {
            transition: all 0.2s ease;
        }

        .menu-item:hover {
            background: linear-gradient(90deg, rgba(102, 92, 158, 0.05), rgba(102, 92, 158, 0.02));
        }

        .menu-item:active {
            background: linear-gradient(90deg, rgba(102, 92, 158, 0.1), rgba(102, 92, 158, 0.05));
        }

        /* Chip Styles */
        .chip {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            background: #F3F4F6;
            border-radius: 9999px;
            font-size: 12px;
            color: #374151;
            margin: 2px;
        }

        .chip-remove {
            margin-left: 6px;
            cursor: pointer;
            color: #9CA3AF;
        }

        .chip-remove:hover {
            color: #EF4444;
        }

        /* Format Options */
        .format-option {
            transition: all 0.2s ease;
        }

        .format-option.active {
            border-color: #665C9E;
            background: linear-gradient(135deg, rgba(102, 92, 158, 0.1), rgba(102, 92, 158, 0.05));
        }

        .format-option:not(.active):hover {
            border-color: #D1D5DB;
            background: #F9FAFB;
        }

        /* Role Filter Buttons */
        .role-filter-btn.active {
            background: linear-gradient(135deg, #665C9E, #817CB2);
            color: white;
        }

        .role-filter-btn:not(.active):hover {
            background: #E5E7EB;
        }
    </style>

    <script>
/**
 * Project Modal JavaScript Functions dengan jQuery
 * This script handles all modal interactions for project management
 */

$(document).ready(function() {
    // CSRF Configuration
    const csrfToken = '<?= csrf_hash() ?>';
    const csrfName = '<?= csrf_token() ?>';
    const baseUrl = '<?= base_url() ?>';

    // State variables
    let currentProjectId = null;
    let currentProjectData = null;
    let bulkStep = 1;
    let selectedBulkProjects = [];
    let selectedBulkUsers = [];
    let importFile = null;

    // Initialize all modals
    initializeAddProjectModal();
    initializeEditProjectModal();
    initializeDeleteProjectModal();
    initializeBulkAssignModal();
    initializeStatusChangeModal();
    initializeProjectActionsMenu();

    // ==================== UTILITY FUNCTIONS ====================
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function showToast(message, type = 'info') {
        // Remove existing toasts
        $('.custom-toast').remove();

        const icons = {
            'error': 'fa-exclamation-circle',
            'success': 'fa-check-circle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };

        const colors = {
            'error': 'bg-red-500 text-white',
            'success': 'bg-green-500 text-white',
            'warning': 'bg-yellow-500 text-white',
            'info': 'bg-blue-500 text-white'
        };

        const toast = $(`
            <div class="custom-toast fixed top-24 right-6 p-4 rounded-lg shadow-lg z-[2000] max-w-sm ${colors[type]}">
                <div class="flex items-center gap-3">
                    <i class="fas ${icons[type]}"></i>
                    <span class="text-sm">${message}</span>
                </div>
            </div>
        `);

        $('body').append(toast);

        setTimeout(() => {
            toast.css({
                'opacity': '0',
                'transform': 'translateY(-10px)',
                'transition': 'all 0.3s ease'
            });
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }

    // ==================== ADD PROJECT MODAL ====================
    function initializeAddProjectModal() {
        const $modal = $('#addProjectModal');
        if ($modal.length === 0) return;

        // Open modal from external button
        $(document).on('click', '#addProjectBtn', function(e) {
            e.preventDefault();
            showAddProjectModal();
        });

        // Close modal
        $modal.find('.close-modal').on('click', function() {
            $modal.addClass('hidden');
            resetAddProjectForm();
        });

        // Project code validation with debounce
        const debouncedValidate = debounce(function() {
            const code = $(this).val().trim().toUpperCase();
            validateProjectCode(code);
        }, 500);

        $modal.find('#projectCode').on('input', debouncedValidate);

        // Auto-update ticket format preview
        $modal.find('#projectCode').on('input', function() {
            const code = $(this).val().trim().toUpperCase() || 'PROJ001';
            $('#ticketFormatPreview').text(`${code}-001`);
        });

        // Confirm add project
        $modal.find('#confirmAddProject').on('click', handleAddProject);
    }

    function showAddProjectModal() {
        const $modal = $('#addProjectModal');
        if ($modal.length) {
            $modal.removeClass('hidden');
            $('body').css('overflow', 'hidden');
            setTimeout(() => $modal.find('#projectName').focus(), 100);
        }
    }

    async function validateProjectCode(code) {
        console.log('Validating project code:', code);

        const $statusEl = $('#projectCodeStatus');
        const $errorEl = $('#projectCodeError');

        // Clear previous messages
        $errorEl.addClass('hidden');
        $statusEl.addClass('hidden');

        // Basic validation
        if (!code || typeof code !== 'string' || code.trim().length < 2) {
            $errorEl.text('Project code must be at least 2 characters').removeClass('hidden');
            console.log('Validation failed: too short');
            return false;
        }

        // Format validation
        const codeRegex = /^[A-Z0-9]{2,20}$/;
        const cleanCode = code.trim().toUpperCase();

        if (!codeRegex.test(cleanCode)) {
            $errorEl.text('Use only uppercase letters and numbers (2-20 chars)').removeClass('hidden');
            console.log('Validation failed: invalid format');
            return false;
        }

        try {
            console.log('Sending AJAX request...');

            const formData = new FormData();
            formData.append(csrfName, csrfToken);
            formData.append('action', 'validate_project_code');
            formData.append('project_code', cleanCode);

            const response = await fetch(`${baseUrl}/admin/projects/ajax-manage`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            console.log('Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Response data:', data);

            if (data.success) {
                $statusEl.removeClass('hidden').html('<i class="fas fa-check-circle text-green-500"></i>');
                $errorEl.addClass('hidden');
                console.log('Validation successful');
                return true;
            } else {
                $statusEl.addClass('hidden');
                $errorEl.text(data.message || 'Project code already exists').removeClass('hidden');
                console.log('Validation failed:', data.message);
                return false;
            }
        } catch (error) {
            console.error('Validation error:', error);
            $errorEl.text('Network error. Please check connection.').removeClass('hidden');
            return false;
        }
    }

    async function handleAddProject() {
        const $modal = $('#addProjectModal');
        const $form = $modal.find('#addProjectForm');
        const $confirmBtn = $modal.find('#confirmAddProject');
        const originalHtml = $confirmBtn.html();

        // Validate required fields
        const projectName = $modal.find('#projectName').val().trim();
        const projectCode = $modal.find('#projectCode').val().trim().toUpperCase();

        if (!projectName || !projectCode) {
            showToast('Please fill in all required fields', 'error');
            return;
        }

        // Validate project code
        if (!await validateProjectCode(projectCode)) {
            return;
        }

        try {
            // Disable button and show loading
            $confirmBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Creating...');

            const formData = new FormData($form[0]);
            formData.append(csrfName, csrfToken);
            formData.append('action', 'create_project');

            const response = await fetch(`${baseUrl}/admin/projects/ajax-manage`, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                $modal.addClass('hidden');
                resetAddProjectForm();

                // Refresh projects list
                if (typeof window.loadProjectsData === 'function') {
                    window.loadProjectsData();
                }
            } else {
                showToast(data.message, 'error');
            }
        } catch (error) {
            console.error('Add project error:', error);
            showToast('Failed to create project', 'error');
        } finally {
            $confirmBtn.prop('disabled', false).html(originalHtml);
        }
    }

    function resetAddProjectForm() {
        const $modal = $('#addProjectModal');
        if ($modal.length) {
            $modal.find('#addProjectForm')[0].reset();
            $('#projectCodeStatus, #projectCodeError').addClass('hidden');
            $('#ticketFormatPreview').text('PROJ001-001');
        }
    }

    // ==================== EDIT PROJECT MODAL ====================
    function initializeEditProjectModal() {
        const $modal = $('#editProjectModal');
        if ($modal.length === 0) return;

        // Close modal
        $modal.find('.close-edit-modal').on('click', function() {
            $modal.addClass('hidden');
            currentProjectId = null;
            currentProjectData = null;
        });

        // Project code validation with debounce
        const debouncedValidate = debounce(function() {
            const code = $(this).val().trim().toUpperCase();
            validateEditProjectCode(code, currentProjectId);
        }, 500);

        $modal.find('#editProjectCode').on('input', debouncedValidate);

        // Confirm edit project
        $modal.find('#confirmEditProject').on('click', handleEditProject);

        // Open edit modal from external buttons
        $(document).on('click', '.edit-project-btn, .menu-item.edit-project', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const projectId = $btn.data('project-id') || currentProjectId;
            if (projectId) {
                showEditProjectModal(projectId);
            }
        });
    }

    async function showEditProjectModal(projectId) {
        const $modal = $('#editProjectModal');
        if ($modal.length === 0) return;

        try {
            // Show loading state
            $modal.removeClass('hidden');
            $modal.find('#confirmEditProject').prop('disabled', true);

            // Fetch project data
            const response = await fetch(`${baseUrl}/admin/projects/ajax-manage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    [csrfName]: csrfToken,
                    action: 'get_project_details',
                    project_id: projectId
                })
            });

            const data = await response.json();

            if (data.success) {
                currentProjectId = projectId;
                currentProjectData = data.project;

                // Populate form
                $modal.find('#editProjectId').val(projectId);
                $modal.find('#editProjectName').val(data.project.project_name || '');
                $modal.find('#editProjectCode').val(data.project.project_code || '');
                $modal.find('#editProjectDescription').val(data.project.description || '');
                $modal.find('#editProjectStatus').val(data.project.is_active ? '1' : '0');

                // Set info fields
                const createdDate = data.project.created_at ? new Date(data.project.created_at) : new Date();
                $modal.find('#editProjectCreated').text(
                    createdDate.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    })
                );

                $modal.find('#editProjectTickets').text(data.project.total_tickets || 0);
                $modal.find('#editProjectUsers').text(data.assigned_users?.length || 0);

                // Update subtitle
                $modal.find('#editProjectSubtitle').text(`Editing: ${data.project.project_code}`);

                // Enable button
                $modal.find('#confirmEditProject').prop('disabled', false);

                // Focus on name field
                setTimeout(() => $modal.find('#editProjectName').focus(), 100);
            } else {
                showToast(data.message, 'error');
                $modal.addClass('hidden');
            }
        } catch (error) {
            console.error('Error loading project data:', error);
            showToast('Failed to load project data', 'error');
            $modal.addClass('hidden');
        }
    }

    async function validateEditProjectCode(code, projectId) {
        const $statusEl = $('#editProjectCodeStatus');
        const $errorEl = $('#editProjectCodeError');

        // Clear previous messages
        $errorEl.addClass('hidden');
        $statusEl.addClass('hidden');

        // Validate input
        if (!code || typeof code !== 'string' || code.trim().length < 2) {
            $errorEl.text('Project code must be at least 2 characters').removeClass('hidden');
            return false;
        }

        // Format validation
        const codeRegex = /^[A-Z0-9]{2,20}$/;
        if (!codeRegex.test(code)) {
            $errorEl.text('Use only uppercase letters and numbers (2-20 chars)').removeClass('hidden');
            return false;
        }

        try {
            const response = await fetch(`${baseUrl}/admin/projects/ajax-manage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    [csrfName]: csrfToken,
                    action: 'validate_project_code',
                    project_code: code,
                    project_id: projectId
                })
            });

            const data = await response.json();

            if (data.success) {
                $statusEl.removeClass('hidden').html('<i class="fas fa-check-circle text-green-500"></i>');
                $errorEl.addClass('hidden');
                return true;
            } else {
                $statusEl.addClass('hidden');
                $errorEl.text(data.message || 'Project code already exists').removeClass('hidden');
                return false;
            }
        } catch (error) {
            console.error('Validation error:', error);
            $errorEl.text('Network error. Please try again.').removeClass('hidden');
            return false;
        }
    }

    async function handleEditProject() {
        const $modal = $('#editProjectModal');
        const $form = $modal.find('#editProjectForm');
        const $confirmBtn = $modal.find('#confirmEditProject');
        const originalHtml = $confirmBtn.html();

        // Validate
        const projectName = $modal.find('#editProjectName').val().trim();
        const projectCode = $modal.find('#editProjectCode').val().trim().toUpperCase();

        if (!projectName || !projectCode) {
            showToast('Please fill in all required fields', 'error');
            return;
        }

        if (!await validateEditProjectCode(projectCode, currentProjectId)) {
            return;
        }

        try {
            $confirmBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...');

            const formData = new FormData($form[0]);
            formData.append(csrfName, csrfToken);
            formData.append('action', 'update_project');
            formData.append('project_id', currentProjectId);

            const response = await fetch(`${baseUrl}/admin/projects/ajax-manage`, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                $modal.addClass('hidden');

                // Refresh data
                if (typeof window.loadProjectsData === 'function') {
                    window.loadProjectsData();
                }

                // If current project is being edited, refresh details
                if (typeof window.loadProjectDetails === 'function') {
                    window.loadProjectDetails(currentProjectId);
                }
            } else {
                showToast(data.message, 'error');
            }
        } catch (error) {
            console.error('Edit project error:', error);
            showToast('Failed to update project', 'error');
        } finally {
            $confirmBtn.prop('disabled', false).html(originalHtml);
        }
    }

    // ==================== DELETE PROJECT MODAL ====================
    function initializeDeleteProjectModal() {
        const $modal = $('#deleteProjectModal');
        if ($modal.length === 0) return;

        // Close modal
        $modal.find('.close-delete-modal').on('click', function() {
            $modal.addClass('hidden');
            resetDeleteModal();
        });

        // Confirmation input
        $modal.find('#deleteConfirmInput').on('input', function() {
            const $confirmBtn = $modal.find('#confirmDeleteProject');
            $confirmBtn.prop('disabled', $(this).val().toUpperCase() !== 'DELETE');
        });

        // Confirm delete
        $modal.find('#confirmDeleteProject').on('click', handleDeleteProject);

        // Open delete modal from external
        $(document).on('click', '.menu-item.delete-project', function(e) {
            e.preventDefault();
            const $item = $(this);
            const projectId = $item.data('project-id') || currentProjectId;
            if (projectId) {
                showDeleteProjectModal(projectId);
            }
        });
    }

    async function showDeleteProjectModal(projectId) {
        const $modal = $('#deleteProjectModal');
        if ($modal.length === 0) return;

        try {
            // Fetch project details for confirmation
            const response = await fetch(`${baseUrl}/admin/projects/ajax-manage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    [csrfName]: csrfToken,
                    action: 'get_project_details',
                    project_id: projectId
                })
            });

            const data = await response.json();

            if (data.success) {
                currentProjectId = projectId;

                // Populate modal
                const project = data.project;
                $modal.find('#deleteProjectName').text(project.project_name);
                $modal.find('#deleteProjectMessage').html(
                    `You are about to delete the project "<span class="font-semibold">${project.project_name}</span>" (${project.project_code}).`
                );

                // Check for dependencies
                const $warningsDiv = $modal.find('#deleteProjectWarnings');
                const $forceDeleteOption = $modal.find('#forceDeleteOption');
                const ticketCount = project.total_tickets || 0;
                const assignmentCount = data.assigned_users?.length || 0;

                if (ticketCount > 0 || assignmentCount > 0) {
                    $warningsDiv.removeClass('hidden');
                    $forceDeleteOption.removeClass('hidden');

                    if (ticketCount > 0) {
                        $('#warningTickets').removeClass('hidden');
                        $('#ticketCount').text(ticketCount);
                    }

                    if (assignmentCount > 0) {
                        $('#warningAssignments').removeClass('hidden');
                        $('#assignmentCount').text(assignmentCount);
                    }
                } else {
                    $warningsDiv.addClass('hidden');
                    $forceDeleteOption.addClass('hidden');
                }

                // Show modal
                $modal.removeClass('hidden');

                // Focus on confirmation input
                setTimeout(() => {
                    $modal.find('#deleteConfirmInput').focus();
                }, 100);
            } else {
                showToast(data.message, 'error');
            }
        } catch (error) {
            console.error('Error loading project data:', error);
            showToast('Failed to load project data', 'error');
        }
    }

    function resetDeleteModal() {
        const $modal = $('#deleteProjectModal');
        if ($modal.length) {
            $modal.find('#deleteConfirmInput').val('');
            $modal.find('#confirmDeleteProject').prop('disabled', true);
            $modal.find('#deleteProjectWarnings').addClass('hidden');
            $modal.find('#forceDeleteOption').addClass('hidden');
            $('#warningTickets, #warningAssignments').addClass('hidden');
            $modal.find('#forceDelete').prop('checked', false);
        }
    }

    async function handleDeleteProject() {
        const $modal = $('#deleteProjectModal');
        const $confirmBtn = $modal.find('#confirmDeleteProject');
        const originalHtml = $confirmBtn.html();
        const forceDelete = $modal.find('#forceDelete').is(':checked');

        try {
            $confirmBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Deleting...');

            const response = await fetch(`${baseUrl}/admin/projects/ajax-manage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    [csrfName]: csrfToken,
                    action: 'delete_project',
                    project_id: currentProjectId,
                    force_delete: forceDelete ? '1' : '0'
                })
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                $modal.addClass('hidden');
                resetDeleteModal();

                // Refresh projects list
                if (typeof window.loadProjectsData === 'function') {
                    window.loadProjectsData();
                }

                // Clear current selection
                currentProjectId = null;
                if (typeof window.clearProjectSelection === 'function') {
                    window.clearProjectSelection();
                }
            } else {
                showToast(data.message, 'error');
            }
        } catch (error) {
            console.error('Delete project error:', error);
            showToast('Failed to delete project', 'error');
        } finally {
            $confirmBtn.prop('disabled', false).html(originalHtml);
        }
    }

    // ==================== BULK ASSIGN MODAL ====================
    function initializeBulkAssignModal() {
        const $modal = $('#bulkAssignModal');
        if ($modal.length === 0) return;

        // Open from external button
        $(document).on('click', '#bulkAssignBtn', function(e) {
            e.preventDefault();
            showBulkAssignModal();
        });

        // Close modal
        $modal.find('.close-bulk-modal').on('click', function() {
            $modal.addClass('hidden');
            resetBulkAssignModal();
        });

        // Navigation
        $modal.find('#bulkPrevStep').on('click', prevBulkStep);
        $modal.find('#bulkNextStep').on('click', nextBulkStep);
        $modal.find('#bulkConfirmAssign').on('click', confirmBulkAssign);

        // Select all/deselect all
        $modal.find('#selectAllProjects').on('click', selectAllProjects);
        $modal.find('#deselectAllProjects').on('click', deselectAllProjects);
        $modal.find('#selectAllUsers').on('click', selectAllUsers);
        $modal.find('#deselectAllUsers').on('click', deselectAllUsers);

        // View selected items
        $modal.find('#viewSelectedProjects').on('click', showSelectedProjects);
        $modal.find('#viewSelectedUsers').on('click', showSelectedUsers);

        // Role filter
        $modal.find('.role-filter-btn').on('click', function() {
            const $btn = $(this);
            $modal.find('.role-filter-btn').removeClass('active');
            $btn.addClass('active');
            filterUsersByRole($btn.data('role'));
        });

        // Search with debounce
        let searchTimeout;
        $modal.find('#bulkProjectSearch').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadBulkProjects(), 300);
        });

        $modal.find('#bulkUserSearch').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadBulkUsers(), 300);
        });
    }

    function showBulkAssignModal() {
        const $modal = $('#bulkAssignModal');
        if ($modal.length) {
            $modal.removeClass('hidden');
            $('body').css('overflow', 'hidden');

            // Reset to step 1
            bulkStep = 1;
            selectedBulkProjects = [];
            selectedBulkUsers = [];

            // Load initial data
            loadBulkProjects();
            updateBulkStepUI();
        }
    }

    function resetBulkAssignModal() {
        bulkStep = 1;
        selectedBulkProjects = [];
        selectedBulkUsers = [];

        const $modal = $('#bulkAssignModal');
        if ($modal.length) {
            $modal.find('#bulkProjectSearch').val('');
            $modal.find('#bulkUserSearch').val('');
            $modal.find('#sendNotifications').prop('checked', true);
            $modal.find('#overwriteAssignments').prop('checked', false);
        }
    }

    // ==================== BULK ASSIGN FUNCTIONS ====================
    function loadBulkProjects() {
        const $modal = $('#bulkAssignModal');
        if ($modal.length === 0) return;

        const search = $modal.find('#bulkProjectSearch').val() || '';
        const $projectsList = $modal.find('#bulkProjectsList');

        $.ajax({
            url: baseUrl + '/admin/projects/ajax-manage',
            method: 'POST',
            data: {
                [csrfName]: csrfToken,
                action: 'get_projects_for_bulk',
                search: search
            },
            dataType: 'json',
            success: function(data) {
                if (data.success && data.projects) {
                    renderBulkProjects(data.projects);
                }
            },
            error: function() {
                $projectsList.html('<p class="text-red-500 text-center py-4">Failed to load projects</p>');
            }
        });
    }

    function renderBulkProjects(projects) {
        const $projectsList = $('#bulkProjectsList');
        if ($projectsList.length === 0) return;

        let html = '';

        projects.forEach(project => {
            const isSelected = selectedBulkProjects.includes(project.project_id);
            html += `
                <div class="bulk-selection-item ${isSelected ? 'selected' : ''}" 
                     data-project-id="${project.project_id}">
                    <div class="flex items-center gap-3">
                        <div class="custom-checkbox ${isSelected ? 'checked' : ''}">
                            ${isSelected ? '<i class="fas fa-check"></i>' : ''}
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">${escapeHtml(project.project_name)}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(project.project_code)}</div>
                        </div>
                    </div>
                    <div class="text-xs px-2 py-1 rounded ${project.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                        ${project.is_active ? 'Active' : 'Inactive'}
                    </div>
                </div>
            `;
        });

        $projectsList.html(html);

        // Add click handlers
        $projectsList.find('.bulk-selection-item').off('click').on('click', function() {
            const $item = $(this);
            const projectId = parseInt($item.data('project-id'));
            toggleBulkProject(projectId, $item);
        });

        updateProjectsSummary();
    }

    function toggleBulkProject(projectId, $element) {
        const index = selectedBulkProjects.indexOf(projectId);
        const $checkbox = $element.find('.custom-checkbox');

        if (index === -1) {
            selectedBulkProjects.push(projectId);
            $element.addClass('selected');
            $checkbox.addClass('checked').html('<i class="fas fa-check"></i>');
        } else {
            selectedBulkProjects.splice(index, 1);
            $element.removeClass('selected');
            $checkbox.removeClass('checked').html('');
        }

        updateProjectsSummary();
        updateBulkNavigation();
    }

    function loadBulkUsers() {
        const $modal = $('#bulkAssignModal');
        if ($modal.length === 0) return;

        const search = $modal.find('#bulkUserSearch').val() || '';
        const role = $modal.find('.role-filter-btn.active').data('role') || 'all';
        const $usersList = $modal.find('#bulkUsersList');

        $.ajax({
            url: baseUrl + '/admin/projects/ajax-manage',
            method: 'POST',
            data: {
                [csrfName]: csrfToken,
                action: 'get_all_users',
                search: search,
                role: role
            },
            dataType: 'json',
            success: function(data) {
                if (data.success && data.users) {
                    renderBulkUsers(data.users);
                }
            },
            error: function() {
                $usersList.html('<p class="text-red-500 text-center py-4">Failed to load users</p>');
            }
        });
    }

    function renderBulkUsers(users) {
        const $usersList = $('#bulkUsersList');
        if ($usersList.length === 0) return;

        let html = '';

        users.forEach(user => {
            const isSelected = selectedBulkUsers.includes(user.user_id);
            const initial = (user.full_name?.charAt(0) || user.username?.charAt(0) || '?').toUpperCase();

            html += `
                <div class="bulk-selection-item ${isSelected ? 'selected' : ''}" 
                     data-user-id="${user.user_id}">
                    <div class="flex items-center gap-3">
                        <div class="custom-checkbox ${isSelected ? 'checked' : ''}">
                            ${isSelected ? '<i class="fas fa-check"></i>' : ''}
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-semibold">${escapeHtml(initial)}</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">${escapeHtml(user.full_name || user.username)}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(user.email || '')}</div>
                            <div class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded inline-block mt-1">
                                ${escapeHtml(user.role_name || 'User')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        $usersList.html(html);

        // Add click handlers
        $usersList.find('.bulk-selection-item').off('click').on('click', function() {
            const $item = $(this);
            const userId = parseInt($item.data('user-id'));
            toggleBulkUser(userId, $item);
        });

        updateUsersSummary();
    }

    function toggleBulkUser(userId, $element) {
        const index = selectedBulkUsers.indexOf(userId);
        const $checkbox = $element.find('.custom-checkbox');

        if (index === -1) {
            selectedBulkUsers.push(userId);
            $element.addClass('selected');
            $checkbox.addClass('checked').html('<i class="fas fa-check"></i>');
        } else {
            selectedBulkUsers.splice(index, 1);
            $element.removeClass('selected');
            $checkbox.removeClass('checked').html('');
        }

        updateUsersSummary();
        updateBulkNavigation();
    }

    function confirmBulkAssign() {
        const $modal = $('#bulkAssignModal');
        const $confirmBtn = $modal.find('#bulkConfirmAssign');
        const originalHtml = $confirmBtn.html();

        // Validation
        if (selectedBulkProjects.length === 0 || selectedBulkUsers.length === 0) {
            showToast('Please select at least one project and one user', 'error');
            return;
        }

        // Prepare data
        const formData = {
            [csrfName]: csrfToken,
            action: 'bulk_assign_projects',
            project_ids: JSON.stringify(selectedBulkProjects),
            user_ids: JSON.stringify(selectedBulkUsers),
            send_notifications: $modal.find('#sendNotifications').is(':checked') ? '1' : '0',
            overwrite_assignments: $modal.find('#overwriteAssignments').is(':checked') ? '1' : '0'
        };

        // Update button state
        $confirmBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Assigning...');

        $.ajax({
            url: baseUrl + '/admin/projects/ajax-manage',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    showToast(data.message, 'success');
                    $modal.addClass('hidden');
                    resetBulkAssignModal();

                    // Refresh projects list
                    if (typeof window.loadProjectsData === 'function') {
                        window.loadProjectsData();
                    }
                } else {
                    showToast(data.message || 'Failed to assign users', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Bulk assign error:', error);
                showToast('Failed to assign users. Please try again.', 'error');
            },
            complete: function() {
                $confirmBtn.prop('disabled', false).html(originalHtml);
            }
        });
    }

    function selectAllProjects() {
        $('#bulkProjectsList .bulk-selection-item').each(function() {
            const $item = $(this);
            const projectId = parseInt($item.data('project-id'));

            if (!selectedBulkProjects.includes(projectId)) {
                selectedBulkProjects.push(projectId);
                $item.addClass('selected');
                $item.find('.custom-checkbox').addClass('checked').html('<i class="fas fa-check"></i>');
            }
        });

        updateProjectsSummary();
        updateBulkNavigation();
    }

    function deselectAllProjects() {
        selectedBulkProjects = [];
        $('#bulkProjectsList .bulk-selection-item').removeClass('selected')
            .find('.custom-checkbox').removeClass('checked').html('');

        updateProjectsSummary();
        updateBulkNavigation();
    }

    function selectAllUsers() {
        $('#bulkUsersList .bulk-selection-item').each(function() {
            const $item = $(this);
            const userId = parseInt($item.data('user-id'));

            if (!selectedBulkUsers.includes(userId)) {
                selectedBulkUsers.push(userId);
                $item.addClass('selected');
                $item.find('.custom-checkbox').addClass('checked').html('<i class="fas fa-check"></i>');
            }
        });

        updateUsersSummary();
        updateBulkNavigation();
    }

    function deselectAllUsers() {
        selectedBulkUsers = [];
        $('#bulkUsersList .bulk-selection-item').removeClass('selected')
            .find('.custom-checkbox').removeClass('checked').html('');

        updateUsersSummary();
        updateBulkNavigation();
    }

    function updateProjectsSummary() {
        const $summaryDiv = $('#selectedProjectsSummary');
        const $countSpan = $('#selectedProjectsCount');

        if (selectedBulkProjects.length > 0) {
            $summaryDiv.removeClass('hidden');
            $countSpan.text(selectedBulkProjects.length);
        } else {
            $summaryDiv.addClass('hidden');
        }
    }

    function updateUsersSummary() {
        const $summaryDiv = $('#selectedUsersSummary');
        const $countSpan = $('#selectedUsersCount');

        if (selectedBulkUsers.length > 0) {
            $summaryDiv.removeClass('hidden');
            $countSpan.text(selectedBulkUsers.length);
        } else {
            $summaryDiv.addClass('hidden');
        }
    }

    function updateBulkSummary() {
        const projectsCount = selectedBulkProjects.length;
        const usersCount = selectedBulkUsers.length;

        $('#summaryProjects').text(projectsCount);
        $('#summaryUsers').text(usersCount);
        $('#summaryTotalAssignments').text(projectsCount * usersCount);

        // Update project list
        let projectsHtml = '';
        selectedBulkProjects.forEach(projectId => {
            const $project = $(`#bulkProjectsList [data-project-id="${projectId}"]`);
            const name = $project.find('.font-medium').text();
            const code = $project.find('.text-xs').text();

            projectsHtml += `<span class="chip">${escapeHtml(code)}: ${escapeHtml(name)}</span>`;
        });
        $('#summaryProjectList').html(projectsHtml || '<span class="text-gray-500">No projects selected</span>');

        // Update user list
        let usersHtml = '';
        selectedBulkUsers.forEach(userId => {
            const $user = $(`#bulkUsersList [data-user-id="${userId}"]`);
            const name = $user.find('.font-medium').text();

            usersHtml += `<span class="chip">${escapeHtml(name)}</span>`;
        });
        $('#summaryUserList').html(usersHtml || '<span class="text-gray-500">No users selected</span>');
    }

    function updateBulkStepUI() {
        const $modal = $('#bulkAssignModal');
        if ($modal.length === 0) return;

        // Update step indicators
        $modal.find('.step-indicator').each(function(index) {
            const $indicator = $(this);
            const step = index + 1;

            if (step < bulkStep) {
                $indicator.addClass('completed').removeClass('active');
            } else if (step === bulkStep) {
                $indicator.addClass('active').removeClass('completed');
            } else {
                $indicator.removeClass('active completed');
            }
        });

        // Show/hide steps
        $modal.find('.bulk-modal-step').each(function(index) {
            const $step = $(this);
            if (index + 1 === bulkStep) {
                $step.removeClass('hidden');
            } else {
                $step.addClass('hidden');
            }
        });

        // Update buttons
        $modal.find('#bulkPrevStep').toggleClass('hidden', bulkStep === 1);
        $modal.find('#bulkNextStep').toggleClass('hidden', bulkStep === 3);
        $modal.find('#bulkConfirmAssign').toggleClass('hidden', bulkStep !== 3);

        // Load data for current step
        switch (bulkStep) {
            case 1:
                loadBulkProjects();
                break;
            case 2:
                loadBulkUsers();
                break;
            case 3:
                updateBulkSummary();
                break;
        }
    }

    function prevBulkStep() {
        if (bulkStep > 1) {
            bulkStep--;
            updateBulkStepUI();
        }
    }

    function nextBulkStep() {
        if (bulkStep === 1) {
            if (selectedBulkProjects.length === 0) {
                showToast('Please select at least one project', 'error');
                return;
            }
            bulkStep = 2;
            loadBulkUsers();
        } else if (bulkStep === 2) {
            if (selectedBulkUsers.length === 0) {
                showToast('Please select at least one user', 'error');
                return;
            }
            bulkStep = 3;
        }
        updateBulkStepUI();
    }

    function updateBulkNavigation() {
        const $nextBtn = $('#bulkNextStep');
        if ($nextBtn.length && bulkStep === 1) {
            $nextBtn.prop('disabled', selectedBulkProjects.length === 0);
        }
    }

    function filterUsersByRole(role) {
        // Filter users based on role
        $('#bulkUsersList .bulk-selection-item').each(function() {
            const $item = $(this);
            const userRole = $item.find('.text-xs.bg-gray-100').text().toLowerCase();
            
            if (role === 'all' || userRole.includes(role.toLowerCase())) {
                $item.removeClass('hidden');
            } else {
                $item.addClass('hidden');
            }
        });
    }

    function showSelectedProjects() {
        if (selectedBulkProjects.length === 0) {
            showToast('No projects selected', 'warning');
            return;
        }

        // Create modal HTML
        const modalHtml = `
            <div id="selectedProjectsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[2000] p-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[80vh] flex flex-col">
                    <div class="p-6 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">Selected Projects</h3>
                            <button type="button" class="close-selected-projects text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">${selectedBulkProjects.length} project(s) selected</p>
                    </div>
                    
                    <div class="p-6 overflow-y-auto flex-grow">
                        <div class="space-y-3">
                            ${generateSelectedProjectsList()}
                        </div>
                    </div>
                    
                    <div class="p-6 border-t">
                        <button type="button" class="close-selected-projects w-full py-2 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Add modal to body
        $('body').append(modalHtml);

        // Add event listener
        $('#selectedProjectsModal .close-selected-projects').on('click', closeSelectedProjectsModal);
    }

    function generateSelectedProjectsList() {
        let html = '';
        selectedBulkProjects.forEach(projectId => {
            const $project = $(`#bulkProjectsList [data-project-id="${projectId}"]`);
            const name = $project.find('.font-medium').text();
            const code = $project.find('.text-xs').text();
            
            html += `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium text-gray-800">${escapeHtml(name)}</div>
                        <div class="text-sm text-gray-500">${escapeHtml(code)}</div>
                    </div>
                    <button type="button" class="remove-project text-red-500 hover:text-red-700" data-project-id="${projectId}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        });
        return html;
    }

    function closeSelectedProjectsModal() {
        $('#selectedProjectsModal').remove();
    }

    function showSelectedUsers() {
        if (selectedBulkUsers.length === 0) {
            showToast('No users selected', 'warning');
            return;
        }

        // Create modal HTML
        const modalHtml = `
            <div id="selectedUsersModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[2000] p-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[80vh] flex flex-col">
                    <div class="p-6 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">Selected Users</h3>
                            <button type="button" class="close-selected-users text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">${selectedBulkUsers.length} user(s) selected</p>
                    </div>
                    
                    <div class="p-6 overflow-y-auto flex-grow">
                        <div class="space-y-3">
                            ${generateSelectedUsersList()}
                        </div>
                    </div>
                    
                    <div class="p-6 border-t">
                        <button type="button" class="close-selected-users w-full py-2 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Add modal to body
        $('body').append(modalHtml);

        // Add event listener
        $('#selectedUsersModal .close-selected-users').on('click', closeSelectedUsersModal);
    }

    function generateSelectedUsersList() {
        let html = '';
        selectedBulkUsers.forEach(userId => {
            const $user = $(`#bulkUsersList [data-user-id="${userId}"]`);
            const name = $user.find('.font-medium').text();
            const email = $user.find('.text-xs.text-gray-500').text();
            const role = $user.find('.text-xs.bg-gray-100').text();
            
            html += `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-semibold">${escapeHtml(name.charAt(0))}</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">${escapeHtml(name)}</div>
                            <div class="text-sm text-gray-500">${escapeHtml(email)}</div>
                            <div class="text-xs text-gray-600">${escapeHtml(role)}</div>
                        </div>
                    </div>
                    <button type="button" class="remove-user text-red-500 hover:text-red-700" data-user-id="${userId}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        });
        return html;
    }

    function closeSelectedUsersModal() {
        $('#selectedUsersModal').remove();
    }

    // ==================== STATUS CHANGE MODAL ====================
    function initializeStatusChangeModal() {
        const $modal = $('#changeStatusModal');
        if ($modal.length === 0) return;

        // Close modal
        $modal.find('.close-status-modal').on('click', function() {
            $modal.addClass('hidden');
            resetStatusChangeModal();
        });

        // Status option buttons
        $modal.find('.status-option').on('click', function() {
            const $btn = $(this);
            $modal.find('.status-option').removeClass('selected border-secondary').addClass('border-gray-200');
            $btn.addClass('selected border-secondary').removeClass('border-gray-200');
            $modal.find('#confirmChangeStatus').prop('disabled', false);
        });

        // Confirm status change
        $modal.find('#confirmChangeStatus').on('click', handleChangeStatus);

        // Open modal from external buttons
        $(document).on('click', '.toggle-status-btn, .menu-item.change-status', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const projectId = $btn.data('project-id') || currentProjectId;
            const isActive = $btn.hasClass('toggle-status-btn') ? 
                $btn.text().includes('Deactivate') : 
                $btn.closest('.project-row').find('.status-badge').text() === 'Active';
            
            if (projectId) {
                showChangeStatusModal(projectId, !isActive);
            }
        });
    }

    function showChangeStatusModal(projectId, newStatus) {
        const $modal = $('#changeStatusModal');
        if ($modal.length === 0) return;

        // Get project name
        const $projectRow = $(`[data-project-id="${projectId}"]`);
        const projectName = $projectRow.find('.font-medium').text() || 'this project';

        // Update modal content
        $modal.find('#statusModalTitle').text(newStatus ? 'Activate Project' : 'Deactivate Project');
        $modal.find('#statusModalMessage').html(
            newStatus ?
                `Are you sure you want to activate <strong>${escapeHtml(projectName)}</strong>?` :
                `Are you sure you want to deactivate <strong>${escapeHtml(projectName)}</strong>?`
        );

        // Highlight appropriate option
        $modal.find('.status-option').removeClass('selected');
        $modal.find(`.status-option[data-status="${newStatus ? 'active' : 'inactive'}"]`).addClass('selected');

        $modal.data('project-id', projectId);
        $modal.removeClass('hidden');
    }

    async function handleChangeStatus() {
        const $modal = $('#changeStatusModal');
        const $confirmBtn = $modal.find('#confirmChangeStatus');
        const originalHtml = $confirmBtn.html();
        const projectId = $modal.data('project-id');

        const $selectedOption = $modal.find('.status-option.selected');
        if ($selectedOption.length === 0) {
            showToast('Please select a status', 'error');
            return;
        }

        const newStatus = $selectedOption.data('status');

        try {
            $confirmBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Updating...');

            const formData = new FormData();
            formData.append(csrfName, csrfToken);
            formData.append('action', 'change_project_status');
            formData.append('project_id', projectId);
            formData.append('status', newStatus);

            const response = await fetch(`${baseUrl}/admin/projects/ajax-manage`, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                $modal.addClass('hidden');
                resetStatusChangeModal();

                // Refresh projects list
                if (typeof window.loadProjectsData === 'function') {
                    window.loadProjectsData();
                }

                // Refresh project details if viewing
                if (typeof window.loadProjectDetails === 'function') {
                    window.loadProjectDetails(projectId);
                }
            } else {
                showToast(data.message, 'error');
            }
        } catch (error) {
            console.error('Status change error:', error);
            showToast('Failed to update project status', 'error');
        } finally {
            $confirmBtn.prop('disabled', false).html(originalHtml);
        }
    }

    function resetStatusChangeModal() {
        const $modal = $('#changeStatusModal');
        if ($modal.length) {
            $modal.find('.status-option').removeClass('selected border-secondary').addClass('border-gray-200');
            $modal.find('#confirmChangeStatus').prop('disabled', true);
            $modal.removeData('project-id');
        }
    }

    // ==================== PROJECT ACTIONS MENU ====================
    function initializeProjectActionsMenu() {
        const $menu = $('#projectActionsMenu');
        if ($menu.length === 0) return;

        // Close menu when clicking outside
        $(document).on('click', function(e) {
            if (!$menu.is(e.target) && $menu.has(e.target).length === 0 && 
                !$(e.target).closest('.project-action-btn').length) {
                hideProjectActionsMenu();
            }
        });

        // Close menu with Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $menu.is(':visible')) {
                hideProjectActionsMenu();
            }
        });

        // Prevent context menu on menu itself
        $menu.on('contextmenu', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        // Menu item clicks
        $menu.find('.menu-item').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $item = $(this);
            const action = $item.data('action');
            const projectId = $item.data('project-id') || currentProjectId;

            if (!projectId) {
                console.error('No project ID found for action');
                return;
            }

            handleProjectAction(action, projectId);
            hideProjectActionsMenu();
        });
    }

    function showProjectActionsMenu(event, projectId) {
        const $menu = $('#projectActionsMenu');
        if ($menu.length === 0) return;

        // Set project ID on all menu items
        $menu.find('.menu-item').data('project-id', projectId);

        // Position menu
        const $button = $(event.target).closest('.project-action-btn');
        const buttonRect = $button[0].getBoundingClientRect();
        $menu.css({
            top: `${buttonRect.bottom + 5}px`,
            left: `${buttonRect.left - $menu.outerWidth() + 40}px`
        });

        // Show menu
        $menu.removeClass('hidden');
    }

    function hideProjectActionsMenu() {
        $('#projectActionsMenu').addClass('hidden');
    }

    function handleProjectAction(action, projectId) {
        switch (action) {
            case 'view-details':
                if (typeof window.selectProject === 'function') {
                    window.selectProject(projectId);
                }
                break;
            case 'edit-project':
                showEditProjectModal(projectId);
                break;
            case 'manage-users':
                // Implement manage users functionality
                showToast('Manage users functionality not implemented yet', 'warning');
                break;
            case 'export-tickets':
                // Implement export tickets functionality
                showToast('Export tickets functionality not implemented yet', 'warning');
                break;
            case 'delete-project':
                showDeleteProjectModal(projectId);
                break;
        }
    }
});
</script>