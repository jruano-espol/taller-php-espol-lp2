<?php
session_start();
require "usuario.php";
require "tarea.php";

if (!isset($_SESSION["cedula"]) || !validar($_SESSION["cedula"])) {
    header("Location: ingreso.php");
    exit;
}

$usuario = Usuario::buscar($_SESSION["cedula"]);
if ($usuario === null) {
    header("Location: ingreso.php");
    exit;
}

list($pendientes, $completadas) = listarTareas($usuario->nombre);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar'])) {
        $texto = $_POST['nombre'];
        guardarTarea($usuario->nombre, $texto);
        header("Location: tareas.php");
        exit;
    }
    if (isset($_POST['completar'])) {
        $id = (int)$_POST['completar'];
        completarTarea($usuario->nombre, $id);
        header("Location: tareas.php");
        exit;
    }
    if (isset($_POST['descompletar'])) {
        $id = (int)$_POST['descompletar'];
        descompletarTarea($usuario->nombre, $id);
        header("Location: tareas.php");
        exit;
    }
    if (isset($_POST['eliminar'])) {
        $id = (int)$_POST['eliminar'];
        eliminarTarea($usuario->nombre, $id);
        header("Location: tareas.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tareas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <br>
    <a href="logout.php">Cerrar Sesión</a>

    <h1>Tareas</h1>

    <h2>Agregar una tarea</h2>
    <form method="POST" class='agregarTareaForm'>
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br>
        <input type="submit" name="agregar" value="Agregar">
    </form>
    
    <h2>Tareas Pendientes</h2>
    <?php
        echo "<div class='listaTareas'>";
        foreach ($pendientes as $tarea) {
            echo "<div class='tareaPendiente'>";
            echo "  <p>" . htmlspecialchars($tarea->texto) . "</p>";
            echo "  <form method='POST'>";
            echo "    <button type='submit' value='{$tarea->id}' name='completar' class='completar'>Completar</button>";
            echo "    <button type='submit' value='{$tarea->id}' name='eliminar' class='eliminar'>Eliminar</button>";
            echo "  </form>";
            echo "</div>";
        }
        echo "</div>";
    ?>

    <h2>Tareas Completadas</h2>
    <?php
        echo "<div class='listaTareas'>";
        foreach ($completadas as $tarea) {
            echo "<div class='tareaCompletada'>";
            echo "  <p>" . htmlspecialchars($tarea->texto) . "</p>";
            echo "  <form method='POST'>";
            echo "    <button type='submit' value='{$tarea->id}' name='descompletar' class='descompletar'>Descompletar</button>";
            echo "    <button type='submit' value='{$tarea->id}' name='eliminar' class='eliminar'>Eliminar</button>";
            echo "  </form>";
            echo "</div>";
        }
        echo "</div>";
    ?>
</body>
</html>