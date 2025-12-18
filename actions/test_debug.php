<?php
session_start();
echo "<h1>DEBUG DATA RECEIPT</h1>";
echo "<h2>POST DATA (What was sent):</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<h2>SESSION DATA (What is stored):</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<a href='../views/professional/step2.php' style='display:inline-block; padding:10px 20px; background:blue; color:white; text-decoration:none; border-radius:5px;'>Volver al Formulario</a>";
?>
