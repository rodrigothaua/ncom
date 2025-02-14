<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Processo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessoService
{
    public function getProcessos()
    {
        return Processo::all();
    }

    public function getTotais()
    {
        // Contar os processos com base nos valores individuais
        $totalConsumo = Processo::whereNotNull('valor_consumo')->count();
        $totalPermanente = Processo::whereNotNull('valor_permanente')->count();
        $totalServico = Processo::whereNotNull('valor_servico')->count();

        // Somar os valores de cada tipo
        $valorConsumo = Processo::whereNotNull('valor_consumo')->sum('valor_consumo');
        $valorPermanente = Processo::whereNotNull('valor_permanente')->sum('valor_permanente');
        $valorServico = Processo::whereNotNull('valor_servico')->sum('valor_servico');

        return [
            'valorTotal' => Processo::sum('valor_total'),
            'totalConsumo' => $totalConsumo,
            'totalPermanente' => $totalPermanente,
            'totalServico' => $totalServico,
            'totalProcessos' => Processo::count(), // Total de processos cadastrados no banco
            'valorConsumo' => $valorConsumo,
            'valorPermanente' => $valorPermanente,
            'valorServico' => $valorServico
        ];
    }

    public function getTotaisPorCategoria()
    {
        $consumo = Processo::where('categoria', 'Consumo')->count();
        $permanente = Processo::where('categoria', 'Permanente')->count();
        $servico = Processo::where('categoria', 'Serviço')->count();

        return [
            'consumo' => $consumo,
            'permanente' => $permanente,
            'servico' => $servico,
        ];
    }

    private function gerarCores($quantidade)
    {
        $cores = [
            '#198754', '#FFC107', '#DC3545', '#0D6EFD', '#6F42C1', '#FF5733', '#28A745'
        ];

        // Se a quantidade de categorias for maior que o número de cores definidas, repete as cores
        return array_merge(array_slice($cores, 0, $quantidade), array_fill(0, max(0, $quantidade - count($cores)), '#6C757D'));
    }


    public function getVencimentos()
    {
        $hoje = Carbon::today();
        return [
            'totalMenos30Dias' => Processo::where('data_vencimento', '<', $hoje->copy()->subDays(30))->count(),
            'totalEntre30e60Dias' => Processo::whereBetween('data_vencimento', [$hoje->copy()->addDays(30), $hoje->copy()->addDays(60)])->count(),
            'totalEntre60e90Dias' => Processo::whereBetween('data_vencimento', [$hoje->copy()->addDays(60), $hoje->copy()->addDays(90)])->count(),
            'totalEntre90e180Dias' => Processo::whereBetween('data_vencimento', [$hoje->copy()->addDays(90), $hoje->copy()->addDays(180)])->count(),
            'totalMais180Dias' => Processo::where('data_vencimento', '>', $hoje->copy()->addDays(180))->count(),
        ];
    }

    public function getRequisitantesENumerosProcessos()
    {
        return [
            'numerosProcessos' => \DB::table('processos')
                ->select('numero_processo')
                ->distinct()
                ->get(),
            
            'requisitantes' => \DB::table('processos')
                ->select('requisitante')
                ->distinct()
                ->get(),
        ];
    }

    public function getProcessosPorAno()
    {
        $processosPorAno = DB::table('processos')
            ->selectRaw('YEAR(data_inicio) as ano, COUNT(*) as total')
            ->groupBy('ano')
            ->orderBy('ano', 'asc')
            ->get();

        return [
            'labels' => $processosPorAno->pluck('ano')->toArray(), // Pega os anos
            'data' => $processosPorAno->pluck('total')->toArray()  // Pega os totais
        ];
    }

    public function getGraficoMensal()
    {
        $primeiroMes = DB::table('processos')->min('data_inicio');
        $ultimoMes = DB::table('processos')->max('data_inicio');
    
        if (!$primeiroMes || !$ultimoMes) {
            return [
                'labelsBarVertical' => [],
                'dataBarVertical' => [],
                'mediaEixoYBarVertical' => 0
            ];
        }
    
        $inicio = Carbon::parse($primeiroMes)->startOfMonth();
        $fim = Carbon::parse($ultimoMes)->endOfMonth();
    
        $periodo = Carbon::parse($inicio);
        $labelsBarVertical = [];
        $dataBarVertical = [];
    
        while ($periodo->lte($fim)) {
            // Nome do mês (ex.: Janeiro 2025)
            $labelsBarVertical[] = $periodo->translatedFormat('F Y');
    
            // Total dos processos para o mês atual
            $totalMensal = DB::table('processos')
                ->whereYear('data_inicio', $periodo->year)
                ->whereMonth('data_inicio', $periodo->month)
                ->sum('valor_total');
    
            $dataBarVertical[] = $totalMensal ?: 0; // Garantir que meses sem dados sejam 0
            $periodo->addMonth(); // Ir para o próximo mês
        }
    
        // Calcular a média do eixo Y
        $maxValor = !empty($dataBarVertical) ? max($dataBarVertical) : 0;
        $mediaEixoYBarVertical = $maxValor > 0 ? ceil($maxValor / 5) : 1;
    
        return [
            'labelsBarVertical' => $labelsBarVertical,
            'dataBarVertical' => $dataBarVertical,
            'mediaEixoYBarVertical' => $mediaEixoYBarVertical
        ];
    }

    public function getFiltro(Request $request)
    {
        $query = Processo::query();

        // Filtrando pelo valor de consumo, permanente ou serviço
        if ($request->has('valor_consumo') && $request->valor_consumo) {
            $query->whereNotNull('valor_consumo');
        }

        if ($request->has('valor_permanente') && $request->valor_permanente) {
            $query->whereNotNull('valor_permanente');
        }

        if ($request->has('valor_servico') && $request->valor_servico) {
            $query->whereNotNull('valor_servico');
        }

        // Retornar os resultados filtrados
        return $query->get();
    }
}
