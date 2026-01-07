<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Create New Ticket - NEXUS<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<style>
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #F0E9F9;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #756EA4;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #665C9E;
    }
    
    /* Smooth transitions */
    .smooth-transition {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* CKEditor Custom Styling */
    .ck-editor__editable {
        min-height: 200px;
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #e5e7eb !important;
        border-radius: 0.5rem !important;
        padding: 1rem !important;
    }
    
    .ck-editor__editable:focus {
        border-color: #756EA4 !important;
        box-shadow: 0 0 0 3px rgba(117, 110, 164, 0.1) !important;
    }
    
    .ck.ck-editor {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .ck.ck-toolbar {
        background: #f9fafb !important;
        border: 1px solid #e5e7eb !important;
        border-bottom: none !important;
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    
    .ck.ck-toolbar .ck-button {
        color: #6b7280 !important;
    }
    
    .ck.ck-toolbar .ck-button:hover {
        background: #e5e7eb !important;
    }
    
    /* Department Selection Styles */
    .department-card {
        transition: all 0.3s ease;
    }
    
    .department-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .department-card.selected {
        border-color: #756EA4;
        background-color: rgba(117, 110, 164, 0.05);
        box-shadow: 0 5px 15px rgba(117, 110, 164, 0.2);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-[77px] px-4 md:px-[30px] py-[20px] relative z-10 max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-[35px] font-semibold mb-2 text-text-dark">Create New Ticket</h1>
                <p class="text-sm md:text-[15px] font-light text-[#666]">Submit a new support request</p>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex gap-3">
                <a href="<?= base_url('dashboard/my_tickets') ?>" 
                   class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 smooth-transition text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-ticket-alt"></i>
                    My Tickets
                </a>
                <a href="<?= base_url('dashboard') ?>" 
                   class="px-4 py-2 bg-white text-text-dark border border-gray-300 rounded-lg hover:bg-gray-50 smooth-transition text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </div>
        </div>
        
        <!-- Progress Steps -->
        <div class="mt-8 mb-6">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-secondary text-white flex items-center justify-center font-bold">
                        1
                    </div>
                    <div class="w-24 h-1 bg-secondary"></div>
                    <div class="w-8 h-8 rounded-full bg-secondary/20 text-secondary flex items-center justify-center font-bold">
                        2
                    </div>
                    <div class="w-24 h-1 bg-gray-300"></div>
                    <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center font-bold">
                        3
                    </div>
                </div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-gray-600">
                <span class="font-medium text-secondary">Details</span>
                <span class="font-medium">Review</span>
                <span>Submit</span>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Form Container -->
        <div class="lg:w-2/3">
            <div class="bg-gradient-to-br from-white/90 to-card-bg/80 rounded-2xl p-6 shadow-lg border border-white/50 backdrop-blur-sm">
                <form id="createTicketForm" action="<?= base_url('dashboard/tickets/create') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <!-- Project Selection -->
                    <div class="mb-8">
                        <label class="block text-text-muted text-base font-semibold mb-4 font-mulish">
                            <i class="fas fa-project-diagram mr-2 text-secondary"></i>Select Project
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <?php 
                            $projects = [
                                ['id' => 'proj1', 'name' => 'Project Alpha', 'description' => 'Main enterprise project', 'tickets' => 12, 'color' => 'from-blue-500 to-blue-600'],
                                ['id' => 'proj2', 'name' => 'Project Beta', 'description' => 'E-commerce platform', 'tickets' => 8, 'color' => 'from-green-500 to-green-600'],
                                ['id' => 'proj3', 'name' => 'Project Gamma', 'description' => 'Mobile application', 'tickets' => 5, 'color' => 'from-purple-500 to-purple-600'],
                            ];
                            ?>
                            
                            <?php foreach ($projects as $project): ?>
                            <div class="relative">
                                <input type="radio" 
                                       id="<?= $project['id'] ?>" 
                                       name="project" 
                                       value="<?= $project['id'] ?>"
                                       class="hidden peer"
                                       required>
                                <label for="<?= $project['id'] ?>" 
                                       class="block p-4 bg-white/70 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-secondary hover:shadow-md smooth-transition peer-checked:border-secondary peer-checked:bg-gradient-to-br peer-checked:<?= $project['color'] ?> peer-checked:text-white peer-checked:shadow-lg department-card">
                                    <div class="flex items-start justify-between">
                                        <div class="pr-4">
                                            <h4 class="text-text-dark text-base font-semibold mb-1 font-mulish peer-checked:text-white"><?= $project['name'] ?></h4>
                                            <p class="text-text-muted text-xs font-light mb-2 peer-checked:text-white/80"><?= $project['description'] ?></p>
                                            <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full peer-checked:bg-white/30 peer-checked:text-white">
                                                <?= $project['tickets'] ?> tickets
                                            </span>
                                        </div>
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center flex-shrink-0 peer-checked:bg-white peer-checked:border-white smooth-transition">
                                            <div class="w-2 h-2 bg-secondary rounded-full peer-checked:block hidden"></div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Ticket Title -->
                    <div class="mb-8">
                        <label for="ticketTitle" class="block text-text-muted text-base font-semibold mb-3 font-mulish">
                            <i class="fas fa-heading mr-2 text-secondary"></i>Ticket Title
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="ticketTitle" 
                                   name="title" 
                                   placeholder="Brief description of your issue"
                                   class="w-full h-[50px] px-4 pl-10 bg-white/70 border border-gray-300 rounded-xl text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 smooth-transition"
                                   required
                                   maxlength="100">
                            <i class="fas fa-pen absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <div class="text-right text-gray-500 text-xs mt-1" id="titleCounter">0/100 characters</div>
                        </div>
                    </div>

                    <!-- Department Selection -->
                    <div class="mb-8">
                        <label class="block text-text-muted text-base font-semibold mb-4 font-mulish">
                            <i class="fas fa-building mr-2 text-secondary"></i>Select Department
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <?php 
                            $departments = [
                                [
                                    'id' => 'frontend',
                                    'name' => 'Frontend',
                                    'description' => 'UI/UX, HTML, CSS, JavaScript',
                                    'icon' => 'fa-palette',
                                    'color' => 'from-blue-400 to-blue-600',
                                    'bgColor' => 'bg-blue-50',
                                    'borderColor' => 'border-blue-200',
                                    'stats' => 'Avg. Response: 2 hours'
                                ],
                                [
                                    'id' => 'backend',
                                    'name' => 'Backend',
                                    'description' => 'APIs, Database, Server Logic',
                                    'icon' => 'fa-server',
                                    'color' => 'from-green-400 to-green-600',
                                    'bgColor' => 'bg-green-50',
                                    'borderColor' => 'border-green-200',
                                    'stats' => 'Avg. Response: 3 hours'
                                ],
                                [
                                    'id' => 'devops',
                                    'name' => 'DevOps',
                                    'description' => 'Deployment, Infrastructure, CI/CD',
                                    'icon' => 'fa-cloud',
                                    'color' => 'from-purple-400 to-purple-600',
                                    'bgColor' => 'bg-purple-50',
                                    'borderColor' => 'border-purple-200',
                                    'stats' => 'Avg. Response: 4 hours'
                                ],
                                [
                                    'id' => 'database',
                                    'name' => 'Database',
                                    'description' => 'SQL, Performance, Optimization',
                                    'icon' => 'fa-database',
                                    'color' => 'from-orange-400 to-orange-600',
                                    'bgColor' => 'bg-orange-50',
                                    'borderColor' => 'border-orange-200',
                                    'stats' => 'Avg. Response: 5 hours'
                                ],
                            ];
                            ?>
                            
                            <?php foreach ($departments as $dept): ?>
                            <div class="relative">
                                <input type="radio" 
                                       id="dept_<?= $dept['id'] ?>" 
                                       name="department" 
                                       value="<?= $dept['id'] ?>"
                                       class="hidden peer"
                                       required>
                                <label for="dept_<?= $dept['id'] ?>" 
                                       class="block p-4 <?= $dept['bgColor'] ?> border-2 <?= $dept['borderColor'] ?> rounded-xl cursor-pointer hover:scale-[1.02] smooth-transition peer-checked:border-secondary peer-checked:bg-gradient-to-br peer-checked:<?= $dept['color'] ?> peer-checked:text-white peer-checked:shadow-xl department-card">
                                    <div class="text-center">
                                        <div class="w-12 h-12 mx-auto mb-3 rounded-full <?= $dept['bgColor'] ?> flex items-center justify-center peer-checked:bg-white/20">
                                            <i class="fas <?= $dept['icon'] ?> text-xl <?= str_replace('from-', 'text-', explode(' ', $dept['color'])[0]) ?> peer-checked:text-white"></i>
                                        </div>
                                        <h4 class="text-text-dark text-base font-bold mb-1 font-mulish peer-checked:text-white"><?= $dept['name'] ?></h4>
                                        <p class="text-text-muted text-xs font-light mb-3 peer-checked:text-white/80"><?= $dept['description'] ?></p>
                                        <div class="text-xs text-gray-500 peer-checked:text-white/70">
                                            <?= $dept['stats'] ?>
                                        </div>
                                        <div class="mt-3">
                                            <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center mx-auto peer-checked:border-white peer-checked:bg-white">
                                                <div class="w-2 h-2 bg-secondary rounded-full peer-checked:block hidden peer-checked:bg-secondary"></div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Priority Selection -->
                    <div class="mb-8">
                        <label class="block text-text-muted text-base font-semibold mb-4 font-mulish">
                            <i class="fas fa-flag mr-2 text-secondary"></i>Priority Level
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <?php 
                            $priorities = [
                                [
                                    'id' => 'low',
                                    'name' => 'Low',
                                    'description' => 'Minor issue, no immediate impact',
                                    'color' => 'from-blue-100 to-blue-300',
                                    'textColor' => 'text-blue-800',
                                    'icon' => 'fa-arrow-down',
                                    'response' => 'Response within 24h'
                                ],
                                [
                                    'id' => 'medium',
                                    'name' => 'Medium',
                                    'description' => 'Important but not urgent',
                                    'color' => 'from-yellow-100 to-yellow-300',
                                    'textColor' => 'text-yellow-800',
                                    'icon' => 'fa-minus',
                                    'response' => 'Response within 12h'
                                ],
                                [
                                    'id' => 'high',
                                    'name' => 'High',
                                    'description' => 'Significant impact on work',
                                    'color' => 'from-orange-100 to-orange-300',
                                    'textColor' => 'text-orange-800',
                                    'icon' => 'fa-arrow-up',
                                    'response' => 'Response within 6h'
                                ],
                                [
                                    'id' => 'urgent',
                                    'name' => 'Urgent',
                                    'description' => 'Critical, needs immediate attention',
                                    'color' => 'from-red-100 to-red-300',
                                    'textColor' => 'text-red-800',
                                    'icon' => 'fa-exclamation-triangle',
                                    'response' => 'Response within 2h'
                                ],
                            ];
                            ?>
                            
                            <?php foreach ($priorities as $priority): ?>
                            <div class="relative">
                                <input type="radio" 
                                       id="pri_<?= $priority['id'] ?>" 
                                       name="priority" 
                                       value="<?= $priority['id'] ?>"
                                       class="hidden peer"
                                       required>
                                <label for="pri_<?= $priority['id'] ?>" 
                                       class="block p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:scale-[1.02] smooth-transition peer-checked:border-secondary peer-checked:bg-gradient-to-br peer-checked:<?= $priority['color'] ?> peer-checked:shadow-lg">
                                    <div class="text-center">
                                        <div class="w-10 h-10 mx-auto mb-2 rounded-full <?= str_replace('from-', 'bg-', explode(' ', $priority['color'])[0]) ?> flex items-center justify-center">
                                            <i class="fas <?= $priority['icon'] ?> <?= $priority['textColor'] ?>"></i>
                                        </div>
                                        <h4 class="<?= $priority['textColor'] ?> text-sm font-bold mb-1 peer-checked:text-current"><?= $priority['name'] ?></h4>
                                        <p class="text-gray-600 text-xs mb-2 peer-checked:text-current/80"><?= $priority['description'] ?></p>
                                        <span class="text-xs <?= $priority['textColor'] ?> font-medium">
                                            <?= $priority['response'] ?>
                                        </span>
                                    </div>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Description with CKEditor -->
                    <div class="mb-8">
                        <label class="block text-text-muted text-base font-semibold mb-3 font-mulish">
                            <i class="fas fa-align-left mr-2 text-secondary"></i>Description
                        </label>
                        <div class="mb-2 text-sm text-gray-600">
                            Please describe your issue in detail. Include steps to reproduce, error messages, and what you've already tried.
                        </div>
                        <div id="editor-container">
                            <!-- CKEditor will be inserted here -->
                        </div>
                        <input type="hidden" id="description" name="description">
                        <div class="flex justify-between text-gray-500 text-xs mt-2">
                            <span id="descCounter">0 characters</span>
                            <span>Minimum 50 characters required</span>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div class="mb-8">
                        <label class="block text-text-muted text-base font-semibold mb-3 font-mulish">
                            <i class="fas fa-paperclip mr-2 text-secondary"></i>Attachments (Optional)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-secondary smooth-transition bg-white/50"
                             id="dropZone">
                            <input type="file" 
                                   id="attachments" 
                                   name="attachments[]" 
                                   multiple 
                                   class="hidden"
                                   accept="image/*,.pdf,.doc,.docx,.txt,.zip,.rar">
                            
                            <div class="mb-4">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-text-dark text-base font-medium mb-1">Drag & drop files here</p>
                                <p class="text-gray-500 text-sm">or click to browse</p>
                            </div>
                            
                            <button type="button" 
                                    onclick="document.getElementById('attachments').click()"
                                    class="px-5 py-2 bg-secondary text-white rounded-lg hover:bg-[#665C9E] smooth-transition font-medium text-sm">
                                <i class="fas fa-plus mr-2"></i>Select Files
                            </button>
                            
                            <p class="text-gray-500 text-xs mt-4">Max file size: 10MB per file • Supported: Images, PDF, Word, Text, ZIP</p>
                        </div>
                        
                        <!-- File List -->
                        <div id="fileList" class="mt-4 space-y-2 max-h-40 overflow-y-auto p-2"></div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-200">
                        <button type="button" 
                                onclick="window.history.back()"
                                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 smooth-transition font-medium flex-1 flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>Cancel
                        </button>
                        
                        <button type="submit" 
                                id="submitBtn"
                                class="px-6 py-3 bg-gradient-to-r from-secondary to-[#8A84C6] text-white rounded-lg hover:from-[#665C9E] hover:to-[#756EA4] smooth-transition font-medium flex-1 flex items-center justify-center shadow-lg hover:shadow-xl">
                            <span id="btnText">Submit Ticket</span>
                            <svg id="loadingSpinner" class="hidden w-5 h-5 ml-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:w-1/3">
            <!-- Help Tips -->
            <div class="bg-gradient-to-br from-dark-bg to-[#4A4570] rounded-2xl p-6 text-white shadow-lg mb-6">
                <h3 class="text-white text-xl font-semibold mb-4 font-mulish">
                    <i class="fas fa-lightbulb text-accent mr-2"></i>Tips for Faster Resolution
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-3 bg-white/10 rounded-lg">
                        <div class="w-8 h-8 bg-accent/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-search text-accent"></i>
                        </div>
                        <div>
                            <h4 class="text-white text-sm font-semibold mb-1">Check Knowledge Base</h4>
                            <p class="text-gray-300 text-xs">Search for similar issues in our knowledge base first.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3 p-3 bg-white/10 rounded-lg">
                        <div class="w-8 h-8 bg-accent/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-building text-accent"></i>
                        </div>
                        <div>
                            <h4 class="text-white text-sm font-semibold mb-1">Correct Department</h4>
                            <p class="text-gray-300 text-xs">Choose the right department for faster routing.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3 p-3 bg-white/10 rounded-lg">
                        <div class="w-8 h-8 bg-accent/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-flag text-accent"></i>
                        </div>
                        <div>
                            <h4 class="text-white text-sm font-semibold mb-1">Set Priority Wisely</h4>
                            <p class="text-gray-300 text-xs">Use appropriate priority for urgent issues.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3 p-3 bg-white/10 rounded-lg">
                        <div class="w-8 h-8 bg-accent/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-image text-accent"></i>
                        </div>
                        <div>
                            <h4 class="text-white text-sm font-semibold mb-1">Add Screenshots</h4>
                            <p class="text-gray-300 text-xs">Visual evidence helps us understand better.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Guide -->
            <div class="bg-gradient-to-br from-white to-card-bg/80 rounded-2xl p-6 shadow-lg border border-white/50 mb-6">
                <h3 class="text-text-dark text-xl font-semibold mb-4 font-mulish">
                    <i class="fas fa-info-circle text-secondary mr-2"></i>Department Guide
                </h3>
                
                <div class="space-y-3">
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-palette text-blue-500"></i>
                            <span class="text-blue-700 text-sm font-bold">Frontend</span>
                        </div>
                        <p class="text-blue-600 text-xs">UI issues, JavaScript errors, CSS problems, browser compatibility</p>
                    </div>
                    
                    <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-server text-green-500"></i>
                            <span class="text-green-700 text-sm font-bold">Backend</span>
                        </div>
                        <p class="text-green-600 text-xs">API errors, server issues, authentication, business logic</p>
                    </div>
                    
                    <div class="p-3 bg-purple-50 rounded-lg border border-purple-200">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-cloud text-purple-500"></i>
                            <span class="text-purple-700 text-sm font-bold">DevOps</span>
                        </div>
                        <p class="text-purple-600 text-xs">Deployment issues, server downtime, CI/CD pipelines</p>
                    </div>
                    
                    <div class="p-3 bg-orange-50 rounded-lg border border-orange-200">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-database text-orange-500"></i>
                            <span class="text-orange-700 text-sm font-bold">Database</span>
                        </div>
                        <p class="text-orange-600 text-xs">Query performance, data integrity, backup/restore, migration</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-gradient-to-br from-dark-bg/90 to-[#3D3C5E] rounded-2xl p-6 shadow-lg text-white">
                <h3 class="text-white text-xl font-semibold mb-4 font-mulish">
                    <i class="fas fa-chart-bar text-accent mr-2"></i>Support Statistics
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-300">Average First Response</span>
                            <span class="text-white font-bold">3.2 hours</span>
                        </div>
                        <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 w-3/4"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-300">Resolution Rate</span>
                            <span class="text-white font-bold">94%</span>
                        </div>
                        <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 w-[94%]"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-300">Customer Satisfaction</span>
                            <span class="text-white font-bold">4.8/5.0</span>
                        </div>
                        <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-500 w-[96%]"></div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-white/20">
                    <p class="text-gray-300 text-sm text-center">
                        <i class="fas fa-clock mr-1"></i>
                        Support Hours: 24/7
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let editor;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#editor-container'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'blockQuote', 'codeBlock', '|',
                        'undo', 'redo'
                    ],
                    shouldNotGroupWhenFull: true
                },
                placeholder: 'Please describe your issue in detail...',
                language: 'en',
                link: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://'
                },
                height: '250px'
            })
            .then(newEditor => {
                editor = newEditor;
                
                // Update character counter on editor content change
                editor.model.document.on('change:data', () => {
                    updateDescriptionCounter();
                });
                
                // Set initial counter
                updateDescriptionCounter();
            })
            .catch(error => {
                console.error(error);
                // Fallback to textarea if CKEditor fails
                document.querySelector('#editor-container').innerHTML = `
                    <textarea id="descriptionTextarea" name="description" rows="6" 
                              class="w-full p-4 bg-white/70 border border-gray-300 rounded-xl text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 resize-none"
                              placeholder="Please describe your issue in detail..."
                              oninput="updateDescriptionCounter()"
                              required></textarea>
                `;
            });

        const form = document.getElementById('createTicketForm');
        const titleInput = document.getElementById('ticketTitle');
        const titleCounter = document.getElementById('titleCounter');
        const descCounter = document.getElementById('descCounter');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('attachments');
        const fileList = document.getElementById('fileList');
        let files = [];
        
        // Character counters
        function updateTitleCounter() {
            const titleLength = titleInput.value.length;
            titleCounter.textContent = `${titleLength}/100 characters`;
            titleCounter.className = `text-right text-xs mt-1 ${titleLength > 100 ? 'text-red-500' : 'text-gray-500'}`;
        }
        
        function updateDescriptionCounter() {
            let content = '';
            if (editor) {
                content = editor.getData();
            } else {
                const textarea = document.querySelector('#descriptionTextarea');
                if (textarea) {
                    content = textarea.value;
                }
            }
            
            // Strip HTML tags for character count
            const strippedContent = content.replace(/<[^>]*>/g, '');
            const descLength = strippedContent.length;
            
            descCounter.textContent = `${descLength} characters`;
            descCounter.className = `text-xs ${descLength < 50 ? 'text-red-500' : 'text-gray-500'}`;
            
            // Update hidden input
            document.getElementById('description').value = content;
        }
        
        titleInput.addEventListener('input', updateTitleCounter);
        updateTitleCounter();
        
        // File upload handling
        function handleFiles(selectedFiles) {
            for (let file of selectedFiles) {
                if (file.size > 10 * 1024 * 1024) {
                    showToast(`File "${file.name}" exceeds 10MB limit`, 'error');
                    continue;
                }
                
                files.push(file);
                displayFile(file);
            }
            
            // Update drop zone text
            dropZone.querySelector('p.text-text-dark').textContent = 
                files.length > 0 ? `${files.length} file(s) selected` : 'Drag & drop files here';
        }
        
        function displayFile(file) {
            const fileId = Date.now() + Math.random();
            const fileSize = (file.size / (1024 * 1024)).toFixed(2);
            const fileExtension = file.name.split('.').pop().toUpperCase();
            
            // Get icon based on file type
            let fileIcon = 'fa-file';
            if (file.type.startsWith('image/')) fileIcon = 'fa-file-image';
            else if (file.type.includes('pdf')) fileIcon = 'fa-file-pdf';
            else if (file.type.includes('word') || file.type.includes('document')) fileIcon = 'fa-file-word';
            else if (file.type.includes('zip') || file.type.includes('compressed')) fileIcon = 'fa-file-archive';
            
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 bg-white/80 border border-gray-300 rounded-lg smooth-transition hover:bg-white';
            fileItem.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center">
                        <i class="fas ${fileIcon} text-secondary"></i>
                    </div>
                    <div>
                        <p class="text-text-dark text-sm font-medium truncate max-w-[150px]">${file.name}</p>
                        <p class="text-gray-500 text-xs">${fileSize} MB • ${fileExtension}</p>
                    </div>
                </div>
                <button type="button" onclick="removeFile('${fileId}')" class="text-red-500 hover:text-red-700 smooth-transition">
                    <i class="fas fa-times"></i>
                </button>
            `;
            fileItem.dataset.id = fileId;
            fileItem.dataset.file = file.name;
            fileList.appendChild(fileItem);
        }
        
        window.removeFile = function(fileId) {
            const fileItem = document.querySelector(`[data-id="${fileId}"]`);
            if (fileItem) {
                const fileName = fileItem.dataset.file;
                files = files.filter(f => f.name !== fileName);
                fileItem.remove();
                
                // Reset drop zone text if no files
                if (files.length === 0) {
                    dropZone.querySelector('p.text-text-dark').textContent = 'Drag & drop files here';
                }
            }
        };
        
        // Drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropZone.classList.add('border-secondary', 'bg-secondary/10', 'scale-[1.02]');
        }
        
        function unhighlight() {
            dropZone.classList.remove('border-secondary', 'bg-secondary/10', 'scale-[1.02]');
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const droppedFiles = dt.files;
            handleFiles(droppedFiles);
        }
        
        // File input change
        fileInput.addEventListener('change', function(e) {
            handleFiles(e.target.files);
            e.target.value = ''; // Reset input
        });
        
        // Toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-24 right-4 p-3 rounded-lg shadow-lg z-50 animate-slide-in max-w-sm ${
                type === 'error' ? 'bg-red-500 text-white' : 
                type === 'success' ? 'bg-green-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            toast.innerHTML = `
                <div class="flex items-start gap-2">
                    <i class="fas ${
                        type === 'error' ? 'fa-exclamation-circle' : 
                        type === 'success' ? 'fa-check-circle' : 
                        'fa-info-circle'
                    } mt-0.5 flex-shrink-0"></i>
                    <span class="text-sm">${message}</span>
                </div>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
        
        // Form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate form
            if (!validateForm()) {
                return;
            }
            
            // Show loading
            submitBtn.disabled = true;
            btnText.textContent = 'Creating Ticket...';
            loadingSpinner.classList.remove('hidden');
            
            try {
                // Create FormData
                const formData = new FormData(form);
                
                // Add files to FormData
                files.forEach(file => {
                    formData.append('attachments[]', file);
                });
                
                // Get CKEditor content
                if (editor) {
                    formData.set('description', editor.getData());
                }
                
                // Simulate API call (replace with actual API endpoint)
                await new Promise(resolve => setTimeout(resolve, 1500));
                
                // Show success message
                showToast('Ticket created successfully! Redirecting...', 'success');
                
                // Redirect to my tickets after delay
                setTimeout(() => {
                    window.location.href = '<?= base_url('dashboard/my_tickets') ?>';
                }, 2000);
                
            } catch (error) {
                console.error('Error:', error);
                showToast('Failed to create ticket. Please try again.', 'error');
                
                // Reset button
                submitBtn.disabled = false;
                btnText.textContent = 'Submit Ticket';
                loadingSpinner.classList.add('hidden');
            }
        });
        
        function validateForm() {
            let isValid = true;
            const errors = [];
            
            // Title validation
            if (!titleInput.value.trim()) {
                errors.push('Ticket title is required');
                titleInput.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/20');
                isValid = false;
            } else if (titleInput.value.length > 100) {
                errors.push('Title must be 100 characters or less');
                titleInput.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/20');
                isValid = false;
            } else {
                titleInput.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/20');
            }
            
            // Description validation
            let descriptionContent = '';
            if (editor) {
                descriptionContent = editor.getData();
            } else {
                const textarea = document.querySelector('#descriptionTextarea');
                if (textarea) {
                    descriptionContent = textarea.value;
                }
            }
            
            const strippedContent = descriptionContent.replace(/<[^>]*>/g, '');
            if (!strippedContent.trim()) {
                errors.push('Description is required');
                isValid = false;
            } else if (strippedContent.length < 50) {
                errors.push('Please provide more details (at least 50 characters)');
                isValid = false;
            }
            
            // Department validation
            const departmentSelected = document.querySelector('input[name="department"]:checked');
            if (!departmentSelected) {
                errors.push('Please select a department');
                isValid = false;
            }
            
            // Priority validation
            const prioritySelected = document.querySelector('input[name="priority"]:checked');
            if (!prioritySelected) {
                errors.push('Please select a priority level');
                isValid = false;
            }
            
            // Project validation
            const projectSelected = document.querySelector('input[name="project"]:checked');
            if (!projectSelected) {
                errors.push('Please select a project');
                isValid = false;
            }
            
            // Show errors
            if (errors.length > 0) {
                const errorHtml = errors.map(error => `<li class="mb-1">• ${error}</li>`).join('');
                showToast(`<div class="text-left"><p class="font-semibold mb-1">Please fix the following:</p><ul>${errorHtml}</ul></div>`, 'error');
            }
            
            return isValid;
        }
        
        // Add department selection feedback
        const departmentInputs = document.querySelectorAll('input[name="department"]');
        departmentInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Remove all selected classes first
                document.querySelectorAll('.department-card').forEach(card => {
                    card.classList.remove('selected');
                });
                
                // Add selected class to current
                const label = document.querySelector(`label[for="${this.id}"]`);
                if (label) {
                    label.classList.add('selected');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>