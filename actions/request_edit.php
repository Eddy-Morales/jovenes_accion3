<?php
session_start();
require_once '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$userId = $_SESSION['user_id'];

try {
    // Check for existing pending request FOR THIS SPECIFIC REPORT
    $archiveId = $_POST['archive_id'] ?? null;
    if ($archiveId === '') $archiveId = null;

    $query = "SELECT id FROM edit_requests WHERE user_id = ? AND status = 'pending'";
    $params = [$userId];

    if ($archiveId) {
        $query .= " AND archive_id = ?";
        $params[] = $archiveId;
    } else {
        $query .= " AND archive_id IS NULL";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    
    if ($stmt->rowCount() > 0) {
        // Already pending for this specific report
        $_SESSION['flash_message'] = [
            'type' => 'info',
            'title' => 'Solicitud ya enviada',
            'text' => 'Ya tienes una solicitud pendiente para este informe.'
        ];
    } else {
        // Create new request
        // $archiveId is already set above

        $insert = $pdo->prepare("INSERT INTO edit_requests (user_id, archive_id, status) VALUES (?, ?, 'pending')");
        $insert->execute([$userId, $archiveId]);
        
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'title' => 'Solicitud enviada',
            'text' => 'El administrador revisará tu solicitud para habilitar la edición.'
        ];
    }
} catch (PDOException $e) {
    $_SESSION['flash_message'] = [
        'type' => 'error',
        'title' => 'Error',
        'text' => 'No se pudo procesar la solicitud.'
    ];
}

header("Location: ../dashboard.php");
exit();
?>
