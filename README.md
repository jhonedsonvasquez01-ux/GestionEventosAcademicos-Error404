# Gestión de Eventos Académicos

Aplicación Laravel para administrar eventos académicos, actividades, participantes, expositores, inscripciones, asistencias y certificados.

## Requisitos
- PHP 8.3 o superior
- Composer
- PostgreSQL
- Extensiones PHP requeridas por Laravel

## Instalación en Windows (Laragon)
1. Copiar `.env.example` a `.env`.
2. Crear en PostgreSQL la base de datos `gestion_eventos_academicos`.
3. Configurar en `.env` los valores reales de PostgreSQL:
   - `DB_CONNECTION=pgsql`
   - `DB_HOST=127.0.0.1`
   - `DB_PORT=5432`
   - `DB_DATABASE=gestion_eventos_academicos`
   - `DB_USERNAME=tu_usuario`
   - `DB_PASSWORD=tu_contraseña`
4. Ejecutar `composer install`.
5. Ejecutar `php artisan key:generate`.
6. Ejecutar `php artisan config:clear`.
7. En una base local vacía, ejecutar `php artisan migrate:fresh --seed`.
8. Iniciar con `php artisan serve`.

> `migrate:fresh` borra todas las tablas de la base configurada. No usar con datos que se quieran conservar.

## Actividad 2
Consultar `documentacion/ACTIVIDAD_2_INTEGRIDAD_BD.md` para:
- Factories para todos los modelos de dominio.
- Seeder con datos relacionados.
- Diez consultas para ejecutar en `php artisan tinker`.
- Explicaciones técnicas y tabla para documentar salidas reales.
- Pasos para repositorio Git.

## Nota sobre las evidencias
Las salidas de Tinker y las capturas deben generarse en el equipo donde se ejecute el proyecto. El documento incluye resultados esperados solo cuando corresponde (por ejemplo, colecciones vacías para verificar duplicados); no inventa conteos de la base del usuario.

## Seguridad
El archivo `.env` no se distribuye en este paquete. Crea uno localmente a partir de `.env.example` y no subas credenciales al repositorio.
