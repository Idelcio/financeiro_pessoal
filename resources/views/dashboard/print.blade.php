<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório {{ \Carbon\Carbon::createFromFormat('Y-m', $mes)->format('m/Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; color: #000; background: #fff; padding: 20px; max-width: 680px; margin: 0 auto; }
        .no-print { margin-bottom: 16px; display: flex; gap: 8px; }
        .btn { padding: 8px 16px; border: 1px solid #333; background: #333; color: #fff; cursor: pointer; font-family: inherit; font-size: 12px; }
        .btn-outline { background: #fff; color: #333; }
        h1 { font-size: 16px; text-align: center; text-transform: uppercase; letter-spacing: 2px; border-bottom: 2px solid #000; padding-bottom: 6px; margin-bottom: 4px; }
        .subtitle { text-align: center; font-size: 11px; margin-bottom: 2px; }
        .divider { border: none; border-top: 1px dashed #000; margin: 10px 0; }
        .divider-solid { border: none; border-top: 2px solid #000; margin: 10px 0; }
        .section-title { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; margin-bottom: 6px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 3px; }
        .row .label { flex: 1; }
        .row .val { text-align: right; min-width: 90px; }
        .row .sub { font-size: 10px; color: #555; margin-left: 10px; }
        .status { font-size: 10px; margin-left: 6px; }
        .pago { color: #000; }
        .pendente { font-weight: bold; }
        .total-row { display: flex; justify-content: space-between; font-weight: bold; margin-top: 4px; border-top: 1px solid #000; padding-top: 4px; }
        .grand-total { font-size: 14px; font-weight: bold; display: flex; justify-content: space-between; margin-top: 6px; border-top: 2px solid #000; padding-top: 6px; }
        .footer { text-align: center; font-size: 10px; margin-top: 16px; color: #555; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 10px; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button class="btn" onclick="window.print()">🖨️ Imprimir / Salvar PDF</button>
    <button class="btn btn-outline" onclick="window.close()">Fechar</button>
</div>

<h1>Gestor Financeiro</h1>
<p class="subtitle">RELATÓRIO DE GASTOS</p>
<p class="subtitle">
    {{ \Carbon\Carbon::createFromFormat('Y-m', $mes)->translatedFormat('F \d\e Y') }}
    @if($tipo !== 'todos') · {{ strtoupper($tipo) }} @endif
    @if($categoriaId)
        @php $catAtiva = $categorias->firstWhere('id', $categoriaId); @endphp
        · {{ strtoupper($catAtiva?->nome ?? '') }}
    @endif
</p>
<p class="subtitle" style="font-size:10px;color:#555;">Gerado em {{ now()->format('d/m/Y H:i') }}</p>

<hr class="divider-solid">

{{-- RESUMO --}}
<div class="section-title">Resumo</div>
<div class="row"><span class="label">Entradas</span><span class="val">R$ {{ number_format($totalReceitas / 100, 2, ',', '.') }}</span></div>
<div class="row"><span class="label">Gastos totais</span><span class="val">R$ {{ number_format($totalGeralMes / 100, 2, ',', '.') }}</span></div>
<div class="row"><span class="label">Saldo</span><span class="val">{{ $saldo >= 0 ? '' : '-' }}R$ {{ number_format(abs($saldo) / 100, 2, ',', '.') }}</span></div>
<div class="row"><span class="label">A pagar</span><span class="val">R$ {{ number_format($totalGeralPendente / 100, 2, ',', '.') }}</span></div>

@if(!$categoriaId)

{{-- GASTOS FIXOS --}}
@if($gastosFixos->isNotEmpty())
<hr class="divider">
<div class="section-title">Gastos Fixos</div>
@foreach($gastosFixos as $g)
<div class="row">
    <span class="label">{{ $g->nome }}</span>
    <span class="val">
        R$ {{ number_format($g->valor_centavos / 100, 2, ',', '.') }}
        <span class="status {{ in_array($g->id, $pagamentosDoMes) ? 'pago' : 'pendente' }}">
            {{ in_array($g->id, $pagamentosDoMes) ? '[PAGO]' : '[PEND]' }}
        </span>
    </span>
</div>
@endforeach
<div class="total-row"><span>Total fixos</span><span>R$ {{ number_format($totalFixosMes / 100, 2, ',', '.') }}</span></div>
@endif

{{-- CARTOES --}}
@if($parcelasMes->isNotEmpty())
<hr class="divider">
<div class="section-title">Cartões de Crédito</div>
@foreach($parcelasMes as $p)
<div class="row">
    <span class="label">{{ $p->cartaoGasto?->descricao ?? '—' }} <span class="sub">{{ $p->cartaoGasto?->cartao?->nome }} {{ $p->numero_parcela }}/{{ $p->cartaoGasto?->total_parcelas }}</span></span>
    <span class="val">
        R$ {{ number_format($p->valor_centavos / 100, 2, ',', '.') }}
        <span class="status {{ $p->pago ? 'pago' : 'pendente' }}">{{ $p->pago ? '[PAGO]' : '[PEND]' }}</span>
    </span>
</div>
@endforeach
<div class="total-row"><span>Total cartões</span><span>R$ {{ number_format($totalCartoesMes / 100, 2, ',', '.') }}</span></div>
@endif

{{-- VEICULOS --}}
@if($totalVeiculosMes > 0)
<hr class="divider">
<div class="section-title">Veículos</div>
@foreach($combustiveisMes as $item)
<div class="row">
    <span class="label">Combustível{{ $item->veiculo ? ' · '.$item->veiculo->nome : '' }}</span>
    <span class="val">R$ {{ number_format($item->valor_total_centavos / 100, 2, ',', '.') }}</span>
</div>
@endforeach
@foreach($manutencoesMes as $item)
<div class="row">
    <span class="label">{{ $item->tipo_label }}{{ $item->veiculo ? ' · '.$item->veiculo->nome : '' }}</span>
    <span class="val">R$ {{ number_format($item->valor_centavos / 100, 2, ',', '.') }}</span>
</div>
@endforeach
@foreach($despesasVeiculoMes as $item)
<div class="row">
    <span class="label">{{ $item->tipo_label }}{{ $item->veiculo ? ' · '.$item->veiculo->nome : '' }}</span>
    <span class="val">R$ {{ number_format($item->valor_centavos / 100, 2, ',', '.') }}</span>
</div>
@endforeach
<div class="total-row"><span>Total veículos</span><span>R$ {{ number_format($totalVeiculosMes / 100, 2, ',', '.') }}</span></div>
@endif

{{-- IMPOSTOS --}}
@if($totalImpostosMes > 0)
<hr class="divider">
<div class="section-title">Impostos (IPVA / IPTU)</div>
@foreach($impostosDoMes as $parcela)
<div class="row">
    <span class="label">{{ $parcela->imposto?->nome ?? 'Imposto' }} ({{ $parcela->numero_parcela }}x)</span>
    <span class="val">
        R$ {{ number_format($parcela->valor_centavos / 100, 2, ',', '.') }}
        <span class="status {{ $parcela->pago ? 'pago' : 'pendente' }}">{{ $parcela->pago ? '[PAGO]' : '[PEND]' }}</span>
    </span>
</div>
@endforeach
<div class="total-row"><span>Total impostos</span><span>R$ {{ number_format($totalImpostosMes / 100, 2, ',', '.') }}</span></div>
@endif

@endif {{-- fim !categoriaId --}}

{{-- GASTOS AVULSOS --}}
@if($gastosAvulsosMes->isNotEmpty())
<hr class="divider">
<div class="section-title">Gastos Avulsos{{ $categoriaId && isset($catAtiva) ? ' · '.$catAtiva->nome : '' }}</div>
@foreach($gastosAvulsosMes as $item)
<div class="row">
    <span class="label">{{ $item->descricao }}{{ $item->categoria ? ' <span class="sub">['.$item->categoria->nome.']</span>' : '' }}</span>
    <span class="val">R$ {{ number_format($item->valor_centavos / 100, 2, ',', '.') }}</span>
</div>
@endforeach
<div class="total-row"><span>Total avulsos</span><span>R$ {{ number_format($totalAvulsosMes / 100, 2, ',', '.') }}</span></div>
@endif

<hr class="divider-solid">
<div class="grand-total">
    <span>TOTAL GERAL</span>
    <span>R$ {{ number_format($totalGeralMes / 100, 2, ',', '.') }}</span>
</div>

<p class="footer">gestor financeiro &mdash; {{ now()->format('d/m/Y H:i') }}</p>

</body>
</html>
