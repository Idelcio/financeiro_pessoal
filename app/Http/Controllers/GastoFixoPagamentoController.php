<?php

namespace App\Http\Controllers;

use App\Models\GastoFixo;
use App\Models\GastoFixoPagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoFixoPagamentoController extends Controller
{
    public function store(Request $request, GastoFixo $gastoFixo)
    {
        abort_if($gastoFixo->user_id != Auth::id(), 403);

        $data = $request->validate([
            'valor_pago'      => 'required|numeric|min:0.01',
            'data_pagamento'  => 'required|date',
            'mes_referencia'  => 'required|string|regex:/^\d{4}-\d{2}$/',
            'observacao'      => 'nullable|string',
        ]);

        // Evita duplicidade de pagamento no mesmo mes
        GastoFixoPagamento::updateOrCreate(
            [
                'gasto_fixo_id'  => $gastoFixo->id,
                'user_id'        => Auth::id(),
                'mes_referencia' => $data['mes_referencia'],
            ],
            [
                'valor_pago_centavos' => (int) round($data['valor_pago'] * 100),
                'data_pagamento'      => $data['data_pagamento'],
                'observacao'          => $data['observacao'] ?? null,
            ]
        );

        return back()->with('success', 'Pagamento registrado com sucesso!');
    }

    public function destroy(GastoFixoPagamento $pagamento)
    {
        abort_if($pagamento->user_id != Auth::id(), 403);
        $pagamento->delete();
        return back()->with('success', 'Pagamento removido!');
    }
}
