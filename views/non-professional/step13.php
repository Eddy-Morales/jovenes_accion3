<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

$data = $_SESSION['form_data']['step13'] ?? [];
$next_url = "../views/non-professional/summary.php";
?>

<div class="max-w-7xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Estudiantes y Certificación</h2>
         <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 13 de 13</span>
            <span class="text-gray-400">|</span>
            <span>Finalización</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
             <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 100%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden space-y-12">
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="13">
        <input type="hidden" name="next_url" value="<?php echo $next_url; ?>">

        <!-- 13. ESTUDIANTES -->
        <div>
            <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">1</span>
                    13. REGISTRO DE ESTUDIANTES
                </h3>
                 <button type="button" onclick="addEstudiante()" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
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
                <div class="estudiante-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm transition-all hover:shadow-md" data-index="<?php echo $eIndex; ?>">
                     <!-- Badge -->
                    <div class="absolute -top-3 -left-3 bg-gradient-to-br from-blue-500 to-purple-600 text-white w-8 h-8 flex items-center justify-center rounded-lg font-bold shadow-lg number-badge transform rotate-3">
                        <?php echo $eIndex + 1; ?>
                    </div>

                    <!-- Header Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                        <div class="col-span-1 md:col-span-2"><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombres y Apellidos</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][nombres]" value="<?php echo htmlspecialchars($estudiante['nombres'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div class="col-span-1 md:col-span-2"><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cédula</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][cedula]" value="<?php echo htmlspecialchars($estudiante['cedula'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombre Oficio</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][nombre_oficio]" value="<?php echo htmlspecialchars($estudiante['nombre_oficio'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo curso</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][tipo_curso]" value="<?php echo htmlspecialchars($estudiante['tipo_curso'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fichas Teóricas</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][fichas_teoricas]" value="<?php echo htmlspecialchars($estudiante['fichas_teoricas'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fichas Prácticas</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][fichas_practicas]" value="<?php echo htmlspecialchars($estudiante['fichas_practicas'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha Nacimiento</label><input type="date" name="estudiantes[<?php echo $eIndex; ?>][fecha_nacimiento]" value="<?php echo htmlspecialchars($estudiante['fecha_nacimiento'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-gray-700"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Edad</label><input type="number" name="estudiantes[<?php echo $eIndex; ?>][edad]" value="<?php echo htmlspecialchars($estudiante['edad'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Instrucción</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][nivel_instruccion]" value="<?php echo htmlspecialchars($estudiante['nivel_instruccion'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Val. Psicosensométrica</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][valoracion_psico]" value="<?php echo htmlspecialchars($estudiante['valoracion_psico'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Val. Psicológica</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][valoracion_psicologica]" value="<?php echo htmlspecialchars($estudiante['valoracion_psicologica'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Matrícula</label><input type="date" name="estudiantes[<?php echo $eIndex; ?>][matricula]" value="<?php echo htmlspecialchars($estudiante['matricula'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-gray-700"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Emisión Permiso</label><input type="date" name="estudiantes[<?php echo $eIndex; ?>][emision_permiso]" value="<?php echo htmlspecialchars($estudiante['emision_permiso'] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-gray-700"></div>
                         <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jornadas Clase</label>
                            <select name="estudiantes[<?php echo $eIndex; ?>][jornadas]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                <option value="">-</option>
                                <option value="Lunes a Viernes" <?php echo ($estudiante['jornadas'] ?? '') == 'Lunes a Viernes' ? 'selected' : ''; ?>>Lunes a Viernes</option>
                                <option value="Fin de Semana" <?php echo ($estudiante['jornadas'] ?? '') == 'Fin de Semana' ? 'selected' : ''; ?>>Fin de Semana</option>
                            </select>
                        </div>
                    </div>

                    <!-- Materias Section -->
                    <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200/50">
                        <div class="flex justify-between items-center mb-4">
                             <h4 class="text-xs font-bold text-gray-500 uppercase flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                Materias
                             </h4>
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
                            <div class="materia-row bg-white p-4 rounded-lg border border-gray-100 relative shadow-sm group/materia">
                                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                                    <div><label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Materia</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][materia]" value="<?php echo htmlspecialchars($materia['materia'] ?? ''); ?>" class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-purple-500"></div>
                                    <div><label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Calificaciones</label><input type="text" name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][calificaciones]" value="<?php echo htmlspecialchars($materia['calificaciones'] ?? ''); ?>" class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-purple-500"></div>
                                    <div><label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">% Asistencia</label><input type="number" step="0.01" name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][asistencia]" value="<?php echo htmlspecialchars($materia['asistencia'] ?? ''); ?>" class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-purple-500"></div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Modalidad</label>
                                        <select name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][clase_tipo]" class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-purple-500">
                                            <option value="">-</option>
                                            <option value="Virtual" <?php echo ($materia['clase_tipo'] ?? '') == 'Virtual' ? 'selected' : ''; ?>>Virtual</option>
                                            <option value="Presencial" <?php echo ($materia['clase_tipo'] ?? '') == 'Presencial' ? 'selected' : ''; ?>>Presencial</option>
                                        </select>
                                    </div>
                                    <div><label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Fecha Inicio</label><input type="date" name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][fecha_inicio]" value="<?php echo htmlspecialchars($materia['fecha_inicio'] ?? ''); ?>" class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-purple-500 text-gray-600"></div>
                                    <div><label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Fecha Fin</label><input type="date" name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][fecha_fin]" value="<?php echo htmlspecialchars($materia['fecha_fin'] ?? ''); ?>" class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-purple-500 text-gray-600"></div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Estado</label>
                                        <select name="estudiantes[<?php echo $eIndex; ?>][materias][<?php echo $mIndex; ?>][estado]" class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-purple-500">
                                            <option value="">-</option>
                                            <option value="Aprobado" <?php echo ($materia['estado'] ?? '') == 'Aprobado' ? 'selected' : ''; ?>>Aprobado</option>
                                            <option value="Reprobado" <?php echo ($materia['estado'] ?? '') == 'Reprobado' ? 'selected' : ''; ?>>Reprobado</option>
                                        </select>
                                    </div>
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

        <!-- 14. COSTOS -->
        <div>
             <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">2</span>
                    14. COSTOS DE CURSOS
                </h3>
                 <button type="button" onclick="addCard('costo-container')" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Agregar Costo
                </button>
            </div>
             <div id="costo-container" class="space-y-6">
                 <?php // Logic for costs loop...
                 $rowsCostos = isset($data['costo_nombres']) ? count($data['costo_nombres']) : 1; 
                 for ($i = 0; $i < $rowsCostos; $i++): ?>
                 <div class="costo-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombre Alumno</label><input type="text" name="costo_nombres[]" value="<?php echo htmlspecialchars($data['costo_nombres'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo curso</label><input type="text" name="costo_tipo_curso[]" value="<?php echo htmlspecialchars($data['costo_tipo_curso'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Num. Factura</label><input type="text" name="costo_num_factura[]" value="<?php echo htmlspecialchars($data['costo_num_factura'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha Factura</label><input type="date" name="costo_fecha_factura[]" value="<?php echo htmlspecialchars($data['costo_fecha_factura'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-gray-600"></div>
                        
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Valor Curso (USD)</label><input type="number" step="0.01" name="costo_valor_curso[]" value="<?php echo htmlspecialchars($data['costo_valor_curso'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Valor Permiso (USD)</label><input type="number" step="0.01" name="costo_valor_permiso[]" value="<?php echo htmlspecialchars($data['costo_valor_permiso'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Valor Examen (USD)</label><input type="number" step="0.01" name="costo_valor_examen[]" value="<?php echo htmlspecialchars($data['costo_valor_examen'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                    </div>
                     <button type="button" onclick="removeCard(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                 </div>
                 <?php endfor; ?>
             </div>
        </div>

        <!-- 15. CERTIFICADO -->
        <div>
             <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2 border-b border-gray-200 pb-2 mb-6">
                <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">3</span>
                15. FORMATO DEL CERTIFICADO DE CONDUCTOR
            </h3>
            <div class="bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative backdrop-blur-sm">
                 <div class="space-y-4">
                     <!-- Helper for certificate rows -->
                     <?php 
                     function certRow($label, $name, $data) {
                         $val = $data[$name] ?? '';
                         echo '<div class="grid grid-cols-3 gap-4 items-center border-b border-gray-50 pb-3 last:border-0 hover:bg-white/50 p-2 rounded-lg transition-colors">';
                         echo '<div class="col-span-2 text-sm font-medium text-gray-600">'.$label.'</div>';
                         echo '<div><select name="'.$name.'" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 cursor-pointer text-sm">';
                         echo '<option value="">- Seleccione -</option>';
                         echo '<option value="Contiene" '.($val == 'Contiene' ? 'selected' : '').'>Contiene</option>';
                         echo '<option value="No contiene" '.($val == 'No contiene' ? 'selected' : '').'>No contiene</option>';
                         echo '</select></div>';
                         echo '</div>';
                     }
                     certRow("Nombre de la Escuela de Conducción", "cert_contiene_nombre", $data);
                     certRow("Número de Resolución de Autorización de Funcionamiento", "cert_contiene_resolucion", $data);
                     certRow("Domicilio de la Escuela de Conducción", "cert_contiene_domicilio", $data);
                     certRow("Número del título o del certificado", "cert_contiene_titulo", $data);
                     certRow("Nombres y apellidos del estudiante", "cert_contiene_estudiante", $data);
                     certRow("Fecha de inicio y fin de curso", "cert_contiene_fecha", $data);
                     certRow("Categoría y tipo de licencia", "cert_contiene_categoria", $data);
                     certRow("Tipo de curso", "cert_contiene_tipo", $data);
                     certRow("Firmas de responsabilidad", "cert_contiene_firmas", $data);
                     certRow("Lugar y fecha de emisión", "cert_contiene_lugar", $data);
                     certRow("Calificación final", "cert_contiene_calificacion", $data);
                     ?>
                 </div>
            </div>
        </div>

        <!-- 16. CAMPAÑAS -->
        <div>
             <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">4</span>
                    16. CAMPAÑAS DE SEGURIDAD VIAL
                </h3>
                 <button type="button" onclick="addCard('camp-container')" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-indigo-200 text-xs uppercase tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Agregar Campaña
                </button>
            </div>
             <div id="camp-container" class="space-y-6">
                 <?php 
                 $rowsCamp = isset($data['camp_nro']) ? count($data['camp_nro']) : 1; 
                 for ($i = 0; $i < $rowsCamp; $i++): ?>
                 <div class="camp-card bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative group backdrop-blur-sm hover:shadow-md transition-all">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Campaña Nro</label><input type="text" name="camp_nro[]" value="<?php echo htmlspecialchars($data['camp_nro'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha</label><input type="date" name="camp_fecha[]" value="<?php echo htmlspecialchars($data['camp_fecha'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-gray-600"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Beneficiarios</label><input type="text" name="camp_beneficiarios[]" value="<?php echo htmlspecialchars($data['camp_beneficiarios'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Metodología</label><input type="text" name="camp_metodologia[]" value="<?php echo htmlspecialchars($data['camp_metodologia'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                        <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Temas</label><input type="text" name="camp_temas[]" value="<?php echo htmlspecialchars($data['camp_temas'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500"></div>
                         <div><label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha Programación</label><input type="date" name="camp_fecha_programacion[]" value="<?php echo htmlspecialchars($data['camp_fecha_programacion'][$i] ?? ''); ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 text-gray-600"></div>
                    </div>
                     <button type="button" onclick="removeCard(this)" class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                 </div>
                 <?php endfor; ?>
             </div>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step12.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Anterior
            </a>
            <button type="submit" class="w-full md:w-auto btn-premium text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:shadow-purple-500/30 flex items-center justify-center gap-2 group">
                Finalizar
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>
</div>

<script>
// Generic functions for Costos & Campañas
function addCard(containerId) {
    const container = document.getElementById(containerId);
    const template = container.querySelector('div[class*="-card"]');
    
    if(!template && container.children.length === 0) {
        alert("Recargue la página para recuperar los campos."); // Simple fallback
        return;
    }

    const newCard = template.cloneNode(true);
    newCard.querySelectorAll('input, select, textarea').forEach(input => {
        if(input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
        } else {
            input.value = '';
        }
    });
    container.appendChild(newCard);
}

function removeCard(button) {
    const card = button.closest('div[class*="-card"]');
    if (card.parentElement.children.length > 1) {
        card.remove();
    } else {
        alert('Debe mantener al menos un registro.');
    }
}

// Specific functions for Students (Estudiantes)
function addEstudiante() {
    const wrapper = document.getElementById('estudiantes-wrapper');
    const index = wrapper.children.length; 
    
    // Copy first student
    const template = wrapper.firstElementChild.cloneNode(true);
    
    // Update badge and index attribute
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
        
        // Remove delete button if exists in first row
        const delBtn = firstRow.querySelector('button[onclick^="removeMateria"]');
        if(delBtn) delBtn.classList.add('hidden'); // or remove
    }
    
    // Update "Agregar Materia" button
    const addMatBtn = template.querySelector('button[onclick^="addMateria"]');
    if(addMatBtn) addMatBtn.setAttribute('onclick', `addMateria(${index})`);
    
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

function addMateria(estIndex) {
    const container = document.getElementById(`materias-container-${estIndex}`);
    const mIndex = container.children.length;
    
    const newRow = container.firstElementChild.cloneNode(true);
    
    newRow.querySelectorAll('input, select').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            // Replace [materias][0] with [materias][mIndex]
            const newName = name.replace(/\[materias\]\[\d+\]/, `[materias][${mIndex}]`);
            input.setAttribute('name', newName);
            input.value = '';
        }
    });


    // Ensure delete button is visible and active
    const delBtn = newRow.querySelector('button[onclick^="removeMateria"]');
    if (delBtn) {
        delBtn.classList.remove('hidden');
        delBtn.setAttribute('onclick', 'removeMateria(this)');
    } else {
         // Create if missing
         // ... simplified logic: standard clone should generally have it if existing rows have it, 
         // but if first row hid it, we need to show it.
         // In PHP loop above, first row (index 0) has no button. So we need to ADD IT.
         const btn = document.createElement('button');
         btn.type = 'button';
         btn.setAttribute('onclick', 'removeMateria(this)');
         btn.className = 'absolute top-1 right-1 text-red-300 hover:text-red-500 opacity-0 group-hover/materia:opacity-100 transition-opacity';
         btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>';
         newRow.appendChild(btn);
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
