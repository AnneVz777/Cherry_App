<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'telefone',
    ];

    protected $hidden = [
        'senha',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
