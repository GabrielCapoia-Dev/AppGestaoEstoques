<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_endereco',
        'id_estoque',
        'nome',
        'status',
    ];

    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'id_endereco');
    } 

    public function estoque()
    {
        return $this->belongsTo(Estoque::class, 'id_estoque');
    }
}
