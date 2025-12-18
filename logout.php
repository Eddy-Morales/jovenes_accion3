<?php
session_start();
session_unset();
session_destroy();

// Start a new session just for the flash message
session_start();
$_SESSION['flash_message'] = [
    'type' => 'success',
    'title' => '¡Hasta luego!',
    'text' => 'Has cerrado sesión correctamente.'
];

header("Location: index.php");
exit();
