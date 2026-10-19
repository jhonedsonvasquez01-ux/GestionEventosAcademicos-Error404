<?php
namespace Database\Factories;
use App\Models\Participante; use Illuminate\Database\Eloquent\Factories\Factory;
/** @extends Factory<Participante> */
class ParticipanteFactory extends Factory {
 protected $model=Participante::class;
 public function definition(): array { return ['nombre'=>fake()->firstName(), 'apellido'=>fake()->lastName(), 'ci'=>fake()->unique()->numerify('########'), 'email'=>fake()->unique()->safeEmail(), 'telefono'=>fake()->optional()->numerify('7#######')]; }
}
