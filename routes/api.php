<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdministradorController;



//Rota exclusivas do admin
Route::middleware(['auth:jwt', 'permissao:Administrador'])->group(function () {

    Route::prefix('admin')->group(function () {
        Route::post('/criar', [AdministradorController::class, 'adminCriar']);
        Route::put('/editar/{id}', [UsuarioController::class, 'editaAdmin']);

        Route::put('/desativar/{id}', [AdministradorController::class, 'desativarAdmin']);
        Route::put('/ativar/{id}', [AdministradorController::class, 'ativarAdmin']);

        Route::get('/listar/ativos', [AdministradorController::class, 'visualizarAdministradoresAtivos']);
        Route::get('/listar/inativos', [AdministradorController::class, 'visualizarAdministradoresInativos']);

        Route::get('/listar', [AdministradorController::class, 'listarComAdmin']);
        Route::get('/listar/{id}', [AdministradorController::class, 'showAdmin']);
    });
});



//Rotas para gerenciamento
Route::middleware(['auth:jwt', 'permissao:Administrador|subAdmin'])->group(function () {

    Route::prefix('usuarios')->group(function () {
        Route::post('/criar', [UsuarioController::class, 'store']);
        Route::put('/editar/{id}', [UsuarioController::class, 'update']);

        Route::put('/desativar/{id}', [UsuarioController::class, 'desativarUsuario']);
        Route::put('/ativar/{id}', [UsuarioController::class, 'ativarUsuario']);

        Route::get('/listar/permissao/{permissao}', [UsuarioController::class, 'listarPorPermissao']);
        Route::get('/listar/ativos', [UsuarioController::class, 'listarAtivos']);
        Route::get('/listar/inativos', [UsuarioController::class, 'listarInativos']);

        Route::get('/listar', [UsuarioController::class, 'listar']);
        Route::get('/listar/{id}', [UsuarioController::class, 'show']);
    });
});
