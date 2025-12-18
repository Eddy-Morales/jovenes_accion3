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
        <h2 class="text-3xl font-bold text-gray-800 mb-2">REGISTRO DE DATOS</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-blue-600 mb-4">
            <span class="bg-blue-100 px-3 py-1 rounded-full">Paso 1 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Información General</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 11%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden">
        
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-400"></div>

        <input type="hidden" name="step" value="1">
        <input type="hidden" name="next_url" value="../views/professional/step2.php">
        <input type="hidden" name="school_type" value="professional">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="md:col-span-2">
                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b border-gray-200 pb-2">1. DATOS INFORMATIVOS</h3>
            </div>
            <!-- Nombre de la Escuela -->
            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Nombre de la Escuela de Capacitación:</label>
                <input type="text" name="escuela_nombre" value="<?php echo htmlspecialchars($data['escuela_nombre'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm"
                    placeholder="Ingrese el nombre oficial">
            </div>

            <!-- Provincia -->
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Provincia:</label>
                <input type="text" name="provincia" value="<?php echo htmlspecialchars($data['provincia'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm">
            </div>

            <!-- Cantón -->
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Cantón:</label>
                <input type="text" name="canton" value="<?php echo htmlspecialchars($data['canton'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm">
            </div>

            <!-- Tipo de curso -->
            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Tipo de curso:</label>
                <div class="relative">
                    <select name="tipo_curso" class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all appearance-none form-input-premium backdrop-blur-sm">
                        <option value="" disabled <?php echo empty($data['tipo_curso']) ? 'selected' : ''; ?>>Seleccione una opción</option>
                        <?php
                        $tipos = ['A1', 'C', 'C1', 'D', 'E', 'G'];
                        foreach ($tipos as $tipo) {
                            $selected = ($data['tipo_curso'] ?? '') == $tipo ? 'selected' : '';
                            echo "<option value=\"$tipo\" $selected>$tipo</option>";
                        }
                        ?>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <!-- RUC -->
            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">RUC:</label>
                <input type="text" name="ruc" value="<?php echo htmlspecialchars($data['ruc'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm"
                    placeholder="Ej: 1790012345001">
            </div>

            <!-- Dirección inspección IN-SITU -->
            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Dirección (1) inspección IN-SITU:</label>
                <textarea name="direccion_insitu" rows="2"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm resize-none"><?php echo htmlspecialchars($data['direccion_insitu'] ?? ''); ?></textarea>
            </div>

            <!-- Dirección autorizada por ANT -->
            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Dirección (2) autorizada por ANT:</label>
                <textarea name="direccion_ant" rows="2"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm resize-none"><?php echo htmlspecialchars($data['direccion_ant'] ?? ''); ?></textarea>
            </div>

            <!-- Dirección que se registra en el SRI -->
            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Dirección (3) que se registra en el SRI:</label>
                <textarea name="direccion_sri" rows="2"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm resize-none"><?php echo htmlspecialchars($data['direccion_sri'] ?? ''); ?></textarea>
            </div>



            <!-- Representante Legal -->
            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Representante Legal:</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" name="gerente_nombre" value="<?php echo htmlspecialchars($data['gerente_nombre'] ?? ''); ?>"
                        class="w-full pl-12 pr-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm"
                        placeholder="Nombre completo del representante">
                </div>
            </div>

            <!-- Número telefónico fijo -->
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Número telefónico fijo:</label>
                <input type="text" name="telefono_fijo" value="<?php echo htmlspecialchars($data['telefono_fijo'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm">
            </div>

             <!-- Número telefónico móvil -->
             <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Número telefónico móvil:</label>
                <input type="text" name="telefono" value="<?php echo htmlspecialchars($data['telefono'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm">
            </div>

            <!-- Email -->
            <div class="group md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Correo electrónico:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>"
                    class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm"
                    placeholder="contacto@escuela.com">
            </div>

            <!-- Separator -->
            <div class="md:col-span-2 mt-4">
                <div class="border-t-2 border-dashed border-gray-200"></div>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-xl font-bold text-gray-800 mb-6">2. DOCUMENTOS HABILITANTES</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Resoluciones para el funcionamiento -->
                    <div class="md:col-span-2 group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Resoluciones para el funcionamiento:</label>
                        <input type="text" name="resolucion_nro" value="<?php echo htmlspecialchars($data['resolucion_nro'] ?? ''); ?>"
                            class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm">
                    </div>

                    <!-- Resoluciones de aumento/disminución -->
                    <div class="md:col-span-2 group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Resoluciones de aumento/disminución; etc.:</label>
                        <textarea name="resoluciones_extra" rows="2"
                            class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm resize-none"><?php echo htmlspecialchars($data['resoluciones_extra'] ?? ''); ?></textarea>
                    </div>

                    <!-- Estado SRI -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Estado SRI:</label>
                        <div class="relative">
                            <select name="estado_sri" class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all appearance-none form-input-premium backdrop-blur-sm">
                                <option value="" disabled <?php echo empty($data['estado_sri']) ? 'selected' : ''; ?>>Seleccione una opción</option>
                                <option value="Activo" <?php echo ($data['estado_sri'] ?? '') == 'Activo' ? 'selected' : ''; ?>>Activo</option>
                                <option value="Suspendido" <?php echo ($data['estado_sri'] ?? '') == 'Suspendido' ? 'selected' : ''; ?>>Suspendido</option>
                                <option value="Cancelado" <?php echo ($data['estado_sri'] ?? '') == 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Domicilio / Tenencia -->
                    <div class="md:col-span-2 group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Domicilio:</label>
                        <div class="relative">
                            <select name="domicilio" id="domicilio" onchange="toggleDomicilio()" class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all appearance-none form-input-premium backdrop-blur-sm">
                                <option value="" disabled <?php echo empty($data['domicilio']) ? 'selected' : ''; ?>>Seleccione una opción</option>
                                <option value="Propia" <?php echo ($data['domicilio'] ?? '') == 'Propia' ? 'selected' : ''; ?>>Propia</option>
                                <option value="Arrendada" <?php echo ($data['domicilio'] ?? '') == 'Arrendada' ? 'selected' : ''; ?>>Arrendada</option>
                                <option value="Comodato" <?php echo ($data['domicilio'] ?? '') == 'Comodato' ? 'selected' : ''; ?>>Comodato</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Patente Municipal (Condicional: Comodato) -->
                    <div id="field_patente" class="group <?php echo ($data['domicilio'] ?? '') == 'Comodato' ? '' : 'hidden'; ?>">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Patente Municipal:</label>
                        <input type="text" name="patente_municipal" value="<?php echo htmlspecialchars($data['patente_municipal'] ?? ''); ?>"
                            class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm">
                    </div>

                    <!-- Predio urbano/ impuesto predial (Condicional: Propia) -->
                    <div id="field_impuesto" class="group <?php echo ($data['domicilio'] ?? '') == 'Propia' ? '' : 'hidden'; ?>">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Predio urbano/ impuesto predial:</label>
                        <input type="text" name="impuesto_predial" value="<?php echo htmlspecialchars($data['impuesto_predial'] ?? ''); ?>"
                            class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm">
                    </div>

                    <!-- Permiso de cuerpo de bomberos -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Permiso de cuerpo de bomberos:</label>
                        <input type="text" name="permiso_bomberos" value="<?php echo htmlspecialchars($data['permiso_bomberos'] ?? ''); ?>"
                            class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm">
                    </div>

                    <!-- Permiso Sanitario -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Permiso Sanitario:</label>
                        <input type="text" name="permiso_sanitario" value="<?php echo htmlspecialchars($data['permiso_sanitario'] ?? ''); ?>"
                            class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all form-input-premium backdrop-blur-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="../../dashboard.php" class="w-full md:w-auto text-gray-500 hover:text-blue-600 font-medium px-6 py-3 rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver al Panel
            </a>
            <button type="submit" class="w-full md:w-auto btn-premium bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:shadow-blue-500/30 flex items-center justify-center gap-2 group">
                Siguiente Paso
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>
</div>

<script>
function toggleDomicilio() {
    const domicilio = document.getElementById('domicilio').value;
    const pat = document.getElementById('field_patente');
    const imp = document.getElementById('field_impuesto');

    // Reset visibility
    pat.classList.add('hidden');
    imp.classList.add('hidden');

    // Show based on selection
    if (domicilio === 'Propia') {
        imp.classList.remove('hidden');
    } else if (domicilio === 'Comodato') {
        pat.classList.remove('hidden');
    }
}
// Ensure correct state on load
document.addEventListener('DOMContentLoaded', toggleDomicilio);
</script>

<?php include '../../includes/form_footer.php'; ?>
