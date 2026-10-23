# Pruebas de la versión 1.1.8

Instalador normal para Windows x64: `Centro-Jubilados-1.1.8-setup.exe`.
Conserva la identidad de la aplicación; al actualizar, no borres la carpeta de datos.
Conviene exportar un respaldo antes de instalar sobre una instalación existente.

## Cambios a comprobar

1. Cerrar sesión y confirmar que vuelve a la pantalla inicial de acceso.
2. Actualizar el nombre y correo del perfil sin cambiar la pregunta de seguridad.
3. Cambiar la pregunta de seguridad y comprobar que se guarda. Si se envía vacía, debe validarse como obligatoria.
4. Confirmar que las opciones de administrador, datos de prueba y respaldos siguen funcionando.

La suite automatizada pasó: 40 pruebas y 205 aserciones. El instalador debe probarse en Windows.

## Limitaciones del paquete

El build generó el instalador, pero NativePHP avisó que no encontró un bundle seguro y compiló con archivos fuente expuestos. El ejecutable tampoco tiene firma de editor. El empaquetado reportó dependencias npm vulnerables; este build no actualizó esas dependencias.
