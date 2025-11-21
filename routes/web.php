<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.index');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('admin')->middleware('auth');

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware('auth');

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