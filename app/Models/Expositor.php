<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Expositor extends Model
{
    use HasFactory;

    protected $table = 'expositores';

    protected $fillable = [
        'nombre',
        'apellido',
        'especialidad',
        'institucion',
        'email',
    ];

    public function actividades(): BelongsToMany
    {
        return $this->belongsToMany(
            Actividad::class,
            'actividad_expositor'
        )->withTimestamps();
    }
}
