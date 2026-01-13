<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>NEXUS - Customer Login</title>
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
                        'primary': '#434264',
                        'secondary': '#756EA4',
                        'accent': '#BA94ED',
                        'light-bg': '#D7D5EE',
                        'text-light': 'rgba(204, 221, 255, 0.80)',
                        'border-light': 'rgba(204, 221, 255, 0.24)',
                    },
                    fontFamily: {
                        'roboto': ['Roboto', 'sans-serif'],
                    },
                    screens: {
                        'xs': '480px',
                    }
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
            min-width: 18px;
            min-height: 18px;
            position: relative;
            background: rgba(204, 221, 255, 0.12);
            border-radius: 4px;
            border: 2px solid rgba(204, 221, 255, 0.12);
            cursor: pointer;
        }
        .checkbox-container.checked {
            background: #756EA4;
            border-color: #756EA4;
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
            border-color: rgba(204, 221, 255, 0.6);
            box-shadow: 0 0 0 2px rgba(204, 221, 255, 0.2);
        }
        /* Style untuk pesan error dengan link */
        .error-message a {
            color: #ffcccb;
            text-decoration: underline;
            font-weight: bold;
        }
        .error-message a:hover {
            color: #ff9999;
        }
    </style>
</head>
<body class="bg-light-bg min-h-screen flex justify-center items-center overflow-hidden relative font-roboto px-4">
    <!-- Background Effects -->
    <div class="absolute w-full h-full top-0 left-0 z-1">
        <div class="w-[40vw] h-[40vw] absolute -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.26)] via-[#D6D3EE] to-[#817CB2] blur-[60px] md:blur-80 opacity-80"></div>
        <div class="w-[35vw] h-[25vw] absolute -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.31)] via-[#D6D3EE] to-[#817CB2] blur-[60px] md:blur-80 opacity-70"></div>
    </div>

    <!-- Login Container -->
    <div class="relative z-2 w-full max-w-[400px] md:max-w-[450px] min-w-[280px] p-4 md:p-5 flex flex-col items-center">
        <!-- Logo -->
        <div class="flex items-center mb-6 md:mb-[30px] justify-center w-full">
            <div class="w-12 h-12 md:w-[60px] md:h-[60px] rounded-lg md:rounded-[10px] bg-primary flex items-center justify-center text-white font-bold text-lg md:text-[24px]">
                NX
            </div>
            <div class="text-[#817CB2] text-2xl md:text-[36px] font-bold ml-3 md:ml-[15px]">
                NEXUS
            </div>
        </div>
        

        
        <!-- Login Card -->
        <div class="w-full bg-primary rounded-xl md:rounded-[19px] p-4 md:p-[25px_30px] shadow-lg md:shadow-[0_10px_30px_rgba(0,0,0,0.2)]">
            <h2 class="text-center text-white text-2xl md:text-[32px] font-bold mb-6 md:mb-[30px]">
                Login
            </h2>
            
            <!-- Flash Messages -->
            <?php if(session()->getFlashdata('success')): ?>
                <div class="bg-[rgba(76,175,80,0.15)] text-[#4CAF50] p-3 md:p-[12px_20px] rounded md:rounded-[6px] mb-4 md:mb-[20px] text-xs md:text-[14px] font-semibold text-center border border-[rgba(76,175,80,0.3)]">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="bg-[rgba(244,67,54,0.15)] text-[#f44336] p-3 md:p-[12px_20px] rounded md:rounded-[6px] mb-4 md:mb-[20px] text-xs md:text-[14px] font-semibold text-center border border-[rgba(244,67,54,0.3)] error-message">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form id="loginForm" action="<?= base_url('process_login') ?>" method="POST">
                <!-- CSRF Token -->
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <!-- Hidden field untuk identifikasi login type -->
                <input type="hidden" name="login_type" value="customer">
                
                <!-- Email Field -->
                <div class="mb-4 md:mb-[20px] w-full">
                    <label for="email" class="block text-text-light text-xs md:text-[14px] font-bold mb-2 md:mb-[8px]">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-light/70">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?= old('email') ?>"
                            class="w-full h-12 md:h-[54px] pl-12 pr-4 bg-[rgba(204,221,255,0.12)] border border-border-light rounded md:rounded-[6px] text-text-light text-sm md:text-[17px] font-bold outline-none form-input transition-all duration-300 placeholder:text-text-light/50"
                            placeholder="Enter your email"
                            required
                            autocomplete="email"
                        >
                    </div>
                    <p class="text-text-light/70 text-xs mt-1">Use your registered email</p>
                </div>
                
                <!-- Password Field -->
                <div class="mb-4 md:mb-[20px] w-full">
                    <div class="flex justify-between items-center mb-2 md:mb-[8px] w-full">
                        <label for="password" class="block text-text-light text-xs md:text-[14px] font-bold">
                            Password
                        </label>
                        <a href="<?= base_url('auth/forgot_password') ?>" class="text-text-light text-xs md:text-[14px] font-bold no-underline hover:text-accent cursor-pointer transition-colors">
                            Forgot Password?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-light/70">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full h-12 md:h-[54px] pl-12 pr-12 bg-[rgba(204,221,255,0.12)] border border-border-light rounded md:rounded-[6px] text-text-light text-sm md:text-[17px] font-bold outline-none form-input transition-all duration-300 placeholder:text-text-light/50"
                            placeholder="Enter your password"
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
                    class="w-full h-12 md:h-[45px] bg-secondary hover:bg-[#817CB2] text-[#C1D1F3] text-sm md:text-[16px] font-extrabold rounded md:rounded-[6px] border-none cursor-pointer transition-all duration-300 my-4 md:my-[20px] hover:-translate-y-[2px] hover:shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
                
                
            </form>
        </div>
        
        <!-- Footer -->
        <div class="mt-4 text-center text-[#817CB2] text-xs md:text-[12px]">
            <p>© 2024 NEXUS Arch System. Customer Portal v1.0</p>
            <p class="mt-1 text-xs opacity-70">Only customers can login through this portal</p>
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
            
            // Form validation untuk memastikan hanya customer yang bisa login
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const email = document.getElementById('email').value;
                    // Bisa ditambahkan validasi frontend tambahan di sini jika perlu
                });
            }
        });
    </script>
</body>
</html>