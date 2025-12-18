<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

$data = $_SESSION['form_data']['step12'] ?? [];
$next_url = "../views/non-professional/step13.php";

$rowsDocente = isset($data['docente_nombres']) ? count($data['docente_nombres']) : 1;
$rowsDocente2 = isset($data['docente2_nombres']) ? count($data['docente2_nombres']) : 1;
$rowsInst = isset($data['inst_nombres']) ? count($data['inst_nombres']) : 1;
$rowsInst2 = isset($data['inst2_nombres']) ? count($data['inst2_nombres']) : 1;
$rowsCurso = isset($data['curso_autorizacion']) ? count($data['curso_autorizacion']) : 1;
?>

<div class="max-w-7xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Docentes e Instructores</h2>
         <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 12 de 13</span>
            <span class="text-gray-400">|</span>
            <span>Talento Humano</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
             <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 100%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden space-y-12">
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="12">
        <input type="hidden" name="next_url" value="<?php echo $next_url; ?>">

        <!-- 10.2 DOCENTES -->
        <div>
            <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">1</span>
                    10.2 DOCENTES
                </h3>
                 <button type="button" onclick="addCard('docente-container')" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Agregar
                </button>
            </div>
            
            <div id="docente-container" class="space-y-6">
                <?php for ($i = 0; $i < $rowsDocente; $i++): ?>
                <div class="docente-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <!-- Fields -->
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombres y Apellidos</label><input type="text" name="docente_nombres[]" value="<?php echo htmlspecialchars($data['docente_nombres'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cédula</label><input type="text" name="docente_cedula[]" value="<?php echo htmlspecialchars($data['docente_cedula'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo curso</label><input type="text" name="docente_curso[]" value="<?php echo htmlspecialchars($data['docente_curso'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Reg. SENESCYT</label><input type="text" name="docente_senescyt[]" value="<?php echo htmlspecialchars($data['docente_senescyt'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Título</label><input type="text" name="docente_titulo[]" value="<?php echo htmlspecialchars($data['docente_titulo'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cátedra</label><input type="text" name="docente_catedra[]" value="<?php echo htmlspecialchars($data['docente_catedra'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Exp. Docencia (Años)</label><input type="number" name="docente_experiencia[]" value="<?php echo htmlspecialchars($data['docente_experiencia'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">¿Cargo público TTTSV?</label>
                            <select name="docente_cargo_publico[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="">-</option>
                                <option value="Si" <?php echo ($data['docente_cargo_publico'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['docente_cargo_publico'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cumple requisitos</label>
                            <select name="docente_cumple[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="">-</option>
                                <option value="Si" <?php echo ($data['docente_cumple'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['docente_cumple'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" onclick="removeCard(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- DOCENTES SECCIÓN 2 -->
        <div>
             <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">2</span>
                    DOCENTES (Sección 2)
                </h3>
                 <button type="button" onclick="addCard('docente2-container')" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Agregar
                </button>
            </div>
            <div id="docente2-container" class="space-y-6">
                <?php for ($i = 0; $i < $rowsDocente2; $i++): ?>
                <div class="docente2-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombres y Apellidos</label><input type="text" name="docente2_nombres[]" value="<?php echo htmlspecialchars($data['docente2_nombres'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cédula</label><input type="text" name="docente2_cedula[]" value="<?php echo htmlspecialchars($data['docente2_cedula'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Reg. SENESCYT</label><input type="text" name="docente2_senescyt[]" value="<?php echo htmlspecialchars($data['docente2_senescyt'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Título</label><input type="text" name="docente2_titulo[]" value="<?php echo htmlspecialchars($data['docente2_titulo'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cátedra</label><input type="text" name="docente2_catedra[]" value="<?php echo htmlspecialchars($data['docente2_catedra'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Exp. Docencia (Años)</label><input type="number" name="docente2_experiencia[]" value="<?php echo htmlspecialchars($data['docente2_experiencia'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cumple requisitos</label>
                            <select name="docente2_cumple[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="">-</option>
                                <option value="Si" <?php echo ($data['docente2_cumple'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['docente2_cumple'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" onclick="removeCard(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- 10.3 INSTRUCTORES -->
        <div>
             <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">3</span>
                    10.3 INSTRUCTORES
                </h3>
                 <button type="button" onclick="addCard('inst-container')" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Agregar
                </button>
            </div>
            <div id="inst-container" class="space-y-6">
                 <?php for ($i = 0; $i < $rowsInst; $i++): ?>
                <div class="inst-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombres y Apellidos</label><input type="text" name="inst_nombres[]" value="<?php echo htmlspecialchars($data['inst_nombres'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cédula</label><input type="text" name="inst_cedula[]" value="<?php echo htmlspecialchars($data['inst_cedula'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo Licencia</label><input type="text" name="inst_tipo_licencia[]" value="<?php echo htmlspecialchars($data['inst_tipo_licencia'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Edad</label><input type="number" name="inst_edad[]" value="<?php echo htmlspecialchars($data['inst_edad'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nivel Instrucción</label><input type="text" name="inst_instruccion[]" value="<?php echo htmlspecialchars($data['inst_instruccion'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cert. Conductor ANT</label><input type="text" name="inst_certificado[]" value="<?php echo htmlspecialchars($data['inst_certificado'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha Licencia (1ra)</label><input type="date" name="inst_fecha_licencia[]" value="<?php echo htmlspecialchars($data['inst_fecha_licencia'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-gray-700"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Puntos Licencia</label><input type="text" name="inst_puntos[]" value="<?php echo htmlspecialchars($data['inst_puntos'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Exp. Conducción (Años)</label><input type="number" name="inst_experiencia[]" value="<?php echo htmlspecialchars($data['inst_experiencia'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo curso capacitar</label><input type="text" name="inst_tipo_curso[]" value="<?php echo htmlspecialchars($data['inst_tipo_curso'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">¿Cargo público TTTSV?</label>
                            <select name="inst_cargo_publico[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="">-</option>
                                <option value="Si" <?php echo ($data['inst_cargo_publico'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['inst_cargo_publico'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cumple requisitos</label>
                            <select name="inst_cumple[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="">-</option>
                                <option value="Si" <?php echo ($data['inst_cumple'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['inst_cumple'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" onclick="removeCard(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- 10.3 INSTRUCTORES SECCIÓN 2 -->
        <div>
             <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">4</span>
                    INSTRUCTORES (Sección 2)
                </h3>
                 <button type="button" onclick="addCard('inst2-container')" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Agregar
                </button>
            </div>
            <div id="inst2-container" class="space-y-6">
                 <?php for ($i = 0; $i < $rowsInst2; $i++): ?>
                <div class="inst2-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombres y Apellidos</label><input type="text" name="inst2_nombres[]" value="<?php echo htmlspecialchars($data['inst2_nombres'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cédula</label><input type="text" name="inst2_cedula[]" value="<?php echo htmlspecialchars($data['inst2_cedula'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo Licencia</label><input type="text" name="inst2_tipo_licencia[]" value="<?php echo htmlspecialchars($data['inst2_tipo_licencia'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nivel Instrucción</label><input type="text" name="inst2_instruccion[]" value="<?php echo htmlspecialchars($data['inst2_instruccion'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Edad</label><input type="number" name="inst2_edad[]" value="<?php echo htmlspecialchars($data['inst2_edad'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Exp. Conducción (Años)</label><input type="number" name="inst2_experiencia[]" value="<?php echo htmlspecialchars($data['inst2_experiencia'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo curso capacitar</label><input type="text" name="inst2_tipo_curso[]" value="<?php echo htmlspecialchars($data['inst2_tipo_curso'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cert. Inst. Vial (B)</label><input type="text" name="inst2_certificado_vial[]" value="<?php echo htmlspecialchars($data['inst2_certificado_vial'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                       <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Acredita idoneidad</label>
                            <select name="inst2_idoneidad[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="">-</option>
                                <option value="Si" <?php echo ($data['inst2_idoneidad'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['inst2_idoneidad'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha última infrac.</label><input type="date" name="inst2_fecha_infraccion[]" value="<?php echo htmlspecialchars($data['inst2_fecha_infraccion'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-gray-700"></div>
                         <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cumple requisitos</label>
                            <select name="inst2_cumple[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="">-</option>
                                <option value="Si" <?php echo ($data['inst2_cumple'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['inst2_cumple'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" onclick="removeCard(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- 12. CONTROL DE CURSOS -->
        <div>
             <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">5</span>
                    CONTROL DE CURSOS DE CONDUCCIÓN
                </h3>
                 <button type="button" onclick="addCard('curso-container')" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Agregar
                </button>
            </div>
            <div id="curso-container" class="space-y-6">
                 <?php for ($i = 0; $i < $rowsCurso; $i++): ?>
                <div class="curso-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nro. Autorización</label><input type="text" name="curso_autorizacion[]" value="<?php echo htmlspecialchars($data['curso_autorizacion'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha</label><input type="date" name="curso_fecha[]" value="<?php echo htmlspecialchars($data['curso_fecha'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-gray-700"></div>
                        
                        <div class="md:col-span-2 lg:col-span-1 grid grid-cols-2 gap-2">
                             <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Matr. Inicio</label><input type="date" name="curso_matricula_inicio[]" value="<?php echo htmlspecialchars($data['curso_matricula_inicio'][$i] ?? ''); ?>" class="w-full px-2 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-xs"></div>
                             <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Matr. Fin</label><input type="date" name="curso_matricula_fin[]" value="<?php echo htmlspecialchars($data['curso_matricula_fin'][$i] ?? ''); ?>" class="w-full px-2 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-xs"></div>
                        </div>
                        <div class="md:col-span-2 lg:col-span-1 grid grid-cols-2 gap-2">
                             <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Clases Inicio</label><input type="date" name="curso_clases_inicio[]" value="<?php echo htmlspecialchars($data['curso_clases_inicio'][$i] ?? ''); ?>" class="w-full px-2 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-xs"></div>
                             <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Clases Fin</label><input type="date" name="curso_clases_fin[]" value="<?php echo htmlspecialchars($data['curso_clases_fin'][$i] ?? ''); ?>" class="w-full px-2 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-xs"></div>
                        </div>

                         <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Modalidad</label>
                            <select name="curso_modalidad[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="">-</option>
                                <option value="Presencial" <?php echo ($data['curso_modalidad'][$i] ?? '') == 'Presencial' ? 'selected' : ''; ?>>Presencial</option>
                                <option value="Virtual" <?php echo ($data['curso_modalidad'][$i] ?? '') == 'Virtual' ? 'selected' : ''; ?>>Virtual</option>
                                <option value="Híbrida" <?php echo ($data['curso_modalidad'][$i] ?? '') == 'Híbrida' ? 'selected' : ''; ?>>Híbrida</option>
                            </select>
                        </div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Alum. Autorizados</label><input type="number" name="curso_alumnos_autorizados[]" value="<?php echo htmlspecialchars($data['curso_alumnos_autorizados'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Alum. Matriculados</label><input type="number" name="curso_alumnos_matriculados[]" value="<?php echo htmlspecialchars($data['curso_alumnos_matriculados'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Alum. Graduados</label><input type="number" name="curso_alumnos_graduados[]" value="<?php echo htmlspecialchars($data['curso_alumnos_graduados'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                    </div>
                     <button type="button" onclick="removeCard(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
                 <?php endfor; ?>
            </div>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step11.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
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
    function addCard(containerId) {
        const container = document.getElementById(containerId);
        let templateCard = container.querySelector('div[class*="-card"]');
        
        if(!templateCard && container.children.length === 0) {
           alert("No hay plantilla para clonar. Por favor recargue la página si borró todo.");
           return;
        }

        const newCard = templateCard.cloneNode(true);
        
        // Clear inputs in the new card
        const inputs = newCard.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.value = '';
        });

        container.appendChild(newCard);
    }

    function removeCard(button) {
        const card = button.closest('div[class*="-card"]');
        const container = card.parentElement;
        if (container.children.length > 1) {
            card.remove();
        } else {
            alert("Debe haber al menos un registro.");
        }
    }
</script>

<?php include '../../includes/form_footer.php'; ?>
