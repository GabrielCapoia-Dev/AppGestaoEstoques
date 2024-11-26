<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnderecoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

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

        if($validator->fails()){
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


        return response()->json([
            'error' => false,
            'message' => 'Endereço criado com sucesso.',
            'endereco' => $endereco
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(Endereco $endereco)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Endereco $endereco)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Endereco $endereco)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Endereco $endereco)
    {
        //
    }
}
