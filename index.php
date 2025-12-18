<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Ant System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234f46e5' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen bg-pattern relative overflow-hidden">
    
    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-br from-blue-600 to-indigo-800 rounded-b-[50px] transform scale-110 -translate-y-20 z-0"></div>
    <div class="absolute bottom-0 right-0 w-64 h-64 bg-yellow-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
    <div class="absolute top-0 left-0 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>

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
                    confirmButtonColor: '#4f46e5',
                    timer: 4000,
                    timerProgressBar: true
                });
            });
        </script>";
        unset($_SESSION['flash_message']);
    }
    ?>

    <div class="relative z-10 w-full max-w-md px-6">
        <div class="glass rounded-3xl shadow-2xl overflow-hidden p-8 md:p-10 border border-white/50">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 mb-6 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">¡Bienvenido!</h1>
                <p class="text-gray-500 mt-2 text-sm">Ingresa a tu cuenta para gestionar tus trámites.</p>
            </div>

            <form action="actions/login.php" method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Usuario</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <input type="text" name="username" id="username" required 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 sm:text-sm"
                            placeholder="Ej. admin">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Contraseña</label>
                        <a href="#" onclick="showForgotPassword(event)" class="text-xs font-medium text-indigo-600 hover:text-indigo-500 transition-colors">¿Olvidaste tu contraseña?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input type="password" name="password" id="password" required 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 sm:text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                        Iniciar Sesión
                        <svg class="ml-2 -mr-1 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-100 text-center text-sm text-gray-500">
                <p>¿Aún no tienes cuenta?</p>
                <a href="register.php" class="mt-2 inline-block font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                    Crear una cuenta nueva &rarr;
                </a>
            </div>
        </div>
        <p class="text-center text-gray-400 text-xs mt-8">&copy; <?php echo date('Y'); ?> Ant Project. Todos los derechos reservados.</p>
    </div>

<script>
function showForgotPassword(e) {
    e.preventDefault();
    Swal.fire({
        title: '¿Olvidaste tu contraseña?',
        html: '<p class="text-gray-600">Para recuperar tu contraseña, por favor contacta al <strong class="text-indigo-600">Administrador del Sistema</strong>.</p><p class="text-sm text-gray-400 mt-3">El administrador podrá restablecer tu contraseña de forma segura.</p>',
        icon: 'info',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#4f46e5',
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        }
    });
}
</script>
</body>
</html>
