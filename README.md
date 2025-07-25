# ToDo List API

## Descripción General

Este proyecto consiste en una API RESTful desarrollada con Laravel, diseñada para gestionar una lista de tareas (ToDo List). La API permite realizar operaciones CRUD (crear, leer, actualizar y eliminar) sobre tareas, lo que la hace ideal como base para aplicaciones frontend o móviles que requieran funcionalidades de gestión de tareas.

---

## Requisitos Previos

Antes de instalar o ejecutar este proyecto, asegúrate de contar con lo siguiente en tu entorno:

- PHP >= 8.0  
- Composer  
- MySQL o MariaDB  
- Laravel >= 10.x  
- XAMPP o cualquier servidor local con Apache y MySQL  
- Git (opcional pero recomendado)

---

## Clonación del Repositorio

Para obtener una copia del proyecto en tu máquina local, ejecuta los siguientes comandos en tu terminal:

```bash
git clone https://github.com/tu-usuario/toDoList.git
cd toDoList
```

---

## Instalación de Dependencias

Una vez dentro del proyecto, instala las dependencias necesarias ejecutando:

```bash
composer install
```

Este comando descargará e instalará todas las librerías requeridas por Laravel para funcionar correctamente.

---

## Configuración del Entorno

1. Copia el archivo de configuración por defecto:

   ```bash
   cp .env.example .env
   ```

2. Configura las credenciales de tu base de datos dentro del archivo `.env`. Por ejemplo:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=todo_list
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. Genera la clave de aplicación de Laravel:

   ```bash
   php artisan key:generate
   ```

---

## Configuración de la Base de Datos

1. Inicia XAMPP y asegúrate de que Apache y MySQL estén ejecutándose.
2. Abre phpMyAdmin y crea una base de datos nueva con el nombre `todo_list` (o el nombre que hayas especificado en el `.env`).

---

## Ejecución de Migraciones

Una vez configurada la base de datos, ejecuta las migraciones para crear las tablas necesarias:

```bash
php artisan migrate
```

---

## Servidor de Desarrollo

Para iniciar el servidor local y probar la API, ejecuta:

```bash
php artisan serve
```

Esto habilitará la aplicación en la URL:

```
http://localhost:8000
```

---

## Documentación de la API con Swagger

Este proyecto utiliza **Swagger** para documentar y probar los endpoints disponibles de la API.

Una vez que el servidor esté corriendo, puedes acceder a la documentación desde tu navegador en:

```
http://localhost:8000/api/documentation
```


---

## Contacto

Para cualquier duda o sugerencia sobre este proyecto, puedes contactarme a través de GitHub.

## Stack Tecnológico

Este proyecto está construido con el siguiente conjunto de tecnologías:

| Tecnología | Descripción | Logo |
|------------|-------------|------|
| **PHP** | Lenguaje principal del backend. Utilizado por Laravel para manejar la lógica del servidor. | ![PHP](https://www.php.net/images/logos/php-logo.svg) |
| **Laravel** | Framework de PHP que permite el desarrollo estructurado y escalable de aplicaciones web. | ![Laravel](https://laravel.com/img/logomark.min.svg) |
| **MySQL** | Sistema de gestión de bases de datos relacional, usado para almacenar las tareas. | ![MySQL](https://www.mysql.com/common/logos/logo-mysql-170x115.png) |
| **Swagger (L5-Swagger)** | Herramienta para generar documentación interactiva de la API. | ![Swagger](https://static1.smartbear.co/swagger/media/assets/images/swagger_logo.svg) |
