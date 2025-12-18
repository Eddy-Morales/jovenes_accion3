<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

$data = $_SESSION['form_data']['step9'] ?? [];
$rowsGAD = 1; // Limit GAD to 1 row always
$rowsVeh = isset($data['veh_placa']) ? count($data['veh_placa']) : 1;
$rowsVehA = isset($data['veha_placa']) ? count($data['veha_placa']) : 1;
?>

<div class="max-w-6xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Permiso GAD y Vehículos</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 9 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Infraestructura Física</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 90%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden">
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="9">
        <input type="hidden" name="next_url" value="../views/non-professional/step10.php">

        <!-- SECTION 1: GAD PERMISSION -->
        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-8 flex items-center gap-2">
            <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">7</span>
            Permiso del GAD
        </h3>
        
        <div id="gad-container" class="space-y-6 mb-8">
            <?php for ($i = 0; $i < $rowsGAD; $i++): ?>
            <div class="gad-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tipo de Curso</label>
                        <select name="gad_tipo[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Tipo A" <?php echo ($data['gad_tipo'][$i] ?? '') == 'Tipo A' ? 'selected' : ''; ?>>Tipo A</option>
                            <option value="Tipo B" <?php echo ($data['gad_tipo'][$i] ?? '') == 'Tipo B' ? 'selected' : ''; ?>>Tipo B</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Número de autorización</label>
                        <input type="text" name="gad_autorizacion[]" value="<?php echo htmlspecialchars($data['gad_autorizacion'][$i] ?? ''); ?>" 
                        class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all form-input-premium">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Institución que emite</label>
                        <input type="text" name="gad_entidad[]" value="<?php echo htmlspecialchars($data['gad_entidad'][$i] ?? ''); ?>" 
                        class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all form-input-premium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Fecha de vigencia hasta</label>
                        <input type="date" name="gad_fechacaducidad[]" value="<?php echo htmlspecialchars($data['gad_fechacaducidad'][$i] ?? ''); ?>" 
                        class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer text-gray-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Oficio de ingreso a Dirección Control</label>
                        <input type="text" name="gad_oficio[]" value="<?php echo htmlspecialchars($data['gad_oficio'][$i] ?? ''); ?>" 
                        class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all form-input-premium">
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <!-- SECTION 2: VEHICLES TYPE A (OPTIONAL) -->
        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-8 flex items-center gap-2 mt-12">
            <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">8.1</span>
            Vehículos Tipo A (Motos)
        </h3>

        <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100 mb-8 flex items-center gap-4">
            <span class="text-sm font-bold text-indigo-800">¿Va a llenar vehículos para tipo A?</span>
            <select name="has_type_a" class="px-4 py-2 bg-white border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-medium text-indigo-700 cursor-pointer transition-all" onchange="toggleTypeA(this.value === 'Si')">
                <option value="">- Seleccione -</option>
                <option value="Si" <?php echo ($data['has_type_a'] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                <option value="No" <?php echo ($data['has_type_a'] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
            </select>
        </div>

        <div id="type_a_container" class="<?php echo ($data['has_type_a'] ?? 'No') == 'Si' ? '' : 'hidden'; ?> mb-8 animate-fade-in-down">
             <div class="flex justify-end mb-4">
                <button type="button" onclick="addCard('veha-container')" class="bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-800 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-blue-200 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Agregar Moto Tipo A
                </button>
            </div>

            <div id="veha-container" class="space-y-6">
                <?php for ($i = 0; $i < $rowsVehA; $i++): ?>
                <div class="veha-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4">Datos del Vehículo</h4>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Placa</label><input type="text" name="veha_placa[]" value="<?php echo htmlspecialchars($data['veha_placa'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Autorizado Res.</label><input type="text" name="veha_aut[]" value="<?php echo htmlspecialchars($data['veha_aut'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Modelo</label><input type="text" name="veha_modelo[]" value="<?php echo htmlspecialchars($data['veha_modelo'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo</label><input type="text" name="veha_tipo[]" value="<?php echo htmlspecialchars($data['veha_tipo'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Año Fab.</label><input type="number" name="veha_anio[]" value="<?php echo htmlspecialchars($data['veha_anio'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500"></div>
                    </div>
                    
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4 mt-6">Características Visuales</h4>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <label class="flex flex-col items-center justify-center p-3 bg-white/50 rounded-xl border border-gray-200 cursor-pointer hover:bg-purple-50 hover:border-purple-200 transition-all">
                            <span class="text-[10px] font-bold text-gray-500 uppercase mb-2">Antena Flexible</span>
                            <div class="relative">
                                <input type="checkbox" class="peer sr-only" onclick="updateHidden(this)" <?php echo ($data['veha_antena'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                            </div>
                            <input type="hidden" name="veha_antena[]" value="<?php echo ($data['veha_antena'][$i] ?? 'No'); ?>">
                        </label>
                         <label class="flex flex-col items-center justify-center p-3 bg-white/50 rounded-xl border border-gray-200 cursor-pointer hover:bg-purple-50 hover:border-purple-200 transition-all">
                            <span class="text-[10px] font-bold text-gray-500 uppercase mb-2">Banderola E</span>
                            <div class="relative">
                                <input type="checkbox" class="peer sr-only" onclick="updateHidden(this)" <?php echo ($data['veha_banderola'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                            </div>
                            <input type="hidden" name="veha_banderola[]" value="<?php echo ($data['veha_banderola'][$i] ?? 'No'); ?>">
                        </label>
                        <label class="flex flex-col items-center justify-center p-3 bg-white/50 rounded-xl border border-gray-200 cursor-pointer hover:bg-purple-50 hover:border-purple-200 transition-all">
                            <span class="text-[10px] font-bold text-gray-500 uppercase mb-2">Logos Identif.</span>
                            <div class="relative">
                                <input type="checkbox" class="peer sr-only" onclick="updateHidden(this)" <?php echo ($data['veha_logos'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                            </div>
                            <input type="hidden" name="veha_logos[]" value="<?php echo ($data['veha_logos'][$i] ?? 'No'); ?>">
                        </label>
                    </div>

                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4 mt-6">Documentación & Estado</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Póliza (Vigencia)</label><input type="text" name="veha_pol_vig[]" placeholder="Desde - Hasta" value="<?php echo htmlspecialchars($data['veha_pol_vig'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500"></div>
                         <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Póliza 100%</label>
                            <select name="veha_pol_100[]" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500 cursor-pointer"><option value="">-</option><option value="Si" <?php echo ($data['veha_pol_100'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option><option value="No" <?php echo ($data['veha_pol_100'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option></select>
                        </div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Matrícula Anual</label><input type="date" name="veha_mat_anual[]" value="<?php echo htmlspecialchars($data['veha_mat_anual'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Rev. Técnica</label><input type="date" name="veha_rev_tec[]" value="<?php echo htmlspecialchars($data['veha_rev_tec'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500"></div>
                    </div>
                     <div class="grid grid-cols-3 gap-4 mt-4">
                         <label class="flex flex-col items-center justify-center p-3 bg-white/50 rounded-xl border border-gray-200 cursor-pointer hover:bg-purple-50 hover:border-purple-200 transition-all">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[10px] font-bold text-gray-500 uppercase">Mant. Prev/Corr</span>
                            </div>
                            <div class="relative">
                                <input type="checkbox" class="peer sr-only" onclick="updateHidden(this)" <?php echo ($data['veha_mant'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                            </div>
                            <input type="hidden" name="veha_mant[]" value="<?php echo ($data['veha_mant'][$i] ?? 'No'); ?>">
                        </label>
                        <label class="flex flex-col items-center justify-center p-3 bg-white/50 rounded-xl border border-gray-200 cursor-pointer hover:bg-purple-50 hover:border-purple-200 transition-all">
                             <div class="flex items-center gap-2 mb-2">
                                <span class="text-[10px] font-bold text-gray-500 uppercase">Rastreo Sat.</span>
                            </div>
                            <div class="relative">
                                <input type="checkbox" class="peer sr-only" onclick="updateHidden(this)" <?php echo ($data['veha_rastreo'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                            </div>
                            <input type="hidden" name="veha_rastreo[]" value="<?php echo ($data['veha_rastreo'][$i] ?? 'No'); ?>">
                        </label>
                         <div class="flex flex-col">
                            <label class="text-[10px] font-bold text-gray-400 uppercase text-center mb-1">Fines Ajenos</label>
                            <select name="veha_ajenos[]" class="w-full px-2 py-2 bg-white/50 border rounded-lg text-sm mt-auto focus:ring-purple-500"><option value="">-</option><option value="Si" <?php echo ($data['veha_ajenos'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option><option value="No" <?php echo ($data['veha_ajenos'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option></select>
                        </div>
                    </div>

                    <button type="button" onclick="removeCard(this)" class="absolute top-4 right-4 text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-xl transition-all opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div> 
                <?php endfor; ?>
            </div>
            
        </div>

        <!-- SECTION 3: VEHICLES TYPE B -->
        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-8 flex items-center gap-2 mt-12">
            <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">8.2</span>
            Vehículos Tipo B
        </h3>

        <div class="flex justify-end mb-4">
             <button type="button" onclick="addCard('veh-container')" class="bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-800 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-blue-200 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Agregar Vehículo Tipo B
            </button>
        </div>

        <div id="veh-container" class="space-y-6">
            <?php for ($i = 0; $i < $rowsVeh; $i++): ?>
            <div class="veh-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4">Datos del Vehículo</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
                     <div class="col-span-1"><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Placa</label><input type="text" name="veh_placa[]" value="<?php echo htmlspecialchars($data['veh_placa'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500"></div>
                    <div class="col-span-1"><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Aut. Res.</label><input type="text" name="veh_aut[]" value="<?php echo htmlspecialchars($data['veh_aut'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500"></div>
                    <div class="col-span-2"><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nro. Chasis</label><input type="text" name="veh_chasis[]" value="<?php echo htmlspecialchars($data['veh_chasis'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500"></div>
                    <div class="col-span-1"><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Modelo</label><input type="text" name="veh_modelo[]" value="<?php echo htmlspecialchars($data['veh_modelo'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500"></div>
                     <div class="col-span-1"><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo</label><input type="text" name="veh_tipo[]" value="<?php echo htmlspecialchars($data['veh_tipo'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500"></div>
                    <div class="col-span-1"><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Año</label><input type="number" name="veh_anio[]" value="<?php echo htmlspecialchars($data['veh_anio'][$i] ?? ''); ?>" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500"></div>
                    <div class="col-span-1"><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Espejo Adi.</label><select name="veh_espejo[]" class="w-full px-3 py-2 bg-white/50 border rounded-lg text-sm focus:ring-purple-500"><option value="">-</option><option value="Si" <?php echo ($data['veh_espejo'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option><option value="No" <?php echo ($data['veh_espejo'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option></select></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-4">
                    <div>
                        <h5 class="text-xs font-bold text-indigo-600 mb-2 uppercase">Franjas Reflectivas</h5>
                        <div class="flex gap-2">
                             <label class="flex flex-col items-center bg-white/50 border rounded-lg p-2 flex-1 cursor-pointer hover:bg-purple-50"><span class="text-[9px] font-bold text-gray-500 uppercase">Front</span><input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600 mt-1" onclick="updateHidden(this)" <?php echo ($data['veh_fr'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_fr[]" value="<?php echo ($data['veh_fr'][$i] ?? 'No'); ?>"></label>
                             <label class="flex flex-col items-center bg-white/50 border rounded-lg p-2 flex-1 cursor-pointer hover:bg-purple-50"><span class="text-[9px] font-bold text-gray-500 uppercase">Post</span><input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600 mt-1" onclick="updateHidden(this)" <?php echo ($data['veh_post'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_post[]" value="<?php echo ($data['veh_post'][$i] ?? 'No'); ?>"></label>
                             <label class="flex flex-col items-center bg-white/50 border rounded-lg p-2 flex-1 cursor-pointer hover:bg-purple-50"><span class="text-[9px] font-bold text-gray-500 uppercase">Lat</span><input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600 mt-1" onclick="updateHidden(this)" <?php echo ($data['veh_lat'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_lat[]" value="<?php echo ($data['veh_lat'][$i] ?? 'No'); ?>"></label>
                        </div>
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-indigo-600 mb-2 uppercase">Logos Identificación</h5>
                        <div class="flex gap-2">
                             <label class="flex flex-col items-center bg-white/50 border rounded-lg p-2 flex-1 cursor-pointer hover:bg-purple-50"><span class="text-[9px] font-bold text-gray-500 uppercase">Front</span><input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600 mt-1" onclick="updateHidden(this)" <?php echo ($data['veh_lf'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_lf[]" value="<?php echo ($data['veh_lf'][$i] ?? 'No'); ?>"></label>
                             <label class="flex flex-col items-center bg-white/50 border rounded-lg p-2 flex-1 cursor-pointer hover:bg-purple-50"><span class="text-[9px] font-bold text-gray-500 uppercase">Post</span><input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600 mt-1" onclick="updateHidden(this)" <?php echo ($data['veh_lp'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_lp[]" value="<?php echo ($data['veh_lp'][$i] ?? 'No'); ?>"></label>
                             <label class="flex flex-col items-center bg-white/50 border rounded-lg p-2 flex-1 cursor-pointer hover:bg-purple-50"><span class="text-[9px] font-bold text-gray-500 uppercase">Lat</span><input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600 mt-1" onclick="updateHidden(this)" <?php echo ($data['veh_ll'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_ll[]" value="<?php echo ($data['veh_ll'][$i] ?? 'No'); ?>"></label>
                        </div>
                    </div>
                      <div>
                        <h5 class="text-xs font-bold text-indigo-600 mb-2 uppercase">Letrero "Estudiante"</h5>
                        <div class="flex gap-2">
                             <label class="flex flex-col items-center bg-white/50 border rounded-lg p-2 flex-1 cursor-pointer hover:bg-purple-50"><span class="text-[9px] font-bold text-gray-500 uppercase">Sup</span><input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600 mt-1" onclick="updateHidden(this)" <?php echo ($data['veh_ls'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_ls[]" value="<?php echo ($data['veh_ls'][$i] ?? 'No'); ?>"></label>
                             <label class="flex flex-col items-center bg-white/50 border rounded-lg p-2 flex-1 cursor-pointer hover:bg-purple-50"><span class="text-[9px] font-bold text-gray-500 uppercase">Post</span><input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600 mt-1" onclick="updateHidden(this)" <?php echo ($data['veh_lpost'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_lpost[]" value="<?php echo ($data['veh_lpost'][$i] ?? 'No'); ?>"></label>
                             <input type="text" name="veh_ld[]" value="<?php echo htmlspecialchars($data['veh_ld'][$i] ?? ''); ?>" class="w-16 px-1 border rounded-lg text-xs text-center h-full bg-white/50 focus:ring-purple-500" placeholder="Dimens.">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-4">
                     <div>
                        <h5 class="text-xs font-bold text-indigo-600 mb-2 uppercase">Doble Comando</h5>
                         <div class="flex gap-2">
                             <label class="flex flex-col items-center bg-white/50 border rounded-lg p-2 flex-1 cursor-pointer hover:bg-purple-50"><span class="text-[9px] font-bold text-gray-500 uppercase">Acel</span><input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600 mt-1" onclick="updateHidden(this)" <?php echo ($data['veh_ac'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_ac[]" value="<?php echo ($data['veh_ac'][$i] ?? 'No'); ?>"></label>
                             <label class="flex flex-col items-center bg-white/50 border rounded-lg p-2 flex-1 cursor-pointer hover:bg-purple-50"><span class="text-[9px] font-bold text-gray-500 uppercase">Fren</span><input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600 mt-1" onclick="updateHidden(this)" <?php echo ($data['veh_frn'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_frn[]" value="<?php echo ($data['veh_frn'][$i] ?? 'No'); ?>"></label>
                             <label class="flex flex-col items-center bg-white/50 border rounded-lg p-2 flex-1 cursor-pointer hover:bg-purple-50"><span class="text-[9px] font-bold text-gray-500 uppercase">Emb</span><input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600 mt-1" onclick="updateHidden(this)" <?php echo ($data['veh_emb'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_emb[]" value="<?php echo ($data['veh_emb'][$i] ?? 'No'); ?>"></label>
                        </div>
                    </div>
                     <div>
                        <h5 class="text-xs font-bold text-indigo-600 mb-2 uppercase">Póliza Seguros</h5>
                        <div class="grid grid-cols-3 gap-2">
                             <div class="col-span-2"><input type="text" name="veh_pol_vigencia[]" value="<?php echo htmlspecialchars($data['veh_pol_vigencia'][$i] ?? ''); ?>" class="w-full px-2 py-2 border rounded-lg text-xs bg-white/50 focus:ring-purple-500" placeholder="Vigencia Desde-Hasta"></div>
                             <label class="flex flex-col items-center bg-white/50 border rounded-lg p-1 cursor-pointer hover:bg-purple-50"><span class="text-[8px] font-bold text-gray-500 uppercase text-center">Cobertura</span><input type="checkbox" class="form-checkbox h-4 w-4 text-purple-600 mt-1" onclick="updateHidden(this)" <?php echo ($data['veh_cob'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_cob[]" value="<?php echo ($data['veh_cob'][$i] ?? 'No'); ?>"></label>
                        </div>
                    </div>
                    <div>
                         <h5 class="text-xs font-bold text-indigo-600 mb-2 uppercase">Revisión/Docs</h5>
                         <div class="grid grid-cols-2 gap-2">
                            <div><label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Matrícula Anual</label><input type="date" name="veh_rev_anual[]" value="<?php echo htmlspecialchars($data['veh_rev_anual'][$i] ?? ''); ?>" class="w-full px-1 py-1 border rounded-lg text-xs bg-white/50 focus:ring-purple-500"></div>
                            <div><label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Matricula Técnica</label><input type="date" name="veh_rev_tec[]" value="<?php echo htmlspecialchars($data['veh_rev_tec'][$i] ?? ''); ?>" class="w-full px-1 py-1 border rounded-lg text-xs bg-white/50 focus:ring-purple-500"></div>
                        </div>
                    </div>
                </div>
                
                 <div class="grid grid-cols-4 gap-2 mt-4 bg-indigo-50/50 p-3 rounded-xl border border-indigo-100">
                    <label class="flex flex-col items-center cursor-pointer hover:opacity-80"><span class="text-[9px] text-indigo-800 mb-1 text-center font-bold uppercase">Bitácora Mant.</span><input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600" onclick="updateHidden(this)" <?php echo ($data['veh_bitk'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_bitk[]" value="<?php echo ($data['veh_bitk'][$i] ?? 'No'); ?>"></label>
                    <label class="flex flex-col items-center cursor-pointer hover:opacity-80"><span class="text-[9px] text-indigo-800 mb-1 text-center font-bold uppercase">Mant. Prev.</span><input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600" onclick="updateHidden(this)" <?php echo ($data['veh_bit'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_bit[]" value="<?php echo ($data['veh_bit'][$i] ?? 'No'); ?>"></label>
                    <label class="flex flex-col items-center cursor-pointer hover:opacity-80"><span class="text-[9px] text-indigo-800 mb-1 text-center font-bold uppercase">Sist. Rastreo</span><input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600" onclick="updateHidden(this)" <?php echo ($data['veh_rs'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_rs[]" value="<?php echo ($data['veh_rs'][$i] ?? 'No'); ?>"></label>
                    <label class="flex flex-col items-center cursor-pointer hover:opacity-80"><span class="text-[9px] text-indigo-800 mb-1 text-center font-bold uppercase">Fines Ajenos</span><input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600" onclick="updateHidden(this)" <?php echo ($data['veh_ua'][$i] ?? '') == 'Si' ? 'checked' : ''; ?>><input type="hidden" name="veh_ua[]" value="<?php echo ($data['veh_ua'][$i] ?? 'No'); ?>"></label>
                </div>

                <button type="button" onclick="removeCard(this)" class="absolute top-4 right-4 text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-xl transition-all opacity-0 group-hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
            <?php endfor; ?>
        </div>
        

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step8.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
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
// Toggle function for Type A section
function toggleTypeA(show) {
    const container = document.getElementById('type_a_container');
    if (show) {
        container.classList.remove('hidden');
        container.classList.add('animate-fade-in-down');
    } else {
        container.classList.add('hidden');
    }
}

// Helper to update hidden input based on checkbox state
function updateHidden(checkbox) {
    const hiddenInput = checkbox.nextElementSibling;
    if (hiddenInput && hiddenInput.type === 'hidden') {
        hiddenInput.value = checkbox.checked ? 'Si' : 'No';
    }
}

// New add function for Cards
function addCard(containerId) {
    const container = document.getElementById(containerId);
    let templateCard = container.querySelector('div[class*="-card"]'); // Get first card as template
    
     // If no card exists (user deleted all), we need a backup plan or prevent deleting the last one.
     // Current remove logic prevents deleting last one, so querySelector should work if there's at least one.
    if(!templateCard) {
        // Fallback or alert if somehow empty
        console.error("No template found");
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
    const card = button.closest('div[class*="-card"]');
    const container = card.parentElement;
    
    if (container.children.length > 1) {
        card.remove();
    } else {
        // Optional: Replace alert with SweetAlert if available, or just keep native alert for simplicity
        alert("Debe existir al menos un registro en esta sección.");
    }
}
</script>

<?php include '../../includes/form_footer.php'; ?>
