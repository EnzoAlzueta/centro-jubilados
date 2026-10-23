# Pruebas de la versión 1.1.3 — Windows x64

## Instaladores

- `dist/Centro-Jubilados-1.1.3-setup.exe`: edición normal para actualizar una instalación existente. Conserva su identidad y no agrega datos DEMO.
- `dist/Centro-Jubilados-DEMO-1.1.3-setup.exe`: edición para pruebas, con nombre e identificador propios y base separada. Agrega los ejemplos al primer inicio. No sirve para comprobar la actualización de la edición normal porque usa otra base.

Los ejemplos se crean con fechas relativas al primer inicio. Se identifican por DEMO; los datos son compartidos entre cuentas dentro de cada edición. Reabrir DEMO no vuelve a cargar ni reinicia los ejemplos.

## Acceso

- Administrador: `admin@admin.com` / `enzoadmin`.
- Usuario común: `test@test.com` / `test1234`.
- En instalaciones existentes se conservan las contraseñas que se hayan cambiado.

## 1. Instalación e inicio

1. Instalar la edición DEMO en Windows y abrirla.
2. Iniciar sesión como usuario de prueba. Verificar 6 socios DEMO (uno deshabilitado), 18 cuotas, 5 alquileres, 2 sectores DEMO, sillas y mesas DEMO.
3. Cerrar y volver a abrir: los datos deben seguir y no duplicarse.

## 2. Usuarios y permisos

1. Con el usuario de prueba, comprobar que no aparece **Crear usuario** en el menú del nombre.
2. Con el administrador, abrir ese menú → **Crear usuario**.
3. Crear una cuenta con su pregunta y respuesta de seguridad. Debe aparecer el mensaje de éxito y conservarse la sesión del administrador.
4. Probar correo repetido y contraseñas distintas: deben mostrarse errores sin crear otra cuenta.
5. Cerrar sesión y entrar con la cuenta nueva. Debe funcionar como usuario común y no mostrar **Crear usuario**.
6. Si se comprueba `/register` directamente, un usuario común debe recibir 403 y una persona sin sesión debe ser enviada al login. El bloqueo también se comprueba automáticamente para el envío del formulario.

## 3. Alquileres y notificaciones

1. Abrir un evento DEMO y revisar solicitante, precio, pagos y utilería.
2. Crear una reserva en una fecha y sector libres, sin seña. Modificarla.
3. Registrar un pago parcial, otro pago y completar el saldo. Verificar el pendiente y el estado confirmado al terminar.
4. Después de cada acción, debe aparecer un único aviso debajo del título. El siguiente reemplaza al anterior y el éxito desaparece a los 5 segundos. El título y el calendario deben mantenerse alineados.
5. Intentar pagar más que el saldo: debe rechazarse y no agregarse un cobro en caja.
6. Cancelar una reserva sin devolución y otra con devolución. Revisar los movimientos de caja correspondientes.
7. Reducir el ancho de la ventana y comprobar que los mensajes largos no desbordan ni desplazan el título a un costado.

## 4. Socios, cuotas y caja

1. Editar un socio DEMO y comprobar que el cambio se conserva al reabrir.
2. Consultar cuotas pagadas y pendientes. Pagar una pendiente: debe quedar pagada y generar un ingreso de caja por el mismo importe.
3. Comprobar que los cobros de alquiler aparecen en caja con categoría Alquiler y las devoluciones como egresos.
4. Crear un ingreso y un egreso manuales y revisar el saldo.

## 5. Reportes

1. Generar los reportes de socios, alquileres y cuentas/caja.
2. Seleccionar el mes correspondiente a los eventos y cobros. Deben abrir como PDF, sin error 500.
3. Usar imprimir y guardar PDF desde el visor y revisar importes y nombres.
4. Elegir un período sin registros: debe mostrarse el aviso de ausencia de datos.
5. Si hay movimientos antiguos sin categoría, el PDF de caja debe mostrar **Sin categoría** y conservarlos en los totales.

## 6. Actualización sin perder datos — edición normal

1. Usar una instalación de la edición normal anterior con registros que se puedan identificar. Anotar cantidades, un socio, un alquiler, un pago y el saldo.
2. Cerrar completamente la aplicación y respaldar su carpeta de datos en `%APPDATA%`. La ruta exacta puede variar según el nombre instalado; comprobar que contenga `database/database.sqlite` y conservar también `storage`.
3. Ejecutar el instalador normal 1.1.3 sobre la instalación existente, sin borrar la carpeta de datos.
4. Abrir, iniciar sesión y comprobar que los registros anteriores siguen y que no aparecieron registros DEMO.
5. Comprobar que la cuenta administradora tiene acceso a Crear usuario y que el resto no.
6. Cerrar y reabrir, y verificar nuevamente la persistencia.

## Verificación automatizada y límites

La compilación de assets fue correcta. La suite previa al empaquetado dio 32 pruebas aprobadas y 3 fallidas ya existentes: redirección al cerrar sesión y dos pruebas de actualización de perfil por falta de pregunta de seguridad. Las pruebas de permisos, creación de usuarios, notificaciones, datos DEMO y PDF aprobaron.

También se ejecutaron las migraciones del paquete normal sobre una copia con datos simulando la versión previa al permiso de administrador: se conservaron todos los registros y contraseñas, y se asignaron los permisos esperados.

Se verificó además la carga de la edición DEMO desde el paquete: 6 socios, 18 cuotas, 5 alquileres y 16 movimientos, sin duplicados en una segunda ejecución de migraciones.

Los instaladores no tienen firma de editor; Windows puede mostrar una advertencia al ejecutarlos.

El instalador debe probarse en Windows: compilar en Linux no verifica su interfaz ni la instalación en el equipo del cliente.

La auditoría local detectó avisos de seguridad en dependencias existentes (12 grupos de paquetes PHP y 3 hallazgos altos en el árbol JavaScript de producción). El empaquetador también informó avisos en su propio árbol de dependencias. No se actualizaron dependencias en esta entrega; revisar estos avisos antes de distribuir a clientes.

## Recompilar

- Edición normal: `npm run build` y `php artisan native:build win x64 --no-interaction`.
- Edición DEMO: `npm run build` y `python3 scripts/build-demo.py`.

El script DEMO cambia temporalmente la configuración y agrega una migración exclusiva de esa edición. Restaura `.env` y elimina esa migración temporal al terminar, incluso si el comando informa un error. No ejecutar ambas compilaciones a la vez porque comparten el directorio de empaquetado.
