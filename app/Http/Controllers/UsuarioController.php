<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    /**
     * Retorna todos os usuarios
     */
    public function index()
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
     * Cria um novo usuário
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:30',
            'email' => [
                'required',
                'email',
                'unique:usuarios',
                function ($attribute, $value, $fail) {
                    // Verifica se o e-mail contém espaços em branco
                    if (strpos($value, ' ') !== false) {
                        $fail('O campo :attribute não pode conter espaços em branco.');
                    }
                    // Verifica se o e-mail contém o símbolo '@'
                    if (strpos($value, '@') === false) {
                        $fail('O campo :attribute deve conter o símbolo @.');
                    }
                    // Verifica se o e-mail contém pelo menos um ponto '.'
                    if (strpos($value, '.') === false) {
                        $fail('O campo :attribute deve conter um ponto (.)');
                    }
                }
            ],
            'senha' => [
                'required',
                'string',
                'min:7',
                function ($attribute, $value, $fail) {
                    // Verifica maiusculas
                    if (!preg_match('/[A-Z]/', $value)) {
                        $fail("A password deve conter pelo menos uma letra maiúscula.");
                    }
                    // Verifica minusculas
                    if (!preg_match('/[a-z]/', $value)) {
                        $fail("A password deve conter pelo menos uma letra minúscula.");
                    }
                    // Verifica os numericos
                    if (!preg_match('/\d/', $value)) {
                        $fail("A password deve conter pelo menos um número.");
                    }
                    // Verifica caracteres especiais
                    if (!preg_match('/[@$!%*?&]/', $value)) {
                        $fail("A password deve conter pelo menos um caractere especial.");
                    }
                }
            ],
            'confirmaSenha' => 'required|same:password',
            'permissao' => 'required|in:Administrador,subAdmin,Gestores,Secretaria,Cozinha,Serviços Gerais',
            'status' => 'required|in:Ativo,Inativo'
        ], [
            'required' => 'O campo :attribute é obrigatório.',
            'min' => 'O campo :attribute deve conter pelo menos :min caracteres.',
            'max' => 'O campo :attribute deve conter no máximo :max caracteres.',
            'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
            'unique' => 'O campo :attribute deve ser único.',
            'same' => 'O campo :attribute deve ser igual ao campo :other.',
            'in' => 'O campo :attribute deve ser um dos seguintes valores: :values.',
        ], [
            'name' => 'Nome',
            'email' => 'E-mail',
            'senha' => 'Senha',
            'confirmaSenha' => 'Confirmar Senha',
            'permissao' => 'Permissão',
            'status' => 'Status'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao cadastrar usuário.',
                'errors' => $validator->errors()
            ], 400);
        }

        $usuario = Usuario::create([
            'name' => $request->name,
            'email' => $request->email,
            'senha' => bcrypt($request->senha),
            'permissao' => $request->permissao,
            'status' => $request->status
        ]);


        return response()->json([
            'error' => false,
            'message' => 'Usuário cadastrado com sucesso.',
            'usuario' => $usuario
        ], 201);
    }

    /**
     * Retorna o usuario selecionado
     */
    public function show($id)
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
     * Edita as informações do usuário
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'error' => true,
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:30',
            'email' => [
                'required',
                'email',
                'unique:usuarios',
                function ($attribute, $value, $fail) {
                    // Verifica se o e-mail contém espaços em branco
                    if (strpos($value, ' ') !== false) {
                        $fail('O campo :attribute não pode conter espaços em branco.');
                    }
                    // Verifica se o e-mail contém o símbolo '@'
                    if (strpos($value, '@') === false) {
                        $fail('O campo :attribute deve conter o símbolo @.');
                    }
                    // Verifica se o e-mail contém pelo menos um ponto '.'
                    if (strpos($value, '.') === false) {
                        $fail('O campo :attribute deve conter um ponto (.)');
                    }
                }
            ],
            'senha' => [
                'required',
                'string',
                'min:7',
                function ($attribute, $value, $fail) {
                    // Verifica maiusculas
                    if (!preg_match('/[A-Z]/', $value)) {
                        $fail("A password deve conter pelo menos uma letra maiúscula.");
                    }
                    // Verifica minusculas
                    if (!preg_match('/[a-z]/', $value)) {
                        $fail("A password deve conter pelo menos uma letra minúscula.");
                    }
                    // Verifica os numericos
                    if (!preg_match('/\d/', $value)) {
                        $fail("A password deve conter pelo menos um número.");
                    }
                    // Verifica caracteres especiais
                    if (!preg_match('/[@$!%*?&]/', $value)) {
                        $fail("A password deve conter pelo menos um caractere especial.");
                    }
                }
            ],
            'confirmaSenha' => 'required|same:password',
            'permissao' => 'required|in:Administrador,subAdmin,Gestores,Secretaria,Cozinha,Serviços Gerais',
            'status' => 'required|in:Ativo,Inativo'
        ], [
            'required' => 'O campo :attribute é obrigatório.',
            'min' => 'O campo :attribute deve conter pelo menos :min caracteres.',
            'max' => 'O campo :attribute deve conter no máximo :max caracteres.',
            'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
            'unique' => 'O campo :attribute deve ser único.',
            'same' => 'O campo :attribute deve ser igual ao campo :other.',
            'in' => 'O campo :attribute deve ser um dos seguintes valores: :values.',
        ], [
            'name' => 'Nome',
            'email' => 'E-mail',
            'senha' => 'Senha',
            'confirmaSenha' => 'Confirmar Senha',
            'permissao' => 'Permissão',
            'status' => 'Status'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao cadastrar usuário.',
                'errors' => $validator->errors()
            ], 400);
        }


        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'senha' => bcrypt($request->senha),
            'permissao' => $request->permissao,
            'status' => $request->status
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Usuário editado com sucesso.',
            'usuario' => $usuario
        ], 200);
    }

    /**
     * Desabilita o usuário
     */
    public function desable($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json([
                'error' => true,
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        $usuario->update([
            'status' => 'Inativo'
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Usuário desabilitado com sucesso.',
            'usuario' => $usuario
        ], 200);
    }

    /**
     * Habilita o usuário
     */
    public function enable($id)
    {
        $usuario = Usuario::find($id);  
        if (!$usuario) {
            return response()->json([
                'error' => true,
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        $usuario->update([
            'status' => 'Ativo'
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Usuário habilitado com sucesso.',
            'usuario' => $usuario
        ], 200);
    }

    /** 
     * Visualiza usuarios listados por permissao
     */
    public function showAllByPermission($permissao)
    {
        $usuarios = Usuario::where('permissao', $permissao)->get();

        if (!$usuarios) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum usuário encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Usuários encontrados.',
            'usuarios' => $usuarios
        ]);
    }
}
