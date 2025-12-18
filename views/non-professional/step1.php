<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

// Retrieve existing data if any
$data = $_SESSION['form_data']['step1'] ?? [];
?>

<div class="max-w-4xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Datos Informativos</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 1 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Información General</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 11%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden">
        
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="1">
        <input type="hidden" name="next_url" value="../views/non-professional/step2.php">
        <input type="hidden" name="school_type" value="non_professional">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Nombre de la Escuela -->
            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Nombre de la Escuela de Capacitación</label>
                <input type="text" name="escuela_nombre" value="<?php echo htmlspecialchars($data['escuela_nombre'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm"
                    placeholder="Ingrese el nombre oficial">
            </div>

            <!-- Provincia -->
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Provincia</label>
                <input type="text" name="provincia" value="<?php echo htmlspecialchars($data['provincia'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm">
            </div>

            <!-- Cantón -->
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Cantón</label>
                <input type="text" name="canton" value="<?php echo htmlspecialchars($data['canton'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm">
            </div>

            <!-- RUC -->
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">RUC</label>
                <input type="text" name="ruc" value="<?php echo htmlspecialchars($data['ruc'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm"
                    placeholder="Ej: 1790012345001">
            </div>

            <!-- Número telefónico fijo -->
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Número telefónico fijo</label>
                <input type="text" name="telefono_fijo" value="<?php echo htmlspecialchars($data['telefono_fijo'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm">
            </div>

             <!-- Número telefónico móvil -->
             <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Número telefónico móvil</label>
                <input type="text" name="telefono" value="<?php echo htmlspecialchars($data['telefono'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm">
            </div>

            <!-- Email -->
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Correo electrónico</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm"
                    placeholder="contacto@escuela.com">
            </div>

            <!-- Representante Legal -->
            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Representante legal</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" name="gerente_nombre" value="<?php echo htmlspecialchars($data['gerente_nombre'] ?? ''); ?>"
                        class="w-full pl-12 pr-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm"
                        placeholder="Nombre completo del representante">
                </div>
            </div>

            <!-- Dirección inspección IN-SITU -->
            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Dirección inspección IN-SITU</label>
                <textarea name="direccion_insitu" rows="2"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm resize-none"><?php echo htmlspecialchars($data['direccion_insitu'] ?? ''); ?></textarea>
            </div>

            <!-- Dirección autorizada por ANT -->
            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Dirección autorizada por ANT</label>
                <textarea name="direccion_ant" rows="2"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm resize-none"><?php echo htmlspecialchars($data['direccion_ant'] ?? ''); ?></textarea>
            </div>

            <!-- Dirección que se registra en el SRI -->
            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Dirección que se registra en el SRI</label>
                <textarea name="direccion_sri" rows="2"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm resize-none"><?php echo htmlspecialchars($data['direccion_sri'] ?? ''); ?></textarea>
            </div>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="../../dashboard.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver al Panel
            </a>
            <button type="submit" class="w-full md:w-auto btn-premium text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:shadow-purple-500/30 flex items-center justify-center gap-2 group">
                Siguiente Paso
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>
</div>

<?php include '../../includes/form_footer.php'; ?>
