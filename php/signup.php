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
	echo "my session id is: " . session_id();
}


//Check the sessions open for the current session.
$numSessions = 0;

$currentSession = session_id();
$pdo = Database::connect();
$sql = 'SELECT id FROM sessions WHERE id = ?';
$query = $pdo->prepare( $sql );

$query->execute( array( $currentSession ) );
$storedSessions = $query->fetchALL(PDO::FETCH_ASSOC);

//Check for sessions in the database matching the users current session
foreach( $storedSessions as $session )
{
	if( $session['id'] == $currentSession )
	{
		$numSessions++;
	}
}

// If there is a session stored, then send the user to their pantry
if( $numSessions > 0 )
{
	header("Location: http://guymoore.me/php/pantry.php");
}


if(isset($_POST['firstname']))
{
	$emailError = null;
	$email = $_POST['email'];

	$pdo = Database::connect();	
	$sql = 'SELECT email FROM users WHERE email = ?';
	$query = $pdo->prepare($sql);
	$query->execute( array($email) );
	
	$storedEmails = $query->fetchAll(PDO::FETCH_ASSOC);
	
	foreach( $storedEmails as $databaseEmail )
	{
		echo "testing";
		if( $email == $databaseEmail['email'] )
		{
			echo "found a match";
			$emailError = "You already have an account!";
		}
	}

	if( !isset($emailError) )
	{
	
		$password = $_POST['pass'];
		$hashpassword = password_hash($password,CRYPT_BLOWFISH );
		 
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		
		$pdo = Database::connect();

		$sql = 'INSERT INTO users ( email, password, firstname, lastname ) values(?, ?, ?, ?)';
		$query = $pdo->prepare($sql);
		$query->execute(array( $email, $hashpassword, $firstname, $lastname ));
			
		$myId = session_id();

		//Now add the session to the session table
		$sql = 'INSERT INTO sessions ( id, time) values( ?, ?)';
		$query = $pdo->prepare($sql);

		$date = date('m/d/Y h:i:s', time());
		echo $date;
		$query->execute(array( $myId, $date));

		Database::disconnect();

		/*header("Location: http://guymoore.me/php/pantry.php");*/
	}

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
			
			
			<div id="mainBody" >
				
				<div class="logoBar">
					<img src="../img/logo.png" alt="Logo" />
				</div>


				<div class="signUpBox" >
	
					<h3>
						Sign Up
					</h3>

					<form  action="signup.php" method="post">
						
						<!-- First Name (firstname) -->
							<input name="firstname" id="name" type="text" data-validation-length="max32" placeholder="First Name">

						<!-- Last Name (lastname) -->
							<input id="lastname" name="lastname" type="text" data-validation-length="max32" placeholder="Last Name">

						<!-- Email (email) -->
							<input id="email" name="email" type="email" data-validation-length="max60" placeholder="Email address">

						<!-- Password (password) -->
							<input id="pass_confirmation" name="pass_confirmation" type="password" data-validation="length" data-validation-length="min8" placeholder="Password">
							
						<!-- Password (confpassword) -->
							<input id="pass" name="pass" type="password" data-validation="confirmation" placeholder="Confirm Password">

						<!-- Email error -->
							<?php if( !empty( $emailError )): ?>
								<div class="emailError" ><?php echo $emailError;?></div>
							<?php endif;?>

						<!-- Sign up button! -->
							<input type="submit" value="Sign Up!" class="button">
								
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
	<script>
	  $.validate({
	    modules : 'security'
	  });
	</script>	

</html>
