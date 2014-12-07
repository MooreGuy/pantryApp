<?php

include 'Database.php';

session_start();

if(!isset($_SESSION['count']))
{
	$_SESSION['count'] = 0;
}
else
{
	$_SESSION['count']++;
}

if(isset($_POST['firstname']))
{
	$password = $_POST['password'];
	
	$hashpassword = password_hash($password, PASSWORD_DEFAULT);
 
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];

	
	$pdo = Database::connect();
	$sql = 'INSERT INTO users ( email, password, firstname, lastname ) values(?, ?, ?, ?)';
	$query = $pdo->prepare($sql);
	$query->execute(array( $email, $hashpassword, $firstname, $lastname ));
		
	$_SESSION['email'] = $email;
	$_SESSION['login'] = true;	

	http_redirect("../index.php",null,false);
}

?>

<!DOCTYPE html>
<html>

	<head>

		<!-- Set basic metadata -->
		<meta charset="utf-8" />
		<title>Pantry App</title>

		<!-- Import Google Fonts -->
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>

		<!-- Import CSS -->
		<link rel="stylesheet" href="../css/form.css" type="text/css" />

	</head>


	<body>

		<div id="wrapper" >
			
			<?php echo $sessioninfo; ?>
			
			<div id="mainBody" >
				
				<div class="logoBar">
					<img src="../img/logo.png" alt="Logo" />
				</div>

				<header>

					<h3>
						Pantry App
					</h3>
				</header>

				<div class="signUpBox" >
	
					<h3>
						Sign Up
					</h3>

					<form  action="signup.php" method="post">
						
						<!-- First Name (firstname) -->
						<label for="firstname">First Name</label>
						<input name="firstname" id="name" type="text" data-validation-length="max32" >

						<!-- Last Name (lastname) -->
						<label for="lastname" >Last Name</label>
						<input id="lastname" name="lastname" type="text" data-validation-length="max32" >

						<!-- Email (email) -->
						<label for="email">E-Mail</label>
						<input id="email" name="email" type="email" data-validation-length="max32">

						<!-- Password (password) -->
						<label for="password" >Password</label>
						<input id="password" name="password" type="password" data-validation-length="max32" >
							
						<!-- Password (confpassword) -->
						<label for="confpassword" >Confirm Password</label>
						<input id="confpassword" type="password" data-validation-length="max32" >

						<!-- Sign up button! -->
						<input type="submit" value="Sign Up!">
					</form>

				</div> <!-- Sign up box -->

				<footer>
					
					<p>
						The Pantry App is open source
					</p>	

				</footer>

			</div> <!-- mainBody -->

		</div> <!-- wrapper -->

	</body>

	<!-- JQuery CDN -->
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js?ver=1.4.2'></script>
	<!-- JQuery Validate plugin -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>	

</html>
