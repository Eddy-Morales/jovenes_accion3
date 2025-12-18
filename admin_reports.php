<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header("Location: index.php");
    exit();
}
require_once 'includes/db_connection.php';

// Fetch ALL user progress records (Projects)
// We join with users table to get username if needed, but the card design mostly focuses on the project itself.
// Fetch Active Projects
$sqlActive = "SELECT p.*, u.username, 'active' as type 
        FROM user_progress p 
        JOIN users u ON p.user_id = u.id";
$active = $pdo->query($sqlActive)->fetchAll(PDO::FETCH_ASSOC);

// Filter Active Reports: Only show completed ones
$active = array_filter($active, function($proj) {
    $data = json_decode($proj['form_data'] ?? '{}', true);
    $schoolType = $data['step1']['school_type'] ?? 'non_professional';
    $lastStep = isset($proj['last_step']) ? (int)$proj['last_step'] : 0;

    if ($schoolType === 'professional') {
        return $lastStep >= 8;
    } else {
        return $lastStep >= 13;
    }
});

// Fetch Archived Reports
$sqlArchive = "SELECT a.*, u.username, 'archive' as type
        FROM reports_archive a 
        JOIN users u ON a.user_id = u.id";
$archived = $pdo->query($sqlArchive)->fetchAll(PDO::FETCH_ASSOC);

// Merge
$projects = array_merge($active, $archived);

