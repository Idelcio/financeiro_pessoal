<?php

namespace App\Http\Controllers;

use App\Models\GastoFixo;
use App\Models\GastoFixoPagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoFixoController extends Controller
{
    public function index()
    {
        $mes = request('mes', now()->format('Y-m'));
        $gastos = GastoFixo::where('user_id', Auth::id())
            ->where('ativo', true)
            ->orderBy('dia_vencimento')
            ->get();

        $pagamentosDoMes = GastoFixoPagamento::where('user_id', Auth::id())
            ->where('mes_referencia', $mes)
            ->get()
            ->keyBy('gasto_fixo_id');

        return view('gastos-fixos.index', compact('gastos', 'pagamentosDoMes', 'mes'));
    }

    public function create()
    {
        return view('gastos-fixos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'           => 'required|string|max:255',
            'tipo_gasto'     => 'required|string',
            'valor'          => 'required|numeric|min:0.01',
            'dia_vencimento' => 'required|integer|min:1|max:31',
            'tipo'           => 'required|in:pessoal,empresa',
            'observacao'     => 'nullable|string',
        ]);

        GastoFixo::create([
            'user_id'        => Auth::id(),
            'nome'           => $data['nome'],
            'tipo_gasto'     => $data['tipo_gasto'],
            'valor_centavos' => (int) round($data['valor'] * 100),
            'dia_vencimento' => $data['dia_vencimento'],
            'tipo'           => $data['tipo'],
            'observacao'     => $data['observacao'] ?? null,
        ]);

        return redirect()->route('gastos-fixos.index')->with('success', 'Gasto fixo cadastrado com sucesso!');
    }

    public function edit(GastoFixo $gastosFixo)
    {
        abort_if($gastosFixo->user_id != Auth::id(), 403);
        return view('gastos-fixos.edit', ['gasto' => $gastosFixo]);
    }

    public function update(Request $request, GastoFixo $gastosFixo)
    {
        abort_if($gastosFixo->user_id != Auth::id(), 403);

        $data = $request->validate([
            'nome'           => 'required|string|max:255',
            'tipo_gasto'     => 'required|string',
            'valor'          => 'required|numeric|min:0.01',
            'dia_vencimento' => 'required|integer|min:1|max:31',
            'tipo'           => 'required|in:pessoal,empresa',
            'ativo'          => 'boolean',
            'observacao'     => 'nullable|string',
        ]);

        $gastosFixo->update([
            'nome'           => $data['nome'],
            'tipo_gasto'     => $data['tipo_gasto'],
            'valor_centavos' => (int) round($data['valor'] * 100),
            'dia_vencimento' => $data['dia_vencimento'],
            'tipo'           => $data['tipo'],
            'ativo'          => $request->boolean('ativo'),
            'observacao'     => $data['observacao'] ?? null,
        ]);

        return redirect()->route('gastos-fixos.index')->with('success', 'Gasto fixo atualizado!');
    }

    public function destroy(GastoFixo $gastosFixo)
    {
        abort_if($gastosFixo->user_id != Auth::id(), 403);
        $gastosFixo->delete();
        return redirect()->route('gastos-fixos.index')->with('success', 'Gasto fixo removido!');
    }

    public function show(GastoFixo $gastosFixo)
    {
        abort_if($gastosFixo->user_id != Auth::id(), 403);
        $pagamentos = $gastosFixo->pagamentos()->orderByDesc('data_pagamento')->get();
        return view('gastos-fixos.show', ['gasto' => $gastosFixo, 'pagamentos' => $pagamentos]);
    }
}
