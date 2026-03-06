<?php

namespace App\Http\Controllers;

use App\Models\CartaoParcela;
use App\Models\GastoFixo;
use App\Models\GastoFixoPagamento;
use App\Models\ImpostoParcela;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tipo = $request->get('tipo', 'todos');
        $mes  = $request->get('mes', now()->format('Y-m'));

        // Gastos fixos do mes
        $gastosFixos = GastoFixo::where('user_id', $user->id)
            ->where('ativo', true)
            ->when($tipo !== 'todos', fn($q) => $q->where('tipo', $tipo))
            ->get();

        $pagamentosDoMes = GastoFixoPagamento::where('user_id', $user->id)
            ->where('mes_referencia', $mes)
            ->pluck('gasto_fixo_id')
            ->toArray();

        $totalFixosMes      = $gastosFixos->sum('valor_centavos');
        $totalFixosPago     = $gastosFixos->filter(fn($g) => in_array($g->id, $pagamentosDoMes))->sum('valor_centavos');
        $totalFixosPendente = $totalFixosMes - $totalFixosPago;

        // Parcelas de cartao do mes
        $parcelasMes = CartaoParcela::where('user_id', $user->id)
            ->where('mes_referencia', $mes)
            ->with('cartaoGasto.cartao')
            ->get();

        $totalCartoesMes      = $parcelasMes->sum('valor_centavos');
        $totalCartoesPago     = $parcelasMes->where('pago', true)->sum('valor_centavos');
        $totalCartoesPendente = $totalCartoesMes - $totalCartoesPago;

        // Impostos vencendo nos proximos 7 dias
        $proximos7Dias = Carbon::now()->addDays(7);
        $impostosVencendo = ImpostoParcela::where('user_id', $user->id)
            ->where('pago', false)
            ->whereBetween('data_vencimento', [now()->startOfDay(), $proximos7Dias])
            ->with('imposto')
            ->get();

        // Gastos fixos vencendo nos proximos 7 dias
        $diaAtual = now()->day;
        $fixosVencendo = $gastosFixos->filter(function ($g) use ($diaAtual, $pagamentosDoMes) {
            return !in_array($g->id, $pagamentosDoMes)
                && $g->dia_vencimento >= $diaAtual
                && $g->dia_vencimento <= ($diaAtual + 7);
        });

        $totalGeralMes      = $totalFixosMes + $totalCartoesMes;
        $totalGeralPago     = $totalFixosPago + $totalCartoesPago;
        $totalGeralPendente = $totalFixosPendente + $totalCartoesPendente;

        return view('dashboard.index', compact(
            'gastosFixos', 'pagamentosDoMes', 'mes', 'tipo',
            'totalFixosMes', 'totalFixosPago', 'totalFixosPendente',
            'parcelasMes', 'totalCartoesMes', 'totalCartoesPago', 'totalCartoesPendente',
            'impostosVencendo', 'fixosVencendo',
            'totalGeralMes', 'totalGeralPago', 'totalGeralPendente'
        ));
    }
}
