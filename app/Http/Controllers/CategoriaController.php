<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    /**
     * Listar todas as categorias
     */
    public function index()
    {
        $categorias = Categoria::all();

        if (!$categorias) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhuma categoria encontrada.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Categorias encontradas.',
            'categorias' => $categorias
        ], 200);
    }

    /**
     * Criar uma caregoria especifica
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nome_categoria' => 'required|string|min:2|max:30',
                'descricao_categoria' => 'required|string|min:2|max:255',
                'status_categoria' => 'required|in:Ativo,Inativo',
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
                'nome_categoria' => 'Nome da categoria',
                'descricao_categoria' => 'Descrição da categoria',
                'status_categoria' => 'Status da categoria',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }


        $categoria = Categoria::create([$request->all()]);

        return response()->json([
            'error' => false,
            'message' => 'Categoria criada com sucesso.',
            'categoria' => $categoria
        ], 200);
    }

    /**
     * Visualizar uma categoria especifica
     */
    public function show($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'error' => true,
                'message' => 'Categoria nao encontrada.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Categoria encontrada.',
            'categoria' => $categoria
        ], 200);
    }

    /**
     * Atualiza informacoes da categoria
     */
    public function update(Request $request, $id)
    {

        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'error' => true,
                'message' => 'Categoria nao encontrada.'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'nome_categoria' => 'required|string|min:2|max:30',
                'descricao_categoria' => 'required|string|min:2|max:255',
                'status_categoria' => 'required|in:Ativo,Inativo',
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
                'nome_categoria' => 'Nome da categoria',
                'descricao_categoria' => 'Descrição da categoria',
                'status_categoria' => 'Status da categoria',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }


        $categoria->update($request->all());

        return response()->json([
            'error' => false,
            'message' => 'Categoria atualizada com sucesso.',
            'categoria' => $categoria
        ], 200);
    }


    /**
     * Desativa uma categoria especifica e os produtos que a possuem
     */
    public function desativarCategoria($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'error' => true,
                'message' => 'Categoria nao encontrada.'
            ], 404);
        }

        $categoria->status_categoria = 'Inativo';
        $categoria->save();

        $produtos = $categoria->produtos()->get();

        foreach ($produtos as $produto) {
            $produto->status_produto = 'Inativo';
            $produto->save();
        }

        return response()->json([
            'error' => false,
            'message' => 'Categoria desativada com sucesso.',
            'categoria' => $categoria
        ], 200);
    }

    /**
     * Ativa uma categoria especifica e os produtos que a possuem
     */
    public function ativarCategoria($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'error' => true,
                'message' => 'Categoria nao encontrada.'
            ], 404);
        }

        $categoria->status_categoria = 'Ativo';
        $categoria->save();

        $produtos = $categoria->produtos()->get();

        foreach ($produtos as $produto) {
            $produto->status_produto = 'Ativo';
            $produto->save();
        }

        return response()->json([
            'error' => false,
            'message' => 'Categoria ativada com sucesso.',
            'categoria' => $categoria
        ], 200);
    }

    /**
     * Listar categorias ativas
     */
    public function visualizarCategoriasAtivas()
    {
        $categorias = Categoria::where('status_categoria', 'Ativo')->get();

        return response()->json([
            'error' => false,
            'message' => 'Categorias encontradas.',
            'categorias' => $categorias
        ], 200);
    }

    /**
     * Listar categorias inativas
     */
    public function visualizarCategoriasInativas()
    {
        $categorias = Categoria::where('status_categoria', 'Inativo')->get();

        return response()->json([
            'error' => false,
            'message' => 'Categorias encontradas.',
            'categorias' => $categorias
        ], 200);
    }
}
