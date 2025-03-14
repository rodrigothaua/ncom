@extends('layouts.login-layout')

@section('title', 'NCOM - Login')

@section('content')
<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-sm-center h-100">
				<div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
					<div class="text-center my-5">
						<img src="https://getbootstrap.com/docs/5.0/assets/brand/bootstrap-logo.svg" alt="logo" width="100">
					</div>
					<div class="card shadow-lg">
						<div class="card-body p-5">
							<h1 class="fs-4 card-title fw-bold mb-4">Login</h1>
							<form action="{{ route('login') }}" method="POST">
                @csrf
								<div class="mb-3">
									<label class="mb-2 text-muted" for="email">E-mail</label>
									<input id="email" type="email" class="form-control" id="email" name="email" value="" required autofocus>
									<div class="invalid-feedback">
										Email is invalid
									</div>
								</div>

								<div class="mb-3">
									<div class="mb-2 w-100">
										<label class="text-muted" for="password">Senha</label>
										<a href="forgot.html" class="float-end">
											Esqueceu a senha?
										</a>
									</div>
									<input id="password" type="password" class="form-control" name="password" id="password" required>
								    <div class="invalid-feedback">
								    	Password is required
							    	</div>
								</div>

								<div class="d-flex align-items-center">
									<div class="form-check">
										<input type="checkbox" name="remember" id="remember" class="form-check-input">
										<label for="remember" class="form-check-label">Lembrar-me</label>
									</div>
									<button type="submit" class="btn btn-primary ms-auto">
										Login
									</button>
								</div>
							</form>
						</div>
						<div class="card-footer py-3 border-0">
							<div class="text-center">
								Não tem conta? <a href="/register" class="text-dark">Solicite uma</a>
							</div>
						</div>
					</div>
					<div class="text-center mt-5 text-muted">
						Copyright &copy; 2025 &mdash; NCOM
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection