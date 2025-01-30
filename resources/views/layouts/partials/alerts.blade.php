<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
    @if (session('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sucesso!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erro!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<script>
    // Remove automaticamente após 5 segundos
    setTimeout(() => {
        let successAlert = document.getElementById('successAlert');
        let errorAlert = document.getElementById('errorAlert');
        
        if (successAlert) {
            successAlert.classList.remove('show');
            successAlert.classList.add('fade');
            setTimeout(() => successAlert.remove(), 500);
        }

        if (errorAlert) {
            errorAlert.classList.remove('show');
            errorAlert.classList.add('fade');
            setTimeout(() => errorAlert.remove(), 500);
        }
    }, 5000);
</script>
