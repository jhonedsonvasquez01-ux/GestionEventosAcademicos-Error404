<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';

    protected $fillable = [
        'participante_id',
        'evento_id',
        'fecha_inscripcion',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inscripcion' => 'date',
        ];
    }

    public function participante(): BelongsTo
    {
        return $this->belongsTo(Participante::class);
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    public function actividades(): BelongsToMany
    {
        return $this->belongsToMany(
            Actividad::class,
            'inscripcion_actividad'
        )->withTimestamps();
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function certificado(): HasOne
    {
        return $this->hasOne(Certificado::class);
    }
}
