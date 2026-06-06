<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{

    protected $fillable = ['id', 'nome', 'descricao', 'preco', 'estoque'];
    protected $table = 'produtos';
    protected $primaryKey = 'id';

    public function pedidos()
{
    return $this->belongsToMany(Pedido::class)
                ->withPivot('quantidade', 'preco')
                ->withTimestamps();
}

public function itensPedidos()
{
    return $this->hasMany(ItemPedido::class);
}

public function avaliacoes()
{
    return $this->hasMany(Avaliacao::class);
}
}
