<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VeiculoController extends Controller
{
    public function index()
    {
        $veiculos = Veiculo::where('user_id', Auth::id())
            ->with(['combustiveis', 'manutencoes', 'despesas'])
            ->get();

        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        return view('veiculos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'             => 'required|string|max:255',
            'marca'            => 'nullable|string|max:100',
            'modelo'           => 'nullable|string|max:100',
            'ano'              => 'nullable|integer|min:1900|max:' . (now()->year + 1),
            'placa'            => 'nullable|string|max:10',
            'cor'              => 'nullable|string|max:50',
            'km_atual'         => 'nullable|integer|min:0',
            'tipo_combustivel' => 'required|string',
            'observacao'       => 'nullable|string',
        ]);

        Veiculo::create([...$data, 'user_id' => Auth::id()]);

        return redirect()->route('veiculos.index')->with('success', 'Veículo cadastrado com sucesso!');
    }

    public function show(Veiculo $veiculo)
    {
        abort_if($veiculo->user_id !== Auth::id(), 403);

        $veiculo->load(['combustiveis', 'manutencoes', 'despesas', 'impostos.parcelas']);

        // Abastecimentos ordenados por data
        $abastecimentos = $veiculo->combustiveis()->orderByDesc('data_abastecimento')->take(10)->get();

        // Manutenções ordenadas por data
        $manutencoes = $veiculo->manutencoes()->orderByDesc('data')->get();

        // Alertas: manutenções com proximidade
        $alertas = $veiculo->manutencoes()
            ->where(function ($q) use ($veiculo) {
                $q->where('proxima_data', '<=', now()->addDays(30))
                  ->orWhere(function ($q2) use ($veiculo) {
                      if ($veiculo->km_atual) {
                          $q2->whereNotNull('proxima_km')
                             ->whereRaw('proxima_km <= ?', [$veiculo->km_atual + 1000]);
                      }
                  });
            })
            ->get();

        // Despesas do mês atual
        $despesasMes = $veiculo->despesas()
            ->whereYear('data', now()->year)
            ->whereMonth('data', now()->month)
            ->sum('valor_centavos');

        // Gasto total combustível mês atual
        $combustivelMes = $veiculo->combustiveis()
            ->whereYear('data_abastecimento', now()->year)
            ->whereMonth('data_abastecimento', now()->month)
            ->sum('valor_total_centavos');

        return view('veiculos.show', compact(
            'veiculo', 'abastecimentos', 'manutencoes', 'alertas', 'despesasMes', 'combustivelMes'
        ));
    }

    public function edit(Veiculo $veiculo)
    {
        abort_if($veiculo->user_id !== Auth::id(), 403);
        return view('veiculos.edit', compact('veiculo'));
    }

    public function update(Request $request, Veiculo $veiculo)
    {
        abort_if($veiculo->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'nome'             => 'required|string|max:255',
            'marca'            => 'nullable|string|max:100',
            'modelo'           => 'nullable|string|max:100',
            'ano'              => 'nullable|integer|min:1900|max:' . (now()->year + 1),
            'placa'            => 'nullable|string|max:10',
            'cor'              => 'nullable|string|max:50',
            'km_atual'         => 'nullable|integer|min:0',
            'tipo_combustivel' => 'required|string',
            'observacao'       => 'nullable|string',
            'ativo'            => 'boolean',
        ]);

        $veiculo->update($data);

        return redirect()->route('veiculos.show', $veiculo)->with('success', 'Veículo atualizado!');
    }

    public function destroy(Veiculo $veiculo)
    {
        abort_if($veiculo->user_id !== Auth::id(), 403);
        $veiculo->delete();
        return redirect()->route('veiculos.index')->with('success', 'Veículo removido!');
    }
}
