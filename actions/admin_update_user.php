<?php
session_start();
require_once '../includes/db_connection.php';

// Verify Admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? null;
    $username = trim($_POST['username'] ?? '');
    $fullName = trim($_POST['full_name'] ?? '');
    $role = $_POST['role'] ?? 'user';
    $password = $_POST['password'] ?? '';
    $gender = $_POST['gender'] ?? 'male';
    $jobTitle = trim($_POST['job_title'] ?? '');

    if (!$userId || empty($username)) {
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'title' => 'Error',
            'text' => 'Datos incompletos.'
        ];
        header("Location: ../admin_user_details.php?id=" . $userId);
        exit();
    }

    try {
        // Check if username exists for other users
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $check->execute([$username, $userId]);
        if ($check->rowCount() > 0) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'title' => 'Error',
                'text' => 'El nombre de usuario ya está en uso.'
            ];
            header("Location: ../admin_user_details.php?id=" . $userId);
            exit();
        }

        // Prepare update query
        $sql = "UPDATE users SET username = ?, full_name = ?, role = ?, gender = ?, job_title = ?"; // removed updated_at
        $params = [$username, $fullName, $role, $gender, $jobTitle];

        // Process password if provided
        if (!empty($password)) {
            $sql .= ", password = ?";
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = ?";
        $params[] = $userId;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $_SESSION['flash_message'] = [
            'type' => 'success',
            'title' => 'Usuario actualizado',
            'text' => 'Los datos del usuario han sido modificados correctamente.'
        ];

    } catch (PDOException $e) {
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'title' => 'Error',
            'text' => 'Error al actualizar: ' . $e->getMessage()
        ];
    }
    
    // Redirect logic
    $redirect = $_POST['redirect_to'] ?? ("../admin_user_details.php?id=" . $userId);
    header("Location: " . $redirect);
    exit();
}
