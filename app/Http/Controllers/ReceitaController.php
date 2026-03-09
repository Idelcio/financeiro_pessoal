<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceitaController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->get('mes', now()->format('Y-m'));

        // Entradas recorrentes ativas desde esse mês ou antes
        $recorrentes = Receita::where('user_id', Auth::id())
            ->where('recorrente', true)
            ->whereRaw("DATE_FORMAT(data, '%Y-%m') <= ?", [$mes])
            ->orderBy('valor_centavos', 'desc')
            ->get();

        // Entradas avulsas deste mês
        $avulsas = Receita::where('user_id', Auth::id())
            ->where('recorrente', false)
            ->whereRaw("DATE_FORMAT(data, '%Y-%m') = ?", [$mes])
            ->orderBy('data', 'desc')
            ->get();

        $entradas = $recorrentes->merge($avulsas);
        $totalMes = $entradas->sum('valor_centavos');

        return view('receitas.index', compact('entradas', 'recorrentes', 'avulsas', 'mes', 'totalMes'));
    }

    public function create()
    {
        return view('receitas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'descricao'  => 'required|string|max:255',
            'valor'      => 'required|numeric|min:0.01',
            'data'       => 'required|date',
            'recorrente' => 'boolean',
            'tipo'       => 'required|in:pessoal,empresa',
            'observacao' => 'nullable|string',
        ]);

        Receita::create([
            'user_id'        => Auth::id(),
            'descricao'      => $data['descricao'],
            'valor_centavos' => (int) round($data['valor'] * 100),
            'data'           => $data['data'],
            'recorrente'     => $request->boolean('recorrente'),
            'tipo'           => $data['tipo'],
            'observacao'     => $data['observacao'] ?? null,
        ]);

        return redirect()->route('receitas.index')->with('success', 'Entrada registrada!');
    }

    public function edit(Receita $receita)
    {
        abort_if($receita->user_id != Auth::id(), 403);
        return view('receitas.edit', compact('receita'));
    }

    public function update(Request $request, Receita $receita)
    {
        abort_if($receita->user_id != Auth::id(), 403);

        $data = $request->validate([
            'descricao'  => 'required|string|max:255',
            'valor'      => 'required|numeric|min:0.01',
            'data'       => 'required|date',
            'recorrente' => 'boolean',
            'tipo'       => 'required|in:pessoal,empresa',
            'observacao' => 'nullable|string',
        ]);

        $receita->update([
            'descricao'      => $data['descricao'],
            'valor_centavos' => (int) round($data['valor'] * 100),
            'data'           => $data['data'],
            'recorrente'     => $request->boolean('recorrente'),
            'tipo'           => $data['tipo'],
            'observacao'     => $data['observacao'] ?? null,
        ]);

        return redirect()->route('receitas.index')->with('success', 'Entrada atualizada!');
    }

    public function destroy(Receita $receita)
    {
        abort_if($receita->user_id != Auth::id(), 403);
        $receita->delete();
        return back()->with('success', 'Entrada removida!');
    }
}
