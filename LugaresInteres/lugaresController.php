<?php

class LugaresController {

    public function obtenerLugares() {

        $ruta = __DIR__ . "/lugares.txt";

        if (!file_exists($ruta)) {
            return [];
        }

        $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $lugares = [];

        foreach ($lineas as $linea) {
            list($nombre, $descripcion, $imagen) = explode("|", $linea);

            $lugares[] = [
                "nombre" => $nombre,
                "descripcion" => $descripcion,
                "imagen" => $imagen
            ];
        }

        return $lugares;
    }
}

