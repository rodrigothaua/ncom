<!-- resources/views/dashboard.blade.php -->
@include('../layouts.navbar')

@section('content')
    <div class="container">
        <h1 class="my-4">Bem-vindo ao Dashboard</h1>

        <!-- Content Row -->
        <div class="row">

            <!-- Card: Total de Processos -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total de Processos</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProcessos ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-folder fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Processos Vencidos -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Processos Vencidos</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $processosVencidos ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Processos Ativos -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Processos Ativos</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $processosAtivos ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Processos Pendentes -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Processos Pendentes</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $processosPendentes ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <strong>Resumo de processos</strong>
                <canvas id="processosPieChart"></canvas>
            </div>
        </div>
    </div>
@endsection
