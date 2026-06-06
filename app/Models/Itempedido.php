<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    protected $table = 'item_pedidos';

    protected $fillable = [
        'pedido_id',
        'produto_id',
        'quantidade',
        'preco',
    ];
    protected $primaryKey = 'id';


    public function produto()
{
    return $this->belongsTo(Produto::class);
}

public function pedido()
{
    return $this->belongsTo(Pedido::class);
}
}