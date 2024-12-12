<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Ncom</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4">Cadastro</h2>
            <form action="/register" method="POST">
                <!-- Adicione o token CSRF no Laravel -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <!-- Nome Completo -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nome Completo</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Digite seu nome completo" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Digite seu email" required>
                </div>

                <!-- Senha -->
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha" required>
                </div>

                <!-- Confirmação de Senha -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirme sua Senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirme sua senha" required>
                </div>

                <!-- Botão de Cadastro -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Cadastrar</button>
                </div>
            </form>

            <!-- Link para Login -->
            <div class="text-center mt-3">
                <small>Já tem uma conta? <a href="/login" class="text-decoration-none">Faça login</a></small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
