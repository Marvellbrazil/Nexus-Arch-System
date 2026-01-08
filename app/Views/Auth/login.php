<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>NEXUS - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN (DEV ONLY) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-[#D7D5EE] relative overflow-hidden">

    <!-- Background Effects -->
    <div class="absolute inset-0 z-0">
        <div class="absolute right-[-10%] bottom-[-10%] w-[40vw] h-[40vw] blur-[80px] opacity-80"
            style="background: linear-gradient(75deg, rgba(56.96,44.47,127.72,0.26) 75%, #D6D3EE 83%, rgba(174.48,162.84,202.33,0.94) 100%, #817CB2 100%);">
        </div>

        <div class="absolute left-[-5%] top-[10%] w-[35vw] h-[25vw] blur-[80px] opacity-70"
            style="background: linear-gradient(75deg, rgba(65.04,45.10,137.14,0.31) 75%, #D6D3EE 83%, rgba(174.48,162.84,202.33,0.94) 100%, #817CB2 100%);">
        </div>

        <div class="absolute right-[5%] top-[-5%] w-[15vw] h-[20vw] blur-[80px] opacity-60"
            style="background: linear-gradient(75deg, rgba(16.56,8.41,46.05,0.97) 33%, #D6D3EE 83%, rgba(174.48,162.84,202.33,0.94) 100%, #817CB2 100%);">
        </div>

        <div class="absolute left-[-5%] top-[-5%] w-[20vw] h-[15vw] blur-[80px] opacity-50"
            style="background: linear-gradient(75deg, rgba(16.56,8.41,46.05,0.39) 75%, #D6D3EE 83%, rgba(174.48,162.84,202.33,0.94) 100%, #817CB2 100%);">
        </div>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-[450px] min-w-[320px] px-5 flex flex-col items-center">

        <!-- Logo -->
        <div class="flex items-center justify-center mb-8">
            <div
                class="w-[60px] h-[60px] bg-[#434264] rounded-lg flex items-center justify-center text-white font-bold text-2xl">
                NX
            </div>
            <span class="ml-4 text-[36px] font-bold text-[#817CB2]">NEXUS</span>
        </div>

        <!-- Card -->
        <div class="w-full bg-[#434264] rounded-[19px] px-10 py-8 shadow-xl">

            <!-- Title -->
            <h2 class="text-center text-white text-[32px] font-bold mb-10">
                Login
            </h2>

            <!-- Alert -->
            <div id="alertMessage" class="hidden mb-6 px-5 py-3 rounded-md text-center text-sm font-semibold"></div>

            <!-- Form -->
            <form id="loginForm" action="<?= base_url('auth/process_login') ?>" method="POST" class="space-y-6">

                <?= csrf_field() ?>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-[#CCDDFDCC]">
                        Email Address
                    </label>
                    <input type="email" name="email" placeholder="Enter email" required class="w-full h-[54px] px-5 rounded-md
                       bg-[#CCDDFD1F]
                       border border-[#CCDDFD3D]
                       text-[#CCDDFDCC]
                       font-bold text-[17px]
                       focus:outline-none
                       focus:ring-2 focus:ring-[#CCDDFD33]">
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="text-sm font-bold text-[#CCDDFDCC]">
                            Password
                        </label>
                        <a href="<?= base_url('auth/forgot_password') ?>"
                            class="text-sm font-bold text-[#CCDDFDCC] hover:text-[#BA94ED]">
                            Forgot?
                        </a>
                    </div>

                    <input type="password" name="password" placeholder="Password" required class="w-full h-[54px] px-5 rounded-md
                       bg-[#CCDDFD1F]
                       border border-[#CCDDFD3D]
                       text-[#CCDDFDCC]
                       font-bold text-[17px]
                       focus:outline-none
                       focus:ring-2 focus:ring-[#CCDDFD33]">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center pt-2 cursor-pointer" onclick="toggleRemember()">
                    <div id="rememberCheckbox" class="w-[18px] h-[18px] mr-3
                       border-2 border-[#CCDDFD1F]
                       rounded
                       bg-[#CCDDFD1F]
                       flex items-center justify-center">
                    </div>
                    <input type="hidden" name="remember" id="remember" value="0">
                    <span class="text-white font-bold text-sm">
                        Remember Me
                    </span>
                </div>

                <!-- Button -->
                <div class="pt-3">
                    <button type="submit" class="w-full h-[45px]
                       bg-[#756EA4]
                       rounded-md
                       text-[#C1D1F3]
                       font-extrabold text-base
                       hover:bg-[#817CB2]
                       transition-all">
                       Login
                    </button>
                </div>

            </form>
        </div>

    </div>

    <!-- JS (tetap sama) -->
    <script>
        function toggleRemember() {
            const box = document.getElementById('rememberCheckbox');
            const input = document.getElementById('remember');

            box.classList.toggle('bg-[#756EA4]');
            box.classList.toggle('border-[#756EA4]');
            box.innerHTML = box.classList.contains('bg-[#756EA4]')
                ? '<div class="w-[10px] h-[6px] border-l-2 border-b-2 border-white rotate-[-45deg]"></div>'
                : '';

            input.value = box.classList.contains('bg-[#756EA4]') ? '1' : '0';
        }
    </script>

</body>

</html>