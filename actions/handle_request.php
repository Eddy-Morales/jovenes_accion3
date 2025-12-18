<?php
session_start();
require_once '../includes/db_connection.php';

// Verify Admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($requestId && in_array($action, ['approve', 'reject'])) {
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        
        try {
            $pdo->beginTransaction();

            // Fetch request details
            $reqStmt = $pdo->prepare("SELECT user_id, archive_id FROM edit_requests WHERE id = ?");
            $reqStmt->execute([$requestId]);
            $request = $reqStmt->fetch(PDO::FETCH_ASSOC);

            // Update status
            $stmt = $pdo->prepare("UPDATE edit_requests SET status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$status, $requestId]);
            
            // Handle Archive Restoration
            if ($action === 'approve' && $request && !empty($request['archive_id'])) {
                // Fetch archive data
                $arcStmt = $pdo->prepare("SELECT * FROM reports_archive WHERE id = ?");
                $arcStmt->execute([$request['archive_id']]);
                $archive = $arcStmt->fetch(PDO::FETCH_ASSOC);

                if ($archive) {
                     // Restore to user_progress (Overwriting existing active progress)
                     // Note: We use REPLACE or UPDATE.
                     // First check if row exists
                     $checkProg = $pdo->prepare("SELECT user_id FROM user_progress WHERE user_id = ?");
                     $checkProg->execute([$request['user_id']]);
                     
                     // Determine step based on type to ensure it's "completed" state but editable
                     // Or better, keep it as is. User can navigate back.
                     // Actually, if they want to EDIT, maybe we should set step to the last one?
                     // Let's keep the step from archive (which is 8 or 13)
                     $step = ($archive['school_type'] === 'professional') ? 8 : 13;

                     if ($checkProg->rowCount() > 0) {
                         // Inject school_type into form_data to ensure dashboard visibility
                         $restoredData = json_decode($archive['form_data'], true);
                         if (!is_array($restoredData)) $restoredData = [];
                         $restoredData['school_type'] = $archive['school_type'];
                         
                         $updProg = $pdo->prepare("UPDATE user_progress SET 
                             form_data = ?, 
                             last_step = ?, 
                             updated_at = NOW() 
                             WHERE user_id = ?");
                         $updProg->execute([json_encode($restoredData), $step, $request['user_id']]);
                     } else {
                         // Inject school_type
                         $restoredData = json_decode($archive['form_data'], true);
                         if (!is_array($restoredData)) $restoredData = [];
                         $restoredData['school_type'] = $archive['school_type'];

                         $insProg = $pdo->prepare("INSERT INTO user_progress (user_id, form_data, last_step) VALUES (?, ?, ?)");
                         $insProg->execute([$request['user_id'], json_encode($restoredData), $step]);
                     }

                     // Remove from archive (since it's now active)
                     $delArc = $pdo->prepare("DELETE FROM reports_archive WHERE id = ?");
                     $delArc->execute([$request['archive_id']]);
                }
            }

            $pdo->commit();
            
            $msg = ($action === 'approve') 
                ? 'Solicitud aprobada. El informe ha sido restaurado para edición.' 
                : 'Solicitud rechazada.';
                
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'title' => 'Éxito',
                'text' => $msg
            ];
        } catch (PDOException $e) {
            $pdo->rollBack();
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'title' => 'Error',
                'text' => 'Error al actualizar la solicitud: ' . $e->getMessage()
            ];
        }
    }
}

header("Location: ../admin_dashboard.php");
exit();
?>
