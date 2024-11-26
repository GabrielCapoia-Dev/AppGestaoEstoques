<?php

namespace App\Http\Controllers;

use App\Models\Local;
use Illuminate\Http\Request;

class LocalController extends Controller
{
    /**
     * Visualizar todos os locais
     */
    public function index()
    {
        $local = Local::all();
    
        if(!$local) {
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
     * Cria um novo local
     */
    public function store(Request $request)
    {
        
    }


    /**
     * Display the specified resource.
     */
    public function show(Local $local)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Local $local)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Local $local)
    {
        //
    }
}
