<?= $this->extend('layouts/auth_layout') ?>

<?= $this->section('title') ?>Reset Password<?= $this->endSection() ?>

<?= $this->section('content') ?>


<!-- Reset Password Card -->
<div
    class="w-full bg-primary rounded-xl md:rounded-[19px] p-4 md:p-[25px_30px] shadow-lg md:shadow-[0_10px_30px_rgba(0,0,0,0.2)]">
    <h2 class="text-center text-white text-2xl md:text-[32px] font-bold mb-6 md:mb-[30px]">
        Reset Password
    </h2>
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div
            class="bg-[rgba(76,175,80,0.15)] text-[#4CAF50] p-3 md:p-[12px_20px] rounded md:rounded-[6px] mb-4 md:mb-[20px] text-xs md:text-[14px] font-semibold text-center border border-[rgba(76,175,80,0.3)]">
            <i class="fas fa-check-circle mr-2"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div
            class="bg-[rgba(244,67,54,0.15)] text-[#f44336] p-3 md:p-[12px_20px] rounded md:rounded-[6px] mb-4 md:mb-[20px] text-xs md:text-[14px] font-semibold text-center border border-[rgba(244,67,54,0.3)] error-message">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    <!-- Reset Password Form -->
    <form action="/auth/process_reset_password" method="POST">
        <?= csrf_field() ?>
        <!-- Hidden ID Field -->
        <input type="hidden" name="userId" value="<?= isset($userId) ? $userId : session()->getFlashdata('userId') ?>">
        <!-- Password Field -->
        <div class="mb-4 md:mb-[20px] w-full">
            <div class="flex justify-between items-center mb-2 md:mb-[8px] w-full">
                <label for="password" class="block text-text-light text-xs md:text-[14px] font-bold">
                    Password
                </label>
            </div>
            <div class="relative">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-light/70">
                    <i class="fas fa-lock"></i>
                </div>
                <input type="password" id="password" name="password"
                    class="w-full h-12 md:h-[54px] pl-12 pr-12 bg-[rgba(204,221,255,0.12)] border border-border-light rounded md:rounded-[6px] text-text-light text-sm md:text-[17px] font-bold outline-none form-input transition-all duration-300 placeholder:text-text-light/50"
                    placeholder="Enter your password" required autocomplete="current-password">
                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-text-light/70 cursor-pointer"
                    id="togglePassword">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>

        <!-- Confirm Password Field -->
        <div class="mb-4 md:mb-[20px] w-full">
            <div class="flex justify-between items-center mb-2 md:mb-[8px] w-full">
                <label for="confirm-password" class="block text-text-light text-xs md:text-[14px] font-bold">
                    Confirm Password
                </label>
            </div>
            <div class="relative">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-light/70">
                    <i class="fas fa-lock"></i>
                </div>
                <input type="password" id="confirm-password" name="confirm_password"
                    class="w-full h-12 md:h-[54px] pl-12 pr-12 bg-[rgba(204,221,255,0.12)] border border-border-light rounded md:rounded-[6px] text-text-light text-sm md:text-[17px] font-bold outline-none form-input transition-all duration-300 placeholder:text-text-light/50"
                    placeholder="Enter your password" required autocomplete="current-password">
                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-text-light/70 cursor-pointer"
                    id="toggleConfirmPassword">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full h-12 md:h-[45px] bg-secondary hover:bg-[#817CB2] text-[#C1D1F3] text-sm md:text-[16px] font-extrabold rounded md:rounded-[6px] border-none cursor-pointer transition-all duration-300 my-2 md:my-[10px] hover:-translate-y-[2px] hover:shadow-lg">
            Change Password
        </button>
        <div class="text-center">
            <a href="<?= base_url('login') ?>"
                class="text-text-light text-xs md:text-[14px] font-bold no-underline hover:text-accent cursor-pointer transition-colors">
                Cancel?
            </a>
        </div>
    </form>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        }

        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirm-password');
        if (toggleConfirmPassword && confirmPasswordInput) {
            toggleConfirmPassword.addEventListener('click', function () {
                const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
                confirmPasswordInput.type = type;
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        }
    });
</script>
<?= $this->endSection() ?>