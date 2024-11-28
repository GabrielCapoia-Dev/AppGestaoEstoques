<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsuarioController extends Controller
{

        /**
     * Verifica se o email passado na request existe no banco
     * apos verificado compara a password do banco com a password da
     * request, se todos os valores estiverem certos gera o token
     */
    public function login(Request $request)
    {

        // Validação dos dados recebidos
        $request->validate([
            'email_usuario' => 'required|email',
            'senha' => 'required',
        ]);

        // Busca o usuário pelo e-mail
        $usuario = Usuario::where('email_usuario', $request->email_usuario)->first();

        // Verifica se o usuário existe e se a senha está correta
        if (!$usuario || !Hash::check($request->senha, $usuario->senha)) {
            return response()->json([
                'error' => true,
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        // Gera o token JWT
        $token = JWTAuth::fromUser($usuario);

        // Retorna o token e os dados do usuário
        return response()->json([
            'error' => false,
            'message' => 'Login realizado com sucesso',
            'accessToken' => $token,
            'usuario' => $usuario
        ], 200);
    }

    /**
     * Retorna todos os usuarios
     */
    public function listarSemAdmin()
    {
        $usuarios = Usuario::where('permissao', '!=', 'Administrador')->get();
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
     * Acesso especifico para o Administradores e SubAdministradores
     */
    public static function store(Request $request, $sobrescreverPermissao = null)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nome_usuario' => 'required|string|min:5|max:30',
                'email_usuario' => [
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
                'confirmaSenha' => 'required|same:senha',
                'permissao' => 'required|in:Administrador,subAdmin,Gestão,Secretaria,Cozinha,Serviços Gerais',
            ],
            [
                'required' => 'O campo :attribute é obrigatório.',
                'min' => 'O campo :attribute deve conter pelo menos :min caracteres.',
                'max' => 'O campo :attribute deve conter no máximo :max caracteres.',
                'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
                'unique' => 'O campo :attribute deve ser único.',
                'same' => 'O campo :attribute deve ser igual ao campo :other.',
                'in' => 'O campo :attribute deve ser um dos seguintes valores: :values.',
            ],
            [
                'nome_usuario' => 'Nome do Usuário',
                'email_usuario' => 'E-mail do Usuário',
                'senha' => 'Senha',
                'confirmaSenha' => 'Confirmar Senha',
                'permissao' => 'Permissão',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao cadastrar usuário.',
                'errors' => $validator->errors()
            ], 400);
        }

        $usuario = Usuario::create([
            'nome_usuario' => $request->nome_usuario,
            'email_usuario' => $request->email_usuario,
            'senha' => bcrypt($request->senha),
            'permissao' => $sobrescreverPermissao ?: $request->permissao,
            'status_usuario' => 'Ativo'
        ]);


        return response()->json([
            'error' => false,
            'message' => 'Usuário cadastrado com sucesso.',
            'usuario' => $usuario
        ], 201);
    }

    /**
     * Retorna o usuario selecionado é possivel ver todos os usuarios exceto Admin
     */
    public function show($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario || $usuario->permissao == 'Administrador') {
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
     * Edita as informações do usuário padrão, o admin e subAdmin tem acesso
     */
    public static function update(Request $request, $id, $sobrescreverPermissao = null)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'error' => true,
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'nome_usuario' => 'required|string|min:5|max:30',
                'email_usuario' => [
                    'required',
                    'email',
                    'unique:usuarios,email_usuario,' . $id, // Exclui o email do próprio usuário no "unique"
                    function ($attribute, $value, $fail) {
                        if (strpos($value, ' ') !== false) {
                            $fail('O campo :attribute não pode conter espaços em branco.');
                        }
                        if (strpos($value, '@') === false) {
                            $fail('O campo :attribute deve conter o símbolo @.');
                        }
                        if (strpos($value, '.') === false) {
                            $fail('O campo :attribute deve conter um ponto (.)');
                        }
                    }
                ],
                'senha' => [
                    'nullable', // Não obrigatório ao atualizar
                    'string',
                    'min:7',
                    function ($attribute, $value, $fail) {
                        if ($value && !preg_match('/[A-Z]/', $value)) {
                            $fail("A senha deve conter pelo menos uma letra maiúscula.");
                        }
                        if ($value && !preg_match('/[a-z]/', $value)) {
                            $fail("A senha deve conter pelo menos uma letra minúscula.");
                        }
                        if ($value && !preg_match('/\d/', $value)) {
                            $fail("A senha deve conter pelo menos um número.");
                        }
                        if ($value && !preg_match('/[@$!%*?&]/', $value)) {
                            $fail("A senha deve conter pelo menos um caractere especial.");
                        }
                    }
                ],
                'confirmaSenha' => 'nullable|same:senha',
                'permissao' => 'nullable|in:Administrador,subAdmin,Gestão,Secretaria,Cozinha,Serviços Gerais',
                'status_usuario' => 'required|in:Ativo,Inativo'
            ],
            [
                'required' => 'O campo :attribute é obrigatório.',
                'min' => 'O campo :attribute deve conter pelo menos :min caracteres.',
                'max' => 'O campo :attribute deve conter no máximo :max caracteres.',
                'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
                'unique' => 'O campo :attribute deve ser único.',
                'same' => 'O campo :attribute deve ser igual ao campo :other.',
                'in' => 'O campo :attribute deve ser um dos seguintes valores: :values.',
            ],
            [
                'nome_usuario' => 'Nome de Usuário',
                'email_usuario' => 'E-mail de Usuário',
                'senha' => 'Senha',
                'confirmaSenha' => 'Confirmar Senha',
                'permissao' => 'Permissão',
                'status_usuario' => 'Status de Usuário'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao atualizar usuário.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Atualiza os dados do usuário
        $usuario->update([
            'nome_usuario' => $request->nome_usuario,
            'email_usuario' => $request->email_usuario,
            'senha' => $request->senha ? bcrypt($request->senha) : $usuario->senha,
            'permissao' => $sobrescreverPermissao ?: $request->permissao,
            'status_usuario' => $request->status_usuario
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Usuário editado com sucesso.',
            'usuario' => $usuario
        ], 200);
    }

    /**
     * Desabilita o usuário
     */
    public function desativarUsuario($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario || $usuario->permissao == 'Administrador') {
            return response()->json([
                'error' => true,
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        $usuario->update([
            'status_usuario' => 'Inativo'
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
    public function ativarUsuario($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario || $usuario->permissao == 'Administrador') {
            return response()->json([
                'error' => true,
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        $usuario->update([
            'status_usuario' => 'Ativo'
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Usuário habilitado com sucesso.',
            'usuario' => $usuario
        ], 200);
    }

    /** 
     * Lista todos os usuarios ativos por permissao
     */
    public function visualizarUsuariosPorPermissao($permissao)
    {
        if ($permissao == 'Administrador') {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum usuário encontrado.'
            ], 404);
        }

        $usuarios = Usuario::where('status_usuario', 'Ativo')->where('permissao', $permissao)->get();

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

    /** 
     * Visualizar todos os usuarios ativos
     */
    public function visualizarUsuariosAtivos()
    {


        $usuarios = Usuario::where('status_usuario', 'Ativo')->where('permissao', '!=', 'Administrador')->get();

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

    /**
     * Visualizar todos os usuarios inativos
     */
    public function visualizarUsuariosInativos()
    {
        $usuarios = Usuario::where('status_usuario', 'Inativo')->wherwe('permissao', '!=', 'Administrador')->get();

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
