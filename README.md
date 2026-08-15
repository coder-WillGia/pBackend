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
   Copia el archivo de plantilla a tu entorno real:
   ```bash
   cp .env.example .env
   ```
   Abre el archivo `.env` recién creado y verifica que las credenciales de **MySQL** estén configuradas con tus accesos locales o en la nube:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=gateway01.ap-northeast-1.prod.aws.tidbcloud.com
   DB_PORT=4000
   DB_DATABASE=prueba_tecnica
   DB_USERNAME=akejx6z3UBZP4Vh.root
   DB_PASSWORD=Ac8MmOua0xb1uz6v
   ```

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

## 🔍 Direcciones Útiles

* **Base URL de la API**: `http://localhost:8000/api`
* **Swagger/OpenAPI Docs**: `http://localhost:8000/api/documentation`
* **Laravel Telescope Dashboard**: `http://localhost:8000/telescope`
