<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>NEXUS - Department Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#5d4037',
                        'secondary': '#6d4c41',
                        'accent': '#8d6e63',
                        'light-bg': '#efebe9',
                        'text-light': 'rgba(255, 255, 255, 0.9)',
                        'border-light': 'rgba(255, 255, 255, 0.2)',
                    },
                    fontFamily: {
                        'roboto': ['Roboto', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .checkbox-container {
            width: 18px;
            height: 18px;
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
        }
        .checkbox-container.checked {
            background: #8d6e63;
            border-color: #8d6e63;
        }
        .checkbox-container.checked::after {
            content: '';
            position: absolute;
            width: 10px;
            height: 6px;
            border-left: 2px solid white;
            border-bottom: 2px solid white;
            transform: rotate(-45deg);
            top: 3px;
            left: 3px;
        }
        .form-input:focus {
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 2px rgba(141, 110, 99, 0.3);
        }
    </style>
</head>
<body class="bg-light-bg min-h-screen flex justify-center items-center overflow-hidden relative font-roboto px-4">
    <!-- Background Effects -->
    <div class="absolute w-full h-full top-0 left-0 z-1">
        <div class="w-[40vw] h-[40vw] absolute -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(93,64,55,0.3)] via-[#d7ccc8] to-[#a1887f] blur-[60px] md:blur-80 opacity-80"></div>
        <div class="w-[35vw] h-[25vw] absolute -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(93,64,55,0.3)] via-[#d7ccc8] to-[#a1887f] blur-[60px] md:blur-80 opacity-70"></div>
    </div>

    <!-- Login Container -->
    <div class="relative z-2 w-full max-w-[400px] md:max-w-[450px] p-4 md:p-5 flex flex-col items-center">
        <!-- Logo -->
        <div class="flex items-center mb-6 md:mb-[30px]">
            <div class="w-12 h-12 md:w-[60px] md:h-[60px] rounded-lg md:rounded-[10px] bg-primary flex items-center justify-center text-white font-bold text-lg md:text-[24px]">
                <i class="fas fa-building"></i>
            </div>
            <div class="text-[#6d4c41] text-2xl md:text-[36px] font-bold ml-3 md:ml-[15px]">
                NEXUS Department
            </div>
        </div>
        
        <!-- Login Card -->
        <div class="w-full bg-primary rounded-xl md:rounded-[19px] p-4 md:p-[25px_30px] shadow-lg md:shadow-[0_10px_30px_rgba(0,0,0,0.2)]">
            <h2 class="text-center text-white text-2xl md:text-[32px] font-bold mb-6 md:mb-[30px]">
                Department Portal
            </h2>
            
            <!-- Role Selection -->
            <div class="mb-4 text-center">
                <div class="inline-flex items-center bg-secondary/50 rounded-full px-4 py-2">
                    <i class="fas fa-building text-white mr-2"></i>
                    <span class="text-white text-sm font-bold">Department Team Access</span>
                </div>
            </div>
            
            <!-- Department Info -->
            <div class="mb-4 p-3 bg-secondary/30 rounded-lg">
                <p class="text-text-light text-xs text-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    For: IT Support, Technical Support, UI/UX Support, Feature Request
                </p>
            </div>
            
            <!-- Flash Messages -->
            <?php if(session()->getFlashdata('success')): ?>
                <div class="bg-[rgba(76,175,80,0.15)] text-[#4CAF50] p-3 md:p-[12px_20px] rounded md:rounded-[6px] mb-4 md:mb-[20px] text-xs md:text-[14px] font-semibold text-center border border-[rgba(76,175,80,0.3)]">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="bg-[rgba(244,67,54,0.15)] text-[#f44336] p-3 md:p-[12px_20px] rounded md:rounded-[6px] mb-4 md:mb-[20px] text-xs md:text-[14px] font-semibold text-center border border-[rgba(244,67,54,0.3)]">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form id="loginForm" action="<?= base_url('process_login') ?>" method="POST">
                <!-- CSRF Token -->
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <!-- Hidden field untuk identifikasi login type -->
                <input type="hidden" name="login_type" value="department">
                
                <!-- Email Field -->
                <div class="mb-4 md:mb-[20px] w-full">
                    <label for="email" class="block text-text-light text-xs md:text-[14px] font-bold mb-2 md:mb-[8px]">
                        Department Email
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-light/70">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?= old('email') ?>"
                            class="w-full h-12 md:h-[54px] pl-12 pr-4 bg-[rgba(255,255,255,0.1)] border border-border-light rounded md:rounded-[6px] text-text-light text-sm md:text-[17px] font-bold outline-none form-input transition-all duration-300 placeholder:text-text-light/50"
                            placeholder="enter your department email"
                            required
                            autocomplete="email"
                        >
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="mb-4 md:mb-[20px] w-full">
                    <label for="password" class="block text-text-light text-xs md:text-[14px] font-bold mb-2 md:mb-[8px]">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-light/70">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full h-12 md:h-[54px] pl-12 pr-12 bg-[rgba(255,255,255,0.1)] border border-border-light rounded md:rounded-[6px] text-text-light text-sm md:text-[17px] font-bold outline-none form-input transition-all duration-300 placeholder:text-text-light/50"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        >
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-text-light/70 cursor-pointer" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Remember Me -->
                <div class="flex items-center my-4 md:my-[20px]">
                    <div class="checkbox-container" id="rememberCheckbox"></div>
                    <input type="hidden" name="remember" id="remember" value="0">
                    <span class="text-white text-xs md:text-[14px] font-bold ml-2 md:ml-[10px] cursor-pointer select-none" onclick="toggleRemember()">
                        Remember Me
                    </span>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full h-12 md:h-[45px] bg-secondary hover:bg-[#5d4037] text-white text-sm md:text-[16px] font-extrabold rounded md:rounded-[6px] border-none cursor-pointer transition-all duration-300 my-4 md:my-[20px] hover:-translate-y-[2px] hover:shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Login to Department
                </button>
                
                <!-- Other Login Portals -->
                <div class="text-center mt-4">
                    <p class="text-text-light text-xs md:text-[12px] mb-2">Access other portals:</p>
                    <div class="flex justify-center space-x-3">
                        <a href="<?= base_url('login') ?>" class="text-text-light hover:text-accent text-xs">
                            <i class="fas fa-users mr-1"></i>Customer
                        </a>
                        <a href="<?= base_url('admin/login') ?>" class="text-text-light hover:text-accent text-xs">
                            <i class="fas fa-user-shield mr-1"></i>Admin
                        </a>
                        <a href="<?= base_url('support/login') ?>" class="text-text-light hover:text-accent text-xs">
                            <i class="fas fa-headset mr-1"></i>Support
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="mt-4 text-center text-[#6d4c41] text-xs md:text-[12px]">
            <p>© 2024 NEXUS Arch System. Department Portal v1.0</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.type === 'password' ? 'text' : 'password';
                    passwordInput.type = type;
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                });
            }
            
            // Toggle remember me
            const rememberCheckbox = document.getElementById('rememberCheckbox');
            const rememberInput = document.getElementById('remember');
            function toggleRemember() {
                if (rememberCheckbox) {
                    rememberCheckbox.classList.toggle('checked');
                    if (rememberInput) {
                        rememberInput.value = rememberCheckbox.classList.contains('checked') ? '1' : '0';
                    }
                }
            }
            if (rememberCheckbox) {
                rememberCheckbox.addEventListener('click', toggleRemember);
            }
        });
    </script>
</body>
</html>