<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo isset($title) ? $title : 'Login - Sistem Notelen BHT'; ?></title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/fontawesome-free/css/all.min.css'); ?>">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url('assets/dist/css/adminlte.min.css'); ?>">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

	<style>
		.login-page {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			min-height: 100vh;
		}

		.card-primary.card-outline {
			border-top: 3px solid #007bff;
		}

		.login-logo {
			color: white;
			text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
		}

		.login-logo a {
			color: white;
			text-decoration: none;
		}

		.login-logo a:hover {
			color: #f8f9fa;
		}

		.card {
			box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
			border-radius: 10px;
		}

		.btn-primary {
			background: linear-gradient(45deg, #007bff, #0056b3);
			border: none;
			border-radius: 25px;
		}

		.btn-primary:hover {
			background: linear-gradient(45deg, #0056b3, #004085);
		}

		.form-control {
			border-radius: 25px;
			border: 2px solid #e9ecef;
		}

		.form-control:focus {
			border-color: #007bff;
			box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
		}

		.input-group-text {
			border-radius: 25px 0 0 25px;
			border: 2px solid #e9ecef;
			border-right: none;
		}

		.user-info-card {
			background: rgba(255, 255, 255, 0.1);
			border: 1px solid rgba(255, 255, 255, 0.2);
			border-radius: 10px;
			margin-bottom: 20px;
		}

		.user-info-card .card-body {
			padding: 15px;
		}

		.demo-login-btn {
			font-size: 12px;
			padding: 5px 10px;
			margin: 2px;
			border-radius: 15px;
		}

		.alert {
			border-radius: 10px;
		}
	</style>
</head>

<body class="hold-transition login-page">
	<div class="login-box">
		<!-- /.login-logo -->
		<div class="login-logo">
			<a href="<?php echo base_url(); ?>">
				<i class="fas fa-gavel"></i>
				<b>Sistem</b> Notelen BHT
			</a>
		</div>

		<!-- Demo User Info Cards -->
		<div class="user-info-card">
			<div class="card-body text-white">
				<h6 class="card-title mb-2">
					<i class="fas fa-info-circle"></i> Demo Login
				</h6>
				<div class="row">
					<div class="col-4">
						<button type="button" class="btn btn-sm btn-outline-light demo-login-btn"
							onclick="fillDemoLogin('admin', 'password')">
							<i class="fas fa-user-shield"></i> Admin
						</button>
					</div>
					<div class="col-4">
						<button type="button" class="btn btn-sm btn-outline-light demo-login-btn"
							onclick="fillDemoLogin('staff1', 'password')">
							<i class="fas fa-user"></i> Staff
						</button>
					</div>
					<div class="col-4">
						<button type="button" class="btn btn-sm btn-outline-light demo-login-btn"
							onclick="fillDemoLogin('panitera1', 'password')">
							<i class="fas fa-user-tie"></i> Panitera
						</button>
					</div>
				</div>
				<small class="text-light">
					<i class="fas fa-key"></i> Password untuk semua: <code>password</code>
				</small>
			</div>
		</div>

		<!-- /.card -->
		<div class="card card-outline card-primary">
			<div class="card-header text-center">
				<h4 class="h5 mb-0">
					<i class="fas fa-sign-in-alt"></i>
					Masuk ke Sistem
				</h4>
			</div>
			<div class="card-body">
				<!-- Flash Messages -->
				<?php if (isset($error_message) && !empty($error_message)): ?>
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<i class="icon fas fa-ban"></i> <?php echo $error_message; ?>
					</div>
				<?php endif; ?>

				<?php if (isset($success_message) && !empty($success_message)): ?>
					<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<i class="icon fas fa-check"></i> <?php echo $success_message; ?>
					</div>
				<?php endif; ?>

				<p class="login-box-msg">Silakan masuk untuk mengakses sistem</p>

				<form action="<?php echo base_url('auth/login'); ?>" method="post" id="loginForm">

					<div class="input-group mb-3">
						<input type="text" class="form-control" name="username" id="username"
							placeholder="Username" required autocomplete="username">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
					</div>

					<div class="input-group mb-3">
						<input type="password" class="form-control" name="password" id="password"
							placeholder="Password" required autocomplete="current-password">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-8">
							<div class="icheck-primary">
								<input type="checkbox" id="remember" name="remember_me" value="1">
								<label for="remember">
									Ingat saya
								</label>
							</div>
						</div>
						<!-- /.col -->
						<div class="col-4">
							<button type="submit" class="btn btn-primary btn-block" id="btnLogin">
								<i class="fas fa-sign-in-alt"></i> Masuk
							</button>
						</div>
						<!-- /.col -->
					</div>
				</form>

				<!-- AJAX Login Button (alternative) -->
				<div class="mt-3 text-center">
					<button type="button" class="btn btn-outline-secondary btn-sm" id="btnAjaxLogin">
						<i class="fas fa-bolt"></i> Login dengan AJAX
					</button>
				</div>

				<div class="mt-4 text-center">
					<small class="text-muted">
						<i class="fas fa-shield-alt"></i>
						Sistem keamanan aktif. Semua aktivitas dicatat.
					</small>
				</div>
			</div>
			<!-- /.card-body -->
		</div>
		<!-- /.card -->
	</div>
	<!-- /.login-box -->

	<!-- jQuery -->
	<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
	<!-- Bootstrap 4 -->
	<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo base_url('assets/dist/js/adminlte.min.js'); ?>"></script>
	<!-- SweetAlert2 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
		$(document).ready(function() {
			// Auto dismiss alerts after 5 seconds
			setTimeout(function() {
				$('.alert').fadeOut();
			}, 5000);

			// Focus on username field
			$('#username').focus();

			// Enhanced form submission with loading
			$('#loginForm').on('submit', function() {
				const btnLogin = $('#btnLogin');
				const originalText = btnLogin.html();

				btnLogin.prop('disabled', true)
					.html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

				// Re-enable button if page doesn't redirect (error case)
				setTimeout(function() {
					btnLogin.prop('disabled', false).html(originalText);
				}, 5000);
			});

			// AJAX Login demonstration
			$('#btnAjaxLogin').on('click', function() {
				const username = $('#username').val();
				const password = $('#password').val();

				if (!username || !password) {
					Swal.fire({
						title: 'Perhatian',
						text: 'Silakan isi username dan password terlebih dahulu',
						icon: 'warning'
					});
					return;
				}

				const btn = $(this);
				const originalText = btn.html();

				btn.prop('disabled', true)
					.html('<i class="fas fa-spinner fa-spin"></i> Login...');

				$.ajax({
					url: '<?php echo base_url('auth/ajax_login'); ?>',
					type: 'POST',
					data: {
						username: username,
						password: password
					},
					dataType: 'json',
					success: function(response) {
						if (response.success) {
							Swal.fire({
								title: 'Berhasil!',
								text: response.message,
								icon: 'success',
								showConfirmButton: false,
								timer: 1500
							}).then(function() {
								window.location.href = response.redirect;
							});
						} else {
							Swal.fire({
								title: 'Gagal!',
								text: response.message,
								icon: 'error'
							});
						}
					},
					error: function() {
						Swal.fire({
							title: 'Error!',
							text: 'Terjadi kesalahan koneksi',
							icon: 'error'
						});
					},
					complete: function() {
						btn.prop('disabled', false).html(originalText);
					}
				});
			});
		});

		// Fill demo login credentials
		function fillDemoLogin(username, password) {
			$('#username').val(username);
			$('#password').val(password);

			// Optional: Auto submit
			Swal.fire({
				title: 'Demo Login',
				text: `Login sebagai ${username}?`,
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, Login!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$('#loginForm').submit();
				}
			});
		}

		// Keyboard shortcut - Enter to submit
		$(document).on('keypress', function(e) {
			if (e.which === 13) { // Enter key
				$('#loginForm').submit();
			}
		});
	</script>
</body>

</html>