<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.index');
});

Auth::routes();

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware('auth');

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

//Ruta para actualizar los datos del usuario autenticado
Route::get('/admin/configuracion/edit', [App\Http\Controllers\ConfiguracionController::class, 'edit'])->name('admin.configuracion.edit')->middleware('auth');
Route::put('/admin/configuracion/{id}', [App\Http\Controllers\ConfiguracionController::class, 'update'])->name('admin.configuracion.update')->middleware('auth');

//Rutas para la gestión de servicios
Route::get('/admin/servicios', [App\Http\Controllers\ServicioController::class, 'index'])->name('admin.servicio.index')->middleware('auth');
Route::get('/admin/servicios/create', [App\Http\Controllers\ServicioController::class, 'create'])->name('admin.servicio.create')->middleware('auth');
Route::post('/admin/servicios/create', [App\Http\Controllers\ServicioController::class, 'store'])->name('admin.servicios.store')->middleware('auth');
Route::get('/admin/servicios/edit/{id}', [App\Http\Controllers\ServicioController::class, 'edit'])->name('admin.servicios.edit')->middleware('auth');
Route::put('/admin/servicios/{id}', [App\Http\Controllers\ServicioController::class, 'update'])->name('admin.servicios.update')->middleware('auth');
Route::delete('/admin/servicios/{id}', [App\Http\Controllers\ServicioController::class, 'destroy'])->name('admin.servicios.destroy')->middleware('auth');

//Rutas para la gestión de reservas
Route::get('/admin/reservas', [App\Http\Controllers\ReservaController::class, 'index'])->name('admin.reservas.index')->middleware('auth');
Route::get('/admin/reservas/create', [App\Http\Controllers\ReservaController::class, 'create'])->name('admin.reservas.create')->middleware('auth');
Route::get('/api/horarios/{fecha}', [App\Http\Controllers\ReservaController::class, 'horariosPorFecha']);
//Route::get('/admin/reservas/{fecha}', [App\Http\Controllers\ReservaController::class, 'horariosPorFecha'])->name('horarios.por.fecha')->middleware('auth');
Route::post('/admin/reservas/create', [App\Http\Controllers\ReservaController::class, 'store'])->name('admin.reservas.store')->middleware('auth');
Route::get('/admin/reservas/edit/{id}', [App\Http\Controllers\ReservaController::class, 'edit'])->name('admin.reservas.edit')->middleware('auth');
Route::put('/admin/reservas/{id}', [App\Http\Controllers\ReservaController::class, 'update'])->name('admin.reservas.update')->middleware('auth');
Route::delete('/admin/reservas/{id}', [App\Http\Controllers\ReservaController::class, 'destroy'])->name('admin.reservas.destroy')->middleware('auth');
