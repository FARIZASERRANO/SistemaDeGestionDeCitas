<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('dashboard', [\App\Http\Controllers\Agenda::class, 'index'])->middleware(['auth:sanctum', 'verified']);
Route::get('eventos', [\App\Http\Controllers\Agenda::class, 'eventos']);
Route::get('eventoDetalle', [\App\Http\Controllers\Agenda::class, 'eventoDetalle']);
Route::get('clientes', [\App\Http\Controllers\Agenda::class, 'clientes']);
Route::get('trabajos', [\App\Http\Controllers\Agenda::class, 'trabajos']);
Route::get('trabajoDetalle', [\App\Http\Controllers\Agenda::class, 'trabajoDetalle']);
Route::get('compromisoCliente', [\App\Http\Controllers\Agenda::class, 'compromisoCliente'])->name('compromisoCliente');;
Route::post('nuevoServicio', [\App\Http\Controllers\Agenda::class, 'nuevoServicio'])->name('nuevoServicio');
Route::post('noShowEvento', [\App\Http\Controllers\Agenda::class, 'noShowEvento'])->name('noShowService');
Route::post('cancelEvento', [\App\Http\Controllers\Agenda::class, 'cancelEvento'])->name('cancelEvento');
Route::post('iniciarServicio', [\App\Http\Controllers\Agenda::class, 'iniciarServicio'])->name('iniciarServicio');
Route::post('finalizarServicio', [\App\Http\Controllers\Agenda::class, 'finalizarServicio'])->name('finalizarServicio');
Route::put('ActualizarHorasServicio', [\App\Http\Controllers\Agenda::class, 'ActualizarHorasServicio'])->name('ActualizarHorasServicio');
//Route::get('eventos', [\App\Http\Controllers\Agenda::class, 'eventos'])->middleware(['auth:sanctum', 'verified']);
