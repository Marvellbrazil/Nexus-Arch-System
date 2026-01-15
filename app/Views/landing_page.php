<?php
use Config\Services;

$session = Services::session();
$success = $session->getFlashdata('success');
$error = $session->getFlashdata('error');
$message = $session->getFlashdata('message');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - Ticketing System</title>

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
                        'text-light': 'rgba(255, 255, 255, 0.80)',
                        'border-light': 'rgba(255, 255, 255, 0.10)',
                        'dark-bg': '#3D3C5E',
                        'nav-bg': '#D3CBE0',
                        'card-bg': '#F0E9F9',
                        'footer-bg': '#C8BFDC',
                        'text-dark': '#302B48',
                        'text-muted': '#3E3B5D',
                    },
                    fontFamily: {
                        'roboto': ['Roboto', 'sans-serif'],
                        'mulish': ['Mulish', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif'],
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    blur: {
                        '100': '100px',
                    },
                    screens: {
                        'xs': '480px',
                        'sm': '640px',
                        'md': '768px',
                        'lg': '1024px',
                        'xl': '1280px',
                        '2xl': '1536px',
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&family=Mulish:wght@300;400;500;600;700&family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-slide-left {
            animation: slideInLeft 0.8s ease-out;
        }

        .animate-slide-right {
            animation: slideInRight 0.8s ease-out;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 8px 32px 0 rgba(31, 38, 135, 0.07),
                0 4px 16px 0 rgba(0, 0, 0, 0.05),
                inset 0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        .feature-card {
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(117, 110, 164, 0.15);
        }

        .gradient-text {
            background: linear-gradient(135deg, #756EA4, #BA94ED);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-primary {
            background: linear-gradient(135deg, #756EA4, #8A84C6);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #665C9E, #756EA4);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(117, 110, 164, 0.3);
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid #756EA4;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(117, 110, 164, 0.1);
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-light-bg min-h-screen font-roboto overflow-x-hidden">
    <!-- Background Effects -->
    <div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
    <div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
    <div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>

    <!-- Alert Messages -->
    <?php if ($success): ?>
        <div class="fixed top-4 right-4 p-4 rounded-lg z-[1000] max-w-xs animate-slide-in shadow-lg bg-green-500 text-white border-l-4 border-green-600">
            <i class="fas fa-check-circle mr-2"></i> <?= esc($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="fixed top-4 right-4 p-4 rounded-lg z-[1000] max-w-xs animate-slide-in shadow-lg bg-red-500 text-white border-l-4 border-red-600">
            <i class="fas fa-exclamation-circle mr-2"></i> <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="fixed top-4 right-4 p-4 rounded-lg z-[1000] max-w-xs animate-slide-in shadow-lg bg-blue-500 text-white border-l-4 border-blue-600">
            <i class="fas fa-info-circle mr-2"></i> <?= esc($message) ?>
        </div>
    <?php endif; ?>

    <!-- Navigation -->
    <nav class="relative z-50 py-6 px-4 md:px-8 lg:px-16">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 glass-effect rounded-xl flex items-center justify-center">
                    <span class="text-secondary font-bold text-xl">N</span>
                </div>
                <div class="text-primary text-xl md:text-2xl font-bold">NEXUS</div>
            </div>

            <!-- Login Button -->
            <a href="<?= base_url('login') ?>" 
               class="btn-primary text-white px-6 py-3 rounded-xl font-semibold text-sm md:text-base flex items-center gap-2 no-underline">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login</span>
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
<section class="relative z-10 pt-20 pb-28 px-4 md:px-8 lg:px-16 overflow-hidden">
    <div class="max-w-5xl mx-auto text-center">
        
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/50 border border-white/80 text-secondary text-sm font-semibold mb-8 animate-fade-in shadow-sm">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
            </span>
            Project-Based Ticketing System
        </div>

        <div class="animate-fade-in" style="animation-delay: 0.1s">
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold mb-8 text-text-dark leading-[1.1] tracking-tight">
                Kelola Kendala <br>
                Secara <span class="gradient-text">Terstruktur</span>
            </h1>
        </div>

        <div class="animate-fade-in" style="animation-delay: 0.2s">
            <p class="text-lg md:text-2xl text-text-muted mb-12 max-w-3xl mx-auto leading-relaxed">
                Jembatan komunikasi antara Customer dan Developer. Laporkan bug, pantau progres, dan selesaikan isu teknis tepat waktu berdasarkan segmentasi proyek Anda.
            </p>
        </div>

<div class="flex flex-col sm:flex-row gap-5 justify-center items-center animate-fade-in" style="animation-delay: 0.3s">
    <a href="<?= base_url('login') ?>" 
       class="btn-primary text-white px-10 py-5 rounded-2xl font-bold text-lg flex items-center justify-center gap-3 no-underline w-full sm:w-auto min-w-[200px] shadow-lg shadow-secondary/25">
        <i class="fas fa-rocket"></i>
        <span>Mulai Sekarang</span>
    </a>
    
    <a href="#features" 
       class="btn-secondary bg-white/40 backdrop-blur-md text-secondary px-10 py-5 rounded-2xl font-bold text-lg flex items-center justify-center gap-3 no-underline border border-white/50 w-full sm:w-auto min-w-[200px] hover:bg-white/60 transition-all">
        <i class="fas fa-list-ul"></i>
        <span>Lihat Fitur</span>
    </a>
</div>



    </div>
</section>

<section id="features" class="relative z-10 py-24 px-4 md:px-8 lg:px-16 bg-white/40">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 animate-fade-in">
            <span class="text-secondary font-bold tracking-widest uppercase text-sm mb-3 block">Fitur Unggulan</span>
            <h2 class="text-3xl md:text-5xl font-bold mb-4 text-text-dark">
                Kelola Dukungan dengan
                <span class="gradient-text">Lebih Cerdas</span>
            </h2>
            <p class="text-lg text-text-muted max-w-2xl mx-auto leading-relaxed">
                Platform terintegrasi yang dirancang untuk mempercepat resolusi masalah dan meningkatkan kepuasan pelanggan Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <div class="group relative feature-card glass-effect rounded-[2rem] p-10 bg-white/60 hover:bg-white/90 transition-all duration-500 overflow-hidden border border-white/40">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-secondary/5 rounded-full group-hover:scale-[3] transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="w-14 h-14 mb-8 flex items-center justify-center rounded-2xl bg-gradient-to-br from-secondary to-accent shadow-lg shadow-secondary/20 group-hover:rotate-6 transition-transform">
                        <i class="fas fa-ticket-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-text-dark group-hover:text-secondary transition-colors">Smart Ticketing</h3>
                    <p class="text-text-muted mb-6 leading-relaxed">
                        Manajemen tiket otomatis dengan prioritas cerdas. Tidak ada lagi permintaan yang terlewatkan.
                    </p>
                    <div class="pt-6 border-t border-secondary/10">
                        <div class="flex items-center gap-3 text-sm text-text-muted font-medium mb-3">
                            <i class="fas fa-check text-green-500 bg-green-50 p-1 rounded-full"></i> 
                            Prioritas Berbasis SLA
                        </div>
                        <div class="flex items-center gap-3 text-sm text-text-muted font-medium">
                            <i class="fas fa-check text-green-500 bg-green-50 p-1 rounded-full"></i> 
                            Lampiran Multi-Format
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative feature-card glass-effect rounded-[2rem] p-10 bg-white/60 hover:bg-white/90 transition-all duration-500 overflow-hidden border border-white/40">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-accent/5 rounded-full group-hover:scale-[3] transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="w-14 h-14 mb-8 flex items-center justify-center rounded-2xl bg-gradient-to-br from-accent to-purple-400 shadow-lg shadow-accent/20 group-hover:rotate-6 transition-transform">
                        <i class="fas fa-layer-group text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-text-dark group-hover:text-accent transition-colors">Isolated Projects</h3>
                    <p class="text-text-muted mb-6 leading-relaxed">
                        Keamanan data maksimal. Klien hanya melihat progres pada proyek yang relevan bagi mereka.
                    </p>
                    <div class="pt-6 border-t border-accent/10">
                        <div class="flex items-center gap-3 text-sm text-text-muted font-medium mb-3">
                            <i class="fas fa-shield-alt text-blue-500 bg-blue-50 p-1 rounded-full"></i> 
                            Role-Based Permission
                        </div>
                        <div class="flex items-center gap-3 text-sm text-text-muted font-medium">
                            <i class="fas fa-project-diagram text-blue-500 bg-blue-50 p-1 rounded-full"></i> 
                            Multi-Project Support
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative feature-card glass-effect rounded-[2rem] p-10 bg-white/60 hover:bg-white/90 transition-all duration-500 overflow-hidden border border-white/40">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-400/5 rounded-full group-hover:scale-[3] transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="w-14 h-14 mb-8 flex items-center justify-center rounded-2xl bg-gradient-to-br from-blue-400 to-cyan-400 shadow-lg shadow-blue-400/20 group-hover:rotate-6 transition-transform">
                        <i class="fas fa-comments text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-text-dark group-hover:text-blue-500 transition-colors">Instant Chat</h3>
                    <p class="text-text-muted mb-6 leading-relaxed">
                        Kolaborasi tanpa hambatan antara pengembang dan klien melalui sistem pesan real-time.
                    </p>
                    <div class="pt-6 border-t border-blue-400/10">
                        <div class="flex items-center gap-3 text-sm text-text-muted font-medium mb-3">
                            <i class="fas fa-at text-orange-500 bg-orange-50 p-1 rounded-full"></i> 
                            Smart @Mentions
                        </div>
                        <div class="flex items-center gap-3 text-sm text-text-muted font-medium">
                            <i class="fas fa-history text-orange-500 bg-orange-50 p-1 rounded-full"></i> 
                            Thread History
                        </div>
                    </div>
                </div>
            </div>

            </div>
    </div>
</section>

<section class="relative z-10 py-24 px-4 md:px-8 lg:px-16 overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-20 animate-fade-in">
            <span class="text-accent font-bold tracking-widest uppercase text-sm mb-3 block">Alur Kerja</span>
            <h2 class="text-3xl md:text-5xl font-bold mb-4 text-text-dark">
                Proses Resolusi <span class="gradient-text">Tanpa Hambatan</span>
            </h2>
            <p class="text-lg text-text-muted max-w-2xl mx-auto">
                Kami menyederhanakan komunikasi rumit menjadi 4 langkah mudah untuk memastikan masalah Anda selesai tepat waktu.
            </p>
        </div>

        <div class="relative">
            <div class="hidden lg:block absolute top-24 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-secondary/30 to-transparent"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
                
                <div class="group relative text-center animate-fade-in">
                    <div class="relative z-10 inline-flex items-center justify-center mb-8">
                        <div class="absolute inset-0 bg-secondary/20 rounded-full blur-xl group-hover:bg-secondary/40 transition-all duration-500"></div>
                        <div class="relative w-20 h-20 bg-white border-4 border-secondary rounded-full flex items-center justify-center shadow-xl transform group-hover:-translate-y-2 transition-transform duration-300">
                            <i class="fas fa-edit text-secondary text-2xl"></i>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-secondary text-white rounded-full flex items-center justify-center text-sm font-bold border-4 border-light-bg">1</div>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-text-dark">Buat Tiket</h3>
                    <p class="text-text-muted text-sm md:text-base px-4">
                        Customer melaporkan isu melalui portal dengan detail proyek yang spesifik.
                    </p>
                </div>

                <div class="group relative text-center animate-fade-in" style="animation-delay: 0.2s">
                    <div class="relative z-10 inline-flex items-center justify-center mb-8">
                        <div class="absolute inset-0 bg-accent/20 rounded-full blur-xl group-hover:bg-accent/40 transition-all duration-500"></div>
                        <div class="relative w-20 h-20 bg-white border-4 border-accent rounded-full flex items-center justify-center shadow-xl transform group-hover:-translate-y-2 transition-transform duration-300">
                            <i class="fas fa-search text-accent text-2xl"></i>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold border-4 border-light-bg">2</div>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-text-dark">Validasi Support</h3>
                    <p class="text-text-muted text-sm md:text-base px-4">
                        Tim Support meninjau tingkat urgensi dan meneruskan tiket ke tim ahli.
                    </p>
                </div>

                <div class="group relative text-center animate-fade-in" style="animation-delay: 0.4s">
                    <div class="relative z-10 inline-flex items-center justify-center mb-8">
                        <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-xl group-hover:bg-blue-500/40 transition-all duration-500"></div>
                        <div class="relative w-20 h-20 bg-white border-4 border-blue-500 rounded-full flex items-center justify-center shadow-xl transform group-hover:-translate-y-2 transition-transform duration-300">
                            <i class="fas fa-tools text-blue-500 text-2xl"></i>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold border-4 border-light-bg">3</div>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-text-dark">Pengerjaan</h3>
                    <p class="text-text-muted text-sm md:text-base px-4">
                        Developer memperbaiki masalah dan memberikan update status secara berkala.
                    </p>
                </div>

                <div class="group relative text-center animate-fade-in" style="animation-delay: 0.6s">
                    <div class="relative z-10 inline-flex items-center justify-center mb-8">
                        <div class="absolute inset-0 bg-green-500/20 rounded-full blur-xl group-hover:bg-green-500/40 transition-all duration-500"></div>
                        <div class="relative w-20 h-20 bg-white border-4 border-green-500 rounded-full flex items-center justify-center shadow-xl transform group-hover:-translate-y-2 transition-transform duration-300">
                            <i class="fas fa-check-double text-green-500 text-2xl"></i>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold border-4 border-light-bg">4</div>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-text-dark">Selesai</h3>
                    <p class="text-text-muted text-sm md:text-base px-4">
                        Solusi dikirimkan ke Customer dan tiket ditutup setelah dikonfirmasi.
                    </p>
                </div>

            </div>
        </div>
    </div>
</section>

<footer class="relative z-10 pt-20 pb-10 px-4 md:px-8 lg:px-16 bg-white/30 backdrop-blur-md border-t border-white/40">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            
            <div class="space-y-4">
                <a href="#" class="flex items-center gap-3 no-underline">
                    <div class="w-8 h-8 bg-gradient-to-br from-secondary to-accent rounded-lg flex items-center justify-center shadow-md">
                        <span class="text-white font-bold text-sm">N</span>
                    </div>
                    <span class="text-primary font-bold text-xl tracking-tight">NEXUS</span>
                </a>
                <p class="text-text-muted text-sm leading-relaxed max-w-xs">
                    Platform manajemen tiket profesional untuk pelaporan kendala teknis dan monitoring progres penyelesaian proyek secara real-time.
                </p>
            </div>

            <div>
                <h4 class="text-text-dark font-bold mb-4">Akses Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#features" class="text-text-muted hover:text-secondary no-underline transition-colors">Fitur Utama</a></li>
                    <li><a href="#workflow" class="text-text-muted hover:text-secondary no-underline transition-colors">Alur Kerja</a></li>
                    <li><a href="<?= base_url('login') ?>" class="text-text-muted hover:text-secondary no-underline transition-colors">Portal Customer</a></li>
                </ul>
            </div>


        </div>

        <div class="pt-8 border-t border-secondary/10 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-text-muted font-medium uppercase tracking-widest">
            <p>&copy; 2026 NEXUS TICKETING SYSTEM. ALL RIGHTS RESERVED.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-secondary no-underline">Privacy Policy</a>
                <a href="#" class="hover:text-secondary no-underline">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>    

    <script>
        // Auto-hide alerts
        const alerts = document.querySelectorAll('.fixed.top-4');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll to top function
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('glass-effect');
                nav.classList.add('py-4');
            } else {
                nav.classList.remove('glass-effect');
                nav.classList.remove('py-4');
            }
        });

        // Initialize animations on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all animate-fade-in elements
        document.querySelectorAll('.animate-fade-in, .animate-slide-left, .animate-slide-right').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>