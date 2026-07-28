<?php
session_start();
require "usuario.php";
require "tarea.php";

if (!isset($_SESSION["cedula"]) && !validar($_SESSION["cedula"])) {
    header("Location: ingreso.php");
    exit;
}
$usuario = Usuario::buscar($_SESSION["cedula"]);
if ($usuario === null) {
    header("Location: tareas.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texto = $_POST['nombre'];
    guardarTarea($usuario->nombre, $texto);
    header("Location: tareas.php");
    exit;
}
?>