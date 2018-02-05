<?php require 'pages/header.php'; ?>

		<div class="container">
			<h1>Login</h1>

			<?php 
			require 'classes/users.class.php';
			$u = new Users();
			if(
			(isset($_POST['email']) && !empty($_POST['email'])) &&
			(isset($_POST['password']) && !empty($_POST['password']))
			){
				$email = addslashes($_POST['email']);
				$password = $_POST['password'];

				if($u->login($email, $password)){
					?>
					<script type="text/javascript">
						window.location.href="./";
					</script>
					<?php
				} else {
					?>
					<div class="alert alert-danger">
						User and/or password invalid.
					</div>
					<?php
				}
			}
			?>

			<form method="POST">
				<div class="form-group">
					<label for="email">E-mail:</label>
					<input type="email" name="email" id="email" class="form-control">
				</div>
				<div class="form-group">
					<label for="password">Password:</label>
					<input type="password" name="password" id="password" class="form-control">
				</div>
				<input type="submit" value="login" class="btn btn-default">
			</form>
		</div>

<?php require 'pages/footer.php'; ?>