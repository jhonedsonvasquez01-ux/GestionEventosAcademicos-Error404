<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('participante_id')
                ->constrained('participantes')
                ->cascadeOnDelete();

            $table->foreignId('evento_id')
                ->constrained('eventos')
                ->cascadeOnDelete();

            $table->date('fecha_inscripcion');
            $table->string('estado', 30)->default('inscrito');
            $table->timestamps();

            $table->unique(
                ['participante_id', 'evento_id'],
                'inscripciones_participante_evento_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};

