<?php
/**
 * Create New Report Action
 * Archives current form data and clears session for a new report
 */
session_start();
require_once '../includes/db_connection.php';

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$schoolType = $_POST['school_type'] ?? null;

if (!$schoolType || !in_array($schoolType, ['professional', 'non_professional'])) {
    $_SESSION['flash_message'] = [
        'type' => 'error',
        'title' => 'Error',
        'text' => 'Tipo de escuela no válido.'
    ];
    header("Location: ../dashboard.php");
    exit();
}

try {
    $userId = $_SESSION['user_id'];
    
    // Check if user has existing progress
    $stmt = $pdo->prepare("SELECT form_data, last_step FROM user_progress WHERE user_id = ?");
    $stmt->execute([$userId]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing && !empty($existing['form_data']) && $existing['last_step'] > 0) {
        // Archive current report - mark as submitted (you could create an archived_reports table for this)
        // For now, we'll just clear the data. In a full implementation, you'd save to an archive table.
        
        // Clear user_progress. Set form_data to empty JSON object '{}' to avoid NOT NULL constraints.
        $stmt = $pdo->prepare("UPDATE user_progress SET form_data = '{}', last_step = 0, updated_at = NOW() WHERE user_id = ?");
        $stmt->execute([$userId]);
    }
    
    // Clear session form data
    unset($_SESSION['form_data']);
    
    // Set new school type in session
    $_SESSION['form_data'] = ['school_type' => $schoolType];
    
    $_SESSION['flash_message'] = [
        'type' => 'success',
        'title' => '¡Nuevo Informe!',
        'text' => 'Se ha iniciado un nuevo informe. Complete todos los pasos.'
    ];
    
    // Redirect to step 1 of the selected school type
    if ($schoolType === 'professional') {
        header("Location: ../views/professional/step1.php");
    } else {
        header("Location: ../views/non-professional/step1.php");
    }
    exit();
    
} catch (Exception $e) {
    error_log("New report error: " . $e->getMessage());
    $_SESSION['flash_message'] = [
        'type' => 'error',
        'title' => 'Error',
        'text' => 'No se pudo crear el nuevo informe. Detalles: ' . $e->getMessage()
    ];
    header("Location: ../dashboard.php");
    exit();
}
