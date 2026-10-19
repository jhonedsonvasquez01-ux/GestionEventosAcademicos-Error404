# Actividad 2 — Integridad de la base de datos
## Sistema de Gestión de Eventos Académicos

**Tecnologías:** Laravel 13, Eloquent ORM, PostgreSQL, PHP 8.3.  
**Idioma:** español.

## 1. Objetivo
Validar el diseño relacional mediante migraciones, relaciones Eloquent, factories, seeders y diez consultas ejecutadas con Tinker.

## 2. Modelo de dominio implementado
- **Evento** tiene muchas **Actividades** y muchas **Inscripciones**.
- **Participante** tiene muchas **Inscripciones**.
- **Inscripción** pertenece a un **Participante** y a un **Evento**, puede asociarse con muchas **Actividades**, registra **Asistencias** y tiene como máximo un **Certificado**.
- **Actividad** pertenece a un **Evento**, se relaciona con muchos **Expositores** y muchas **Inscripciones**.
- **Asistencia** relaciona una inscripción con una actividad.
- **Certificado** pertenece a una inscripción.

Las migraciones incluyen claves foráneas y borrado en cascada en relaciones dependientes, unicidad para CI y correo del participante, correo del expositor cuando está informado, una inscripción por participante/evento, una asociación por par en tablas pivote, una asistencia por inscripción/actividad y un certificado por inscripción con código único.

## 3. Preparación del entorno
1. Crear la base de datos PostgreSQL `gestion_eventos_academicos`.
2. Copiar `.env.example` a `.env` y configurar `DB_CONNECTION=pgsql`, host, puerto, base de datos, usuario y contraseña según la instalación local.
3. Instalar dependencias: `composer install`.
4. Generar clave: `php artisan key:generate`.
5. Limpiar configuración: `php artisan config:clear`.
6. Ejecutar migraciones y datos de prueba: `php artisan migrate:fresh --seed`.

> **Advertencia:** `migrate:fresh` elimina todas las tablas de la base configurada. Usarlo únicamente en una base local de pruebas y después de respaldar datos importantes.

Para conservar los datos existentes, usar `php artisan migrate` y ejecutar el seeder solo si se desea insertar datos de demostración; evitar ejecutar varias veces en una base de producción.

## 4. Factories y seeders
Se incorporan factories para Evento, Actividad, Participante, Expositor, Inscripcion, Asistencia y Certificado, además de la factory de User que ya trae Laravel. `DatabaseSeeder` genera un conjunto relacionado de eventos, actividades, expositores, participantes, inscripciones, asistencias y certificados. Las asociaciones se crean respetando las claves foráneas y las restricciones únicas.

Comando:
```bash
php artisan db:seed
```
Para recrear desde cero en una base de pruebas:
```bash
php artisan migrate:fresh --seed
```

**Evidencia requerida:** insertar aquí una captura real de la terminal mostrando que `php artisan migrate:fresh --seed` finalizó sin errores.

## 5. Diez consultas para Tinker

Abrir Tinker:
```bash
php artisan tinker
```
Ejecutar las siguientes consultas **una por una**. Guardar una captura real de cada salida o agrupar capturas legibles en el informe. Los números de resultado dependen de los datos de la base; no se deben copiar resultados inventados.

### Consulta 1 — Conteo general de entidades
```php
[
    'eventos' => \App\Models\Evento::count(),
    'actividades' => \App\Models\Actividad::count(),
    'participantes' => \App\Models\Participante::count(),
    'expositores' => \App\Models\Expositor::count(),
    'inscripciones' => \App\Models\Inscripcion::count(),
    'asistencias' => \App\Models\Asistencia::count(),
    'certificados' => \App\Models\Certificado::count(),
]
```
**Valida:** que las tablas principales estén creadas y contengan datos después de ejecutar el seeder. Registrar la salida real.

### Consulta 2 — Unicidad de CI y correo de participantes
```php
\App\Models\Participante::query()
    ->select('ci')
    ->groupBy('ci')
    ->havingRaw('COUNT(*) > 1')
    ->get();
```
**Resultado esperado:** colección vacía. La restricción `unique` de la migración impide CI duplicados; esta consulta verifica los datos ya guardados.

### Consulta 3 — Inscripciones duplicadas por participante y evento
```php
\App\Models\Inscripcion::query()
    ->select('participante_id', 'evento_id')
    ->groupBy('participante_id', 'evento_id')
    ->havingRaw('COUNT(*) > 1')
    ->get();
```
**Resultado esperado:** colección vacía. Comprueba la regla de una sola inscripción por participante y evento, reforzada por una restricción única compuesta.

