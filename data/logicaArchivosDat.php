<?php
class LogicaArchivosDat{

    function obtenerCriterios() {
        $ruta = 'C:/xampp/htdocs/LUV/resources/criterios';
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

    function obtenerValoresDeCriterio($criterio) {
        $ruta = 'C:/xampp/htdocs/LUV/resources/criterios';
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
}
?>
