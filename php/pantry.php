<?php

include 'Database.php';	

if( !isset($_SESSION) )
{
	session_start();
}

//Check if the user has a valid session
$currentSession = session_id();
$pdo = Database::connect();
$sql = 'SELECT id FROM sessions WHERE id = ?';
$storedSessions = $pdo->prepare($sql);
$storedSessions->execute(array($currentSession));
$storedSessions = $storedSessions->fetchALL(PDO::FETCH_ASSOC);

//Check for sessions in the database matching the users current session
if( !empty($storedSessions) )
{
	$numAuthenticatedSessions = 1;
}


// If there is a session stored, then send the user to their pantry
if( $numAuthenticatedSessions == 0 )
{
	header("Location: http://guymoore.me/php/login.php");
}


$pdo = Database::connect();


/* Get user email */
$emailQuerry = 'SELECT useremail, id 
	FROM sessions
	WHERE id = ?';

$querry = $pdo->prepare( $emailQuerry );
$querry->execute( array( session_id() ) );

$sqlReturn = $querry->fetchAll(PDO::FETCH_ASSOC);
$email = $sqlReturn[0]['useremail'];

Database::disconnect();

if( !empty($_POST) )
{

	$pdo = Database::connect();

	/* Get user email */
	$emailQuerry = 'SELECT useremail, id 
		FROM sessions
		WHERE id = ?';

	$querry = $pdo->prepare( $emailQuerry );
	$querry->execute( array( session_id() ) );

	$sqlReturn = $querry->fetchAll(PDO::FETCH_ASSOC);
	$email = $sqlReturn[0]['useremail'];

	Database::disconnect();

	/* Enter a food into the pantry. */
	if( ! empty($_GET['pantry']) )
	{
		$pantryName = $_GET['pantry'];
		$quantity = $_POST['quantity'];
		$expdate = $_POST['expdate'];
		$food = $_POST['food'];

		/*Check to make sure the user has that food*/
		$foodsQuerry = 'SELECT id  
							FROM foods
							WHERE name = ?';
									
		$pdo = Database::connect();
		$querry = $pdo->prepare( $foodsQuerry );
		$querry->execute( array( $food ) );
		
		$sqlReturn = $querry->fetchAll(PDO::FETCH_ASSOC);
		Database::disconnect();

		$chosenFoodID = $sqlReturn[0]['id'];
		
		/* Check to see if a food was found to match. If not, don't do anything. If it was, start to add it to the pantry.*/
		if( !empty($chosenFoodID) )
		{
			$pdo = Database::connect();	
		
			/* Check to make sure the pantry specified is owned by the user. */
			$checkPantryQuery = 'SELECT id 
						FROM pantries  
						WHERE userid = ? 
						AND name = ?'; 
										

			$querry = $pdo->prepare( $checkPantryQuery );
			$querry->execute( array( $email, $pantryName ) );

			$validPantry = $querry->fetchAll(PDO::FETCH_ASSOC);
			Database::disconnect(); 

			if( !empty($validPantry) )
			{

				$pantryID = $validPantry[0]['id'];

				$insertFoodPantry = 'INSERT INTO foodspantries 
							( pantryid, foodsid, expdate, quantity ) 
							values( ?, ?, ?, ? )';

				$pdo = Database::connect();
				$insert = $pdo->prepare( $insertFoodPantry );
				$insert->execute( array( $pantryID, $chosenFoodID, $expdate, $quantity ) );

				Database::disconnect();
			}
			else
			{
				echo 'Not a valid pantry';
			}

		}
		else
		{
			echo "error, can't find food.";
		}
	}

	elseif( !empty($_GET['foods']) )
	{
		$name = $_POST['name'];
		$units = $_POST['units'];
		$type = $_POST['type'];
		
		$pdo = Database::connect();
		$insertFoods = 'INSERT INTO foods
				(name, units, type)
				values( ?, ?, ?)';
		$querry = $pdo->prepare( $insertFoods );
		$querry->execute( array( $name, $units, $type ) );
		
	}

	/* If it's not a pantry or the foods, then we must be adding a pantry.*/
	else
	{	
		$name = $_POST['name'];
		
		$pdo = Database::connect();	
		$insertPantry = 'INSERT INTO pantries
				(name, userid)
				values( ?, ? )';

		$querry = $pdo->prepare( $insertPantry );
		$querry->execute( array( $name, $email ) );
		Database::disconnect();	
	}

}

