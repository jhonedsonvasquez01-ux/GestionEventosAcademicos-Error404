
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividad_expositor', function (Blueprint $table) {
            $table->id();

            $table->foreignId('actividad_id')
                ->constrained('actividades')
                ->cascadeOnDelete();

            $table->foreignId('expositor_id')
                ->constrained('expositores')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(
                ['actividad_id', 'expositor_id'],
                'actividad_expositor_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_expositor');
    }
};

