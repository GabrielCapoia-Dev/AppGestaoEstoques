<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdministradorController extends Controller
{
    /**
     * Retorna todos os usuarios
     */
    public function listarComAdmin()
    {
        $usuarios = Usuario::all();
        if (!$usuarios) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum usuário encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Usuários encontrados.',
            'usuario' => $usuarios
        ], 200);
    }

    /**
     * Cria um novo Administrador
     * Acesso especifico para o Administradores
     */
    public function adminCriar(Request $request) 
    {
        $request->merge(['permissao' => 'Administrador']);

        return UsuarioController::store($request, 'Administrador');
    }

    /**
     * Retorna o usuario selecionado é possivel ver todos os usuarios exceto Admin
     */
    public function showAdmin($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json([
                'error' => true,
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Usuário encontrado.',
            'usuario' => $usuario
        ], 200);
    }

    /**
     * Edita as informações do Admin, apenas o admin tem acesso
     * chama o metodo statico de usuarioController::update e passa a permissão como Administrador
     */
    public function editarAdmin(Request $request, $id)
    {
        $request->merge(['permissao' => 'Administrador']);


        return UsuarioController::update($request, $id, 'Administrador');
    }

    /**
     * Desabilita o Admin
     */
    public function desativarAdmin($id)
    {
        $administradores = Usuario::find($id);
        if (!$administradores) {
            return response()->json([
                'error' => true,
                'message' => 'Admin não encontrado.'
            ], 404);
        }

        $administradores->update([
            'status_usuario' => 'Inativo'
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Admin desabilitado com sucesso.',
            'administradores' => $administradores
        ], 200);
    }

    /**
     * Habilita o Admin
     */
    public function ativarAdmin($id)
    {
        $administradores = Usuario::find($id);
        if (!$administradores) {
            return response()->json([
                'error' => true,
                'message' => 'Admin não encontrado.'
            ], 404);
        }

        $administradores->update([
            'status_usuario' => 'Ativo'
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Admin habilitado com sucesso.',
            'administradores' => $administradores
        ], 200);
    }

    /** 
     * Visualizar todos os administradores ativos
     */
    public function visualizarAdministradoresAtivos()
    {
        $administradores = Usuario::where('status_usuario', 'Ativo')->where('permissao', 'Administrador')->get();

        if (!$administradores) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum Admin encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Admin encontrados.',
            'admini$administradores' => $administradores
        ]);
    }

    /**
     * Visualizar todos os administradores inativos
     */
    public function visualizarAdministradoresInativos()
    {
        $administradores = Usuario::where('status_usuario', 'Inativo')->whare('permissao', 'Administrador')->get();

        if (!$administradores) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum Admin encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Admin encontrados.',
            'administradores' => $administradores
        ]);
    }
}
