<?php
session_start();
require_once '../vendor/autoload.php';

if (!isset($_SESSION['user_id'])) {
    die("Acceso denegado.");
}

// Load session data by default
$userData = $_SESSION['form_data'] ?? [];

// MERGE POST DATA FOR PREVIEW (Important for Step 2 testing)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determine which step is being posted
    $currentStep = $_POST['step'] ?? null;
    if ($currentStep) {
        $stepKey = 'step' . $currentStep;
        // Merge POST data into the specific step array of userData
        // We use array_merge to keep existing session keys that might not be in POST (though usually POST replaces)
        $userData[$stepKey] = array_merge($userData[$stepKey] ?? [], $_POST);
        
        // Special handling for nested arrays (Aulas, Talleres, Expedientes) which might not merge deeper levels correctly with simple array_merge if keys differ
        // For Step 2 specifically, we want the POST to overwrite these arrays entirely to reflect the form state
        if ($currentStep == 2) {
             if (isset($_POST['aulas'])) $userData[$stepKey]['aulas'] = $_POST['aulas'];
             if (isset($_POST['taller'])) $userData[$stepKey]['taller'] = $_POST['taller'];
             if (isset($_POST['admin_areas'])) $userData[$stepKey]['admin_areas'] = $_POST['admin_areas'];
             if (isset($_POST['exp_cursos'])) $userData[$stepKey]['exp_cursos'] = $_POST['exp_cursos'];
             if (isset($_POST['exp_graduados'])) $userData[$stepKey]['exp_graduados'] = $_POST['exp_graduados'];
        }
        if ($currentStep == 8) {
            // Estudiantes nested structure
            if (isset($_POST['estudiantes'])) $userData[$stepKey]['estudiantes'] = $_POST['estudiantes'];
        }

        // Save to session
        $userData['school_type'] = 'professional'; // FORCE professional type
        $_SESSION['form_data'] = $userData;

        // Save to DB
        $encodedData = json_encode($userData);
        require_once '../includes/db_connection.php';
        try {
            $stmt = $pdo->prepare("UPDATE user_progress SET form_data = ? WHERE user_id = ?");
            $stmt->execute([$encodedData, $_SESSION['user_id']]);
        } catch (Exception $e) { /* Ignore DB error for now */ }

        // Logic for Finish and View Summary
        if (isset($_POST['finish_and_view_summary']) && $_POST['finish_and_view_summary'] == '1') {
            header("Location: ../views/professional/summary.php");
            exit;
        }
    }
}

// Admin Override: If admin wants to download a specific user's report
if (isset($_GET['admin_download_user_id']) && ($_SESSION['role'] ?? '') === 'admin') {
    $targetUserId = (int)$_GET['admin_download_user_id'];
    require_once '../includes/db_connection.php';
    try {
        $stmt = $pdo->prepare("SELECT form_data FROM user_progress WHERE user_id = ?");
        $stmt->execute([$targetUserId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['form_data'])) {
            $dbData = json_decode($row['form_data'] ?? '', true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($dbData)) {
                $userData = $dbData;
            }
        }
    } catch (Exception $e) {
        // Fallback to session or empty on error
    }
}