### Consulta 4 — Integridad referencial de inscripciones
```php
\App\Models\Inscripcion::query()
    ->whereDoesntHave('participante')
    ->orWhereDoesntHave('evento')
    ->count();
```
**Resultado esperado:** `0`. Comprueba que todas las inscripciones tengan participante y evento asociados. Las claves foráneas también protegen la integridad en la base de datos.

### Consulta 5 — Relaciones Eloquent de eventos y actividades
```php
\App\Models\Evento::withCount(['actividades', 'inscripciones'])
    ->orderBy('nombre')
    ->get(['id', 'nombre']);
```
**Valida:** que Eloquent resuelva las relaciones `hasMany` y que el conteo relacionado sea consultable.

### Consulta 6 — Collection: participantes con inscripciones confirmadas
```php
\App\Models\Participante::with('inscripciones')
    ->get()
    ->filter(fn ($p) => $p->inscripciones->contains('estado', 'confirmado'))
    ->values()
    ->map(fn ($p) => ['participante' => $p->nombre.' '.$p->apellido,
                      'inscripciones_confirmadas' => $p->inscripciones->where('estado', 'confirmado')->count()]);
```
**Valida:** uso de Collection `filter`, `contains`, `where` y `map` sobre relaciones cargadas.

### Consulta 7 — Collection: actividades agrupadas por tipo
```php
\App\Models\Actividad::all()
    ->groupBy('tipo')
    ->map(fn ($grupo) => $grupo->count());
```
**Valida:** clasificación de actividades por tipo mediante `groupBy` de Collections. Permite observar la distribución de talleres, conferencias u otros tipos.

### Consulta 8 — Agregación: número de inscripciones por evento
```php
\App\Models\Evento::withCount('inscripciones')
    ->get(['id', 'nombre'])
    ->map(fn ($e) => ['evento' => $e->nombre, 'total_inscripciones' => $e->inscripciones_count]);
```
**Valida:** agregación `COUNT` por evento mediante Eloquent.

### Consulta 9 — Agregación: porcentaje de asistencia registrada
```php
[
    'total_registros' => \App\Models\Asistencia::count(),
    'asistencias_presentes' => \App\Models\Asistencia::where('asistio', true)->count(),
    'porcentaje_presentes' => round(
        (\App\Models\Asistencia::count() > 0)
            ? \App\Models\Asistencia::where('asistio', true)->count() * 100 / \App\Models\Asistencia::count()
            : 0,
        2
    ),
]
```
**Valida:** agregaciones `COUNT` y cálculo porcentual de asistencias. Si no hay registros, evita dividir entre cero.

### Consulta 10 — Integridad de certificados e inscripciones
```php
\App\Models\Certificado::whereDoesntHave('inscripcion')->count();
```
**Resultado esperado:** `0`. Comprueba que cada certificado tenga una inscripción asociada. La clave foránea protege esta relación y la restricción única limita a un certificado por inscripción.

## 6. Tabla para documentar resultados reales
Completar durante la ejecución de Tinker.

| N.º | Resultado observado | Evidencia |
|---|---|---|
| 1 | Pegar la salida real de conteos | Captura 1 |
| 2 | Indicar si la colección está vacía | Captura 2 |
| 3 | Indicar si la colección está vacía | Captura 3 |
| 4 | Anotar el conteo obtenido | Captura 4 |
| 5 | Resumir los eventos y conteos | Captura 5 |
| 6 | Resumir el resultado filtrado | Captura 6 |
| 7 | Resumir el agrupamiento por tipo | Captura 7 |
| 8 | Resumir el conteo por evento | Captura 8 |
| 9 | Anotar totales y porcentaje obtenidos | Captura 9 |
| 10 | Anotar el conteo obtenido | Captura 10 |

## 7. Repositorio Git
La consigna solicita un enlace a GitHub/GitLab con historial incremental. Desde la carpeta del proyecto:
```bash
git init
git add .
git commit -m "Base inicial del sistema de eventos"
```
Después de revisar y confirmar los archivos, crear commits separados para migraciones/modelos, factories/seeders y documentación. Crear un repositorio remoto en GitHub/GitLab y seguir las instrucciones que proporciona la plataforma para vincularlo y hacer `push`.

No subir `.env`, contraseñas, claves ni credenciales. Subir `.env.example` sin secretos.

## 8. Conclusión
La actividad reúne las piezas solicitadas para probar el diseño: migraciones con restricciones, modelos y relaciones, generación de datos y diez consultas variadas. La evidencia final solo se considera completa después de ejecutar los comandos en el entorno real, revisar las salidas y adjuntar las capturas correspondientes.

**Estudiante:** ____________________  
**Materia:** ____________________  
**Docente:** ____________________  
**Fecha:** ____________________
