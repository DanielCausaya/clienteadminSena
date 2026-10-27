<?php

use App\Http\Controllers\ComputerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rutas del panel. Este proyecto es un cliente de la API de adminSena,
| por lo que cada controlador consulta config('services.api.url').
|
*/

Route::get('/', [ComputerController::class, 'index']);

Route::get('computers', [ComputerController::class, 'index'])->name('computers.index');
Route::get('computer/create', [ComputerController::class, 'create'])->name('computer.create');
Route::post('computer', [ComputerController::class, 'store'])->name('computer.store');
Route::get('computer/{computer}', [ComputerController::class, 'show'])->name('computer.show');
Route::get('computer/{computer}/editar', [ComputerController::class, 'edit'])->name('computer.edit');
Route::put('computer/{computer}', [ComputerController::class, 'update'])->name('computer.update');
Route::delete('computer/{computer}', [ComputerController::class, 'destroy'])->name('computer.destroy');
