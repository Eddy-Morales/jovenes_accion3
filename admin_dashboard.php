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
// HEADER IS NOT INCLUDED DIRECTLY to control layout manually
// We typically would include header, but for a full custom dashboard layout, we might need custom HTML structure.
// However, to keep styles, we just need the head part. Let's include header.php but we might need to close its container if it opens one.
// Actually, looking at previous header.php, it includes Tailwind CDN. Let's assume we can build our own structure.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Antaplicacion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
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

<?php
// --- DATA FETCHING ---

// 1. Total Users (excluding admin)
$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin'");
$totalUsers = $stmt->fetchColumn();

// 2. Users with Activity Today
$today = date('Y-m-d');
$stmt = $pdo->query("SELECT COUNT(*) FROM user_progress WHERE DATE(updated_at) = '$today'");
$activeToday = $stmt->fetchColumn();

// 3. Completed Processes (Last Step >= 13)
$stmt = $pdo->query("SELECT COUNT(*) FROM user_progress WHERE last_step >= 13");
$completedProcs = $stmt->fetchColumn();

// 4. In Progress
$inProgress = $totalUsers - $completedProcs; // Approximation

// Fetch Users List
$sql = "SELECT u.id, u.username, u.created_at, u.role, p.last_step, p.updated_at as last_activity 
        FROM users u 
        LEFT JOIN user_progress p ON u.id = p.user_id 
        WHERE u.role != 'admin' 
        ORDER BY u.created_at DESC";
