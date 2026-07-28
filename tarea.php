<?php
class Tarea {
    public function __construct(
        public int $id,
        public string $texto,
        public string $estado
    ) {}

    public static function deLinea(string $linea) {
        $campos = explode(",", $linea);
        return new Tarea((int)$campos[0], $campos[1], trim($campos[2]));
    }
}

function obtenerMayorID(string $usuario) {
    if (!file_exists("tareas_$usuario.csv")) {
        return 0;
    }
    $lineas = file("tareas_$usuario.csv");
    $max_id = 0;
    foreach ($lineas as $linea) {
        $tarea = Tarea::deLinea($linea);
        if ($tarea->id > $max_id) {
            $max_id = $tarea->id;
        }
    }
    return $max_id;
}

function guardarTarea(string $usuario, string $texto) {
    $id = obtenerMayorID($usuario) + 1;
    $estado = "pendiente";
    $linea = "$id,$texto,$estado\n";
    file_put_contents("tareas_$usuario.csv", $linea, FILE_APPEND);
}

function listarTareas(string $usuario) {
    $pendientes = [];
    $completadas = [];
    if (!file_exists("tareas_$usuario.csv")) {
        return [$pendientes, $completadas];
    }
    $lineas = file("tareas_$usuario.csv");
    foreach ($lineas as $linea) {
        $tarea = Tarea::deLinea($linea);
        if (strcmp($tarea->estado, 'pendiente') == 0) {
            $pendientes[] = $tarea;
        } else if (strcmp($tarea->estado, 'completada') == 0) {
            $completadas[] = $tarea;
        }
    }
    return [$pendientes, $completadas];
}

function completarTarea(string $usuario, int $id) {
    if (!file_exists("tareas_$usuario.csv")) {
        return null;
    }
    $lineas = file("tareas_$usuario.csv");
    foreach ($lineas as &$linea) {
        $tarea = Tarea::deLinea($linea);
        if ($tarea->id == $id) {
            $tarea->estado = 'completada';
            $linea = "{$tarea->id},{$tarea->texto},{$tarea->estado}\n";
        }
    }
    file_put_contents("tareas_$usuario.csv", implode("", $lineas));
}

function eliminarTarea(string $usuario, int $id) {
    if (!file_exists("tareas_$usuario.csv")) {
        return null;
    }
    $lineas = file("tareas_$usuario.csv");
    $nuevas_lineas = [];
    foreach ($lineas as $linea) {
        $tarea = Tarea::deLinea($linea);
        if ($tarea->id != $id) {
            $nuevas_lineas[] = $linea;
        }
    }
    file_put_contents("tareas_$usuario.csv", implode("", $nuevas_lineas));
}
?>