# Prueba Técnica - Backend Base

Este es el proyecto base para la prueba técnica, estructurado con **Arquitectura Hexagonal** en **Laravel 11+** (Laravel 13.x) y configurado para trabajar con **PostgreSQL**.

---

## 🛠️ Tecnologías y Configuración

El proyecto cuenta con las siguientes herramientas y dependencias instaladas y configuradas:

1. **Laravel 11+ / PHP 8.3+**
2. **PostgreSQL** (`DB_CONNECTION=pgsql` configurado hacia la base de datos Neon).
3. **Laravel Telescope**: Para monitorear peticiones, base de datos, logs, etc. accesible en `/telescope`.
4. **L5-Swagger / OpenAPI**: Para la documentación de la API. Documentación autogenerada y accesible en `/api/documentation`.
5. **Spatie Laravel Activitylog**: Para la auditoría automatizada y logs de actividades del sistema.
6. **API y CORS**: Configuración activa en `config/cors.php` que permite el acceso cross-origin para rutas `api/*`.
7. **Variables de entorno**: Archivos `.env` y `.env.example` sincronizados con los accesos PostgreSQL.

---

## 📐 Estructura Hexagonal (`app/`)

El código de negocio está estructurado bajo patrones de Arquitectura Hexagonal en las siguientes carpetas dentro de `app/`:

* **`Domain/`**: Contiene la lógica del negocio pura (Entidades de dominio, Excepciones, Reglas de negocio e Interfaces de Repositorios). *No depende de frameworks ni librerías externas.*
* **`Application/`**: Casos de uso de la aplicación, Command/Query handlers y servicios de aplicación que orquestan el flujo de datos.
* **`Infrastructure/`**: Adaptadores y dependencias externas. Contiene implementaciones concretas de interfaces de dominio (Repositorios Eloquent, integraciones con APIs externas, etc.).
* **`Presentation/`**: Punto de entrada a la aplicación (Controladores API, Requests de validación, Resources de formato de salida, y documentación de OpenAPI/Swagger).

---

## 🚀 Comandos Útiles

### Levantar el Servidor de Desarrollo
```bash
php artisan serve
```

### Ejecutar Migraciones
```bash
php artisan migrate
```

### Regenerar Documentación de Swagger (OpenAPI)
```bash
php artisan l5-swagger:generate
```

### Limpiar Caché de Configuración
```bash
php artisan config:clear
```
