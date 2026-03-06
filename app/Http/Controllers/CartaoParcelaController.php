<?php

namespace App\Http\Controllers;

use App\Models\CartaoParcela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartaoParcelaController extends Controller
{
    public function pagar(Request $request, CartaoParcela $parcela)
    {
        abort_if($parcela->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'data_pagamento' => 'required|date',
        ]);

        $parcela->update([
            'pago'           => true,
            'data_pagamento' => $data['data_pagamento'],
        ]);

        return back()->with('success', "Parcela {$parcela->numero_parcela} marcada como paga!");
    }

    public function desfazer(CartaoParcela $parcela)
    {
        abort_if($parcela->user_id !== Auth::id(), 403);

        $parcela->update([
            'pago'           => false,
            'data_pagamento' => null,
        ]);

        return back()->with('success', 'Pagamento desfeito!');
    }
}
