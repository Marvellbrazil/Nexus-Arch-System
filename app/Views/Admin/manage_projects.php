<?= $this->extend('layouts/admin_layout') ?>

<?= $this::section('title') ?>Manage Projects - NEXUS Admin<?= $this::endSection() ?>

<?= $this::section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this::endSection() ?>

<?= $this::section('content') ?>
<div class="relative z-10">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-[34.77px] font-semibold mb-2 text-text-dark">Manage Projects</h1>
        <p class="text-[15.45px] font-light text-text-dark">Project management and configuration</p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Content -->
        <div class="lg:col-span-2">
            <!-- Search and Filter Card -->
            <div class="dashboard-card mb-6">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Search & Filter</div>
                </div>

                <div class="p-4 space-y-4">
                    <!-- Search Bar -->
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-muted">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" placeholder="Search projects by name or code..." id="projectSearch"
                            class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                    </div>

                    <!-- Filter Options -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-text-dark/70 text-sm mb-2">Status</label>
                            <select id="statusFilter"
                                class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="completed">Completed</option>
                                <option value="on-hold">On Hold</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div>
                            <label class="block text-text-dark/70 text-sm mb-2">Sort By</label>
                            <select id="sortFilter"
                                class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                                <option value="default">Default</option>
                                <option value="newest">Newest Project</option>
                                <option value="oldest">Oldest Project</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div>
                        <button id="resetFilters"
                            class="w-full h-12 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium">
                            Reset Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Projects Table -->
            <div class="dashboard-card">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">Project Management</div>
                    <div class="text-sm text-secondary font-medium">
                        <span id="showingCount"><span id="totalCount"><?= $total_projects ?? 0 ?></span> projects</span>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <!-- Table Header -->
                    <div class="grid grid-cols-12 gap-4 py-4 px-6 bg-[#E3DAEE] rounded-lg text-sm font-semibold text-text-dark/80">
                        <div class="col-span-1 flex items-center gap-2">
                            <span>No.</span>
                        </div>
                        <div class="col-span-4">Project Name</div>
                        <div class="col-span-2">Project Code</div>
                        <div class="col-span-2">Total Tickets</div>
                        <div class="col-span-3">Status</div>
                    </div>

                    <!-- Projects List -->
                    <div id="projectsList" class="divide-y divide-white/30">
                        <?php if (!empty($projects)): ?>
                            <?php $rowNumber = 1; ?>
                            <?php foreach ($projects as $project): ?>
                                <div class="project-row cursor-pointer hover:bg-[#F8F7FC]"
                                    data-project-id="<?= $project['project_id'] ?>"
                                    onclick="selectProject(<?= $project['project_id'] ?>)">
                                    <!-- No. -->
                                    <div class="col-span-1 text-text-dark/60 font-medium text-center py-4">
                                        <?= $rowNumber++ ?>.
                                    </div>

                                    <!-- Project Name -->
                                    <div class="col-span-4 py-4">
                                        <div class="font-medium text-text-dark"><?= esc($project['project_name']) ?></div>
                                    </div>

                                    <!-- Project Code -->
                                    <div class="col-span-2 py-4">
                                        <div class="font-medium text-secondary"><?= $project['project_code'] ?></div>
                                    </div>

                                    <!-- Total Tickets -->
                                    <div class="col-span-2 py-4">
                                        <div class="font-medium text-text-dark"><?= $project['total_tickets'] ?? 0 ?></div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-span-3 py-4">
                                        <?php
                                        $status = $project['is_active'] ? 'active' : 'inactive';
                                        $statusClass = $status === 'active' ? 'status-active' : ($status === 'completed' ? 'status-completed' : ($status === 'on-hold' ? 'status-onhold' : 'status-inactive'));
                                        ?>
                                        <span class="<?= $statusClass ?> status-badge">
                                            <?= $status === 'active' ? 'Active' : ($status === 'completed' ? 'Completed' : ($status === 'on-hold' ? 'On Hold' : 'Inactive')) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Project Details & Actions -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Quick Actions</div>
                </div>

                <div class="p-4 space-y-3">
                    <button id="addProjectBtn"
                        class="w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-plus text-lg"></i>
                        Add New Project
                    </button>

                    <button id="assignToUsersBtn"
                        class="w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        Assign to Users
                    </button>
                </div>
            </div>

            <!-- Project Detail Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Project Details</div>
                </div>

                <div id="projectDetails" class="p-4">
                    <?php if (!empty($projects)): ?>
                        <?php $firstProject = $projects[0]; ?>
                        <div id="defaultProjectView">
                            <div class="project-avatar"><?= substr($firstProject['project_code'], 0, 2) ?></div>
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-semibold text-text-dark"><?= esc($firstProject['project_name']) ?></h3>
                                <p class="text-text-dark/60 text-sm"><?= $firstProject['project_code'] ?></p>
                                <?php
                                $status = $firstProject['is_active'] ? 'active' : 'inactive';
                                $statusClass = $status === 'active' ? 'status-active' : 'status-inactive';
                                ?>
                                <span class="inline-block mt-2 <?= $statusClass ?> status-badge">
                                    <?= $status === 'active' ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>

                            <div class="space-y-2">
                                <div class="project-info-item">
                                    <span class="text-text-dark/70 text-sm">Description:</span>
                                    <span class="text-text-dark font-medium text-right text-xs"><?= $firstProject['description'] ? esc($firstProject['description']) : 'No description' ?></span>
                                </div>
                                <div class="project-info-item">
                                    <span class="text-text-dark/70 text-sm">Total Tickets:</span>
                                    <span class="text-text-dark font-medium"><?= $firstProject['total_tickets'] ?? 0 ?></span>
                                </div>
                                <div class="project-info-item">
                                    <span class="text-text-dark/70 text-sm">Created:</span>
                                    <span class="text-text-dark font-medium"><?= date('M d, Y', strtotime($firstProject['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                        <!-- Dynamic content will be loaded here -->
                        <div id="dynamicProjectView" class="hidden"></div>
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-project-diagram text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-text-dark/60 text-sm">Select a project to view details</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Project Actions Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Project Actions</div>
                </div>

                <div id="projectActions" class="p-4 space-y-3">
                    <?php if (!empty($projects)): ?>
                        <?php $firstProject = $projects[0]; ?>
                        <div class="space-y-2">
                            <button onclick="editProject(<?= $firstProject['project_id'] ?>)"
                                class="w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
                                <i class="fas fa-edit"></i>
                                Edit Project
                            </button>

                            <button onclick="deleteProject(<?= $firstProject['project_id'] ?>)"
                                class="w-full py-3 bg-red-50 text-red-600 border border-red-200 rounded-xl hover:bg-red-100 transition-colors font-medium flex items-center justify-center gap-2">
                                <i class="fas fa-trash"></i>
                                Delete Project
                            </button>

                            <button onclick="changeStatus(<?= $firstProject['project_id'] ?>, <?= $firstProject['is_active'] ? 'false' : 'true' ?>)"
                                class="w-full py-3 <?= $firstProject['is_active'] ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-green-50 text-green-600 border border-green-200' ?> rounded-xl hover:<?= $firstProject['is_active'] ? 'bg-red-100' : 'bg-green-100' ?> transition-colors font-medium flex items-center justify-center gap-2">
                                <i class="fas fa-power-off"></i>
                                <?= $firstProject['is_active'] ? 'Deactivate Project' : 'Activate Project' ?>
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="space-y-2">
                            <button class="w-full py-3 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed" disabled>
                                <i class="fas fa-edit mr-2"></i>
                                Edit Project
                            </button>
                            <button class="w-full py-3 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed" disabled>
                                <i class="fas fa-trash mr-2"></i>
                                Delete Project
                            </button>
                            <button class="w-full py-3 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed" disabled>
                                <i class="fas fa-power-off mr-2"></i>
                                Change Status
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODAL DIALOGS ==================== -->
<!-- Add New Project Modal -->
<div id="addProjectModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800">Add New Project</h3>
            </div>
            <p class="text-gray-500 text-sm mt-1">Create a new project for ticket management</p>
        </div>

        <div class="p-6">
            <form id="addProjectForm" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="add_project">

                <!-- Project Name -->
                <div>
                    <label for="newProjectName" class="block text-gray-700 text-sm font-medium mb-2">
                        Project Name
                    </label>
                    <input type="text" id="newProjectName" name="project_name" required
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm 
                               focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent 
                               transition-all placeholder-gray-400"
                        maxlength="100">
                    <p class="text-xs text-gray-500 mt-1">Maximum 100 characters</p>
                </div>

                <!-- Project Code -->
                <div>
                    <label for="newProjectCode" class="block text-gray-700 text-sm font-medium mb-2">
                        Project Code
                    </label>
                    <input type="text" id="newProjectCode" name="project_code" required
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm 
                               focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent 
                               transition-all placeholder-gray-400 uppercase"
                        maxlength="20">
                    <p class="text-xs text-gray-500 mt-1">Uppercase letters and numbers only</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="newProjectDescription" class="block text-gray-700 text-sm font-medium mb-2">
                        Description
                    </label>
                    <textarea id="newProjectDescription" name="description" rows="3"
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm 
                               focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent 
                               transition-all placeholder-gray-400 resize-none"
                        maxlength="500"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Maximum 500 characters</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" id="newProjectActive" name="is_active" value="1" checked
                            class="w-4 h-4 text-secondary bg-gray-100 border-gray-300 rounded focus:ring-secondary focus:ring-2">
                        <span class="text-gray-700 text-sm font-medium">Set as active project</span>
                    </label>
                </div>
            </form>
        </div>

        <div class="p-6 border-t border-gray-200 flex gap-3">
            <button type="button" onclick="closeModal('addProjectModal')"
                class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 
                       transition-colors font-medium text-sm">
                Cancel
            </button>
            <button type="button" onclick="submitAddProject()" id="submitAddProjectBtn"
                class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] 
                       transition-colors font-medium text-sm flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i>
                Create Project
            </button>
        </div>
    </div>
</div>

<!-- Assign to Users Modal -->
<div id="assignToUsersModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="p-6 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">Assign Project to Users</h3>
                    <p class="text-gray-500 text-sm mt-1">Select users to assign to the project</p>
                </div>
            </div>
        </div>

        <div class="p-6 overflow-y-auto flex-1">
            <div class="space-y-6">
                <!-- Project Selection -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-3">
                        Select Project
                    </label>
                    <select id="assignProjectSelect"
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm 
                               focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent 
                               transition-all">
                        <option value="">Choose a project</option>
                        <?php if (!empty($projects)): ?>
                            <?php foreach ($projects as $project): ?>
                                <?php if ($project['is_active']): ?>
                                    <option value="<?= $project['project_id'] ?>">
                                        [<?= $project['project_code'] ?>] <?= esc($project['project_name']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Users Selection -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-gray-700 text-sm font-medium">
                            Select Users    
                        </label>
                        <div class="text-xs text-gray-500">
                            <span id="selectedUsersCount">0</span> users selected
                        </div>
                    </div>

                    <!-- Search Users -->
                    <div class="relative mb-4">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" id="searchUsers" placeholder="Search users by name or role..."
                            class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-300 rounded-lg 
                                   text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-secondary 
                                   focus:border-transparent transition-all">
                    </div>

                    <!-- Users List -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden max-h-[300px] overflow-y-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left">
                                        <input type="checkbox" id="selectAllUsers"
                                            class="w-4 h-4 text-secondary rounded border-gray-300 focus:ring-secondary">
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="usersListBody" class="divide-y divide-gray-100">
                                <?php if (!empty($all_users)): ?>
                                    <?php foreach ($all_users as $user): ?>
                                        <tr class="user-row hover:bg-gray-50 transition-colors"
                                            data-user-id="<?= $user['user_id'] ?>"
                                            data-user-name="<?= esc($user['full_name']) ?>"
                                            data-user-role="<?= esc($user['role_name']) ?>"
                                            data-user-email="<?= esc($user['email']) ?>">
                                            <td class="px-4 py-3">
                                                <input type="checkbox" name="user_ids[]"
                                                    value="<?= $user['user_id'] ?>"
                                                    class="user-checkbox w-4 h-4 text-secondary rounded border-gray-300 focus:ring-secondary">
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-secondary/10 rounded-full flex items-center justify-center text-secondary font-medium text-sm">
                                                        <?= substr($user['full_name'], 0, 2) ?>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?= esc($user['full_name']) ?>
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            @<?= $user['username'] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                                    <?= $user['role_name'] ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                <?= $user['email'] ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                            <i class="fas fa-users text-3xl mb-2 opacity-20"></i>
                                            <p>No users available</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Selected Users Preview -->
                    <div id="selectedUsersPreview" class="mt-4 p-3 bg-gray-50 rounded-lg hidden">
                        <div class="text-sm font-medium text-gray-700 mb-2">Selected Users:</div>
                        <div id="selectedUsersTags" class="flex flex-wrap gap-2">
                            <!-- Selected users will appear here as tags -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 flex gap-3 flex-shrink-0">
            <button type="button" onclick="closeModal('assignToUsersModal')"
                class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 
                       transition-colors font-medium text-sm">
                Cancel
            </button>
            <button type="button" onclick="submitAssignUsers()" id="submitAssignUsersBtn"
                class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] 
                       transition-colors font-medium text-sm flex items-center justify-center gap-2 
                       disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                <i class="fas fa-user-plus"></i>
                Assign Users
            </button>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div id="editProjectModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800">Edit Project</h3>
                <button type="button" onclick="closeModal('editProjectModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-gray-500 text-sm mt-1">Edit project details</p>
        </div>

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
                        maxlength="100">
                </div>

                <!-- Project Code -->
                <div>
                    <label for="editProjectCode" class="block text-gray-700 text-sm font-medium mb-2">
                        Project Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="editProjectCode" name="project_code" required
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all uppercase"
                        maxlength="20">
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
                    </select>
                </div>
            </form>
        </div>

        <div class="p-6 border-t border-gray-200 flex gap-3">
            <button type="button" onclick="closeModal('editProjectModal')"
                class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                Cancel
            </button>
            <button type="button" onclick="submitEditProject()"
                class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                <i class="fas fa-save mr-2"></i>Save Changes
            </button>
        </div>
    </div>
</div>

<!-- Delete Project Modal -->
<div id="deleteProjectModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800">Delete Project</h3>
                <button type="button" onclick="closeModal('deleteProjectModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="p-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-800 mb-2">Are you sure?</h4>
                <p class="text-gray-600 mb-4" id="deleteProjectMessage">
                    You are about to delete the project.
                </p>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 flex gap-3">
            <button type="button" onclick="closeModal('deleteProjectModal')"
                class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                Cancel
            </button>
            <button type="button" onclick="confirmDeleteProject()"
                class="flex-1 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                <i class="fas fa-trash mr-2"></i>Delete Project
            </button>
        </div>
    </div>
</div>

<style>
    /* Project row styling */
    .project-row {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 1rem;
        padding: 1rem 1.5rem;
        align-items: center;
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }

    /* Hover state untuk semua rows */
    .project-row:hover {
        background: rgba(102, 92, 158, 0.02);
    }

    /* Selected state - styling utama */
    .project-row.selected {
        background: rgba(102, 92, 158, 0.05) !important;
        border-left-color: #665C9E !important;
    }

    /* Selected state dengan lebih detail */
    .project-row.selected::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 0;
        background: #665C9E;
        border-radius: 0 3px 3px 0;
    }

    /* Selected row text styling */
    .project-row.selected .col-span-1 {
        color: #665C9E !important;
        font-weight: 600;
    }

    .project-row.selected .font-medium {
        color: #665C9E !important;
    }

    .project-row.selected .text-secondary {
        color: #817CB2 !important;
    }

    /* Animation untuk selection */
    @keyframes highlightSelection {
        0% {
            background: rgba(102, 92, 158, 0);
        }

        50% {
            background: rgba(102, 92, 158, 0.1);
        }

        100% {
            background: rgba(102, 92, 158, 0.05);
        }
    }

    .project-row.selected {
        animation: highlightSelection 0.3s ease;
    }

    /* Status badges */
    .status-badge {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .status-active {
        background: #C4E3AC;
        color: #15803D;
    }

    .status-inactive {
        background: #ECDCD3;
        color: #93867E;
    }

    .status-completed {
        background: #BFDBFE;
        color: #1E40AF;
    }

    .status-onhold {
        background: #FEF3C7;
        color: #92400E;
    }

    /* Project details */
    .project-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 24px;
        color: white;
        margin: 0 auto 16px;
        background: linear-gradient(135deg, #665C9E, #8A84C6);
    }

    .project-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid rgba(209, 209, 233, 0.5);
    }

    .project-info-item:last-child {
        border-bottom: none;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .project-row {
            grid-template-columns: repeat(8, minmax(0, 1fr));
        }

        .project-row>div:nth-child(1) {
            grid-column: span 1;
        }

        .project-row>div:nth-child(2) {
            grid-column: span 3;
        }

        .project-row>div:nth-child(3) {
            grid-column: span 2;
        }

        .project-row>div:nth-child(4) {
            grid-column: span 1;
        }

        .project-row>div:nth-child(5) {
            grid-column: span 1;
        }
    }

    @media (max-width: 768px) {
        .project-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
            padding: 1rem;
        }

        .project-row>div {
            grid-column: span 1 !important;
            text-align: left !important;
            padding: 4px 0;
        }

        .project-row.selected::before {
            width: 100%;
            height: 3px;
            top: 0;
            left: 0;
            right: 0;
            bottom: auto;
            border-radius: 3px 3px 0 0;
        }
    }

    /* Custom scrollbar for modal */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    /* Selected user tag style */
    .user-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background: #665C9E;
        color: white;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 500;
    }

    .user-tag button {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .user-tag button:hover {
        background: rgba(255, 255, 255, 0.2);
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    // ==================== GLOBAL VARIABLES ====================
    let selectedProjectId = null;
    let currentProjectData = null;

    // ==================== UTILITY FUNCTIONS ====================
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // ==================== TOAST NOTIFICATION ====================
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
            'error': 'bg-red-500',
            'success': 'bg-green-500',
            'warning': 'bg-yellow-500',
            'info': 'bg-blue-500'
        };

        const toast = $(`
            <div class="custom-toast fixed top-24 right-6 p-4 rounded-lg shadow-lg z-[2000] max-w-sm ${colors[type]} text-white transform transition-all duration-300 translate-x-0 opacity-0">
                <div class="flex items-center gap-3">
                    <i class="fas ${icons[type]}"></i>
                    <span class="text-sm">${escapeHtml(message)}</span>
                </div>
            </div>
        `);

        $('body').append(toast);

        // Animate in
        setTimeout(() => {
            toast.removeClass('opacity-0').addClass('opacity-100');
        }, 10);

        // Remove after 3 seconds
        setTimeout(() => {
            toast.addClass('opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ==================== MODAL MANAGEMENT ====================
    function closeModal(modalId) {
        const $modal = $('#' + modalId);
        if ($modal.length) {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');

            // Reset forms if needed
            if (modalId === 'addProjectModal') {
                $('#addProjectForm')[0]?.reset();
            } else if (modalId === 'assignToUsersModal') {
                resetAssignUsersModal();
            }
        }
    }

    function openModal(modalId) {
        const $modal = $('#' + modalId);
        if ($modal.length) {
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');

            // Focus on first input
            if (modalId === 'addProjectModal') {
                setTimeout(() => {
                    $('#newProjectName').focus();
                }, 100);
            } else if (modalId === 'assignToUsersModal') {
                initializeAssignUsersModal();
                // Set selected project jika ada
                if (selectedProjectId) {
                    $('#assignProjectSelect').val(selectedProjectId);
                }
            }
        }
    }

    // ==================== PROJECT MANAGEMENT ====================
    // Project selection
    function selectProject(projectId) {
        selectedProjectId = projectId;

        // Update UI
        $('.project-row').removeClass('selected');
        $(`.project-row[data-project-id="${projectId}"]`).addClass('selected');

        // Load project details
        loadProjectDetails(projectId);
    }

    // Load project details
    function loadProjectDetails(projectId) {
        if (!projectId) return;

        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        formData.append('action', 'get_project_details');
        formData.append('project_id', projectId);

        $.ajax({
            url: '<?= base_url('admin/projects/ajax-manage') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    currentProjectData = data.project;
                    updateProjectDetails(data.project);
                    updateProjectActions(data.project);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showToast('Failed to load project details', 'error');
            }
        });
    }

    // Update project details UI
    function updateProjectDetails(project) {
        const $dynamicView = $('#dynamicProjectView');
        const $defaultView = $('#defaultProjectView');

        $defaultView.addClass('hidden');

        const status = project.is_active ? 'active' : 'inactive';
        const statusClass = status === 'active' ? 'status-active' : 'status-inactive';
        const statusText = status === 'active' ? 'Active' : 'Inactive';

        const html = `
        <div>
            <div class="project-avatar">${project.project_code ? project.project_code.substring(0, 2) : 'PR'}</div>
            <div class="text-center mb-6">
                <h3 class="text-lg font-semibold text-text-dark">${escapeHtml(project.project_name || '')}</h3>
                <p class="text-text-dark/60 text-sm">${escapeHtml(project.project_code || '')}</p>
                <span class="inline-block mt-2 ${statusClass} status-badge">
                    ${statusText}
                </span>
            </div>
            
            <div class="space-y-2">
                <div class="project-info-item">
                    <span class="text-text-dark/70 text-sm">Description:</span>
                    <span class="text-text-dark font-medium text-right text-xs">${escapeHtml(project.description || 'No description')}</span>
                </div>
                <div class="project-info-item">
                    <span class="text-text-dark/70 text-sm">Total Tickets:</span>
                    <span class="text-text-dark font-medium">${project.total_tickets || 0}</span>
                </div>
                <div class="project-info-item">
                    <span class="text-text-dark/70 text-sm">Created:</span>
                    <span class="text-text-dark font-medium">${new Date(project.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                </div>
            </div>
        </div>`;

        $dynamicView.html(html).removeClass('hidden');
    }

    // Update project actions UI
    function updateProjectActions(project) {
        const $projectActions = $('#projectActions');
        if (!$projectActions.length) return;

        const status = project.is_active ? 'active' : 'inactive';

        const html = `
        <div class="space-y-2">
            <button onclick="editProject(${project.project_id})"
                class="w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
                <i class="fas fa-edit"></i>
                Edit Project
            </button>
            
            <button onclick="deleteProject(${project.project_id})"
                class="w-full py-3 bg-red-50 text-red-600 border border-red-200 rounded-xl hover:bg-red-100 transition-colors font-medium flex items-center justify-center gap-2">
                <i class="fas fa-trash"></i>
                Delete Project
            </button>
            
            <button onclick="changeStatus(${project.project_id}, ${!project.is_active})"
                class="w-full py-3 ${project.is_active ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-green-50 text-green-600 border border-green-200'} rounded-xl hover:${project.is_active ? 'bg-red-100' : 'bg-green-100'} transition-colors font-medium flex items-center justify-center gap-2">
                <i class="fas fa-power-off"></i>
                ${project.is_active ? 'Deactivate Project' : 'Activate Project'}
            </button>
        </div>`;

        $projectActions.html(html);
    }

    // Edit Project
    function editProject(projectId) {
        if (!projectId) return;

        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        formData.append('action', 'get_project_details');
        formData.append('project_id', projectId);

        $.ajax({
            url: '<?= base_url('admin/projects/ajax-manage') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    $('#editProjectId').val(projectId);
                    $('#editProjectName').val(data.project.project_name || '');
                    $('#editProjectCode').val(data.project.project_code || '');
                    $('#editProjectDescription').val(data.project.description || '');
                    $('#editProjectStatus').val(data.project.is_active ? '1' : '0');

                    openModal('editProjectModal');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showToast('Failed to load project data', 'error');
            }
        });
    }

    function submitEditProject() {
        const $form = $('#editProjectForm');
        const formData = new FormData($form[0]);
        formData.append('action', 'update_project');
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        $.ajax({
            url: '<?= base_url('admin/projects') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showToast('Failed to update project', 'error');
            }
        });
    }

    // Delete Project
    function deleteProject(projectId) {
        if (!projectId) return;

        // Get project name for confirmation
        const projectName = currentProjectData?.project_name || 'this project';
        $('#deleteProjectMessage').text(`You are about to delete the project "${projectName}". This action cannot be undone.`);

        // Store project ID in modal
        $('#deleteProjectModal').data('projectId', projectId);

        openModal('deleteProjectModal');
    }

    function confirmDeleteProject() {
        const projectId = $('#deleteProjectModal').data('projectId');
        if (!projectId) return;

        const formData = new FormData();
        formData.append('action', 'delete_project');
        formData.append('project_id', projectId);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        $.ajax({
            url: '<?= base_url('admin/projects') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showToast('Failed to delete project', 'error');
            }
        });
    }

    // Change Status
    function changeStatus(projectId, newStatus) {
        if (!projectId) return;

        const formData = new FormData();
        formData.append('action', 'change_project_status');
        formData.append('project_id', projectId);
        formData.append('status', newStatus ? 'active' : 'inactive');
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        $.ajax({
            url: '<?= base_url('admin/projects') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showToast('Failed to change project status', 'error');
            }
        });
    }

    // ==================== NEW PROJECT MODAL ====================
    function submitAddProject() {
        const projectName = $('#newProjectName').val().trim();
        const projectCode = $('#newProjectCode').val().trim();

        if (!projectName || !projectCode) {
            showToast('Please fill in all required fields', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        formData.append('action', 'create_project');
        formData.append('project_name', projectName);
        formData.append('project_code', projectCode.toUpperCase());
        formData.append('description', $('#newProjectDescription').val() || '');
        formData.append('is_active', $('#newProjectActive').is(':checked') ? '1' : '0');

        $.ajax({
            url: '<?= base_url('admin/projects/ajax-manage') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    showToast('Project created successfully!', 'success');
                    closeModal('addProjectModal');
                    $('#addProjectForm')[0].reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.message || 'Failed to create project', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showToast('Network error. Please try again.', 'error');
            }
        });
    }

    // ==================== ASSIGN USERS MODAL ====================
    function initializeAssignUsersModal() {
        const $selectAll = $('#selectAllUsers');
        const $userCheckboxes = $('.user-checkbox');

        // Select all checkbox
        $selectAll.off('change').on('change', function() {
            const isChecked = $(this).is(':checked');
            $userCheckboxes.prop('checked', isChecked);
            updateSelectedUsersCount();
            updateSelectedUsersPreview();
        });

        // Individual checkbox change
        $userCheckboxes.off('change').on('change', function() {
            updateSelectedUsersCount();
            updateSelectedUsersPreview();
            updateSelectAllCheckbox();
        });

        // Search functionality
        $('#searchUsers').off('input').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.user-row').each(function() {
                const $row = $(this);
                const userName = $row.data('user-name')?.toLowerCase() || '';
                const userRole = $row.data('user-role')?.toLowerCase() || '';
                const userEmail = $row.data('user-email')?.toLowerCase() || '';

                if (userName.includes(searchTerm) || userRole.includes(searchTerm) || userEmail.includes(searchTerm)) {
                    $row.show();
                } else {
                    $row.hide();
                }
            });
            updateSelectAllCheckbox();
        });

        // Project select change handler
        $('#assignProjectSelect').off('change').on('change', function() {
            updateSelectedUsersCount();
        });
    }

    function updateSelectedUsersCount() {
        const selectedCount = $('.user-checkbox:checked').length;
        $('#selectedUsersCount').text(selectedCount);

        // Enable/disable submit button
        const projectSelected = $('#assignProjectSelect').val() !== '';
        $('#submitAssignUsersBtn').prop('disabled', selectedCount === 0 || !projectSelected);
    }

    function updateSelectAllCheckbox() {
        const $selectAll = $('#selectAllUsers');
        const $visibleCheckboxes = $('.user-checkbox:visible');
        const $checkedCheckboxes = $('.user-checkbox:checked:visible');

        if ($visibleCheckboxes.length > 0) {
            $selectAll.prop('checked', $checkedCheckboxes.length === $visibleCheckboxes.length);
            $selectAll.prop('indeterminate', $checkedCheckboxes.length > 0 && $checkedCheckboxes.length < $visibleCheckboxes.length);
        }
    }

    function updateSelectedUsersPreview() {
        const $selectedUsers = $('.user-checkbox:checked');
        const $previewContainer = $('#selectedUsersPreview');
        const $tagsContainer = $('#selectedUsersTags');

        $tagsContainer.empty();

        if ($selectedUsers.length === 0) {
            $previewContainer.addClass('hidden');
            return;
        }

        $selectedUsers.each(function() {
            const $checkbox = $(this);
            const $row = $checkbox.closest('.user-row');
            const userId = $row.data('user-id');
            const userName = $row.data('user-name') || 'Unknown User';

            const tag = $(`
                <div class="user-tag">
                    ${escapeHtml(userName)}
                    <button type="button" onclick="removeUserFromSelection(${userId})">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            `);

            $tagsContainer.append(tag);
        });

        $previewContainer.removeClass('hidden');
    }

    function removeUserFromSelection(userId) {
        $(`.user-checkbox[value="${userId}"]`).prop('checked', false);
        updateSelectedUsersCount();
        updateSelectedUsersPreview();
        updateSelectAllCheckbox();
    }

    function resetAssignUsersModal() {
        // Reset checkboxes
        $('#selectAllUsers').prop('checked', false).prop('indeterminate', false);
        $('.user-checkbox').prop('checked', false);

        // Reset search
        $('#searchUsers').val('').trigger('input');

        // Reset project select jika tidak ada project yang dipilih
        if (!selectedProjectId) {
            $('#assignProjectSelect').val('');
        }

        // Reset UI
        updateSelectedUsersCount();
        $('#selectedUsersPreview').addClass('hidden');

        // Show all rows
        $('.user-row').show();
    }

    function submitAssignUsers() {
        const projectId = $('#assignProjectSelect').val();
        const $selectedCheckboxes = $('.user-checkbox:checked');

        if (!projectId || $selectedCheckboxes.length === 0) {
            showToast('Please select a project and at least one user', 'error');
            return;
        }

        const userIds = $selectedCheckboxes.map(function() {
            return $(this).val();
        }).get();

        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        formData.append('action', 'bulk_assign_projects');
        formData.append('project_ids', JSON.stringify([projectId]));
        formData.append('user_ids', JSON.stringify(userIds));

        $.ajax({
            url: '<?= base_url('admin/projects/ajax-manage') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    showToast(`Successfully assigned ${userIds.length} user(s) to project`, 'success');
                    closeModal('assignToUsersModal');
                    if (selectedProjectId == projectId) {
                        loadProjectDetails(selectedProjectId);
                    }
                } else {
                    showToast(data.message || 'Failed to assign users', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showToast('Network error. Please try again.', 'error');
            }
        });
    }

    // ==================== SEARCH AND FILTER ====================
    function initializeSearchAndFilter() {
        // Event listener untuk Reset Filters
        $('#resetFilters').off('click').on('click', function(e) {
            e.preventDefault();

            // Reset semua filter
            $('#projectSearch').val('');
            $('#statusFilter').val('');
            $('#sortFilter').val('default');

            // Tampilkan semua project kembali
            resetProjectDisplay();

            // Reset tampilan count
            const initialCount = $('.project-row').length;
            updateProjectCount(initialCount);

            // Reset row numbers
            updateRowNumbers();

            // Pilih project pertama jika ada
            const $firstProject = $('.project-row').first();
            if ($firstProject.length) {
                const projectId = $firstProject.data('project-id');
                selectProject(projectId);
            }
        });

        // Search input dengan debounce
        let searchTimeout;
        $('#projectSearch').off('input').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterProjects();
            }, 300);
        });

        // Status filter
        $('#statusFilter').off('change').on('change', function() {
            filterProjects();
        });

        // Sort filter
        $('#sortFilter').off('change').on('change', function() {
            const sortValue = $(this).val();
            if (sortValue === 'default') {
                resetProjectOrder();
            } else {
                sortProjects(sortValue);
            }
        });
    }

    // Fungsi untuk reset tampilan project
    function resetProjectDisplay() {
        $('.project-row').show();
        $('.no-results-message').remove();
        resetProjectOrder();
    }

    // Fungsi untuk reset urutan project ke default
    function resetProjectOrder() {
        const $container = $('#projectsList');
        const $rows = $('.project-row').toArray();

        // Urutkan berdasarkan project_id (ascending)
        $rows.sort((a, b) => {
            const idA = $(a).data('project-id');
            const idB = $(b).data('project-id');
            return idA - idB;
        });

        // Reorder rows
        $rows.forEach(row => {
            $container.append(row);
        });

        // Update row numbers
        updateRowNumbers();
    }

    // Fungsi filter projects
    function filterProjects() {
        const search = $('#projectSearch').val().toLowerCase();
        const status = $('#statusFilter').val();
        const $projectsList = $('#projectsList');
        const $rows = $('.project-row');

        let visibleCount = 0;
        let hasVisibleRows = false;

        // Hapus pesan "no results" sebelumnya jika ada
        $('.no-results-message').remove();

        // Filter rows
        $rows.each(function() {
            const $row = $(this);
            const $projectNameElement = $row.find('.col-span-4 .font-medium');
            const $projectCodeElement = $row.find('.col-span-2 .font-medium');
            const $statusBadgeElement = $row.find('.status-badge');

            if (!$projectNameElement.length || !$projectCodeElement.length || !$statusBadgeElement.length) {
                $row.hide();
                return;
            }

            const projectName = $projectNameElement.text().toLowerCase();
            const projectCode = $projectCodeElement.text().toLowerCase();
            const statusText = $statusBadgeElement.text().toLowerCase();

            // Normalize status text
            const normalizedStatus = normalizeStatusText(statusText);

            // Check filters
            const matchesSearch = !search || projectName.includes(search) || projectCode.includes(search);
            const matchesStatus = !status || (status === 'active' && normalizedStatus === 'active') ||
                (status === 'inactive' && normalizedStatus === 'inactive') ||
                (status === 'completed' && normalizedStatus === 'completed') ||
                (status === 'on-hold' && normalizedStatus === 'on hold');

            if (matchesSearch && matchesStatus) {
                $row.show();
                visibleCount++;
                hasVisibleRows = true;
            } else {
                $row.hide();
            }
        });

        // Tampilkan pesan jika tidak ada hasil
        if (!hasVisibleRows) {
            const noResultsHtml = `
            <div class="no-results-message py-12 text-center col-span-12">
                <i class="fas fa-search text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500">No projects found</p>
                <p class="text-gray-400 text-sm mt-2">
                    ${search ? `No projects match "${search}"` : 'No projects match your filter criteria'}
                </p>
            </div>`;

            $projectsList.append(noResultsHtml);
        }

        // Update project count
        updateProjectCount(visibleCount);

        // Apply sorting jika ada
        const sortValue = $('#sortFilter').val() || 'default';
        if (sortValue !== 'default' && hasVisibleRows) {
            sortProjects(sortValue);
        }
    }

    // Helper function untuk normalize status text
    function normalizeStatusText(statusText) {
        const statusMap = {
            'active': 'active',
            'inactive': 'inactive',
            'completed': 'completed',
            'on hold': 'on-hold',
            'on-hold': 'on-hold',
            'onhold': 'on-hold'
        };

        const normalized = statusText.trim().toLowerCase();
        return statusMap[normalized] || normalized;
    }

    // Fungsi sort projects
    function sortProjects(sortType) {
        const $container = $('#projectsList');
        const $rows = $('.project-row:visible').toArray();

        if ($rows.length === 0) return;

        $rows.sort((a, b) => {
            const createdA = $(a).data('created-at');
            const createdB = $(b).data('created-at');
            const idA = $(a).data('project-id');
            const idB = $(b).data('project-id');

            switch (sortType) {
                case 'newest':
                    // Sort by creation date descending (newest first)
                    if (createdA && createdB) {
                        return new Date(createdB) - new Date(createdA);
                    }
                    // Fallback to ID if date not available
                    return idB - idA;

                case 'oldest':
                    // Sort by creation date ascending (oldest first)
                    if (createdA && createdB) {
                        return new Date(createdA) - new Date(createdB);
                    }
                    // Fallback to ID if date not available
                    return idA - idB;

                default:
                    return 0;
            }
        });

        // Reorder rows
        $rows.forEach(row => {
            $container.append(row);
        });

        // Update row numbers
        updateRowNumbers();
    }

    // Update row numbers
    function updateRowNumbers() {
        $('.project-row:visible').each(function(index) {
            $(this).find('.col-span-1').text(`${index + 1}.`);
        });
    }

    // Update project count display
    function updateProjectCount(count) {
        const $showingCount = $('#showingCount');
        if (count === 0) {
            $showingCount.html('<span class="text-red-500 font-medium">No projects found</span>');
        } else {
            $showingCount.html(`
                <span class="text-secondary font-medium">${count}</span> 
                <span class="text-text-dark/70">project${count !== 1 ? 's' : ''}</span>
            `);
        }
    }

    // ==================== KEYBOARD SHORTCUTS ====================
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('.fixed.bg-black\\/50:not(.hidden)').each(function() {
                const modalId = $(this).attr('id');
                closeModal(modalId);
            });
        }
    });

    // ==================== EVENT LISTENERS ====================
    // Modal buttons
    $('#addProjectBtn').on('click', function() {
        openModal('addProjectModal');
    });

    $('#assignToUsersBtn').on('click', function() {
        // Set selected project jika ada
        if (selectedProjectId) {
            $('#assignProjectSelect').val(selectedProjectId);
        }
        openModal('assignToUsersModal');
    });

    // Submit buttons
    $('#submitAddProjectBtn').on('click', submitAddProject);
    $('#submitAssignUsersBtn').on('click', submitAssignUsers);

    // Form submit handlers
    $('#addProjectForm').on('submit', function(e) {
        e.preventDefault();
        submitAddProject();
    });

    // Enter key pada modal
    $('#addProjectModal').on('keydown', function(e) {
        if (e.key === 'Enter' && !$(e.target).is('textarea')) {
            e.preventDefault();
            submitAddProject();
        }
    });

    $('#assignToUsersModal').on('keydown', function(e) {
        if (e.key === 'Enter' && !$(e.target).is('textarea') && $(e.target).attr('id') !== 'searchUsers') {
            e.preventDefault();
            submitAssignUsers();
        }
    });

        
    $(document).on('click', function(e) {
        // Check if clicked element is a Cancel button or X button
        const $target = $(e.target);        
        
        // Check for Cancel button (button with text "Cancel")
        if ($target.is('button') && $target.text().trim() === 'Cancel') {
            const $modal = $target.closest('.fixed.bg-black\\/50');
            if ($modal.length) {
                const modalId = $modal.attr('id');
                closeModal(modalId);
                return;
            }
        }
        
        // Check if parent is a Cancel button
        if ($target.parent().is('button') && $target.parent().text().trim() === 'Cancel') {
            const $modal = $target.parent().closest('.fixed.bg-black\\/50');
            if ($modal.length) {
                const modalId = $modal.attr('id');
                closeModal(modalId);
            }
        }
    });

    // ==================== INITIALIZE ON PAGE LOAD ====================
    // Initialize search and filter
    initializeSearchAndFilter();

    // Set data attributes untuk sorting
    $('.project-row').each(function() {
        const $dateElement = $(this).find('[data-created-date]');
        if ($dateElement.length) {
            $(this).data('created-at', $dateElement.data('created-date'));
        }
    });

    // Click handler untuk project rows (delegated)
    $('#projectsList').on('click', '.project-row', function() {
        const projectId = $(this).data('project-id');
        if (projectId) {
            selectProject(projectId);
        }
    });
});
</script>
<?= $this::endSection() ?>