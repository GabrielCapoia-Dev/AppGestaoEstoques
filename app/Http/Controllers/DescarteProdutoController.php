<?php

namespace App\Http\Controllers;

use App\Models\DescarteProduto;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DescarteProdutoController extends Controller
{
    /**
     * Visualizar todos os produtos descartados
     */
    public function index()
    {
        $descartes = DescarteProduto::all();
        return response()->json([
            'error' => false,
            'message' => 'Descartes encontrados.',
            'descartes' => $descartes
        ], 200);
    }

    /**
     * Criar um novo descarte
     */
    public function store(Request $request, $id_produto)
    {
        $produto = Produto::find($id_produto);

        if (!$produto) {
            return response()->json([
                'error' => true,
                'message' => 'Produto não encontrado.'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'id_produto' => 'required|exists:produtos,id',
                'quantidade_descarte' => 'required|numeric|min:0',
                'descricao_descarte' => 'required|string|min:2|max:255',
                'vencimento_descarte' => 'required|in:Sim,Não',
                'defeito_descarte' => 'required|in:Sim,Não',
                'data_descarte' => 'required|date_format:Y-m-d',
            ],
            [
                'required' => 'O campo :attribute é obrigatório.',
                'exists' => 'O campo :attribute não existe.',
                'string' => 'O campo :attribute deve ser uma string.',
                'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
                'numeric' => 'O campo :attribute deve ser um número.',
                'date_format' => 'O campo :attribute deve ser uma data no formato Y-m-d.',
                'in' => 'O campo :attribute deve ser um dos seguintes valores: :values.',
            ],
            [
                'id_produto' => 'ID do produto',
                'quantidade_descarte' => 'Quantidade descartada',
                'descricao_descarte' => 'Descrição do descarte',
                'vencimento_descarte' => 'Vencimento do descarte',
                'defeito_descarte' => 'Defeito do descarte',
                'data_descarte' => 'Data do descarte',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao criar descarte.',
                'errors' => $validator->errors()
            ], 422);
        }

        $descarte = DescarteProduto::create([
            'id_produto' => $produto->id,
            'quantidade_descarte' => $request->quantidade_descarte,
            'descricao_descarte' => $request->descricao_descarte,
            'vencimento_descarte' => $request->vencimento_descarte,
            'defeito_descarte' => $request->defeito_descarte,
            'data_descarte' => $request->data_descarte,
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Descarte criado com sucesso.',
            'descarte' => $descarte
        ], 201);
    }

    /**
     * Visualizar o descarte de um produto especifico
     */
    public function show($id_produto)
    {
        $descartes = DescarteProduto::where('id_produto', $id_produto)->get();
        return response()->json([
            'error' => false,
            'message' => 'Descartes encontrados.',
            'descartes' => $descartes
        ], 200);
    }

    /**
     * Atualizar informacoes do descarte
     */
    public function update(Request $request, $id_descarte)
    {
        $descarte = DescarteProduto::find($id_descarte);

        if (!$descarte) {
            return response()->json([
                'error' => true,
                'message' => 'Descarte nao encontrado.'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'id_produto' => 'required|exists:produtos,id',
                'quantidade_descarte' => 'required|numeric|min:0',
                'descricao_descarte' => 'required|string|min:2|max:255',
                'vencimento_descarte' => 'required|in:Sim,Não',
                'defeito_descarte' => 'required|in:Sim,Não',
                'data_descarte' => 'required|date_format:Y-m-d',
            ],
            [
                'required' => 'O campo :attribute é obrigatório.',
                'exists' => 'O campo :attribute não existe.',
                'string' => 'O campo :attribute deve ser uma string.',
                'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
                'numeric' => 'O campo :attribute deve ser um número.',
                'date_format' => 'O campo :attribute deve ser uma data no formato Y-m-d.',
                'in' => 'O campo :attribute deve ser um dos seguintes valores: :values.',
            ],
            [
                'id_produto' => 'ID do produto',
                'quantidade_descarte' => 'Quantidade descartada',
                'descricao_descarte' => 'Descrição do descarte',
                'vencimento_descarte' => 'Vencimento do descarte',
                'defeito_descarte' => 'Defeito do descarte',
                'data_descarte' => 'Data do descarte',
            ]
        );

        $descarte->update($request->all());

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao criar descarte.',
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'error' => false,
            'message' => 'Descarte atualizado com sucesso.',
            'descarte' => $descarte
        ], 200);
    }

}
