<?php

namespace App\Http\Controllers;

use App\Models\Imposto;
use App\Models\ImpostoParcela;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpostoController extends Controller
{
    public function index()
    {
        $ano = request('ano', now()->year);

        $impostos = Imposto::where('user_id', Auth::id())
            ->where('ano', $ano)
            ->with('parcelas')
            ->orderBy('tipo')
            ->orderBy('nome')
            ->get();

        return view('impostos.index', compact('impostos', 'ano'));
    }

    public function create()
    {
        return view('impostos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'           => 'required|string|max:255',
            'tipo'           => 'required|in:ipva,iptu',
            'ano'            => 'required|integer|min:2000|max:2099',
            'valor_total'    => 'required|numeric|min:0.01',
            'total_parcelas' => 'required|integer|min:1|max:12',
            'descricao_bem'  => 'nullable|string|max:255',
            'tipo_uso'       => 'required|in:pessoal,empresa',
            'observacao'     => 'nullable|string',
            'data_primeira_parcela' => 'required|date',
        ]);

        $imposto = Imposto::create([
            'user_id'              => Auth::id(),
            'nome'                 => $data['nome'],
            'tipo'                 => $data['tipo'],
            'ano'                  => $data['ano'],
            'valor_total_centavos' => (int) round($data['valor_total'] * 100),
            'total_parcelas'       => $data['total_parcelas'],
            'descricao_bem'        => $data['descricao_bem'] ?? null,
            'tipo_uso'             => $data['tipo_uso'],
            'observacao'           => $data['observacao'] ?? null,
        ]);

        $valorParcela = (int) round(($data['valor_total'] * 100) / $data['total_parcelas']);
        $dataBase = Carbon::parse($data['data_primeira_parcela']);

        for ($i = 1; $i <= $data['total_parcelas']; $i++) {
            ImpostoParcela::create([
                'imposto_id'     => $imposto->id,
                'user_id'        => Auth::id(),
                'numero_parcela' => $i,
                'valor_centavos' => $valorParcela,
                'data_vencimento'=> $dataBase->copy()->addMonths($i - 1)->toDateString(),
            ]);
        }

        return redirect()->route('impostos.show', $imposto)->with('success', 'Imposto cadastrado!');
    }

    public function show(Imposto $imposto)
    {
        abort_if($imposto->user_id !== Auth::id(), 403);
        $imposto->load('parcelas');
        return view('impostos.show', compact('imposto'));
    }

    public function edit(Imposto $imposto)
    {
        abort_if($imposto->user_id !== Auth::id(), 403);
        return view('impostos.edit', compact('imposto'));
    }

    public function update(Request $request, Imposto $imposto)
    {
        abort_if($imposto->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'nome'          => 'required|string|max:255',
            'descricao_bem' => 'nullable|string|max:255',
            'tipo_uso'      => 'required|in:pessoal,empresa',
            'observacao'    => 'nullable|string',
        ]);

        $imposto->update($data);

        return redirect()->route('impostos.show', $imposto)->with('success', 'Imposto atualizado!');
    }

    public function destroy(Imposto $imposto)
    {
        abort_if($imposto->user_id !== Auth::id(), 403);
        $imposto->delete();
        return redirect()->route('impostos.index')->with('success', 'Imposto removido!');
    }
}
