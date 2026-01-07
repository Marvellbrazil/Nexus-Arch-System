<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Ticket Detail - NEXUS<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.26)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.31)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.97)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-[77px] p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-[25px]">
        <h1 class="text-[35px] font-semibold mb-[5px] text-text-dark">Ticket Detail</h1>
        <p class="text-[15px] font-light text-[#666]">Tickets Area</p>
    </div>

    <!-- Ticket Header Info -->
    <div class="bg-dark-bg rounded-[24px] p-6 mb-6">
        <!-- Ticket ID, Title, Project -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-8">
                <div class="bg-[#BFBBE4] rounded-[34px] px-6 py-3">
                    <div class="flex items-center gap-6">
                        <span class="text-primary text-[17px] font-bold font-mulish">#12345</span>
                        <span class="text-primary/95 text-[17px] font-bold font-mulish">Cannot Access Dashboard</span>
                        <span class="text-primary text-[16px] font-bold font-mulish">ProjectX</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Date -->
                <span class="text-[#D8DEFF] text-[20px] font-semibold font-mulish">February 19, 2026</span>
                
                <!-- Priority -->
                <div class="w-[77px] h-[28px] bg-[#FF7D2C] rounded-[6px] flex items-center justify-center">
                    <span class="text-white text-[14px] font-bold font-mulish">HIGH</span>
                </div>
                
                <!-- Status -->
                <div class="w-[77px] h-[28px] bg-[#10B981] rounded-[6px] flex items-center justify-center">
                    <span class="text-white text-[14px] font-bold font-mulish">OPEN</span>
                </div>
            </div>
        </div>
        
        <!-- Status Label -->
        <div class="flex justify-end">
            <span class="text-[#D6D8FF] text-[11px] font-semibold font-mulish">Status</span>
        </div>
    </div>

    <!-- Ticket Details Section -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <!-- Ticket Summary -->
        <div class="col-span-2 bg-card-bg rounded-[24px] p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-[20px] font-normal text-text-muted opacity-90 font-mulish">Ticket Summary</h2>
                <div class="w-[121px] h-[22px] bg-secondary rounded-[4px] flex items-center gap-2 px-3">
                    <i class="fas fa-check text-white text-[12px]"></i>
                    <span class="text-white text-[10px] font-semibold font-mulish">Technical Issue</span>
                </div>
            </div>
            
            <div class="border-t border-text-muted/28 pt-4">
                <p class="text-text-muted text-[11px] font-medium leading-[17px] font-mulish">
                    I'm having trouble accessing the ProjectX dashboard. Every time I try to log in, I receive an error message that says "Access Denied".<br/>
                    I've tried clearing my cache and using different browsers, but the issue persists. Please assist.
                </p>
            </div>
        </div>
        
        <!-- Progress Info -->
        <div class="bg-card-bg rounded-[24px] p-6">
            <h3 class="text-[17px] font-normal text-text-muted opacity-90 mb-6 font-mulish">Progress Info :</h3>
            
            <div class="space-y-4">
                <!-- Progress Bar -->
                <div class="flex items-center gap-3">
                    <i class="fas fa-chart-line text-text-muted text-[20px]"></i>
                    <div class="flex-1">
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-secondary w-3/4"></div>
                        </div>
                        <span class="text-text-muted text-[11px]">75% Complete</span>
                    </div>
                </div>
                
                <!-- Status -->
                <div class="flex items-center gap-3">
                    <i class="fas fa-circle text-secondary text-[16px]"></i>
                    <span class="text-text-muted text-[17px] font-normal">Status : <span class="text-secondary font-semibold">In Progress</span></span>
                </div>
                
                <!-- Last Update -->
                <div class="flex items-center gap-3">
                    <i class="far fa-clock text-text-muted text-[16px]"></i>
                    <div>
                        <span class="text-text-muted text-[17px] font-normal">Last Update :</span>
                        <div class="text-[#6D5BD0] text-[11px] font-normal mt-1">Accept by Support Team 1 hour ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversation Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-[27px] font-medium text-text-dark/85">Conversation</h2>
            <div class="flex items-center gap-3">
                <!-- Download Button -->
                <button class="px-4 py-2 bg-[#E3DAEE] text-primary rounded-[5px] border border-[#E9D5FF] flex items-center gap-2 hover:bg-[#D5CBE8] transition-colors">
                    <i class="fas fa-download text-[#A855F7] text-[19px]"></i>
                    <span class="text-primary text-[19px] font-normal font-inter">Download</span>
                </button>
                
                <!-- Attach Button -->
                <button class="w-[56px] h-[44px] bg-[#E3DAEE] rounded-[5px] border-r border-[#E9D5FF] flex items-center justify-center hover:bg-[#D5CBE8] transition-colors">
                    <i class="fas fa-link text-[#A855F7] text-[22px]"></i>
                </button>
            </div>
        </div>
        
        <!-- Conversation Thread -->
        <div class="bg-card-bg rounded-[16px] p-8 shadow-sm">
            <!-- Customer Message -->
            <div class="mb-8 pb-8 border-b border-primary/41">
                <div class="flex gap-4">
                    <!-- Avatar -->
                    <div class="w-[48px] h-[48px] rounded-full border border-[rgba(59,62,68,0.03)] overflow-hidden flex-shrink-0">
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Message Content -->
                    <div class="flex-1">
                        <!-- Message Header -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <span class="text-[#1E293B] text-[16px] font-semibold font-roboto">John Smith</span>
                                <div class="bg-[#D6CCF3] rounded-[8px] px-3 py-1">
                                    <span class="text-[#7E22CE] text-[14px] font-normal font-inter">Customer</span>
                                </div>
                                <div class="w-[11px] h-[11px] bg-[#581C87] rounded-full flex items-center justify-center">
                                    <i class="fas fa-level-up-alt text-white text-[8px]"></i>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-primary text-[14px] font-normal font-roboto">Today, 11:00 AM</span>
                                <i class="fas fa-chevron-down text-primary text-[16px]"></i>
                            </div>
                        </div>
                        
                        <!-- Message Text -->
                        <p class="text-[#475569] text-[15px] font-normal leading-[27px] mb-4 font-roboto">
                            Hi team, please assist me in this issue. Attached is a screenshot of the error message and the log file.
                        </p>
                        
                        <!-- Attachment -->
                        <div class="bg-[#FAF5FF] rounded-[5px] border border-[#E2E8F0] p-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-[19px] h-[19px] bg-[#F3E8FF] rounded-[5px] flex items-center justify-center">
                                    <i class="fas fa-image text-[#A855F7]"></i>
                                </div>
                                <div>
                                    <div class="text-primary/65 text-[10px] font-normal font-inter">Image - 320KB</div>
                                    <div class="text-primary text-[14px] font-normal font-roboto">error_screenshot.png</div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="w-[22px] h-[22px] bg-[#F3E8FF] rounded-[5px] flex items-center justify-center">
                                    <i class="fas fa-download text-[#A855F7] text-[11px]"></i>
                                </button>
                                <button class="w-[22px] h-[22px] bg-[#F3E8FF] rounded-[5px] flex items-center justify-center">
                                    <i class="fas fa-paperclip text-[#A855F7] text-[11px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Support Message -->
            <div>
                <div class="flex gap-4">
                    <!-- Avatar -->
                    <div class="w-[49px] h-[48px] rounded-full bg-[#E2E8F0] flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-headset text-gray-400"></i>
                    </div>
                    
                    <!-- Message Content -->
                    <div class="flex-1">
                        <!-- Message Header -->
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-[#1E293B] text-[16px] font-semibold font-roboto">Bigmo Nasihuy</span>
                            <div class="bg-[#D6CCF3] rounded-[8px] px-3 py-1">
                                <span class="text-[#7E22CE] text-[14px] font-normal font-inter">Support</span>
                            </div>
                            <div class="w-[11px] h-[11px] bg-[#581C87] rounded-full flex items-center justify-center">
                                <i class="fas fa-level-up-alt text-white text-[8px]"></i>
                            </div>
                        </div>
                        
                        <!-- Message Text -->
                        <p class="text-[#475569] text-[16px] font-normal leading-[27px] mb-4 font-roboto">
                            Thank you for report, John. We've received your issue and will look into it as soon as possible.
                        </p>
                        
                        <!-- Attachment -->
                        <div class="bg-[#FAF5FF] rounded-[5px] border border-[#E2E8F0] p-3 w-[183px]">
                            <div class="flex items-center gap-3">
                                <div class="w-[19px] h-[19px] bg-[#F3E8FF] rounded-[5px] flex items-center justify-center">
                                    <i class="fas fa-image text-[#A855F7]"></i>
                                </div>
                                <div>
                                    <div class="text-primary/65 text-[10px] font-normal font-inter">Image - 123KB</div>
                                    <div class="text-primary text-[14px] font-normal font-inter">fix_attempt.png</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Section -->
    <div class="bg-card-bg rounded-[16px] p-6 shadow-lg">
        <div class="flex items-start gap-4">
            <!-- Textarea -->
            <div class="flex-1">
                <textarea 
                    placeholder="Write a reply to the customer..." 
                    class="w-full h-[50px] bg-transparent text-primary/52 text-[19px] font-normal leading-[27px] focus:outline-none resize-none font-roboto"
                    rows="1"
                ></textarea>
                
                <!-- File Info -->
                <div class="text-[#94A3B8] text-[12px] font-normal mt-2 font-roboto">
                    Max file size: 10MB
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="flex items-center gap-3">
                <!-- Choose File Button -->
                <button class="w-[109px] h-[26px] bg-[#7E6BC4] rounded-[20px] flex items-center justify-center gap-2 hover:bg-[#6D5CB3] transition-colors">
                    <div class="w-[8px] h-[14px] bg-white transform -rotate-16"></div>
                    <span class="text-white text-[12px] font-normal font-poppins">Choose File</span>
                </button>
                
                <!-- Send Reply Button -->
                <button class="w-[98px] h-[26px] bg-[#7E6BC4] rounded-[20px] flex items-center justify-center hover:bg-[#6D5CB3] transition-colors">
                    <span class="text-white text-[12px] font-normal font-poppins">Send Reply</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Poppins font -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Textarea auto-resize
        const textarea = document.querySelector('textarea');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // File upload preview
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.style.display = 'none';
        fileInput.accept = 'image/*,.pdf,.doc,.docx';
        
        document.querySelector('button:contains("Choose File")').addEventListener('click', function() {
            fileInput.click();
        });
        
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const fileName = e.target.files[0].name;
                const fileSize = (e.target.files[0].size / (1024 * 1024)).toFixed(2);
                
                if (fileSize > 10) {
                    alert('File size exceeds 10MB limit');
                    return;
                }
                
                // Show file info
                const fileInfo = document.createElement('div');
                fileInfo.className = 'text-green-600 text-sm mt-2';
                fileInfo.innerHTML = `Selected: ${fileName} (${fileSize} MB)`;
                
                // Remove previous file info
                const prevInfo = document.querySelector('.text-green-600');
                if (prevInfo) prevInfo.remove();
                
                document.querySelector('textarea').parentElement.appendChild(fileInfo);
            }
        });
        
        // Send reply button
        document.querySelector('button:contains("Send Reply")').addEventListener('click', function() {
            const message = document.querySelector('textarea').value.trim();
            
            if (!message) {
                alert('Please write a reply before sending');
                return;
            }
            
            // Simulate sending
            alert('Reply sent successfully!');
            document.querySelector('textarea').value = '';
            document.querySelector('textarea').style.height = 'auto';
            
            // Remove file info if exists
            const fileInfo = document.querySelector('.text-green-600');
            if (fileInfo) fileInfo.remove();
        });
    });
</script>
<?= $this->endSection() ?>