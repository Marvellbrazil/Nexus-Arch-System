<?php
// File: app/Views/Admin/ticket_details.php
?>

<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Ticket Details - <?= esc($ticket['subject'] ?? 'NEXUS Admin') ?><?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="relative z-10">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="<?= base_url('admin/tickets') ?>"
            class="inline-flex items-center gap-2 text-secondary hover:text-[#665C9E] transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Tickets</span>
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-[34.77px] font-semibold text-text-dark">
                        <?= esc($ticket['subject'] ?? 'Ticket Details') ?>
                    </h1>
                </div>
                <p class="text-[15.45px] font-light text-text-dark">
                    Ticket #<?= esc($ticket['ticket_number'] ?? $ticket['ticket_id']) ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Ticket Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Information Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Ticket Information</div>
                </div>
                <div class="p-6">
                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-text-dark font-medium mb-2">Description</h3>
                        <div class="text-text-dark/80 bg-white/50 rounded-lg p-4 border border-white/30 min-h-[100px]">
                            <?php if (!empty($ticket['description'])): ?>
                                <?= nl2br(esc($ticket['description'])) ?>
                            <?php else: ?>
                                <div class="text-text-dark/50 italic">No description provided</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Ticket ID -->
                        <div>
                            <div class="text-text-dark/70 text-sm mb-1">Ticket ID</div>
                            <div class="text-text-dark font-medium">
                                <?= esc($ticket['ticket_number'] ?? $ticket['ticket_id']) ?>
                            </div>
                        </div>

                        <!-- Project -->
                        <div>
                            <div class="text-text-dark/70 text-sm mb-1">Project</div>
                            <div class="text-text-dark font-medium">
                                <?= esc($ticket['project_name'] ?? 'Not assigned') ?>
                                <?php if (!empty($ticket['project_code'])): ?>
                                    <span class="text-text-dark/60 text-sm ml-1">(<?= esc($ticket['project_code']) ?>)</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Department -->
                        <div>
                            <div class="text-text-dark/70 text-sm mb-1">Department</div>
                            <div class="text-text-dark font-medium">
                                <?= esc($ticket['department_name'] ?? 'Not assigned') ?>
                            </div>
                        </div>

                        <!-- Customer -->
                        <div>
                            <div class="text-text-dark/70 text-sm mb-1">Customer</div>
                            <div class="text-text-dark font-medium">
                                <?= esc($ticket['customer_name'] ?? 'Unknown') ?>

                            </div>
                        </div>

                        <!-- Assigned To -->
                        <div>
                            <div class="text-text-dark/70 text-sm mb-1">Assigned To</div>
                            <div class="text-text-dark font-medium">
                                <?php if (!empty($ticket['assigned_to_name'])): ?>
                                    <?= esc($ticket['assigned_to_name']) ?>
                                <?php else: ?>
                                    <span class="text-text-dark/50 italic">Unassigned</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <div class="text-text-dark/70 text-sm mb-1">Category</div>
                            <div class="text-text-dark font-medium">
                                <?= esc($ticket['category_name'] ?? 'Uncategorized') ?>
                            </div>
                        </div>

                        <!-- Priority -->
                        <div>
                            <div class="text-text-dark/70 text-sm mb-1">Priority</div>
                            <div class="text-text-dark font-medium">
                                <span class="<?= $getPriorityBadgeClass($ticket['priority_name'] ?? '') ?> priority-badge inline-block">
                                    <?= esc($ticket['priority_name'] ?? 'Unknown') ?>
                                </span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <div class="text-text-dark/70 text-sm mb-1">Status</div>
                            <div class="text-text-dark font-medium">
                                <span class="<?= $getStatusBadgeClass($ticket['status_name'] ?? '') ?> status-badge text-sm">
                                    <?= esc($ticket['status_name'] ?? 'Unknown') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Recent Activity</div>
                </div>
                <div class="p-6">
                    <?php if (!empty($activity)): ?>
                        <div class="space-y-4">
                            <?php foreach ($activity as $activityItem): ?>
                                <div class="flex items-start gap-3 pb-4 border-b border-white/30 last:border-b-0 last:pb-0">
                                    <!-- Avatar -->
                                    <div class="w-10 h-10 rounded-full bg-secondary/20 flex items-center justify-center flex-shrink-0">
                                        <?php if (!empty($activityItem['photo_profile'])): ?>
                                            <img src="<?= base_url('uploads/profile/' . esc($activityItem['photo_profile'])) ?>"
                                                alt="<?= esc($activityItem['full_name'] ?? 'User') ?>"
                                                class="w-10 h-10 rounded-full object-cover">
                                        <?php else: ?>
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center">
                                                <span class="text-white font-medium text-sm">
                                                    <?= substr($activityItem['full_name'] ?? 'U', 0, 1) ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-1">
                                            <div>
                                                <div class="font-medium text-text-dark">
                                                    <?= esc($activityItem['full_name'] ?? 'System') ?>
                                                    <?php if (!empty($activityItem['role_name'])): ?>
                                                        <span class="text-text-dark/60 text-xs ml-2">(<?= esc($activityItem['role_name']) ?>)</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="text-text-dark/60 text-sm">
                                                <?= date('M d, Y H:i', strtotime($activityItem['created_at'])) ?>
                                            </div>
                                        </div>

                                        <!-- Message Content -->
                                        <div class="text-text-dark/80 text-sm mt-2 bg-white/30 rounded-lg p-3">
                                            <?php if (!empty($activityItem['message'])): ?>
                                                <?= nl2br(esc($activityItem['message'])) ?>
                                            <?php else: ?>
                                                <span class="text-text-dark/50 italic">No message content</span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Message Type Badge -->
                                        <?php if (!empty($activityItem['is_internal']) && $activityItem['is_internal']): ?>
                                            <div class="inline-block mt-2 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                                                <i class="fas fa-lock mr-1"></i> Internal Note
                                            </div>
                                        <?php else: ?>
                                            <div class="inline-block mt-2 px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded">
                                                <i class="fas fa-comment mr-1"></i> Public Comment
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 text-text-dark/50">
                            <i class="fas fa-history text-3xl mb-3"></i>
                            <p>No activity recorded yet</p>
                            <p class="text-sm mt-2">Be the first to add a comment!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Timeline & Actions -->
        <div class="space-y-6">
            <!-- Timeline Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Timeline</div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Created -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-plus text-green-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-text-dark mb-1">Ticket Created</div>
                                <div class="text-text-dark/60 text-sm">
                                    <?= date('M d, Y H:i', strtotime($ticket['created_at'])) ?>
                                </div>
                                <div class="text-text-dark/80 text-sm mt-1">
                                    by <?= esc($ticket['customer_name'] ?? 'Customer') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Last Updated -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-edit text-blue-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-text-dark mb-1">Last Updated</div>
                                <div class="text-text-dark/60 text-sm">
                                    <?= date('M d, Y H:i', strtotime($ticket['updated_at'] ?? $ticket['created_at'])) ?>
                                </div>
                                <?php if (!empty($ticket['updated_at']) && $ticket['updated_at'] != $ticket['created_at']): ?>
                                    <div class="text-text-dark/80 text-sm mt-1">
                                        <?= $time_ago($ticket['updated_at']) ?> ago
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Status Changes -->
                        <?php if (!empty($status_history)): ?>
                            <?php foreach ($status_history as $history): ?>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-exchange-alt text-purple-600 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-text-dark mb-1">
                                            Status changed to <?= esc($history['status_name'] ?? $history['new_value']) ?>
                                        </div>
                                        <div class="text-text-dark/60 text-sm">
                                            <?= date('M d, Y H:i', strtotime($history['created_at'])) ?>
                                        </div>
                                        <?php if (!empty($history['changed_by_name'])): ?>
                                            <div class="text-text-dark/80 text-sm mt-1">
                                                by <?= esc($history['changed_by_name']) ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($history['change_reason'])): ?>
                                            <div class="text-text-dark/70 text-sm mt-1 italic">
                                                "<?= esc($history['change_reason']) ?>"
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Quick Actions</div>
                </div>
                <div class="p-6 space-y-3">
                    <!-- Delete Ticket -->
                    <button onclick="confirmDelete()"
                        class="w-full h-10 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i>
                        Delete Ticket
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Style yang sama dengan view_tickets.php */
    .status-badge {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .status-open {
        background: #ACCBE3;
        color: #3E566B;
    }

    .status-in-progress {
        background: #BBACE3;
        color: #403E6B;
    }

    .status-resolved {
        background: #C4E3AC;
        color: #3E6B57;
    }

    .status-closed {
        background: #E3DDAC;
        color: #6B553E;
    }

    .status-need-info {
        background: #C7C5C8;
        color: #403E6B;
    }

    .priority-badge {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .priority-urgent {
        background: #E16D7F;
        color: #873134;
    }

    .priority-high {
        background: #FFD2D2;
        color: #991B1B;
    }

    .priority-medium {
        background: #FED7AA;
        color: #9A3412;
    }

    .priority-low {
        background: #C7D2FE;
        color: #3730A3;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function confirmDelete() {
        if (confirm('Are you sure you want to delete this ticket? This action cannot be undone.')) {
            const ticketId = '<?= $ticket["ticket_id"] ?>';

            $.ajax({
                url: '/admin/tickets/delete',
                type: 'POST',
                data: {
                    ticket_id: ticketId
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Ticket deleted successfully');
                        window.location.href = '/admin/tickets';
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Network error. Please try again.');
                }
            });
        }
    }
</script>
<?= $this->endSection() ?>