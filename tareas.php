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
    header("Location: ingreso.php");
    exit;
}

list($pendientes, $completadas) = listarTareas($usuario->nombre);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tareas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Tareas</h1>

    <h2>Agregar una tarea</h2>
    <form method="POST" action="tarea_nueva.php">
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br>
        <input type="submit" value="Agregar">
    </form>
    
    <h2>Tareas Pendientes</h2>
    <?php
        echo "<div class='listaTareas'>";
        foreach ($pendientes as $tarea) {
            echo "<div class='tareaPendiente'>";
            echo "<p>" . htmlspecialchars($tarea->texto) . "</p>";
            echo "</div>";
        }
        echo "</div>";
    ?>

    <h2>Tareas Completadas</h2>
    <?php
        echo "<div class='listaTareas'>";
        foreach ($completadas as $tarea) {
            echo "<div class='tareaCompletada'>";
            echo "<p>" . htmlspecialchars($tarea->texto) . "</p>";
            echo "</div>";
        }
        echo "</div>";
    ?>
</body>
</html>