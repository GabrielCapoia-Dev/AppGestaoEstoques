<?php

namespace App\Http\Controllers;

use App\Models\NotificacaoProduto;
use Illuminate\Http\Request;

class NotificacaoProdutoController extends Controller
{
    /**
     * Retorna todas as notificações de produtos
     */
    public function index()
    {
        $notificacaoProduto = NotificacaoProduto::all();

        if (!$notificacaoProduto) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum item encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Itens encontrados.',
            'notificacao' => $notificacaoProduto
        ], 200);
    }

    /**
     * O metodo deve ser static para quando o produto for atualizado a notificação também seja atualizada
     */
    public static function store(Request $request)
    {
        $notificacaoProduto = NotificacaoProduto::create([
            'id_produto' => $request->id_produto,
            'visualizado' => 'Não',
            'mensagem' => 'O produto ' . $request->nome . ' foi alterado.'
        ], 200);

        return $notificacaoProduto;
    }

    /**
     * Visualizar uma notificação especifica
     */
    public function show($id)
    {
        $notificacaoProduto = NotificacaoProduto::find($id);

        if (!$notificacaoProduto) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhuma notificação encontrada.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Notificação encontrada.',
            'notificacao' => $notificacaoProduto
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        $notificacaoProduto = NotificacaoProduto::all();

        if (!$notificacaoProduto) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhuma notificação encontrada.'
            ], 404);
        }

        $notificacaoProduto->update([
            'visualizado' => 'Sim'
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Notificação atualizada.',
            'notificacao' => $notificacaoProduto
        ], 200);
    }


    /**
     *  Visualizar a quantidade de notificações nao visualizadas
     */

    public function quantidadeNotificacoesNaoVisualizadas()
    {
        $quantidade = NotificacaoProduto::where('visualizado', 'Não')->count();

        return response()->json([
            'error' => false,
            'message' => 'Quantidade de notificação nao visualizadas.',
            'notificacoes' => $quantidade
        ], 200);
    }
}
