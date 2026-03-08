<?php

namespace App\Http\Controllers;

use App\Models\DespesaVeiculo;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DespesaVeiculoController extends Controller
{
    public function create(Veiculo $veiculo)
    {
        abort_if($veiculo->user_id != Auth::id(), 403);
        $tipos = DespesaVeiculo::$tipos;
        return view('despesas_veiculo.create', compact('veiculo', 'tipos'));
    }

    public function store(Request $request, Veiculo $veiculo)
    {
        abort_if($veiculo->user_id != Auth::id(), 403);

        $data = $request->validate([
            'tipo'       => 'required|string',
            'descricao'  => 'required|string|max:255',
            'data'       => 'required|date',
            'valor'      => 'required|numeric|min:0.01',
            'tipo_uso'   => 'required|in:pessoal,empresa',
            'observacao' => 'nullable|string',
        ]);

        DespesaVeiculo::create([
            'user_id'        => Auth::id(),
            'veiculo_id'     => $veiculo->id,
            'tipo'           => $data['tipo'],
            'descricao'      => $data['descricao'],
            'data'           => $data['data'],
            'valor_centavos' => (int) round($data['valor'] * 100),
            'tipo_uso'       => $data['tipo_uso'],
            'observacao'     => $data['observacao'] ?? null,
        ]);

        return redirect()->route('veiculos.show', $veiculo)->with('success', 'Despesa registrada!');
    }

    public function edit(Veiculo $veiculo, DespesaVeiculo $despesa)
    {
        abort_if($veiculo->user_id != Auth::id(), 403);
        abort_if($despesa->veiculo_id !== $veiculo->id, 403);
        $tipos = DespesaVeiculo::$tipos;
        return view('despesas_veiculo.edit', compact('veiculo', 'despesa', 'tipos'));
    }

    public function update(Request $request, Veiculo $veiculo, DespesaVeiculo $despesa)
    {
        abort_if($veiculo->user_id != Auth::id(), 403);
        abort_if($despesa->veiculo_id !== $veiculo->id, 403);

        $data = $request->validate([
            'tipo'       => 'required|string',
            'descricao'  => 'required|string|max:255',
            'data'       => 'required|date',
            'valor'      => 'required|numeric|min:0.01',
            'tipo_uso'   => 'required|in:pessoal,empresa',
            'observacao' => 'nullable|string',
        ]);

        $despesa->update([
            'tipo'           => $data['tipo'],
            'descricao'      => $data['descricao'],
            'data'           => $data['data'],
            'valor_centavos' => (int) round($data['valor'] * 100),
            'tipo_uso'       => $data['tipo_uso'],
            'observacao'     => $data['observacao'] ?? null,
        ]);

        return redirect()->route('veiculos.show', $veiculo)->with('success', 'Despesa atualizada!');
    }

    public function destroy(Veiculo $veiculo, DespesaVeiculo $despesa)
    {
        abort_if($veiculo->user_id != Auth::id(), 403);
        abort_if($despesa->veiculo_id !== $veiculo->id, 403);
        $despesa->delete();
        return redirect()->route('veiculos.show', $veiculo)->with('success', 'Despesa removida!');
    }
}
