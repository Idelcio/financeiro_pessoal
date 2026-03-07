<?php

namespace App\Http\Controllers;

use App\Models\Combustivel;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CombustivelController extends Controller
{
    public function index()
    {
        $mes = request('mes', now()->format('Y-m'));
        [$ano, $mesNum] = explode('-', $mes);

        $abastecimentos = Combustivel::where('user_id', Auth::id())
            ->whereYear('data_abastecimento', $ano)
            ->whereMonth('data_abastecimento', $mesNum)
            ->orderByDesc('data_abastecimento')
            ->get();

        $totalMes = $abastecimentos->sum('valor_total_centavos');

        return view('combustivel.index', compact('abastecimentos', 'totalMes', 'mes'));
    }

    public function create()
    {
        $veiculos = Veiculo::where('user_id', Auth::id())->where('ativo', true)->get();
        $veiculoSelecionado = request('veiculo_id');
        return view('combustivel.create', compact('veiculos', 'veiculoSelecionado'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'veiculo_id'         => 'nullable|exists:veiculos,id',
            'data_abastecimento' => 'required|date',
            'tipo_combustivel'   => 'required|string',
            'litros'             => 'nullable|numeric|min:0',
            'valor_total'        => 'required|numeric|min:0.01',
            'valor_litro'        => 'nullable|numeric|min:0',
            'km_atual'           => 'nullable|integer|min:0',
            'posto'              => 'nullable|string|max:255',
            'tipo'               => 'required|in:pessoal,empresa',
            'observacao'         => 'nullable|string',
        ]);

        $combustivel = Combustivel::create([
            'user_id'              => Auth::id(),
            'veiculo_id'           => $data['veiculo_id'] ?? null,
            'data_abastecimento'   => $data['data_abastecimento'],
            'tipo_combustivel'     => $data['tipo_combustivel'],
            'litros'               => $data['litros'] ?? null,
            'valor_total_centavos' => (int) round($data['valor_total'] * 100),
            'valor_litro_centavos' => isset($data['valor_litro']) ? (int) round($data['valor_litro'] * 100) : null,
            'km_atual'             => $data['km_atual'] ?? null,
            'posto'                => $data['posto'] ?? null,
            'tipo'                 => $data['tipo'],
            'observacao'           => $data['observacao'] ?? null,
        ]);

        // Atualiza km do veículo se informado e maior que o atual
        if ($combustivel->veiculo_id && $combustivel->km_atual) {
            $veiculo = Veiculo::find($combustivel->veiculo_id);
            if ($veiculo && $combustivel->km_atual > ($veiculo->km_atual ?? 0)) {
                $veiculo->update(['km_atual' => $combustivel->km_atual]);
            }
        }

        // Redireciona de volta para o veículo se veio de lá
        if ($combustivel->veiculo_id) {
            return redirect()->route('veiculos.show', $combustivel->veiculo_id)->with('success', 'Abastecimento registrado!');
        }

        return redirect()->route('combustivel.index')->with('success', 'Abastecimento registrado!');
    }

    public function edit(Combustivel $combustivel)
    {
        abort_if($combustivel->user_id !== Auth::id(), 403);
        $veiculos = Veiculo::where('user_id', Auth::id())->where('ativo', true)->get();
        return view('combustivel.edit', compact('combustivel', 'veiculos'));
    }

    public function update(Request $request, Combustivel $combustivel)
    {
        abort_if($combustivel->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'veiculo_id'         => 'nullable|exists:veiculos,id',
            'data_abastecimento' => 'required|date',
            'tipo_combustivel'   => 'required|string',
            'litros'             => 'nullable|numeric|min:0',
            'valor_total'        => 'required|numeric|min:0.01',
            'valor_litro'        => 'nullable|numeric|min:0',
            'km_atual'           => 'nullable|integer|min:0',
            'posto'              => 'nullable|string|max:255',
            'tipo'               => 'required|in:pessoal,empresa',
            'observacao'         => 'nullable|string',
        ]);

        $combustivel->update([
            'veiculo_id'           => $data['veiculo_id'] ?? null,
            'data_abastecimento'   => $data['data_abastecimento'],
            'tipo_combustivel'     => $data['tipo_combustivel'],
            'litros'               => $data['litros'] ?? null,
            'valor_total_centavos' => (int) round($data['valor_total'] * 100),
            'valor_litro_centavos' => isset($data['valor_litro']) ? (int) round($data['valor_litro'] * 100) : null,
            'km_atual'             => $data['km_atual'] ?? null,
            'posto'                => $data['posto'] ?? null,
            'tipo'                 => $data['tipo'],
            'observacao'           => $data['observacao'] ?? null,
        ]);

        // Redireciona de volta para o veículo se vinculado
        if ($combustivel->veiculo_id) {
            return redirect()->route('veiculos.show', $combustivel->veiculo_id)->with('success', 'Abastecimento atualizado!');
        }

        return redirect()->route('combustivel.index')->with('success', 'Abastecimento atualizado!');
    }

    public function destroy(Combustivel $combustivel)
    {
        abort_if($combustivel->user_id !== Auth::id(), 403);
        $combustivel->delete();
        return redirect()->route('combustivel.index')->with('success', 'Abastecimento removido!');
    }

    public function show(string $id)
    {
        //
    }
}
