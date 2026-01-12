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
        min-height: 150px;
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #e5e7eb !important;
        border-radius: 0.5rem !important;
        padding: 0.75rem !important;
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
    
    /* Priority Selection Styles */
    .priority-card {
        transition: all 0.3s ease;
    }
    
    .priority-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .priority-card.selected {
        border-color: #756EA4;
        background-color: rgba(117, 110, 164, 0.05);
        box-shadow: 0 5px 15px rgba(117, 110, 164, 0.2);
    }
    
    /* Mobile Responsive Adjustments */
    @media (max-width: 768px) {
        .ck-editor__editable {
            min-height: 120px;
            max-height: 250px;
        }
        
        .priority-card {
            padding: 0.5rem !important;
        }
        
        .selected-project-card {
            padding: 0.75rem !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-4 md:mt-[77px] px-4 md:px-[30px] py-4 md:py-[20px] relative z-10 max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex-1">
                <h1 class="text-xl md:text-2xl lg:text-[35px] font-semibold mb-1 md:mb-2 text-text-dark">Create New Ticket</h1>
                <p class="text-xs md:text-sm lg:text-[15px] font-light text-[#666]">Submit a new support request</p>
            </div>
            
            <a href="<?= base_url('customer/dashboard') ?>" 
               class="px-4 md:px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 smooth-transition font-medium flex items-center justify-center text-sm md:text-base no-underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="flex flex-col lg:flex-row gap-4 md:gap-6">
        <!-- Form Container -->
        <div class="lg:w-2/3">
            <div class="bg-gradient-to-br from-white/90 to-card-bg/80 rounded-2xl p-4 md:p-6 shadow-lg border border-white/50 backdrop-blur-sm">
                <form id="createTicketForm" action="<?= base_url('dashboard/tickets/create') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <!-- Selected Project Display -->
                    <?php
                    // Get project from URL parameter
                    $projectId = $_GET['project'] ?? 'proj1';
                    
                    // Define projects data (should come from database in real application)
                    $projects = [
                        'proj1' => [
                            'name' => 'Project Alpha', 
                            'description' => 'Main enterprise project', 
                            'tickets' => 12, 
                            'color' => 'from-blue-500 to-blue-600',
                            'icon' => 'fa-project-diagram'
                        ],
                        'proj2' => [
                            'name' => 'Project Beta', 
                            'description' => 'E-commerce platform', 
                            'tickets' => 8, 
                            'color' => 'from-green-500 to-green-600',
                            'icon' => 'fa-shopping-cart'
                        ],
                        'proj3' => [
                            'name' => 'Project Gamma', 
                            'description' => 'Mobile application', 
                            'tickets' => 5, 
                            'color' => 'from-purple-500 to-purple-600',
                            'icon' => 'fa-mobile-alt'
                        ],
                        'proj4' => [
                            'name' => 'Project Delta', 
                            'description' => 'Database migration', 
                            'tickets' => 3, 
                            'color' => 'from-orange-500 to-orange-600',
                            'icon' => 'fa-database'
                        ],
                        'proj5' => [
                            'name' => 'Project Epsilon', 
                            'description' => 'API development', 
                            'tickets' => 7, 
                            'color' => 'from-pink-500 to-pink-600',
                            'icon' => 'fa-code'
                        ],
                        'proj6' => [
                            'name' => 'Project Zeta', 
                            'description' => 'Security audit', 
                            'tickets' => 4, 
                            'color' => 'from-red-500 to-red-600',
                            'icon' => 'fa-shield-alt'
                        ],
                    ];
                    
                    $selectedProject = $projects[$projectId] ?? $projects['proj1'];
                    ?>
                    
                    <div class="mb-6 md:mb-8">
                        <label class="block text-text-muted text-sm md:text-base font-semibold mb-3 md:mb-4 font-mulish">
                            <i class="fas fa-project-diagram mr-1 md:mr-2 text-secondary"></i>Selected Project
                        </label>
                        
                        <div class="p-4 md:p-5 bg-gradient-to-br <?= $selectedProject['color'] ?> rounded-xl text-white shadow-lg selected-project-card">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start gap-3 md:gap-4">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                        <i class="fas <?= $selectedProject['icon'] ?> text-white text-lg md:text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white text-lg md:text-xl font-bold mb-1"><?= $selectedProject['name'] ?></h4>
                                        <p class="text-white/90 text-sm md:text-base mb-2"><?= $selectedProject['description'] ?></p>
                                        <div class="flex items-center gap-4">
                                            <span class="inline-flex items-center gap-1 bg-white/20 px-3 py-1 rounded-full text-xs md:text-sm">
                                                <i class="fas fa-ticket-alt"></i>
                                                <?= $selectedProject['tickets'] ?> total tickets
                                            </span>
                                            <a href="<?= base_url('customer/dashboard') ?>" 
                                               class="text-white/80 hover:text-white text-xs md:text-sm font-medium flex items-center gap-1">
                                                <i class="fas fa-exchange-alt"></i>
                                                Change Project
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-6 h-6 md:w-8 md:h-8 bg-white/20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="project" value="<?= $projectId ?>">
                        
                        <div class="mt-3 text-gray-600 text-xs md:text-sm">
                            <i class="fas fa-info-circle text-secondary mr-1"></i>
                            This ticket will be created under <?= $selectedProject['name'] ?>. 
                            <a href="<?= base_url('customer/dashboard') ?>" class="text-secondary font-medium hover:underline">Click here</a> 
                            to select a different project.
                        </div>
                    </div>

                    <!-- Ticket Title -->
                    <div class="mb-6 md:mb-8">
                        <label for="ticketTitle" class="block text-text-muted text-sm md:text-base font-semibold mb-2 md:mb-3 font-mulish">
                            <i class="fas fa-heading mr-1 md:mr-2 text-secondary"></i>Ticket Title
                        </label>
                        <div class="relative">
                            <input type="text" 
                                    id="ticketTitle" 
                                    name="title" 
                                    placeholder="Brief description of your issue"
                                    class="w-full h-10 md:h-[50px] px-3 md:px-4 pl-8 md:pl-10 bg-white/70 border border-gray-300 rounded-xl text-text-dark text-xs md:text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 smooth-transition"
                                    required
                                    maxlength="100">
                            <i class="fas fa-pen absolute left-2 md:left-3 top-1/2 transform -translate-y-5 text-gray-400 text-xs md:text-sm"></i>
                            <div class="text-right text-gray-500 text-xs mt-1" id="titleCounter">0/100 characters</div>
                        </div>
                    </div>

                    <!-- Priority Selection -->
                    <div class="mb-6 md:mb-8">
                        <label class="block text-text-muted text-sm md:text-base font-semibold mb-3 md:mb-4 font-mulish">
                            <i class="fas fa-flag mr-1 md:mr-2 text-secondary"></i>Priority Level
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-3">
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
                                       class="block p-2 md:p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:scale-[1.02] smooth-transition peer-checked:border-secondary peer-checked:bg-gradient-to-br peer-checked:<?= $priority['color'] ?> peer-checked:shadow-lg priority-card">
                                    <div class="text-center">
                                        <div class="w-6 h-6 md:w-10 md:h-10 mx-auto mb-1 md:mb-2 rounded-full <?= str_replace('from-', 'bg-', explode(' ', $priority['color'])[0]) ?> flex items-center justify-center">
                                            <i class="fas <?= $priority['icon'] ?> <?= $priority['textColor'] ?> text-xs md:text-base"></i>
                                        </div>
                                        <h4 class="<?= $priority['textColor'] ?> text-xs md:text-sm font-bold mb-1 peer-checked:text-current"><?= $priority['name'] ?></h4>
                                        <p class="text-gray-600 text-xs mb-1 md:mb-2 peer-checked:text-current/80 hidden md:block"><?= $priority['description'] ?></p>
                                        <span class="text-xs <?= $priority['textColor'] ?> font-medium hidden md:inline">
                                            <?= $priority['response'] ?>
                                        </span>
                                    </div>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Description with CKEditor -->
                    <div class="mb-6 md:mb-8">
                        <label class="block text-text-muted text-sm md:text-base font-semibold mb-2 md:mb-3 font-mulish">
                            <i class="fas fa-align-left mr-1 md:mr-2 text-secondary"></i>Description
                        </label>
                        <div class="mb-2 text-xs md:text-sm text-gray-600">
                            Please describe your issue in detail. Include steps to reproduce, error messages, and what you've already tried.
                        </div>
                        <div id="editor-container">
                            <!-- CKEditor will be inserted here -->
                        </div>
                        <input type="hidden" id="description" name="description">
                        <div class="flex justify-between text-gray-500 text-xs mt-2">
                            <span id="descCounter">0 characters</span>
                            <span class="hidden sm:inline">Minimum 50 characters required</span>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div class="mb-6 md:mb-8">
                        <label class="block text-text-muted text-sm md:text-base font-semibold mb-2 md:mb-3 font-mulish">
                            <i class="fas fa-paperclip mr-1 md:mr-2 text-secondary"></i>Attachments (Optional)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 md:p-6 text-center hover:border-secondary smooth-transition bg-white/50"
                             id="dropZone">
                            <input type="file" 
                                   id="attachments" 
                                   name="attachments[]" 
                                   multiple 
                                   class="hidden"
                                   accept="image/*,.pdf,.doc,.docx,.txt,.zip,.rar">
                            
                            <div class="mb-3 md:mb-4">
                                <i class="fas fa-cloud-upload-alt text-2xl md:text-3xl text-gray-400 mb-1 md:mb-2"></i>
                                <p class="text-text-dark text-sm md:text-base font-medium mb-1">Drag & drop files here</p>
                                <p class="text-gray-500 text-xs md:text-sm">or click to browse</p>
                            </div>
                            
                            <button type="button" 
                                    onclick="document.getElementById('attachments').click()"
                                    class="px-3 md:px-5 py-1 md:py-2 bg-secondary text-white rounded-lg hover:bg-[#665C9E] smooth-transition font-medium text-xs md:text-sm">
                                <i class="fas fa-plus mr-1 md:mr-2"></i>Select Files
                            </button>
                            
                            <p class="text-gray-500 text-xs mt-3 md:mt-4">Max file size: 10MB per file • Supported: Images, PDF, Word, Text, ZIP</p>
                        </div>
                        
                        <!-- File List -->
                        <div id="fileList" class="mt-3 md:mt-4 space-y-2 max-h-32 md:max-h-40 overflow-y-auto p-2"></div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 md:gap-4 pt-6 md:pt-8 border-t border-gray-200">
                        <button type="button" 
                                onclick="window.history.back()"
                                class="px-4 md:px-6 py-2 md:py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 smooth-transition font-medium flex-1 flex items-center justify-center text-sm md:text-base">
                            <i class="fas fa-arrow-left mr-1 md:mr-2"></i>Cancel
                        </button>
                        
                        <button type="submit" 
                                id="submitBtn"
                                class="px-4 md:px-6 py-2 md:py-3 bg-gradient-to-r from-secondary to-[#8A84C6] text-white rounded-lg hover:from-[#665C9E] hover:to-[#756EA4] smooth-transition font-medium flex-1 flex items-center justify-center shadow-lg hover:shadow-xl text-sm md:text-base">
                            <span id="btnText">Create Ticket for <?= $selectedProject['name'] ?></span>
                            <svg id="loadingSpinner" class="hidden w-4 h-4 md:w-5 md:h-5 ml-1 md:ml-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:w-1/3 mt-4 md:mt-0">
            <!-- Project Info -->
            <div class="bg-gradient-to-br from-dark-bg to-[#4A4570] rounded-2xl p-4 md:p-6 text-white shadow-lg mb-4 md:mb-6">
                <h3 class="text-white text-lg md:text-xl font-semibold mb-3 md:mb-4 font-mulish">
                    <i class="fas fa-project-diagram text-accent mr-1 md:mr-2"></i>Project Information
                </h3>
                
                <div class="space-y-3 md:space-y-4">
                    <div class="flex items-start gap-2 md:gap-3 p-2 md:p-3 bg-white/10 rounded-lg">
                        <div class="w-6 h-6 md:w-8 md:h-8 bg-accent/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-ticket-alt text-accent text-xs md:text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white text-xs md:text-sm font-semibold mb-1">Total Tickets</h4>
                            <p class="text-gray-300 text-xs"><?= $selectedProject['tickets'] ?> tickets in this project</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-2 md:gap-3 p-2 md:p-3 bg-white/10 rounded-lg">
                        <div class="w-6 h-6 md:w-8 md:h-8 bg-accent/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-accent text-xs md:text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white text-xs md:text-sm font-semibold mb-1">Average Resolution</h4>
                            <p class="text-gray-300 text-xs">24 hours for similar issues</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-2 md:gap-3 p-2 md:p-3 bg-white/10 rounded-lg">
                        <div class="w-6 h-6 md:w-8 md:h-8 bg-accent/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-users text-accent text-xs md:text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white text-xs md:text-sm font-semibold mb-1">Assigned Team</h4>
                            <p class="text-gray-300 text-xs">Development & Support Team</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-2 md:gap-3 p-2 md:p-3 bg-white/10 rounded-lg">
                        <div class="w-6 h-6 md:w-8 md:h-8 bg-accent/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-history text-accent text-xs md:text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white text-xs md:text-sm font-semibold mb-1">Recent Activity</h4>
                            <p class="text-gray-300 text-xs">Last ticket: 2 days ago</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Tips -->
            <div class="bg-white rounded-2xl p-4 md:p-6 shadow-lg border border-gray-200">
                <h3 class="text-text-dark text-lg md:text-xl font-semibold mb-3 md:mb-4 font-mulish">
                    <i class="fas fa-lightbulb text-secondary mr-1 md:mr-2"></i>Tips for Faster Resolution
                </h3>
                
                <div class="space-y-3 md:space-y-4">
                    <div class="flex items-start gap-2 md:gap-3 p-2 md:p-3 bg-gray-50 rounded-lg">
                        <div class="w-6 h-6 md:w-8 md:h-8 bg-secondary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-search text-secondary text-xs md:text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-text-dark text-xs md:text-sm font-semibold mb-1">Be Specific</h4>
                            <p class="text-gray-600 text-xs">Include exact error messages and steps to reproduce.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-2 md:gap-3 p-2 md:p-3 bg-gray-50 rounded-lg">
                        <div class="w-6 h-6 md:w-8 md:h-8 bg-secondary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-flag text-secondary text-xs md:text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-text-dark text-xs md:text-sm font-semibold mb-1">Set Priority Wisely</h4>
                            <p class="text-gray-600 text-xs">Use appropriate priority for urgent issues.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-2 md:gap-3 p-2 md:p-3 bg-gray-50 rounded-lg">
                        <div class="w-6 h-6 md:w-8 md:h-8 bg-secondary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-image text-secondary text-xs md:text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-text-dark text-xs md:text-sm font-semibold mb-1">Add Screenshots</h4>
                            <p class="text-gray-600 text-xs">Visual evidence helps us understand better.</p>
                        </div>
                    </div>
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
                height: '200px'
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
                              class="w-full p-3 md:p-4 bg-white/70 border border-gray-300 rounded-xl text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 resize-none"
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
            fileItem.className = 'flex items-center justify-between p-2 md:p-3 bg-white/80 border border-gray-300 rounded-lg smooth-transition hover:bg-white';
            fileItem.innerHTML = `
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-secondary/10 flex items-center justify-center">
                        <i class="fas ${fileIcon} text-secondary text-sm md:text-base"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-text-dark text-xs md:text-sm font-medium truncate max-w-[120px] md:max-w-[150px]">${file.name}</p>
                        <p class="text-gray-500 text-xs">${fileSize} MB • ${fileExtension}</p>
                    </div>
                </div>
                <button type="button" onclick="removeFile('${fileId}')" class="text-red-500 hover:text-red-700 smooth-transition ml-2">
                    <i class="fas fa-times text-xs md:text-sm"></i>
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
            toast.className = `fixed top-20 md:top-24 right-4 p-3 rounded-lg shadow-lg z-50 animate-slide-in max-w-xs md:max-w-sm ${type === 'error' ? 'bg-red-500 text-white' : type === 'success' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white'}`;
            toast.innerHTML = `
                <div class="flex items-start gap-2">
                    <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : type === 'success' ? 'fa-check-circle' : 'fa-info-circle'} mt-0.5 flex-shrink-0"></i>
                    <span class="text-xs md:text-sm">${message}</span>
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
                
                // Simulate API call
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
                btnText.textContent = 'Create Ticket for <?= $selectedProject['name'] ?>';
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
            
            // Priority validation
            const prioritySelected = document.querySelector('input[name="priority"]:checked');
            if (!prioritySelected) {
                errors.push('Please select a priority level');
                isValid = false;
            }
            
            // Show errors
            if (errors.length > 0) {
                const errorHtml = errors.map(error => `<li class="mb-1 text-xs">• ${error}</li>`).join('');
                showToast(`<div class="text-left"><p class="font-semibold mb-1 text-xs">Please fix the following:</p><ul>${errorHtml}</ul></div>`, 'error');
            }
            
            return isValid;
        }
        
        // Add priority selection feedback
        const priorityInputs = document.querySelectorAll('input[name="priority"]');
        priorityInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Remove all selected classes first
                document.querySelectorAll('.priority-card').forEach(card => {
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