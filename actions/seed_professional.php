<?php
session_start();
require_once '../includes/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Debe iniciar sesión.");
}

// ---------------------------------------------------------
// DUMMY DATA GENERATION
// ---------------------------------------------------------

$step1 = [
    'escuela_nombre' => 'Escuela de Conducción Profesional "El Volante"',
    'provincia' => 'Pichincha',
    'canton' => 'Quito',
    'parroquia' => 'Iñaquito', // Added if existing in form, otherwise ignored
    'direccion_insitu' => 'Av. Amazonas y Naciones Unidas',
    'direccion_ant' => 'Av. Occidental s/n',
    'direccion_sri' => 'Av. Amazonas N-23',
    'telefono' => '0991234567',
    'telefono_fijo' => '022345678',
    'email' => 'contacto@elvolante.com',
    'ruc' => '1790012345001',
    'gerente_nombre' => 'Juan Pérez',
    'gerente_cedula' => '1712345678', // Hypothetical
    
    // Documentos Habilitantes
    'resolucion_nro' => 'ANT-2024-001-R',
    'resoluciones_extra' => 'ANT-2023-099-R (Ampliación)',
    'doc_resolucion' => 'archivo_dummy.pdf', // File input simulation
    'patente_municipal' => 'PAT-2025-QUI',
    'doc_patente' => 'patente.pdf',
    'permiso_bomberos' => 'BOM-2025-001',
    'doc_bomberos' => 'bomberos.pdf',
    'permiso_sanitario' => 'MSP-2025-X',
    'estado_sri' => 'ACTIVO',
    'doc_ruc' => 'ruc.pdf',
    'domicilio' => 'Propio',
    'doc_domicilio' => 'escrituras.pdf',
    'impuesto_predial' => 'PRE-2025-12345',
    'tipo_curso' => 'Tipo C', // Field added recently
];

$step2 = [
    'aulas' => [
        [
            'nro' => '1', 'alumnos' => '20', 'resolucion' => 'ANT-R-01', 'material' => 'Si', 
            'cam_tiene' => 'Si', 'cam_conectada' => 'Si', 'cam_acceso' => 'IP Publica', 'cam_frecuencia' => '24/7',
            'proyector' => 'on', 'computador' => 'on', 'lista' => 'on'
        ],
        [
            'nro' => '2', 'alumnos' => '15', 'resolucion' => 'ANT-R-01', 'material' => 'Si', 
            'cam_tiene' => 'Si', 'cam_conectada' => 'Si', 'cam_acceso' => 'IP Publica', 'cam_frecuencia' => '24/7',
            'proyector' => 'on', 'computador' => 'on', 'lista' => 'on'
        ]
    ],
    'taller' => [
        [
            'nro' => '1', 'tipo' => 'Mecánica Básica', 'alumnos' => '10', 'resolucion' => 'ANT-R-02', 'material' => 'Si',
            'cam_tiene' => 'Si', 'cam_conectada' => 'Si', 'cam_acceso' => 'IP', 'cam_frecuencia' => '24/7',
            'motor' => 'on', 'caja' => 'on', 'ejes' => 'on'
        ]
    ],
    'taller_curso_1' => 'Mecánica',
    'taller_curso_2' => 'Electricidad'
];

$step3 = [
    'lab_comp' => [
        ['nro' => '1', 'equipos' => '15', 'internet' => 'Si', 'programas' => 'Si', 'simuladores' => 'No']
    ],
    'lab_psico' => [
        ['nro' => '1', 'equipos' => '1', 'profesional' => 'Dr. House', 'registro' => '123456']
    ],
    'pista' => [
        'ubicacion' => 'Calle Secundaria', 'area' => '2000 m2', 'tipo' => 'Asfalto', 'cerramiento' => 'Si', 
        'iluminacion' => 'Si', 'banos' => 'Si', 'senalizacion' => 'Si'
    ],
    'circuito_gad' => 'No tiene'
];

$step4 = [
    'baterias' => [
        ['ubicacion' => 'Planta Baja', 'hombres' => '2', 'mujeres' => '2', 'discapacitados' => '1', 'estado' => 'Bueno']
    ],
    'cafeteria' => 'Si',
    'parqueadero' => 'Si',
    'recreacion' => 'Si'
];

