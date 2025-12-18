<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

$data = $_SESSION['form_data']['step11'] ?? [];
$next_url = "../views/non-professional/step12.php";

// Helper for count, effectively 1 as per original logic, but robust if data exists.
// Original file had $rows = 1 hardcoded. We will respect that but check data to be safe in case future logic needs it.
// Actually, strict adherence to original: it had $rows = 1; so we keep 1.
$rowsExpedientes = 1;
$rowsDirAdm = 1;
$rowsDirGen = 1;
$rowsAsesor = 1;
$rowsAsesor2 = 1;
$rowsInspSup = 1;
$rowsSup = 1;
$rowsSec = 1;
$rowsSec2 = 1;
$rowsCont = 1;
$rowsTes = 1;
$rowsPsic = 1;
$rowsPsicEdu = 1;
$rowsEval = 1;
?>

<div class="max-w-7xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Personal y Expedientes</h2>
         <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 11 de 12</span>
            <span class="text-gray-400">|</span>
            <span>Talento Humano</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
             <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 100%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden space-y-8">
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="11">
        <input type="hidden" name="next_url" value="<?php echo $next_url; ?>">

        <!-- Section Template Function -->
        <?php 
        function renderSection($title, $prefix, $data, $fields) {
            echo '<div class="bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative backdrop-blur-sm transition-all hover:shadow-md">';
            echo '<h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">';
            echo '<span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">#</span>';
            echo $title;
            echo '</h3>';
            echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">';
            
            foreach ($fields as $field) {
                $name = $prefix . '_' . $field['name'] . '[]';
                $value = htmlspecialchars($data[$prefix . '_' . $field['name']][0] ?? '');
                $label = $field['label'];
                
                echo '<div>';
                echo '<label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1.5" title="'.$label.'">'.$label.'</label>';
                
                if ($field['type'] === 'select') {
                    echo '<select name="'.$name.'" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">';
                    echo '<option value="">- Seleccione -</option>';
                    foreach ($field['options'] as $opt) {
                         $selected = ($data[$prefix . '_' . $field['name']][0] ?? '') == $opt ? 'selected' : '';
                        // Special case for Yes/No with custom labels if needed, simplifies to just value display
                        $display = $opt;
                        echo '<option value="'.$opt.'" '.$selected.'>'.$display.'</option>';
                    }
                    echo '</select>';
                } elseif ($field['type'] === 'date') {
                    echo '<input type="date" name="'.$name.'" value="'.$value.'" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all text-gray-700">';
                } else {
                     echo '<input type="'.$field['type'].'" name="'.$name.'" value="'.$value.'" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all placeholder-gray-300">';
                }
                echo '</div>';
            }
            echo '</div>'; // grid
            echo '</div>'; // card
        }
        ?>

        <!-- 1. Expedientes Alumnos -->
        <?php renderSection("1. Tabla Nro. 4. Expedientes Alumnos", "exp", $data, [
            ['name' => 'archivo', 'label' => 'Archivo pasivo', 'type' => 'text'],
            ['name' => 'docs', 'label' => 'Documentos inscripción', 'type' => 'text'],
            ['name' => 'asistencia', 'label' => 'Asistencia', 'type' => 'text'],
            ['name' => 'pruebas_teo', 'label' => 'Pruebas teóricas', 'type' => 'text'],
            ['name' => 'pruebas_prac', 'label' => 'Pruebas prácticas', 'type' => 'text'],
            ['name' => 'calificaciones', 'label' => 'Calificaciones', 'type' => 'text'],
            ['name' => 'permiso', 'label' => 'Permiso de aprendizaje', 'type' => 'text'],
            ['name' => 'doc_aprob', 'label' => 'Doc. aprobación/reprobación', 'type' => 'text'],
            ['name' => 'certificado', 'label' => 'Certificado de aprobación', 'type' => 'text'],
        ]); ?>

        <!-- 2. Director Administrativo -->
        <?php renderSection("2. Tabla Nro. 19. Director/a Administrativo", "dir_adm", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
            ['name' => 'gerencia', 'label' => 'Gerencia/Dirección', 'type' => 'text'],
            ['name' => 'experiencia', 'label' => 'Años Experiencia', 'type' => 'number'],
            ['name' => 'cargo_publico', 'label' => '¿Cargo público TTTSV?', 'type' => 'select', 'options' => ['Si', 'No']],
            ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>

        <!-- 3. Director General -->
        <?php renderSection("3. Director General", "dir_gen", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
            ['name' => 'experiencia', 'label' => 'Años Exp. Dirección', 'type' => 'number'],
            ['name' => 'idoneidad', 'label' => 'Acredita idoneidad', 'type' => 'select', 'options' => ['Si', 'No']],
            ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>

        <!-- 4. Asesor Técnico 1 -->
        <?php renderSection("4. Tabla Nro. 20. Asesor/a Técnico (1)", "asesor", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
            ['name' => 'titulo', 'label' => 'Título 3er/4to Nivel', 'type' => 'text'],
            ['name' => 'senescyt', 'label' => 'Reg. SENESCYT', 'type' => 'text'],
            ['name' => 'experiencia', 'label' => 'Exp. Seg. Vial (Ex-miembro)', 'type' => 'number'],
            ['name' => 'horas', 'label' => 'Horas Cap. (Ex-miembro)', 'type' => 'number'],
            ['name' => 'doc_pasivo', 'label' => 'Doc. Servicio Pasivo', 'type' => 'text'],
            ['name' => 'cargo_publico', 'label' => '¿Cargo público TTTSV?', 'type' => 'select', 'options' => ['Si', 'No']],
            ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>

        <!-- 5. Asesor Técnico 2 -->
        <?php renderSection("5. Tabla Nro. 20. Asesor/a Técnico (2)", "asesor2", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
            ['name' => 'titulo', 'label' => 'Título', 'type' => 'text'],
            ['name' => 'senescyt', 'label' => 'Reg. SENESCYT', 'type' => 'text'],
            ['name' => 'experiencia', 'label' => 'Exp. (Ex-miembro)', 'type' => 'number'],
            ['name' => 'doc_pasivo', 'label' => 'Doc. Servicio Pasivo', 'type' => 'text'],
            ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>

         <!-- 6. Inspector Supervisor -->
        <?php renderSection("6. Tabla Nro. 24. Inspector/a Supervisor/a", "insp_sup", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
            ['name' => 'titulo', 'label' => 'Título', 'type' => 'text'],
            ['name' => 'senescyt', 'label' => 'Reg. SENESCYT', 'type' => 'text'],
             ['name' => 'exp_instruccion', 'label' => 'Exp. Instrucción (Años)', 'type' => 'number'],
             ['name' => 'exp_admin', 'label' => 'Exp. Admin (Años)', 'type' => 'number'],
             ['name' => 'ex_exp', 'label' => 'Exp. TTTSV (Ex-miembro)', 'type' => 'text'],
             ['name' => 'ex_horas', 'label' => 'Horas Cap. (Ex-miembro)', 'type' => 'number'],
             ['name' => 'cargo', 'label' => '¿Cargo público TTTSV?', 'type' => 'select', 'options' => ['Si', 'No']],
             ['name' => 'ofimatica', 'label' => 'Conoc. Ofimática', 'type' => 'text'],
             ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>

        <!-- 7. Supervisor -->
        <?php renderSection("7. Tabla Nro. 24. Supervisor", "sup", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
            ['name' => 'titulo', 'label' => 'Título', 'type' => 'text'],
             ['name' => 'senescyt', 'label' => 'Reg. SENESCYT', 'type' => 'text'],
             ['name' => 'experiencia', 'label' => 'Años Experiencia', 'type' => 'number'],
             ['name' => 'lottsv', 'label' => 'Conoc. LOTTTSV', 'type' => 'text'],
             ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>

        <!-- 8. Secretario 1 -->
        <?php renderSection("8. Tabla Nro. 22. Secretario/a (1)", "sec", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
            ['name' => 'titulo', 'label' => 'Título', 'type' => 'text'],
             ['name' => 'senescyt', 'label' => 'Reg. SENESCYT', 'type' => 'text'],
             ['name' => 'experiencia', 'label' => 'Años Experiencia', 'type' => 'number'],
             ['name' => 'ofimatica', 'label' => 'Conoc. Ofimática', 'type' => 'text'],
             ['name' => 'cargo_publico', 'label' => '¿Cargo público TTTSV?', 'type' => 'select', 'options' => ['Si', 'No']],
             ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>

        <!-- 9. Secretario 2 -->
        <?php renderSection("9. Tabla Nro. 22. Secretario/a (2)", "sec2", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
             ['name' => 'experiencia', 'label' => 'Años Experiencia', 'type' => 'number'],
             ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>

        <!-- 10. Contador -->
        <?php renderSection("10. Tabla Nro. 25. Contador/a", "cont", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
             ['name' => 'cargo', 'label' => 'Cargo', 'type' => 'text'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
            ['name' => 'titulo', 'label' => 'Título', 'type' => 'text'],
             ['name' => 'senescyt', 'label' => 'Reg. SENESCYT', 'type' => 'text'],
             ['name' => 'experiencia', 'label' => 'Años Experiencia', 'type' => 'number'],
             ['name' => 'cargo_publico', 'label' => '¿Cargo público TTTSV?', 'type' => 'select', 'options' => ['Si', 'No']],
             ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>

        <!-- 11. Tesorero -->
        <?php renderSection("11. Tabla Nro. 25. Tesorero/a", "tes", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
             ['name' => 'cargo', 'label' => 'Cargo', 'type' => 'text'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
             ['name' => 'conocimientos', 'label' => 'Conoc. Contables', 'type' => 'text'],
             ['name' => 'caucion', 'label' => 'Caución (Póliza)', 'type' => 'text'],
             ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>

        <!-- 12. Psicólogo -->
        <?php renderSection("12. Tabla Nro. 25. Psicólogo/a", "psic", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
            ['name' => 'titulo', 'label' => 'Título', 'type' => 'text'],
             ['name' => 'senescyt', 'label' => 'Reg. SENESCYT', 'type' => 'text'],
             ['name' => 'ofimatica', 'label' => 'Conoc. Ofimática', 'type' => 'text'],
             ['name' => 'capacitacion', 'label' => 'Capacitación Equipos', 'type' => 'text'],
             ['name' => 'cargo_publico', 'label' => '¿Cargo público TTTSV?', 'type' => 'select', 'options' => ['Si', 'No']],
             ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>

        <!-- 13. Psicólogo Educativo -->
        <?php renderSection("13. Tabla Nro. 25. Psicólogo Educativo", "psic_edu", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
            ['name' => 'titulo', 'label' => 'Título', 'type' => 'text'],
             ['name' => 'senescyt', 'label' => 'Reg. SENESCYT', 'type' => 'text'],
             ['name' => 'experiencia', 'label' => 'Exp. Análisis Exámenes', 'type' => 'text'],
             ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>

        <!-- 14. Evaluador -->
         <?php renderSection("14. Tabla Nro. 26. Evaluador Psicosensométrico", "eval", $data, [
            ['name' => 'fecha', 'label' => 'Fecha Contratación', 'type' => 'date'],
            ['name' => 'nombres', 'label' => 'Nombres y Apellidos', 'type' => 'text'],
            ['name' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
            ['name' => 'titulo', 'label' => 'Título', 'type' => 'text'],
             ['name' => 'senescyt', 'label' => 'Reg. SENESCYT', 'type' => 'text'],
             ['name' => 'ofimatica', 'label' => 'Conoc. Ofimática', 'type' => 'text'],
             ['name' => 'capacitacion', 'label' => 'Capacitación Equipos', 'type' => 'text'],
             ['name' => 'cargo_publico', 'label' => '¿Cargo público TTTSV?', 'type' => 'select', 'options' => ['Si', 'No']],
             ['name' => 'cumple', 'label' => 'Cumple requisitos', 'type' => 'select', 'options' => ['Si', 'No']],
        ]); ?>
        
        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step10.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
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

<?php include '../../includes/form_footer.php'; ?>
