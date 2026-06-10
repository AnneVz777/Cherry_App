<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    protected $fillable = ['id', 'produto_id', 'usuario_id', 'avaliacao', 'comentario'];
    protected $table = 'avaliacoes';
    protected $primaryKey = 'id';

    public function produto()
{
    return $this->belongsTo(Produto::class);
}

public function usuario()
{
    return $this->belongsTo(Usuario::class);
}
}
