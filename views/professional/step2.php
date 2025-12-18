<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
include '../../includes/form_header.php';

// Retrieve existing data
$data = $_SESSION['form_data']['step2'] ?? [];
?>

<div class="max-w-4xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-lg md:text-3xl text-gray-800 mb-2">2. INFRAESTRUCTURA</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-blue-600 mb-4">
            <span class="bg-blue-100 px-3 py-1 rounded-full">Paso 2 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Instalaciones y Equipamiento</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 22%"></div>
        </div>
    </div>

    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-4 md:p-8 shadow-2xl border border-white/50 relative">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-400"></div>
        <input type="hidden" name="step" value="2">
        <input type="hidden" name="next_url" value="../views/professional/step3.php">
        <input type="hidden" name="school_type" value="professional">

        <!-- 6.1 Aulas Pedagógicas (Dynamic Cards) -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">2.1</span>
                <span>Aulas para las clases teóricas</span>
            </h3>
            
            <div id="aulasContainer" class="space-y-6">
                <!-- Cards will be added here via JS -->
            </div>

            <button type="button" onclick="addAulaCard()" class="mt-6 w-full md:w-auto text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 font-medium rounded-xl px-4 py-3 text-sm flex items-center justify-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Aula
            </button>
        </div>

        <!-- 6.2 Aula Taller (Responsive List) -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">2.2</span>
                <span>TALLERES (Aula Taller)</span>
            </h3>

            <!-- Course Type Selectors -->
            <div class="bg-blue-50/50 p-4 rounded-t-xl border-x border-t border-gray-200 mb-0">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-blue-700 uppercase mb-1">Tipo de Curso 1</label>
                        <select name="taller_curso_1" id="taller_curso_1" onchange="updateCourseLabels()" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                            <option value="">Seleccione</option>
                            <?php 
                            $tipos = ['A1', 'C', 'C1', 'D', 'E', 'G'];
                            foreach ($tipos as $tipo) {
                                $selected = ($data['taller_curso_1'] ?? '') == $tipo ? 'selected' : '';
                                echo "<option value=\"$tipo\" $selected>$tipo</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-blue-700 uppercase mb-1">Tipo de Curso 2</label>
                        <select name="taller_curso_2" id="taller_curso_2" onchange="updateCourseLabels()" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                            <option value="">Seleccione</option>
                            <?php 
                            foreach ($tipos as $tipo) {
                                $selected = ($data['taller_curso_2'] ?? '') == $tipo ? 'selected' : '';
                                echo "<option value=\"$tipo\" $selected>$tipo</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white/50 rounded-b-xl border border-gray-200 divide-y divide-gray-100">
                <!-- Material pedagógico -->
                <div class="p-4">
                    <div class="mb-2 font-medium text-gray-700 text-sm">Material pedagógico</div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative bg-blue-50/30 rounded-lg p-2 border border-blue-50 transition-colors hover:border-blue-100">
                            <label class="block text-[10px] text-blue-600 font-bold mb-1 label-c1">Curso 1:</label>
                            <select name="taller_material_c1" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-blue-100 outline-none">
                                <option value="">Seleccione</option>
                                <option value="SI" <?php echo ($data['taller_material_c1'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                                <option value="NO" <?php echo ($data['taller_material_c1'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                            </select>
                        </div>
                        <div class="relative bg-cyan-50/30 rounded-lg p-2 border border-cyan-50 transition-colors hover:border-cyan-100">
                            <label class="block text-[10px] text-cyan-600 font-bold mb-1 label-c2">Curso 2:</label>
                            <select name="taller_material_c2" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-cyan-100 outline-none">
                                <option value="">Seleccione</option>
                                <option value="SI" <?php echo ($data['taller_material_c2'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                                <option value="NO" <?php echo ($data['taller_material_c2'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Diagramas y esquemas -->
                <div class="p-4">
                    <div class="mb-2 font-medium text-gray-700 text-sm">Diagramas y esquemas</div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative bg-blue-50/30 rounded-lg p-2 border border-blue-50 transition-colors hover:border-blue-100">
                            <label class="block text-[10px] text-blue-600 font-bold mb-1 label-c1">Curso 1:</label>
                            <select name="taller_diagramas_c1" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-blue-100 outline-none">
                                <option value="">Seleccione</option>
                                <option value="SI" <?php echo ($data['taller_diagramas_c1'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                                <option value="NO" <?php echo ($data['taller_diagramas_c1'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                            </select>
                        </div>
                        <div class="relative bg-cyan-50/30 rounded-lg p-2 border border-cyan-50 transition-colors hover:border-cyan-100">
                            <label class="block text-[10px] text-cyan-600 font-bold mb-1 label-c2">Curso 2:</label>
                            <select name="taller_diagramas_c2" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-cyan-100 outline-none">
                                <option value="">Seleccione</option>
                                <option value="SI" <?php echo ($data['taller_diagramas_c2'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                                <option value="NO" <?php echo ($data['taller_diagramas_c2'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Panel de instrumentos -->
                <div class="p-4">
                    <div class="mb-2 font-medium text-gray-700 text-sm">Panel de instrumentos</div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative bg-blue-50/30 rounded-lg p-2 border border-blue-50 transition-colors hover:border-blue-100">
                            <label class="block text-[10px] text-blue-600 font-bold mb-1 label-c1">Curso 1:</label>
                            <select name="taller_panel_c1" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-blue-100 outline-none">
                                <option value="">Seleccione</option>
                                <option value="SI" <?php echo ($data['taller_panel_c1'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                                <option value="NO" <?php echo ($data['taller_panel_c1'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                            </select>
                        </div>
                        <div class="relative bg-cyan-50/30 rounded-lg p-2 border border-cyan-50 transition-colors hover:border-cyan-100">
                            <label class="block text-[10px] text-cyan-600 font-bold mb-1 label-c2">Curso 2:</label>
                            <select name="taller_panel_c2" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-cyan-100 outline-none">
                                <option value="">Seleccione</option>
                                <option value="SI" <?php echo ($data['taller_panel_c2'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                                <option value="NO" <?php echo ($data['taller_panel_c2'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Fosa o elevador -->
                <div class="p-4">
                    <div class="mb-2 font-medium text-gray-700 text-sm">Fosa o elevador</div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative bg-blue-50/30 rounded-lg p-2 border border-blue-50 transition-colors hover:border-blue-100">
                            <label class="block text-[10px] text-blue-600 font-bold mb-1 label-c1">Curso 1:</label>
                            <select name="taller_fosa_c1" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-blue-100 outline-none">
                                <option value="">Seleccione</option>
                                <option value="SI" <?php echo ($data['taller_fosa_c1'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                                <option value="NO" <?php echo ($data['taller_fosa_c1'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                            </select>
                        </div>
                        <div class="relative bg-cyan-50/30 rounded-lg p-2 border border-cyan-50 transition-colors hover:border-cyan-100">
                            <label class="block text-[10px] text-cyan-600 font-bold mb-1 label-c2">Curso 2:</label>
                            <select name="taller_fosa_c2" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-cyan-100 outline-none">
                                <option value="">Seleccione</option>
                                <option value="SI" <?php echo ($data['taller_fosa_c2'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                                <option value="NO" <?php echo ($data['taller_fosa_c2'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Herramientas básicas -->
                <div class="p-4">
                    <div class="mb-2 font-medium text-gray-700 text-sm">Herramientas básicas</div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative bg-blue-50/30 rounded-lg p-2 border border-blue-50 transition-colors hover:border-blue-100">
                            <label class="block text-[10px] text-blue-600 font-bold mb-1 label-c1">Curso 1:</label>
                            <select name="taller_herramientas_c1" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-blue-100 outline-none">
                                <option value="">Seleccione</option>
                                <option value="SI" <?php echo ($data['taller_herramientas_c1'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                                <option value="NO" <?php echo ($data['taller_herramientas_c1'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                            </select>
                        </div>
                        <div class="relative bg-cyan-50/30 rounded-lg p-2 border border-cyan-50 transition-colors hover:border-cyan-100">
                            <label class="block text-[10px] text-cyan-600 font-bold mb-1 label-c2">Curso 2:</label>
                            <select name="taller_herramientas_c2" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-cyan-100 outline-none">
                                <option value="">Seleccione</option>
                                <option value="SI" <?php echo ($data['taller_herramientas_c2'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                                <option value="NO" <?php echo ($data['taller_herramientas_c2'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Motores en corte (art 8, literal f) -->
                <div class="p-4">
                    <div class="mb-2 font-medium text-gray-700 text-sm">Motores en corte (art 8, literal f)</div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative bg-blue-50/30 rounded-lg p-2 border border-blue-50 transition-colors hover:border-blue-100">
                            <label class="block text-[10px] text-blue-600 font-bold mb-1 label-c1">Curso 1:</label>
                            <select name="taller_motores_c1" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-blue-100 outline-none">
                                <option value="">Seleccione</option>
                                <option value="SI" <?php echo ($data['taller_motores_c1'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                                <option value="NO" <?php echo ($data['taller_motores_c1'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                            </select>
                        </div>
                        <div class="relative bg-cyan-50/30 rounded-lg p-2 border border-cyan-50 transition-colors hover:border-cyan-100">
                            <label class="block text-[10px] text-cyan-600 font-bold mb-1 label-c2">Curso 2:</label>
                            <select name="taller_motores_c2" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-cyan-100 outline-none">
                                <option value="">Seleccione</option>
                                <option value="SI" <?php echo ($data['taller_motores_c2'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                                <option value="NO" <?php echo ($data['taller_motores_c2'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<script>
function updateCourseLabels() {
    const c1 = document.getElementById('taller_curso_1').value || 'Curso 1';
    const c2 = document.getElementById('taller_curso_2').value || 'Curso 2';
    
    document.querySelectorAll('.label-c1').forEach(el => el.textContent = c1 + ':');
    document.querySelectorAll('.label-c2').forEach(el => el.textContent = c2 + ':');
}
document.addEventListener('DOMContentLoaded', updateCourseLabels);
</script>

        <!-- 6.3 Áreas Administrativas (Responsive List) -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">2.3</span>
                <span>Áreas administrativas</span>
            </h3>
            <div class="bg-white/50 rounded-xl border border-gray-200 divide-y divide-gray-100">
                <!-- Recepción -->
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <span class="font-medium text-gray-700 text-sm">Recepción</span>
                    <select name="admin_recepcion" class="w-full sm:w-32 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['admin_recepcion'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['admin_recepcion'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>

                <!-- Inspección -->
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <span class="font-medium text-gray-700 text-sm">Inspección</span>
                    <select name="admin_inspeccion" class="w-full sm:w-32 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['admin_inspeccion'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['admin_inspeccion'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>

                <!-- Dirección -->
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <span class="font-medium text-gray-700 text-sm">Dirección</span>
                    <select name="admin_direccion" class="w-full sm:w-32 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['admin_direccion'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['admin_direccion'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>

                <!-- Sala de profesores -->
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <span class="font-medium text-gray-700 text-sm">Sala de profesores</span>
                    <select name="admin_sala_profesores" class="w-full sm:w-32 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['admin_sala_profesores'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['admin_sala_profesores'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>

                <!-- Sala de espera -->
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <span class="font-medium text-gray-700 text-sm">Sala de espera</span>
                    <select name="admin_sala_espera" class="w-full sm:w-32 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['admin_sala_espera'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['admin_sala_espera'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>

                <!-- Archivo -->
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <span class="font-medium text-gray-700 text-sm">Archivo</span>
                    <select name="admin_archivo" class="w-full sm:w-32 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['admin_archivo'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['admin_archivo'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>

                <!-- Departamento Contable -->
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <span class="font-medium text-gray-700 text-sm">Departamento Contable</span>
                    <select name="admin_contable" class="w-full sm:w-32 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['admin_contable'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['admin_contable'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>

                <!-- Secretaria General -->
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <span class="font-medium text-gray-700 text-sm">Secretaria General</span>
                    <select name="admin_secretaria" class="w-full sm:w-32 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['admin_secretaria'] ?? '') === 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['admin_secretaria'] ?? '') === 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
            </div>
        <!-- 2.4 Expedientes de Cursos en Período (Dynamic) -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">2.4</span>
                <span>Expedientes de cursos en período de capacitación</span>
            </h3>
            
            <div id="expedientesCursoContainer" class="space-y-6">
                <!-- Cards will be injected here -->
            </div>

            <button type="button" onclick="addExpedienteCard('curso')" class="mt-6 w-full md:w-auto text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 font-medium rounded-xl px-4 py-3 text-sm flex items-center justify-center gap-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Expediente de Curso
            </button>
        </div>

        <!-- 2.5 Expedientes de Alumnos Graduados (Dynamic) -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">2.5</span>
                <span>Expedientes de cursos de alumnos graduados</span>
            </h3>
            
            <div id="expedientesGraduadosContainer" class="space-y-6">
                <!-- Cards will be injected here -->
            </div>

            <button type="button" onclick="addExpedienteCard('graduado')" class="mt-6 w-full md:w-auto text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 font-medium rounded-xl px-4 py-3 text-sm flex items-center justify-center gap-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Expediente de Graduado
            </button>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step1.php" class="w-full md:w-auto text-gray-500 hover:text-blue-600 font-medium px-6 py-3 rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Anterior
            </a>
            <button type="submit" class="w-full md:w-auto btn-premium bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:shadow-blue-500/30 flex items-center justify-center gap-2 group">
                Siguiente Paso
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>
</div>

<!-- Template for Aula Card -->
<template id="aulaCardTemplate">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 relative group transition-all hover:shadow-md animate-fade-in-up">
        <button type="button" onclick="removeAulaCard(this)" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        
        <h4 class="text-blue-600 font-bold mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Aula #<span class="aula-number">1</span>
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Basic Info -->
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Identificador / Número de Aula</label>
                    <input type="text" name="aulas[index][nro]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: Aula 1, B-102">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Número de alumnos por aula</label>
                    <input type="number" name="aulas[index][alumnos]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: 25">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Autorizada en Resolución Nro.</label>
                    <input type="text" name="aulas[index][resolucion]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: ANT-2024-001">
                </div>
                 <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Material Pedagógico</label>
                    <input type="text" name="aulas[index][material]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: Pizarrón, Proyector, Pupitres">
                </div>
            </div>

            <!-- Specs & Cameras -->
            <div class="space-y-4">
                <div class="bg-blue-50/50 p-3 rounded-lg border border-blue-100">
                    <label class="block text-xs font-bold text-blue-700 uppercase mb-2 text-center">Sistema de Cámaras</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="text-[10px] font-semibold text-gray-600 block mb-1">¿Tiene cámara?</span>
                            <select name="aulas[index][cam_tiene]" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs">
                                <option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option>
                            </select>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-gray-600 block mb-1">¿Conectada en tiempo real?</span>
                            <select name="aulas[index][cam_conectada]" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs">
                                <option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option>
                            </select>
                        </div>
                        <div>
                             <span class="text-[10px] font-semibold text-gray-600 block mb-1">¿Permite acceso en tiempo real?</span>
                            <select name="aulas[index][cam_acceso]" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs">
                                <option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option>
                            </select>
                        </div>
                        <div>
                             <span class="text-[10px] font-semibold text-gray-600 block mb-1">Frecuencia de acceso</span>
                            <select name="aulas[index][cam_frecuencia]" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs">
                                <option value="">Seleccione</option><option value="Semanal">Semanal</option><option value="Quincenal">Quincenal</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <label class="flex flex-col items-center justify-center p-2 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" name="aulas[index][proyector]" value="SI" class="mb-1 text-blue-600 rounded focus:ring-blue-500">
                        <span class="text-[10px] font-medium text-gray-600">Proyector</span>
                    </label>
                    <label class="flex flex-col items-center justify-center p-2 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" name="aulas[index][computador]" value="SI" class="mb-1 text-blue-600 rounded focus:ring-blue-500">
                        <span class="text-[10px] font-medium text-gray-600">PC</span>
                    </label>
                    <label class="flex flex-col items-center justify-center p-2 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" name="aulas[index][lista]" value="SI" class="mb-1 text-blue-600 rounded focus:ring-blue-500">
                        <span class="text-[10px] font-medium text-gray-600 text-center leading-tight">Lista de alumnos publicada</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Template for Expediente Card -->
<template id="expedienteCardTemplate">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 relative group transition-all hover:shadow-md animate-fade-in-up">
        <button type="button" onclick="this.closest('.bg-white').remove()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors bg-white rounded-full p-1 border border-gray-100 shadow-sm">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Curso / Período</label>
                    <input type="text" name="type_placeholder[index][curso]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: Licencia Tipo C - 2024">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Archivo Tipo</label>
                    <input type="text" name="type_placeholder[index][archivo]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: Físico / Digital">
                </div>
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Expedientes de administrativos</label>
                     <select name="type_placeholder[index][exp_admin]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm border focus:ring-2 focus:ring-blue-100 outline-none">
                        <option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option>
                    </select>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Expedientes de Alumnos</label>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">Registro de asistencia</span>
                            <select name="type_placeholder[index][reg_asistencia]" class="w-24 bg-white border border-gray-200 rounded px-2 py-1 text-xs outline-none focus:ring-1 focus:ring-blue-100"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">Registro de Notas</span>
                            <select name="type_placeholder[index][reg_notas]" class="w-24 bg-white border border-gray-200 rounded px-2 py-1 text-xs outline-none focus:ring-1 focus:ring-blue-100"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">Documentos previos</span>
                            <select name="type_placeholder[index][docs_previos]" class="w-24 bg-white border border-gray-200 rounded px-2 py-1 text-xs outline-none focus:ring-1 focus:ring-blue-100"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50/50 p-3 rounded-lg border border-blue-100">
                    <label class="block text-xs font-bold text-blue-700 uppercase mb-2">Curso de Capacitación</label>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">Cronograma de actividades</span>
                            <select name="type_placeholder[index][cronograma]" class="w-24 bg-white border border-gray-200 rounded px-2 py-1 text-xs outline-none focus:ring-1 focus:ring-blue-100"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">Expedientes de docentes</span>
                            <select name="type_placeholder[index][exp_docentes]" class="w-24 bg-white border border-gray-200 rounded px-2 py-1 text-xs outline-none focus:ring-1 focus:ring-blue-100"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">Expedientes de Instructores</span>
                            <select name="type_placeholder[index][exp_instructores]" class="w-24 bg-white border border-gray-200 rounded px-2 py-1 text-xs outline-none focus:ring-1 focus:ring-blue-100"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
let aulaIndex = 0;
let expCursoIndex = 0;
let expGraduadoIndex = 0;

const aulasContainer = document.getElementById('aulasContainer');
const existingAulas = <?php echo json_encode($data['aulas'] ?? []); ?>;

// Get data for new sections
const existingExpCursos = <?php echo json_encode($data['exp_cursos'] ?? []); ?>;
const existingExpGraduados = <?php echo json_encode($data['exp_graduados'] ?? []); ?>;

function addAulaCard(data = null) {
    const template = document.getElementById('aulaCardTemplate');
    const clone = template.content.cloneNode(true);
    const card = clone.querySelector('div');
    
    // Update numbering
    const numberSpan = card.querySelector('.aula-number');
    numberSpan.textContent = aulaIndex + 1;

    // Replace index placeholder
    const inputs = card.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.name = input.name.replace('index', aulaIndex);
        
        // Populate if data exists
        if (data) {
            const keys = input.name.match(/\[(\w+)\]$/);
            if (keys && keys[1]) {
                const key = keys[1];
                if (input.type === 'checkbox') {
                    if (data[key] === 'SI') input.checked = true;
                } else {
                    input.value = data[key] || '';
                }
            }
        }
    });

    aulasContainer.appendChild(card);
    aulaIndex++;
}

function addExpedienteCard(type, data = null) {
    const template = document.getElementById('expedienteCardTemplate');
    const clone = template.content.cloneNode(true);
    const card = clone.querySelector('div');
    const inputs = card.querySelectorAll('input, select');
    const container = type === 'curso' ? document.getElementById('expedientesCursoContainer') : document.getElementById('expedientesGraduadosContainer');
    const index = type === 'curso' ? expCursoIndex : expGraduadoIndex;
    const arrayName = type === 'curso' ? 'exp_cursos' : 'exp_graduados';
    
    inputs.forEach(input => {
        input.name = input.name.replace('type_placeholder', arrayName).replace('index', index);
        
         if (data) {
            const keys = input.name.match(/\[(\w+)\]$/);
            if (keys && keys[1]) {
                const key = keys[1];
                input.value = data[key] || '';
            }
        }
    });
    
    container.appendChild(card);
    if(type === 'curso') expCursoIndex++; else expGraduadoIndex++;
}

function removeAulaCard(btn) {
    const card = btn.closest('.bg-white');
    card.remove();
}

// Initialize Logic
if (Object.keys(existingAulas).length > 0) {
    Object.values(existingAulas).forEach(aula => addAulaCard(aula));
} else {
    addAulaCard(); // Add one empty card by default
}

if (Object.keys(existingExpCursos).length > 0) {
    Object.values(existingExpCursos).forEach(item => addExpedienteCard('curso', item));
} else {
    addExpedienteCard('curso');
}

if (Object.keys(existingExpGraduados).length > 0) {
    Object.values(existingExpGraduados).forEach(item => addExpedienteCard('graduado', item));
} else {
    addExpedienteCard('graduado');
}
</script>

<?php include '../../includes/form_footer.php'; ?>
