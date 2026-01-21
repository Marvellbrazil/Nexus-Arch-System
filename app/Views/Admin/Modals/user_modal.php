<!-- resources/views/Admin/modals/user_modal.php
<div id="userModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800" id="modalTitle">Add New User</h3>
                <button class="close-modal text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <form id="userForm" class="p-6">
            <input type="hidden" id="userId" name="user_id">

            <div class="space-y-4">
                <div>
                    <label class="block text-gray-600 text-sm mb-2">Full Name *</label>
                    <input type="text" id="fullName" name="full_name"
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    <div class="text-red-500 text-sm mt-1 hidden" id="fullNameError"></div>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm mb-2">Username *</label>
                    <input type="text" id="username" name="username"
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    <div class="text-red-500 text-sm mt-1 hidden" id="usernameError"></div>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm mb-2">Email Address *</label>
                    <input type="email" id="email" name="email"
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    <div class="text-red-500 text-sm mt-1 hidden" id="emailError"></div>
                </div>

                <div id="passwordField">
                    <label class="block text-gray-600 text-sm mb-2">Password *</label>
                    <input type="password" id="password" name="password"
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    <div class="text-red-500 text-sm mt-1 hidden" id="passwordError"></div>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm mb-2">Role *</label>
                    <select id="roleId" name="role_id"
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                        <option value="">Select Role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="text-red-500 text-sm mt-1 hidden" id="roleIdError"></div>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm mb-2">Department</label>
                    <select id="departmentId" name="department_id"
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?= $department['department_id'] ?>">
                                <?= htmlspecialchars($department['department_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm mb-2">Phone Number</label>
                    <input type="text" id="phoneNumber" name="phone_number"
                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="isActive" name="is_active"
                        class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                    <label for="isActive" class="ml-2 block text-sm text-gray-700">Active Account</label>
                </div>
            </div>
        </form>

        <div class="p-6 border-t border-gray-200 flex gap-3">
            <button
                class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Cancel
            </button>
            <button id="saveUser"
                class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                Save User
            </button>
        </div>
    </div>
</div> -->