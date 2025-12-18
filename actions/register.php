<?php
session_start();
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name'] ?? '');
    $job_title = trim($_POST['job_title'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($username) || empty($password) || empty($confirm_password) || empty($full_name) || empty($job_title) || empty($gender)) {
        $_SESSION['flash_message'] = [
            'type' => 'warning',
            'title' => 'Campos Incompletos',
            'text' => 'Por favor complete todos los campos requeridos.'
        ];
        header("Location: ../register.php");
        exit();
    }

    // Validate gender
    if (!in_array($gender, ['male', 'female'])) {
        $_SESSION['flash_message'] = [
            'type' => 'warning',
            'title' => 'Género Inválido',
            'text' => 'Por favor seleccione un género válido.'
        ];
        header("Location: ../register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'title' => 'Contraseñas no coinciden',
            'text' => 'Por favor verifique que las contraseñas sean idénticas.'
        ];
        header("Location: ../register.php");
        exit();
    }

    try {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'title' => 'Usuario existente',
                'text' => 'El nombre de usuario ya está en uso. Intente con otro.'
            ];
            header("Location: ../register.php");
            exit();
        }

        // Insert new user with profile fields
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, full_name, job_title, gender, password) VALUES (:username, :full_name, :job_title, :gender, :password)");
        
        if ($stmt->execute([
            'username' => $username, 
            'full_name' => $full_name,
            'job_title' => $job_title,
            'gender' => $gender,
            'password' => $hashed_password
        ])) {
            // Success
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'title' => '¡Registro Exitoso!',
                'text' => 'Su cuenta ha sido creada. Ahora puede iniciar sesión.'
            ];
            header("Location: ../index.php");
            exit();
        } else {
            throw new Exception("Error al insertar usuario.");
        }

    } catch (Exception $e) {
        error_log("Registration error: " . $e->getMessage());
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'title' => 'Error del Sistema',
            'text' => 'Ocurrió un error al procesar el registro.'
        ];
        header("Location: ../register.php");
        exit();
    }
} else {
    header("Location: ../register.php");
    exit();
}

