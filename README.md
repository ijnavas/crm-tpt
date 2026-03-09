# CRM TPT Empleo

## Requisitos
- PHP 8.2+
- MySQL / MariaDB
- Apache con mod_rewrite
- Composer

## Instalación
1. Sube el proyecto al servidor.
2. Copia `.env.example` a `.env` y ajusta credenciales.
3. Ejecuta `composer dump-autoload`.
4. Crea la base de datos `crm_tpt`.
5. Ejecuta los SQL de `database/migrations`.
6. Inserta un usuario admin usando el ejemplo del archivo `database/seeds/002_admin_seed.sql`.
7. Apunta el dominio o subdominio a la carpeta `public/`.

## Login
El proyecto no trae una contraseña real por seguridad. Genera el hash con:

```php
<?php echo password_hash('TuPasswordSegura123!', PASSWORD_DEFAULT);
```

Y sustitúyelo en `database/seeds/002_admin_seed.sql`.
