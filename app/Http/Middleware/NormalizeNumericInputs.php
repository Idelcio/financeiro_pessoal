<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NormalizeNumericInputs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // Campos monetários conhecidos (aceitar vírgula BR e separadores de milhar)
    private array $moneyFields = [
        'valor', 'valor_total', 'limite', 'preco_litro', 'litros',
        'valor_pago', 'preco', 'valor_parcela',
    ];

    // Campos de dia (aceitar "07" como 7)
    private array $dayFields = [
        'dia_fechamento', 'dia_vencimento',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $merge = [];

            foreach ($this->moneyFields as $field) {
                if ($request->has($field)) {
                    $merge[$field] = $this->cleanMoney($request->input($field));
                }
            }

            foreach ($this->dayFields as $field) {
                if ($request->has($field) && $request->input($field) !== null) {
                    $merge[$field] = (int) $request->input($field) ?: null;
                }
            }

            if (!empty($merge)) {
                $request->merge($merge);
            }
        }

        return $next($request);
    }

    private function cleanMoney(mixed $value): string
    {
        $v = trim((string) $value);
        if (str_contains($v, ',')) {
            // Formato BR: 1.500,99 → 1500.99
            $v = str_replace('.', '', $v);
            $v = str_replace(',', '.', $v);
        }
        return $v;
    }
}
