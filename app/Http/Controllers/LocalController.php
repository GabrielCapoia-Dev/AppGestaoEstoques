<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocalController extends Controller
{
    /**
     * Visualizar todos os locais
     */
    public function index()
    {
        $local = Local::all();

        if (!$local) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum local encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Locais encontrados.',
            'local' => $local
        ], 200);
    }

    /**
     * Cria um novo local chamando os metodos static de endereco e estoque
     */
    public function store(Request $request)
    {
        $endereco = EnderecoController::store($request);
        $estoque = EstoqueController::store($request);

        $validator = Validator::make(
            $request->all(),
            [
                'nome_local' => 'required|string|min:2|max:30',
                'status_local' => 'required|string|in:Ativo,Inativo',
            ],
            [
                'required' => 'O campo :attribute é obrigatório.',
                'exists' => 'O campo :attribute não existe.',
                'string' => 'O campo :attribute deve ser uma string.',
                'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
                'in' => 'O campo :attribute deve ser um dos seguintes valores: :values.',
            ],
            [
                'nome_local' => 'Nome do local',
                'status_local' => 'Status do local',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        $local = Local::create([
            'nome_local' => $request->nome_local,
            'status_local' => $request->status_local,
            'id_endereco' => $endereco->id,
            'id_estoque' => $estoque->id,
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Local criado com sucesso.',
            'local' => $local,
            'estoque' => $estoque,
            'endereco' => $endereco
        ], 201);
    }

    /**
     * Retorna um local especifico
     */
    public function show($id)
    {
        $local = Local::find($id);

        if (!$local) {
            return response()->json([
                'error' => true,
                'message' => 'Local não encontrado.'
            ], 404);
        }

        $endereco = EnderecoController::show($local->id_endereco);

        return response()->json([
            'error' => false,
            'message' => 'Local encontrado.',
            'local' => $local,
            'endereco' => $endereco
        ], 200);
    }

    /**
     * Atualiza o local
     */
    public function update(Request $request, Local $local)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nome_local' => 'required|string|min:2|max:30',
                'status_local' => 'required|string|in:Ativo,Inativo',
            ],
            [
                'required' => 'O campo :attribute é obrigatório.',
                'exists' => 'O campo :attribute não existe.',
                'string' => 'O campo :attribute deve ser uma string.',
                'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
                'in' => 'O campo :attribute deve ser um dos seguintes valores: :values.',
            ],
            [
                'nome_local' => 'Nome do local',
                'status_local' => 'Status do local',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }
        $endereco = EnderecoController::update($request, $local->id_endereco);
        $local->update($request->all());

        return response()->json([
            'error' => false,
            'message' => 'Local atualizado com sucesso.',
            'local' => $local,
            'endereco' => $endereco
        ], 200);
    }

    /**
     * Desativa o local
     */
    public function desativarLocal($id)
    {
        $local = Local::find($id);

        if (!$local) {
            return response()->json([
                'error' => true,
                'message' => 'Local não encontrado.'
            ], 404);
        }

        $local->status_local = 'Inativo';
        $local->save();
        return response()->json([
            'error' => false,
            'message' => 'Local desativado com sucesso.',
            'local' => $local
        ], 200);
    }


    /**
     * Ativa o local
     */
    public function ativarLocal($id)
    {
        $local = Local::find($id);

        if (!$local) {
            return response()->json([
                'error' => true,
                'message' => 'Local não encontrado.'
            ], 404);
        }

        $local->status_local = 'Ativo';
        $local->save();
        return response()->json([
            'error' => false,
            'message' => 'Local ativado com sucesso.',
            'local' => $local
        ], 200);
    }


    /**
     * Retorna os estoques ativos do local
     */
    public function visualizarEstoquesAtivosDoLocal($id)
    {
        $local = Local::find($id);

        if (!$local) {
            return response()->json([
                'error' => true,
                'message' => 'Local não encontrado.'
            ], 404);
        }

        $estoques = $local->estoques()->where('status', 'Ativo')->get();

        return response()->json([
            'error' => false,
            'message' => 'Estoques ativos do local.',
            'estoques' => $estoques
        ], 200);
    }

    /**
     * Visualizar estoques inativos do local
     */
    public function visualizarEstoquesInativosDoLocal($id)
    {
        $local = Local::find($id);

        if (!$local) {
            return response()->json([
                'error' => true,
                'message' => 'Local não encontrado.'
            ], 404);
        }

        $estoques = $local->estoques()->where('status', 'Inativo')->get();

        return response()->json([
            'error' => false,
            'message' => 'Estoques inativos do local.',
            'estoques' => $estoques
        ], 200);
    }
}
