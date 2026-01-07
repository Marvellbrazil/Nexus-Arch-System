<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - Login</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Configuration -->
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
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS untuk checkbox -->
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
        
        .blur-80 {
            filter: blur(80px);
        }
        
        .blur-60 {
            filter: blur(60px);
        }
        
        @media (max-width: 768px) {
            .blur-80 {
                filter: blur(60px);
            }
        }
    </style>
</head>
<body class="bg-light-bg min-h-screen flex justify-center items-center overflow-hidden relative font-roboto">
    
    <!-- Background Effects -->
    <div class="absolute w-full h-full top-0 left-0 z-1">
        <div class="w-[40vw] h-[40vw] absolute -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.26)] via-[#D6D3EE] to-[#817CB2] blur-80 opacity-80"></div>
        <div class="w-[35vw] h-[25vw] absolute -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.31)] via-[#D6D3EE] to-[#817CB2] blur-80 opacity-70"></div>
        <div class="w-[15vw] h-[20vw] absolute right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.97)] via-[#D6D3EE] to-[#817CB2] blur-80 opacity-60"></div>
        <div class="w-[20vw] h-[15vw] absolute -left-[5%] -top-[5%] rotate-[4deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.39)] via-[#D6D3EE] to-[#817CB2] blur-80 opacity-50"></div>
    </div>
    
    <!-- Login Container -->
    <div class="relative z-2 w-[90%] max-w-[450px] min-w-[320px] p-5 flex flex-col items-center">
        <!-- Logo -->
        <div class="flex items-center mb-[30px] justify-center w-full">
            <div class="w-[60px] h-[60px] rounded-[10px] bg-primary flex items-center justify-center text-white font-bold text-[24px]">
                NX
            </div>
            <div class="text-[#817CB2] text-[36px] font-bold ml-[15px]">
                NEXUS
            </div>
        </div>
        
        <!-- Login Card -->
        <div class="w-full bg-primary rounded-[19px] p-[25px_30px] shadow-[0_10px_30px_rgba(0,0,0,0.2)]">
            <h2 class="text-center text-white text-[32px] font-bold mb-[30px]">
                Login
            </h2>
            
            <!-- Alert Message -->
            <div id="alertMessage" class="alert-message"></div>
            
            <!-- Login Form -->
            <form id="loginForm" action="<?= base_url('auth/process_login') ?>" method="POST">
                <!-- CSRF Token -->
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                
                <!-- Email Field -->
                <div class="mb-[20px] w-full">
                    <label for="email" class="block text-text-light text-[14px] font-bold mb-[8px]">
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="w-full h-[54px] px-5 bg-[rgba(204,221,255,0.12)] border border-border-light rounded-[6px] text-text-light text-[17px] font-bold outline-none form-input transition-all duration-300 placeholder:text-text-light/50"
                        placeholder="Enter email"
                        required
                    >
                    <small id="emailError" class="text-[#ff6b6b] text-[12px] mt-[5px] hidden"></small>
                </div>
                
                <!-- Password Field -->
                <div class="mb-[20px] w-full">
                    <div class="flex justify-between items-center mb-[8px] w-full">
                        <label for="password" class="block text-text-light text-[14px] font-bold">
                            Password
                        </label>
                        <a href="<?= base_url('auth/forgot_password') ?>" class="text-text-light text-[14px] font-bold no-underline hover:text-accent cursor-pointer">
                            Forgot?
                        </a>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full h-[54px] px-5 bg-[rgba(204,221,255,0.12)] border border-border-light rounded-[6px] text-text-light text-[17px] font-bold outline-none form-input transition-all duration-300 placeholder:text-text-light/50"
                        placeholder="Password"
                        required
                    >
                    <small id="passwordError" class="text-[#ff6b6b] text-[12px] mt-[5px] hidden"></small>
                </div>
                
                <!-- Remember Me -->
                <div class="flex items-center my-[20px]">
                    <div class="checkbox-container" id="rememberCheckbox"></div>
                    <input type="hidden" name="remember" id="remember" value="0">
                    <span class="text-white text-[14px] font-bold ml-[10px] cursor-pointer" onclick="toggleRemember()">
                        Remember Me
                    </span>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full h-[45px] bg-secondary hover:bg-[#817CB2] text-[#C1D1F3] text-[16px] font-extrabold rounded-[6px] border-none cursor-pointer transition-all duration-300 my-[20px] hover:-translate-y-[2px] hover:shadow-[0_5px_15px_rgba(0,0,0,0.2)]"
                >
                    Login
                </button>
            </form>
        </div>
    </div>
    
    <!-- JavaScript (Sama seperti original) -->
    <script>
        // Fungsi untuk toggle Remember Me
        function toggleRemember() {
            const rememberCheckbox = document.getElementById('rememberCheckbox');
            const rememberInput = document.getElementById('remember');
            
            rememberCheckbox.classList.toggle('checked');
            rememberInput.value = rememberCheckbox.classList.contains('checked') ? '1' : '0';
        }
        
        // Fungsi untuk menampilkan alert
        function showAlert(message, type = 'success') {
            const alertDiv = document.getElementById('alertMessage');
            alertDiv.textContent = message;
            alertDiv.className = `p-[12px_20px] rounded-[6px] mb-[20px] text-[14px] font-semibold text-center ${type === 'success' ? 'bg-[rgba(76,175,80,0.15)] text-[#4CAF50] border border-[rgba(76,175,80,0.3)]' : 'bg-[rgba(244,67,54,0.15)] text-[#f44336] border border-[rgba(244,67,54,0.3)]'}`;
            alertDiv.style.display = 'block';
            
            // Sembunyikan alert setelah 5 detik
            setTimeout(() => {
                alertDiv.style.display = 'none';
            }, 5000);
        }
        
        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const rememberCheckbox = document.getElementById('rememberCheckbox');
            const rememberInput = document.getElementById('remember');
            const loginForm = document.getElementById('loginForm');
            
            // Event listener untuk checkbox
            rememberCheckbox.addEventListener('click', toggleRemember);
            
            // Form validation
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Reset error messages
                document.getElementById('emailError').style.display = 'none';
                document.getElementById('passwordError').style.display = 'none';
                
                // Get form values
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                let isValid = true;
                
                // Validate email
                if (!email) {
                    document.getElementById('emailError').textContent = 'Email is required';
                    document.getElementById('emailError').style.display = 'block';
                    isValid = false;
                } else if (!isValidEmail(email)) {
                    document.getElementById('emailError').textContent = 'Please enter a valid email';
                    document.getElementById('emailError').style.display = 'block';
                    isValid = false;
                }
                
                // Validate password
                if (!password) {
                    document.getElementById('passwordError').textContent = 'Password is required';
                    document.getElementById('passwordError').style.display = 'block';
                    isValid = false;
                } else if (password.length < 6) {
                    document.getElementById('passwordError').textContent = 'Password must be at least 6 characters';
                    document.getElementById('passwordError').style.display = 'block';
                    isValid = false;
                }
                
                // If valid, submit form
                if (isValid) {
                    // Tampilkan pesan loading
                    showAlert('Logging in...', 'success');
                    
                    // Submit form secara asynchronous
                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('Login successful! Redirecting...', 'success');
                            // Redirect setelah 2 detik
                            setTimeout(() => {
                                window.location.href = '/dashboard';
                            }, 2000);
                        } else {
                            showAlert(data.message || 'Login failed. Please try again.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('An error occurred. Please try again.', 'error');
                    });
                }
                
                return false;
            });
            
            // Helper function untuk validasi email
            function isValidEmail(email) {
                const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            }
            
            // Demo: Cek jika ada parameter di URL untuk menampilkan pesan
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('message')) {
                const message = urlParams.get('message');
                const type = urlParams.get('type') || 'success';
                showAlert(decodeURIComponent(message), type);
            }
        });
    </script>
</body>
</html>