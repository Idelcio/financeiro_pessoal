<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::where('user_id', Auth::id())->withCount('gastosAvulsos')->get();
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'  => 'required|string|max:255',
            'icone' => 'nullable|string|max:50',
            'cor'   => 'required|string|max:20',
        ]);

        Categoria::create([
            'user_id' => Auth::id(),
            'nome'    => $data['nome'],
            'icone'   => $data['icone'] ?? 'tag',
            'cor'     => $data['cor'],
        ]);

        return back()->with('success', 'Categoria criada!');
    }

    public function update(Request $request, Categoria $categoria)
    {
        abort_if($categoria->user_id != Auth::id(), 403);

        $data = $request->validate([
            'nome'  => 'required|string|max:255',
            'icone' => 'nullable|string|max:50',
            'cor'   => 'required|string|max:20',
        ]);

        $categoria->update($data);

        return back()->with('success', 'Categoria atualizada!');
    }

    public function destroy(Categoria $categoria)
    {
        abort_if($categoria->user_id != Auth::id(), 403);
        $categoria->delete();
        return back()->with('success', 'Categoria removida!');
    }

    public function create()
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }
}
