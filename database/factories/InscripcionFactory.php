<?php
namespace Database\Factories;
use App\Models\Inscripcion; use App\Models\Participante; use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;
/** @extends Factory<Inscripcion> */
class InscripcionFactory extends Factory {
 protected $model=Inscripcion::class;
 public function definition(): array { return ['participante_id'=>Participante::factory(), 'evento_id'=>Evento::factory(), 'fecha_inscripcion'=>fake()->dateTimeBetween('-30 days','now')->format('Y-m-d'), 'estado'=>fake()->randomElement(['inscrito','confirmado','cancelado'])]; }
}