// Sort by date (updated_at for active, created_at for archive)
usort($projects, function($a, $b) {
    $tA = isset($a['updated_at']) ? strtotime($a['updated_at']) : strtotime($a['created_at']);
    $tB = isset($b['updated_at']) ? strtotime($b['updated_at']) : strtotime($b['created_at']);
    return $tB - $tA;
});
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 font-sans antialiased">
<div class="flex h-screen overflow-hidden">
    
    <!-- SIDEBAR OVERLAY -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300 md:hidden"></div>

    <!-- SIDEBAR -->
    <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 w-64 bg-white border-r border-gray-200 flex flex-col md:relative md:translate-x-0 md:flex shadow-2xl md:shadow-none">
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100">
            <span class="text-blue-600 font-bold text-2xl tracking-tight">Ant Admin</span>
            <!-- Close button for mobile -->
            <button id="closeSidebar" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <nav class="flex-1 py-6 space-y-1">
            <a href="admin_dashboard.php" class="flex items-center px-6 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="admin_users.php" class="flex items-center px-6 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-medium">Usuarios</span>
            </a>
            
            <div class="uppercase text-xs font-semibold text-gray-400 px-6 mt-8 mb-2">Reportes</div>
            
            <a href="admin_reports.php" class="flex items-center px-6 py-3 text-blue-600 bg-blue-50 border-r-4 border-blue-600 transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-medium">Todos los Informes</span>
            </a>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <a href="logout.php" id="logoutBtn" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 rounded transition-colors text-sm font-medium">Cerrar Sesión</a>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50/50">
        <!-- TOPBAR -->
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-200 flex items-center justify-between px-4 md:px-8 sticky top-0 z-30 transition-all duration-300 shadow-sm">
            <div class="flex items-center gap-4">
                 <!-- Hamburger Button -->
                <button id="sidebarToggle" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none bg-gray-100 p-2 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-800 to-gray-600">Reportes Generados</div>
            </div>
            <div class="flex items-center gap-4">
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

        <div class="flex-1 overflow-y-auto p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php if (!empty($projects)): ?>
                    <?php foreach ($projects as $proj): 
                        $reportType = $proj['type'];
                        $userId = $proj['user_id'];
                        $username = $proj['username'];

                        if ($reportType === 'archive') {
                            $reportId = $proj['id']; 
                            $schoolName = $proj['school_name'] ?? 'Informe Archivado';
                            $schoolType = $proj['school_type'];
                            $date = date('d/m/Y', strtotime($proj['created_at']));
                            $isCompleted = true; // Archives are considered complete snapshots
                            $downloadUrl = "actions/generate_word.php?archive_id=" . $reportId;
                            if ($schoolType === 'professional') {
                                $downloadUrl = "actions/generate_word_professional.php?archive_id=" . $reportId;
                            }
                        } else {
                            // Active
                            $reportId = $proj['user_id']; // Active uses user_id
                            $data = json_decode($proj['form_data'] ?? '', true);
                            $schoolName = $data['step1']['escuela_nombre'] ?? 'Escuela de Conducción';
                            if (empty($schoolName)) $schoolName = 'Sin Nombre';
                            $schoolType = $data['step1']['school_type'] ?? 'non_professional';
                            $date = date('d/m/Y', strtotime($proj['updated_at']));
                            $lastStep = $proj['last_step'] ?? 0;
                            // Check if completed logic matches
                            $isCompleted = ($schoolType === 'professional' && $lastStep >= 8) || ($schoolType !== 'professional' && $lastStep >= 13);
                            $downloadUrl = "actions/generate_word.php?admin_download_user_id=" . $reportId;
                            if ($schoolType === 'professional') {
                                $downloadUrl = "actions/generate_word_professional.php?admin_download_user_id=" . $reportId;
                            }
                        }

                        // Icons and Labels
                        if ($schoolType === 'professional') {
                            $typeLabel = 'Escuela Profesional';
                            $iconColor = 'text-blue-500';
                            $bgColor = 'bg-blue-50';
                            $iconSvg = '<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>'; 
                        } else {
                            $typeLabel = 'Escuela No Profesional';
                            $iconColor = 'text-indigo-500';
                            $bgColor = 'bg-indigo-50';
                            $iconSvg = '<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M19.305 9.172l-1.636-2.864A3 3 0 0015.075 4.5H8.925a3 3 0 00-2.594 1.808L4.695 9.172A4.002 4.002 0 003 12.5V17a1 1 0 001 1h2a1 1 0 001-1v-1h10v1a1 1 0 001 1h2a1 1 0 001-1v-4.5a4.002 4.002 0 00-1.695-3.328zM6.879 10L8.18 7.5h7.64l1.3 2.5H6.88z"></path><circle cx="7.5" cy="14.5" r="1.5"></circle><circle cx="16.5" cy="14.5" r="1.5"></circle></svg>';
                        }
                    ?>
                    <!-- Project Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-2xl transition-all duration-300 relative overflow-hidden group flex flex-col h-full border-t-4 hover:-translate-y-1 <?php echo str_replace('text-', 'border-', $iconColor); ?>">
                        
                        <!-- Header Background Decoration -->
                        <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full <?php echo $bgColor; ?> opacity-50 group-hover:scale-110 transition-transform duration-500"></div>

                        <div class="p-6 pb-4 flex-1 relative z-10">
                            <div class="flex justify-between items-start mb-5">
                                <span class="<?php echo $bgColor . ' ' . $iconColor; ?> text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wide shadow-sm">
                                    <?php echo $reportType === 'archive' ? 'Archivado' : 'Trámite'; ?>
                                </span>
                                <span class="text-xs text-gray-400 font-medium bg-gray-50 px-2 py-1 rounded"><?php echo $date; ?></span>
                            </div>
                            
                            <div class="mb-4 flex items-center justify-center">
                                <div class="w-16 h-16 rounded-full <?php echo $bgColor; ?> flex items-center justify-center <?php echo $iconColor; ?> shadow-inner mb-2 group-hover:scale-105 transition-transform duration-300">
                                     <div class="transform scale-75">
                                        <?php echo $iconSvg; ?>
                                     </div>
                                </div>
                            </div>

                            <h3 class="text-xl font-bold text-gray-800 leading-tight mb-2 text-center line-clamp-2 min-h-[3.5rem]" title="<?php echo htmlspecialchars($schoolName); ?>">
                                <?php echo htmlspecialchars($schoolName); ?>
                            </h3>
                            <p class="text-sm text-gray-500 text-center mb-6 font-medium"><?php echo $typeLabel; ?></p>
                            
                            <!-- User Info Badge -->
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 mx-1">
                                <div class="w-10 h-10 rounded-full bg-white border-2 border-white shadow-sm flex items-center justify-center text-blue-600 font-bold text-sm shrink-0">
                                    <?php echo substr($username, 0, 1); ?>
                                </div>
                                <div class="flex flex-col overflow-hidden">
                                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mb-0.5">Creado por</span>
                                    <span class="text-sm font-bold text-gray-700 truncate"><?php echo htmlspecialchars($username); ?></span>
                                </div>
                            </div>
                        </div>
                        
                         <!-- Footer: Status & Action -->
                        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex items-center justify-between mt-auto">
                            <?php if ($isCompleted): ?>
                                <div class="flex items-center text-green-600 text-xs font-bold bg-green-50 px-2 py-1 rounded-lg border border-green-100">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    COMPLETADO
                                </div>
                            <?php else: ?>
                                <div class="flex items-center text-yellow-600 text-xs font-bold bg-yellow-50 px-2 py-1 rounded-lg border border-yellow-100">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    PASO <?php echo $proj['last_step'] ?? 0; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex gap-2">
                                <form action="actions/admin_delete_report.php" method="POST" class="inline-block">
                                    <input type="hidden" name="report_id" value="<?php echo $reportId; ?>">
                                    <input type="hidden" name="report_type" value="<?php echo $reportType; ?>">
                                    
                                    <button type="button" onclick="confirmDeleteReport(this.form)" class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-lg transition-all duration-200" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>

                                <a href="<?php echo $downloadUrl; ?>" class="text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-200 hover:border-blue-600 p-2 rounded-lg transition-all duration-200 shadow-sm" title="Descargar Word">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                     <div class="col-span-full flex flex-col items-center justify-center py-12 text-gray-400 bg-white rounded-xl border-2 border-dashed border-gray-200">
                        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-lg font-medium">No hay informes disponibles</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script>
// Sidebar Toggle Logic
const sidebar = document.getElementById('adminSidebar');
const openBtn = document.getElementById('sidebarToggle');
const closeBtn = document.getElementById('closeSidebar');
const overlay = document.getElementById('sidebarOverlay');

function toggleSidebar() {
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
    // Prevent scrolling when menu is open
    document.body.classList.toggle('overflow-hidden'); 
}

openBtn.addEventListener('click', toggleSidebar);
closeBtn.addEventListener('click', toggleSidebar);
overlay.addEventListener('click', toggleSidebar);

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

function confirmDeleteReport(formElement) {
    Swal.fire({
        title: '¿Eliminar informe?',
        text: "Se eliminará el informe seleccionado. Si es un informe activo, se reiniciará el progreso.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            formElement.submit();
        }
    });
}
</script>
</body>
</html>