?>

<!DOCTYPE html>
<html>

	<head>

		<!-- Set basic metadata -->
		<meta charset="utf-8" />
		<title>Cloud Pantry</title>

		<!-- Import Google Fonts -->
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>

		<!-- Import CSS -->
		<link rel="stylesheet" href="../css/main.css" type="text/css" />

	</head>


	<body>

		<div id="wrapper" >
			
			<div id="sidebar" >

				<div id="logo" >
					
					<img src="../img/logo.png" alt="fruit logo" />
					
				</div> <!-- logo -->

			

				<!-- This is a side panel to modify content -->
				<div id="contentOptions"  >			
				
					<ul>
						<li id="addButton" class="button" >|+| Add</li>
						<li id="editButton" class="button" >|%| Edit</li>
						<li id="deleteButton" class="button" >|-| Delete</li>
						<li id="Something" class="button" >Something</li>
						<li id="Something" class="button" >Something</li>
					</ul>
				
				</div> <!-- contentOptions -->
			
			</div> <!-- sidebar -->

			<div id="mainBody" >

				<!-- Holds the navigation and title -->
				<header>
				
					<h3>
						Cloud Pantry
					</h3>

					<nav>

						<ul>
							<li class="button" ><a href="pantry.php" >My Pantries</a></li>
							<li>|</li>
							<li class="button" ><a href="pantry.php?foods=viewing" >Foods</a></li>
							<li>|</li>
							<li class="button" ><a href="logout.php" >Log out</a></li>
						</ul>

					</nav>

				</header>
					
							
				<!-- Main area to display content -->
				<div id="contentBody" class="right container" >

					<table class="currentPantry" >
						
						<thead>

							<tr>

								<?php 
					
									$theadColumns = array();

									/* ALL the hardcoded strings! */
									if( !empty($_GET['pantry']) )
									{
										$theadColumns[] = '<th class="row-name">Name</th>';
										$theadColumns[] = '<th class="row-type">Type</th>';
										$theadColumns[] = '<th class="row-units">Units</th>';
										$theadColumns[] = '<th class="row-quatity">Quantity</th>';
										$theadColumns[] = '<th class="row-expiration">Expiration Date</th>';
									}
			
									elseif( !empty($_GET['foods']) )
									{
										$theadColumns[] = '<th class="row-name">Name</th>';
										$theadColumns[] = '<th class="row-type">Type</th>';
										$theadColumns[] = '<th class="row-units">Units</th>';
									}

									/* If it's not a food or within a pantry, then we show just a list of pantries. */
									else
									{
										$theadColumns[] = '<th class="row-name">Name</th>';
									}	

									foreach( $theadColumns as $column )
									{
										echo $column;
									}


								?>

							</tr>

						</thead>
						
						<tbody>

						<!-- Populate the table with whatever the chosen content is, if nothing is chose, display pantries. -->
						<?php

							/* Connect to the database to populate the main table. */
							$pdo = Database::connect();

							
							/* Get user email */
							$emailQuerry = 'SELECT useremail, id 
								FROM sessions
								WHERE id = ?';

							$querry = $pdo->prepare( $emailQuerry );
							$querry->execute( array( session_id() ) );

							$sqlReturn = $querry->fetchAll(PDO::FETCH_ASSOC);
							$email = $sqlReturn[0]['useremail'];
							
							Database::disconnect();

							/* Check what the user wants to view, all pantries if nothing selected.*/
							if( isset( $_GET['pantry'] ) )
							{

								/*Check to make sure pantry is the users pantry */
								$currentPantry = $_GET['pantry'];

								$correctPantryQuerry = 'SELECT id FROM pantries WHERE name = ? AND userid = ?';

								$querry = $pdo->prepare( $correctPantryQuerry );
								$querry->execute( array( $currentPantry, $email ) );

								$sqlReturn = $querry->fetchAll(PDO::FETCH_ASSOC);

								
								Database::disconnect();
							
								if( !empty($sqlReturn) )
								{
									$pantryID = $sqlReturn[0]['id'];
									$pantryQuerry = 'SELECT foodspantries.expdate, foodspantries.quantity, foods.type, foods.name, foods.units 
										FROM foodspantries
										INNER JOIN foods ON foodspantries.foodsid = foods.id 
										WHERE foodspantries.pantryid = ?';
		 
									$pdo = Database::connect();
									$querry = $pdo->prepare( $pantryQuerry ); 
									$querry->execute( array( $pantryID ) );

									$sqlReturn = $querry->fetchAll(PDO::FETCH_ASSOC);

									foreach ( $sqlReturn as $row )
									{
										echo '<tr>';
										echo '<td>' . $row['name'] . '</td>';
										echo '<td>' . $row['type'] . '</td>';
										echo '<td>' . $row['units'] . '</td>';	
										echo '<td>' . $row['quantity'] . '</td>';
										echo '<td>' . $row['expdate'] . '</td>';
										echo '</tr>';
									}
								}
								else
								{
									echo 'Unknown pantry';
								}

							}

							/* Check if the user is wanting to view foods instead. */
							elseif( !empty( $_GET['foods'] ) )
							{
								$foodsQuerry = 'SELECT name, type, units
										FROM foods';
								
								$pdo = Database::connect();
								$querry = $pdo->prepare( $foodsQuerry );
								$querry->execute( array( $email ) );

								$sqlReturn = $querry->fetchAll(PDO::FETCH_ASSOC);

								foreach ( $sqlReturn as $row )
								{
									echo '<tr>';
									echo '<td>' . $row['name'] . '</td>';
									echo '<td>' . $row['type'] . '</td>';
									echo '<td>' . $row['units'] . '</td>';
									echo '</tr>';
								}	
							}

							/* The user is undecided, default to show pantries. */
							else
							{
								$pantriesQuerry = 'SELECT name, userid
										 FROM pantries
										 WHERE ? = userid';

								$pdo = Database::connect();
								$querry = $pdo->prepare( $pantriesQuerry );
								$querry->execute( array( $email ) );
			
								$pantries = $querry->fetchAll(PDO::FETCH_ASSOC);

								foreach ( $pantries as $row )
								{
									echo '<tr>';
									echo '<td><a href="pantry.php?pantry=' . $row['name'] . '">' . $row['name'] . '</a></td>';
									echo '</tr>';
								}
							}

							/* Close the database. */
							Database::disconnect();

						?>

						</tbody>	

					</table>

					<form class="addForm"  method="post" >
						
						<?php
						
							$formColumns = array();

							if( ! empty($_GET['pantry']) )
							{
								$formColumns[] = '<input id="food" name="food" type="text" placeholder="Food" >';
								$formColumns[] = '<input id="quantity" name="quantity" type="number" placeholder="Quantity" >';
								$formColumns[] = '<input id="expdate" name="expdate" type="date" placeholder="Expiration Date" >';
							}
	
							elseif( !empty($_GET['foods']) )
							{
								$formColumns[] = '<input id="name" name="name" type="text" placeholder="Name" >';
								$formColumns[] = '<input id="type" name="type" type="text" placeholder="Type" >';
								$formColumns[] = '<input id="units" name="units" type="measurement" placeholder="Units" >';
							}

							else
							{
								$formColumns[] = '<input id="name" name="name" type="text" placeholder="Name" >';
							}
				
							$formColumns[] = '<input type="submit" value="Add" class="button">';

							foreach( $formColumns as $column )
							{
								echo $column;
							}
								
						?>

				</form>
				
				</div> <!-- contentBody -->

				<footer>
					
					<p>
						The Pantry App is open source
					</p>	

				</footer>

			</div> <!-- mainBody -->

		</div> <!-- wrapper -->

		<!-- Add Jquery and Javascript -->
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js?ver=1.4.2'></script>
		<script src="js/app.js" ></script> 

	</body>	

</html>
