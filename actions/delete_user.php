<?php
session_start();
require_once '../includes/db_connection.php';

// Verify admin role
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Get user ID to delete
$userId = $_POST['user_id'] ?? null;

if ($userId) {
    try {
        // Prevent deleting self (admin) if they try to delete themselves (though UI should prevent it)
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'title' => 'Error',
                'text' => 'No puedes eliminar tu propia cuenta de administrador.'
            ];
        } else {
            // Delete user. Cascade/foreign keys should handle related data (user_progress), 
            // but let's be explicit if needed. Our setup_db.php has ON DELETE CASCADE.
            
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            if ($stmt->execute([$userId])) {
                $_SESSION['flash_message'] = [
                    'type' => 'success',
                    'title' => 'Eliminado',
                    'text' => 'El usuario ha sido eliminado correctamente.'
                ];
            } else {
                throw new Exception("No se pudo ejecutar la eliminación.");
            }
        }
    } catch (Exception $e) {
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'title' => 'Error',
            'text' => 'Hubo un problema al eliminar el usuario: ' . $e->getMessage()
        ];
    }
} else {
    $_SESSION['flash_message'] = [
        'type' => 'warning',
        'title' => 'Atención',
        'text' => 'No se especificó un usuario para eliminar.'
    ];
}

// Redirect back to user list or details
$referrer = $_SERVER['HTTP_REFERER'] ?? '../admin_users.php';
// If referrer was details page of deleted user, go to list
if (strpos($referrer, 'admin_user_details.php') !== false) {
    $referrer = '../admin_users.php';
}
header("Location: $referrer");
exit();
?>
