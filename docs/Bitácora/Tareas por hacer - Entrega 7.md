- [ ] Actividad: 
(
    tbactividad:
        tbactividadid
        tbactividadtitulo
        tbactividaddescripcion
        tbactividadfecha
        tbactividadduracion
        tbactividaddireccion
        tbactividadlatitud
        tbactividadlongitud
        tbactividadestado
        tbactividadanonimo
)
    - [ ] Tablas en base de datos (objeto y relación N:N con colectivos, creo que ocupa también tabla de N:N con los usuarios para llevar la asistencia).
    - [ ] Capas del modelo en código.
    - [ ] Interfaces:
        - [ ] CRUD administrativo.
        - [ ] Usuario:
            - [ ] Anual.
            - [ ] Mensual.
            - [ ] Semanal.
            - [ ] Diaria. 

[ ] Cambiar el registro de criterios y valores para que se maneje solo con archivos .dat
    - [ ] Revisar el código actual que maneja el registro de criterios y valores.
    - [ ] Modificar el sistema para que guarde exclusivamente en archivos .dat.
    - [ ] Asegurarse de que los archivos .dat se creen automáticamente si no existen.


[ ] Eliminación de criterios y valores
    - [ ] Crear un método para eliminar un criterio específico y sus valores asociados.
    - [ ] Asegurar que al eliminar un criterio se elimine el archivos .dat.


[ ] Revisar que se guarden bien los criterios y valores con la IA
    - [ ] Realizar pruebas para confirmar que la IA genere datos válidos.
    - [ ] Asegurarse de que los datos generados se guarden correctamente en los archivos .dat.
    - [ ] Corregir cualquier fallo en el guardado de datos.(como que termine con punto y no con coma)


[ ] Acomodar la lógica de acomodamiento de árbol con criterio en perfiles
    - [ ] Revisar la lógica actual del árbol que organiza los criterios en los perfiles.
    - [ ] Ajustar la lógica para que funcione con la nueva estructura de archivos .dat.
    - [ ] Realizar pruebas para verificar que el ordenamiento en árbol se ejecute correctamente.


[ ] Revisar que se guarden más valores relacionados si se agrega otro que no existía
    - [ ] Asegurarse de que el sistema detecte el nuevo valor y lo guarde en el archivo .dat.
    - [ ] Realizar pruebas para confirmar que el sistema actualiza correctamente los archivos .dat cuando se añaden nuevos valores(ósea que si se añade un valor que se añadan más valores relacionados).