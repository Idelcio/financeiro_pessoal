<?php

namespace App\Http\Controllers;

use App\Models\Cartao;
use App\Models\CartaoGasto;
use App\Models\CartaoParcela;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartaoGastoController extends Controller
{
    public function create(Cartao $cartao)
    {
        abort_if($cartao->user_id != Auth::id(), 403);
        return view('cartoes.gastos.create', compact('cartao'));
    }

    public function store(Request $request, Cartao $cartao)
    {
        abort_if($cartao->user_id != Auth::id(), 403);

        $recorrente = $request->boolean('recorrente');

        $rules = [
            'descricao'   => 'required|string|max:255',
            'valor_total' => 'required|numeric|min:0.01',
            'data_compra' => 'required|date',
            'categoria'   => 'nullable|string|max:100',
            'tipo'        => 'required|in:pessoal,empresa',
            'observacao'  => 'nullable|string',
        ];
        if (!$recorrente) {
            $rules['total_parcelas'] = 'required|integer|min:1|max:120';
        }

        $data = $request->validate($rules);

        $gasto = CartaoGasto::create([
            'cartao_id'            => $cartao->id,
            'user_id'              => Auth::id(),
            'descricao'            => $data['descricao'],
            'valor_total_centavos' => (int) round($data['valor_total'] * 100),
            'total_parcelas'       => $recorrente ? 1 : $data['total_parcelas'],
            'data_compra'          => $data['data_compra'],
            'categoria'            => $data['categoria'] ?? null,
            'tipo'                 => $data['tipo'],
            'observacao'           => $data['observacao'] ?? null,
            'recorrente'           => $recorrente,
            'recorrente_ativa'     => true,
        ]);

        if ($recorrente) {
            CartaoParcela::create([
                'cartao_gasto_id' => $gasto->id,
                'user_id'         => Auth::id(),
                'numero_parcela'  => 1,
                'valor_centavos'  => $gasto->valor_total_centavos,
                'mes_referencia'  => Carbon::parse($data['data_compra'])->format('Y-m'),
            ]);
            $msg = 'Compra recorrente registrada!';
        } else {
            $valorParcela = (int) round(($data['valor_total'] * 100) / $data['total_parcelas']);
            $dataBase = Carbon::parse($data['data_compra']);
            for ($i = 1; $i <= $data['total_parcelas']; $i++) {
                CartaoParcela::create([
                    'cartao_gasto_id' => $gasto->id,
                    'user_id'         => Auth::id(),
                    'numero_parcela'  => $i,
                    'valor_centavos'  => $valorParcela,
                    'mes_referencia'  => $dataBase->copy()->addMonths($i - 1)->format('Y-m'),
                ]);
            }
            $msg = "Compra registrada em {$data['total_parcelas']}x!";
        }

        return redirect()->route('cartoes.show', $cartao)->with('success', $msg);
    }

    public function cancelarRecorrente(CartaoGasto $cartaoGasto)
    {
        abort_if($cartaoGasto->user_id != Auth::id(), 403);
        $cartaoGasto->update(['recorrente_ativa' => false]);
        return back()->with('success', 'Recorrência cancelada.');
    }

    public function destroy(CartaoGasto $cartaoGasto)
    {
        abort_if($cartaoGasto->user_id != Auth::id(), 403);
        $cartaoId = $cartaoGasto->cartao_id;
        $cartaoGasto->delete();
        return redirect()->route('cartoes.show', $cartaoId)->with('success', 'Gasto removido!');
    }
}
