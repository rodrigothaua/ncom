@extends('layouts.app')

@section('title', 'Documentação da API - SIGECOM')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-dark">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Documentação da API</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Autenticação</h6>
                    <p>Para utilizar a API, você precisa incluir um token de autenticação em todas as requisições:</p>
                    <pre class="bg-light p-3 rounded"><code>Authorization: Bearer seu-token-aqui</code></pre>

                    <hr>

                    <h6 class="fw-bold mt-4">Endpoints Disponíveis</h6>

                    <!-- Processos -->
                    <div class="mt-4">
                        <h6 class="text-primary">Processos</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Método</th>
                                        <th>Endpoint</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td><code>/api/processos</code></td>
                                        <td>Lista todos os processos</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td><code>/api/processos/{id}</code></td>
                                        <td>Retorna um processo específico</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-primary">POST</span></td>
                                        <td><code>/api/processos</code></td>
                                        <td>Cria novo processo</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-warning text-dark">PUT</span></td>
                                        <td><code>/api/processos/{id}</code></td>
                                        <td>Atualiza um processo</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-danger">DELETE</span></td>
                                        <td><code>/api/processos/{id}</code></td>
                                        <td>Remove um processo</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td><code>/api/processos/totais</code></td>
                                        <td>Retorna totais dos processos</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td><code>/api/processos/vencimentos</code></td>
                                        <td>Retorna vencimentos dos contratos</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td><code>/api/processos/contratos-por-ano</code></td>
                                        <td>Estatísticas de contratos por ano</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Categorias -->
                    <div class="mt-4">
                        <h6 class="text-primary">Categorias</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Método</th>
                                        <th>Endpoint</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td><code>/api/categorias</code></td>
                                        <td>Lista todas as categorias</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td><code>/api/categorias/{id}</code></td>
                                        <td>Retorna uma categoria específica</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-primary">POST</span></td>
                                        <td><code>/api/categorias</code></td>
                                        <td>Cria nova categoria</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-warning text-dark">PUT</span></td>
                                        <td><code>/api/categorias/{id}</code></td>
                                        <td>Atualiza uma categoria</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-danger">DELETE</span></td>
                                        <td><code>/api/categorias/{id}</code></td>
                                        <td>Remove uma categoria</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Contratos -->
                    <div class="mt-4">
                        <h6 class="text-primary">Contratos</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Método</th>
                                        <th>Endpoint</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td><code>/api/contratos</code></td>
                                        <td>Lista todos os contratos</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td><code>/api/contratos/{id}</code></td>
                                        <td>Retorna um contrato específico</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-primary">POST</span></td>
                                        <td><code>/api/contratos</code></td>
                                        <td>Cria novo contrato</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-warning text-dark">PUT</span></td>
                                        <td><code>/api/contratos/{id}</code></td>
                                        <td>Atualiza um contrato</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-danger">DELETE</span></td>
                                        <td><code>/api/contratos/{id}</code></td>
                                        <td>Remove um contrato</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td><code>/api/contratos/estatisticas</code></td>
                                        <td>Retorna estatísticas dos contratos</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Detalhes de Despesas -->
                    <div class="mt-4">
                        <h6 class="text-primary">Detalhes de Despesas</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Método</th>
                                        <th>Endpoint</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td><code>/api/detalhes-despesas</code></td>
                                        <td>Lista todos os detalhes de despesas</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-success">GET</span></td>
                                        <td><code>/api/detalhes-despesas/{id}</code></td>
                                        <td>Retorna um detalhe específico</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-primary">POST</span></td>
                                        <td><code>/api/detalhes-despesas</code></td>
                                        <td>Cria novo detalhe de despesa</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-warning text-dark">PUT</span></td>
                                        <td><code>/api/detalhes-despesas/{id}</code></td>
                                        <td>Atualiza um detalhe de despesa</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-danger">DELETE</span></td>
                                        <td><code>/api/detalhes-despesas/{id}</code></td>
                                        <td>Remove um detalhe de despesa</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mt-4">Formato das Respostas</h6>
                    <p>Todas as respostas da API seguem o seguinte formato:</p>
                    <pre class="bg-light p-3 rounded"><code>{
    "status": true/false,
    "data": {...} // Em caso de sucesso
    "message": "mensagem de erro", // Em caso de erro
    "error": "detalhes do erro" // Em caso de erro
}</code></pre>

                    <h6 class="fw-bold mt-4">Códigos de Status HTTP</h6>
                    <ul class="list-unstyled">
                        <li><span class="badge bg-success">200</span> Sucesso</li>
                        <li><span class="badge bg-primary">201</span> Criação bem-sucedida</li>
                        <li><span class="badge bg-warning text-dark">404</span> Recurso não encontrado</li>
                        <li><span class="badge bg-danger">422</span> Erro de validação</li>
                        <li><span class="badge bg-danger">500</span> Erro interno do servidor</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
