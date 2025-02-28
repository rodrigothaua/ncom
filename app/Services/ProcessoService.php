<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Processo;
use App\Models\Categorias;
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
        // Busca os valores da tabela categorias
        $valorConsumo = Categorias::sum('valor_consumo');
        $valorPermanente = Categorias::sum('valor_permanente');
        $valorServico = Categorias::sum('valor_servico');

        // Calcula o valor total somando as categorias
        $valorTotal = $valorConsumo + $valorPermanente + $valorServico;

        return [
            'valorTotal' => $valorTotal,
            'totalProcessos' => Processo::count(),
            'valorConsumo' => $valorConsumo,
            'valorPermanente' => $valorPermanente,
            'valorServico' => $valorServico
        ];
    }

    public function getTotaisPorCategoria()
    {
        $categorias = Processo::distinct('categoria')->pluck('categoria');
        $totais = [];

        foreach ($categorias as $categoria) {
            $totais[$categoria] = Processo::where('categoria', $categoria)->count();
        }

        return $totais;
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

        if ($request->has('requisitante') && $request->requisitante) {
            $query->where('requisitante', 'like', '%' . $request->requisitante . '%');
        }

        if ($request->has('numero_processo') && $request->numero_processo) {
            $query->where('numero_processo', $request->numero_processo);
        }

        if ($request->has('categoria') && $request->categoria) {
            $query->where('categoria', $request->categoria);
        }

        return $query->get();
    }
}
