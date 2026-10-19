<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inscripcion_id')
                ->constrained('inscripciones')
                ->cascadeOnDelete();

            $table->foreignId('actividad_id')
                ->constrained('actividades')
                ->cascadeOnDelete();

            $table->boolean('asistio')->default(false);
            $table->timestamp('fecha_hora_registro')->nullable();
            $table->timestamps();

            $table->unique(
                ['inscripcion_id', 'actividad_id'],
                'asistencias_inscripcion_actividad_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
