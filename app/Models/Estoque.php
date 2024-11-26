<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_estoque',
        'status_estoque',
        'descricao_estoque',
    ];

}
