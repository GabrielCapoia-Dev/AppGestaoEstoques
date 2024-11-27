<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\NotificacaoProdutoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\DescarteProdutoController;

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

    Route::prefix('produtos')->group(function () {
        Route::post('/criar', [ProdutoController::class, 'store']);
        Route::put('/editar/{id}', [ProdutoController::class, 'update']);

        Route::put('/desativar/{id}', [ProdutoController::class, 'desativarProduto']);
        Route::put('/ativar/{id}', [ProdutoController::class, 'ativarProduto']);

        Route::get('/listar/categoria/{id`^', [ProdutoController::class, 'listarProdutosPorCategoria']);
        Route::get('/listar/ativos/estoque/{id}', [ProdutoController::class, 'listarProdutosAtivosPorEstoque']);

        Route::get('/listar/todos', [ProdutoController::class, 'index']);
        Route::get('/listar/{id}', [ProdutoController::class, 'show']);
    });

    Route::prefix('estoques')->group(function () {
        Route::post('/criar', [EstoqueController::class, 'store']);
        Route::put('/editar/{id}', [EstoqueController::class, 'update']);

        Route::put('/desativar/{id}', [EstoqueController::class, 'desativarEstoque']);
        Route::put('/ativar/{id}', [EstoqueController::class, 'ativarEstoque']);

        Route::get('/listar/ativos', [EstoqueController::class, 'listarEstoquesAtivos']);
        Route::get('/listar/inativos', [EstoqueController::class, 'listarEstoquesInativos']);

        Route::get('/listar', [EstoqueController::class, 'index']);
        Route::get('/listar/{id}', [EstoqueController::class, 'show']);
    });

    Route::prefix('enderecos')->group(function () {
        Route::post('/criar', [EnderecoController::class, 'store']);
        Route::put('/editar/{id}', [EnderecoController::class, 'update']);

        Route::get('/listar/{id}', [EnderecoController::class, 'show']);
    });

    Route::prefix('locais')->group(function () {
        Route::post('/criar', [LocalController::class, 'store']);
        Route::put('/editar/{id}', [LocalController::class, 'update']);

        Route::get('/listar', [LocalController::class, 'index']);
        Route::get('/listar/{id}', [LocalController::class, 'show']);

        Route::put('/desativar/{id}', [LocalController::class, 'desativarLocal']);
        Route::put('/ativar/{id}', [LocalController::class, 'ativarLocal']);

        Route::get('/listar/ativos', [LocalController::class, 'visualizarEstoquesAtivosDoLocal']);
        Route::get('/listar/inativos', [LocalController::class, 'visualizarEstoquesInativosDoLocal']);
    });

    Route::prefix('categorias')->group(function () {
        Route::post('/criar', [CategoriaController::class, 'store']);
        Route::put('/editar/{id}', [CategoriaController::class, 'update']);

        Route::get('/listar', [CategoriaController::class, 'index']);
        Route::get('/listar/{id}', [CategoriaController::class, 'show']);

        Route::put('/desativar/{id}', [CategoriaController::class, 'desativarCategoria']);
        Route::put('/ativar/{id}', [CategoriaController::class, 'ativarCategoria']);

        Route::get('/listar/ativos', [CategoriaController::class, 'visualizarCategoriasAtivas']);
        Route::get('/listar/inativos', [CategoriaController::class, 'visualizarCategoriasInativas']);
    });

    Route::prefix('notificacoes')->group(function () {
        Route::post('/criar', [NotificacaoProdutoController::class, 'store']);
        Route::put('/editar', [NotificacaoProdutoController::class, 'update']);
        Route::get('/listar', [NotificacaoProdutoController::class, 'index']);
        Route::get('/listar/{id}', [NotificacaoProdutoController::class, 'show']);
        Route::get('/listar/quantidade', [NotificacaoProdutoController::class, 'quantidadeNotificacoesNaoVisualizadas']);
    });

    Route::prefix('historicos')->group(function () {
        Route::post('/listar/tudo/{id}', [HistoricoController::class, 'visuzalizarHistoricoDeProduto']);
        Route::get('/listar', [HistoricoController::class, 'index']);
        Route::get('/listar/{id}', [HistoricoController::class, 'show']);
    });

    Route::prefix('descartes')->group(function () {    
        Route::post('/criar', [DescarteProdutoController::class, 'store']);
        Route::get('/listar', [DescarteProdutoController::class, 'index']);
        Route::get('/listar/{id}', [DescarteProdutoController::class, 'show']);
        Route::put('/editar/{id}', [DescarteProdutoController::class, 'update']);
    });
});
