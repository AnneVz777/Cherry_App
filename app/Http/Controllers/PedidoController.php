<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Produto;
use App\Models\ItemPedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index()
    {
        return response()->json(
            Pedido::with(['itens.produto', 'usuario'])->get()
        );
    }

    public function show($id)
    {
        $pedido = Pedido::with(['itens.produto', 'usuario'])->findOrFail($id);

        return response()->json($pedido);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $pedido = Pedido::create([
                'usuario_id' => $request->usuario_id,
                'status' => 'pendente',

                'metodo_pagamento' => $request->metodo_pagamento,
                'status_pagamento' => 'pendente',

                'data_pedido' => now(),
                'data_entrega_prevista' => $request->data_entrega_prevista,
                'codigo_rastreio' => null
            ]);

            $total = 0;

            foreach ($request->itens as $item) {

                $produto = Produto::findOrFail($item['produto_id']);

                if ($produto->estoque < $item['quantidade']) {
                    throw new \Exception("Estoque insuficiente para o produto {$produto->nome}");
                }

                $subtotal = $produto->preco * $item['quantidade'];

                ItemPedido::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $produto->id,
                    'quantidade' => $item['quantidade'],
                    'preco' => $produto->preco
                ]);

                $produto->decrement('estoque', $item['quantidade']);

                $total += $subtotal;
            }

            DB::commit();

            return response()->json(
                $pedido->load(['itens.produto', 'usuario']),
                201
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'erro' => true,
                'mensagem' => $e->getMessage()
                ], 400);
                        }
    }
}