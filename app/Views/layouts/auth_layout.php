<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - <?= $this->renderSection('title') ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
    </style>
</head>
<body class="bg-light-bg min-h-screen flex justify-center items-center overflow-hidden relative font-roboto px-4">
    
    <!-- Background Effects -->
    <div class="absolute w-full h-full top-0 left-0 z-1">
        <div class="w-[40vw] h-[40vw] absolute -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.26)] via-[#D6D3EE] to-[#817CB2] blur-[60px] md:blur-80 opacity-80"></div>
        <div class="w-[35vw] h-[25vw] absolute -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.31)] via-[#D6D3EE] to-[#817CB2] blur-[60px] md:blur-80 opacity-70"></div>
    </div>
    
    <!-- Main Content -->
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

        <!-- Flash Messages -->
        <?= $this->renderSection('flashMessages') ?>

        <!-- Content Section -->
        <?= $this->renderSection('content') ?>
    </div>
    
</body>
</html>