$step5 = [
    'biometrico' => 'Si',
    'simulador' => 'Si',
    'software' => 'Si',
    'vehiculos' => [
        ['placa' => 'ABC-1234', 'marca' => 'Chevrolet', 'modelo' => 'Sail', 'anio' => '2020', 'tipo' => 'Automóvil', 'servicio' => 'Prácticas', 'estado' => 'Bueno', 'matricula' => 'Vigente', 'revision' => 'Vigente', 'seguro' => 'Vigente', 'doble_mando' => 'Si'],
        ['placa' => 'PBA-5678', 'marca' => 'Hino', 'modelo' => 'GH', 'anio' => '2019', 'tipo' => 'Camión', 'servicio' => 'Prácticas', 'estado' => 'Bueno', 'matricula' => 'Vigente', 'revision' => 'Vigente', 'seguro' => 'Vigente', 'doble_mando' => 'Si']
    ]
];

$step6 = [
    'personal_admin' => [
        ['nombre' => 'Ana Gomez', 'cargo' => 'Secretaria', 'cedula' => '1700000001', 'titulo' => 'Bachiller', 'contrato' => 'Indefinido', 'afiliacion' => 'Si']
    ],
    'director_pedagogico' => [
        'nombre' => 'Carlos Andrade', 'cedula' => '1700000002', 'titulo' => 'Lic. Educación', 'registro_senescyt' => '1001-2010', 'experiencia' => '5 años'
    ]
];

$step7 = [
    'instructores' => [
        ['nombre' => 'Pedro Pablo', 'cedula' => '1700000003', 'licencia' => 'Tipo E', 'puntos' => '30', 'caducidad' => '2028-01-01', 'titulo' => 'Bachiller', 'capacitacion' => 'Si', 'evaluacion' => 'Aprobado']
    ]
];

$step8 = [
    'cc_aut' => [
        [
            'control_autorizacion' => 'AUT-2024-001', 'control_fecha' => '2024-01-15', 
            'control_matr_ini' => '2024-02-01', 'control_matr_fin' => '2024-02-15',
            'control_teo_lv_ini' => '08:00', 'control_teo_lv_fin' => '10:00',
            'control_teo_fds_ini' => '', 'control_teo_fds_fin' => '',
            'control_prac_lv_ini' => '10:00', 'control_prac_lv_fin' => '12:00',
            'control_prac_fds_ini' => '', 'control_prac_fds_fin' => '',
            'control_alum_aut' => '30', 'control_alum_mat' => '25', 'control_alum_grad' => '24'
        ]
    ],
    'est_nom' => [
        [
            'est_nom' => 'Estudiante 1', 'est_ced' => '1755555555', 'est_mem' => 'MEM-001', 'est_tipo' => 'Regular',
            'est_lic' => 'Tipo B', 'est_lic_f' => '2020-01-01', 'est_edad' => '25', 'est_instr' => 'Bachiller',
            'est_psico' => 'Apto', 'est_med' => 'Apto', 'est_matr' => 'Si', 'est_perm' => 'Si', 'est_jorn' => 'Matutina'
        ]
    ],
    'cost_nom' => [
        ['cost_nom' => 'Curso Licencia C', 'cost_tipo' => 'Conducción', 'cost_fact' => 'Si', 'cost_fecha' => '2024-01-01', 'cost_v_cur' => '900.00', 'cost_v_perm' => '50.00', 'cost_v_ex' => '20.00']
    ],
    'camp_nro' => [
        ['camp_nro' => '1', 'camp_fecha' => '2024-06-01', 'camp_benef' => 'Comunidad', 'camp_met' => 'Charla', 'camp_temas' => 'Señales', 'camp_prog' => 'Si']
    ]
];

// ---------------------------------------------------------
// SAVE TO SESSION & DB
// ---------------------------------------------------------

$fullData = [
    'school_type' => 'professional',
    'step1' => $step1,
    'step2' => $step2,
    'step3' => $step3,
    'step4' => $step4,
    'step5' => $step5,
    'step6' => $step6,
    'step7' => $step7,
    'step8' => $step8,
];

// Merge with existing session to preserve user_id etc if needed, but overwrite form_data
$_SESSION['form_data'] = $fullData;
$_SESSION['last_step'] = 8;

// Save to DB
$jsonData = json_encode($fullData);
$stmt = $pdo->prepare("INSERT INTO user_progress (user_id, form_data, last_step) VALUES (?, ?, 8) ON DUPLICATE KEY UPDATE form_data = VALUES(form_data), last_step = 8");
$stmt->execute([$_SESSION['user_id'], $jsonData]);

// Redirect
header("Location: ../views/professional/summary.php?msg=seeded");
exit;
?>
