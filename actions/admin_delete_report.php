<?php
session_start();
require_once '../includes/db_connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportId = $_POST['report_id'] ?? null;
    $reportType = $_POST['report_type'] ?? null;
    // We also need the user_id (of the report owner) to redirect back to details page correctly
    $redirectUserId = $_POST['user_id'] ?? null; 

    if ($reportId && $reportType) {
        try {
            if ($reportType === 'archive') {
                // Delete from archives
                $stmt = $pdo->prepare("DELETE FROM reports_archive WHERE id = ?");
                $stmt->execute([$reportId]);
                $_SESSION['flash_message'] = [
                    'type' => 'success',
                    'title' => 'Informe eliminado',
                    'text' => 'El informe archivado ha sido eliminado correctamente.'
                ];
            } elseif ($reportType === 'active') {
                // Reset active progress for the user
                // $reportId in this case IS the user_id for active reports as per admin_user_details logic, 
                // BUT let's be careful. In admin_user_details: 'id' => $project['user_id'].
                // So yes, report_id passed here will be the user_id.
                $stmt = $pdo->prepare("UPDATE user_progress SET form_data = NULL, last_step = 0, updated_at = NOW() WHERE user_id = ?");
                $stmt->execute([$reportId]);
                $_SESSION['flash_message'] = [
                    'type' => 'success',
                    'title' => 'Progreso eliminado',
                    'text' => 'El informe activo ha sido reiniciado.'
                ];
            }
        } catch (Exception $e) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar el informe.'
            ];
        }
    }
}

if ($redirectUserId) {
    header("Location: ../admin_user_details.php?id=" . $redirectUserId);
} else {
    header("Location: ../admin_users.php");
}
exit();
