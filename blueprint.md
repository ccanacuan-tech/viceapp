
# Gestor de Planificaciones Docentes (GPD)

## Visión General

Este proyecto tiene como objetivo digitalizar y automatizar el flujo de trabajo de creación, revisión, aprobación y archivo de las planificaciones docentes.

## Plan de Desarrollo

### Sprint 1: Configuración del Proyecto y Autenticación

- [x] **Instalar y configurar el paquete Spatie para roles y permisos.**
- [x] **Crear los roles (Docente, Secretaría, Vicerrector).**
- [x] **Crear el sistema de autenticación (vistas y controladores).**

### Sprint 2: Carga y Organización de Archivos

- [x] **Crear el modelo y la migración para las planificaciones.**
- [x] **Implementar la funcionalidad de carga de archivos.**
- [x] **Crear vistas para listar y organizar las planificaciones.**

### Sprint 3: Flujo de Trabajo y Visualización

- [x] **Implementar la lógica para cambiar el estado de las planificaciones.**
- [x] **Crear un visualizador de archivos en el navegador.**

### Sprint 4: Colaboración y Búsqueda

- [x] **Implementar la funcionalidad de comentarios.**
- [x] **Implementar un sistema de notificaciones para los comentarios.**
- [x] **Implementar la funcionalidad de búsqueda y filtrado.**

### Sprint 5: Integración con Google Drive

- [x] **Configurar la API de Google Drive.**
- [x] **Implementar la selección de archivos desde Google Drive.**

### Sprint 6: Rediseño Visual y Mejoras de la Experiencia de Usuario

- [x] **Actualizar la paleta de colores:** Rediseñar las vistas de la aplicación utilizando una paleta de colores beige, blanco y rojo.
    - Se ha actualizado `tailwind.config.js` para incluir los nuevos colores.
    - Se han modificado las plantillas `welcome.blade.php`, `guest.blade.php`, `app.blade.php`, y `navigation.blade.php` para usar la nueva paleta.
    - Se ha cambiado el color del botón principal a rojo.
- [x] **Revisar y arreglar la navegación:** Asegurar que todos los enlaces de navegación, incluidos los botones de perfil y de cierre de sesión, funcionan correctamente.
    - Se ha creado el componente de notificaciones (`notifications.blade.php`).
- [x] **Establecer el inicio de sesión como punto de entrada principal:** Modificar el enrutamiento para que la página de inicio de sesión sea la página por defecto para los usuarios no autenticados.
- [x] **Crear seeders de usuario:** Implementar un seeder para crear usuarios por defecto para los roles: 'docente', 'secretaria' y 'vicerrector'.
- [x] **Generar un resumen de las credenciales de los usuarios:** Crear una tabla markdown con las credenciales de acceso de los usuarios recién creados.

## Credenciales de Usuario

| Rol | Email | Contraseña |
| :--- | :--- | :--- |
| Docente | `docente@example.com` | `password` |
| Secretaria | `secretaria@example.com`| `password` |
| Vicerrector | `vicerrector@example.com`| `password` |
