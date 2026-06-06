<?php

namespace App\Http\Controllers;

use App\Models\Avaliacoes;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    public function index()
    {
        $avaliacoes = Avaliacoes::all();
        return response()->json($avaliacoes);
    }

    public function show($id)
    {
        $avaliacao = Avaliacoes::find($id);
        if (!$avaliacao) {
            return response()->json(['message' => 'Avaliação não encontrada'], 404);
        }
        return response()->json($avaliacao);
    }

    public function store(Request $request)
    {
        $avaliacao = Avaliacoes::create($request->all());
        return response()->json($avaliacao, 201);
    }

    public function update(Request $request, $id)
    {
        $avaliacao = Avaliacoes::find($id);
        if (!$avaliacao) {
            return response()->json(['message' => 'Avaliação não encontrada'], 404);
        }
        $avaliacao->update($request->all());
        return response()->json($avaliacao);
    }

    public function destroy($id)
    {
        $avaliacao = Avaliacoes::find($id);
        if (!$avaliacao) {
            return response()->json(['message' => 'Avaliação não encontrada'], 404);
        }
        $avaliacao->delete();
        return response()->json(['message' => 'Avaliação deletada com sucesso']);
    }
}