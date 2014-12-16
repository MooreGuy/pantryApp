<?php

include 'Database.php';


//Start the session
session_start();

//Count the number of visits to the website by sessions
if(!isset($_SESSION['count']))
{
	$_SESSION['count'] = 0;
}
else
{
	$_SESSION['count']++;
}

//Check if the user has a valid session
$currentSession = session_id();
$pdo = Database::connect();
$sql = 'SELECT id FROM sessions WHERE id = ?';
$storedSessions = $pdo->prepare($sql);
$storedSessions->execute(array($currentSession));
$storedSessions = $storedSessions->fetchALL(PDO::FETCH_ASSOC);

//Check for sessions in the database matching the users current session
$numAuthenticatedSessions = 0;
foreach( $storedSessions as $session )
{
	if( $session['id'] == $currentSession )
	{
		$numAuthenticatedSessions++;
	}
}

// If there is a session stored, then send the user to their pantry
if( $numAuthenticatedSessions > 0 )
{
	header("Location: http://guymoore.me/php/pantry.php");
}


//When form is submitted check login status.
if(isset($_POST['pass']))
{

	$loginError = "Please enter your correct email and password.";


	//store post variables
	$password = $_POST['pass']; 
	$email = $_POST['email'];
	
	$pdo = Database::connect();

	$sql = 'SELECT password, email
		FROM users
		WHERE email =  ? ';

	
	$result = $pdo->prepare($sql);
	$result->execute( array($email) );
	$i = 0;
	$dPassword = $result->fetchAll(PDO::FETCH_ASSOC);

	if(password_verify($password, $dPassword[0]['password']))
	{	
		$loginError = null;
	}
	
	//Disconect the database.
	Database::disconnect();	

	//Now add the session to the sessions table
	if( empty( $loginError ) )
	{
		//store the current session id
		$myId = session_id();

		//Store the session
		$sql = 'INSERT INTO sessions ( id, time, useremail) values( ?, ?, ? )';
	
		$time = date('m/d/Y h:i:s', time());
		$query = $pdo->prepare($sql);
		$query->execute(array( $myId, $time, $email));

		header("Location: http://guymoore.me/php/pantry.php");
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
						Sign In
					</h3>

					<form  action="login.php" method="post">

						<!-- Email (email) -->
							<input id="email" name="email" type="email" data-validation-length="max60" placeholder="Email address">

						<!-- Password (password) -->
							<input id="pass" name="pass" type="password" placeholder="Password">
							

						<!-- Email error -->
							<?php if( !empty( $loginError )): ?>
								<div class="loginError" ><?php echo $loginError;?></div>
							<?php endif;?>

						<!-- Sign up button! -->
							<input type="submit" value="Sign in" class="button">
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

</html>
