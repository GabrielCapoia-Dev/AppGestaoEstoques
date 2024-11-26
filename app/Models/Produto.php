<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_categoria',
        'id_estoque',
        'nome_produto',
        'status_produto',
        'descricao_produto',
        'preco',
        'quantidade_atual',
        'quantidade_minima',
        'quantidade_maxima',
        'validade'
    ];


    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }


    public function estoque()
    {
        return $this->belongsTo(Estoque::class, 'id_estoque');
    }

}