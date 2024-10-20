<?php
class logicaArchivosData {

    // Función para obtener todos los criterios desde el directorio ../resources/criterios
    function obtenerCriterios() {
        $ruta = '../resources/criterios';
        $criterios = array();
        
        // Abre el directorio
        if ($handle = opendir($ruta)) {
            // Lee los archivos dentro del directorio
            while (false !== ($archivo = readdir($handle))) {
                // Excluir los directorios . y ..
                if ($archivo != "." && $archivo != "..") {
                    // Agregar solo los archivos con extensión .dat
                    if (pathinfo($archivo, PATHINFO_EXTENSION) == "dat") {
                        $criterios[] = pathinfo($archivo, PATHINFO_FILENAME);
                    }
                }
            }
            closedir($handle);
        }

        return $criterios;
    }

    // Función para obtener los valores de un criterio (desde un archivo .dat)
    function obtenerValoresDeCriterio($criterio) {
        $ruta = '../resources/criterios';
        $archivo = $ruta . '/' . $criterio . '.dat';
        
        if (file_exists($archivo)) {
            // Leer el contenido del archivo
            $contenido = file_get_contents($archivo);
            // Separar los valores por comas y devolverlos en un array
            $valores = explode(',', $contenido);
            return array_map('trim', $valores); // Eliminar espacios en blanco
        }
        
        return null; // Si el archivo no existe
    }

    // Función para verificar si un criterio (archivo .dat) existe en el directorio
    function existeCriterio($criterio) {
        $ruta = '../resources/criterios';
        $archivo = $ruta . '/' . $criterio . '.dat';
        
        // Retorna true si el archivo existe, false en caso contrario
        return file_exists($archivo);
    }

    // Función para verificar si un valor existe dentro de un criterio
    function existeValorEnCriterio($criterio, $valor) {
        // Obtener los valores del criterio
        $valores = $this->obtenerValoresDeCriterio($criterio);
        
        if ($valores !== null) {
            // Verificar si el valor existe en el array de valores del criterio
            return in_array(trim($valor), $valores, true);
        }

        return false; // Si no existe el criterio o el valor no está presente
    }
}
?>
