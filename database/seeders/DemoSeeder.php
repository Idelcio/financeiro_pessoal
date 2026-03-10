<?php

namespace Database\Seeders;

use App\Models\Receita;
use App\Models\GastoFixo;
use App\Models\GastoFixoPagamento;
use App\Models\Cartao;
use App\Models\CartaoGasto;
use App\Models\CartaoParcela;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $uid = 1;
        $mes = now()->format('Y-m');
        $hoje = now()->toDateString();
        $mesInicio = now()->startOfMonth()->toDateString();

        // ===== RECEITAS =====
        $receitas = [
            ['descricao' => 'Salário', 'valor_centavos' => 1200000, 'data' => '2026-01-05', 'recorrente' => true, 'tipo' => 'pessoal', 'observacao' => null],
            ['descricao' => 'Freelance Web Design', 'valor_centavos' => 350000, 'data' => '2026-02-10', 'recorrente' => true, 'tipo' => 'pessoal', 'observacao' => 'Cliente fixo mensal'],
            ['descricao' => 'Aluguel imóvel', 'valor_centavos' => 180000, 'data' => '2026-01-01', 'recorrente' => true, 'tipo' => 'pessoal', 'observacao' => null],
            ['descricao' => 'Pró-labore empresa', 'valor_centavos' => 500000, 'data' => '2026-01-01', 'recorrente' => true, 'tipo' => 'empresa', 'observacao' => null],
            ['descricao' => 'Serviço de garçom fim de semana', 'valor_centavos' => 20000, 'data' => $hoje, 'recorrente' => false, 'tipo' => 'pessoal', 'observacao' => 'Evento extra'],
            ['descricao' => 'Venda de equipamento', 'valor_centavos' => 50000, 'data' => $hoje, 'recorrente' => false, 'tipo' => 'pessoal', 'observacao' => null],
        ];
        foreach ($receitas as $r) {
            Receita::create(array_merge($r, ['user_id' => $uid]));
        }

        // ===== GASTOS FIXOS =====
        $fixos = [
            ['nome' => 'Aluguel apartamento', 'valor_centavos' => 250000, 'dia_vencimento' => 5, 'tipo' => 'pessoal', 'tipo_gasto' => 'moradia'],
            ['nome' => 'Internet Fibra', 'valor_centavos' => 12990, 'dia_vencimento' => 10, 'tipo' => 'pessoal', 'tipo_gasto' => 'servicos'],
            ['nome' => 'Plano de saúde', 'valor_centavos' => 58000, 'dia_vencimento' => 8, 'tipo' => 'pessoal', 'tipo_gasto' => 'saude'],
            ['nome' => 'Academia', 'valor_centavos' => 9900, 'dia_vencimento' => 1, 'tipo' => 'pessoal', 'tipo_gasto' => 'lazer'],
            ['nome' => 'Netflix', 'valor_centavos' => 5590, 'dia_vencimento' => 15, 'tipo' => 'pessoal', 'tipo_gasto' => 'lazer'],
            ['nome' => 'Spotify', 'valor_centavos' => 2190, 'dia_vencimento' => 20, 'tipo' => 'pessoal', 'tipo_gasto' => 'lazer'],
            ['nome' => 'Seguro do carro', 'valor_centavos' => 38000, 'dia_vencimento' => 12, 'tipo' => 'pessoal', 'tipo_gasto' => 'transporte'],
            ['nome' => 'Conta de luz', 'valor_centavos' => 28000, 'dia_vencimento' => 18, 'tipo' => 'pessoal', 'tipo_gasto' => 'moradia'],
            ['nome' => 'Água e esgoto', 'valor_centavos' => 8500, 'dia_vencimento' => 20, 'tipo' => 'pessoal', 'tipo_gasto' => 'moradia'],
            ['nome' => 'Coworking', 'valor_centavos' => 45000, 'dia_vencimento' => 1, 'tipo' => 'empresa', 'tipo_gasto' => 'servicos'],
            ['nome' => 'Software ERP', 'valor_centavos' => 19900, 'dia_vencimento' => 5, 'tipo' => 'empresa', 'tipo_gasto' => 'servicos'],
        ];
        $fixoIds = [];
        foreach ($fixos as $f) {
            $g = GastoFixo::create(array_merge($f, ['user_id' => $uid, 'ativo' => true]));
            $fixoIds[] = $g->id;
        }
        // Marcar 7 como pagos
        $fixosObj = GastoFixo::whereIn('id', array_slice($fixoIds, 0, 7))->get()->keyBy('id');
        foreach (array_slice($fixoIds, 0, 7) as $id) {
            GastoFixoPagamento::create([
                'user_id' => $uid,
                'gasto_fixo_id' => $id,
                'mes_referencia' => $mes,
                'data_pagamento' => $mesInicio,
                'valor_pago_centavos' => $fixosObj[$id]->valor_centavos,
            ]);
        }

        // ===== CARTÕES =====
        $cartao1 = Cartao::create([
            'user_id' => $uid, 'nome' => 'Nubank Roxo', 'bandeira' => 'mastercard',
            'limite_centavos' => 1500000, 'dia_fechamento' => 20, 'dia_vencimento' => 27,
            'cor' => '#8B5CF6', 'tipo' => 'pessoal',
        ]);
        $cartao2 = Cartao::create([
            'user_id' => $uid, 'nome' => 'Itaú Platinum', 'bandeira' => 'visa',
            'limite_centavos' => 800000, 'dia_fechamento' => 15, 'dia_vencimento' => 22,
            'cor' => '#F59E0B', 'tipo' => 'empresa',
        ]);

        $compras = [
            // cartao1
            ['cartao_id' => $cartao1->id, 'descricao' => 'Supermercado Extra', 'valor_total_centavos' => 85000, 'total_parcelas' => 1, 'data_compra' => $mesInicio, 'tipo' => 'pessoal', 'recorrente' => false],
            ['cartao_id' => $cartao1->id, 'descricao' => 'iPhone 16 Pro', 'valor_total_centavos' => 900000, 'total_parcelas' => 12, 'data_compra' => '2026-01-15', 'tipo' => 'pessoal', 'recorrente' => false],
            ['cartao_id' => $cartao1->id, 'descricao' => 'Curso de React', 'valor_total_centavos' => 49900, 'total_parcelas' => 3, 'data_compra' => '2026-02-01', 'tipo' => 'pessoal', 'recorrente' => false],
            ['cartao_id' => $cartao1->id, 'descricao' => 'Farmácia', 'valor_total_centavos' => 13500, 'total_parcelas' => 1, 'data_compra' => $hoje, 'tipo' => 'pessoal', 'recorrente' => false],
            ['cartao_id' => $cartao1->id, 'descricao' => 'Restaurante sushi', 'valor_total_centavos' => 19800, 'total_parcelas' => 1, 'data_compra' => $hoje, 'tipo' => 'pessoal', 'recorrente' => false],
            ['cartao_id' => $cartao1->id, 'descricao' => 'Uber mensal', 'valor_total_centavos' => 35000, 'total_parcelas' => 1, 'data_compra' => '2026-01-01', 'tipo' => 'pessoal', 'recorrente' => true],
            ['cartao_id' => $cartao1->id, 'descricao' => 'Amazon Prime', 'valor_total_centavos' => 1490, 'total_parcelas' => 1, 'data_compra' => '2026-01-01', 'tipo' => 'pessoal', 'recorrente' => true],
            // cartao2
            ['cartao_id' => $cartao2->id, 'descricao' => 'Google Workspace', 'valor_total_centavos' => 28000, 'total_parcelas' => 1, 'data_compra' => '2026-01-01', 'tipo' => 'empresa', 'recorrente' => true],
            ['cartao_id' => $cartao2->id, 'descricao' => 'Notebook Dell', 'valor_total_centavos' => 650000, 'total_parcelas' => 10, 'data_compra' => '2026-02-01', 'tipo' => 'empresa', 'recorrente' => false],
            ['cartao_id' => $cartao2->id, 'descricao' => 'Material de escritório', 'valor_total_centavos' => 18000, 'total_parcelas' => 1, 'data_compra' => $mesInicio, 'tipo' => 'empresa', 'recorrente' => false],
        ];

        foreach ($compras as $c) {
            $gasto = CartaoGasto::create([
                'user_id' => $uid,
                'cartao_id' => $c['cartao_id'],
                'descricao' => $c['descricao'],
                'valor_total_centavos' => $c['valor_total_centavos'],
                'total_parcelas' => $c['total_parcelas'],
                'data_compra' => $c['data_compra'],
                'tipo' => $c['tipo'],
                'recorrente' => $c['recorrente'],
                'recorrente_ativa' => true,
            ]);

            if ($c['recorrente']) {
                // Gera parcela do mês atual
                $mesCompra = Carbon::parse($c['data_compra'])->format('Y-m');
                if ($mes >= $mesCompra) {
                    $inicio = Carbon::createFromFormat('Y-m', $mesCompra)->startOfMonth();
                    $atual  = Carbon::createFromFormat('Y-m', $mes)->startOfMonth();
                    CartaoParcela::create([
                        'user_id' => $uid,
                        'cartao_gasto_id' => $gasto->id,
                        'numero_parcela' => $inicio->diffInMonths($atual) + 1,
                        'valor_centavos' => $c['valor_total_centavos'],
                        'mes_referencia' => $mes,
                    ]);
                }
            } else {
                $mesCompra = Carbon::parse($c['data_compra'])->startOfMonth();
                for ($i = 0; $i < $c['total_parcelas']; $i++) {
                    $mesRef = $mesCompra->copy()->addMonths($i)->format('Y-m');
                    if ($mesRef > now()->addMonths(6)->format('Y-m')) {
                        break;
                    }
                    $pago = $mesRef < $mes;
                    CartaoParcela::create([
                        'user_id' => $uid,
                        'cartao_gasto_id' => $gasto->id,
                        'numero_parcela' => $i + 1,
                        'valor_centavos' => (int) round($c['valor_total_centavos'] / $c['total_parcelas']),
                        'mes_referencia' => $mesRef,
                        'pago' => $pago,
                        'data_pagamento' => $pago ? $mesInicio : null,
                    ]);
                }
            }
        }

        $this->command->info('Demo data criado com sucesso!');
    }
}
