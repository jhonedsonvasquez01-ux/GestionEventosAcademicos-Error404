<?php
namespace Database\Factories;
use App\Models\Certificado; use App\Models\Inscripcion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/** @extends Factory<Certificado> */
class CertificadoFactory extends Factory {
 protected $model=Certificado::class;
 public function definition(): array { return ['inscripcion_id'=>Inscripcion::factory(), 'codigo'=>'CERT-'.Str::upper(Str::random(12)), 'fecha_emision'=>fake()->dateTimeBetween('-1 year','now')->format('Y-m-d')]; }
}
