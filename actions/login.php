<?php
session_start();
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['flash_message'] = [
            'type' => 'warning',
            'title' => 'Campos vacíos',
            'text' => 'Por favor, ingrese usuario y contraseña.'
        ];
        header("Location: ../index.php");
        exit();
    }

    try {
        // Prepare statement to prevent SQL injection
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Prevent Session Fixation and clear old data
            session_regenerate_id(true);
            
            // Clear any potential stale data from previous sessions
            $tempFlash = $_SESSION['flash_message'] ?? null; // Persist flash if any (rare here)
            $_SESSION = [];
            if ($tempFlash) $_SESSION['flash_message'] = $tempFlash;

            // Set new user session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Store role
            
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'title' => '¡Bienvenido!',
                'text' => 'Has iniciado sesión correctamente.'
            ];

            if ($user['role'] === 'admin') {
                header("Location: ../admin_dashboard.php");
            } else {
                header("Location: ../dashboard.php");
            }
            exit();
        } else {
            // Invalid credentials
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'title' => 'Error de acceso',
                'text' => 'Usuario o contraseña incorrectos.'
            ];
            header("Location: ../index.php");
            exit();
        }

    } catch (PDOException $e) {
        // Log error and redirect with generic error
        error_log("Login error: " . $e->getMessage());
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'title' => 'Error del sistema',
            'text' => 'Hubo un problema al conectar con la base de datos.'
        ];
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
