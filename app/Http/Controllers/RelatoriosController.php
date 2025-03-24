<?php

namespace App\Http\Controllers;

use App\Models\Processo;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RelatoriosController extends Controller
{
    public function index()
    {
        return view('relatorios.index');
    }

    public function filtroGeral()
    {
        $requisitantes = Processo::distinct('requisitante')->pluck('requisitante');
        $modalidades = Processo::distinct('modalidade')->whereNotNull('modalidade')->pluck('modalidade');
        $procedimentos = Processo::distinct('procedimentos_auxiliares')->whereNotNull('procedimentos_auxiliares')->pluck('procedimentos_auxiliares');

        return view('relatorios.filtro_geral', compact('requisitantes', 'modalidades', 'procedimentos'));
    }

    public function buscarFiltroGeral(Request $request)
    {
        $query = Processo::with(['contratos', 'categorias', 'categorias.detalhesDespesa']);
        $query = $this->applyFilters($query, $request);
        $resultados = $query->get();

        return view('relatorios.filtro_geral', [
            'resultados' => $resultados,
            'requisitantes' => Processo::distinct('requisitante')->pluck('requisitante'),
            'modalidades' => Processo::distinct('modalidade')->whereNotNull('modalidade')->pluck('modalidade'),
            'procedimentos' => Processo::distinct('procedimentos_auxiliares')->whereNotNull('procedimentos_auxiliares')->pluck('procedimentos_auxiliares')
        ]);
    }

    public function contratosPorVencimento()
    {
        return view('relatorios.contratos_vencimento');
    }

    public function buscarContratosPorVencimento(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio'
        ]);

        $query = Contrato::with(['processo']);

        $query->where(function($q) use ($request) {
            $q->where('data_inicial_contrato', '<=', $request->data_fim)
              ->where('data_final_contrato', '>=', $request->data_inicio);
        });

        $query->orderBy('data_final_contrato', 'asc');
        $contratos = $query->get();

        return view('relatorios.contratos_vencimento', compact('contratos'));
    }

    public function contratosPorValor()
    {
        return view('relatorios.contratos_valor');
    }

    public function buscarContratosPorValor(Request $request)
    {
        $query = Contrato::with(['processo']);

        if ($request->filled('valor_min')) {
            $valorMin = str_replace(['R$', '.', ','], ['', '', '.'], $request->valor_min);
            $query->where('valor_contrato', '>=', floatval($valorMin));
        }

        if ($request->filled('valor_max')) {
            $valorMax = str_replace(['R$', '.', ','], ['', '', '.'], $request->valor_max);
            $query->where('valor_contrato', '<=', floatval($valorMax));
        }

        $query->orderBy('valor_contrato', 'desc');
        $contratos = $query->get();

        return view('relatorios.contratos_valor', compact('contratos'));
    }

    public function categoriasPorProcesso()
    {
        return view('relatorios.categorias_processo');
    }

    public function buscarCategoriasPorProcesso(Request $request)
    {
        $query = Processo::with(['categorias', 'categorias.detalhesDespesa']);

        if ($request->filled(['valor_consumo_min', 'valor_consumo_max'])) {
            $valorMin = str_replace(['R$', '.', ','], ['', '', '.'], $request->valor_consumo_min);
            $valorMax = str_replace(['R$', '.', ','], ['', '', '.'], $request->valor_consumo_max);
            $query->whereHas('categorias', function($q) use ($valorMin, $valorMax) {
                $q->whereBetween('valor_consumo', [floatval($valorMin), floatval($valorMax)]);
            });
        }

        if ($request->filled(['valor_permanente_min', 'valor_permanente_max'])) {
            $valorMin = str_replace(['R$', '.', ','], ['', '', '.'], $request->valor_permanente_min);
            $valorMax = str_replace(['R$', '.', ','], ['', '', '.'], $request->valor_permanente_max);
            $query->whereHas('categorias', function($q) use ($valorMin, $valorMax) {
                $q->whereBetween('valor_permanente', [floatval($valorMin), floatval($valorMax)]);
            });
        }

        if ($request->filled(['valor_servico_min', 'valor_servico_max'])) {
            $valorMin = str_replace(['R$', '.', ','], ['', '', '.'], $request->valor_servico_min);
            $valorMax = str_replace(['R$', '.', ','], ['', '', '.'], $request->valor_servico_max);
            $query->whereHas('categorias', function($q) use ($valorMin, $valorMax) {
                $q->whereBetween('valor_servico', [floatval($valorMin), floatval($valorMax)]);
            });
        }

        $paFields = ['pa_consumo', 'pa_permanente', 'pa_servico'];
        foreach ($paFields as $field) {
            if ($request->filled($field)) {
                $query->whereHas('categorias.detalhesDespesa', function($q) use ($field, $request) {
                    $q->where($field, $request->$field);
                });
            }
        }

        $ndFields = ['nd_consumo', 'nd_permanente', 'nd_servico'];
        foreach ($ndFields as $field) {
            if ($request->filled($field)) {
                $query->whereHas('categorias.detalhesDespesa', function($q) use ($field, $request) {
                    $q->where($field, $request->$field);
                });
            }
        }

        $processos = $query->get();

        return view('relatorios.categorias_processo', compact('processos'));
    }

    public function gerarPdfContratosSelecionados(Request $request)
    {
        $request->validate([
            'contratos' => 'required|array|min:1',
            'contratos.*' => 'exists:contratos,id'
        ]);

        $contratos = Contrato::with(['processo'])
            ->whereIn('id', $request->contratos)
            ->orderBy('data_final_contrato', 'asc')
            ->get();

        $pdf = PDF::loadView('relatorios.pdf.contratos_selecionados', [
            'contratos' => $contratos,
            'titulo' => 'Relatório de Contratos Selecionados',
            'data_geracao' => Carbon::now()->format('d/m/Y H:i:s')
        ]);

        return $pdf->download('contratos_selecionados.pdf');
    }

    public function gerarPdfProcessosSelecionados(Request $request)
    {
        $request->validate([
            'processos' => 'required|array|min:1',
            'processos.*' => 'exists:processos,id'
        ]);

        $processos = Processo::with(['categorias', 'categorias.detalhesDespesa', 'contratos'])
            ->whereIn('id', $request->processos)
            ->orderBy('data_entrada', 'desc')
            ->get();

        $pdf = PDF::loadView('relatorios.pdf.processos_selecionados', [
            'processos' => $processos,
            'titulo' => 'Relatório de Processos Selecionados',
            'data_geracao' => Carbon::now()->format('d/m/Y H:i:s')
        ]);

        return $pdf->download('processos_selecionados.pdf');
    }

    protected function applyFilters($query, Request $request)
    {
        if ($request->filled('numero_processo')) {
            $query->where('numero_processo', $request->numero_processo);
        }

        if ($request->filled('requisitante')) {
            $query->where('requisitante', $request->requisitante);
        }

        if ($request->filled('data_inicio')) {
            $query->where('data_entrada', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('data_entrada', '<=', $request->data_fim);
        }

        if ($request->filled('modalidade')) {
            $query->where('modalidade', $request->modalidade);
        }

        if ($request->filled('procedimentos')) {
            $query->where('procedimentos_auxiliares', $request->procedimentos);
        }

        return $query;
    }
}
