<?php

namespace App\Http\Controllers;

use App\Models\ImpostoParcela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpostoParcelaController extends Controller
{
    public function pagar(Request $request, ImpostoParcela $parcela)
    {
        abort_if($parcela->user_id != Auth::id(), 403);

        $data = $request->validate([
            'data_pagamento' => 'nullable|date',
        ]);

        $parcela->update([
            'pago'           => true,
            'data_pagamento' => $data['data_pagamento'] ?? now()->toDateString(),
        ]);

        return back()->with('success', "Parcela {$parcela->numero_parcela} paga com sucesso!");
    }

    public function desfazer(ImpostoParcela $parcela)
    {
        abort_if($parcela->user_id != Auth::id(), 403);

        $parcela->update([
            'pago'           => false,
            'data_pagamento' => null,
        ]);

        return back()->with('success', 'Pagamento desfeito!');
    }

    public function destroy(ImpostoParcela $parcela)
    {
        abort_if($parcela->user_id != Auth::id(), 403);
        $parcela->delete();
        return back()->with('success', 'Parcela removida!');
    }
}
