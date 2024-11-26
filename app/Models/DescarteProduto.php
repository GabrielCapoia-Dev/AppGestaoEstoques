<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescarteProduto extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_produto',
        'vencimento_descarte',
        'data_vencimento_descarte',
        'defeito_descarte',
        'descricao_descarte',
        'quantidade_descarte',
    ];


    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}