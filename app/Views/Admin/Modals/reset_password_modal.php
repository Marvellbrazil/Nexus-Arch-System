<!-- resources/views/Admin/modals/reset_password_modal.php -->
<div id="resetPasswordModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800">Reset Password</h3>
                <button class="close-modal text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <form id="resetPasswordForm" class="p-6">
            <input type="hidden" id="resetUserId" name="user_id">

            <div class="space-y-4">
                <div>
                    <label class="block text-gray-600 text-sm mb-2">New Password *</label>
                    <input type="password" id="newPassword" name="new_password"
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    <div class="text-red-500 text-sm mt-1 hidden" id="newPasswordError"></div>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm mb-2">Confirm Password *</label>
                    <input type="password" id="confirmPassword" name="confirm_password"
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    <div class="text-red-500 text-sm mt-1 hidden" id="confirmPasswordError"></div>
                </div>
            </div>
        </form>

        <div class="p-6 border-t border-gray-200 flex gap-3">
            <button
                class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Cancel
            </button>
            <button id="confirmReset"
                class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                Reset Password
            </button>
        </div>
    </div>
</div>