$users = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$users = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Fetch Pending Edit Requests
$reqStmt = $pdo->query("
    SELECT er.*, u.username, u.full_name, ra.school_name 
    FROM edit_requests er 
    JOIN users u ON er.user_id = u.id 
    LEFT JOIN reports_archive ra ON er.archive_id = ra.id
    WHERE er.status = 'pending' 
    ORDER BY er.created_at DESC
");
$pendingRequests = $reqStmt->fetchAll(PDO::FETCH_ASSOC);
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
            <a href="#" class="flex items-center px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/30 transition-all duration-200 group">
                <svg class="w-5 h-5 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-bold">Dashboard</span>
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
        <!-- TOPBAR -->
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-200 flex items-center justify-between px-4 md:px-8 sticky top-0 z-30 transition-all duration-300 shadow-sm">
            <div class="flex items-center gap-4">
                 <!-- Hamburger Button -->
                <button id="sidebarToggle" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none bg-gray-100 p-2 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-800 to-gray-600">Dashboard Administrador</div>
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

        <!-- MAIN SCROLL AREA -->
        <div class="flex-1 overflow-y-auto p-8">
            
            <!-- STATS CARDS -->
            <!-- STATS CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl shadow-lg p-6 relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-white opacity-10 rounded-full -mr-6 -mt-6 transform rotate-45 group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="text-blue-100 text-xs font-bold uppercase tracking-wider mb-1">Total Usuarios</div>
                        <div class="text-4xl font-extrabold mb-1"><?php echo $totalUsers; ?></div>
                        <div class="inline-flex items-center text-xs bg-blue-800/30 px-2 py-1 rounded-full text-blue-50 border border-blue-400/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-300 mr-1.5 animate-pulse"></span>
                            Registrados
                        </div>
                    </div>
                    <div class="absolute bottom-4 right-4 text-blue-200/40">
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-gradient-to-br from-violet-500 to-purple-600 text-white rounded-2xl shadow-lg p-6 relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-white opacity-10 rounded-full -mr-6 -mt-6 transform rotate-45 group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="text-purple-100 text-xs font-bold uppercase tracking-wider mb-1">Activos Hoy</div>
                        <div class="text-4xl font-extrabold mb-1"><?php echo $activeToday; ?></div>
                        <div class="inline-flex items-center text-xs bg-purple-800/30 px-2 py-1 rounded-full text-purple-50 border border-purple-400/20">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Actividad reciente
                        </div>
                    </div>
                    <div class="absolute bottom-4 right-4 text-purple-200/40">
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                </div>
                
                <!-- Card 3 -->
                <div class="bg-gradient-to-br from-amber-400 to-orange-500 text-white rounded-2xl shadow-lg p-6 relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                     <div class="absolute right-0 top-0 w-24 h-24 bg-white opacity-10 rounded-full -mr-6 -mt-6 transform rotate-45 group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="text-orange-100 text-xs font-bold uppercase tracking-wider mb-1">En Progreso</div>
                        <div class="text-4xl font-extrabold mb-1"><?php echo $inProgress; ?></div>
                        <div class="inline-flex items-center text-xs bg-orange-800/30 px-2 py-1 rounded-full text-orange-50 border border-orange-400/20">
                             <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Pendientes
                        </div>
                    </div>
                     <div class="absolute bottom-4 right-4 text-orange-200/40">
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                </div>
                
                <!-- Card 4 -->
                 <div class="bg-gradient-to-br from-emerald-400 to-teal-500 text-white rounded-2xl shadow-lg p-6 relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                     <div class="absolute right-0 top-0 w-24 h-24 bg-white opacity-10 rounded-full -mr-6 -mt-6 transform rotate-45 group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="text-emerald-100 text-xs font-bold uppercase tracking-wider mb-1">Finalizados</div>
                        <div class="text-4xl font-extrabold mb-1"><?php echo $completedProcs; ?></div>
                        <div class="inline-flex items-center text-xs bg-emerald-800/30 px-2 py-1 rounded-full text-emerald-50 border border-emerald-400/20">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Completados
                        </div>
                    </div>
                     <div class="absolute bottom-4 right-4 text-emerald-200/40">
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- EDIT REQUESTS SECTION -->
            <?php if (count($pendingRequests) > 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-orange-100 bg-orange-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-orange-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Solicitudes de Edición Pendientes
                    </h3>
                    <span class="bg-orange-200 text-orange-800 text-xs font-bold px-2 py-1 rounded-full"><?php echo count($pendingRequests); ?></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-orange-100">
                         <thead class="bg-orange-50/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-orange-600 uppercase tracking-wider">Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-orange-600 uppercase tracking-wider">Informe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-orange-600 uppercase tracking-wider">Fecha Solicitud</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-orange-600 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-orange-100">
                            <?php foreach ($pendingRequests as $req): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 text-xs font-bold mr-3">
                                            <?php echo strtoupper(substr($req['username'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($req['username']); ?></div>
                                            <div class="text-xs text-gray-500">ID: #<?php echo $req['user_id']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium"><?php echo htmlspecialchars($req['school_name'] ?? 'Informe General'); ?></div>
                                    <div class="text-xs text-gray-500">Archivo ID: #<?php echo $req['archive_id'] ?? 'N/A'; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('d/m/Y H:i', strtotime($req['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="actions/handle_request.php" method="POST" class="inline-flex gap-2 request-form">
                                        <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                        
                                        <button type="button" onclick="confirmAction(this.form, 'approve')" class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1 rounded flex items-center gap-1 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Aprobar
                                        </button>
                                        
                                        <button type="button" onclick="confirmAction(this.form, 'reject')" class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded flex items-center gap-1 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Rechazar
                                        </button>
                                        <input type="hidden" name="action" id="action_input_<?php echo $req['id']; ?>">
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- EXPLICIT EMPTY STATE FOR REQUESTS (User feedback that component exists) -->
            <?php if (count($pendingRequests) === 0): ?>
            <div class="bg-white rounded-2xl shadow-sm border-2 border-dashed border-gray-200 mb-8 p-8 text-center bg-gray-50/50">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-sm mb-4 border border-gray-100">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-gray-900 font-bold text-lg mb-1">Todo al día</h3>
                <p class="text-gray-500 text-sm">¡Excelente trabajo! No hay solicitudes pendientes de revisión.</p>
            </div>
            <?php endif; ?>

            <!-- TABLE SECTION -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden border-t-4 border-t-indigo-500">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Actividad Reciente
                    </h3>
                    <a href="admin_users.php" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold hover:underline">Ver todos los usuarios &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                         <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Usuario</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Registro</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Última Actividad</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                             <?php if (count($users) > 0): ?>
                                <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-indigo-50/40 transition-colors duration-200 group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-gray-200 to-gray-300 flex items-center justify-center text-gray-600 text-xs font-bold shadow-sm ring-2 ring-white">
                                                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($user['username']); ?></div>
                                                <div class="text-xs text-gray-400">ID: #<?php echo $user['id']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                        <?php echo date('d M, Y', strtotime($user['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php if($user['last_activity']): ?>
                                            <div class="flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                <?php echo date('d/m H:i', strtotime($user['last_activity'])); ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-300">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php 
                                            // Determine status badge
                                            if (!$user['last_step']) {
                                                echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">Inactivo</span>';
                                            } elseif ($user['last_step'] >= 13) {
                                                echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Completado</span>';
                                            } else {
                                                echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">Paso ' . $user['last_step'] . '</span>';
                                            }
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <?php if ($user['last_step']): ?>
                                            <a href="actions/generate_word.php?admin_download_user_id=<?php echo $user['id']; ?>" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors text-xs font-bold uppercase tracking-wide inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                Word
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-300 text-xs italic">Sin informe</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        No hay usuarios registrados aún.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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

function confirmAction(form, action) {
    const title = action === 'approve' ? '¿Aprobar solicitud?' : '¿Rechazar solicitud?';
    const text = action === 'approve' 
        ? 'El usuario podrá editar su informe nuevamente.' 
        : 'El usuario no podrá editar su informe.';
    const confirmColor = action === 'approve' ? '#10b981' : '#ef4444';

    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            let input = form.querySelector('input[name="action"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'action';
                form.appendChild(input);
            }
            input.value = action;
            form.submit();
        }
    });
}
</script>
</body>
</html>
