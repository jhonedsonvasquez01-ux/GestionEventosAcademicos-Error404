
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inscripcion_id')
                ->unique()
                ->constrained('inscripciones')
                ->cascadeOnDelete();

            $table->string('codigo', 100)->unique();
            $table->date('fecha_emision');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
};
