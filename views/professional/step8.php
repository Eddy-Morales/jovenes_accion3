<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
include '../../includes/form_header.php';

// Retrieve existing data
$data = $_SESSION['form_data']['step8'] ?? [];
$rowsControl = isset($data['control_autorizacion']) ? count($data['control_autorizacion']) : 1;
?>

<div class="max-w-7xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">8. FINALIZACIÓN Y REPORTES</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-blue-600 mb-4">
            <span class="bg-blue-100 px-3 py-1 rounded-full">Paso 8 de 8</span>
            <span class="text-gray-400">|</span>
            <span>Cursos y Estudiantes</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 100%"></div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Ingrese la información de cursos, estudiantes, costos y campañas.</p>
    </div>

    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-4 md:p-8 shadow-2xl border border-white/50 relative">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-400"></div>
        <input type="hidden" name="step" value="8">
        <input type="hidden" name="next_url" value="../views/professional/summary.php">
        <input type="hidden" name="school_type" value="professional">
        
        <!-- 13. CONTROL DE CURSOS AUTORIZADOS -->
        <div class="mb-10">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full text-sm">13</span>
                Control de Cursos Autorizados
            </h3>
            
            <div id="control-container" class="space-y-6">
                <!-- Dynamic cards will be manipulated by JS, but we render server-side loop for edit mode -->
                <?php for ($i = 0; $i < $rowsControl; $i++): ?>
                <div class="control-card bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative group hover:border-blue-300 transition-all">
                    <button type="button" onclick="removeCard(this)" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Autorización</label>
                            <input type="text" name="control_autorizacion[]" value="<?php echo htmlspecialchars($data['control_autorizacion'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Fecha</label>
                            <input type="date" name="control_fecha[]" value="<?php echo htmlspecialchars($data['control_fecha'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none text-gray-500">
                        </div>
                        <div>
                             <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tipo de Curso</label>
                            <input type="text" name="control_tipo_cursos[]" value="<?php echo htmlspecialchars($data['control_tipo_cursos'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: Licencia Tipo C">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 border-t border-gray-100 pt-4">
                        <div class="lg:col-span-1">
                            <span class="block text-xs font-bold text-blue-600 uppercase mb-2">Matrículas</span>
                            <div class="grid grid-cols-2 gap-2">
                                <div><label class="text-[10px] text-gray-400 uppercase">Inicio</label><input type="date" name="control_matr_ini[]" value="<?php echo htmlspecialchars($data['control_matr_ini'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-xs"></div>
                                <div><label class="text-[10px] text-gray-400 uppercase">Fin</label><input type="date" name="control_matr_fin[]" value="<?php echo htmlspecialchars($data['control_matr_fin'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-xs"></div>
                            </div>
                        </div>
                        <div class="lg:col-span-3">
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-xs font-bold text-blue-600 uppercase mb-2">Clases Teóricas</span>
                                    <div class="grid grid-cols-2 gap-2 mb-2">
                                        <div class="col-span-2 text-[10px] font-bold text-gray-400 text-center bg-gray-50 rounded">Lunes a Viernes</div>
                                        <div><label class="text-[10px] text-gray-400 uppercase">Inicio</label><input type="date" name="control_teo_lv_ini[]" value="<?php echo htmlspecialchars($data['control_teo_lv_ini'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-xs"></div>
                                        <div><label class="text-[10px] text-gray-400 uppercase">Fin</label><input type="date" name="control_teo_lv_fin[]" value="<?php echo htmlspecialchars($data['control_teo_lv_fin'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-xs"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="col-span-2 text-[10px] font-bold text-gray-400 text-center bg-gray-50 rounded">Fin de Semana</div>
                                        <div><label class="text-[10px] text-gray-400 uppercase">Inicio</label><input type="date" name="control_teo_fds_ini[]" value="<?php echo htmlspecialchars($data['control_teo_fds_ini'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-xs"></div>
                                        <div><label class="text-[10px] text-gray-400 uppercase">Fin</label><input type="date" name="control_teo_fds_fin[]" value="<?php echo htmlspecialchars($data['control_teo_fds_fin'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-xs"></div>
                                    </div>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-blue-600 uppercase mb-2">Clases Prácticas</span>
                                    <div class="grid grid-cols-2 gap-2 mb-2">
                                        <div class="col-span-2 text-[10px] font-bold text-gray-400 text-center bg-gray-50 rounded">Lunes a Viernes</div>
                                        <div><label class="text-[10px] text-gray-400 uppercase">Inicio</label><input type="date" name="control_prac_lv_ini[]" value="<?php echo htmlspecialchars($data['control_prac_lv_ini'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-xs"></div>
                                        <div><label class="text-[10px] text-gray-400 uppercase">Fin</label><input type="date" name="control_prac_lv_fin[]" value="<?php echo htmlspecialchars($data['control_prac_lv_fin'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-xs"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="col-span-2 text-[10px] font-bold text-gray-400 text-center bg-gray-50 rounded">Fin de Semana</div>
                                        <div><label class="text-[10px] text-gray-400 uppercase">Inicio</label><input type="date" name="control_prac_fds_ini[]" value="<?php echo htmlspecialchars($data['control_prac_fds_ini'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-xs"></div>
                                        <div><label class="text-[10px] text-gray-400 uppercase">Fin</label><input type="date" name="control_prac_fds_fin[]" value="<?php echo htmlspecialchars($data['control_prac_fds_fin'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-xs"></div>
                                    </div>
                                </div>
                             </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 border-t border-gray-100 pt-4">
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Alum. Autorizados</label><input type="number" name="control_alum_aut[]" value="<?php echo htmlspecialchars($data['control_alum_aut'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Alum. Matriculados</label><input type="number" name="control_alum_mat[]" value="<?php echo htmlspecialchars($data['control_alum_mat'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Alum. Graduados</label><input type="number" name="control_alum_grad[]" value="<?php echo htmlspecialchars($data['control_alum_grad'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100"></div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>

            <button type="button" onclick="addControlCard()" class="mt-4 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-medium transition-colors flex items-center gap-2 border border-blue-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Curso
            </button>
        </div>

        <hr class="my-8 border-gray-200">

        <!-- 14. REGISTRO DE ESTUDIANTES -->
        <div class="mb-10">
             <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="bg-cyan-600 text-white w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">14</span>
                    Registro de Estudiantes - Alumnos por Tipo de Curso
                </h3>
                 <button type="button" onclick="addEstudiante()" class="bg-cyan-50 text-cyan-600 hover:bg-cyan-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-cyan-200 text-xs uppercase tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Agregar Estudiante
                </button>
            </div>
            
            <div id="estudiantes-wrapper" class="space-y-8">
                <?php 
                $estudiantes = $data['estudiantes'] ?? [];
                if (empty($estudiantes)) {
                    $estudiantes = [['materias' => [[]]]];
                }
                foreach ($estudiantes as $eIndex => $estudiante): 
                ?>
                <div class="estudiante-card bg-white p-6 rounded-xl border border-gray-200 shadow-sm relative group hover:border-cyan-300 transition-all" data-index="<?php echo $eIndex; ?>">
                     <!-- Badge -->
                    <div class="absolute -top-3 -left-3 bg-gradient-to-br from-cyan-500 to-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-lg font-bold shadow-lg number-badge transform rotate-3">
                        <?php echo $eIndex + 1; ?>
                    </div>

                    <!-- Header Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="col-span-1 md:col-span-2"><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombres y Apellidos</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][nombres]" value="<?php echo htmlspecialchars($estudiante['nombres'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100"></div>
                        <div class="col-span-1 md:col-span-2"><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cédula</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][cedula]" value="<?php echo htmlspecialchars($estudiante['cedula'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100"></div>
                        
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nómina de memorando en el que consta el alumno</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][nombre_oficio]" value="<?php echo htmlspecialchars($estudiante['nombre_oficio'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100"></div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo de curso: Regular/ Convalidado</label>
                            <select name="estudiantes[<?php echo $eIndex; ?>][tipo_curso]" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100 cursor-pointer text-sm">
                                <option value="">Seleccione</option>
                                <option value="Regular" <?php echo ($estudiante['tipo_curso'] ?? '') == 'Regular' ? 'selected' : ''; ?>>Regular</option>
                                <option value="Convalidado" <?php echo ($estudiante['tipo_curso'] ?? '') == 'Convalidado' ? 'selected' : ''; ?>>Convalidado</option>
                            </select>
                        </div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tip. Licencia Posee</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][licencia_posee]" value="<?php echo htmlspecialchars($estudiante['licencia_posee'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">F. Emisión Lic 1ra</label><input type="date" name="estudiantes[<?php echo $eIndex; ?>][fecha_licencia]" value="<?php echo htmlspecialchars($estudiante['fecha_licencia'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100 text-gray-500"></div>

                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Edad</label><input type="number" name="estudiantes[<?php echo $eIndex; ?>][edad]" value="<?php echo htmlspecialchars($estudiante['edad'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Instrucción</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][nivel_instruccion]" value="<?php echo htmlspecialchars($estudiante['nivel_instruccion'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Examen Psicosensométrico</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][valoracion_psico]" value="<?php echo htmlspecialchars($estudiante['valoracion_psico'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Certificado Médico</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][certificado_medico]" value="<?php echo htmlspecialchars($estudiante['certificado_medico'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100"></div>
                         
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Matrícula</label><input type="date" name="estudiantes[<?php echo $eIndex; ?>][matricula]" value="<?php echo htmlspecialchars($estudiante['matricula'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100 text-gray-500"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Emisión Permiso Aprend.</label><input type="date" name="estudiantes[<?php echo $eIndex; ?>][emision_permiso]" value="<?php echo htmlspecialchars($estudiante['emision_permiso'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100 text-gray-500"></div>
                         <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jornadas Clase</label>
                            <select name="estudiantes[<?php echo $eIndex; ?>][jornadas]" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-100 cursor-pointer">
                                <option value="">-</option>
                                <option value="Lunes a Viernes" <?php echo ($estudiante['jornadas'] ?? '') == 'Lunes a Viernes' ? 'selected' : ''; ?>>Lunes a Viernes</option>
                                <option value="Sábado - Domingo" <?php echo ($estudiante['jornadas'] ?? '') == 'Sábado - Domingo' ? 'selected' : ''; ?>>Sábado - Domingo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Materias Section -->
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                             <h4 class="text-xs font-bold text-gray-500 uppercase flex items-center gap-2">Materias y Notas</h4>
                             <button type="button" onclick="addMateria(<?php echo $eIndex; ?>)" class="text-[10px] bg-green-50 text-green-600 hover:bg-green-100 px-3 py-1.5 rounded-lg border border-green-200 transition-colors uppercase font-bold tracking-wide flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Agregar Materia
                            </button>
                        </div>
                        
                        <div id="materias-container-<?php echo $eIndex; ?>" class="space-y-3">
                            <?php 
                            $materias = $estudiante['materias'] ?? [[]];
                            foreach ($materias as $mIndex => $materia): 
                            ?>
                            <div class="materia-row bg-white p-3 rounded-lg border border-gray-100 relative shadow-sm group/materia">
                                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                                    <div><label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Materia</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][materia]" value="<?php echo htmlspecialchars($materia['materia'] ?? ''); ?>" class="w-full px-2 py-1.5 bg-gray-50 border border-gray-200 rounded text-xs focus:ring-1 focus:ring-cyan-200"></div>
                                    <div><label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Calificaciones</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][calificaciones]" value="<?php echo htmlspecialchars($materia['calificaciones'] ?? ''); ?>" class="w-full px-2 py-1.5 bg-gray-50 border border-gray-200 rounded text-xs focus:ring-1 focus:ring-cyan-200"></div>
                                    <div><label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">% Asistencia</label><input type="number" step="0.01" name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][asistencia]" value="<?php echo htmlspecialchars($materia['asistencia'] ?? ''); ?>" class="w-full px-2 py-1.5 bg-gray-50 border border-gray-200 rounded text-xs focus:ring-1 focus:ring-cyan-200"></div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Forma Clases</label>
                                        <select name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][clase_tipo]" class="w-full px-2 py-1.5 bg-gray-50 border border-gray-200 rounded text-xs focus:ring-1 focus:ring-cyan-200">
                                            <option value="">-</option>
                                            <option value="Virtual" <?php echo ($materia['clase_tipo'] ?? '') == 'Virtual' ? 'selected' : ''; ?>>Virtual</option>
                                            <option value="Presencial" <?php echo ($materia['clase_tipo'] ?? '') == 'Presencial' ? 'selected' : ''; ?>>Presencial</option>
                                        </select>
                                    </div>
                                    <div><label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Fecha Inicio</label><input type="date" name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][fecha_inicio]" value="<?php echo htmlspecialchars($materia['fecha_inicio'] ?? ''); ?>" class="w-full px-2 py-1.5 bg-gray-50 border border-gray-200 rounded text-xs focus:ring-1 focus:ring-cyan-200 text-gray-500"></div>
                                    <div><label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Fecha Fin</label><input type="date" name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][fecha_fin]" value="<?php echo htmlspecialchars($materia['fecha_fin'] ?? ''); ?>" class="w-full px-2 py-1.5 bg-gray-50 border border-gray-200 rounded text-xs focus:ring-1 focus:ring-cyan-200 text-gray-500"></div>
                                 </div>
                                 <?php if ($mIndex > 0): ?>
                                 <button type="button" onclick="removeMateria(this)" class="absolute top-1 right-1 text-red-300 hover:text-red-500 opacity-0 group-hover/materia:opacity-100 transition-opacity">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                 </button>
                                 <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button type="button" onclick="removeEstudiante(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100" title="Eliminar Estudiante">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <hr class="my-8 border-gray-200">

        <!-- 15. COSTOS DE CURSOS -->
         <div class="mb-10">
             <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="bg-teal-600 text-white w-8 h-8 flex items-center justify-center rounded-full text-sm">15</span>
                Costos de Cursos
            </h3>
             <div id="costo-container" class="space-y-6">
                 <?php 
                 $rowsCostos = isset($data['costo_nombres']) ? count($data['costo_nombres']) : 1; 
                 for ($i = 0; $i < $rowsCostos; $i++): ?>
                 <div class="costo-card bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative group hover:border-teal-300 transition-all">
                    <button type="button" onclick="removeCard(this)" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombres y Apellidos</label><input type="text" name="costo_nombres[]" value="<?php echo htmlspecialchars($data['costo_nombres'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo curso</label><input type="text" name="costo_tipo_curso[]" value="<?php echo htmlspecialchars($data['costo_tipo_curso'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Num. Factura</label><input type="text" name="costo_num_factura[]" value="<?php echo htmlspecialchars($data['costo_num_factura'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha Factura</label><input type="date" name="costo_fecha_factura[]" value="<?php echo htmlspecialchars($data['costo_fecha_factura'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100 text-gray-500"></div>
                        
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Valor Curso (USD)</label><input type="number" step="0.01" name="costo_valor_curso[]" value="<?php echo htmlspecialchars($data['costo_valor_curso'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Valor Permiso (USD)</label><input type="number" step="0.01" name="costo_valor_permiso[]" value="<?php echo htmlspecialchars($data['costo_valor_permiso'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Valor Examen (USD)</label><input type="number" step="0.01" name="costo_valor_examen[]" value="<?php echo htmlspecialchars($data['costo_valor_examen'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100"></div>
                    </div>
                 </div>
                 <?php endfor; ?>
             </div>
             <button type="button" onclick="addCard('costo-container')" class="mt-4 px-4 py-2 bg-teal-50 text-teal-600 rounded-lg hover:bg-teal-100 font-medium transition-colors flex items-center gap-2 border border-teal-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Agregar Costo
            </button>
        </div>

        <hr class="my-8 border-gray-200">

        <!-- 16. CAMPAÑAS -->
         <div class="mb-10">
             <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="bg-indigo-600 text-white w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold">16</span>
                Campañas de Seguridad Vial
            </h3>
             <div id="camp-container" class="space-y-6">
                 <?php 
                 $rowsCamp = isset($data['camp_nro']) ? count($data['camp_nro']) : 1; 
                 for ($i = 0; $i < $rowsCamp; $i++): ?>
                 <div class="camp-card bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative group hover:border-indigo-300 transition-all">
                    <button type="button" onclick="removeCard(this)" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Campaña Nro</label><input type="text" name="camp_nro[]" value="<?php echo htmlspecialchars($data['camp_nro'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-100"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha</label><input type="date" name="camp_fecha[]" value="<?php echo htmlspecialchars($data['camp_fecha'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-100 text-gray-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Beneficiarios</label><input type="text" name="camp_beneficiarios[]" value="<?php echo htmlspecialchars($data['camp_beneficiarios'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-100"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Metodología</label><input type="text" name="camp_metodologia[]" value="<?php echo htmlspecialchars($data['camp_metodologia'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-100"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Temas</label><input type="text" name="camp_temas[]" value="<?php echo htmlspecialchars($data['camp_temas'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-100"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha Programación</label><input type="date" name="camp_fecha_programacion[]" value="<?php echo htmlspecialchars($data['camp_fecha_programacion'][$i] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-100 text-gray-500"></div>
                    </div>
                 </div>
                 <?php endfor; ?>
             </div>
             <button type="button" onclick="addCard('camp-container')" class="mt-4 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 font-medium transition-colors flex items-center gap-2 border border-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Agregar Campaña
            </button>
        </div>


        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step7.php" class="w-full md:w-auto text-gray-500 hover:text-blue-600 font-medium px-6 py-3 rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Anterior
            </a>
            
            <!-- Generate Button (Assuming this is the last step and goes to summary/generation) -->
            <button type="submit" name="finish_and_view_summary" value="1" class="w-full md:w-auto btn-premium bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:shadow-green-500/30 flex items-center justify-center gap-2 group">
                Finalizar y Generar Informe
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </button>
        </div>
    </form>
</div>

<script>
// --- General add/remove card functions ---
function addCard(containerId) {
    const container = document.getElementById(containerId);
    let template = container.querySelector('div[class*="-card"]');
    
    if(!template) { 
        // Fallback if empty - should ideally handle this better, but requires reload if all deleted
        alert("Debe existir al menos una tarjeta para clonar. Recargue la página.");
        return; 
    }
    
    const clone = template.cloneNode(true);
    clone.querySelectorAll('input').forEach(input => input.value = '');
    container.appendChild(clone);
}

function removeCard(button) {
    const card = button.closest('div[class*="-card"]');
    if(card.parentElement.children.length > 1) {
        card.remove();
    } else {
        alert("Integridad: Debe quedar al menos un registro.");
    }
}

// --- Specific for Control Cursos ---
function addControlCard() {
    addCard('control-container');
}

// --- Specific for Estudiantes (Deep Copy with Index Update) ---
function addEstudiante() {
    const wrapper = document.getElementById('estudiantes-wrapper');
    const index = wrapper.children.length; 
    
    // Copy first student
    const template = wrapper.firstElementChild.cloneNode(true);
    
    // Update badge and data-index
    template.setAttribute('data-index', index);
    const badge = template.querySelector('.number-badge');
    if(badge) badge.textContent = index + 1;
    
    // Reset main inputs
    template.querySelectorAll('input, select').forEach(input => {
        const name = input.getAttribute('name');
        if (name && name.includes('estudiantes[')) {
             // Replace first occurence of array index
             const newName = name.replace(/estudiantes\[\d+\]/, `estudiantes[${index}]`);
             input.setAttribute('name', newName);
             input.value = '';
        }
    });
    
    // Handle Materias Container
    const materiasContainer = template.querySelector('[id^="materias-container-"]');
    if (materiasContainer) {
        materiasContainer.id = `materias-container-${index}`;
        
        // Remove all rows except one
        while(materiasContainer.children.length > 1) {
            materiasContainer.lastElementChild.remove();
        }
        
        const firstRow = materiasContainer.firstElementChild;
        // Reset first row content
        firstRow.querySelectorAll('input, select').forEach(input => {
            const name = input.getAttribute('name');
             if (name) {
                 // Format: estudiantes[oldIndex][materias][0]...
                 // Update to estudiantes[NEWIndex][materias][0]
                 const newName = name.replace(/estudiantes\[\d+\]\[materias\]\[\d+\]/, `estudiantes[${index}][materias][0]`);
                 input.setAttribute('name', newName);
                 input.value = '';
            }
        });
        
        // Hide delete button for the first row of new student (optional, but good UX)
        const delBtn = firstRow.querySelector('button[onclick^="removeMateria"]');
        if(delBtn) delBtn.classList.add('hidden'); // hidden initially until checking length > 1 logic
    }
    
    // Update "Agregar Materia" button onclick to use new index
    const addMatBtn = template.querySelector('button[onclick^="addMateria"]');
    if(addMatBtn) {
        addMatBtn.setAttribute('onclick', `addMateria(${index})`);
    }
    
    wrapper.appendChild(template);
}

function removeEstudiante(button) {
    const wrapper = document.getElementById('estudiantes-wrapper');
    if (wrapper.children.length > 1) {
        button.closest('.estudiante-card').remove();
        // Re-index badges
        Array.from(wrapper.children).forEach((card, i) => {
             const badge = card.querySelector('.number-badge');
             if(badge) badge.textContent = i + 1;
        });
    } else {
        alert("Debe haber al menos un estudiante.");
    }
}

// --- Specific for Materias ---
function addMateria(estIndex) {
    const container = document.getElementById(`materias-container-${estIndex}`);
    const mIndex = container.children.length; // Next index
    
    const newRow = container.firstElementChild.cloneNode(true);
    
    newRow.querySelectorAll('input, select').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            // Logic: find [materias][X] and replace with [materias][mIndex]
            // Constraint: input name looks like estudiantes[estIndex][materias][0][field]
            // We want to replace the LAST numeric index associated with materias.
            // Regex to find [materias][digits]
            const newName = name.replace(/\[materias\]\[\d+\]/, `[materias][${mIndex}]`);
            input.setAttribute('name', newName);
            input.value = '';
        }
    });

    // Ensure delete button is visible/active
    let delBtn = newRow.querySelector('button[onclick^="removeMateria"]');
    if (delBtn) {
        delBtn.classList.remove('hidden'); // allow showing
        delBtn.style.opacity = '1'; // fix opacity CSS if dependent on group-hover/materia
    }
    
    container.appendChild(newRow);
}

function removeMateria(button) {
    const row = button.closest('.materia-row');
    if (row.parentElement.children.length > 1) {
        row.remove();
    } else {
        alert("Debe haber al menos una materia.");
    }
}
</script>

<?php include '../../includes/form_footer.php'; ?>
