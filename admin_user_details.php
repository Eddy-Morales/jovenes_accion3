<?php
session_start();
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header("Location: index.php");
    exit();
}
require_once 'includes/db_connection.php';

$userId = $_GET['id'] ?? 0;
// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Usuario no encontrado.");
}

// Fetch user progress (Project)
$stmt2 = $pdo->prepare("SELECT * FROM user_progress WHERE user_id = ?");
$stmt2->execute([$userId]);
$project = $stmt2->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Usuario - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 font-sans antialiased">
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
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#2563EB'
                });
            });
        </script>";
        unset($_SESSION['flash_message']);
    }
    ?>
<div class="flex h-screen overflow-hidden">
    
    <!-- SIDEBAR OVERLAY -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300 md:hidden"></div>
    <!-- SIDEBAR -->
    <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 w-64 bg-white border-r border-gray-200 flex flex-col md:relative md:translate-x-0 md:flex shadow-2xl md:shadow-none font-inter">
        <div class="h-20 flex items-center justify-between px-8 border-b border-gray-100">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 font-extrabold text-2xl tracking-tight">Ant Admin</span>
             <!-- Close button for mobile -->
            <button id="closeSidebar" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none bg-gray-50 p-1 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <nav class="flex-1 py-6 space-y-2 px-3">
            <a href="admin_dashboard.php" class="flex items-center px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-all duration-200 group">
                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="admin_users.php" class="flex items-center px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-all duration-200 group">
                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-medium">Usuarios</span>
            </a>
            
            <div class="uppercase text-xs font-bold text-gray-400 px-4 mt-8 mb-2 tracking-wider">GESTIÓN</div>
            
            <a href="admin_reports.php" class="flex items-center px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-all duration-200 group">
                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-medium">Todos los Informes</span>
            </a>
        </nav>
        
        <div class="p-4 border-t border-gray-100">
            <a href="logout.php" id="logoutBtn" class="flex items-center justify-center w-full px-4 py-2.5 text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 rounded-xl transition-all duration-200 text-sm font-bold shadow-sm hover:shadow">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Cerrar Sesión
            </a>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50/50">
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-200 flex items-center justify-between px-4 md:px-8 sticky top-0 z-30 transition-all duration-300 shadow-sm">
            <div class="flex items-center gap-4">
                 <!-- Hamburger Button -->
                <button id="sidebarToggle" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none bg-gray-100 p-2 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <a href="admin_users.php" class="text-gray-400 hover:text-gray-600 md:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-800 to-gray-600">Detalles de Usuario</div>
            </div>
            
            <div class="flex items-center gap-6">
                <!-- Edit Button -->
                <button onclick="openEditModal()" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center gap-1 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded transition-colors mr-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar Usuario
                </button>

                <!-- Delete Button Form -->
                <form id="deleteForm" action="actions/delete_user.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                    <button type="button" onclick="confirmDelete()" class="text-red-500 hover:text-red-700 font-medium text-sm flex items-center gap-1 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Eliminar Usuario
                    </button>
                </form>

                <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                     <div class="text-right hidden md:block">
                        <div class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                        <div class="text-xs text-gray-500 font-medium">Administrador</div>
                    </div>
                     <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 p-0.5 shadow-md">
                        <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-blue-600 font-bold text-sm">
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <script>
        // Sidebar Toggle Logic
        const sidebar = document.getElementById('adminSidebar');
        const openBtn = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('closeSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if(sidebar && openBtn && closeBtn && overlay) {
            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            }

            openBtn.addEventListener('click', toggleSidebar);
            closeBtn.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);
        }
        
        // Logout Confirmation Logic
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtn = document.getElementById('logoutBtn');
            if(logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Cerrar sesión?',
                        text: '¿Estás seguro de que deseas salir del sistema?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, cerrar sesión',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = this.href;
                        }
                    });
                });
            }
        });

        function confirmDelete() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará al usuario y todos sus datos permanentemente. ¡No podrás revertirlo!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            })
        }

        function confirmDeleteReport(formElement) {
            Swal.fire({
                title: '¿Eliminar informe?',
                text: "Esta acción eliminará el informe seleccionado. Si es un informe activo, se reiniciará el progreso del usuario.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    formElement.submit();
                }
            })
        }
        </script>

        <div class="flex-1 overflow-y-auto p-8">
            
            <!-- User Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8 flex items-center gap-6">
                <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold">
                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($user['username']); ?></h2>
                    <p class="text-gray-500">Miembro desde <?php echo date('d F, Y', strtotime($user['created_at'])); ?></p>
                    <div class="mt-2 text-sm text-gray-400">ID Usuario: #<?php echo $user['id']; ?></div>
                </div>
            </div>

            <!-- Projects Grid -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Proyectos Creados</h3>
                
                <?php 
                // Combine Active and Archived reports
                $allReports = [];

                // 1. Add Active Project (if valid)
                if ($project && !empty($project['form_data'])) {
                    $activeData = json_decode($project['form_data'], true);
                    if (is_array($activeData)) {
                        $allReports[] = [
                            'type' => 'active',
                            'id' => $project['user_id'], // Uses user_id for active download
                            'school_type' => $activeData['step1']['school_type'] ?? 'non_professional',
                            'school_name' => $activeData['step1']['escuela_nombre'] ?? 'Escuela de Conducción',
                            'updated_at' => $project['updated_at'],
                            'step' => $project['last_step']
                        ];
                    }
                }

                // 2. Fetch Archived Reports
                try {
                    $arcStmt = $pdo->prepare("SELECT * FROM reports_archive WHERE user_id = ? ORDER BY created_at DESC");
                    $arcStmt->execute([$userId]);
                    $archives = $arcStmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($archives as $arc) {
                        $allReports[] = [
                            'type' => 'archive',
                            'id' => $arc['id'], // Uses archive_id
                            'school_type' => $arc['school_type'],
                            'school_name' => $arc['school_name'] ?? 'Informe Archivado',
                            'updated_at' => $arc['created_at'],
                            'step' => ($arc['school_type'] == 'professional' ? 8 : 13) // Completed
                        ];
                    }
                } catch (Exception $e) {}
                ?>

                <?php if (count($allReports) > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($allReports as $report): 
                            // Determine styles based on type
                            $isProfessional = ($report['school_type'] === 'professional');
                            $typeLabel = $isProfessional ? 'Escuela Profesional' : 'Escuela No Profesional';
                            
                            if ($isProfessional) {
                                $iconColor = 'text-blue-500';
                                $bgColor = 'bg-blue-50';
                                $iconSvg = '<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>'; 
                            } else {
                                $iconColor = 'text-indigo-500';
                                $bgColor = 'bg-indigo-50';
                                $iconSvg = '<svg class="w-24 h-24 transform -rotate-12 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>';
                            }

                            // Download URL
                            $baseUrl = $isProfessional ? 'actions/generate_word_professional.php' : 'actions/generate_word.php';
                            if ($report['type'] === 'active') {
                                $downloadUrl = $baseUrl . "?admin_download_user_id=" . $report['id'];
                            } else {
                                $downloadUrl = $baseUrl . "?archive_id=" . $report['id'];
                            }

                            // Status Logic
                            $isCompleted = ($isProfessional && $report['step'] >= 8) || (!$isProfessional && $report['step'] >= 13);
                        ?>
                        
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group h-full flex flex-col justify-between">
                            
                            <div class="absolute -right-6 -top-6 <?php echo $iconColor; ?> opacity-10 group-hover:opacity-20 transition-opacity">
                                <?php echo $iconSvg; ?>
                            </div>

                            <div class="relative z-10">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="<?php echo $bgColor . ' ' . $iconColor; ?> text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                                        <?php echo $report['type'] === 'archive' ? 'Archivado' : 'En Progreso'; ?>
                                    </span>
                                    <span class="text-xs text-gray-400 font-medium"><?php echo date('d/m/Y', strtotime($report['updated_at'])); ?></span>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-800 leading-snug mb-2 break-words" title="<?php echo htmlspecialchars($report['school_name']); ?>">
                                    <?php echo htmlspecialchars($report['school_name']); ?>
                                </h3>
                                <p class="text-sm text-gray-500 mb-6"><?php echo $typeLabel; ?></p>
                            </div>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-50 mt-auto relative z-10">
                                <?php if ($isCompleted): ?>
                                    <div class="flex items-center text-green-600 text-sm font-semibold">
                                        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Completado
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center text-yellow-600 text-sm font-semibold">
                                        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Paso <?php echo $report['step']; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex gap-2">
                                    <form action="actions/admin_delete_report.php" method="POST" class="inline-block delete-report-form">
                                        <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                        <input type="hidden" name="report_type" value="<?php echo $report['type']; ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                                        
                                        <button type="button" onclick="confirmDeleteReport(this.form)" class="text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 font-medium rounded-lg px-4 py-2 text-sm transition-colors border border-red-100">
                                            Eliminar
                                        </button>
                                    </form>

                                    <a href="<?php echo $downloadUrl; ?>" class="text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 font-medium rounded-lg px-4 py-2 text-sm transition-colors">
                                        Descargar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12 bg-white rounded-xl border border-gray-200 border-dashed">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay proyectos</h3>
                        <p class="mt-1 text-sm text-gray-500">Este usuario aún no ha iniciado ningún trámite.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 opacity-0 transition-all duration-300" id="modalContent">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-2xl">
            <h3 class="text-xl font-bold text-gray-800">Editar Usuario</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="actions/admin_update_user.php" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de Usuario</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                <div>
                   <label class="block text-sm font-semibold text-gray-700 mb-1">Rol</label>
                   <select name="role" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                       <option value="user" <?php echo ($user['role'] ?? 'user') === 'user' ? 'selected' : ''; ?>>Usuario</option>
                       <option value="admin" <?php echo ($user['role'] ?? 'user') === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                   </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" 
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Título / Cargo</label>
                    <input type="text" name="job_title" value="<?php echo htmlspecialchars($user['job_title'] ?? ''); ?>" 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Género</label>
                    <select name="gender" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="male" <?php echo ($user['gender'] ?? 'male') === 'male' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="female" <?php echo ($user['gender'] ?? 'male') === 'female' ? 'selected' : ''; ?>>Femenino</option>
                    </select>
                </div>
            </div>

            <div class="pt-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Contraseña Nueva <span class="text-xs text-gray-400 font-normal">(Dejar en blanco para no cambiar)</span></label>
                <input type="password" name="password" placeholder="••••••••" 
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all bg-gray-50/50">
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-gray-50 mt-4">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">Cancelar</button>
                <button type="submit" class="px-5 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium shadow-lg shadow-blue-500/30 transition-all hover:scale-105">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal() {
    const modal = document.getElementById('editUserModal');
    const content = document.getElementById('modalContent');
    modal.classList.remove('hidden');
    // Small delay for transition
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
    document.body.classList.add('overflow-hidden');
}

function closeEditModal() {
    const modal = document.getElementById('editUserModal');
    const content = document.getElementById('modalContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }, 300);
}

// Close on click outside
document.getElementById('editUserModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
</html>
