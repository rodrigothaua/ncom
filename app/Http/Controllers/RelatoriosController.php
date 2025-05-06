<?php

namespace App\Http\Controllers;

use App\Models\Processo;
use App\Models\Contrato;
use App\Models\Categorias;
use App\Models\DetalhesDespesa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RelatoriosController extends Controller
{
    public function index()
    {
        return view('relatorios.index');
    }

    public function filtroGeral(Request $request)
    {
        $tipo = $request->get('tipo', 'processo');
        $dados = [
            'requisitantes' => Processo::distinct('requisitante')->pluck('requisitante'),
            'modalidades' => Processo::distinct('modalidade')->whereNotNull('modalidade')->pluck('modalidade'),
            'procedimentos' => Processo::distinct('procedimentos_auxiliares')->whereNotNull('procedimentos_auxiliares')->pluck('procedimentos_auxiliares'),
            'tipo' => $tipo
        ];

        return view('relatorios.filtro_geral', $dados);
    }

    public function buscarFiltroGeral(Request $request)
    {
        $query = Processo::with(['contratos', 'categorias', 'categorias.detalhesDespesa']);
        $query = $this->applyFilters($query, $request);
        $resultados = $query->get();
        $tipo = $request->get('tipo', 'processo');

        return view('relatorios.filtro_geral', [
            'resultados' => $resultados,
            'requisitantes' => Processo::distinct('requisitante')->pluck('requisitante'),
            'modalidades' => Processo::distinct('modalidade')->whereNotNull('modalidade')->pluck('modalidade'),
            'procedimentos' => Processo::distinct('procedimentos_auxiliares')->whereNotNull('procedimentos_auxiliares')->pluck('procedimentos_auxiliares'),
            'tipo' => $tipo,
            'filtros' => $request->all() // Passa os filtros aplicados para a view
        ]);
    }

    public function contratosPorVencimento()
    {
        return view('relatorios.contratos_vencimento');
    }

    public function buscarContratosPorVencimento(Request $request)
    {
        $query = Contrato::with(['processo']);

        if ($request->filled(['data_inicio', 'data_fim'])) {
            $query->where(function($q) use ($request) {
                $q->where('data_inicial_contrato', '<=', $request->data_fim)
                  ->where('data_final_contrato', '>=', $request->data_inicio);
            });
        }

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
        $valores = [
            'consumo' => ['min' => 0, 'max' => Categorias::max('valor_consumo')],
            'permanente' => ['min' => 0, 'max' => Categorias::max('valor_permanente')],
            'servico' => ['min' => 0, 'max' => Categorias::max('valor_servico')]
        ];

        $pas = DetalhesDespesa::distinct()
            ->whereNotNull('pa_consumo')
            ->orWhereNotNull('pa_permanente')
            ->orWhereNotNull('pa_servico')
            ->get()
            ->map(function($item) {
                return [
                    $item->pa_consumo,
                    $item->pa_permanente,
                    $item->pa_servico
                ];
            })
            ->flatten()
            ->unique()
            ->filter();

        $nds = DetalhesDespesa::distinct()
            ->whereNotNull('nd_consumo')
            ->orWhereNotNull('nd_permanente')
            ->orWhereNotNull('nd_servico')
            ->get()
            ->map(function($item) {
                return [
                    $item->nd_consumo,
                    $item->nd_permanente,
                    $item->nd_servico
                ];
            })
            ->flatten()
            ->unique()
            ->filter();

        return view('relatorios.categorias_processo', compact('valores', 'pas', 'nds'));
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
        $ndFields = ['nd_consumo', 'nd_permanente', 'nd_servico'];

        foreach ($paFields as $field) {
            if ($request->filled($field)) {
                $query->whereHas('categorias.detalhesDespesa', function($q) use ($field, $request) {
                    $q->where($field, $request->$field);
                });
            }
        }

        foreach ($ndFields as $field) {
            if ($request->filled($field)) {
                $query->whereHas('categorias.detalhesDespesa', function($q) use ($field, $request) {
                    $q->where($field, $request->$field);
                });
            }
        }

        $processos = $query->get();

        // Recupera os valores para os selects
        $valores = [
            'consumo' => ['min' => 0, 'max' => Categorias::max('valor_consumo')],
            'permanente' => ['min' => 0, 'max' => Categorias::max('valor_permanente')],
            'servico' => ['min' => 0, 'max' => Categorias::max('valor_servico')]
        ];

        $pas = DetalhesDespesa::distinct()
            ->whereNotNull('pa_consumo')
            ->orWhereNotNull('pa_permanente')
            ->orWhereNotNull('pa_servico')
            ->get()
            ->map(function($item) {
                return [
                    $item->pa_consumo,
                    $item->pa_permanente,
                    $item->pa_servico
                ];
            })
            ->flatten()
            ->unique()
            ->filter();

        $nds = DetalhesDespesa::distinct()
            ->whereNotNull('nd_consumo')
            ->orWhereNotNull('nd_permanente')
            ->orWhereNotNull('nd_servico')
            ->get()
            ->map(function($item) {
                return [
                    $item->nd_consumo,
                    $item->nd_permanente,
                    $item->nd_servico
                ];
            })
            ->flatten()
            ->unique()
            ->filter();

        return view('relatorios.categorias_processo', compact('processos', 'valores', 'pas', 'nds'));
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

    public function gerarPdfFiltroGeral(Request $request)
    {
        $request->validate([
            'processos' => 'required|array|min:1',
            'processos.*' => 'exists:processos,id'
        ]);

        $processosSelecionados = Processo::with(['contratos', 'categorias', 'categorias.detalhesDespesa'])
            ->whereIn('id', $request->processos)
            ->get();

        $tipo = $request->get('tipo', 'processo');

        $pdf = PDF::loadView('relatorios.pdf.filtro_geral', [
            'resultados' => $processosSelecionados,
            'titulo' => 'Relatório de Filtro Geral',
            'data_geracao' => Carbon::now()->format('d/m/Y H:i:s'),
            'tipo' => $tipo
        ]);

        return $pdf->download('relatorio_filtro_geral.pdf');
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
