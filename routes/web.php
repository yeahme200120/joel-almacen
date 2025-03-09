<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();
Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/actualizarProducto/{campo}/{valor}/{id}', [App\Http\Controllers\HomeController::class, 'actualizarProducto'])->name('actualizarProducto');
Route::get('/registrarProducto', [App\Http\Controllers\HomeController::class, 'registrarProducto'])->name('registrarProducto');
Route::post('/registerProduct', [App\Http\Controllers\HomeController::class, 'registerProduct'])->name('registerProduct');
Route::get('/eliminarProducto/{producto}', [App\Http\Controllers\HomeController::class, 'eliminarProducto'])->name('eliminarProducto');
Route::get('/pedidos', [App\Http\Controllers\HomeController::class, 'pedidos'])->name('pedidos');
Route::post('/agregarProducto', [App\Http\Controllers\HomeController::class, 'agregarProducto'])->name('agregarProducto');
Route::get('/getExistencia/{producto}', [App\Http\Controllers\HomeController::class, 'getExistencia'])->name('getExistencia');
Route::post('/agregarSolicitud', [App\Http\Controllers\HomeController::class, 'agregarSolicitud'])->name('agregarSolicitud');

