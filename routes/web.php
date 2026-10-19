<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\ExpositorController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\CertificadoController;

Route::get('/', function () { return view('dashboard'); })->name('dashboard');
Route::resource('eventos', EventoController::class);
Route::resource('actividades', ActividadController::class)->parameters(['actividades' => 'actividad']);
Route::resource('participantes', ParticipanteController::class);
Route::resource('expositores', ExpositorController::class)->parameters(['expositores' => 'expositor']);
Route::resource('inscripciones', InscripcionController::class)->parameters(['inscripciones' => 'inscripcion']);
Route::get('asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
Route::post('asistencias', [AsistenciaController::class, 'store'])->name('asistencias.store');
Route::resource('certificados', CertificadoController::class)->only(['index','store','show','destroy']);
