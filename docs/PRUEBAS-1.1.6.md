# Pruebas de la versión 1.1.6

1. Actualizar la edición normal anterior. Los registros y la contraseña actual del administrador deben conservarse.
2. Ingresar como administrador. En el menú del nombre deben aparecer por separado **Datos de prueba** y **Respaldos**.
3. Abrir **Respaldos** y exportar la base. Elegir una carpeta y confirmar que el archivo `.sqlite` se creó y contiene los registros actuales.
4. Cancelar el diálogo: debe mostrarse el mensaje de cancelación y la base de la aplicación no debe cambiar.
5. Entrar con un usuario normal: las opciones administrativas no deben aparecer y sus rutas deben estar protegidas.
6. Abrir **Datos de prueba**: debe mostrar solamente las opciones para habilitar o quitar los ejemplos, sin controles de respaldo.
7. Cerrar y abrir la aplicación y comprobar que los datos persisten.

La copia se crea mientras la aplicación está abierta. La compilación debe probarse en Windows.
