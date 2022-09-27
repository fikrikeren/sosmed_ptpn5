<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/login/images/icons/favicon.ico" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/css/main.css">
	<!--===============================================================================================-->
</head>

<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="<?php echo base_url(); ?>assets/login/images/img-01.png" alt="IMG">
				</div>

				<form class="w3-container" method="POST" action="<?php echo site_url('auth/autentikasi'); ?>">
					<span class=" login100-form-title">
						<center>
							Login
						</center>
					</span>

					<div class="wrap-input100 validate-input">
						<label for="sap">
							<input class="input100" type="text" name="sap" placeholder="sap">
							<span class="focus-input100"></span>
						</label>
					</div>

					<div class="wrap-input100 validate-input">
						<label for="password">
							<input class="input100" type="password" name="pass" placeholder="Password">
							<span class="focus-input100"></span>
						</label>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>
				</form>

				<!-- <form class="w3-container" method="POST" action="<?php echo site_url('auth/autentikasi'); ?>">
					<p>
						<label class="w3-text-blue"><b>sap</b></label>
						<input class="w3-input w3-border w3-sand" name="sap" type="sap">
					</p>
					<p>
						<label class="w3-text-blue"><b>Password</b></label>
						<input class="w3-input w3-border w3-sand" name="pass" type="password">
					</p>
					<p>
						<button class="w3-btn w3-blue">Masuk</button>
					</p>
					<div class="w3-panel w3-blue w3-display-container">
						<?php echo $this->session->flashdata('msg'); ?>
					</div>
				</form> -->
			</div>
		</div>
	</div>




	<!--===============================================================================================-->
	<script src="<?php echo base_url(); ?>assets/login/vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url(); ?>assets/login/vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo base_url(); ?>assets/login/vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url(); ?>assets/login/vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url(); ?>assets/login/vendor/tilt/tilt.jquery.min.js"></script>
	<script>
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url(); ?>assets/login/js/main.js"></script>

</body>

</html>