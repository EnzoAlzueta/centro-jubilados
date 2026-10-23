# Pruebas de la versión 1.1.9

Instalador normal para Windows x64: `Centro-Jubilados-1.1.9-setup.exe`.
Actualiza la instalación existente sin borrar su carpeta de datos. Antes de instalar,
conviene exportar un respaldo.

## Importación de respaldo

1. Abrir la aplicación e iniciar sesión como administrador.
2. Exportar un respaldo desde **Respaldos**.
3. Cambiar un dato sencillo para distinguir el estado actual del respaldo.
4. Importar el archivo exportado y aceptar la confirmación.
5. Comprobar que el dato vuelve al estado respaldado y que se informa la copia automática previa.
6. Cerrar y abrir la aplicación; verificar que los datos continúan disponibles.
7. Probar un archivo no válido y cancelar el selector: ambos casos deben conservar la base actual.

También comprobar logout y actualización de perfil según la guía 1.1.8.
La suite automatizada pasó: 40 pruebas y 205 aserciones. El instalador debe probarse en Windows.

## Limitaciones del paquete

NativePHP avisó que compiló sin bundle seguro (con archivos fuente expuestos). El ejecutable no tiene firma de editor. El empaquetado reportó vulnerabilidades de dependencias npm; no se actualizaron en esta corrección.
