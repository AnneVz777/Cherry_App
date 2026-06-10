<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Avaliacoes;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    public function index()
    {
        $avaliacoes = Avaliacao::all();
        return response()->json($avaliacoes);
    }

    public function show($id)
    {
        $avaliacoes = Avaliacao::find($id);
        if (!$avaliacoes) {
            return response()->json(['message' => 'Avaliação não encontrada'], 404);
        }
        return response()->json($avaliacoes);
    }

    public function store(Request $request)
    {
        $avaliacao = Avaliacao::create($request->all());
        return response()->json($avaliacao, 201);
    }

    public function update(Request $request, $id)
    {
        $avaliacao = Avaliacao::find($id);
        if (!$avaliacao) {
            return response()->json(['message' => 'Avaliação não encontrada'], 404);
        }
        $avaliacao->update($request->all());
        return response()->json($avaliacao);
    }

    public function destroy($id)
    {
        $avaliacao = Avaliacao ::find($id);
        if (!$avaliacao) {
            return response()->json(['message' => 'Avaliação não encontrada'], 404);
        }
        $avaliacao->delete();
        return response()->json(['message' => 'Avaliação deletada com sucesso']);
    }
}