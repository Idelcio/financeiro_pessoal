<?php

namespace App\Http\Controllers;

use App\Models\CartaoParcela;
use App\Models\Categoria;
use App\Models\Combustivel;
use App\Models\DespesaVeiculo;
use App\Models\GastoAvulso;
use App\Models\GastoFixo;
use App\Models\GastoFixoPagamento;
use App\Models\ImpostoParcela;
use App\Models\Manutencao;
use App\Models\Receita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user       = Auth::user();
        $tipo       = $request->get('tipo', 'todos');
        $mes        = $request->get('mes', now()->format('Y-m'));
        $categoriaId = $request->get('categoria_id');

        // Categorias do usuario para o filtro
        $categorias = Categoria::where('user_id', $user->id)->orderBy('nome')->get();

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
            ->whereHas('cartaoGasto')
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

        // Gastos com veiculos do mes (combustivel + manutencoes + despesas)
        $mesParts = explode('-', $mes);
        $mesAno = $mesParts[0];
        $mesNum = $mesParts[1];

        $combustiveisMes = Combustivel::where('user_id', $user->id)
            ->whereYear('data_abastecimento', $mesAno)
            ->whereMonth('data_abastecimento', $mesNum)
            ->when($tipo !== 'todos', fn($q) => $q->where('tipo', $tipo))
            ->with('veiculo')
            ->get();

        $manutencoesMes = Manutencao::where('user_id', $user->id)
            ->whereYear('data', $mesAno)
            ->whereMonth('data', $mesNum)
            ->when($tipo !== 'todos', fn($q) => $q->where('tipo_uso', $tipo))
            ->with('veiculo')
            ->get();

        $despesasVeiculoMes = DespesaVeiculo::where('user_id', $user->id)
            ->whereYear('data', $mesAno)
            ->whereMonth('data', $mesNum)
            ->when($tipo !== 'todos', fn($q) => $q->where('tipo_uso', $tipo))
            ->with('veiculo')
            ->get();

        $totalVeiculosMes = $combustiveisMes->sum('valor_total_centavos')
            + $manutencoesMes->sum('valor_centavos')
            + $despesasVeiculoMes->sum('valor_centavos');

        // Gastos avulsos do mes
        $gastosAvulsosMes = GastoAvulso::where('user_id', $user->id)
            ->whereYear('data', $mesAno)
            ->whereMonth('data', $mesNum)
            ->when($tipo !== 'todos', fn($q) => $q->where('tipo', $tipo))
            ->when($categoriaId, fn($q) => $q->where('categoria_id', $categoriaId))
            ->with('categoria')
            ->orderByDesc('data')
            ->get();

        $totalAvulsosMes = $gastosAvulsosMes->sum('valor_centavos');

        // Impostos pagos no mes
        $impostosDoMes = ImpostoParcela::where('user_id', $user->id)
            ->whereYear('data_vencimento', $mesAno)
            ->whereMonth('data_vencimento', $mesNum)
            ->with('imposto')
            ->get();

        $totalImpostosMes      = $impostosDoMes->sum('valor_centavos');
        $totalImpostosPago     = $impostosDoMes->where('pago', true)->sum('valor_centavos');
        $totalImpostosPendente = $totalImpostosMes - $totalImpostosPago;

        $totalGeralMes      = $totalFixosMes + $totalCartoesMes + $totalVeiculosMes + $totalAvulsosMes + $totalImpostosMes;
        $totalGeralPago     = $totalFixosPago + $totalCartoesPago + $totalImpostosPago;
        $totalGeralPendente = $totalFixosPendente + $totalCartoesPendente + $totalImpostosPendente;

        // Entradas (receitas) do mes
        $receitasRecorrentes = Receita::where('user_id', $user->id)
            ->where('recorrente', true)
            ->whereRaw("DATE_FORMAT(data, '%Y-%m') <= ?", [$mes])
            ->get();

        $receitasAvulsas = Receita::where('user_id', $user->id)
            ->where('recorrente', false)
            ->whereRaw("DATE_FORMAT(data, '%Y-%m') = ?", [$mes])
            ->get();

        $totalReceitas = $receitasRecorrentes->sum('valor_centavos') + $receitasAvulsas->sum('valor_centavos');
        $saldo = $totalReceitas - $totalGeralMes;

        return view('dashboard.index', compact(
            'gastosFixos', 'pagamentosDoMes', 'mes', 'tipo', 'categorias', 'categoriaId',
            'totalFixosMes', 'totalFixosPago', 'totalFixosPendente',
            'parcelasMes', 'totalCartoesMes', 'totalCartoesPago', 'totalCartoesPendente',
            'impostosVencendo', 'fixosVencendo',
            'combustiveisMes', 'manutencoesMes', 'despesasVeiculoMes', 'totalVeiculosMes',
            'gastosAvulsosMes', 'totalAvulsosMes',
            'impostosDoMes', 'totalImpostosMes', 'totalImpostosPago', 'totalImpostosPendente',
            'totalGeralMes', 'totalGeralPago', 'totalGeralPendente',
            'totalReceitas', 'saldo'
        ));
    }
}
