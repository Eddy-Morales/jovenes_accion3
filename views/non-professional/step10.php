<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

$data = $_SESSION['form_data']['step10'] ?? [];
$rowsChalecosInst = isset($data['chaleco_inst_nro']) ? count($data['chaleco_inst_nro']) : 1;
$rowsChalecosAlum = isset($data['chaleco_alum_nro']) ? count($data['chaleco_alum_nro']) : 1;
$rowsSim = isset($data['sim_marca']) ? count($data['sim_marca']) : 1;
$rowsBio = isset($data['bio_equipo']) ? count($data['bio_equipo']) : 1;
$rowsPsico = isset($data['psico_equipo']) ? count($data['psico_equipo']) : 1;

// Define variables for dynamic content
$next_url = "../views/non-professional/step11.php";
?>

<div class="max-w-6xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Equipamiento Adicional</h2>
         <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 10 de 11</span>
            <span class="text-gray-400">|</span>
            <span>Equipamiento</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
             <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 100%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden">
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="10">
        <input type="hidden" name="next_url" value="<?php echo $next_url; ?>">

        <!-- 9. Chalecos Instructores -->
        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-8 flex items-center gap-2">
            <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">9</span>
            Chalecos Instructores
        </h3>
        
        <div class="flex justify-end mb-4">
             <button type="button" onclick="addCard('chaleco-inst-container')" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Agregar Chaleco
            </button>
        </div>

        <div id="chaleco-inst-container" class="space-y-6 mb-12">
            <?php for ($i = 0; $i < $rowsChalecosInst; $i++): ?>
            <div class="chaleco-inst-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Chaleco Nro.</label>
                        <input type="text" name="chaleco_inst_nro[]" value="<?php echo htmlspecialchars($data['chaleco_inst_nro'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Placa Nro.</label>
                        <input type="text" name="chaleco_inst_placa[]" value="<?php echo htmlspecialchars($data['chaleco_inst_placa'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Color</label>
                        <input type="text" name="chaleco_inst_color[]" value="<?php echo htmlspecialchars($data['chaleco_inst_color'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Palabra "INSTRUCTOR"</label>
                        <select name="chaleco_inst_palabra[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Si" <?php echo ($data['chaleco_inst_palabra'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data['chaleco_inst_palabra'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                </div>
                <button type="button" onclick="removeCard(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
            <?php endfor; ?>
        </div>

        <!-- 10. Chalecos Alumnos -->
        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-8 flex items-center gap-2">
            <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">10</span>
            Chalecos Alumnos
        </h3>
        
        <div class="flex justify-end mb-4">
            <button type="button" onclick="addCard('chaleco-alum-container')" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Agregar Chaleco
            </button>
        </div>

        <div id="chaleco-alum-container" class="space-y-6 mb-12">
            <?php for ($i = 0; $i < $rowsChalecosAlum; $i++): ?>
            <div class="chaleco-alum-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Chaleco Nro.</label>
                        <input type="text" name="chaleco_alum_nro[]" value="<?php echo htmlspecialchars($data['chaleco_alum_nro'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Color</label>
                        <input type="text" name="chaleco_alum_color[]" value="<?php echo htmlspecialchars($data['chaleco_alum_color'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Palabra "ESTUDIANTE"</label>
                        <select name="chaleco_alum_palabra[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Si" <?php echo ($data['chaleco_alum_palabra'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data['chaleco_alum_palabra'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                </div>
                <button type="button" onclick="removeCard(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
            <?php endfor; ?>
        </div>

        <!-- 11. Simulador de Conducción -->
        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-8 flex items-center gap-2">
            <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">11</span>
            Simulador de Conducción
        </h3>
        
        <div class="flex justify-end mb-4">
             <button type="button" onclick="addCard('sim-container')" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Agregar Simulador
            </button>
        </div>

        <div id="sim-container" class="space-y-6 mb-12">
            <?php for ($i = 0; $i < $rowsSim; $i++): ?>
            <div class="sim-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Marca</label>
                        <input type="text" name="sim_marca[]" value="<?php echo htmlspecialchars($data['sim_marca'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Modelo</label>
                        <input type="text" name="sim_modelo[]" value="<?php echo htmlspecialchars($data['sim_modelo'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Certificado Homologación</label>
                        <input type="text" name="sim_cert[]" value="<?php echo htmlspecialchars($data['sim_cert'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo de cursos</label>
                        <input type="text" name="sim_tipo_curso[]" value="<?php echo htmlspecialchars($data['sim_tipo_curso'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">¿Uso en prácticas?</label>
                        <select name="sim_uso_prac[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Si" <?php echo ($data['sim_uso_prac'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data['sim_uso_prac'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">% de Uso (por alumno)</label>
                        <input type="number" name="sim_pct_uso[]" value="<?php echo htmlspecialchars($data['sim_pct_uso'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                </div>
                <button type="button" onclick="removeCard(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
            <?php endfor; ?>
        </div>

        <!-- 12. Equipo Biométrico -->
        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-8 flex items-center gap-2">
            <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">12</span>
            Equipo Biométrico
        </h3>
        
        <div class="flex justify-end mb-4">
            <button type="button" onclick="addCard('bio-container')" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Agregar Equipo
            </button>
        </div>

        <div id="bio-container" class="space-y-6 mb-12">
            <?php for ($i = 0; $i < $rowsBio; $i++): ?>
            <div class="bio-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Equipo Nro.</label>
                        <input type="text" name="bio_equipo[]" value="<?php echo htmlspecialchars($data['bio_equipo'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">¿Registra Docentes?</label>
                        <select name="bio_docente[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Si" <?php echo ($data['bio_docente'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data['bio_docente'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">¿Registra Alumnos?</label>
                        <select name="bio_alumno[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Si" <?php echo ($data['bio_alumno'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data['bio_alumno'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">¿Registra Instructores?</label>
                        <select name="bio_instructor[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Si" <?php echo ($data['bio_instructor'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data['bio_instructor'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                </div>
                <button type="button" onclick="removeCard(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
            <?php endfor; ?>
        </div>

        <!-- 13. Equipo Psicosensométrico -->
        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-8 flex items-center gap-2">
            <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">13</span>
            Equipo Psicosensométrico
        </h3>
        
        <div class="flex justify-end mb-4">
            <button type="button" onclick="addCard('psico-container')" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Agregar Equipo
            </button>
        </div>

        <div id="psico-container" class="space-y-6 mb-12">
            <?php for ($i = 0; $i < $rowsPsico; $i++): ?>
            <div class="psico-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nro. Equipo</label>
                        <input type="text" name="psico_equipo[]" value="<?php echo htmlspecialchars($data['psico_equipo'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Modelo</label>
                        <input type="text" name="psico_modelo[]" value="<?php echo htmlspecialchars($data['psico_modelo'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Certificado Homologación</label>
                        <input type="text" name="psico_cert[]" value="<?php echo htmlspecialchars($data['psico_cert'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                </div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4">Evaluaciones que Realiza</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Auditiva</label>
                        <select name="psico_eval_aud[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Si" <?php echo ($data['psico_eval_aud'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data['psico_eval_aud'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Psicomotriz</label>
                        <select name="psico_eval_psico[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Si" <?php echo ($data['psico_eval_psico'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data['psico_eval_psico'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                     <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Visual</label>
                        <select name="psico_eval_vis[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Si" <?php echo ($data['psico_eval_vis'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data['psico_eval_vis'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Ubicación (Evita filtraciones sonoras)</label>
                        <select name="psico_ubic[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Si" <?php echo ($data['psico_ubic'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Cumple</option>
                            <option value="No" <?php echo ($data['psico_ubic'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No Cumple</option>
                        </select>
                    </div>
                </div>

                <button type="button" onclick="removeCard(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
            <?php endfor; ?>
        </div>
        
        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step9.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Anterior
            </a>
            <button type="submit" class="w-full md:w-auto btn-premium text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:shadow-purple-500/30 flex items-center justify-center gap-2 group">
                Siguiente Paso
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>
</div>

<script>
// New add function for Cards
function addCard(containerId) {
    const container = document.getElementById(containerId);
    let templateCard = container.querySelector('div[class*="-card"]');
    
    // Safety check if user deletes all
    if(!templateCard && container.children.length === 0) {
        alert("No hay plantilla para clonar. Por favor recargue la página si borró todo.");
        return;
    }

    const newCard = templateCard.cloneNode(true);
    
    // Reset inputs
    newCard.querySelectorAll('input[type="text"], input[type="number"], input[type="date"]').forEach(input => input.value = '');
    newCard.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    newCard.querySelectorAll('input[type="checkbox"]').forEach(cb => {
        cb.checked = false;
        // Reset hidden neighbor
        if(cb.nextElementSibling && cb.nextElementSibling.type === 'hidden') {
            cb.nextElementSibling.value = 'No';
        }
    });

    container.appendChild(newCard);
}

// Remove card function
function removeCard(button) {
    // Determine which container we are in
    const card = button.closest('div[class*="-card"]'); // matches .gad-card, .veha-card, .veh-card
    const container = card.parentElement;
    
    if (container.children.length > 1) {
        card.remove();
    } else {
        alert("Integridad requerida: Debe existir al menos un registro en esta sección.");
    }
}
</script>

<?php include '../../includes/form_footer.php'; ?>
