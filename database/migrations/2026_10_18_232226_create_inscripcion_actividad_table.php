
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripcion_actividad', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inscripcion_id')
                ->constrained('inscripciones')
                ->cascadeOnDelete();

            $table->foreignId('actividad_id')
                ->constrained('actividades')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(
                ['inscripcion_id', 'actividad_id'],
                'inscripcion_actividad_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripcion_actividad');
    }
};
