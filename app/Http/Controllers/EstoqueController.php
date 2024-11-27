<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstoqueController extends Controller
{
    /**
     * Retorna todos os estoques
     */
    public function index()
    {
        $estoque = Estoque::all();

        if (!$estoque) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum estoque encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Estoques encontrados.',
            'estoque' => $estoque
        ], 200);
    }

    /**
     * O metodo deve ser static pois quando o local é gerado o estoque também deve ser criado junto
     */
    public static function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nome_estoque' => 'required|string|min:2|max:30',
                'descricao_estoque' => 'required|string|min:2|max:255',
            ],
            [
                'required' => 'O campo :attribute é obrigatório.',
                'exists' => 'O campo :attribute nao existe.',
                'string' => 'O campo :attribute deve ser uma string.',
                'in' => 'O campo :attribute deve ser "Ativo" ou "Inativo".',
                'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
            ],
            [
                'nome_estoque' => 'Nome Estoque',
                'descricao_estoque' => 'Descricao Estoque',
            ]
        );


        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $estoque = Estoque::create([
            'nome_estoque' => $request->nome_estoque,
            'status_estoque' => 'Ativo', // O estoque é criado automaticamente com status Ativo
            'descricao_estoque' => $request->descricao_estoque
        ]);

        return $estoque;
    }

    /**
     * Retorna um estoque especifico
     */
    public function show($id)
    {
        $estoque = Estoque::find($id);

        if (!$estoque) {
            return response()->json([
                'error' => true,
                'message' => 'Estoque nao encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Estoque encontrado.',
            'estoque' => $estoque
        ], 200);
    }

    /**
     * Atualiza o estoque
     */
    public function update(Request $request, $id)
    {
        $estoque = Estoque::find($id);

        if (!$estoque) {
            return response()->json([
                'error' => true,
                'message' => 'Estoque nao encontrado.'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'nome_estoque' => 'required|string|min:2|max:30',
                'descricao_estoque' => 'required|string|min:2|max:255',
                'status_estoque' => 'required|string|in:Ativo,Inativo',
            ],
            [
                'required' => 'O campo :attribute é obrigatório.',
                'exists' => 'O campo :attribute nao existe.',
                'string' => 'O campo :attribute deve ser uma string.',
                'in' => 'O campo :attribute deve ser "Ativo" ou "Inativo".',
                'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
            ],
            [
                'nome_estoque' => 'Nome Estoque',
                'status_estoque' => 'Status Estoque',
                'descricao_estoque' => 'Descricao Estoque',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $estoque->nome_estoque = $request->nome_estoque;
        $estoque->status = $request->status;
        $estoque->descricao = $request->descricao;
        $estoque->save();

        return response()->json([
            'error' => false,
            'message' => 'Estoque atualizado com sucesso.',
            'estoque' => $estoque
        ], 200);
    }


    /**
     * Inativa o estoque e os produtos presentes nele
     */

    public function desativarEstoque($id)
    {
        $estoque = Estoque::find($id);

        if (!$estoque) {
            return response()->json([
                'error' => true,
                'message' => 'Estoque nao encontrado.'
            ], 404);
        }

        $estoque->status_estoque = 'Inativo';
        $estoque->save();

        $inativaProduto = ProdutoController::estoqueInativadoInativarProdutos($id);
        return response()->json([
            'error' => false,
            'message' => 'Estoque desativado com sucesso.',
            'estoque' => $estoque,
            'produtos' => $inativaProduto
        ], 200);
    }


    /**
     * Ativa o estoque e os produtos presentes nele
     */
    public function ativarEstoque($id)
    {
        $estoque = Estoque::find($id);

        if (!$estoque) {
            return response()->json([
                'error' => true,
                'message' => 'Estoque nao encontrado.'
            ], 404);
        }    

        $estoque->status_estoque = 'Ativo';
        $estoque->save();

        $ativaProduto = ProdutoController::estoqueAtivadoAtivarProdutos($id);

        return response()->json([
            'error' => false,
            'message' => 'Estoque ativado com sucesso.',
            'estoque' => $estoque,
            'produtos' => $ativaProduto
        ], 200);
    }

    /**
     * Listar estoque ativos
     */
    public function listarEstoquesAtivos()
    {
        $estoque = Estoque::where('status_estoque', 'Ativo')->get();

        if (!$estoque) {
            return response()->json([
                'error' => true,
                'message' => 'Estoque nao encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Estoque encontrado.',
            'estoque' => $estoque
        ], 200);
    }
    

    /**
     * Listar estoque inativo
     */
    public function listarEstoquesInativos()
    {
        $estoque = Estoque::where('status_estoque', 'Inativo')->get();

        if (!$estoque) {
            return response()->json([
                'error' => true,
                'message' => 'Estoque nao encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Estoque encontrado.',
            'estoque' => $estoque
        ], 200);
    }
}
