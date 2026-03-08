<?php

namespace App\Http\Controllers;

use App\Models\Cartao;
use App\Models\CartaoParcela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartaoController extends Controller
{
    public function index()
    {
        $cartoes = Cartao::where('user_id', Auth::id())
            ->where('ativo', true)
            ->withCount('gastos')
            ->get();

        return view('cartoes.index', compact('cartoes'));
    }

    public function create()
    {
        return view('cartoes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'            => 'required|string|max:255',
            'bandeira'        => 'nullable|string|max:50',
            'limite'          => 'nullable|numeric|min:0',
            'dia_fechamento'  => 'nullable|integer|min:1|max:31',
            'dia_vencimento'  => 'nullable|integer|min:1|max:31',
            'cor'             => 'required|string',
            'tipo'            => 'required|in:pessoal,empresa',
        ]);

        Cartao::create([
            'user_id'          => Auth::id(),
            'nome'             => $data['nome'],
            'bandeira'         => $data['bandeira'] ?? null,
            'limite_centavos'  => isset($data['limite']) ? (int) round($data['limite'] * 100) : null,
            'dia_fechamento'   => $data['dia_fechamento'] ?? null,
            'dia_vencimento'   => $data['dia_vencimento'] ?? null,
            'cor'              => $data['cor'],
            'tipo'             => $data['tipo'],
        ]);

        return redirect()->route('cartoes.index')->with('success', 'Cartao cadastrado!');
    }

    public function show(Cartao $cartao)
    {
        abort_if($cartao->user_id != Auth::id(), 403);

        $mes = request('mes', now()->format('Y-m'));

        $gastos = $cartao->gastos()
            ->with(['parcelas' => fn($q) => $q->where('mes_referencia', $mes)])
            ->orderByDesc('data_compra')
            ->get();

        $parcelasMes = $cartao->gastos()
            ->with(['parcelas' => fn($q) => $q->where('mes_referencia', $mes)])
            ->get()
            ->flatMap(fn($g) => $g->parcelas);

        $totalFaturaMes = $parcelasMes->sum('valor_centavos');
        $totalPagoMes   = $parcelasMes->where('pago', true)->sum('valor_centavos');
        $totalDevido     = $cartao->totalDevidoCentavos();

        return view('cartoes.show', compact('cartao', 'gastos', 'mes', 'totalFaturaMes', 'totalPagoMes', 'totalDevido'));
    }

    public function edit(Cartao $cartao)
    {
        abort_if($cartao->user_id != Auth::id(), 403);
        return view('cartoes.edit', compact('cartao'));
    }

    public function update(Request $request, Cartao $cartao)
    {
        abort_if($cartao->user_id != Auth::id(), 403);

        $data = $request->validate([
            'nome'           => 'required|string|max:255',
            'bandeira'       => 'nullable|string|max:50',
            'limite'         => 'nullable|numeric|min:0',
            'dia_fechamento' => 'nullable|integer|min:1|max:31',
            'dia_vencimento' => 'nullable|integer|min:1|max:31',
            'cor'            => 'required|string',
            'tipo'           => 'required|in:pessoal,empresa',
            'ativo'          => 'boolean',
        ]);

        $cartao->update([
            'nome'            => $data['nome'],
            'bandeira'        => $data['bandeira'] ?? null,
            'limite_centavos' => isset($data['limite']) ? (int) round($data['limite'] * 100) : null,
            'dia_fechamento'  => $data['dia_fechamento'] ?? null,
            'dia_vencimento'  => $data['dia_vencimento'] ?? null,
            'cor'             => $data['cor'],
            'tipo'            => $data['tipo'],
            'ativo'           => $request->boolean('ativo'),
        ]);

        return redirect()->route('cartoes.show', $cartao)->with('success', 'Cartao atualizado!');
    }

    public function destroy(Cartao $cartao)
    {
        abort_if($cartao->user_id != Auth::id(), 403);
        $cartao->delete();
        return redirect()->route('cartoes.index')->with('success', 'Cartao removido!');
    }
}
