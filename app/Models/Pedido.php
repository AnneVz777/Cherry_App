<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'metodo_pagamento',
        'data_pedido',
        'data_entrega_prevista',
        'codigo_rastreio'
    ];

    protected $table = 'pedidos';
    protected $primaryKey = 'id';

    public function produtos()
{
    return $this->belongsToMany(Produto::class)
                ->withPivot('quantidade', 'preco')
                ->withTimestamps();
}

public function usuario()
{
    return $this->belongsTo(User::class, 'user_id');
    return $this->belongsTo(User::class);
}

public function itens()
{
    return $this->hasMany(ItemPedido::class);
}

}

