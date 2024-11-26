<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnderecoController extends Controller
{

    /**
     * O endereço deve ser static pois quando o local é criado o endereço também deve ser criado junto
     */
    public static function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'cep' => 'required|size:8|string',
                'logradouro' => 'required|string|max:50',
                'bairro' => 'required|string|max:50',
                'cidade' => 'required|string|max:50',
                'estado' => 'required|string|max:16',
                'complemento' => 'required|string|max:150',
                'numero' => 'required|numeric|max:10',
            ],
            [
                'required' => 'O campo :attribute é obrigatório.',
                'size' => 'O campo :attribute deve ter :size caracteres.',
                'string' => 'O campo :attribute deve ser uma string.',
                'numeric' => 'O campo :attribute deve ser um número.',
            ],
            [
                'cep' => 'CEP',
                'logradouro' => 'Logradouro',
                'bairro' => 'Bairro',
                'cidade' => 'Cidade',
                'estado' => 'Estado',
                'complemento' => 'Complemento',
                'numero' => 'Número',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        $endereco = Endereco::create([
            'cep' => $request->cep,
            'logradouro' => $request->logradouro,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'complemento' => $request->complemento,
            'numero' => $request->numero
        ], 200);


        return  $endereco;
    }

    /**
     * Retorna o endereço de um local especifico
     */
    public static function show($id_local)
    {
        $local = Local::find($id_local);

        if (!$local) {
            return response()->json([
                'error' => true,
                'message' => 'Local não encontrado.'
            ], 404);
        }

        $enderecos = $local->enderecos()->get();

        return  $enderecos;
    }

    /**
     * Atualiza o endereço de um local especifico
     */
    public static function update(Request $request, $id_endereco)
    {
        $endereco = Endereco::find($id_endereco);

        if (!$endereco) {
            return response()->json([
                'error' => true,
                'message' => 'Local não encontrado.'
            ], 404);
        }


        $validator = Validator::make(
            $request->all(),
            [
                'cep' => 'required|size:8|string',
                'logradouro' => 'required|string|max:50',
                'bairro' => 'required|string|max:50',
                'cidade' => 'required|string|max:50',
                'estado' => 'required|string|max:16',
                'complemento' => 'required|string|max:150',
                'numero' => 'required|numeric|max:10',
            ],
            [
                'required' => 'O campo :attribute é obrigatório.',
                'size' => 'O campo :attribute deve ter :size caracteres.',
                'string' => 'O campo :attribute deve ser uma string.',
                'numeric' => 'O campo :attribute deve ser um número.',
            ],
            [
                'cep' => 'CEP',
                'logradouro' => 'Logradouro',
                'bairro' => 'Bairro',
                'cidade' => 'Cidade',
                'estado' => 'Estado',
                'complemento' => 'Complemento',
                'numero' => 'Número',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        $endereco->update([
            'cep' => $request->cep,
            'logradouro' => $request->logradouro,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'complemento' => $request->complemento,
            'numero' => $request->numero
        ], 200);


        return  $endereco;
    }
}
