<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EntregaController extends Controller
{
    public function index() {
        foreach (Entrega::all() as $entrega) {
            echo $entrega->status;
            echo "<br>";
        }
        dd();
        $entregas = Entrega::all();
        return response()->json($entregas);
    }

    public function show($id) {
        $entrega = Entrega::find($id);
        if (!$entrega) {
            return response()->json(['message' => 'Entrega não encontrada'], 404);
        }
        return response()->json($entrega);
    }

    public function store(Request $request) {
        $entrega = Entrega::create($request->all());
        return response()->json($entrega, 201);
    }

    public function update(Request $request, $id) {
        $entrega = Entrega::find($id);
        if (!$entrega) {
            return response()->json(['message' => 'Entrega não encontrada'], 404);
        }
        $entrega->update($request->all());
        return response()->json($entrega);
    }

    public function destroy($id) {
        $entrega = Entrega::find($id);
        if (!$entrega) {
            return response()->json(['message' => 'Entrega não encontrada'], 404);
        }
        $entrega->delete();
        return response()->json(['message' => 'Entrega deletada com sucesso']);
    }
}
