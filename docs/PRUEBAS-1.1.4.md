# Datos de prueba en una sola instalación — versión 1.1.4

Usar `dist/Centro-Jubilados-1.1.4-setup.exe`. Esta edición normal conserva la identidad de la aplicación y no carga ejemplos automáticamente. Incluye la opción para administrarlos desde la misma instalación.

## Probar

1. Antes de actualizar una instalación con datos reales, cerrarla y respaldar su carpeta de datos.
2. Instalar la versión 1.1.4 y entrar con `admin@admin.com` (contraseña inicial `enzoadmin`, salvo que se haya cambiado).
3. Menú del nombre → **Datos de prueba** → **Habilitar datos de prueba**. Confirmar.
4. Revisar socios, cuotas, alquileres y caja. La pantalla debe indicar las cantidades de ejemplos. El usuario `test@test.com` / `test1234` debe ver esos datos, pero no debe ver el acceso a esta pantalla ni poder utilizar sus rutas.
5. Editar el nombre de un socio DEMO. Volver al panel: no debe ofrecer cargar otra copia mientras haya ejemplos registrados.
6. Marcar la confirmación de limpieza y pulsar **Deshabilitar y quitar datos de prueba**. Confirmar en el modal. Los ejemplos cargados y editados deben desaparecer, y las cuentas deben conservarse. Debe aparecer un aviso de respaldo creado.
7. Volver a habilitar: debe poder cargarse un nuevo conjunto de ejemplos.
8. Crear un registro manual vinculado a un ejemplo (por ejemplo, un alquiler sobre un sector DEMO o un pago sobre un alquiler DEMO). Al limpiar, ese registro debe conservarse junto con los ejemplos necesarios y sus pagos. El panel debe avisar que quedaron registros vinculados.
9. Los datos que ya existían antes de habilitar deben conservarse. También se conservan los registros creados manualmente, aunque su nombre contenga DEMO.
10. Cerrar y reabrir la aplicación: el estado y los datos deben persistir.

## Qué significa deshabilitar

Deshabilitar elimina los ejemplos registrados por el cargador; no se limita a ocultarlos. Antes de borrar se guarda una copia completa de SQLite. Si el respaldo falla, se cancela la eliminación. No se borran usuarios ni se cambian contraseñas.

Las marcas se guardan en una tabla interna y no dependen del nombre actual del registro. Las cuentas normales no pueden modificar esas marcas desde los formularios del sistema.

Si hay referencias a ejemplos desde registros ajenos a la carga, se conserva lo necesario para no romper relaciones ni quitar pagos de una cuota o alquiler conservado. Esos ejemplos se pueden limpiar cuando se hayan resuelto los vínculos.

Los respaldos están en `storage/app/private/backups`, dentro de la carpeta de datos de la aplicación (en Windows, bajo `%APPDATA%`). No se incluyen en nuevas compilaciones. No hay restauración automática desde el panel.

Los ejemplos sin marcas de versiones anteriores no se adoptan automáticamente por su nombre, para evitar clasificar registros reales como DEMO. Los ejemplos locales cargados durante esta sesión se registraron contrastando con los respaldos anteriores a esa carga.

## Validación realizada

- 36 pruebas aprobadas; siguen los mismos 3 fallos previos en perfil y redirección de cierre de sesión.
- Pruebas de permisos, carga repetida, eliminación después de editar, respaldo SQLite legible, fallo de respaldo, conservación de registros manuales y pagos vinculados.
- Prueba en Chrome sobre una copia de la base: inicio como administrador, habilitar/deshabilitar con los modales y vista de escritorio y móvil sin desborde horizontal ni errores JavaScript.
- La instalación del archivo EXE aún requiere validación en Windows. Los instaladores no tienen firma de editor. Los avisos de dependencias informados en la guía 1.1.3 siguen pendientes de revisión.
