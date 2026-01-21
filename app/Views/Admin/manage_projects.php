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
<!-- Add Project Modal -->
<div id="addProjectModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800">Add New Project</h3>
                <button type="button" onclick="closeModal('addProjectModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-gray-500 text-sm mt-1">Create a new project for ticket management</p>
        </div>

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
                </div>

                <!-- Project Code -->
                <div>
                    <label for="projectCode" class="block text-gray-700 text-sm font-medium mb-2">
                        Project Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="projectCode" name="project_code" required
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all uppercase"
                        placeholder="e.g., PROJ001"
                        maxlength="20">
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
                </div>
            </form>
        </div>

        <div class="p-6 border-t border-gray-200 flex gap-3">
            <button type="button" onclick="closeModal('addProjectModal')"
                class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                Cancel
            </button>
            <button type="button" onclick="submitAddProject()"
                class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                <i class="fas fa-plus mr-2"></i>Create Project
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

<!-- Assign to Users Modal -->
<div id="assignToUsersModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
    <div class="bg-white rounded-2xl w-full max-w-lg">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800">Assign Project to Users</h3>
                <button type="button" onclick="closeModal('assignToUsersModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-gray-500 text-sm mt-1">Assign users to this project</p>
        </div>

        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Select Project</label>
                    <select id="assignProjectId" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm">
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= $project['project_id'] ?>">
                                <?= esc($project['project_name']) ?> (<?= $project['project_code'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Select Users</label>
                    <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-2">
                        <?php foreach ($all_users as $user): ?>
                            <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" id="user_<?= $user['user_id'] ?>"
                                    value="<?= $user['user_id'] ?>" class="assign-user-checkbox">
                                <label for="user_<?= $user['user_id'] ?>" class="text-sm text-gray-700">
                                    <?= esc($user['full_name']) ?> (<?= $user['role_name'] ?>)
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 flex gap-3">
            <button type="button" onclick="closeModal('assignToUsersModal')"
                class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                Cancel
            </button>
            <button type="button" onclick="submitAssignUsers()"
                class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                <i class="fas fa-user-plus mr-2"></i>Assign Users
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
</style>

<script>
    // Global variables
    let selectedProjectId = null;
    let currentProjectData = null;

    // Utility functions
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());

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

        const toast = document.createElement('div');
        toast.className = `custom-toast fixed top-24 right-6 p-4 rounded-lg shadow-lg z-[2000] max-w-sm ${colors[type]} text-white`;
        toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${icons[type]}"></i>
            <span class="text-sm">${message}</span>
        </div>
    `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    // Project selection
    function selectProject(projectId) {
        selectedProjectId = projectId;

        // Update UI
        document.querySelectorAll('.project-row').forEach(row => {
            row.classList.remove('selected');
        });

        const selectedRow = document.querySelector(`.project-row[data-project-id="${projectId}"]`);
        if (selectedRow) {
            selectedRow.classList.add('selected');
        }

        // Load project details
        loadProjectDetails(projectId);
    }

    // Load project details
    function loadProjectDetails(projectId) {
        if (!projectId) return;

        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        formData.append('project_id', projectId);

        fetch('<?= base_url('admin/projects/ajax-manage') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentProjectData = data.project;
                    updateProjectDetails(data.project);
                    updateProjectActions(data.project);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to load project details', 'error');
            });
    }

    // Update project details UI
    function updateProjectDetails(project) {
        const dynamicView = document.getElementById('dynamicProjectView');
        const defaultView = document.getElementById('defaultProjectView');

        if (defaultView) {
            defaultView.classList.add('hidden');
        }

        if (!dynamicView) return;

        const status = project.is_active ? 'active' : 'inactive';
        const statusClass = status === 'active' ? 'status-active' : 'status-inactive';
        const statusText = status === 'active' ? 'Active' : 'Inactive';

        dynamicView.innerHTML = `
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
        </div>
    `;

        dynamicView.classList.remove('hidden');
    }

    // Update project actions UI
    function updateProjectActions(project) {
        const projectActions = document.getElementById('projectActions');
        if (!projectActions) return;

        const status = project.is_active ? 'active' : 'inactive';

        projectActions.innerHTML = `
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
        </div>
    `;
    }

    // Modal functions
    function showAddProjectModal() {
        const modal = document.getElementById('addProjectModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.getElementById('projectName').focus();
        }
    }

    function submitAddProject() {
        const form = document.getElementById('addProjectForm');
        const formData = new FormData(form);
        formData.append('action', 'add_project');
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('admin/projects') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Handle response
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to create project', 'error');
            });
    }

    function editProject(projectId) {
        if (!projectId) return;

        // Load project data into modal
        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        formData.append('project_id', projectId);

        fetch('<?= base_url('admin/projects/ajax-manage') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('editProjectId').value = projectId;
                    document.getElementById('editProjectName').value = data.project.project_name || '';
                    document.getElementById('editProjectCode').value = data.project.project_code || '';
                    document.getElementById('editProjectDescription').value = data.project.description || '';
                    document.getElementById('editProjectStatus').value = data.project.is_active ? '1' : '0';

                    openModal('editProjectModal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to load project data', 'error');
            });
    }

    function submitEditProject() {
        const form = document.getElementById('editProjectForm');
        const formData = new FormData(form);
        formData.append('action', 'edit_project');
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('admin/projects') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Handle response
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to update project', 'error');
            });
    }

    function deleteProject(projectId) {
        if (!projectId) return;

        // Get project name for confirmation
        const projectName = currentProjectData?.project_name || 'this project';
        document.getElementById('deleteProjectMessage').textContent =
            `You are about to delete the project "${projectName}". This action cannot be undone.`;

        // Store project ID in modal
        const modal = document.getElementById('deleteProjectModal');
        modal.dataset.projectId = projectId;

        openModal('deleteProjectModal');
    }

    function confirmDeleteProject() {
        const modal = document.getElementById('deleteProjectModal');
        const projectId = modal.dataset.projectId;

        if (!projectId) return;

        const formData = new FormData();
        formData.append('action', 'delete_project');
        formData.append('project_id', projectId);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('admin/projects') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Handle response
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to delete project', 'error');
            });
    }

    function changeStatus(projectId, newStatus) {
        if (!projectId) return;

        const formData = new FormData();
        formData.append('action', 'change_project_status');
        formData.append('project_id', projectId);
        formData.append('status', newStatus ? 'active' : 'inactive');
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('admin/projects') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Handle response
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to change project status', 'error');
            });
    }

    function showAssignToUsersModal() {
        openModal('assignToUsersModal');
    }

    function submitAssignUsers() {
        const projectId = document.getElementById('assignProjectId').value;
        const checkboxes = document.querySelectorAll('.assign-user-checkbox:checked');
        const userIds = Array.from(checkboxes).map(cb => cb.value);

        if (!projectId || userIds.length === 0) {
            showToast('Please select a project and at least one user', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'assign_project_to_users');
        formData.append('project_id', projectId);
        userIds.forEach(userId => formData.append('user_ids[]', userId));
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('admin/projects') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Handle response
                closeModal('assignToUsersModal');
                showToast('Users assigned successfully', 'success');

                // Clear selections
                document.querySelectorAll('.assign-user-checkbox').forEach(cb => cb.checked = false);
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to assign users', 'error');
            });
    }

    // Search and filter functionality
    function initializeSearchAndFilter() {
        const searchInput = document.getElementById('projectSearch');
        const statusFilter = document.getElementById('statusFilter');
        const sortFilter = document.getElementById('sortFilter');
        const resetButton = document.getElementById('resetFilters');

        // Event listener untuk Reset Filters
        if (resetButton) {
            resetButton.addEventListener('click', (e) => {
                e.preventDefault(); // Mencegah refresh halaman

                // Reset semua filter
                searchInput.value = '';
                statusFilter.value = '';
                sortFilter.value = 'default';

                // Tampilkan semua project kembali
                resetProjectDisplay();

                // Reset tampilan count
                const initialCount = document.querySelectorAll('.project-row').length;
                updateProjectCount(initialCount);

                // Reset row numbers
                updateRowNumbers();

                // // Show toast notification
                // showToast('Filters have been reset', 'success');

                // Pilih project pertama jika ada
                const firstProject = document.querySelector('.project-row');
                if (firstProject) {
                    const projectId = firstProject.dataset.projectId;
                    selectProject(projectId);
                }
            });
        }

        // Event listener untuk search input
        if (searchInput) {
            let timeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    filterProjects();
                }, 300);
            });
        }

        // Event listener untuk status filter
        if (statusFilter) {
            statusFilter.addEventListener('change', () => {
                filterProjects();
            });
        }

        // Event listener untuk sort filter
        if (sortFilter) {
            sortFilter.addEventListener('change', () => {
                const sortValue = sortFilter.value;
                if (sortValue === 'default') {
                    resetProjectOrder();
                } else {
                    sortProjects(sortValue);
                }
            });
        }
    }

    // Fungsi untuk reset tampilan project
    function resetProjectDisplay() {
        const rows = document.querySelectorAll('.project-row');
        const projectsList = document.getElementById('projectsList');

        // Reset semua row ke tampilan normal
        rows.forEach(row => {
            row.style.display = 'grid';
        });

        // Hapus pesan "no results" jika ada
        const noResultsMsg = projectsList.querySelector('.no-results-message');
        if (noResultsMsg) {
            noResultsMsg.remove();
        }

        // Reset order ke default
        resetProjectOrder();
    }

    // Fungsi untuk reset urutan project ke default
    function resetProjectOrder() {
        const container = document.getElementById('projectsList');
        const rows = Array.from(container.querySelectorAll('.project-row'));

        // Urutkan berdasarkan project_id (ascending)
        rows.sort((a, b) => {
            const idA = parseInt(a.dataset.projectId);
            const idB = parseInt(b.dataset.projectId);
            return idA - idB;
        });

        // Reorder rows
        rows.forEach(row => container.appendChild(row));

        // Update row numbers
        updateRowNumbers();
    }

    // Fungsi filter projects yang diperbaiki
    function filterProjects() {
        const search = document.getElementById('projectSearch')?.value.toLowerCase() || '';
        const status = document.getElementById('statusFilter')?.value || '';
        const projectsList = document.getElementById('projectsList');
        const rows = document.querySelectorAll('.project-row');

        let visibleCount = 0;
        let hasVisibleRows = false;

        // Hapus pesan "no results" sebelumnya jika ada
        const existingNoResults = projectsList.querySelector('.no-results-message');
        if (existingNoResults) {
            existingNoResults.remove();
        }

        // Filter rows
        rows.forEach(row => {
            const projectNameElement = row.querySelector('.col-span-4 .font-medium');
            const projectCodeElement = row.querySelector('.col-span-2 .font-medium');
            const statusBadgeElement = row.querySelector('.status-badge');

            if (!projectNameElement || !projectCodeElement || !statusBadgeElement) {
                row.style.display = 'none';
                return;
            }

            const projectName = projectNameElement.textContent.toLowerCase();
            const projectCode = projectCodeElement.textContent.toLowerCase();
            const statusText = statusBadgeElement.textContent.toLowerCase();

            // Normalize status text untuk matching
            const normalizedStatus = normalizeStatusText(statusText);

            // Check filters
            const matchesSearch = !search ||
                projectName.includes(search) ||
                projectCode.includes(search);

            const matchesStatus = !status ||
                (status === 'active' && normalizedStatus === 'active') ||
                (status === 'inactive' && normalizedStatus === 'inactive') ||
                (status === 'completed' && normalizedStatus === 'completed') ||
                (status === 'on-hold' && normalizedStatus === 'on hold');

            if (matchesSearch && matchesStatus) {
                row.style.display = 'grid';
                visibleCount++;
                hasVisibleRows = true;
            } else {
                row.style.display = 'none';
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
            </div>
        `;

            projectsList.insertAdjacentHTML('beforeend', noResultsHtml);
        }

        // Update project count
        updateProjectCount(visibleCount);

        // Apply sorting jika ada
        const sortValue = document.getElementById('sortFilter')?.value || 'default';
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
        const container = document.getElementById('projectsList');
        const rows = Array.from(container.querySelectorAll('.project-row[style*="grid"]'));

        if (rows.length === 0) return;

        rows.sort((a, b) => {
            const createdA = a.dataset.createdAt;
            const createdB = b.dataset.createdAt;
            const idA = parseInt(a.dataset.projectId);
            const idB = parseInt(b.dataset.projectId);

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
        rows.forEach(row => container.appendChild(row));

        // Update row numbers
        updateRowNumbers();
    }

    // Update row numbers
    function updateRowNumbers() {
        const rows = document.querySelectorAll('.project-row[style*="grid"]');
        rows.forEach((row, index) => {
            const numberCell = row.querySelector('.col-span-1');
            if (numberCell) {
                numberCell.textContent = `${index + 1}.`;
            }
        });
    }

    // Update project count display
    function updateProjectCount(count) {
        const showingCount = document.getElementById('showingCount');
        if (showingCount) {
            if (count === 0) {
                showingCount.innerHTML = '<span class="text-red-500 font-medium">No projects found</span>';
            } else {
                showingCount.innerHTML = `
                <span class="text-secondary font-medium">${count}</span> 
                <span class="text-text-dark/70">project${count !== 1 ? 's' : ''}</span>
            `;
            }
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        initializeSearchAndFilter();

        // Set data attributes untuk sorting
        document.querySelectorAll('.project-row').forEach(row => {
            const dateElement = row.querySelector('[data-created-date]');
            if (dateElement) {
                row.dataset.createdAt = dateElement.dataset.createdDate;
            }
        });

        // Select first project jika ada
        const firstProject = document.querySelector('.project-row');
        if (firstProject) {
            const projectId = firstProject.dataset.projectId;
            selectProject(projectId);
        } else {
            // Jika tidak ada project, update count
            updateProjectCount(0);
        }
    });
</script>
<?= $this::endSection() ?>