<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividades';
    
    protected $fillable = [
        'evento_id',
        'nombre',
        'tipo',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'sala',
        'cupo',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'cupo' => 'integer',
        ];
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    public function expositores(): BelongsToMany
    {
        return $this->belongsToMany(Expositor::class, 'actividad_expositor')->withTimestamps();
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function inscripciones(): BelongsToMany
    {
        return $this->belongsToMany(Inscripcion::class, 'inscripcion_actividad')->withTimestamps();
    }
}