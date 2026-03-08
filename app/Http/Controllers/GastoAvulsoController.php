<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\GastoAvulso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoAvulsoController extends Controller
{
    public function index()
    {
        $mes = request('mes', now()->format('Y-m'));
        [$ano, $mesNum] = explode('-', $mes);

        $tipo = request('tipo', 'todos');

        $gastos = GastoAvulso::where('user_id', Auth::id())
            ->whereYear('data', $ano)
            ->whereMonth('data', $mesNum)
            ->when($tipo !== 'todos', fn($q) => $q->where('tipo', $tipo))
            ->with('categoria')
            ->orderByDesc('data')
            ->get();

        $totalMes = $gastos->sum('valor_centavos');
        $categorias = Categoria::where('user_id', Auth::id())->get();

        return view('gastos-avulsos.index', compact('gastos', 'totalMes', 'mes', 'tipo', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::where('user_id', Auth::id())->get();
        return view('gastos-avulsos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'descricao'    => 'required|string|max:255',
            'valor'        => 'required|numeric|min:0.01',
            'data'         => 'required|date',
            'categoria_id' => 'nullable|exists:categorias,id',
            'tipo'         => 'required|in:pessoal,empresa',
            'observacao'   => 'nullable|string',
        ]);

        // Garantir que a categoria pertence ao usuario
        if ($data['categoria_id']) {
            $cat = Categoria::find($data['categoria_id']);
            if ($cat && $cat->user_id != Auth::id()) {
                abort(403);
            }
        }

        GastoAvulso::create([
            'user_id'        => Auth::id(),
            'categoria_id'   => $data['categoria_id'] ?? null,
            'descricao'      => $data['descricao'],
            'valor_centavos' => (int) round($data['valor'] * 100),
            'data'           => $data['data'],
            'tipo'           => $data['tipo'],
            'observacao'     => $data['observacao'] ?? null,
        ]);

        return redirect()->route('gastos-avulsos.index')->with('success', 'Gasto registrado!');
    }

    public function edit(GastoAvulso $gastosAvulso)
    {
        abort_if($gastosAvulso->user_id != Auth::id(), 403);
        $categorias = Categoria::where('user_id', Auth::id())->get();
        return view('gastos-avulsos.edit', ['gasto' => $gastosAvulso, 'categorias' => $categorias]);
    }

    public function update(Request $request, GastoAvulso $gastosAvulso)
    {
        abort_if($gastosAvulso->user_id != Auth::id(), 403);

        $data = $request->validate([
            'descricao'    => 'required|string|max:255',
            'valor'        => 'required|numeric|min:0.01',
            'data'         => 'required|date',
            'categoria_id' => 'nullable|exists:categorias,id',
            'tipo'         => 'required|in:pessoal,empresa',
            'observacao'   => 'nullable|string',
        ]);

        $gastosAvulso->update([
            'categoria_id'   => $data['categoria_id'] ?? null,
            'descricao'      => $data['descricao'],
            'valor_centavos' => (int) round($data['valor'] * 100),
            'data'           => $data['data'],
            'tipo'           => $data['tipo'],
            'observacao'     => $data['observacao'] ?? null,
        ]);

        return redirect()->route('gastos-avulsos.index')->with('success', 'Gasto atualizado!');
    }

    public function destroy(GastoAvulso $gastosAvulso)
    {
        abort_if($gastosAvulso->user_id != Auth::id(), 403);
        $gastosAvulso->delete();
        return redirect()->route('gastos-avulsos.index')->with('success', 'Gasto removido!');
    }

    public function show(string $id)
    {
        //
    }
}
