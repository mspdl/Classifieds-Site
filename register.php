<?php require 'pages/header.php'; ?>

		<div class="container">
			<h1>Register</h1>

			<?php 
			require 'classes/users.class.php';
			$u = new Users();
			if(
			(isset($_POST['name']) && !empty($_POST['name'])) ||
			(isset($_POST['email']) && !empty($_POST['email'])) ||
			(isset($_POST['password']) && !empty($_POST['password']))
			){
				$name = addslashes($_POST['name']);
				$email = addslashes($_POST['email']);
				$password = $_POST['name'];
				$phone = addslashes($_POST['phone']);

				if (!empty($name) && !empty($email) && !empty($password)) {
					if($u->register($name, $email, $password, $phone)) { 
						?>
						<div class="alert alert-success">
							<strong>Congratulations!</strong> User registered successfully! <a href="login.php">Login now.</a>
						</div>
						<?php
					} else {
						?>
						<div class="alert alert-warning">
							User already registered. <a href="login.php">Login now.</a>
						</div>
						<?php
					}
				} else {
					?>
					<div class="alert alert-warning">
						Fill in all the fields.
					</div>
					<?php
				}
			}
			?>

			<form method="POST">
				<div class="form-group">
					<label for="name">Name:</label>
					<input type="text" name="name" id="name" class="form-control">
				</div>
				<div class="form-group">
					<label for="email">E-mail:</label>
					<input type="email" name="email" id="email" class="form-control">
				</div>
				<div class="form-group">
					<label for="password">Password:</label>
					<input type="password" name="password" id="password" class="form-control">
				</div>
				<div class="form-group">
					<label for="phone">Phone:</label>
					<input type="text" name="phone" id="phone" class="form-control">
				</div>
				<input type="submit" value="register" class="btn btn-default">
			</form>
		</div>

<?php require 'pages/footer.php'; ?>