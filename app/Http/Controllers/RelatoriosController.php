<?php

namespace App\Http\Controllers;

use App\Models\Processo;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatoriosController extends Controller
{
    protected function applyFilters($query, Request $request, $isProcesso = false)
    {
        if ($request->filled('data_inicio')) {
            if ($isProcesso) {
                $query->whereHas('contratos', function($q) use ($request) {
                    $q->where('data_inicial_contrato', '>=', $request->data_inicio);
                });
            } else {
                $query->where('data_inicial_contrato', '>=', $request->data_inicio);
            }
        }
        
        if ($request->filled('data_fim')) {
            if ($isProcesso) {
                $query->whereHas('contratos', function($q) use ($request) {
                    $q->where('data_final_contrato', '<=', $request->data_fim);
                });
            } else {
                $query->where('data_final_contrato', '<=', $request->data_fim);
            }
        }

        if ($request->filled('status')) {
            $hoje = now();
            $statusQuery = function($q) use ($request, $hoje) {
                switch ($request->status) {
                    case 'vencido':
                        $q->where('data_final_contrato', '<', $hoje);
                        break;
                    case 'menos30':
                        $q->whereBetween('data_final_contrato', [$hoje, $hoje->copy()->addDays(30)]);
                        break;
                    case '30a60':
                        $q->whereBetween('data_final_contrato', [$hoje->copy()->addDays(30), $hoje->copy()->addDays(60)]);
                        break;
                    case '60a90':
                        $q->whereBetween('data_final_contrato', [$hoje->copy()->addDays(60), $hoje->copy()->addDays(90)]);
                        break;
                    case '90a180':
                        $q->whereBetween('data_final_contrato', [$hoje->copy()->addDays(90), $hoje->copy()->addDays(180)]);
                        break;
                    case 'mais180':
                        $q->where('data_final_contrato', '>', $hoje->copy()->addDays(180));
                        break;
                }
            };

            if ($isProcesso) {
                $query->whereHas('contratos', $statusQuery);
            } else {
                $statusQuery($query);
            }
        }

        if ($request->filled('empresa')) {
            if ($isProcesso) {
                $query->whereHas('contratos', function($q) use ($request) {
                    $q->where('nome_empresa_contrato', 'LIKE', '%' . $request->empresa . '%');
                });
            } else {
                $query->where('nome_empresa_contrato', 'LIKE', '%' . $request->empresa . '%');
            }
        }

        if ($request->filled('cnpj')) {
            if ($isProcesso) {
                $query->whereHas('contratos', function($q) use ($request) {
                    $q->where('cnpj_contrato', 'LIKE', '%' . $request->cnpj . '%');
                });
            } else {
                $query->where('cnpj_contrato', 'LIKE', '%' . $request->cnpj . '%');
            }
        }

        if ($request->filled('valor_min')) {
            if ($isProcesso) {
                $query->whereHas('contratos', function($q) use ($request) {
                    $q->where('valor_contrato', '>=', $request->valor_min);
                });
            } else {
                $query->where('valor_contrato', '>=', $request->valor_min);
            }
        }

        if ($request->filled('valor_max')) {
            if ($isProcesso) {
                $query->whereHas('contratos', function($q) use ($request) {
                    $q->where('valor_contrato', '<=', $request->valor_max);
                });
            } else {
                $query->where('valor_contrato', '<=', $request->valor_max);
            }
        }

        if ($request->filled('modalidade')) {
            if ($isProcesso) {
                $query->where('modalidade', $request->modalidade);
            } else {
                $query->whereHas('processo', function($q) use ($request) {
                    $q->where('modalidade', $request->modalidade);
                });
            }
        }

        if ($request->filled('procedimentos')) {
            if ($isProcesso) {
                $query->where('procedimentos_auxiliares', $request->procedimentos);
            } else {
                $query->whereHas('processo', function($q) use ($request) {
                    $q->where('procedimentos_auxiliares', $request->procedimentos);
                });
            }
        }

        // Filtros para categorias
        if ($request->filled('valor_consumo_min')) {
            if ($isProcesso) {
                $query->whereHas('categorias', function($q) use ($request) {
                    $q->where('valor_consumo', '>=', $request->valor_consumo_min);
                });
            } else {
                $query->whereHas('processo.categorias', function($q) use ($request) {
                    $q->where('valor_consumo', '>=', $request->valor_consumo_min);
                });
            }
        }

        if ($request->filled('valor_consumo_max')) {
            if ($isProcesso) {
                $query->whereHas('categorias', function($q) use ($request) {
                    $q->where('valor_consumo', '<=', $request->valor_consumo_max);
                });
            } else {
                $query->whereHas('processo.categorias', function($q) use ($request) {
                    $q->where('valor_consumo', '<=', $request->valor_consumo_max);
                });
            }
        }

        if ($request->filled('valor_permanente_min')) {
            if ($isProcesso) {
                $query->whereHas('categorias', function($q) use ($request) {
                    $q->where('valor_permanente', '>=', $request->valor_permanente_min);
                });
            } else {
                $query->whereHas('processo.categorias', function($q) use ($request) {
                    $q->where('valor_permanente', '>=', $request->valor_permanente_min);
                });
            }
        }

        if ($request->filled('valor_permanente_max')) {
            if ($isProcesso) {
                $query->whereHas('categorias', function($q) use ($request) {
                    $q->where('valor_permanente', '<=', $request->valor_permanente_max);
                });
            } else {
                $query->whereHas('processo.categorias', function($q) use ($request) {
                    $q->where('valor_permanente', '<=', $request->valor_permanente_max);
                });
            }
        }

        if ($request->filled('valor_servico_min')) {
            if ($isProcesso) {
                $query->whereHas('categorias', function($q) use ($request) {
                    $q->where('valor_servico', '>=', $request->valor_servico_min);
                });
            } else {
                $query->whereHas('processo.categorias', function($q) use ($request) {
                    $q->where('valor_servico', '>=', $request->valor_servico_min);
                });
            }
        }

        if ($request->filled('valor_servico_max')) {
            if ($isProcesso) {
                $query->whereHas('categorias', function($q) use ($request) {
                    $q->where('valor_servico', '<=', $request->valor_servico_max);
                });
            } else {
                $query->whereHas('processo.categorias', function($q) use ($request) {
                    $q->where('valor_servico', '<=', $request->valor_servico_max);
                });
            }
        }

        // Filtros para números PA e ND
        if ($request->filled('pa_numero')) {
            if ($isProcesso) {
                $query->whereHas('categorias.detalhesDespesa', function($q) use ($request) {
                    $q->where('pa_consumo', 'LIKE', '%' . $request->pa_numero . '%')
                      ->orWhere('pa_permanente', 'LIKE', '%' . $request->pa_numero . '%')
                      ->orWhere('pa_servico', 'LIKE', '%' . $request->pa_numero . '%');
                });
            } else {
                $query->whereHas('processo.categorias.detalhesDespesa', function($q) use ($request) {
                    $q->where('pa_consumo', 'LIKE', '%' . $request->pa_numero . '%')
                      ->orWhere('pa_permanente', 'LIKE', '%' . $request->pa_numero . '%')
                      ->orWhere('pa_servico', 'LIKE', '%' . $request->pa_numero . '%');
                });
            }
        }

        if ($request->filled('nd_numero')) {
            if ($isProcesso) {
                $query->whereHas('categorias.detalhesDespesa', function($q) use ($request) {
                    $q->where('nd_consumo', 'LIKE', '%' . $request->nd_numero . '%')
                      ->orWhere('nd_permanente', 'LIKE', '%' . $request->nd_numero . '%')
                      ->orWhere('nd_servico', 'LIKE', '%' . $request->nd_numero . '%');
                });
            } else {
                $query->whereHas('processo.categorias.detalhesDespesa', function($q) use ($request) {
                    $q->where('nd_consumo', 'LIKE', '%' . $request->nd_numero . '%')
                      ->orWhere('nd_permanente', 'LIKE', '%' . $request->nd_numero . '%')
                      ->orWhere('nd_servico', 'LIKE', '%' . $request->nd_numero . '%');
                });
            }
        }

        return $query;
    }

    public function index()
    {
        return view('relatorios.index');
    }

    public function contratosPorVencimento(Request $request)
    {
        $query = Contrato::with([
            'processo',
            'processo.categorias',
            'processo.categorias.detalhesDespesa'
        ])->orderBy('data_final_contrato', 'asc');
            
        $query = $this->applyFilters($query, $request);
        $contratos = $query->get();
        
        if ($request->has('export_pdf')) {
            $pdf = PDF::loadView('relatorios.pdf.resultado_filtro', [
                'contratos' => $contratos,
                'filtros' => $request->all(),
                'tipo' => 'vencimento'
            ]);
            return $pdf->download('relatorio_contratos_vencimento.pdf');
        }

        return view('relatorios.contratos_vencimento', compact('contratos'));
    }

    public function contratosPorValor(Request $request)
    {
        $query = Contrato::with([
            'processo',
            'processo.categorias',
            'processo.categorias.detalhesDespesa'
        ])->orderBy('valor_contrato', 'desc');
            
        $query = $this->applyFilters($query, $request);
        $contratos = $query->get();
        
        if ($request->has('export_pdf')) {
            $pdf = PDF::loadView('relatorios.pdf.resultado_filtro', [
                'contratos' => $contratos,
                'filtros' => $request->all(),
                'tipo' => 'valor'
            ]);
            return $pdf->download('relatorio_contratos_valor.pdf');
        }

        return view('relatorios.contratos_valor', compact('contratos'));
    }

    public function categoriasPorProcesso(Request $request)
    {
        $query = Processo::with([
            'categorias',
            'contratos',
            'categorias.detalhesDespesa'
        ]);
        
        if ($request->filled('order_by')) {
            switch ($request->order_by) {
                case 'consumo':
                    $query->whereHas('categorias')->orderBy('categorias.valor_consumo', $request->order_dir ?? 'desc');
                    break;
                case 'permanente':
                    $query->whereHas('categorias')->orderBy('categorias.valor_permanente', $request->order_dir ?? 'desc');
                    break;
                case 'servico':
                    $query->whereHas('categorias')->orderBy('categorias.valor_servico', $request->order_dir ?? 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
            
        $query = $this->applyFilters($query, $request, true);
        $processos = $query->get();
        
        if ($request->has('export_pdf')) {
            $pdf = PDF::loadView('relatorios.pdf.resultado_filtro', [
                'processos' => $processos,
                'filtros' => $request->all(),
                'tipo' => 'categorias'
            ]);
            return $pdf->download('relatorio_categorias_processo.pdf');
        }

        return view('relatorios.categorias_processo', compact('processos'));
    }
}
