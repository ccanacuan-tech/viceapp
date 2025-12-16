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
- [x] **Revisar y arreglar la navegación:** Asegurar que todos los enlaces de navegación, incluidos los botones de perfil y de cierre de sesión, funcionan correctamente.
- [x] **Establecer el inicio de sesión como punto de entrada principal.**
- [x] **Crear seeders de usuario.**
- [x] **Generar un resumen de las credenciales de los usuarios.**
- [x] **Mejorar el dashboard con un mensaje de bienvenida y tarjetas de funcionalidades.**
- [x] **Implementar la navegación del dashboard.**

### Sprint 7: Administración de Docentes (CRUD)

- [x] **Definir rutas CRUD para docentes.**
- [x] **Implementar el `TeacherController` para gestionar las operaciones CRUD.**
- [x] **Crear la vista `index` para listar los docentes.**
- [x] **Crear la vista `create` con un formulario para añadir nuevos docentes.**
- [x] **Crear la vista `edit` con un formulario para actualizar la información de los docentes.**
- [x] **Implementar la lógica para eliminar docentes.**

### Sprint 8: Gestión de Áreas Académicas

- [ ] **Crear modelo y migración para Áreas Académicas (`Subject`).**
- [ ] **Implementar CRUD para que el rol `vicerrector` gestione las Áreas Académicas.**
- [ ] **Añadir enlace en la navegación para la gestión de Áreas Académicas (solo visible para `vicerrector`).**
- [ ] **Actualizar la migración de `plannings` para añadir la relación con `Subject`.**
- [ ] **Modificar el formulario de subida de planificaciones para incluir un selector de Área Académica.**
- [ ] **Mostrar el Área Académica en las vistas de listado de planificaciones.**

## Credenciales de Usuario

| Rol | Email | Contraseña |
| :--- | :--- | :--- |
| Docente | `docente@example.com` | `password` |
| Secretaria | `secretaria@example.com`| `password` |
| Vicerrector | `vicerrector@example.com`| `password` |
