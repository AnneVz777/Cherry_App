<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\AvaliacaoController;

Route::middleware('throttle:api')->prefix('v1')->group(function () {



    // Produtos
    Route::get('/produtos',        [ProdutoController::class, 'index']);
    Route::get('/produtos/{id}',   [ProdutoController::class, 'show']);
    Route::post('/produtos',       [ProdutoController::class, 'store']);
    Route::put('/produtos/{id}',   [ProdutoController::class, 'update']);
    Route::delete('/produtos/{id}',[ProdutoController::class, 'destroy']);

    // Usuários
    Route::get('/usuarios',        [UsuarioController::class, 'index']);
    Route::get('/usuarios/{id}',   [UsuarioController::class, 'show']);
    Route::post('/usuarios',       [UsuarioController::class, 'store']);
    Route::put('/usuarios/{id}',   [UsuarioController::class, 'update']);
    Route::delete('/usuarios/{id}',[UsuarioController::class, 'destroy']);

    // Pedidos
    Route::get('/pedidos',         [PedidoController::class, 'index']);
    Route::get('/pedidos/{id}',    [PedidoController::class, 'show']);
    Route::post('/pedidos',        [PedidoController::class, 'store']);
    Route::put('/pedidos/{id}',    [PedidoController::class, 'update']);
    Route::delete('/pedidos/{id}', [PedidoController::class, 'destroy']);

    // Rotas especiais de pedido
    Route::put('/pedidos/{id}/entrega',   [PedidoController::class, 'atualizarEntrega']);
    Route::put('/pedidos/{id}/avaliacao', [PedidoController::class, 'atualizarAvaliacao']);

    // Entregas
    Route::get('/entregas',        [EntregaController::class, 'index']);
    Route::get('/entregas/{id}',   [EntregaController::class, 'show']);
    Route::post('/entregas',       [EntregaController::class, 'store']);
    Route::put('/entregas/{id}',   [EntregaController::class, 'update']);
    Route::delete('/entregas/{id}',[EntregaController::class, 'destroy']);

    // Avaliações
    Route::get('/avaliacoes',        [AvaliacaoController::class, 'index']);
    Route::get('/avaliacoes/{id}',   [AvaliacaoController::class, 'show']);
    Route::post('/avaliacoes',       [AvaliacaoController::class, 'store']);
    Route::put('/avaliacoes/{id}',   [AvaliacaoController::class, 'update']);
    Route::delete('/avaliacoes/{id}',[AvaliacaoController::class, 'destroy']);
});