<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Estoque;
use App\Models\NotificacaoProduto;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdutoController extends Controller
{
    /**
     * Busca todos os produtos
     */
    public function index()
    {
        $produtos = Produto::all();
        if (!$produtos) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum produto encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Produtos encontrados.',
            'produtos' => $produtos
        ], 200);
    }

    /**
     * Cria um novo produto
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_categoria' => 'required|exists:categorias,id',
                'id_estoque' => 'required|exists:estoques,id',
                'nome_produto' => 'required|string|min:2|max:30',
                'status_produto' => 'required|string|in:Ativo,Inativo',
                'descricao_produto' => 'required|string|min:2|max:255',
                'preco' => 'required|numeric|min:0.01',
                'quantidade_atual' => 'required|numeric|min:0',
                'quantidade_minima' => 'required|numeric|min:0',
                'quantidade_maxima' => 'required|numeric|min:0',
            ],
            [
                'required' => 'O campo :attribute é obrigatório.',
                'exists' => 'O campo :attribute não existe.',
                'string' => 'O campo :attribute deve ser uma string.',
                'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
                'numeric' => 'O campo :attribute deve ser um número.',
                'in' => 'O campo :attribute deve ser um dos seguintes valores: :values.',
            ],
            [
                'id_categoria' => 'Categoria',
                'id_estoque' => 'Estoque',
                'nome_produto' => 'Nome Produto',
                'status_produto' => 'Status Produto',
                'descricao_produto' => 'Descrição Produto',
                'preco' => 'Preço',
                'quantidade_atual' => 'Quantidade Atual',
                'quantidade_minima' => 'Quantidade Mínima',
                'quantidade_maxima' => 'Quantidade Máxima',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        $produto = Produto::create($request->all());

        //Cria o historico do produto depois que o produto é criado
        HistoricoProdutoController::store($request);

        return response()->json([
            'error' => false,
            'message' => 'Produto criado com sucesso.',
            'produto' => $produto
        ], 201);
    }

    /**
     * Busca apenas o produto listado por id
     */
    public function show($id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum produto encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Produto encontrado.',
            'produto' => $produto
        ], 200);
    }

    /**
     * Atualiza as informações do produto
     */
    public function update(Request $request, $id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json([
                'error' => true,
                'message' => 'Produto não encontrado.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_categoria' => 'required|exists:categorias,id',
            'id_estoque' => 'required|exists:estoques,id',
            'nome' => 'required|string|min:2|max:30',
            'status' => 'required|string|in:Ativo,Inativo',
            'descricao' => 'required|string|min:2|max:255',
            'preco' => 'required|numeric|min:0.01',
            'quantidade_atual' => 'required|numeric|min:0',
            'quantidade_minima' => 'required|numeric|min:0',
            'quantidade_maxima' => 'required|numeric|min:0',
        ], [
            'required' => 'O campo :attribute é obrigatório.',
            'exists' => 'O campo :attribute não existe.',
            'string' => 'O campo :attribute deve ser uma string.',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
            'numeric' => 'O campo :attribute deve ser um número.',
            'in' => 'O campo :attribute deve ser um dos seguintes valores: :values.',
        ], [
            'id_categoria' => 'Categoria',
            'id_estoque' => 'Estoque',
            'nome' => 'Nome',
            'status' => 'Status',
            'descricao' => 'Descrição',
            'preco' => 'Preço',
            'quantidade_atual' => 'Quantidade Atual',
            'quantidade_minima' => 'Quantidade Mínima',
            'quantidade_maxima' => 'Quantidade Máxima',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }


        //Armazenando o historico antes de dar o update no produto
        HistoricoProdutoController::store($request);

        $produto->update($request->all());

        //Gera a notificação depois que o produto é atualizado
        NotificacaoProdutoController::store($request);

        return response()->json([
            'error' => false,
            'message' => 'Produto atualizado com sucesso.',
            'produto' => $produto
        ], 200);
    }

    /**
     * Desabilita o produto
     */
    public function desativarProduto($id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json([
                'error' => true,
                'message' => 'Produto não encontrado.'
            ], 404);
        }

        $produto->update([
            'status' => 'Inativo'
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Produto desabilitado com sucesso.',
            'produto' => $produto
        ], 200);
    }

    /**
     * Habilita o produto
     */
    public function ativarProduto($id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json([
                'error' => true,
                'message' => 'Produto não encontrado.'
            ], 404);
        }

        $produto->update([
            'status' => 'Ativo'
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Produto habilitado com sucesso.',
            'produto' => $produto
        ], 200);
    }


    /**
     * Listar todos os produtos de uma categoria
     */
    public function visualizarProdutosPorCategoria($id)
    {
        $categoria = Categoria::find($id);
        if (!$categoria) {
            return response()->json([
                'error' => true,
                'message' => 'Categoria não encontrada.'
            ], 404);
        }

        $produtos = Produto::where('id_categoria', $id)->get();
        if (!$produtos) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum produto encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Produtos encontrados da categoria: ' . $categoria->nome,
            'produtos' => $produtos
        ], 200);
    }

    /**
     * Valida se o estoque foi inativado, se sim inativar todos os produtos
     */
    public static function estoqueInativadoInativarProdutos($id)
    {
        $estoque = Estoque::find($id);
        if (!$estoque) {
            return response()->json([
                'error' => true,
                'message' => 'Estoque não encontrado.'
            ], 404);
        }

        if ($estoque->status == 'Inativo') {
            $produtos = Produto::where('id_estoque', $id)->get();
            foreach ($produtos as $produto) {
                $produto->update([
                    'status' => 'Inativo'
                ]);
            }
        }

        return response()->json([
            'error' => false,
            'message' => 'Estoque inativado com sucesso.',
            'estoque' => $estoque
        ], 200);
    }

    /**
     * Valida se o estoque foi ativado, se sim ativar todos os produtos
     */
    public static function estoqueAtivadoAtivarProdutos($id)
    {
        $estoque = Estoque::find($id);
        if (!$estoque) {
            return response()->json([
                'error' => true,
                'message' => 'Estoque não encontrado.'
            ], 404);
        }

        if ($estoque->status == 'Ativo') {
            $produtos = Produto::where('id_estoque', $id)->get();
            foreach ($produtos as $produto) {
                $produto->update([
                    'status' => 'Ativo'
                ]);
            }
        }

        return response()->json([
            'error' => false,
            'message' => 'Estoque ativado com sucesso.',
            'estoque' => $estoque
        ], 200);
    }

    /**
     * Funcao estatica para visualizar todos os produtos ativos de um estoque
     */
    public static function visualizarProdutosAtivos($id_estoque)
    {
        $estoque = Estoque::find($id_estoque);
        if (!$estoque) {
            return response()->json([
                'error' => true,
                'message' => 'Estoque não encontrado.'
            ], 404);
        }

        $produtos = Produto::where('id_estoque', $id_estoque)->where('status', 'Ativo')->get();

        if (!$produtos) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum produto encontrado.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Produtos encontrados do estoque: ' . $estoque->nome,
            'produtos' => $produtos
        ], 200);
    }
}
