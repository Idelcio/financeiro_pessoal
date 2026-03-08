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

        $data = $request->validate([
            'descricao'      => 'required|string|max:255',
            'valor_total'    => 'required|numeric|min:0.01',
            'total_parcelas' => 'required|integer|min:1|max:120',
            'data_compra'    => 'required|date',
            'categoria'      => 'nullable|string|max:100',
            'tipo'           => 'required|in:pessoal,empresa',
            'observacao'     => 'nullable|string',
        ]);

        $gasto = CartaoGasto::create([
            'cartao_id'            => $cartao->id,
            'user_id'              => Auth::id(),
            'descricao'            => $data['descricao'],
            'valor_total_centavos' => (int) round($data['valor_total'] * 100),
            'total_parcelas'       => $data['total_parcelas'],
            'data_compra'          => $data['data_compra'],
            'categoria'            => $data['categoria'] ?? null,
            'tipo'                 => $data['tipo'],
            'observacao'           => $data['observacao'] ?? null,
        ]);

        // Gera as parcelas automaticamente
        $valorParcela = (int) round(($data['valor_total'] * 100) / $data['total_parcelas']);
        $dataBase = Carbon::parse($data['data_compra']);

        for ($i = 1; $i <= $data['total_parcelas']; $i++) {
            $mesRef = $dataBase->copy()->addMonths($i - 1)->format('Y-m');
            CartaoParcela::create([
                'cartao_gasto_id' => $gasto->id,
                'user_id'         => Auth::id(),
                'numero_parcela'  => $i,
                'valor_centavos'  => $valorParcela,
                'mes_referencia'  => $mesRef,
            ]);
        }

        return redirect()->route('cartoes.show', $cartao)->with('success', "Compra registrada em {$data['total_parcelas']}x!");
    }

    public function destroy(CartaoGasto $cartaoGasto)
    {
        abort_if($cartaoGasto->user_id != Auth::id(), 403);
        $cartaoId = $cartaoGasto->cartao_id;
        $cartaoGasto->delete();
        return redirect()->route('cartoes.show', $cartaoId)->with('success', 'Gasto removido!');
    }

    public function index()
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

    public function update(Request $request, string $id)
    {
        //
    }
}
