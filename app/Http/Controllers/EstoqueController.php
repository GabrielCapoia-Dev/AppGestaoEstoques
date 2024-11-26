<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
                'descricao' => 'required|string|min:2|max:255',
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
                'descricao' => 'Descricao',
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
            'status' => 'Ativo', // O estoque é criado automaticamente com status Ativo
            'descricao' => $request->descricao
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Estoque criado com sucesso.',
            'estoque' => $estoque,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Estoque $estoque)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estoque $estoque)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estoque $estoque)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estoque $estoque)
    {
        //
    }
}
