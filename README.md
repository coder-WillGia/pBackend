# Prueba Técnica Larael Full Stack - Backend API

API REST para la gestión de productos y categorías. Construida con **Laravel 11+** (arquitectura estructurada para desacoplar dominio, aplicación e infraestructura) y utilizando **MySQL** como base de datos.

---

## 🛠️ Tecnologías y Características

1. **Laravel 11+ / PHP 8.3+**
2. **MySQL**: Configurado como motor de base de datos principal.
3. **Laravel Telescope**: Para monitoreo en tiempo real de peticiones, logs y base de datos (disponible en `/telescope`).
4. **L5-Swagger (OpenAPI 3.0)**: Documentación interactiva de la API (disponible en `/api/documentation`).
5. **Spatie Laravel Activitylog**: Registro automático de auditorías para acciones CRUD (Crear, Actualizar, Eliminar).
6. **Validación y Formato Estándar**: Respuestas JSON uniformes para peticiones exitosas y errores.

---

## ⚙️ Requisitos Previos

Asegúrate de tener instalado en tu máquina local:
* PHP 8.3 o superior
* Composer
* MySQL (o compatibilidad con protocolo MySQL como TiDB/MariaDB)
* Docker y Docker Compose *(opcional, si deseas levantarlo con contenedores)*

---

## 🚀 Instalación y Ejecución Local

Sigue estos pasos sencillos para levantar el backend en tu entorno local:

1. **Instalar dependencias de Composer**:
   ```bash
   composer install
   ```

2. **Configurar el Entorno**:
   Copia el archivo de plantilla a tu entorno real (el archivo `.env` contiene credenciales locales y no debe subirse a repositorios públicos de Git):
   ```bash
   cp .env.example .env
   ```
   
   ### 🛢️ Conexión a Base de Datos de Prueba (TiDB Cloud)
   Para facilitar la evaluación inmediata de esta prueba técnica, **se incluye una base de datos remota de MySQL (TiDB Cloud)** ya migrada y sembrada con datos de prueba. Puedes usar las siguientes variables de conexión directamente en tu `.env` para conectarte y validar la efectividad del proyecto al instante:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=gateway01.ap-northeast-1.prod.aws.tidbcloud.com
   DB_PORT=4000
   DB_DATABASE=prueba_tecnica
   DB_USERNAME=akejx6z3UBZP4Vh.root
   DB_PASSWORD=Ac8MmOua0xb1uz6v
   ```

   > [!NOTE]  
   > Esta base de datos ya tiene toda la estructura creada y datos de prueba precargados. Al configurar estas credenciales, no necesitas levantar un servidor local de base de datos ni correr migraciones adicionales.

3. **Generar la clave de la aplicación**:
   ```bash
   php artisan key:generate
   ```

4. **Ejecutar las Migraciones**:
   Crea las tablas necesarias en la base de datos:
   ```bash
   php artisan migrate
   ```

5. **Generar Documentación de Swagger**:
   Genera los archivos estáticos de OpenAPI para documentar las rutas:
   ```bash
   php artisan l5-swagger:generate
   ```

6. **Levantar el Servidor**:
   ```bash
   php artisan serve
   ```
   La API estará accesible en `http://localhost:8000`.

---

## 🐳 Ejecución con Docker (Alternativa)

Si prefieres levantar el proyecto usando contenedores Docker (con el Dockerfile y docker-compose configurado):

1. Construye y levanta los servicios:
   ```bash
   docker-compose up -d --build
   ```

2. Ejecuta las migraciones y genera la documentación dentro del contenedor:
   ```bash
   docker-compose exec app php artisan migrate
   docker-compose exec app php artisan l5-swagger:generate
   ```
   El backend estará disponible en `http://localhost:8000`.

---

## 📝 Logs de Auditoría (Spatie Activitylog)

El sistema cuenta con auditoría automática y detallada utilizando el paquete **Spatie Activitylog** para hacer trazabilidad de todas las acciones del CRUD:

*   **Entidades Auditadas:** Categorías y Productos.
*   **Eventos Capturados:** Creación (`created`), Modificación (`updated`) y Eliminación (`deleted`).
*   **Datos Registrados:** Atributos cambiados (`attributes`) junto con los valores anteriores (`old`) y marcas de tiempo (`timestamps`).

### 🔍 ¿Cómo visualizar las auditorías realizadas?
Todos los movimientos se registran de forma automática en la tabla **`activity_log`** de la base de datos. Para consultarlos desde tu consola o cliente SQL de MySQL:

```sql
SELECT id, log_name, description, subject_type, subject_id, event, properties, created_at FROM activity_log ORDER BY id DESC;
```

*(En la base de datos de TiDB Cloud compartida ya puedes visualizar el historial completo de las creaciones, ediciones y eliminaciones de prueba que se han efectuado en la base de datos).*

---

## 🔍 Direcciones Útiles

* **Base URL de la API**: `http://localhost:8000/api`
* **Swagger/OpenAPI Docs**: `http://localhost:8000/api/documentation`
* **Laravel Telescope Dashboard**: `http://localhost:8000/telescope`
