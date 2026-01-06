<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }
        
        body {
            background-color: #D7D5EE;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }
        
        .background-effects {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }
        
        .background-effect-1 {
            width: 40vw;
            height: 40vw;
            position: absolute;
            right: -10%;
            bottom: -10%;
            transform: rotate(149deg);
            background: linear-gradient(75deg, rgba(56.96, 44.47, 127.72, 0.26) 75%, #D6D3EE 83%, rgba(174.48, 162.84, 202.33, 0.94) 100%, #817CB2 100%);
            filter: blur(80px);
            opacity: 0.8;
        }
        
        .background-effect-2 {
            width: 35vw;
            height: 25vw;
            position: absolute;
            left: -5%;
            top: 10%;
            transform: rotate(8deg);
            background: linear-gradient(75deg, rgba(65.04, 45.10, 137.14, 0.31) 75%, #D6D3EE 83%, rgba(174.48, 162.84, 202.33, 0.94) 100%, #817CB2 100%);
            filter: blur(80px);
            opacity: 0.7;
        }
        
        .background-effect-3 {
            width: 15vw;
            height: 20vw;
            position: absolute;
            right: 5%;
            top: -5%;
            transform: rotate(8deg);
            background: linear-gradient(75deg, rgba(16.56, 8.41, 46.05, 0.97) 33%, #D6D3EE 83%, rgba(174.48, 162.84, 202.33, 0.94) 100%, #817CB2 100%);
            filter: blur(80px);
            opacity: 0.6;
        }
        
        .background-effect-4 {
            width: 20vw;
            height: 15vw;
            position: absolute;
            left: -5%;
            top: -5%;
            transform: rotate(4deg);
            background: linear-gradient(75deg, rgba(16.56, 8.41, 46.05, 0.39) 75%, #D6D3EE 83%, rgba(174.48, 162.84, 202.33, 0.94) 100%, #817CB2 100%);
            filter: blur(80px);
            opacity: 0.5;
        }
        
        .login-container {
            position: relative;
            z-index: 2;
            width: 90%;
            max-width: 450px;
            min-width: 320px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            justify-content: center;
            width: 100%;
        }
        
        .logo-img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            background: #434264;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
        }
        
        .logo-text {
            color: #817CB2;
            font-size: 36px;
            font-weight: 700;
            margin-left: 15px;
        }
        
        .login-card {
            width: 100%;
            background: #434264;
            border-radius: 19px;
            padding: 25px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .login-title {
            text-align: center;
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            width: 100%;
        }
        
        .form-label {
            color: rgba(204, 221, 255, 0.80);
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-input {
            width: 100%;
            height: 54px;
            padding: 0 20px;
            background: rgba(204, 221, 255, 0.12);
            border: 1px solid rgba(204, 221, 255, 0.24);
            border-radius: 6px;
            color: rgba(204, 221, 255, 0.80);
            font-size: 17px;
            font-weight: 700;
            outline: none;
            transition: all 0.3s;
        }
        
        .form-input:focus {
            border-color: rgba(204, 221, 255, 0.6);
            box-shadow: 0 0 0 2px rgba(204, 221, 255, 0.2);
        }
        
        .password-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            width: 100%;
        }
        
        .forgot-link {
            color: rgba(204, 221, 255, 0.80);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }
        
        .forgot-link:hover {
            color: #BA94ED;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        
        .checkbox-container {
            width: 18px;
            height: 18px;
            min-width: 18px;
            min-height: 18px;
            position: relative;
            background: rgba(204, 221, 255, 0.12);
            border-radius: 4px;
            border: 2px solid rgba(204, 221, 255, 0.12);
            margin-right: 10px;
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
        
        .remember-label {
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }
        
        .login-button {
            width: 100%;
            height: 45px;
            background: #756EA4;
            border: none;
            border-radius: 6px;
            color: #C1D1F3;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            margin: 20px 0;
        }
        
        .login-button:hover {
            background: #817CB2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .signup-section {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 15px;
            width: 100%;
        }
        
        .signup-text {
            color: rgba(204, 221, 255, 0.92);
            font-size: 14px;
            font-weight: 700;
            margin-right: 5px;
        }
        
        .signup-link {
            color: #BA94ED;
            font-size: 14px;
            font-weight: 400;
            text-decoration: none;
            cursor: pointer;
        }
        
        .signup-link:hover {
            text-decoration: underline;
        }
        
        .alert-message {
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            display: none; /* Sembunyikan default */
        }
        
        .alert-success {
            background-color: rgba(76, 175, 80, 0.15);
            color: #4CAF50;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }
        
        .alert-error {
            background-color: rgba(244, 67, 54, 0.15);
            color: #f44336;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }
        
        @media (max-width: 768px) {
            .login-container {
                max-width: 400px;
                padding: 15px;
            }
            
            .logo-text {
                font-size: 30px;
            }
            
            .logo-img {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            
            .login-card {
                padding: 20px;
            }
            
            .background-effect-1, .background-effect-2, .background-effect-3, .background-effect-4 {
                filter: blur(60px);
            }
        }
        
        @media (max-width: 480px) {
            .login-container {
                max-width: 350px;
            }
            
            .logo-text {
                font-size: 26px;
            }
            
            .login-title {
                font-size: 28px;
            }
            
            .form-input {
                height: 48px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Background Effects -->
    <div class="background-effects">
        <div class="background-effect-1"></div>
        <div class="background-effect-2"></div>
        <div class="background-effect-3"></div>
        <div class="background-effect-4"></div>
    </div>
    
    <!-- Login Container -->
    <div class="login-container">
        <!-- Logo -->
        <div class="logo-container">
            <div class="logo-img">NX</div>
            <div class="logo-text">NEXUS</div>
        </div>
        
        <!-- Login Card -->
        <div class="login-card">
            <h2 class="login-title">Login</h2>
            
            <!-- Alert Message (akan ditampilkan via JavaScript) -->
            <div id="alertMessage" class="alert-message"></div>
            
            <!-- Login Form -->
            <form id="loginForm" action="<?= base_url('auth/process_login') ?>" method="POST">
                <!-- CSRF Token untuk keamanan -->
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                
                <!-- Email Field -->
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Enter email" required>
                    <small id="emailError" style="color: #ff6b6b; font-size: 12px; margin-top: 5px; display: none;"></small>
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <div class="password-header">
                        <label class="form-label" for="password">Password</label>
                        <a href="<?= base_url('auth/forgot_password') ?>" class="forgot-link">Forgot?</a>
                    </div>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Password" required>
                    <small id="passwordError" style="color: #ff6b6b; font-size: 12px; margin-top: 5px; display: none;"></small>
                </div>
                
                <!-- Remember Me -->
                <div class="remember-me">
                    <div class="checkbox-container" id="rememberCheckbox"></div>
                    <input type="hidden" name="remember" id="remember" value="0">
                    <span class="remember-label" onclick="toggleRemember()">Remember Me</span>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="login-button">Login</button>
            </form>
            
            
        </div>
    </div>
    
    <!-- JavaScript -->
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
            alertDiv.className = `alert-message alert-${type}`;
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