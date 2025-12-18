<?php
session_start();
require_once '../vendor/autoload.php';

if (!isset($_SESSION['user_id'])) {
    die("Acceso denegado.");
}

// Load session data by default
$userData = $_SESSION['form_data'] ?? [];

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
                 // Set a flag to skip archiving this download
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
$s9 = $userData['step9'] ?? []; // GAD & Vehicles
$s10 = $userData['step10'] ?? []; // Extra Equipment
$s11 = $userData['step11'] ?? []; // Personnel
$s12 = $userData['step12'] ?? []; // Teachers
$s13 = $userData['step13'] ?? []; // Students

// Path to template
$templatePath = '../templates/no_profesionales.docx';
if (!file_exists($templatePath)) {
    die("Error: No se encuentra la plantilla en $templatePath");
}

try {
    // Validation removed - user requested no field validation on Word generation

    $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

    // =================================================================================
    // HELPER FUNCTIONS
    // =================================================================================
    
    /**
     * Helper to safely set a scalar value. If it's an array, logic needs to be different.
     */
    function setSafeValue($template, $key, $value) {
        if (is_array($value)) {
            // If we receive an array for a seemingly scalar field, take the first one or implode
             $template->setValue($key, implode(', ', $value));
        } else {
            $template->setValue($key, $value ?? '');
        }
    }

    // =================================================================================
    // STEP 1: Datos Informativos (Scalar)
    // =================================================================================
    $scalars1 = ['escuela_nombre','provincia','canton','ruc','telefono_fijo','telefono','email','gerente_nombre','direccion_insitu','direccion_ant','direccion_sri'];
    foreach ($scalars1 as $k) { setSafeValue($template, $k, $s1[$k] ?? ''); }

    // =================================================================================
    // STEP 2: Documentos Habilitantes (Scalar)
    // =================================================================================
    $scalars2 = ['resolucion_nro','cursos','resoluciones_extra','estado_sri','patente_municipal','impuesto_predial'];
    foreach ($scalars2 as $k) { setSafeValue($template, $k, $s2[$k] ?? ''); }

    // =================================================================================
    // STEP 3: Area Administrativa (Scalar - Radios)
    // =================================================================================
    $areas = [
        'direccion_general', 'inspeccion', 'adm_secretaria_academica', 
        'adm_contabilidad_tesoreria', 'adm_educacion_seguridad_vial', 
        'adm_sala_espera_recepcion', 'adm_recepcion'
    ];
    foreach ($areas as $area) {
        setSafeValue($template, $area . '_existe', $s3[$area . '_existe'] ?? '');
        setSafeValue($template, $area . '_acceso', $s3[$area . '_acceso'] ?? '');
    }

    // =================================================================================
    // STEP 4: Seguridad (Dynamic)
    // =================================================================================
    // Data: area_detalle[], rotulos_salida[], senales_incendios[]
    // Key row for valid clone: area_detalle
    $countS4 = isset($s4['area_detalle']) ? count($s4['area_detalle']) : 0;
    
    if ($countS4 > 0) {
        try {
            $template->cloneRow('area_detalle', $countS4);
            for ($i = 0; $i < $countS4; $i++) {
                $row = $i + 1;
                $template->setValue('area_detalle#' . $row, $s4['area_detalle'][$i] ?? '');
                $template->setValue('rotulos_salida#' . $row, $s4['rotulos_salida'][$i] ?? '');
                $template->setValue('senales_incendios#' . $row, $s4['senales_incendios'][$i] ?? '');
            }
        } catch (\Exception $e) { /* Ignore clone error if key missing */ }
    } else {
        // Clear placeholders if no data
        $template->setValue('area_detalle', '');
        $template->setValue('rotulos_salida', '');
        $template->setValue('senales_incendios', '');
    }

    // =================================================================================
    // STEP 5: Aulas (Dynamic)
    // =================================================================================
    $countS5 = isset($s5['aul_num']) ? count($s5['aul_num']) : 0;
    if ($countS5 > 0) {
        try {
            $template->cloneRow('aul_num', $countS5);
            for ($i = 0; $i < $countS5; $i++) {
                $row = $i + 1;
                $template->setValue('aul_num#' . $row, $s5['aul_num'][$i] ?? '');
                $template->setValue('aul_tipo#' . $row, $s5['aul_tipo'][$i] ?? '');
                $template->setValue('aul_res_func#' . $row, $s5['aul_res_func'][$i] ?? '');
                $template->setValue('aul_cap#' . $row, $s5['aul_cap'][$i] ?? '');
                $template->setValue('aul_ventilacion#' . $row, $s5['aul_ventilacion'][$i] ?? '');
                $template->setValue('aul_iluminacion#' . $row, $s5['aul_iluminacion'][$i] ?? '');
                $template->setValue('aul_tec#' . $row, $s5['aul_tec'][$i] ?? '');
                $template->setValue('aul_pizarra#' . $row, $s5['aul_pizarra'][$i] ?? '');
                $template->setValue('aul_otro_mat#' . $row, $s5['aul_otro_mat'][$i] ?? '');
                $template->setValue('aul_estacion#' . $row, $s5['aul_estacion'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        $template->setValue('aul_num', '');
    }

    // =================================================================================
    // STEP 6: Aula Taller & Plataforma (Dynamic - Two Sections)
    // =================================================================================
    // Part A: Taller (taller_curso[])
    $countS6Taller = isset($s6['taller_curso']) ? count($s6['taller_curso']) : 0;
    if ($countS6Taller > 0) {
        try {
            $template->cloneRow('taller_curso', $countS6Taller);
            for ($i = 0; $i < $countS6Taller; $i++) {
                $row = $i + 1;
                $template->setValue('taller_curso#' . $row, $s6['taller_curso'][$i] ?? '');
                $template->setValue('taller_existe#' . $row, $s6['taller_existe'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        $template->setValue('taller_curso', '');
        $template->setValue('taller_existe', '');
    }

    // Part B: Plataforma (plat_curso[])
    $countS6Plat = isset($s6['plat_curso']) ? count($s6['plat_curso']) : 0;
    if ($countS6Plat > 0) {
        try {
            $template->cloneRow('plat_curso', $countS6Plat);
            for ($i = 0; $i < $countS6Plat; $i++) {
                $row = $i + 1;
                $template->setValue('plat_curso#' . $row, $s6['plat_curso'][$i] ?? '');
                $template->setValue('plat_existe#' . $row, $s6['plat_existe'][$i] ?? '');
                $template->setValue('plat_doc#' . $row, $s6['plat_doc'][$i] ?? '');
                $template->setValue('plat_matricula#' . $row, $s6['plat_matricula'][$i] ?? '');
                $template->setValue('plat_asistencia_in#' . $row, $s6['plat_asistencia_in'][$i] ?? '');
                $template->setValue('plat_asistencia_out#' . $row, $s6['plat_asistencia_out'][$i] ?? '');
                $template->setValue('plat_calif#' . $row, $s6['plat_calif'][$i] ?? '');
                $template->setValue('plat_interaccion#' . $row, $s6['plat_interaccion'][$i] ?? '');
                $template->setValue('plat_eval#' . $row, $s6['plat_eval'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else {
        $template->setValue('plat_curso', '');
    }

    // =================================================================================
    // STEP 7: Pista Motos (Scalar)
    // =================================================================================
    $scalars7 = [
        'pista_propiedad','fechaarrendada','pista_direccion','pista_predio','pista_ubikilome',
        'metros_pista','tipo_cerramiento','p_perimetrales','postes_altura','rampa_elevada',
        'rampa_material','dispositivos_cono','trave_num','num_indi'
    ];
    foreach ($scalars7 as $k) { setSafeValue($template, $k, $s7[$k] ?? ''); }

    // =================================================================================
    // STEP 8: Baterias Sanitarias (Scalar - Flat)
    // =================================================================================
    // Note: Step 8 fields are all scalar as verified previously
    $scalars8 = [
        'bs_num_hombres','bs_num_mujeres','inodoro','inodoro_h','urinario',
        'Lava_h','Lava_m','E_Lava_h','E_Lava_m','imp_jabon_h','imp_jabon_m',
        'manos_h','manos_m','imp_papel_higienico_h','imp_papel_higienico_m',
        'imp_basurero_tapa_h','imp_basurero_tapa_m','imp_alcohol_h','imp_alcohol_m',
        'ilum_central_h','ilum_central_m','bs_banos_discapacidad_h','bs_banos_discapacidad_m'
    ];
    foreach ($scalars8 as $k) { setSafeValue($template, $k, $s8[$k] ?? ''); }

    // =================================================================================
    // STEP 9: GAD & Vehiculos (Dynamic)
    // =================================================================================
    
    // 9.1 GAD (Escalar - Solo 1 registro permitido)
    // Se toma el primer elemento de los arrays si existen
    $template->setValue('gad_tipo', $s9['gad_tipo'][0] ?? '');
    $template->setValue('gad_autorizacion', $s9['gad_autorizacion'][0] ?? '');
    $template->setValue('gad_entidad', $s9['gad_entidad'][0] ?? '');
    $template->setValue('gad_fechacaducidad', $s9['gad_fechacaducidad'][0] ?? '');
    $template->setValue('gad_oficio', $s9['gad_oficio'][0] ?? '');

    // 9.2 Vehiculos Tipo A (veha_placa[])
    $countVehA = isset($s9['veha_placa']) ? count($s9['veha_placa']) : 0;
    if ($countVehA > 0) {
        try {
            $template->cloneRow('veha_placa', $countVehA);
            for ($i = 0; $i < $countVehA; $i++) {
                $row = $i + 1;
                $template->setValue('veha_placa#' . $row, $s9['veha_placa'][$i] ?? '');
                $template->setValue('veha_aut#' . $row, $s9['veha_aut'][$i] ?? '');
                $template->setValue('veha_modelo#' . $row, $s9['veha_modelo'][$i] ?? '');
                $template->setValue('veha_tipo#' . $row, $s9['veha_tipo'][$i] ?? '');
                $template->setValue('veha_anio#' . $row, $s9['veha_anio'][$i] ?? '');
                $template->setValue('veha_antena#' . $row, $s9['veha_antena'][$i] ?? '');
                $template->setValue('veha_banderola#' . $row, $s9['veha_banderola'][$i] ?? '');
                $template->setValue('veha_logos#' . $row, $s9['veha_logos'][$i] ?? '');
                $template->setValue('veha_pol_vig#' . $row, $s9['veha_pol_vig'][$i] ?? '');
                $template->setValue('veha_pol_100#' . $row, $s9['veha_pol_100'][$i] ?? '');
                $template->setValue('veha_mat_anual#' . $row, $s9['veha_mat_anual'][$i] ?? '');
                $template->setValue('veha_rev_tec#' . $row, $s9['veha_rev_tec'][$i] ?? '');
                $template->setValue('veha_mant#' . $row, $s9['veha_mant'][$i] ?? '');
                $template->setValue('veha_rastreo#' . $row, $s9['veha_rastreo'][$i] ?? '');
                $template->setValue('veha_ajenos#' . $row, $s9['veha_ajenos'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('veha_placa', ''); }

    // 9.3 Vehiculos Tipo B (veh_placa[])
    $countVehB = isset($s9['veh_placa']) ? count($s9['veh_placa']) : 0;
    if ($countVehB > 0) {
        try {
            $template->cloneRow('veh_placa', $countVehB);
            for ($i = 0; $i < $countVehB; $i++) {
                $row = $i + 1;
                $template->setValue('veh_placa#' . $row, $s9['veh_placa'][$i] ?? '');
                $template->setValue('veh_aut#' . $row, $s9['veh_aut'][$i] ?? '');
                $template->setValue('veh_chasis#' . $row, $s9['veh_chasis'][$i] ?? '');
                $template->setValue('veh_modelo#' . $row, $s9['veh_modelo'][$i] ?? '');
                $template->setValue('veh_tipo#' . $row, $s9['veh_tipo'][$i] ?? '');
                $template->setValue('veh_anio#' . $row, $s9['veh_anio'][$i] ?? '');
                $template->setValue('veh_espejo#' . $row, $s9['veh_espejo'][$i] ?? '');
                $template->setValue('veh_fr#' . $row, $s9['veh_fr'][$i] ?? '');
                $template->setValue('veh_post#' . $row, $s9['veh_post'][$i] ?? '');
                $template->setValue('veh_lat#' . $row, $s9['veh_lat'][$i] ?? '');
                $template->setValue('veh_lf#' . $row, $s9['veh_lf'][$i] ?? '');
                $template->setValue('veh_lp#' . $row, $s9['veh_lp'][$i] ?? '');
                $template->setValue('veh_ll#' . $row, $s9['veh_ll'][$i] ?? '');
                $template->setValue('veh_ls#' . $row, $s9['veh_ls'][$i] ?? '');
                $template->setValue('veh_lpost#' . $row, $s9['veh_lpost'][$i] ?? '');
                $template->setValue('veh_ld#' . $row, $s9['veh_ld'][$i] ?? '');
                $template->setValue('veh_ac#' . $row, $s9['veh_ac'][$i] ?? '');
                $template->setValue('veh_frn#' . $row, $s9['veh_frn'][$i] ?? '');
                $template->setValue('veh_emb#' . $row, $s9['veh_emb'][$i] ?? '');
                $template->setValue('veh_cob#' . $row, $s9['veh_cob'][$i] ?? '');
                $template->setValue('veh_pol_vigencia#' . $row, $s9['veh_pol_vigencia'][$i] ?? '');
                $template->setValue('veh_rev_anual#' . $row, $s9['veh_rev_anual'][$i] ?? '');
                $template->setValue('veh_rev_tec#' . $row, $s9['veh_rev_tec'][$i] ?? '');
                $template->setValue('veh_bitk#' . $row, $s9['veh_bitk'][$i] ?? '');
                $template->setValue('veh_bit#' . $row, $s9['veh_bit'][$i] ?? '');
                $template->setValue('veh_rs#' . $row, $s9['veh_rs'][$i] ?? '');
                $template->setValue('veh_ua#' . $row, $s9['veh_ua'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('veh_placa', ''); }

    // =================================================================================
    // STEP 10: Equipamiento Adicional (Dynamic)
    // =================================================================================
    
    // Chalecos Inst (chaleco_inst_nro[])
    $countCInst = isset($s10['chaleco_inst_nro']) ? count($s10['chaleco_inst_nro']) : 0;
    if ($countCInst > 0) {
        try {
            $template->cloneRow('chaleco_inst_nro', $countCInst);
            for ($i = 0; $i < $countCInst; $i++) {
                $row = $i + 1;
                $template->setValue('chaleco_inst_nro#' . $row, $s10['chaleco_inst_nro'][$i] ?? '');
                $template->setValue('chaleco_inst_placa#' . $row, $s10['chaleco_inst_placa'][$i] ?? '');
                $template->setValue('chaleco_inst_color#' . $row, $s10['chaleco_inst_color'][$i] ?? '');
                $template->setValue('chaleco_inst_palabra#' . $row, $s10['chaleco_inst_palabra'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('chaleco_inst_nro', ''); }

    // Chalecos Alum (chaleco_alum_nro[])
    $countCAlum = isset($s10['chaleco_alum_nro']) ? count($s10['chaleco_alum_nro']) : 0;
    if ($countCAlum > 0) {
        try {
            $template->cloneRow('chaleco_alum_nro', $countCAlum);
            for ($i = 0; $i < $countCAlum; $i++) {
                $row = $i + 1;
                $template->setValue('chaleco_alum_nro#' . $row, $s10['chaleco_alum_nro'][$i] ?? '');
                $template->setValue('chaleco_alum_color#' . $row, $s10['chaleco_alum_color'][$i] ?? '');
                $template->setValue('chaleco_alum_palabra#' . $row, $s10['chaleco_alum_palabra'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('chaleco_alum_nro', ''); }

    // Simulador (sim_marca[])
    $countSim = isset($s10['sim_marca']) ? count($s10['sim_marca']) : 0;
    if ($countSim > 0) {
        try {
            $template->cloneRow('sim_marca', $countSim);
            for ($i = 0; $i < $countSim; $i++) {
                $row = $i + 1;
                $template->setValue('sim_marca#' . $row, $s10['sim_marca'][$i] ?? '');
                $template->setValue('sim_modelo#' . $row, $s10['sim_modelo'][$i] ?? '');
                $template->setValue('sim_cert#' . $row, $s10['sim_cert'][$i] ?? '');
                $template->setValue('sim_tipo_curso#' . $row, $s10['sim_tipo_curso'][$i] ?? '');
                $template->setValue('sim_uso_prac#' . $row, $s10['sim_uso_prac'][$i] ?? '');
                $template->setValue('sim_pct_uso#' . $row, $s10['sim_pct_uso'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('sim_marca', ''); }

    // Biometrico (bio_equipo[])
    $countBio = isset($s10['bio_equipo']) ? count($s10['bio_equipo']) : 0;
    if ($countBio > 0) {
        try {
             // Try to clone if the template supports it, otherwise manual index might be needed if template logic changed
             // But assuming cloneRow is standard here:
            $template->cloneRow('bio_equipo', $countBio);
            for ($i = 0; $i < $countBio; $i++) {
                $row = $i + 1;
                $template->setValue('bio_equipo#' . $row, $s10['bio_equipo'][$i] ?? '');
                $template->setValue('bio_docente#' . $row, $s10['bio_docente'][$i] ?? '');
                $template->setValue('bio_alumno#' . $row, $s10['bio_alumno'][$i] ?? '');
                $template->setValue('bio_instructor#' . $row, $s10['bio_instructor'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('bio_equipo', ''); }
    
    // Psicosensometrico (psico_equipo[])
    $countPsico = isset($s10['psico_equipo']) ? count($s10['psico_equipo']) : 0;
    if ($countPsico > 0) {
        try {
            $template->cloneRow('psico_equipo', $countPsico);
            for ($i = 0; $i < $countPsico; $i++) {
                $row = $i + 1;
                $template->setValue('psico_equipo#' . $row, $s10['psico_equipo'][$i] ?? '');
                $template->setValue('psico_modelo#' . $row, $s10['psico_modelo'][$i] ?? '');
                $template->setValue('psico_cert#' . $row, $s10['psico_cert'][$i] ?? '');
                $template->setValue('psico_eval_aud#' . $row, $s10['psico_eval_aud'][$i] ?? '');
                $template->setValue('psico_eval_psico#' . $row, $s10['psico_eval_psico'][$i] ?? '');
                $template->setValue('psico_eval_vis#' . $row, $s10['psico_eval_vis'][$i] ?? '');
                $template->setValue('psico_ubic#' . $row, $s10['psico_ubic'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('psico_equipo', ''); }

    // =================================================================================
    // STEP 11: Personal y Expedientes (Dynamic)
    // =================================================================================
    
    // Expedientes (exp_archivo[])
    $countExp = isset($s11['exp_archivo']) ? count($s11['exp_archivo']) : 0;
    if ($countExp > 0) {
         try {
            $template->cloneRow('exp_archivo', $countExp);
            for ($i = 0; $i < $countExp; $i++) {
                $row = $i + 1;
                $template->setValue('exp_archivo#' . $row, $s11['exp_archivo'][$i] ?? '');
                $template->setValue('exp_docs#' . $row, $s11['exp_docs'][$i] ?? '');
                $template->setValue('exp_asistencia#' . $row, $s11['exp_asistencia'][$i] ?? '');
                $template->setValue('exp_pruebas_teo#' . $row, $s11['exp_pruebas_teo'][$i] ?? '');
                $template->setValue('exp_pruebas_prac#' . $row, $s11['exp_pruebas_prac'][$i] ?? '');
                $template->setValue('exp_calificaciones#' . $row, $s11['exp_calificaciones'][$i] ?? '');
                $template->setValue('exp_permiso#' . $row, $s11['exp_permiso'][$i] ?? '');
                $template->setValue('exp_doc_aprob#' . $row, $s11['exp_doc_aprob'][$i] ?? '');
                $template->setValue('exp_certificado#' . $row, $s11['exp_certificado'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('exp_archivo', ''); }
    
    // Dir Adm (dir_adm_nombres[])
    $countDirAdm = isset($s11['dir_adm_nombres']) ? count($s11['dir_adm_nombres']) : 0;
    if ($countDirAdm > 0) {
         try {
            $template->cloneRow('dir_adm_nombres', $countDirAdm);
            for ($i = 0; $i < $countDirAdm; $i++) {
                $row = $i + 1;
                $template->setValue('dir_adm_fecha', $s11['dir_adm_fecha'][0] ?? '');
                $template->setValue('dir_adm_fecha#' . $row, $s11['dir_adm_fecha'][$i] ?? '');
                $template->setValue('dir_adm_nombres#' . $row, $s11['dir_adm_nombres'][$i] ?? '');
                $template->setValue('dir_adm_cedula#' . $row, $s11['dir_adm_cedula'][$i] ?? '');
                $template->setValue('dir_adm_gerencia#' . $row, $s11['dir_adm_gerencia'][$i] ?? '');
                $template->setValue('dir_adm_experiencia#' . $row, $s11['dir_adm_experiencia'][$i] ?? '');
                $template->setValue('dir_adm_cargo_publico#' . $row, $s11['dir_adm_cargo_publico'][$i] ?? '');
                $template->setValue('dir_adm_cumple#' . $row, $s11['dir_adm_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('dir_adm_nombres', ''); }

    // Dir General (dir_gen_nombres[])
    $countDirGen = isset($s11['dir_gen_nombres']) ? count($s11['dir_gen_nombres']) : 0;
    if ($countDirGen > 0) {
         try {
            $template->cloneRow('dir_gen_nombres', $countDirGen);
            for ($i = 0; $i < $countDirGen; $i++) {
                $row = $i + 1;
                $template->setValue('dir_gen_fecha', $s11['dir_gen_fecha'][0] ?? '');
                $template->setValue('dir_gen_fecha#' . $row, $s11['dir_gen_fecha'][$i] ?? '');
                $template->setValue('dir_gen_nombres#' . $row, $s11['dir_gen_nombres'][$i] ?? '');
                $template->setValue('dir_gen_cedula#' . $row, $s11['dir_gen_cedula'][$i] ?? '');
                $template->setValue('dir_gen_experiencia#' . $row, $s11['dir_gen_experiencia'][$i] ?? '');
                $template->setValue('dir_gen_idoneidad#' . $row, $s11['dir_gen_idoneidad'][$i] ?? '');
                $template->setValue('dir_gen_cumple#' . $row, $s11['dir_gen_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('dir_gen_nombres', ''); }

    // Asesor (asesor_nombres[])
    $countAsesor = isset($s11['asesor_nombres']) ? count($s11['asesor_nombres']) : 0;
    if ($countAsesor > 0) {
         try {
            $template->cloneRow('asesor_nombres', $countAsesor);
            for ($i = 0; $i < $countAsesor; $i++) {
                $row = $i + 1;
                $template->setValue('asesor_fecha', $s11['asesor_fecha'][0] ?? '');
                $template->setValue('asesor_fecha#' . $row, $s11['asesor_fecha'][$i] ?? '');
                $template->setValue('asesor_nombres#' . $row, $s11['asesor_nombres'][$i] ?? '');
                $template->setValue('asesor_cedula#' . $row, $s11['asesor_cedula'][$i] ?? '');
                $template->setValue('asesor_titulo#' . $row, $s11['asesor_titulo'][$i] ?? '');
                $template->setValue('asesor_senescyt#' . $row, $s11['asesor_senescyt'][$i] ?? '');
                $template->setValue('asesor_experiencia#' . $row, $s11['asesor_experiencia'][$i] ?? '');
                $template->setValue('asesor_horas#' . $row, $s11['asesor_horas'][$i] ?? '');
                $template->setValue('asesor_doc_pasivo#' . $row, $s11['asesor_doc_pasivo'][$i] ?? '');
                $template->setValue('asesor_cargo_publico#' . $row, $s11['asesor_cargo_publico'][$i] ?? '');
                $template->setValue('asesor_cumple#' . $row, $s11['asesor_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('asesor_nombres', ''); }

    // Asesor 2 (asesor2_nombres[]) - Section 2
    $countAsesor2 = isset($s11['asesor2_nombres']) ? count($s11['asesor2_nombres']) : 0;
    if ($countAsesor2 > 0) {
         try {
            $template->cloneRow('asesor2_nombres', $countAsesor2);
            for ($i = 0; $i < $countAsesor2; $i++) {
                $row = $i + 1;
                $template->setValue('asesor2_fecha', $s11['asesor2_fecha'][0] ?? '');
                $template->setValue('asesor2_fecha#' . $row, $s11['asesor2_fecha'][$i] ?? '');
                $template->setValue('asesor2_nombres#' . $row, $s11['asesor2_nombres'][$i] ?? '');
                $template->setValue('asesor2_cedula#' . $row, $s11['asesor2_cedula'][$i] ?? '');
                $template->setValue('asesor2_titulo#' . $row, $s11['asesor2_titulo'][$i] ?? '');
                $template->setValue('asesor2_senescyt#' . $row, $s11['asesor2_senescyt'][$i] ?? '');
                $template->setValue('asesor2_experiencia#' . $row, $s11['asesor2_experiencia'][$i] ?? '');
                $template->setValue('asesor2_doc_pasivo#' . $row, $s11['asesor2_doc_pasivo'][$i] ?? '');
                $template->setValue('asesor2_cumple#' . $row, $s11['asesor2_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('asesor2_nombres', ''); }

    // 7. Supervisor (sup_nombres[]) - RE-ADDED AS SECTION 7
    $countSup = isset($s11['sup_nombres']) ? count($s11['sup_nombres']) : 0;
    if ($countSup > 0) {
         try {
            $template->cloneRow('sup_nombres', $countSup);
            for ($i = 0; $i < $countSup; $i++) {
                $row = $i + 1;
                $template->setValue('sup_fecha', $s11['sup_fecha'][0] ?? '');
                $template->setValue('sup_fecha#' . $row, $s11['sup_fecha'][$i] ?? '');
                $template->setValue('sup_nombres#' . $row, $s11['sup_nombres'][$i] ?? '');
                $template->setValue('sup_cedula#' . $row, $s11['sup_cedula'][$i] ?? '');
                $template->setValue('sup_titulo#' . $row, $s11['sup_titulo'][$i] ?? '');
                $template->setValue('sup_senescyt#' . $row, $s11['sup_senescyt'][$i] ?? '');
                $template->setValue('sup_experiencia#' . $row, $s11['sup_experiencia'][$i] ?? '');
                $template->setValue('sup_lottsv#' . $row, $s11['sup_lottsv'][$i] ?? '');
                $template->setValue('sup_cumple#' . $row, $s11['sup_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('sup_nombres', ''); }

    // 6. Inspector/a Supervisor/a (insp_nombres[]) - NEW
    $countInsp = isset($s11['insp_nombres']) ? count($s11['insp_nombres']) : 0;
    if ($countInsp > 0) {
         try {
            $template->cloneRow('insp_nombres', $countInsp);
            for ($i = 0; $i < $countInsp; $i++) {
                $row = $i + 1;
                $template->setValue('insp_fecha', $s11['insp_fecha'][0] ?? '');
                $template->setValue('insp_fecha#' . $row, $s11['insp_fecha'][$i] ?? '');
                $template->setValue('insp_nombres#' . $row, $s11['insp_nombres'][$i] ?? '');
                $template->setValue('insp_cedula#' . $row, $s11['insp_cedula'][$i] ?? '');
                $template->setValue('insp_titulo#' . $row, $s11['insp_titulo'][$i] ?? '');
                $template->setValue('insp_senescyt#' . $row, $s11['insp_senescyt'][$i] ?? '');
                $template->setValue('insp_experiencia#' . $row, $s11['insp_experiencia'][$i] ?? '');
                $template->setValue('insp_experiencia_admin#' . $row, $s11['insp_experiencia_admin'][$i] ?? '');
                $template->setValue('insp_ex_miembro#' . $row, $s11['insp_ex_miembro'][$i] ?? '');
                $template->setValue('insp_cargo_publico#' . $row, $s11['insp_cargo_publico'][$i] ?? '');
                $template->setValue('insp_ofimatica#' . $row, $s11['insp_ofimatica'][$i] ?? '');
                $template->setValue('insp_experiencia_tttsv#' . $row, $s11['insp_experiencia_tttsv'][$i] ?? '');
                $template->setValue('insp_horas_capacitacion#' . $row, $s11['insp_horas_capacitacion'][$i] ?? '');
                $template->setValue('insp_cumple#' . $row, $s11['insp_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('sup_nombres', ''); }

    // 6. Inspector/a Supervisor/a (insp_sup_nombres[]) - NEW SECTION
    $countInspSup = isset($s11['insp_sup_nombres']) ? count($s11['insp_sup_nombres']) : 0;
    if ($countInspSup > 0) {
         try {
            $template->cloneRow('insp_sup_nombres', $countInspSup);
            for ($i = 0; $i < $countInspSup; $i++) {
                $row = $i + 1;
                $template->setValue('insp_sup_fecha', $s11['insp_sup_fecha'][0] ?? '');
                $template->setValue('insp_sup_fecha#' . $row, $s11['insp_sup_fecha'][$i] ?? '');
                $template->setValue('insp_sup_nombres#' . $row, $s11['insp_sup_nombres'][$i] ?? '');
                $template->setValue('insp_sup_cedula#' . $row, $s11['insp_sup_cedula'][$i] ?? '');
                $template->setValue('insp_sup_titulo#' . $row, $s11['insp_sup_titulo'][$i] ?? '');
                $template->setValue('insp_sup_senescyt#' . $row, $s11['insp_sup_senescyt'][$i] ?? '');
                $template->setValue('insp_sup_exp_instruccion#' . $row, $s11['insp_sup_exp_instruccion'][$i] ?? '');
                $template->setValue('insp_sup_exp_admin#' . $row, $s11['insp_sup_exp_admin'][$i] ?? '');
                $template->setValue('insp_sup_ex_exp#' . $row, $s11['insp_sup_ex_exp'][$i] ?? '');
                $template->setValue('insp_sup_ex_horas#' . $row, $s11['insp_sup_ex_horas'][$i] ?? '');
                $template->setValue('insp_sup_cargo#' . $row, $s11['insp_sup_cargo'][$i] ?? '');
                $template->setValue('insp_sup_ofimatica#' . $row, $s11['insp_sup_ofimatica'][$i] ?? '');
                $template->setValue('insp_sup_cumple#' . $row, $s11['insp_sup_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('insp_sup_nombres', ''); }

    // 7. Supervisor (sup_nombres[]) - RE-ADDED AS SECTION 7
    $countSup = isset($s11['sup_nombres']) ? count($s11['sup_nombres']) : 0;
    if ($countSup > 0) {
         try {
            $template->cloneRow('sup_nombres', $countSup);
            for ($i = 0; $i < $countSup; $i++) {
                $row = $i + 1;
                $template->setValue('sup_fecha', $s11['sup_fecha'][0] ?? '');
                $template->setValue('sup_fecha#' . $row, $s11['sup_fecha'][$i] ?? '');
                $template->setValue('sup_nombres#' . $row, $s11['sup_nombres'][$i] ?? '');
                $template->setValue('sup_cedula#' . $row, $s11['sup_cedula'][$i] ?? '');
                $template->setValue('sup_titulo#' . $row, $s11['sup_titulo'][$i] ?? '');
                $template->setValue('sup_senescyt#' . $row, $s11['sup_senescyt'][$i] ?? '');
                $template->setValue('sup_experiencia#' . $row, $s11['sup_experiencia'][$i] ?? '');
                $template->setValue('sup_lottsv#' . $row, $s11['sup_lottsv'][$i] ?? '');
                $template->setValue('sup_cumple#' . $row, $s11['sup_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('sup_nombres', ''); }

    // 8. Secretario/a (sec_nombres[]) - Section 8
    $countSec = isset($s11['sec_nombres']) ? count($s11['sec_nombres']) : 0;
    if ($countSec > 0) {
         try {
            $template->cloneRow('sec_nombres', $countSec);
            for ($i = 0; $i < $countSec; $i++) {
                $row = $i + 1;
                $template->setValue('sec_fecha', $s11['sec_fecha'][0] ?? '');
                $template->setValue('sec_fecha#' . $row, $s11['sec_fecha'][$i] ?? '');
                $template->setValue('sec_nombres#' . $row, $s11['sec_nombres'][$i] ?? '');
                $template->setValue('sec_cedula#' . $row, $s11['sec_cedula'][$i] ?? '');
                $template->setValue('sec_titulo#' . $row, $s11['sec_titulo'][$i] ?? '');
                $template->setValue('sec_senescyt#' . $row, $s11['sec_senescyt'][$i] ?? '');
                $template->setValue('sec_experiencia#' . $row, $s11['sec_experiencia'][$i] ?? '');
                $template->setValue('sec_ofimatica#' . $row, $s11['sec_ofimatica'][$i] ?? '');
                $template->setValue('sec_cargo_publico#' . $row, $s11['sec_cargo_publico'][$i] ?? '');
                $template->setValue('sec_cumple#' . $row, $s11['sec_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('sec_nombres', ''); }

    // 9. Secretario/a (sec2_nombres[]) - Section 9
    $countSec2 = isset($s11['sec2_nombres']) ? count($s11['sec2_nombres']) : 0;
    if ($countSec2 > 0) {
         try {
            $template->cloneRow('sec2_nombres', $countSec2);
            for ($i = 0; $i < $countSec2; $i++) {
                $row = $i + 1;
                $template->setValue('sec2_fecha', $s11['sec2_fecha'][0] ?? '');
                $template->setValue('sec2_fecha#' . $row, $s11['sec2_fecha'][$i] ?? '');
                $template->setValue('sec2_nombres#' . $row, $s11['sec2_nombres'][$i] ?? '');
                $template->setValue('sec2_cedula#' . $row, $s11['sec2_cedula'][$i] ?? '');
                $template->setValue('sec2_experiencia#' . $row, $s11['sec2_experiencia'][$i] ?? '');
                $template->setValue('sec2_cumple#' . $row, $s11['sec2_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('sec2_nombres', ''); }

    // 10. Contador/a y/o Tesorero/a (cont_nombres[]) - Section 10
    $countCont = isset($s11['cont_nombres']) ? count($s11['cont_nombres']) : 0;
    if ($countCont > 0) {
         try {
            $template->cloneRow('cont_nombres', $countCont);
            for ($i = 0; $i < $countCont; $i++) {
                $row = $i + 1;
                $template->setValue('cont_fecha', $s11['cont_fecha'][0] ?? '');
                $template->setValue('cont_fecha#' . $row, $s11['cont_fecha'][$i] ?? '');
                $template->setValue('cont_cargo#' . $row, $s11['cont_cargo'][$i] ?? '');
                $template->setValue('cont_nombres#' . $row, $s11['cont_nombres'][$i] ?? '');
                $template->setValue('cont_cedula#' . $row, $s11['cont_cedula'][$i] ?? '');
                $template->setValue('cont_titulo#' . $row, $s11['cont_titulo'][$i] ?? '');
                $template->setValue('cont_senescyt#' . $row, $s11['cont_senescyt'][$i] ?? '');
                $template->setValue('cont_experiencia#' . $row, $s11['cont_experiencia'][$i] ?? '');
                $template->setValue('cont_cargo_publico#' . $row, $s11['cont_cargo_publico'][$i] ?? '');
                $template->setValue('cont_cumple#' . $row, $s11['cont_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('cont_nombres', ''); }

    // 11. Tesorero/a (tes_nombres[]) - Section 11
    $countTes = isset($s11['tes_nombres']) ? count($s11['tes_nombres']) : 0;
    if ($countTes > 0) {
         try {
            $template->cloneRow('tes_nombres', $countTes);
            for ($i = 0; $i < $countTes; $i++) {
                $row = $i + 1;
                $template->setValue('tes_fecha', $s11['tes_fecha'][0] ?? '');
                $template->setValue('tes_fecha#' . $row, $s11['tes_fecha'][$i] ?? '');
                $template->setValue('tes_cargo#' . $row, $s11['tes_cargo'][$i] ?? '');
                $template->setValue('tes_nombres#' . $row, $s11['tes_nombres'][$i] ?? '');
                $template->setValue('tes_cedula#' . $row, $s11['tes_cedula'][$i] ?? '');
                $template->setValue('tes_conocimientos#' . $row, $s11['tes_conocimientos'][$i] ?? '');
                $template->setValue('tes_caucion#' . $row, $s11['tes_caucion'][$i] ?? '');
                $template->setValue('tes_cumple#' . $row, $s11['tes_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('tes_nombres', ''); }

    // 12. Psicólogo/a (psic_nombres[]) - Section 12
    $countPsic = isset($s11['psic_nombres']) ? count($s11['psic_nombres']) : 0;
    if ($countPsic > 0) {
         try {
            $template->cloneRow('psic_nombres', $countPsic);
            for ($i = 0; $i < $countPsic; $i++) {
                $row = $i + 1;
                $template->setValue('psic_fecha', $s11['psic_fecha'][0] ?? '');
                $template->setValue('psic_fecha#' . $row, $s11['psic_fecha'][$i] ?? '');
                $template->setValue('psic_nombres#' . $row, $s11['psic_nombres'][$i] ?? '');
                $template->setValue('psic_cedula#' . $row, $s11['psic_cedula'][$i] ?? '');
                $template->setValue('psic_titulo#' . $row, $s11['psic_titulo'][$i] ?? '');
                $template->setValue('psic_senescyt#' . $row, $s11['psic_senescyt'][$i] ?? '');
                $template->setValue('psic_ofimatica#' . $row, $s11['psic_ofimatica'][$i] ?? '');
                $template->setValue('psic_capacitacion#' . $row, $s11['psic_capacitacion'][$i] ?? '');
                $template->setValue('psic_cargo_publico#' . $row, $s11['psic_cargo_publico'][$i] ?? '');
                $template->setValue('psic_cumple#' . $row, $s11['psic_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('psic_nombres', ''); }

    // 13. Psicólogo educativo (psic_edu_nombres[]) - Section 13
    $countPsicEdu = isset($s11['psic_edu_nombres']) ? count($s11['psic_edu_nombres']) : 0;
    if ($countPsicEdu > 0) {
         // Fecha fuera del try/catch de clonación para asegurar que se reemplace
         $template->setValue('psic_edu_fecha', $s11['psic_edu_fecha'][0] ?? '');
         
         try {
            // Intentamos clonar usando el nombre con typo que parece estar en el template
            // Si falla, intentamos con el nombre correcto por si acaso (esto requeriría dos try-catch anidados o chequeo previo, 
            // pero PhpWord no tiene 'hasVariable'. Asumimos el typo por la evidencia visual).
            $template->cloneRow('sic_edu_nombres', $countPsicEdu);
            
            for ($i = 0; $i < $countPsicEdu; $i++) {
                $row = $i + 1;
                $template->setValue('psic_edu_fecha#' . $row, $s11['psic_edu_fecha'][$i] ?? '');
                
                // Seteamos ambas variantes por seguridad
                $template->setValue('psic_edu_nombres#' . $row, $s11['psic_edu_nombres'][$i] ?? '');
                $template->setValue('sic_edu_nombres#' . $row, $s11['psic_edu_nombres'][$i] ?? '');
                
                $template->setValue('psic_edu_cedula#' . $row, $s11['psic_edu_cedula'][$i] ?? '');
                $template->setValue('psic_edu_titulo#' . $row, $s11['psic_edu_titulo'][$i] ?? '');
                $template->setValue('psic_edu_senescyt#' . $row, $s11['psic_edu_senescyt'][$i] ?? '');
                $template->setValue('psic_edu_experiencia#' . $row, $s11['psic_edu_experiencia'][$i] ?? '');
                $template->setValue('psic_edu_cumple#' . $row, $s11['psic_edu_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { 
            // Fallback: si falla clonar por sic_edu_nombres, intentamos psic_edu_nombres
             try {
                $template->cloneRow('psic_edu_nombres', $countPsicEdu);
                for ($i = 0; $i < $countPsicEdu; $i++) {
                    $row = $i + 1;
                    $template->setValue('psic_edu_fecha#' . $row, $s11['psic_edu_fecha'][$i] ?? '');
                    $template->setValue('psic_edu_nombres#' . $row, $s11['psic_edu_nombres'][$i] ?? '');
                    $template->setValue('sic_edu_nombres#' . $row, $s11['psic_edu_nombres'][$i] ?? ''); // Seteamos el typo también aquí
                    $template->setValue('psic_edu_cedula#' . $row, $s11['psic_edu_cedula'][$i] ?? '');
                    $template->setValue('psic_edu_titulo#' . $row, $s11['psic_edu_titulo'][$i] ?? '');
                    $template->setValue('psic_edu_senescyt#' . $row, $s11['psic_edu_senescyt'][$i] ?? '');
                    $template->setValue('psic_edu_experiencia#' . $row, $s11['psic_edu_experiencia'][$i] ?? '');
                    $template->setValue('psic_edu_cumple#' . $row, $s11['psic_edu_cumple'][$i] ?? '');
                }
             } catch (\Exception $e2) {}
        }
    } else { 
        $template->setValue('psic_edu_nombres', ''); 
        $template->setValue('sic_edu_nombres', ''); 
        $template->setValue('psic_edu_fecha', '');
    }

    // 14. Evaluador Psicosensométrico (eval_nombres[]) - Section 14
    $countEval = isset($s11['eval_nombres']) ? count($s11['eval_nombres']) : 0;
    if ($countEval > 0) {
         try {
            $template->cloneRow('eval_nombres', $countEval);
            for ($i = 0; $i < $countEval; $i++) {
                $row = $i + 1;
                $template->setValue('eval_fecha', $s11['eval_fecha'][0] ?? '');
                $template->setValue('eval_fecha#' . $row, $s11['eval_fecha'][$i] ?? '');
                $template->setValue('eval_nombres#' . $row, $s11['eval_nombres'][$i] ?? '');
                $template->setValue('eval_cedula#' . $row, $s11['eval_cedula'][$i] ?? '');
                $template->setValue('eval_titulo#' . $row, $s11['eval_titulo'][$i] ?? '');
                $template->setValue('eval_senescyt#' . $row, $s11['eval_senescyt'][$i] ?? '');
                $template->setValue('eval_ofimatica#' . $row, $s11['eval_ofimatica'][$i] ?? '');
                $template->setValue('eval_capacitacion#' . $row, $s11['eval_capacitacion'][$i] ?? '');
                $template->setValue('eval_cargo_publico#' . $row, $s11['eval_cargo_publico'][$i] ?? '');
                $template->setValue('eval_cumple#' . $row, $s11['eval_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('eval_nombres', ''); }

    // 15. Docentes (docente_nombres[]) - Section 15 (Step 12)
    $countDocente = isset($s12['docente_nombres']) ? count($s12['docente_nombres']) : 0;
    if ($countDocente > 0) {
         try {
            $template->cloneRow('docente_nombres', $countDocente);
            for ($i = 0; $i < $countDocente; $i++) {
                $row = $i + 1;
                $template->setValue('docente_nombres#' . $row, $s12['docente_nombres'][$i] ?? '');
                $template->setValue('docente_cedula#' . $row, $s12['docente_cedula'][$i] ?? '');
                $template->setValue('docente_curso#' . $row, $s12['docente_curso'][$i] ?? '');
                $template->setValue('docente_senescyt#' . $row, $s12['docente_senescyt'][$i] ?? '');
                $template->setValue('docente_titulo#' . $row, $s12['docente_titulo'][$i] ?? '');
                $template->setValue('docente_catedra#' . $row, $s12['docente_catedra'][$i] ?? '');
                $template->setValue('docente_experiencia#' . $row, $s12['docente_experiencia'][$i] ?? '');
                $template->setValue('docente_cargo_publico#' . $row, $s12['docente_cargo_publico'][$i] ?? '');
                $template->setValue('docente_cumple#' . $row, $s12['docente_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('docente_nombres', ''); }

    // 16. Docentes Sección 2 (docente2_nombres[]) - Section 16 (Step 12)
    $countDocente2 = isset($s12['docente2_nombres']) ? count($s12['docente2_nombres']) : 0;
    if ($countDocente2 > 0) {
         try {
            $template->cloneRow('docente2_nombres', $countDocente2);
            for ($i = 0; $i < $countDocente2; $i++) {
                $row = $i + 1;
                $template->setValue('docente2_nombres#' . $row, $s12['docente2_nombres'][$i] ?? '');
                $template->setValue('docente2_cedula#' . $row, $s12['docente2_cedula'][$i] ?? '');
                $template->setValue('docente2_senescyt#' . $row, $s12['docente2_senescyt'][$i] ?? '');
                $template->setValue('docente2_titulo#' . $row, $s12['docente2_titulo'][$i] ?? '');
                $template->setValue('docente2_catedra#' . $row, $s12['docente2_catedra'][$i] ?? '');
                $template->setValue('docente2_experiencia#' . $row, $s12['docente2_experiencia'][$i] ?? '');
                $template->setValue('docente2_cumple#' . $row, $s12['docente2_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('docente2_nombres', ''); }

    // 17. Instructores (inst_nombres[]) - Section 17 (Step 12)
    $countInst = isset($s12['inst_nombres']) ? count($s12['inst_nombres']) : 0;
    if ($countInst > 0) {
         try {
            $template->cloneRow('inst_nombres', $countInst);
            for ($i = 0; $i < $countInst; $i++) {
                $row = $i + 1;
                $template->setValue('inst_nombres#' . $row, $s12['inst_nombres'][$i] ?? '');
                $template->setValue('inst_cedula#' . $row, $s12['inst_cedula'][$i] ?? '');
                $template->setValue('inst_tipo_licencia#' . $row, $s12['inst_tipo_licencia'][$i] ?? '');
                $template->setValue('inst_edad#' . $row, $s12['inst_edad'][$i] ?? '');
                $template->setValue('inst_instruccion#' . $row, $s12['inst_instruccion'][$i] ?? '');
                $template->setValue('inst_certificado#' . $row, $s12['inst_certificado'][$i] ?? '');
                $template->setValue('inst_fecha_licencia#' . $row, $s12['inst_fecha_licencia'][$i] ?? '');
                $template->setValue('inst_puntos#' . $row, $s12['inst_puntos'][$i] ?? '');
                $template->setValue('inst_experiencia#' . $row, $s12['inst_experiencia'][$i] ?? '');
                $template->setValue('inst_tipo_curso#' . $row, $s12['inst_tipo_curso'][$i] ?? '');
                $template->setValue('inst_cargo_publico#' . $row, $s12['inst_cargo_publico'][$i] ?? '');
                $template->setValue('inst_cumple#' . $row, $s12['inst_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('inst_nombres', ''); }

    // 18. Instructores Sección 2 (inst2_nombres[]) - Section 18 (Step 12)
    $countInst2 = isset($s12['inst2_nombres']) ? count($s12['inst2_nombres']) : 0;
    if ($countInst2 > 0) {
         try {
            $template->cloneRow('inst2_nombres', $countInst2);
            for ($i = 0; $i < $countInst2; $i++) {
                $row = $i + 1;
                $template->setValue('inst2_nombres#' . $row, $s12['inst2_nombres'][$i] ?? '');
                $template->setValue('inst2_cedula#' . $row, $s12['inst2_cedula'][$i] ?? '');
                $template->setValue('inst2_tipo_licencia#' . $row, $s12['inst2_tipo_licencia'][$i] ?? '');
                $template->setValue('inst2_instruccion#' . $row, $s12['inst2_instruccion'][$i] ?? '');
                $template->setValue('inst2_edad#' . $row, $s12['inst2_edad'][$i] ?? '');
                $template->setValue('inst2_experiencia#' . $row, $s12['inst2_experiencia'][$i] ?? '');
                $template->setValue('inst2_tipo_curso#' . $row, $s12['inst2_tipo_curso'][$i] ?? '');
                $template->setValue('inst2_certificado_vial#' . $row, $s12['inst2_certificado_vial'][$i] ?? '');
                $template->setValue('inst2_idoneidad#' . $row, $s12['inst2_idoneidad'][$i] ?? '');
                $template->setValue('inst2_fecha_infraccion#' . $row, $s12['inst2_fecha_infraccion'][$i] ?? '');
                $template->setValue('inst2_cumple#' . $row, $s12['inst2_cumple'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('inst2_nombres', ''); }

    // 19. Control de Cursos (curso_autorizacion[]) - Section 19 (Step 12)
    $countCurso = isset($s12['curso_autorizacion']) ? count($s12['curso_autorizacion']) : 0;
    if ($countCurso > 0) {
         try {
            $template->cloneRow('curso_autorizacion', $countCurso);
            for ($i = 0; $i < $countCurso; $i++) {
                $row = $i + 1;
                $template->setValue('curso_autorizacion#' . $row, $s12['curso_autorizacion'][$i] ?? '');
                $template->setValue('curso_fecha#' . $row, $s12['curso_fecha'][$i] ?? '');
                $template->setValue('curso_matricula_inicio#' . $row, $s12['curso_matricula_inicio'][$i] ?? '');
                $template->setValue('curso_matricula_fin#' . $row, $s12['curso_matricula_fin'][$i] ?? '');
                $template->setValue('curso_clases_inicio#' . $row, $s12['curso_clases_inicio'][$i] ?? '');
                $template->setValue('curso_clases_fin#' . $row, $s12['curso_clases_fin'][$i] ?? '');
                $template->setValue('curso_modalidad#' . $row, $s12['curso_modalidad'][$i] ?? '');
                $template->setValue('curso_alumnos_autorizados#' . $row, $s12['curso_alumnos_autorizados'][$i] ?? '');
                $template->setValue('curso_alumnos_matriculados#' . $row, $s12['curso_alumnos_matriculados'][$i] ?? '');
                $template->setValue('curso_alumnos_graduados#' . $row, $s12['curso_alumnos_graduados'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('curso_autorizacion', ''); }

    // 20. Estudiantes (est_nombre_oficio[]) - Section 20 (Step 13)
    // 20. Estudiantes (est_nombre_oficio[]) - Section 20 (Step 13)
    // Nueva lógica anidada: Estudiantes (Bloque) -> Materias (Filas)
    $estudiantes = $s13['estudiantes'] ?? [];

    // Si viene del formato antiguo (arrays planos), intentar convertirlo o usar lógica legacy,
    // pero idealmente el usuario usará el nuevo form. Haremos un fallback básico o asumiremos nuevo formato.
    // Si $estudiantes está vacío pero hay est_nombres old style, convertirlo.
    if (empty($estudiantes) && isset($s13['est_nombres']) && is_array($s13['est_nombres'])) {
        $countOld = count($s13['est_nombres']);
        for($i=0; $i<$countOld; $i++) {
            $estudiantes[] = [
                'nombres' => $s13['est_nombres'][$i] ?? '',
                'nombre_oficio' => $s13['est_nombre_oficio'][$i] ?? '',
                'tipo_curso' => $s13['est_tipo_curso'][$i] ?? '',
                'fichas_teoricas' => $s13['est_fichas_teoricas'][$i] ?? '',
                'fichas_practicas' => $s13['est_fichas_practicas'][$i] ?? '',
                'cedula' => $s13['est_cedula'][$i] ?? '',
                'fecha_nacimiento' => $s13['est_fecha_nacimiento'][$i] ?? '',
                'edad' => $s13['est_edad'][$i] ?? '',
                'nivel_instruccion' => $s13['est_nivel_instruccion'][$i] ?? '',
                'valoracion_psico' => $s13['est_valoracion_psico'][$i] ?? '',
                'valoracion_psicologica' => $s13['est_valoracion_psicologica'][$i] ?? '',
                'matricula' => $s13['est_matricula'][$i] ?? '',
                'emision_permiso' => $s13['est_emision_permiso'][$i] ?? '',
                'jornadas' => $s13['est_jornadas'][$i] ?? '',
                'materias' => [
                    [
                        'materia' => $s13['est_materia'][$i] ?? '',
                        'calificaciones' => $s13['est_calificaciones'][$i] ?? '',
                        'asistencia' => $s13['est_asistencia'][$i] ?? '',
                        'clase_tipo' => $s13['est_clase_tipo'][$i] ?? '',
                        'fecha_inicio' => $s13['est_fecha_inicio'][$i] ?? '',
                        'fecha_fin' => $s13['est_fecha_fin'][$i] ?? '',
                        'estado' => $s13['est_estado'][$i] ?? ''
                    ]
                ]
            ];
        }
    }

    $countEst = count($estudiantes);

    if ($countEst > 0) {
         try {
            // Se asume que en el template existe un bloque ${estudiante_block} ... ${/estudiante_block}
            // que envuelve toda la tabla del estudiante.
            $template->cloneBlock('estudiante_block', $countEst, true, true);
            
            foreach ($estudiantes as $ix => $est) {
                $row = $ix + 1;
                
                $template->setValue('est_nombre_oficio#' . $row, $est['nombre_oficio'] ?? '');
                $template->setValue('est_tipo_curso#' . $row, $est['tipo_curso'] ?? '');
                $template->setValue('est_fichas_teoricas#' . $row, $est['fichas_teoricas'] ?? '');
                $template->setValue('est_fichas_practicas#' . $row, $est['fichas_practicas'] ?? '');
                $template->setValue('est_nombres#' . $row, $est['nombres'] ?? '');
                $template->setValue('est_cedula#' . $row, $est['cedula'] ?? '');
                $template->setValue('est_fecha_nacimiento#' . $row, $est['fecha_nacimiento'] ?? '');
                $template->setValue('est_edad#' . $row, $est['edad'] ?? '');
                $template->setValue('est_nivel_instruccion#' . $row, $est['nivel_instruccion'] ?? '');
                $template->setValue('est_valoracion_psico#' . $row, $est['valoracion_psico'] ?? '');
                $template->setValue('est_valoracion_psicologica#' . $row, $est['valoracion_psicologica'] ?? '');
                $template->setValue('est_matricula#' . $row, $est['matricula'] ?? '');
                $template->setValue('est_emision_permiso#' . $row, $est['emision_permiso'] ?? '');
                $template->setValue('est_jornadas#' . $row, $est['jornadas'] ?? '');
                
                // Procesar Materias
                $materias = $est['materias'] ?? [];
                // Filtrar materias vacías si es necesario, o al menos asegurarse de que es array
                if (!is_array($materias)) $materias = [];
                
                $countMat = count($materias);
                if ($countMat > 0) {
                    // La variable base para materia debe ser est_materia, pero como está dentro de un bloque clonado #row,
                    // ahora se llama est_materia#row.
                    try {
                        $template->cloneRow('est_materia#' . $row, $countMat);
                        
                        for ($m = 0; $m < $countMat; $m++) {
                            $mRow = $m + 1;
                            // La sintaxis de PHPWord para nested clones suele ser variable#parentIndex#childIndex
                            $suffix = '#' . $row . '#' . $mRow;
                            
                            $mat = $materias[$m];
                            $template->setValue('est_materia' . $suffix, $mat['materia'] ?? '');
                            $template->setValue('est_calificaciones' . $suffix, $mat['calificaciones'] ?? '');
                            $template->setValue('est_asistencia' . $suffix, $mat['asistencia'] ?? '');
                            $template->setValue('est_clase_tipo' . $suffix, $mat['clase_tipo'] ?? '');
                            $template->setValue('est_fecha_inicio' . $suffix, $mat['fecha_inicio'] ?? '');
                            $template->setValue('est_fecha_fin' . $suffix, $mat['fecha_fin'] ?? '');
                            $template->setValue('est_estado' . $suffix, $mat['estado'] ?? '');
                        }
                    } catch (\Exception $e2) { 
                        // Fallback: si cloneRow falla (ej. tag no encontrado), intentar llenar al menos el primero
                        // Esto pasa si el usuario no pone materias o el tag está mal.
                    }
                } else {
                    // Limpiar fila de materia si no tiene materias
                    $template->setValue('est_materia#' . $row, '');
                    $template->setValue('est_calificaciones#' . $row, '');
                    $template->setValue('est_asistencia#' . $row, '');
                    $template->setValue('est_clase_tipo#' . $row, '');
                    $template->setValue('est_fecha_inicio#' . $row, '');
                    $template->setValue('est_fecha_fin#' . $row, '');
                    $template->setValue('est_estado#' . $row, '');
                }
            }
        } catch (\Exception $e) { }
    } else { 
        // Si no hay estudiantes, limpiar el bloque (necesita estar el bloque en el template)
         // O limpiar variables dummy si no se usa block
         $template->setValue('est_nombre_oficio', '');
    }

    // 21. Costos de Cursos (costo_nombres[]) - Section 21 (Step 13)
    $countCosto = isset($s13['costo_nombres']) ? count($s13['costo_nombres']) : 0;
    if ($countCosto > 0) {
         try {
            $template->cloneRow('costo_nombres', $countCosto);
            for ($i = 0; $i < $countCosto; $i++) {
                $row = $i + 1;
                $template->setValue('costo_nombres#' . $row, $s13['costo_nombres'][$i] ?? '');
                $template->setValue('costo_tipo_curso#' . $row, $s13['costo_tipo_curso'][$i] ?? '');
                $template->setValue('costo_num_factura#' . $row, $s13['costo_num_factura'][$i] ?? '');
                $template->setValue('costo_fecha_factura#' . $row, $s13['costo_fecha_factura'][$i] ?? '');
                
                $template->setValue('costo_valor_curso#' . $row, $s13['costo_valor_curso'][$i] ?? '');
                $template->setValue('costo_valor_permiso#' . $row, $s13['costo_valor_permiso'][$i] ?? '');
                $template->setValue('costo_valor_examen#' . $row, $s13['costo_valor_examen'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('costo_nombres', ''); }

    // 22. Formato del Certificado de Conductor - Simplified (Step 13)
    $template->setValue('cert_contiene_nombre', $s13['cert_contiene_nombre'] ?? '');
    $template->setValue('cert_contiene_resolucion', $s13['cert_contiene_resolucion'] ?? '');
    $template->setValue('cert_contiene_domicilio', $s13['cert_contiene_domicilio'] ?? '');
    $template->setValue('cert_contiene_titulo', $s13['cert_contiene_titulo'] ?? '');
    $template->setValue('cert_contiene_estudiante', $s13['cert_contiene_estudiante'] ?? '');
    $template->setValue('cert_contiene_fecha', $s13['cert_contiene_fecha'] ?? '');
    $template->setValue('cert_contiene_categoria', $s13['cert_contiene_categoria'] ?? '');
    $template->setValue('cert_contiene_tipo', $s13['cert_contiene_tipo'] ?? '');
    $template->setValue('cert_contiene_firmas', $s13['cert_contiene_firmas'] ?? '');
    $template->setValue('cert_contiene_lugar', $s13['cert_contiene_lugar'] ?? '');
    $template->setValue('cert_contiene_calificacion', $s13['cert_contiene_calificacion'] ?? '');

    // 23. Campañas de seguridad vial (camp_nro[]) - Section 23 (Step 13)
    $countCamp = isset($s13['camp_nro']) ? count($s13['camp_nro']) : 0;
    if ($countCamp > 0) {
         try {
            $template->cloneRow('camp_nro', $countCamp);
            for ($i = 0; $i < $countCamp; $i++) {
                $row = $i + 1;
                $template->setValue('camp_nro#' . $row, $s13['camp_nro'][$i] ?? '');
                $template->setValue('camp_fecha#' . $row, $s13['camp_fecha'][$i] ?? '');
                $template->setValue('camp_beneficiarios#' . $row, $s13['camp_beneficiarios'][$i] ?? '');
                $template->setValue('camp_metodologia#' . $row, $s13['camp_metodologia'][$i] ?? '');
                $template->setValue('camp_temas#' . $row, $s13['camp_temas'][$i] ?? '');
                $template->setValue('camp_fecha_programacion#' . $row, $s13['camp_fecha_programacion'][$i] ?? '');
            }
        } catch (\Exception $e) { }
    } else { $template->setValue('camp_nro', ''); }


    // --- Output File ---
    $tempFile = tempnam(sys_get_temp_dir(), 'word');
    $template->saveAs($tempFile);

    // LOCK LOGIC: Ensure report is marked as completed (Step 13)
    // If this is a user download (not admin override), ensure persistence
    // ARCHIVING LOGIC:
    // 1. Save to Archive
    // 2. Reset Active Progress
    // 3. Clear Session
    
    if (!isset($_GET['admin_download_user_id']) && !isset($_GET['archive_mode'])) {
        require_once '../includes/db_connection.php';
        
        // Prepare data for archive
        $formDataJson = json_encode($_SESSION['form_data'] ?? []);
        $schoolName = $_SESSION['form_data']['step1']['escuela_nombre'] ?? 'Escuela No Profesional';
        
        // Insert into Archive
        $archStmt = $pdo->prepare("INSERT INTO reports_archive (user_id, school_type, school_name, form_data) VALUES (?, 'non_professional', ?, ?)");
        $archStmt->execute([$_SESSION['user_id'], $schoolName, $formDataJson]);
        
        // Reset Active Progress (Clear it for new report)
        $resetStmt = $pdo->prepare("UPDATE user_progress SET form_data = '{}', last_step = 0, updated_at = NOW() WHERE user_id = ?");
        $resetStmt->execute([$_SESSION['user_id']]);
        
        // Clear Edit Requests if any
        $lockStmt = $pdo->prepare("UPDATE edit_requests SET status = 'completed' WHERE user_id = ? AND status = 'approved'");
        $lockStmt->execute([$_SESSION['user_id']]);

        // Clear Session to force fresh start
        unset($_SESSION['form_data']);
        unset($_SESSION['last_step']);
    }

    // Force download
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment; filename="Informe_No_Profesional_' . date('Ymd_His') . '.docx"');
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
