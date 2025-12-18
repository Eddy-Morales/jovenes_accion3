<?php
session_start();
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Load progress and user data from DB
require_once 'includes/db_connection.php';
try {
    // Get user profile data
    $stmt = $pdo->prepare("SELECT username, full_name, job_title, gender FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get progress data
    $stmt = $pdo->prepare("SELECT form_data, last_step FROM user_progress WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $lastStep = 0;
    $schoolType = null;
    if ($row && !empty($row['form_data'])) {
        $savedData = json_decode($row['form_data'] ?? '', true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($savedData)) {
            if (!isset($_SESSION['form_data']) || empty($_SESSION['form_data'])) {
                 $_SESSION['form_data'] = $savedData;
            } else {
                 $_SESSION['form_data'] = array_merge($_SESSION['form_data'], $savedData);
            }
            $schoolType = $savedData['school_type'] ?? null;
        }
        $lastStep = $row['last_step'] ?? 0;
    }
    
    // Count reports by type and determine total steps expectation
    $professionalCount = 0;
    $nonProfessionalCount = 0;
    
    // Default total steps for progress calculation
    $totalSteps = 13; 

    if ($schoolType === 'professional') {
        $totalSteps = 8;
        if ($lastStep >= 8) {
             $professionalCount = 1;
        }
    } elseif ($schoolType === 'non_professional') {
        $totalSteps = 13;
        if ($lastStep >= 13) {
            $nonProfessionalCount = 1;
        }
    }
    
    // Check for edit requests
    $editStatus = null;
    $reqStmt = $pdo->prepare("SELECT status FROM edit_requests WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $reqStmt->execute([$_SESSION['user_id']]);
    $editStatus = $reqStmt->fetchColumn();
    // Default to 'none' if false
    if (!$editStatus) $editStatus = 'none';
    
    // Fetch Archived Reports
    $archivedReports = [];
    try {
        $arcStmt = $pdo->prepare("SELECT * FROM reports_archive WHERE user_id = ? ORDER BY created_at DESC");
        $arcStmt->execute([$_SESSION['user_id']]);
        $archivedReports = $arcStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) { /* ignore */ }

} catch (PDOException $e) {
    error_log("Error loading data: " . $e->getMessage());
    $lastStep = 0;
    $user = ['username' => $_SESSION['username'], 'full_name' => '', 'job_title' => '', 'gender' => ''];
}

// Fallbacks for user data
$fullName = $user['full_name'] ?? $_SESSION['username'];
$jobTitle = $user['job_title'] ?? 'Usuario';
$gender = $user['gender'] ?? 'male';
$username = $user['username'] ?? $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - Ant System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .card-hover:hover {
            transform: translateY(-8px) scale(1.01);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-purple-50 to-blue-50 relative overflow-x-hidden">
    
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 float-animation"></div>
        <div class="absolute top-1/2 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 float-animation" style="animation-delay: -2s;"></div>
        <div class="absolute -bottom-40 right-1/3 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 float-animation" style="animation-delay: -4s;"></div>
    </div>

    <?php
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        $type = $msg['type'];
        $text = $msg['text'];
        $title = $msg['title'] ?? ucfirst($type);
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '" . addslashes($title) . "',
                    text: '" . addslashes($text) . "',
                    icon: '$type',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#7c3aed',
                    timer: 4000,
                    timerProgressBar: true
                });
            });
        </script>";
        unset($_SESSION['flash_message']);
    }
    ?>

    <!-- Header -->
    <header class="relative z-10 glass border-b border-white/30 shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 gradient-bg rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Panel Principal</h1>
                        <p class="text-sm text-gray-500">Sistema de Gestión de Trámites</p>
                    </div>
                </div>
                
                <button id="logoutBtn" class="flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-full font-medium shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="hidden sm:inline">Cerrar Sesión</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 container mx-auto px-6 py-10">
        
        <!-- User Profile Card -->
        <div class="glass rounded-3xl p-8 border border-white/50 shadow-xl mb-10 max-w-2xl mx-auto">
            <div class="flex items-center gap-6">
                <!-- Gender-based Avatar -->
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center shadow-lg <?php echo $gender === 'female' ? 'bg-gradient-to-br from-pink-400 to-rose-500' : 'bg-gradient-to-br from-blue-400 to-indigo-500'; ?>">
                    <?php if ($gender === 'female'): ?>
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C9.243 2 7 4.243 7 7s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5zm0 8c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3-1.346 3-3 3zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4zm6 5H6v-.99c.2-.72 3.3-2.01 6-2.01s5.8 1.29 6 2v1z"/>
                        </svg>
                    <?php else: ?>
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C9.243 2 7 4.243 7 7s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5zm0 8c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3-1.346 3-3 3zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4zm6 5H6v-.99c.2-.72 3.3-2.01 6-2.01s5.8 1.29 6 2v1z"/>
                        </svg>
                    <?php endif; ?>
                </div>
                
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($fullName ?: $username); ?></h2>
                    <p class="text-purple-600 font-medium"><?php echo htmlspecialchars($jobTitle); ?></p>
                    <p class="text-gray-500 text-sm mt-1">@<?php echo htmlspecialchars($username); ?></p>
                </div>
                
                <div class="text-right">
                    <div class="inline-flex items-center gap-2 bg-purple-100 text-purple-700 px-4 py-2 rounded-full text-sm font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <?php if ($lastStep > 0): ?>
                            Paso <?php echo $lastStep; ?>/13
                        <?php else: ?>
                            Sin progreso
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex justify-center mb-8">
            <div class="bg-white/50 backdrop-blur-sm p-1 rounded-2xl inline-flex shadow-sm border border-white/60">
                <button onclick="switchTab('home')" id="btn-home" class="px-6 py-2 rounded-xl text-sm font-bold transition-all shadow-sm bg-white text-purple-600">
                    Inicio
                </button>
                <button onclick="switchTab('reports')" id="btn-reports" class="px-6 py-2 rounded-xl text-sm font-medium text-gray-500 hover:text-purple-600 transition-all">
                    Mis Informes
                    <?php if (!empty($archivedReports)): ?>
                        <span class="ml-2 bg-green-100 text-green-600 text-[10px] px-1.5 py-0.5 rounded-full"><?php echo count($archivedReports); ?></span>
                    <?php endif; ?>
                </button>
            </div>
        </div>

        <!-- TAB: HOME (Main Cards) -->
        <div id="tab-home" class="transition-all duration-300">
            <!-- Section Title -->
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">
                    Seleccione el <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600">Tipo de Informe</span>
                </h2>
                <p class="text-gray-500 text-lg">Elija una categoría para comenzar o continuar con su formulario.</p>
            </div>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
                
                <!-- Professional Schools Card -->
                <div class="glass rounded-3xl overflow-hidden border border-white/50 shadow-xl card-hover">
                    <div class="h-44 bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full"></div>
                        <div class="absolute -top-10 -left-10 w-32 h-32 bg-white/10 rounded-full"></div>
                        <div class="relative z-10 text-center">
                            <div class="w-20 h-20 mx-auto bg-white/20 rounded-2xl flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                </svg>
                            </div>
                            <span class="text-white/80 text-sm font-medium uppercase tracking-wider">Categoría A</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Escuelas Profesionales</h3>
                        <p class="text-gray-500 text-sm mb-4">Formularios para escuelas de conducción profesional (Licencias C, D, E).</p>
                        
                        <!-- Stats -->
                        <div class="flex items-center gap-4 mb-4 p-3 bg-blue-50 rounded-xl">
                            <div class="text-center flex-1">
                                <div class="text-2xl font-bold text-blue-600"><?php echo $professionalCount; ?></div>
                                <div class="text-xs text-gray-500">Informes Creados</div>
                            </div>
                            <div class="w-px h-10 bg-blue-200"></div>
                            <div class="text-center flex-1">
                                <div class="text-2xl font-bold text-blue-600">8</div>
                                <div class="text-xs text-gray-500">Pasos Totales</div>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <?php 
                            // Strict Logic for Professional Card
                            $currentSchoolType = $schoolType ?? '';
                            $currentLastStep = $lastStep ?? 0;
                            
                            // Condition: Is this specific report type currently active?
                            // Modified to include step 8 (restored/completed active state) so they can edit.
                            $isInProgressProfessional = ($currentSchoolType === 'professional' && $currentLastStep > 0 && $currentLastStep <= 8);
                            
                            if ($isInProgressProfessional): 
                            ?>
                                <!-- Active In-Progress State: Continue + New -->
                                <button onclick="handleContinue('professional', 'views/professional/step1.php')" class="flex-1 text-center py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all shadow-md hover:shadow-lg">
                                    Continuar
                                </button>
                                <button type="button" onclick="createNewReport('professional')" class="py-3 px-4 bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 rounded-xl font-medium transition-all">
                                    + Nuevo
                                </button>
                            <?php else: ?>
                                <!-- Not Started, Completed, or Other Type Active: Only Allow New/Start -->
                                <button type="button" onclick="createNewReport('professional')" class="w-full py-3 px-4 bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 rounded-xl font-medium transition-all flex items-center justify-center gap-2 group">
                                    <span class="group-hover:scale-110 transition-transform">+</span> Crear Nuevo Informe
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Non-Professional Schools Card -->
                <div class="glass rounded-3xl overflow-hidden border border-white/50 shadow-xl card-hover">
                    <div class="h-44 bg-gradient-to-br from-emerald-500 to-green-700 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full"></div>
                        <div class="absolute -top-10 -left-10 w-32 h-32 bg-white/10 rounded-full"></div>
                        <div class="relative z-10 text-center">
                            <div class="w-20 h-20 mx-auto bg-white/20 rounded-2xl flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <span class="text-white/80 text-sm font-medium uppercase tracking-wider">Categoría B</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Escuelas No Profesionales</h3>
                        <p class="text-gray-500 text-sm mb-4">Formularios para escuelas de conducción no profesional (Licencias A, B).</p>
                        
                        <!-- Stats -->
                        <div class="flex items-center gap-4 mb-4 p-3 bg-green-50 rounded-xl">
                            <div class="text-center flex-1">
                                <div class="text-2xl font-bold text-green-600"><?php echo $nonProfessionalCount; ?></div>
                                <div class="text-xs text-gray-500">Informes Creados</div>
                            </div>
                            <div class="w-px h-10 bg-green-200"></div>
                            <div class="text-center flex-1">
                                <div class="text-2xl font-bold text-green-600">13</div>
                                <div class="text-xs text-gray-500">Pasos Totales</div>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <?php 
                            // Strict Logic for Non-Professional Card
                            // Modified to include step 13
                            $isInProgressNonProfessional = ($schoolType === 'non_professional' && $lastStep > 0 && $lastStep <= 13);
                            
                            if ($isInProgressNonProfessional): 
                            ?>
                                <!-- Active In-Progress State: Continue + New -->
                                <button onclick="handleContinue('non_professional', 'views/non-professional/step1.php')" class="flex-1 text-center py-3 px-4 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-all shadow-md hover:shadow-lg">
                                    Continuar
                                </button>
                                <button type="button" onclick="createNewReport('non_professional')" class="py-3 px-4 bg-white border-2 border-green-600 text-green-600 hover:bg-green-50 rounded-xl font-medium transition-all">
                                    + Nuevo
                                </button>
                            <?php else: ?>
                                <!-- Not Started, Completed, or Other Type Active: Only Allow New/Start -->
                                <button type="button" onclick="createNewReport('non_professional')" class="w-full py-3 px-4 bg-white border-2 border-green-600 text-green-600 hover:bg-green-50 rounded-xl font-medium transition-all flex items-center justify-center gap-2 group">
                                    <span class="group-hover:scale-110 transition-transform">+</span> Crear Nuevo Informe
                                </button>
                            <?php endif; ?>
                        </div>
                        <?php 
                        // Check for completion message
                        $isCompletedNonProfessional = ($schoolType === 'non_professional' && $lastStep >= 13);
                        if ($isCompletedNonProfessional): 
                        ?>
                            <p class="text-xs text-center text-gray-400 mt-3">
                                <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1"></span>
                                Tienes un informe completado en "Mis Informes".
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: REPORTS (Completed) -->
        <div id="tab-reports" class="hidden transition-all duration-300">
            <!-- Completed Reports Section -->
            <!-- Completed Reports Section -->
            <?php if (!empty($archivedReports)): ?>
            <div class="mb-12 max-w-5xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Mis Informes Completados</h2>
                    <p class="text-gray-500">Historial de informes finalizados y su estado actual.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php 
                    // 1. RENDER ACTIVE REPORT (En Edición)
                    if (!empty($schoolType) && $lastStep > 0):
                        $activeTitle = $savedData['step1']['escuela_nombre'] ?? 'Borrador sin título';
                        $activeDate = date('d/m/Y'); // Or fetch updated_at if available
                        $activeTypeLabel = ($schoolType === 'professional') ? 'Escuela Profesional' : 'Escuela No Profesional';
                        $editUrl = ($schoolType === 'professional') ? 'views/professional/step1.php' : 'views/non-professional/step1.php';
                    ?>
                    <!-- Active Report Card -->
                    <div class="bg-white rounded-3xl p-6 shadow-xl border-2 border-purple-100 flex flex-col relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300 ring-4 ring-purple-50">
                        <div class="absolute top-0 right-0 bg-purple-600 text-white text-[10px] uppercase font-bold px-3 py-1 rounded-bl-xl shadow-sm">
                            En Edición
                        </div>

                        <div class="flex items-start justify-between mb-4 mt-2">
                            <!-- Icon Container (Purple) -->
                            <div class="w-16 h-16 rounded-2xl border-2 border-purple-500 flex items-center justify-center bg-purple-50 shadow-sm">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <h4 class="font-bold text-gray-800 text-lg mb-1 line-clamp-2"><?php echo htmlspecialchars($activeTitle); ?></h4>
                        <p class="text-xs text-purple-500 font-medium mb-6"><?php echo $activeTypeLabel; ?> &bull; Paso <?php echo $lastStep; ?></p>
                        
                        <div class="mt-auto">
                             <!-- Edit Button -->
                            <a href="<?php echo $editUrl; ?>" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold transition-colors flex items-center justify-center gap-2 shadow-lg shadow-purple-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                Editar Informe
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php foreach ($archivedReports as $report): 
                        $repData = json_decode($report['form_data'] ?? '', true);
                        $repType = $report['school_type'];
                        $repDate = date('d/m/Y', strtotime($report['created_at']));
                        $repSchool = $report['school_name'] ?? ($repType === 'professional' ? 'Escuela Profesional' : 'Escuela No Profesional');
                        $downloadUrl = $repType === 'professional' 
                            ? 'actions/generate_word_professional.php?archive_id=' . $report['id']
                            : 'actions/generate_word.php?archive_id=' . $report['id'];
                    ?>
                    <!-- Report Card -->
                    <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-100 flex flex-col relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                        <div class="flex items-start justify-between mb-4">
                            <!-- Icon Container (Green Border) -->
                            <div class="w-16 h-16 rounded-2xl border-2 border-green-500 flex items-center justify-center bg-white shadow-sm group-hover:shadow-md transition-shadow">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            
                            <!-- Static 'Blocked' Badge (since it's archive) -->
                             <span class="bg-gray-100 text-gray-500 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Archivo
                            </span>
                        </div>
                        
                        <h4 class="font-bold text-gray-800 text-lg mb-1 line-clamp-2"><?php echo htmlspecialchars($repSchool); ?></h4>
                        <p class="text-xs text-gray-400 mb-6">Completado el <?php echo $repDate; ?></p>
                        
                        <div class="mt-auto space-y-2">
                             <!-- Download Word -->
                            <a href="<?php echo $downloadUrl; ?>" class="w-full py-2.5 bg-green-50 hover:bg-green-100 text-green-700 rounded-xl font-medium transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Descargar Word
                            </a>
                            
                            <!-- Request Edit (Archive) -->
                            <button onclick="confirmRequestEdit(<?php echo $report['id']; ?>)" class="w-full py-2.5 border-2 border-gray-200 text-gray-400 hover:border-gray-400 hover:text-gray-600 rounded-xl font-medium transition-colors flex items-center justify-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Solicitar Edición
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-600">No hay informes completados</h3>
                    <p class="text-gray-400">Tus informes finalizados aparecerán aquí.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Progress Summary (Always Visible if progress > 0) -->
        <?php if ($lastStep > 0): ?>
        <div class="mt-10 glass rounded-2xl p-6 border border-white/50 shadow-lg max-w-3xl mx-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">Progreso del Informe Actual</h3>
                <span class="text-sm text-purple-600 font-medium"><?php echo ucfirst(str_replace('_', ' ', $schoolType ?? 'N/A')); ?></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-purple-500 to-blue-500 rounded-full transition-all duration-500" style="width: <?php echo round($lastStep / $totalSteps * 100); ?>%"></div>
            </div>
            <div class="flex justify-between mt-2 text-sm text-gray-500">
                <span>Paso <?php echo $lastStep; ?> de <?php echo $totalSteps; ?></span>
                <span><?php echo round($lastStep / $totalSteps * 100); ?>% completado</span>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 text-center py-8 text-gray-400 text-sm">
        <p>&copy; <?php echo date('Y'); ?> Ant Project. Todos los derechos reservados.</p>
    </footer>

    <!-- Hidden form for new report -->
    <form id="newReportForm" action="actions/new_report.php" method="POST" class="hidden">
        <input type="hidden" name="school_type" id="newReportType">
    </form>
    
    <!-- Hidden form for edit request -->
    <form id="requestEditForm" action="actions/request_edit.php" method="POST" class="hidden">
        <input type="hidden" name="archive_id" id="requestEditArchiveId">
    </form>

    <script>
        // ... (existing code)

        function confirmRequestEdit(archiveId = null) {
            Swal.fire({
                title: '¿Solicitar edición?',
                text: 'El informe está bloqueado porque fue completado. ¿Deseas solicitar al administrador permiso para editarlo?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#7c3aed',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, solicitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Set archive ID if provided
                    if (archiveId) {
                        document.getElementById('requestEditArchiveId').value = archiveId;
                    } else {
                        document.getElementById('requestEditArchiveId').value = '';
                    }
                    // Use the specific hidden form ID we created
                    document.getElementById('requestEditForm').submit();
                }
            });
        }

        // Tab Switching Logic
        function switchTab(tabName) {
            // Hide all tabs
            document.getElementById('tab-home').classList.add('hidden');
            document.getElementById('tab-reports').classList.add('hidden');
            
            // Remove active styles from buttons
            const btnHome = document.getElementById('btn-home');
            const btnReports = document.getElementById('btn-reports');
            
            btnHome.classList.remove('bg-white', 'text-purple-600', 'shadow-sm', 'font-bold');
            btnHome.classList.add('text-gray-500', 'font-medium');
            
            btnReports.classList.remove('bg-white', 'text-purple-600', 'shadow-sm', 'font-bold');
            btnReports.classList.add('text-gray-500', 'font-medium');
            
            // Show selected tab
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            
            // Add active styles to selected button
            const selectedBtn = document.getElementById('btn-' + tabName);
            selectedBtn.classList.remove('text-gray-500', 'font-medium');
            selectedBtn.classList.add('bg-white', 'text-purple-600', 'shadow-sm', 'font-bold');
            
            // Optional: Store preference
            // localStorage.setItem('activeTab', tabName);
        }

        // Initialize Tab based on URL param
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab === 'reports') {
                switchTab('reports');
            } else {
                switchTab('home');
            }
        });

        // Logout confirmation
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Estás seguro de que deseas cerrar tu sesión?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7c3aed',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        });

        // Create new report function
        function createNewReport(type) {
            console.log('Creating new report for:', type);
            
            const proceed = () => {
                // Create a form dynamically to ensure clean submission
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'actions/new_report.php';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'school_type';
                input.value = type;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            };

            // Calculate if current report is completed
            // Professional: >= 8, Non-Professional: >= 13
            // If completed, we DO NOT warn about "losing progress" because it's already done.
            let isCompleted = false;
            if (currentSchoolType === 'professional' && currentLastStep >= 8) isCompleted = true;
            if (currentSchoolType === 'non_professional' && currentLastStep >= 13) isCompleted = true;

            // Warning ONLY if progress exists AND it is NOT completed
            if (currentLastStep > 0 && !isCompleted) {
                 Swal.fire({
                    title: '¿Iniciar nuevo informe?',
                    text: 'Actualmente tienes un informe en progreso. Si inicias uno nuevo, SE PERDERÁ todo el progreso actual.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, borrar y crear nuevo',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        proceed();
                    }
                });
            } else {
                // If it's 0 (new) or Completed (done), proceed directly
                proceed();
            }
        }

        // Handle Continue Button
        const currentSchoolType = "<?php echo $schoolType ?? ''; ?>";
        const currentLastStep = <?php echo $lastStep; ?>;

        function handleContinue(type, url) {
            // Determine colors based on type
            const confirmColor = type === 'professional' ? '#2563EB' : '#10b981'; // Blue vs Green

            // If user has a project of this type, let them continue/redirect
            if (currentSchoolType === type && currentLastStep > 0) {
                window.location.href = url;
            } else if (currentSchoolType && currentSchoolType !== type && currentLastStep > 0) {
                // User has a DIFFERENT type of project active
                Swal.fire({
                    title: 'Proyecto en curso',
                    text: 'Ya tienes un proyecto de otro tipo en curso. Debes terminarlo o crear uno nuevo (lo cual borrará el actual).',
                    icon: 'info',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#7c3aed'
                });
            } else {
                // No project found (or lastStep is 0)
                Swal.fire({
                    title: 'No hay proyectos',
                    text: 'No tienes proyectos creados en esta categoría. ¿Deseas crear uno nuevo?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, crear nuevo',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        createNewReport(type);
                    }
                });
            }
        }
    </script>
</body>
</html>
