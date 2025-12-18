<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario - Ant System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php include __DIR__ . '/security_check.php'; ?>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .glass-dark {
            background: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .form-input-premium {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-input-premium:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(124, 58, 237, 0.1), 0 4px 6px -2px rgba(124, 58, 237, 0.05);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .btn-premium {
            background: linear-gradient(to right, #7c3aed, #4f46e5);
            transition: all 0.3s ease;
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(124, 58, 237, 0.5);
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-purple-50 to-blue-50 relative overflow-x-hidden flex flex-col">
    
    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 float-animation"></div>
        <div class="absolute top-1/2 -left-40 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 float-animation" style="animation-delay: -2s;"></div>
        <div class="absolute -bottom-40 right-1/3 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 float-animation" style="animation-delay: -4s;"></div>
    </div>

    <!-- Top Navigation (Sticky & Premium) -->
    <header class="sticky top-0 z-50 glass border-b border-white/50 shadow-sm transition-all duration-300" id="main-header">
        <div class="container mx-auto px-4 sm:px-6 py-3 flex flex-wrap justify-between items-center gap-4">
            <!-- Logo & Brand -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center shadow-lg text-white transform hover:rotate-12 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="hidden md:block">
                    <h1 class="font-bold text-gray-800 text-lg leading-tight">Sistema de Informes</h1>
                    <p class="text-[10px] text-gray-500 font-medium uppercase tracking-wider">Gestión de Escuelas</p>
                </div>
            </div>

            <!-- Navigation Controls -->
            <div class="flex items-center gap-2 sm:gap-4 flex-wrap">
                <!-- Dashboard Link -->
                <a href="../../dashboard.php" class="p-2 text-gray-400 hover:text-purple-600 transition-colors rounded-lg hover:bg-purple-50" title="Ir al Dashboard">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>

                <div class="h-6 w-px bg-gray-200"></div>

                <!-- Quick Nav: Start -->
                <a href="../non-professional/step1.php" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-purple-600 bg-white border border-gray-200 hover:border-purple-200 rounded-lg transition-all hover:shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                    Inicio
                </a>

                <!-- Quick Nav: Steps Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-2 px-3 py-1.5 text-sm font-bold text-white gradient-bg rounded-lg shadow hover:shadow-lg hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <span class="hidden sm:inline">Secciones</span>
                        <svg class="w-3 h-3 ml-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="absolute top-full right-0 mt-2 w-72 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-100 p-2 invisible opacity-0 group-hover:visible group-hover:opacity-100 transform group-hover:translate-y-0 translate-y-2 transition-all duration-200 z-50">
                        <div class="text-[10px] uppercase font-bold text-gray-400 px-3 py-2">Ir a Sección</div>
                        <div class="grid grid-cols-1 gap-1 max-h-[60vh] overflow-y-auto custom-scrollbar">
                            <?php 
                            // Detect Context (Professional vs Non-Professional)
                            $current_uri = $_SERVER['REQUEST_URI'];
                            $is_professional = strpos($current_uri, '/professional/') !== false;
                            
                            if ($is_professional) {
                                // Professional School Steps (Total 8)
                                $basePath = '../professional/';
                                $steps = [
                                    1 => "1. Información General",
                                    2 => "2. Infraestructura",
                                    3 => "3. Laboratorios",
                                    4 => "4. Áreas Complementarias",
                                    5 => "5. Equip. y Vehículos",
                                    6 => "6. Personal Admin.",
                                    7 => "7. Cons. Académico/Docentes",
                                    8 => "8. Cursos y Campañas"
                                ];
                            } else {
                                // Non-Professional School Steps (Total 13)
                                $basePath = '../non-professional/';
                                $steps = [
                                    1 => "1. Información General",
                                    2 => "2. Resoluciones",
                                    3 => "3. Infraestructura",
                                    4 => "4. Aulas",
                                    5 => "5. Equipos Computación",
                                    6 => "6. Plataforma",
                                    7 => "7. Pista de Conducción",
                                    8 => "8. Baterías Sanitarias",
                                    9 => "9. Vehículos",
                                    10 => "10. Equipamiento",
                                    11 => "11. Personal Admin.",
                                    12 => "12. Docentes/Inst.",
                                    13 => "13. Estudiantes"
                                ];
                            }

                            foreach($steps as $i => $title): ?>
                            <a href="<?php echo $basePath; ?>step<?php echo $i; ?>.php" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-white hover:bg-purple-500 rounded-lg transition-colors group/item">
                                <span class="bg-gray-100 text-gray-500 group-hover/item:text-purple-600 group-hover/item:bg-white text-[10px] font-bold px-1.5 py-0.5 rounded mr-2 min-w-[20px] text-center transition-colors"><?php echo $i; ?></span>
                                <?php echo substr($title, 3); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Nav: End -->
                <a href="<?php echo $is_professional ? '../professional/summary.php' : '../non-professional/step13.php'; ?>" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-purple-600 bg-white border border-gray-200 hover:border-purple-200 rounded-lg transition-all hover:shadow-sm">
                    Final
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Wrapper -->
    <main class="relative z-10 flex-grow py-12 px-4 sm:px-6">
