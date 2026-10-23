# Pruebas de la versión 1.1.7

1. Actualizar una instalación con información y exportar un respaldo desde **Respaldos**.
2. Crear o modificar algunos datos para distinguir el estado actual del respaldo.
3. En **Respaldos**, pulsar **Seleccionar respaldo para importar** y aceptar la confirmación. Elegir el archivo `.sqlite` guardado antes.
4. Comprobar que vuelven los registros de ese respaldo, y que aparece el aviso con la copia de seguridad automática del estado anterior.
5. Entrar con un usuario común y confirmar que no vea el menú Respaldos ni pueda acceder directamente a sus opciones.
6. Cancelar el selector y confirmar que no cambia la base. Probar un archivo que no sea de Centro de Jubilados: debe rechazarse y conservarse la base actual.
7. Usar de nuevo los datos actuales después de importar, cerrar y reabrir la aplicación, y comprobar que los cambios persisten.

La importación comprueba la integridad SQLite, requiere una cuenta administradora en el respaldo, aplica migraciones pendientes y conserva una copia previa de la base actual. Probar la instalación y el selector de archivos en Windows.
