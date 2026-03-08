<?php

namespace App\Http\Controllers;

use App\Models\Manutencao;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManutencaoController extends Controller
{
    public function create(Veiculo $veiculo)
    {
        abort_if($veiculo->user_id != Auth::id(), 403);
        $tipos = Manutencao::$tipos;
        return view('manutencoes.create', compact('veiculo', 'tipos'));
    }

    public function store(Request $request, Veiculo $veiculo)
    {
        abort_if($veiculo->user_id != Auth::id(), 403);

        $data = $request->validate([
            'tipo'              => 'required|string',
            'descricao'         => 'required|string|max:255',
            'data'              => 'required|date',
            'km_na_manutencao'  => 'nullable|integer|min:0',
            'valor'             => 'nullable|numeric|min:0',
            'oficina'           => 'nullable|string|max:255',
            'proxima_km'        => 'nullable|integer|min:0',
            'proxima_data'      => 'nullable|date',
            'tipo_uso'          => 'required|in:pessoal,empresa',
            'observacao'        => 'nullable|string',
        ]);

        Manutencao::create([
            'user_id'          => Auth::id(),
            'veiculo_id'       => $veiculo->id,
            'tipo'             => $data['tipo'],
            'descricao'        => $data['descricao'],
            'data'             => $data['data'],
            'km_na_manutencao' => $data['km_na_manutencao'] ?? null,
            'valor_centavos'   => isset($data['valor']) ? (int) round($data['valor'] * 100) : null,
            'oficina'          => $data['oficina'] ?? null,
            'proxima_km'       => $data['proxima_km'] ?? null,
            'proxima_data'     => $data['proxima_data'] ?? null,
            'tipo_uso'         => $data['tipo_uso'],
            'observacao'       => $data['observacao'] ?? null,
        ]);

        // Atualiza km do veículo se maior que o atual
        if (!empty($data['km_na_manutencao']) && $data['km_na_manutencao'] > ($veiculo->km_atual ?? 0)) {
            $veiculo->update(['km_atual' => $data['km_na_manutencao']]);
        }

        return redirect()->route('veiculos.show', $veiculo)->with('success', 'Manutenção registrada!');
    }

    public function edit(Veiculo $veiculo, Manutencao $manutencao)
    {
        abort_if($veiculo->user_id != Auth::id(), 403);
        abort_if($manutencao->veiculo_id !== $veiculo->id, 403);
        $tipos = Manutencao::$tipos;
        return view('manutencoes.edit', compact('veiculo', 'manutencao', 'tipos'));
    }

    public function update(Request $request, Veiculo $veiculo, Manutencao $manutencao)
    {
        abort_if($veiculo->user_id != Auth::id(), 403);
        abort_if($manutencao->veiculo_id !== $veiculo->id, 403);

        $data = $request->validate([
            'tipo'              => 'required|string',
            'descricao'         => 'required|string|max:255',
            'data'              => 'required|date',
            'km_na_manutencao'  => 'nullable|integer|min:0',
            'valor'             => 'nullable|numeric|min:0',
            'oficina'           => 'nullable|string|max:255',
            'proxima_km'        => 'nullable|integer|min:0',
            'proxima_data'      => 'nullable|date',
            'tipo_uso'          => 'required|in:pessoal,empresa',
            'observacao'        => 'nullable|string',
        ]);

        $manutencao->update([
            'tipo'             => $data['tipo'],
            'descricao'        => $data['descricao'],
            'data'             => $data['data'],
            'km_na_manutencao' => $data['km_na_manutencao'] ?? null,
            'valor_centavos'   => isset($data['valor']) ? (int) round($data['valor'] * 100) : null,
            'oficina'          => $data['oficina'] ?? null,
            'proxima_km'       => $data['proxima_km'] ?? null,
            'proxima_data'     => $data['proxima_data'] ?? null,
            'tipo_uso'         => $data['tipo_uso'],
            'observacao'       => $data['observacao'] ?? null,
        ]);

        return redirect()->route('veiculos.show', $veiculo)->with('success', 'Manutenção atualizada!');
    }

    public function destroy(Veiculo $veiculo, Manutencao $manutencao)
    {
        abort_if($veiculo->user_id != Auth::id(), 403);
        abort_if($manutencao->veiculo_id !== $veiculo->id, 403);
        $manutencao->delete();
        return redirect()->route('veiculos.show', $veiculo)->with('success', 'Manutenção removida!');
    }
}
