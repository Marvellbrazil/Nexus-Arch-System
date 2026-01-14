<?= $this->extend('layouts/auth_layout') ?>

<?= $this->section('title') ?>Forgot Password<?= $this->endSection() ?>

<?= $this->section('content') ?>


<!-- Forgot Password Card -->
<div
    class="w-full bg-primary rounded-xl md:rounded-[19px] p-4 md:p-[25px_30px] shadow-lg md:shadow-[0_10px_30px_rgba(0,0,0,0.2)]">
    <h2 class="text-center text-white text-2xl md:text-[32px] font-bold mb-6 md:mb-[30px]">
        Forgot Password
    </h2>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div
            class="bg-[rgba(244,67,54,0.15)] text-[#f44336] p-3 md:p-[12px_20px] rounded md:rounded-[6px] mb-4 md:mb-[20px] text-xs md:text-[14px] font-semibold text-center border border-[rgba(244,67,54,0.3)]">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div
            class="bg-[rgba(76,175,80,0.15)] text-[#4CAF50] p-3 md:p-[12px_20px] rounded md:rounded-[6px] mb-4 md:mb-[20px] text-xs md:text-[14px] font-semibold text-center border border-[rgba(76,175,80,0.3)]">
            <i class="fas fa-check-circle mr-2"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Forgot Password Form -->
    <form action="/auth/process_forgot_password" method="POST">
        <?= csrf_field() ?>

        <!-- Email Field -->
        <div class="mb-4 md:mb-[20px] w-full">
            <label for="email" class="block text-text-light text-xs md:text-[14px] font-bold mb-2 md:mb-[8px]">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-light/70">
                    <i class="fas fa-envelope"></i>
                </div>
                <input type="email" id="email" name="email"
                    class="w-full h-12 md:h-[54px] pl-12 pr-4 bg-[rgba(204,221,255,0.12)] border border-border-light rounded md:rounded-[6px] text-text-light text-sm md:text-[17px] font-bold outline-none form-input transition-all duration-300 placeholder:text-text-light/50"
                    placeholder="Enter your email" required autocomplete="email">
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full h-12 md:h-[45px] bg-secondary hover:bg-[#817CB2] text-[#C1D1F3] text-sm md:text-[16px] font-extrabold rounded md:rounded-[6px] border-none cursor-pointer transition-all duration-300 my-2 md:my-[10px] hover:-translate-y-[2px] hover:shadow-lg">
            Send Reset Link
        </button>
        <div class="text-center">
            <a href="<?= base_url('login') ?>"
                class="text-text-light text-xs md:text-[14px] font-bold no-underline hover:text-accent cursor-pointer transition-colors">
                Back to Login?
            </a>
        </div>
    </form>
</div>
</div>
<?= $this->endSection() ?>