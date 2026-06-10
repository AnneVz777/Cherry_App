<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CadastroConfirmado;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function index() {
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }

    public function show($id) {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        return response()->json($usuario);
    }

    public function store(Request $request) {
        $usuario = Usuario::create($request->all());

        Mail::to($usuario->email)->send(new CadastroConfirmado($usuario->nome));

        return response()->json($usuario, 201);
}

    public function update(Request $request, $id) {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        $usuario->update($request->all());
        return response()->json($usuario);
    }

    public function destroy($id) {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        $usuario->delete();
        return response()->json(['message' => 'Usuário deletado com sucesso']);
    }
}
