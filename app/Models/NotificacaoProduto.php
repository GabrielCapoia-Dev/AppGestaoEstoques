<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacaoProduto extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_produto',
        'visualizado',
        'mensagem',
    ];


    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }
}