// Archive Download Mode
if (isset($_GET['archive_id'])) {
    require_once '../includes/db_connection.php';
    try {
        $stmt = $pdo->prepare("SELECT form_data, user_id FROM reports_archive WHERE id = ?");
        $stmt->execute([$_GET['archive_id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Security Check: Ensure report belongs to user OR user is admin
        if ($row && ($row['user_id'] == $_SESSION['user_id'] || ($_SESSION['role'] ?? '') === 'admin')) {
             if (!empty($row['form_data'])) {
             $archivedData = json_decode($row['form_data'] ?? '', true);
             if (is_array($archivedData)) {
                 $userData = $archivedData;
                 // Set a flag to skip archiving this download (it's already archived)
                 $_GET['archive_mode'] = 1; 
             }
        }
        }
    } catch (Exception $e) {}
}

$s1 = $userData['step1'] ?? [];
$s2 = $userData['step2'] ?? [];
$s3 = $userData['step3'] ?? [];
$s4 = $userData['step4'] ?? [];
$s5 = $userData['step5'] ?? [];
$s6 = $userData['step6'] ?? [];
$s7 = $userData['step7'] ?? [];
$s8 = $userData['step8'] ?? [];

// Path to template - PROFESSIONAL
$templatePath = '../templates/profesionales.docx';
if (!file_exists($templatePath)) {
    die("Error: No se encuentra la plantilla en $templatePath");
}

try {
    // REMOYED REQUIRED FIELDS VALIDATION as per user request

    $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

    // =================================================================================
    // HELPER FUNCTIONS
    // =================================================================================
    
    function setSafeValue($template, $key, $value) {
        if (is_array($value)) {
             $template->setValue($key, implode(', ', $value));
        } else {
            $value = $value ?? ''; // Normalize null to empty string
            // Sanitize XML characters to prevent corruption, but keep basic text
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $template->setValue($key, $value);
        }
    }

    // =================================================================================
    // STEP 1: Datos Informativos
    // =================================================================================
    $scalars1 = [
        'escuela_nombre','provincia','canton','ruc','telefono_fijo','telefono','email','gerente_nombre','direccion_insitu','direccion_ant','direccion_sri',
        // Documentos Habilitantes
        'resolucion_nro', 'resoluciones_extra', 'estado_sri', 'domicilio', 'patente_municipal', 'impuesto_predial', 'permiso_bomberos', 'permiso_sanitario',
        // Otros
        'tipo_curso'
    ];
    foreach ($scalars1 as $k) { setSafeValue($template, $k, $s1[$k] ?? ''); }

    // =================================================================================
    // STEP 2: INFRAESTRUCTURA
    // =================================================================================
    
    // 2.1 Aulas
    $aulas = $s2['aulas'] ?? [];
    $countAulas = count($aulas);
    if ($countAulas > 0) {
        try {
            $template->cloneRow('nro', $countAulas);
            for ($i = 0; $i < $countAulas; $i++) {
                $row = $i + 1;
                $item = $aulas[$i] ?? [];
                setSafeValue($template, 'nro#' . $row, $item['nro'] ?? '');
                setSafeValue($template, 'alumnos#' . $row, $item['alumnos'] ?? '');
                setSafeValue($template, 'resolucion#' . $row, $item['resolucion'] ?? '');
                setSafeValue($template, 'material#' . $row, $item['material'] ?? '');
                setSafeValue($template, 'cam_tiene#' . $row, $item['cam_tiene'] ?? '');
                setSafeValue($template, 'cam_conectada#' . $row, $item['cam_conectada'] ?? '');
                setSafeValue($template, 'cam_acceso#' . $row, $item['cam_acceso'] ?? '');
                setSafeValue($template, 'cam_frecuencia#' . $row, $item['cam_frecuencia'] ?? '');
                setSafeValue($template, 'proyector#' . $row, isset($item['proyector']) ? 'SI' : 'NO');
                setSafeValue($template, 'computador#' . $row, isset($item['computador']) ? 'SI' : 'NO');
                setSafeValue($template, 'lista#' . $row, isset($item['lista']) ? 'SI' : 'NO');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'nro', '');
        setSafeValue($template, 'alumnos', '');
        setSafeValue($template, 'resolucion', '');
        setSafeValue($template, 'material', '');
        setSafeValue($template, 'cam_tiene', '');
        setSafeValue($template, 'cam_conectada', '');
        setSafeValue($template, 'cam_acceso', '');
        setSafeValue($template, 'cam_frecuencia', '');
        setSafeValue($template, 'proyector', '');
        setSafeValue($template, 'computador', '');
        setSafeValue($template, 'lista', '');
    }

    // 2.2 Talleres (Direct Placeholder Replacement - No Row Cloning)
    setSafeValue($template, 'taller_curso_1', $s2['taller_curso_1'] ?? '');
    setSafeValue($template, 'taller_curso_2', $s2['taller_curso_2'] ?? '');

    // Individual field replacements for each taller parameter
    setSafeValue($template, 'taller_material_c1', $s2['taller_material_c1'] ?? '');
    setSafeValue($template, 'taller_material_c2', $s2['taller_material_c2'] ?? '');
    
    setSafeValue($template, 'taller_diagramas_c1', $s2['taller_diagramas_c1'] ?? '');
    setSafeValue($template, 'taller_diagramas_c2', $s2['taller_diagramas_c2'] ?? '');
    
    setSafeValue($template, 'taller_panel_c1', $s2['taller_panel_c1'] ?? '');
    setSafeValue($template, 'taller_panel_c2', $s2['taller_panel_c2'] ?? '');
    
    setSafeValue($template, 'taller_fosa_c1', $s2['taller_fosa_c1'] ?? '');
    setSafeValue($template, 'taller_fosa_c2', $s2['taller_fosa_c2'] ?? '');
    
    setSafeValue($template, 'taller_herramientas_c1', $s2['taller_herramientas_c1'] ?? '');
    setSafeValue($template, 'taller_herramientas_c2', $s2['taller_herramientas_c2'] ?? '');
    
    setSafeValue($template, 'taller_motores_c1', $s2['taller_motores_c1'] ?? '');
    setSafeValue($template, 'taller_motores_c2', $s2['taller_motores_c2'] ?? '');

    // 2.3 Areas Administrativas (Direct Placeholder Replacement - No Row Cloning)
    setSafeValue($template, 'admin_recepcion', $s2['admin_recepcion'] ?? '');
    setSafeValue($template, 'admin_inspeccion', $s2['admin_inspeccion'] ?? '');
    setSafeValue($template, 'admin_direccion', $s2['admin_direccion'] ?? '');
    setSafeValue($template, 'admin_sala_profesores', $s2['admin_sala_profesores'] ?? '');
    setSafeValue($template, 'admin_sala_espera', $s2['admin_sala_espera'] ?? '');
    setSafeValue($template, 'admin_archivo', $s2['admin_archivo'] ?? '');
    setSafeValue($template, 'admin_contable', $s2['admin_contable'] ?? '');
    setSafeValue($template, 'admin_secretaria', $s2['admin_secretaria'] ?? '');

    // 2.4/2.5 Expedientes Cursos/Graduados
    $expCursos = $s2['exp_cursos'] ?? [];
    $countExpC = count($expCursos);
    if ($countExpC > 0) {
        try {
            $template->cloneRow('ec_curso', $countExpC);
            for ($i = 0; $i < $countExpC; $i++) {
                $row = $i + 1;
                $exp = $expCursos[$i] ?? [];
                setSafeValue($template, 'ec_curso#' . $row, $exp['curso'] ?? '');
                setSafeValue($template, 'ec_archivo#' . $row, $exp['archivo'] ?? '');
                setSafeValue($template, 'ec_asistencia#' . $row, $exp['reg_asistencia'] ?? '');
                setSafeValue($template, 'ec_notas#' . $row, $exp['reg_notas'] ?? '');
                setSafeValue($template, 'ec_previos#' . $row, $exp['docs_previos'] ?? '');
                setSafeValue($template, 'ec_cronograma#' . $row, $exp['cronograma'] ?? '');
                setSafeValue($template, 'ec_docentes#' . $row, $exp['exp_docentes'] ?? '');
                setSafeValue($template, 'ec_instructores#' . $row, $exp['exp_instructores'] ?? '');
                setSafeValue($template, 'ec_admin#' . $row, $exp['exp_admin'] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'ec_curso', '');
    }

    $expGrad = $s2['exp_graduados'] ?? [];
    $countExpG = count($expGrad);
    if ($countExpG > 0) {
        try {
            $template->cloneRow('eg_curso', $countExpG);
             for ($i = 0; $i < $countExpG; $i++) {
                $row = $i + 1;
                $exp = $expGrad[$i] ?? [];
                setSafeValue($template, 'eg_curso#' . $row, $exp['curso'] ?? '');
                setSafeValue($template, 'eg_archivo#' . $row, $exp['archivo'] ?? '');
                setSafeValue($template, 'eg_asistencia#' . $row, $exp['reg_asistencia'] ?? '');
                setSafeValue($template, 'eg_notas#' . $row, $exp['reg_notas'] ?? '');
                setSafeValue($template, 'eg_previos#' . $row, $exp['docs_previos'] ?? '');
                setSafeValue($template, 'eg_cronograma#' . $row, $exp['cronograma'] ?? '');
                setSafeValue($template, 'eg_docentes#' . $row, $exp['exp_docentes'] ?? '');
                setSafeValue($template, 'eg_instructores#' . $row, $exp['exp_instructores'] ?? '');
                setSafeValue($template, 'eg_admin#' . $row, $exp['exp_admin'] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'eg_curso', '');
    }

    // =================================================================================
    // STEP 3: LABORATORIOS
    // =================================================================================
    
    // 3.1 Laboratorio de Computación (Dynamic Table)
    $labs = $s3['labs'] ?? [];
    $countLabs = count($labs);
    if ($countLabs > 0) {
        try {
            $template->cloneRow('lab_nro', $countLabs);
            for ($i = 0; $i < $countLabs; $i++) {
                $row = $i + 1;
                $item = $labs[$i] ?? [];
                setSafeValue($template, 'lab_nro#' . $row, $item['nro'] ?? '');
                setSafeValue($template, 'lab_computadores#' . $row, $item['computadores'] ?? '');
                setSafeValue($template, 'lab_proyector#' . $row, $item['proyector'] ?? '');
                setSafeValue($template, 'lab_material#' . $row, $item['material'] ?? '');
                setSafeValue($template, 'lab_fijo#' . $row, $item['fijo'] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'lab_nro', '');
    }

    // 3.2 Laboratorio Psicosensométrico (Dynamic Table)
    $psicolabs = $s3['psicolabs'] ?? [];
    $countPsicoLabs = count($psicolabs);
    if ($countPsicoLabs > 0) {
        try {
            $template->cloneRow('psicolab_nro', $countPsicoLabs);
            for ($i = 0; $i < $countPsicoLabs; $i++) {
                $row = $i + 1;
                $item = $psicolabs[$i] ?? [];
                setSafeValue($template, 'psicolab_nro#' . $row, $item['nro'] ?? '');
                setSafeValue($template, 'psicolab_sonido#' . $row, $item['sonido'] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'psicolab_nro', '');
    }

    // 3.3 Equipo Psicosensométrico (Dynamic Table)
    $psicoequip = $s3['psicoequip'] ?? [];
    $countPsicoEquip = count($psicoequip);
    if ($countPsicoEquip > 0) {
        try {
            $template->cloneRow('psicoequip_nro', $countPsicoEquip);
            for ($i = 0; $i < $countPsicoEquip; $i++) {
                $row = $i + 1;
                $item = $psicoequip[$i] ?? [];
                setSafeValue($template, 'psicoequip_nro#' . $row, $item['nro'] ?? '');
                setSafeValue($template, 'psicoequip_modelo#' . $row, $item['modelo'] ?? '');
                setSafeValue($template, 'psicoequip_certificado#' . $row, $item['certificado'] ?? '');
                setSafeValue($template, 'psicoequip_propiedad#' . $row, $item['propiedad'] ?? '');
                setSafeValue($template, 'psicoequip_eval_auditiva#' . $row, $item['eval_auditiva'] ?? '');
                setSafeValue($template, 'psicoequip_eval_psicomotriz#' . $row, $item['eval_psicomotriz'] ?? '');
                setSafeValue($template, 'psicoequip_eval_visual#' . $row, $item['eval_visual'] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'psicoequip_nro', '');
    }

    // 3.4 Parque Vial
    setSafeValue($template, 'parque_horizontal', $s3['parque_horizontal'] ?? '');
    setSafeValue($template, 'parque_vertical', $s3['parque_vertical'] ?? '');

    // 3.5 Circuito Autorizado GAD
    setSafeValue($template, 'gad_numero', $s3['gad_numero'] ?? '');
    setSafeValue($template, 'gad_vigencia', $s3['gad_vigencia'] ?? '');
    setSafeValue($template, 'gad_institucion', $s3['gad_institucion'] ?? '');

    // =================================================================================
    // STEP 4: ÁREAS COMPLEMENTARIAS
    // =================================================================================
    
    // 4.1 Baterías Sanitarias
    $batLimpieza = $s4['baterias_limpieza'] ?? '';
    setSafeValue($template, 'baterias_hombres', $s4['baterias_hombres'] ?? '');
    setSafeValue($template, 'baterias_mujeres', $s4['baterias_mujeres'] ?? '');
    
    // Logic to split single select into unique "named" placeholders for Word
    setSafeValue($template, 'baterias_limpieza_excelente', ($batLimpieza === 'Excelente') ? 'X' : '');
    setSafeValue($template, 'baterias_limpieza_bueno', ($batLimpieza === 'Bueno') ? 'X' : '');
    setSafeValue($template, 'baterias_limpieza_malo', ($batLimpieza === 'Malo') ? 'X' : '');

    // 4.2 Bar - Cafetería (Dynamic Table)
    $cafeterias = $s4['cafeterias'] ?? [];
    $countCafeterias = count($cafeterias);
    if ($countCafeterias > 0) {
        try {
            $template->cloneRow('cafeteria_nro', $countCafeterias);
            for ($i = 0; $i < $countCafeterias; $i++) {
                $row = $i + 1;
                $item = $cafeterias[$i] ?? [];
                $cafLimpieza = $item['limpieza'] ?? '';

                setSafeValue($template, 'cafeteria_nro#' . $row, $item['nro'] ?? '');
                
                // Logic to split cafeteria cleaning into unique placeholders
                setSafeValue($template, 'cafeteria_limpieza_excelente#' . $row, ($cafLimpieza === 'Excelente') ? 'X' : '');
                setSafeValue($template, 'cafeteria_limpieza_bueno#' . $row, ($cafLimpieza === 'Bueno') ? 'X' : '');
                setSafeValue($template, 'cafeteria_limpieza_malo#' . $row, ($cafLimpieza === 'Malo') ? 'X' : '');
                
                setSafeValue($template, 'cafeteria_permiso#' . $row, $item['permiso'] ?? '');
                setSafeValue($template, 'cafeteria_vigencia#' . $row, $item['vigencia'] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'cafeteria_nro', '');
    }

    // 4.3 Parqueadero
    setSafeValue($template, 'parking_instruccion', $s4['parking_instruccion'] ?? '');
    setSafeValue($template, 'parking_funcionarios', $s4['parking_funcionarios'] ?? '');
    setSafeValue($template, 'parking_usuarios', $s4['parking_usuarios'] ?? '');

    // 4.4 Área de Recreación
    setSafeValue($template, 'recreacion_estado', $s4['recreacion_estado'] ?? '');
    setSafeValue($template, 'recreacion_detalle', $s4['recreacion_detalle'] ?? '');

    // =================================================================================
    // STEP 5: EQUIPAMIENTO TECNOLÓGICO Y VEHICULAR
    // =================================================================================
    
    // 5.1 Equipo Biométrico (Dynamic Table)
    $biometric = $s5['biometric'] ?? [];
    $countBiometric = count($biometric);
    if ($countBiometric > 0) {
        try {
            $template->cloneRow('bio_nro', $countBiometric);
            for ($i = 0; $i < $countBiometric; $i++) {
                $row = $i + 1;
                $item = $biometric[$i] ?? [];
                setSafeValue($template, 'bio_nro#' . $row, $item['nro'] ?? '');
                setSafeValue($template, 'bio_frecuencia#' . $row, $item['frecuencia'] ?? '');
                setSafeValue($template, 'bio_asis_docentes#' . $row, $item['asis_docentes'] ?? '');
                setSafeValue($template, 'bio_asis_instructores#' . $row, $item['asis_instructores'] ?? '');
                setSafeValue($template, 'bio_asis_alumnos#' . $row, $item['asis_alumnos'] ?? '');
                setSafeValue($template, 'bio_internet#' . $row, $item['internet'] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'bio_nro', '');
    }

    // 5.2 Equipo Simulador (Dynamic Table)
    $simulator = $s5['simulator'] ?? [];
    $countSimulator = count($simulator);
    if ($countSimulator > 0) {
        try {
            $template->cloneRow('sim_marca', $countSimulator);
            for ($i = 0; $i < $countSimulator; $i++) {
                $row = $i + 1;
                $item = $simulator[$i] ?? [];
                setSafeValue($template, 'sim_marca#' . $row, $item['marca'] ?? '');
                setSafeValue($template, 'sim_modelo#' . $row, $item['modelo'] ?? '');
                setSafeValue($template, 'sim_certificado#' . $row, $item['certificado'] ?? '');
                setSafeValue($template, 'sim_tipo_cursos#' . $row, $item['tipo_cursos'] ?? '');
                setSafeValue($template, 'sim_uso_practico#' . $row, $item['uso_practico'] ?? '');
                setSafeValue($template, 'sim_porcentaje#' . $row, $item['porcentaje'] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'sim_marca', '');
    }

    // 5.3 Equipamiento Vehicular (Dynamic Table)
    $vehicles = $s5['vehicles'] ?? [];
    $countVehicles = count($vehicles);
    if ($countVehicles > 0) {
        try {
            $template->cloneRow('veh_modelo', $countVehicles);
            for ($i = 0; $i < $countVehicles; $i++) {
                $row = $i + 1;
                $item = $vehicles[$i] ?? [];
                setSafeValue($template, 'veh_modelo#' . $row, $item['modelo'] ?? '');
                setSafeValue($template, 'veh_placa#' . $row, $item['placa'] ?? '');
                setSafeValue($template, 'veh_resolucion#' . $row, $item['resolucion'] ?? '');
                setSafeValue($template, 'veh_vigencia#' . $row, $item['vigencia'] ?? '');
                setSafeValue($template, 'veh_anio#' . $row, $item['anio'] ?? '');
                setSafeValue($template, 'veh_logos#' . $row, $item['logos'] ?? '');
                setSafeValue($template, 'veh_doble_comando#' . $row, $item['doble_comando'] ?? '');
                setSafeValue($template, 'veh_poliza_100#' . $row, $item['poliza_100'] ?? '');
                setSafeValue($template, 'veh_poliza_periodo#' . $row, $item['poliza_periodo'] ?? '');
                setSafeValue($template, 'veh_fecha_matricula#' . $row, $item['fecha_matricula'] ?? '');
                setSafeValue($template, 'veh_fecha_revision#' . $row, $item['fecha_revision'] ?? '');
                setSafeValue($template, 'veh_fines_ajenos#' . $row, $item['fines_ajenos'] ?? '');
                setSafeValue($template, 'veh_mantenimiento#' . $row, $item['mantenimiento'] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'veh_modelo', '');
    }

    // =================================================================================
    // STEP 6: TALENTO HUMANO - PERSONAL ADMINISTRATIVO
    // =================================================================================
    
    // 6.1 Director/a Administrativo/a
    setSafeValue($template, 'dir_admin_cargo', $s6['dir_admin_cargo'] ?? '');
    setSafeValue($template, 'dir_admin_nombres', $s6['dir_admin_nombres'] ?? '');
    setSafeValue($template, 'dir_admin_cedula', $s6['dir_admin_cedula'] ?? '');
    setSafeValue($template, 'dir_admin_titulo', $s6['dir_admin_titulo'] ?? '');
    setSafeValue($template, 'dir_admin_senescyt', $s6['dir_admin_senescyt'] ?? '');
    setSafeValue($template, 'dir_admin_exp', $s6['dir_admin_exp'] ?? '');
    setSafeValue($template, 'dir_admin_solvencia', $s6['dir_admin_solvencia'] ?? '');
    setSafeValue($template, 'dir_admin_cumple', $s6['dir_admin_cumple'] ?? '');

    // 6.2 Director/a Pedagógico/a
    setSafeValue($template, 'dir_ped_cargo', $s6['dir_ped_cargo'] ?? '');
    setSafeValue($template, 'dir_ped_nombres', $s6['dir_ped_nombres'] ?? '');
    setSafeValue($template, 'dir_ped_cedula', $s6['dir_ped_cedula'] ?? '');
    setSafeValue($template, 'dir_ped_titulo', $s6['dir_ped_titulo'] ?? '');
    setSafeValue($template, 'dir_ped_senescyt', $s6['dir_ped_senescyt'] ?? '');
    setSafeValue($template, 'dir_ped_exp', $s6['dir_ped_exp'] ?? '');
    setSafeValue($template, 'dir_ped_solvencia', $s6['dir_ped_solvencia'] ?? '');
    setSafeValue($template, 'dir_ped_cumple', $s6['dir_ped_cumple'] ?? '');

    // 6.3 Tesorero/a
    setSafeValue($template, 'tesorero_cargo', $s6['tesorero_cargo'] ?? '');
    setSafeValue($template, 'tesorero_nombres', $s6['tesorero_nombres'] ?? '');
    setSafeValue($template, 'tesorero_cedula', $s6['tesorero_cedula'] ?? '');
    setSafeValue($template, 'tesorero_titulo', $s6['tesorero_titulo'] ?? '');
    setSafeValue($template, 'tesorero_senescyt', $s6['tesorero_senescyt'] ?? '');
    setSafeValue($template, 'tesorero_exp', $s6['tesorero_exp'] ?? '');
    setSafeValue($template, 'tesorero_caucion', $s6['tesorero_caucion'] ?? '');
    setSafeValue($template, 'tesorero_cumple', $s6['tesorero_cumple'] ?? '');

    // 6.4 Secretario/a
    setSafeValue($template, 'secretario_cargo', $s6['secretario_cargo'] ?? '');
    setSafeValue($template, 'secretario_nombres', $s6['secretario_nombres'] ?? '');
    setSafeValue($template, 'secretario_cedula', $s6['secretario_cedula'] ?? '');
    setSafeValue($template, 'secretario_titulo', $s6['secretario_titulo'] ?? '');
    setSafeValue($template, 'secretario_senescyt', $s6['secretario_senescyt'] ?? '');
    setSafeValue($template, 'secretario_exp', $s6['secretario_exp'] ?? '');
    setSafeValue($template, 'secretario_cumple', $s6['secretario_cumple'] ?? '');

    // 6.5 Asesor Técnico
    setSafeValue($template, 'asesor_cargo', $s6['asesor_cargo'] ?? '');
    setSafeValue($template, 'asesor_nombres', $s6['asesor_nombres'] ?? '');
    setSafeValue($template, 'asesor_cedula', $s6['asesor_cedula'] ?? '');
    setSafeValue($template, 'asesor_titulo', $s6['asesor_titulo'] ?? '');
    setSafeValue($template, 'asesor_senescyt', $s6['asesor_senescyt'] ?? '');
    setSafeValue($template, 'asesor_exp', $s6['asesor_exp'] ?? '');
    setSafeValue($template, 'asesor_cumple', $s6['asesor_cumple'] ?? '');

    // 6.6 Inspector/a
    setSafeValue($template, 'inspector_cargo', $s6['inspector_cargo'] ?? '');
    setSafeValue($template, 'inspector_nombres', $s6['inspector_nombres'] ?? '');
    setSafeValue($template, 'inspector_cedula', $s6['inspector_cedula'] ?? '');
    setSafeValue($template, 'inspector_titulo', $s6['inspector_titulo'] ?? '');
    setSafeValue($template, 'inspector_senescyt', $s6['inspector_senescyt'] ?? '');
    setSafeValue($template, 'inspector_exp', $s6['inspector_exp'] ?? '');
    setSafeValue($template, 'inspector_cumple', $s6['inspector_cumple'] ?? '');

    // 6.7 Contador/a
    setSafeValue($template, 'contador_cargo', $s6['contador_cargo'] ?? '');
    setSafeValue($template, 'contador_nombres', $s6['contador_nombres'] ?? '');
    setSafeValue($template, 'contador_cedula', $s6['contador_cedula'] ?? '');
    setSafeValue($template, 'contador_titulo', $s6['contador_titulo'] ?? '');
    setSafeValue($template, 'contador_senescyt', $s6['contador_senescyt'] ?? '');
    setSafeValue($template, 'contador_exp', $s6['contador_exp'] ?? '');
    setSafeValue($template, 'contador_cumple', $s6['contador_cumple'] ?? '');

    // 6.8 Evaluador Psicosensométrico
    setSafeValue($template, 'eval_psico_cargo', $s6['eval_psico_cargo'] ?? '');
    setSafeValue($template, 'eval_psico_nombres', $s6['eval_psico_nombres'] ?? '');
    setSafeValue($template, 'eval_psico_cedula', $s6['eval_psico_cedula'] ?? '');
    setSafeValue($template, 'eval_psico_titulo', $s6['eval_psico_titulo'] ?? '');
    setSafeValue($template, 'eval_psico_senescyt', $s6['eval_psico_senescyt'] ?? '');
    setSafeValue($template, 'eval_psico_exp', $s6['eval_psico_exp'] ?? '');
    setSafeValue($template, 'eval_psico_cert', $s6['eval_psico_cert'] ?? '');
    setSafeValue($template, 'eval_psico_cumple', $s6['eval_psico_cumple'] ?? '');

    // 6.9 Evaluador Psicológico
    setSafeValue($template, 'eval_psicol_cargo', $s6['eval_psicol_cargo'] ?? '');
    setSafeValue($template, 'eval_psicol_nombres', $s6['eval_psicol_nombres'] ?? '');
    setSafeValue($template, 'eval_psicol_cedula', $s6['eval_psicol_cedula'] ?? '');
    setSafeValue($template, 'eval_psicol_titulo', $s6['eval_psicol_titulo'] ?? '');
    setSafeValue($template, 'eval_psicol_senescyt', $s6['eval_psicol_senescyt'] ?? '');
    setSafeValue($template, 'eval_psicol_exp', $s6['eval_psicol_exp'] ?? '');
    setSafeValue($template, 'eval_psicol_cumple', $s6['eval_psicol_cumple'] ?? '');

    // 6.10 Recepcionista
    setSafeValue($template, 'recepcionista_cargo', $s6['recepcionista_cargo'] ?? '');
    setSafeValue($template, 'recepcionista_nombres', $s6['recepcionista_nombres'] ?? '');
    setSafeValue($template, 'recepcionista_cedula', $s6['recepcionista_cedula'] ?? '');
    setSafeValue($template, 'recepcionista_titulo', $s6['recepcionista_titulo'] ?? '');
    setSafeValue($template, 'recepcionista_senescyt', $s6['recepcionista_senescyt'] ?? '');
    setSafeValue($template, 'recepcionista_cumple', $s6['recepcionista_cumple'] ?? '');

    // =================================================================================
    // STEP 7: CONSEJO ACADÉMICO Y PERSONAL DOCENTE
    // =================================================================================
    
    // 7.1 Consejo Académico (Dynamic Table - Arrays)
    $consejoNombres = $s7['consejo_nombres'] ?? [];
    $countConsejo = count($consejoNombres);
    if ($countConsejo > 0) {
        try {
            $template->cloneRow('consejo_nombres', $countConsejo);
            for ($i = 0; $i < $countConsejo; $i++) {
                $row = $i + 1;
                setSafeValue($template, 'consejo_nombres#' . $row, $s7['consejo_nombres'][$i] ?? '');
                setSafeValue($template, 'consejo_cedula#' . $row, $s7['consejo_cedula'][$i] ?? '');
                setSafeValue($template, 'consejo_cargo#' . $row, $s7['consejo_cargo'][$i] ?? '');
                setSafeValue($template, 'consejo_acta#' . $row, $s7['consejo_acta'][$i] ?? '');
                setSafeValue($template, 'consejo_fecha#' . $row, $s7['consejo_fecha'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'consejo_nombres', '');
    }

    // 7.2 Personal Docente (Dynamic Table - Arrays)
    $docenteNombres = $s7['docente_nombres'] ?? [];
    $countDocentes = count($docenteNombres);
    if ($countDocentes > 0) {
        try {
            $template->cloneRow('docente_nombres', $countDocentes);
            for ($i = 0; $i < $countDocentes; $i++) {
                $row = $i + 1;
                setSafeValue($template, 'docente_tipo_curso#' . $row, $s7['docente_tipo_curso'][$i] ?? '');
                setSafeValue($template, 'docente_catedra#' . $row, $s7['docente_catedra'][$i] ?? '');
                setSafeValue($template, 'docente_nombres#' . $row, $s7['docente_nombres'][$i] ?? '');
                setSafeValue($template, 'docente_cedula#' . $row, $s7['docente_cedula'][$i] ?? '');
                setSafeValue($template, 'docente_titulo#' . $row, $s7['docente_titulo'][$i] ?? '');
                setSafeValue($template, 'docente_senescyt#' . $row, $s7['docente_senescyt'][$i] ?? '');
                setSafeValue($template, 'docente_experiencia#' . $row, $s7['docente_experiencia'][$i] ?? '');
                setSafeValue($template, 'docente_cumple#' . $row, $s7['docente_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'docente_nombres', '');
    }

    // 7.3 Instructores (Dynamic Table - Arrays)
    $instructorNombres = $s7['instructor_nombres'] ?? [];
    $countInstructores = count($instructorNombres);
    if ($countInstructores > 0) {
        try {
            $template->cloneRow('instructor_nombres', $countInstructores);
            for ($i = 0; $i < $countInstructores; $i++) {
                $row = $i + 1;
                setSafeValue($template, 'instructor_nombres#' . $row, $s7['instructor_nombres'][$i] ?? '');
                setSafeValue($template, 'instructor_cedula#' . $row, $s7['instructor_cedula'][$i] ?? '');
                setSafeValue($template, 'instructor_tipo_licencia#' . $row, $s7['instructor_tipo_licencia'][$i] ?? '');
                setSafeValue($template, 'instructor_tipo_curso#' . $row, $s7['instructor_tipo_curso'][$i] ?? '');
                setSafeValue($template, 'instructor_puntos#' . $row, $s7['instructor_puntos'][$i] ?? '');
                setSafeValue($template, 'instructor_experiencia#' . $row, $s7['instructor_experiencia'][$i] ?? '');
                setSafeValue($template, 'instructor_fecha_emision#' . $row, $s7['instructor_fecha_emision'][$i] ?? '');
                setSafeValue($template, 'instructor_fecha_caducidad#' . $row, $s7['instructor_fecha_caducidad'][$i] ?? '');
                setSafeValue($template, 'instructor_solvencia#' . $row, $s7['instructor_solvencia'][$i] ?? '');
                setSafeValue($template, 'instructor_certificado#' . $row, $s7['instructor_certificado'][$i] ?? '');
                setSafeValue($template, 'instructor_cumple#' . $row, $s7['instructor_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'instructor_nombres', '');
    }

    // =================================================================================
    // STEP 8: FINALIZACIÓN Y REPORTES (Tablas Grandes)
    // =================================================================================

    // 1. Control de Cursos Autorizados
    $controlAut = $s8['control_autorizacion'] ?? []; // We use this array count determines rows
    $countCC = count($controlAut);
    
    if ($countCC > 0) {
        try {
            // Updated to cloneBlock to repeat the entire Table structure
            // User must wrap the table and title in ${bloque_cursos} ... ${/bloque_cursos} in the Word template
            $template->cloneBlock('bloque_cursos', $countCC, true, true);
            
            for ($i = 0; $i < $countCC; $i++) {
                $row = $i + 1;
                setSafeValue($template, 'cc_aut#' . $row, $s8['control_autorizacion'][$i] ?? '');
                setSafeValue($template, 'cc_fecha#' . $row, $s8['control_fecha'][$i] ?? '');
                setSafeValue($template, 'cc_tipo_curso#' . $row, $s8['control_tipo_cursos'][$i] ?? '');
                
                setSafeValue($template, 'cc_m_ini#' . $row, $s8['control_matr_ini'][$i] ?? '');
                setSafeValue($template, 'cc_m_fin#' . $row, $s8['control_matr_fin'][$i] ?? '');
                
                setSafeValue($template, 'cc_t_lv_ini#' . $row, $s8['control_teo_lv_ini'][$i] ?? '');
                setSafeValue($template, 'cc_t_lv_fin#' . $row, $s8['control_teo_lv_fin'][$i] ?? '');
                setSafeValue($template, 'cc_t_fds_ini#' . $row, $s8['control_teo_fds_ini'][$i] ?? '');
                setSafeValue($template, 'cc_t_fds_fin#' . $row, $s8['control_teo_fds_fin'][$i] ?? '');
                
                setSafeValue($template, 'cc_p_lv_ini#' . $row, $s8['control_prac_lv_ini'][$i] ?? '');
                setSafeValue($template, 'cc_p_lv_fin#' . $row, $s8['control_prac_lv_fin'][$i] ?? '');
                setSafeValue($template, 'cc_p_fds_ini#' . $row, $s8['control_prac_fds_ini'][$i] ?? '');
                setSafeValue($template, 'cc_p_fds_fin#' . $row, $s8['control_prac_fds_fin'][$i] ?? '');
                
                setSafeValue($template, 'cc_a_aut#' . $row, $s8['control_alum_aut'][$i] ?? '');
                setSafeValue($template, 'cc_a_mat#' . $row, $s8['control_alum_mat'][$i] ?? '');
                setSafeValue($template, 'cc_a_grad#' . $row, $s8['control_alum_grad'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        // If no courses, remove the block entirely
        $template->cloneBlock('bloque_cursos', 0);
    }

    // 2. Registro de Estudiantes
    $estudiantes = $s8['estudiantes'] ?? [];
    $countEst = count($estudiantes);
    
    if ($countEst > 0) {
        try {
            // Using cloneBlock for Estudiantes to duplicate the whole table/section per student
            $template->cloneBlock('bloque_estudiantes', $countEst, true, true);
            
            for ($i = 0; $i < $countEst; $i++) {
                $row = $i + 1;
                $est = $estudiantes[$i] ?? [];
                
                setSafeValue($template, 'est_nom#' . $row, $est['nombres'] ?? '');
                setSafeValue($template, 'est_ced#' . $row, $est['cedula'] ?? '');
                setSafeValue($template, 'est_mem#' . $row, $est['nombre_oficio'] ?? '');
                setSafeValue($template, 'est_tipo#' . $row, $est['tipo_curso'] ?? '');
                setSafeValue($template, 'est_lic#' . $row, $est['licencia_posee'] ?? '');
                setSafeValue($template, 'est_lic_f#' . $row, $est['fecha_licencia'] ?? '');
                setSafeValue($template, 'est_edad#' . $row, $est['edad'] ?? '');
                setSafeValue($template, 'est_instr#' . $row, $est['nivel_instruccion'] ?? '');
                setSafeValue($template, 'est_psico#' . $row, $est['valoracion_psico'] ?? '');
                setSafeValue($template, 'est_med#' . $row, $est['certificado_medico'] ?? '');
                setSafeValue($template, 'est_matr#' . $row, $est['matricula'] ?? '');
                setSafeValue($template, 'est_perm#' . $row, $est['emision_permiso'] ?? '');
                setSafeValue($template, 'est_jorn#' . $row, $est['jornadas'] ?? '');

                // --- MATERIAS / ASIGNATURAS (Nested Dynamic Row) ---
                $materias = $est['materias'] ?? [];
                $countMat = count($materias);
                // The placeholder base name inside the template must be 'mat_nombre'
                // After cloneBlock, it becomes 'mat_nombre#1' for student 1
                $matKey = 'mat_nombre#' . $row;
                
                if ($countMat > 0) {
                    try {
                        // Clone the subject row S within Student P
                        $template->cloneRow($matKey, $countMat);
                        
                        for ($j = 0; $j < $countMat; $j++) {
                            $mRow = $j + 1;
                            $mat = $materias[$j] ?? [];
                            
                            // Construct nested key: base#studentRow#subjectRow
                            $suffix = '#' . $row . '#' . $mRow;
                            
                            setSafeValue($template, 'mat_nombre' . $suffix, $mat['materia'] ?? '');
                            setSafeValue($template, 'mat_notas' . $suffix, $mat['calificaciones'] ?? '');
                            setSafeValue($template, 'mat_asist' . $suffix, $mat['asistencia'] ?? '');
                            setSafeValue($template, 'mat_tipo' . $suffix, $mat['clase_tipo'] ?? '');
                            setSafeValue($template, 'mat_ini' . $suffix, $mat['fecha_inicio'] ?? '');
                            setSafeValue($template, 'mat_fin' . $suffix, $mat['fecha_fin'] ?? '');
                        }
                    } catch (\Exception $e) { 
                       // Fallback if row not found (user didn't put tags)
                    }
                } else {
                    setSafeValue($template, $matKey, '');
                    setSafeValue($template, 'mat_notas#' . $row, '');
                    setSafeValue($template, 'mat_asist#' . $row, '');
                    setSafeValue($template, 'mat_tipo#' . $row, '');
                    setSafeValue($template, 'mat_ini#' . $row, '');
                    setSafeValue($template, 'mat_fin#' . $row, '');
                }
            }
        } catch (\Exception $e) { }
    } else {
        // If no students, remove the block
        $template->cloneBlock('bloque_estudiantes', 0);
    }

    // 3. Costos de Cursos
    $costosNom = $s8['costo_nombres'] ?? [];
    $countCostos = count($costosNom);
    
    if ($countCostos > 0) {
         try {
            $template->cloneRow('cost_nom', $countCostos);
            for ($i = 0; $i < $countCostos; $i++) {
                $row = $i + 1;
                setSafeValue($template, 'cost_nom#' . $row, $s8['costo_nombres'][$i] ?? '');
                setSafeValue($template, 'cost_tipo#' . $row, $s8['costo_tipo_curso'][$i] ?? '');
                setSafeValue($template, 'cost_fact#' . $row, $s8['costo_num_factura'][$i] ?? '');
                setSafeValue($template, 'cost_fecha#' . $row, $s8['costo_fecha_factura'][$i] ?? '');
                setSafeValue($template, 'cost_v_cur#' . $row, $s8['costo_valor_curso'][$i] ?? '');
                setSafeValue($template, 'cost_v_perm#' . $row, $s8['costo_valor_permiso'][$i] ?? '');
                setSafeValue($template, 'cost_v_ex#' . $row, $s8['costo_valor_examen'][$i] ?? '');
            }
         } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'cost_nom', '');
    }

    // 4. Campañas Viales
    $campNro = $s8['camp_nro'] ?? [];
    $countCamp = count($campNro);
    
    if ($countCamp > 0) {
        try {
            $template->cloneRow('camp_nro', $countCamp);
            for ($i = 0; $i < $countCamp; $i++) {
                $row = $i + 1;
                setSafeValue($template, 'camp_nro#' . $row, $s8['camp_nro'][$i] ?? '');
                setSafeValue($template, 'camp_fecha#' . $row, $s8['camp_fecha'][$i] ?? '');
                setSafeValue($template, 'camp_benef#' . $row, $s8['camp_beneficiarios'][$i] ?? '');
                setSafeValue($template, 'camp_met#' . $row, $s8['camp_metodologia'][$i] ?? '');
                setSafeValue($template, 'camp_temas#' . $row, $s8['camp_temas'][$i] ?? '');
                setSafeValue($template, 'camp_prog#' . $row, $s8['camp_fecha_programacion'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        setSafeValue($template, 'camp_nro', '');
    }


    // --- Output File ---
    $tempFile = tempnam(sys_get_temp_dir(), 'word');
    $template->saveAs($tempFile);

    // LOCK LOGIC: Ensure report is marked as completed (Step 8 for Professionals)
    // Updated to lock on step 8 as per new flow
    // ARCHIVING LOGIC:
    // 1. Save to Archive
    // 2. Reset Active Progress
    // 3. Clear Session
    
    if (!isset($_GET['admin_download_user_id']) && !isset($_GET['archive_mode'])) {
        require_once '../includes/db_connection.php';
        
        // Prepare data for archive
        $formDataJson = json_encode($_SESSION['form_data'] ?? []);
        $schoolName = $_SESSION['form_data']['step1']['escuela_nombre'] ?? 'Escuela Profesional';
        
        // Insert into Archive
        $archStmt = $pdo->prepare("INSERT INTO reports_archive (user_id, school_type, school_name, form_data) VALUES (?, 'professional', ?, ?)");
        $archStmt->execute([$_SESSION['user_id'], $schoolName, $formDataJson]);
        
        // Reset Active Progress (Clear it for new report)
        // We set form_data to empty object to satisfy JSON validity if strict, but empty string is often fine or {} 
        $resetStmt = $pdo->prepare("UPDATE user_progress SET form_data = '{}', last_step = 0, updated_at = NOW() WHERE user_id = ?");
        $resetStmt->execute([$_SESSION['user_id']]);
        
        // Clear Edit Requests if any (optional, but good practice to close them)
        $lockStmt = $pdo->prepare("UPDATE edit_requests SET status = 'completed' WHERE user_id = ? AND status = 'approved'");
        $lockStmt->execute([$_SESSION['user_id']]);

        // Clear Session to force fresh start on next text load
        unset($_SESSION['form_data']);
        unset($_SESSION['last_step']);
    }

    // Force download
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment; filename="Informe_Profesional_' . date('Ymd_His') . '.docx"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($tempFile));
    readfile($tempFile);
    unlink($tempFile);
    exit;

} catch (Exception $e) {
    die("Error al generar el documento: " . $e->getMessage());
}
