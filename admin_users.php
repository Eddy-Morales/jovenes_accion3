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

// Fetch ALL users (including admin)
// Searching
$search = $_GET['search'] ?? '';
$where = "1=1"; // Default to true to include everyone
$params = [];
if (!empty($search)) {
    $where .= " AND (u.username LIKE ? OR u.id LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$currentUserId = $_SESSION['user_id'];
$sql = "SELECT u.*, p.last_step, p.updated_at as last_activity 
        FROM users u 
        LEFT JOIN user_progress p ON u.id = p.user_id 
        WHERE $where 
        ORDER BY (u.id = $currentUserId) DESC, u.role = 'admin' DESC, u.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Admin Dashboard</title>
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
            <a href="admin_users.php" class="flex items-center px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/30 transition-all duration-200 group">
                <svg class="w-5 h-5 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-bold">Usuarios</span>
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
                <div class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-800 to-gray-600">Gestión de Usuarios</div>
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
            <div class="flex justify-between items-center mb-6">
                 <!-- Search -->
                <form class="relative w-full max-w-md group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Buscar por nombre o ID..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm">
                </form>
                <div>
                    <!-- Could add "Export" or "Add User" buttons here -->
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden border-t-4 border-t-blue-500">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/80">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Usuario</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rol</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Creado</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php if (count($users) > 0): ?>
                                <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-blue-50/40 transition-colors duration-200 group" onclick="window.location='admin_user_details.php?id=<?php echo $user['id']; ?>'">
                                    <td class="px-6 py-4 whitespace-nowrap cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold shadow-md ring-2 ring-white transform group-hover:scale-110 transition-transform duration-300">
                                                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors"><?php echo htmlspecialchars($user['username']); ?></div>
                                                <div class="text-xs text-gray-500 font-mono bg-gray-100 px-1.5 py-0.5 rounded inline-block mt-0.5">ID: #<?php echo $user['id']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if (($user['role'] ?? 'user') === 'admin'): ?>
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-purple-500 to-indigo-600 text-white shadow-md">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                                Administrador
                                            </div>
                                        <?php else: ?>
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-sky-100 text-sky-700 border border-sky-200">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                Usuario
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                         <?php 
                                            if (!$user['last_step']) {
                                                echo '<div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>
                                                        Inactivo
                                                      </div>';
                                            } elseif ($user['last_step'] >= 13) {
                                                echo '<div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                                        Completado
                                                      </div>';
                                            } else {
                                                echo '<div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                                        En Progreso
                                                      </div>';
                                            }
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-700"><?php echo date('d M, Y', strtotime($user['created_at'])); ?></span>
                                            <span class="text-xs text-gray-400"><?php echo date('H:i', strtotime($user['created_at'])); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-3">
                                            <div class="text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors cursor-pointer font-semibold text-xs uppercase tracking-wide flex items-center gap-1 group/btn" onclick="event.stopPropagation(); window.location='admin_user_details.php?id=<?php echo $user['id']; ?>'">
                                                Ver Proyecto
                                                <svg class="w-3 h-3 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </div>
                                            
                                            <!-- Edit User Button -->
                                            <button type="button" 
                                                onclick="event.stopPropagation(); openEditModal(this)"
                                                data-id="<?php echo $user['id']; ?>"
                                                data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                                data-role="<?php echo htmlspecialchars($user['role']); ?>"
                                                data-fullname="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>"
                                                data-job="<?php echo htmlspecialchars($user['job_title'] ?? ''); ?>"
                                                data-gender="<?php echo htmlspecialchars($user['gender'] ?? 'male'); ?>"
                                                class="text-gray-400 hover:text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition-all duration-200" 
                                                title="Editar Usuario">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>

                                            <!-- Delete Icon Button -->
                                            <form id="deleteForm_<?php echo $user['id']; ?>" action="actions/delete_user.php" method="POST" class="inline-block" onclick="event.stopPropagation();">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <button type="button" onclick="confirmDelete(<?php echo $user['id']; ?>)" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-all duration-200" title="Eliminar Usuario">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            <span class="text-lg font-medium">No se encontraron usuarios</span>
                                            <span class="text-sm">Intenta ajustar tu búsqueda</span>
                                        </div>
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
            <input type="hidden" name="user_id" id="edit_user_id">
            <input type="hidden" name="redirect_to" value="../admin_users.php">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de Usuario</label>
                    <input type="text" name="username" id="edit_username" required 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                <div>
                   <label class="block text-sm font-semibold text-gray-700 mb-1">Rol</label>
                   <select name="role" id="edit_role" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                       <option value="user">Usuario</option>
                       <option value="admin">Administrador</option>
                   </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo</label>
                <input type="text" name="full_name" id="edit_full_name" 
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Título / Cargo</label>
                    <input type="text" name="job_title" id="edit_job_title" 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Género</label>
                    <select name="gender" id="edit_gender" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="male">Masculino</option>
                        <option value="female">Femenino</option>
                    </select>
                </div>
            </div>

            <div class="pt-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Contraseña Nueva <span class="text-xs text-gray-400 font-normal">(Vacío para mantener actual)</span></label>
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

function confirmDelete(id) {
    Swal.fire({
        title: '¿Eliminar Usuario?',
        text: "Se borrarán todos sus datos y proyectos. ¡No se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm_' + id).submit();
        }
    })
}

// Modal Logic
function openEditModal(btn) {
    // Populate fields
    document.getElementById('edit_user_id').value = btn.dataset.id;
    document.getElementById('edit_username').value = btn.dataset.username;
    document.getElementById('edit_role').value = btn.dataset.role;
    document.getElementById('edit_full_name').value = btn.dataset.fullname;
    document.getElementById('edit_job_title').value = btn.dataset.job;
    document.getElementById('edit_gender').value = btn.dataset.gender;

    const modal = document.getElementById('editUserModal');
    const content = document.getElementById('modalContent');
    modal.classList.remove('hidden');
    
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
</body>
</html>
