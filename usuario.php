<?php
function guardar($datos) {
    $linea = $datos['cedula'] . "," . $datos['nombre'] . "," .
             $datos['estado_civil'] . "," . $datos['correo'] . "," .
             $datos['clave_hash'] . "\n";
    file_put_contents("usuarios.csv", $linea, FILE_APPEND);
}
 
function validar($cedula) {
    if (!file_exists("usuarios.csv")) return false;
    $lineas = file("usuarios.csv");
    foreach ($lineas as $linea) {
        $campos = explode(",", $linea);
        if ($campos[0] == $cedula) {
            return true;
        }
    }
    return false;
}

class Usuario {
    public function __construct(
        public string $cedula,
        public string $nombre,
        public string $estado_civil,
        public string $correo,
        public string $clave_hash
    ) {}

    public static function buscar(string $cedula) {
        if (!file_exists("usuarios.csv")) return null;
        $lineas = file("usuarios.csv");
        foreach ($lineas as $linea) {
            $campos = explode(",", $linea);
            if ($campos[0] == $cedula) {
                return new Usuario($campos[0], $campos[1], $campos[2], $campos[3], $campos[4]);
            }
        }
        return null;
    }
}
 
function autenticar($cedula, $contrasena) {
    if (!file_exists("usuarios.csv")) return false;
    $lineas = file("usuarios.csv");
    foreach ($lineas as $linea) {
        $campos = explode(",", $linea);
        if ($campos[0] == $cedula &&
            password_verify($contrasena, trim($campos[4]))) {
            return true;
        }
    }
    return false;
}
?>