<?php

if( !isset($_SESSION) )
{
	session_start();
}

?>

<!DOCTYPE html>
<html>

	<head>

		<!-- Set basic metadata -->
		<meta charset="utf-8" />
		<title>My Pantry</title>

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
						<li id="add" class="button" >|+| Add</li>
						<li id="edit" class="button" >|%| Edit</li>
						<li id="delete" class="button" >|-| Delete</li>
						<li id="Something" class="button" >Something</li>
						<li id="Something" class="button" >Something</li>
					</ul>
				
				</div> <!-- contentOptions -->
			
			</div> <!-- sidebar -->

			<div id="mainBody" >

				<!-- Holds the navigation and title -->
				<header>
				
					<h3>
						My Pantry
					</h3>

					<nav>

						<ul>
							<li class="button" >My Pantries</li>
							<li>|</li>
							<li class="button" ><a href="foods.php" >My Foods</a></li>
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
								<td class="row-checkBox">
									<div class="checkbox"> 
									</div>
								</td>
								<th class="row-name">Name</th>
								<th class="row-type">Type</th>
								<th class="row-quatity">Quantity</th>
								<th class="row-expiration">Expiration Date</th>
							</tr>
						</thead>
						
						<tbody>

						<!-- Populate the table with whatever the chosen content is, if nothing is chose, display pantries. -->
						<?php

						/* Connect to the database to populate the main table. */
						include 'Database.php';	
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

							$sql = 'SELECT * FROM foods ORDER BY id DESC';
							foreach ($pdo->query($sql) as $row)
							{
								echo '<tr>';
								echo '<td><div class="checkbox"></div></td>';
								echo '<td>' . $row['name'] . '</td>';
								echo '<td>' . $row['type'] . '</td>';
								echo '<td>' . $row['quantity'] . '</td>';
								echo '<td>' . $row['expiration_date'] . '</td>';
								echo '</tr>';
							}

						}
						/* Check if the user is wanting to view foods instead. */
						elseif( !empty( $_GET['foods'] ) )
						{

							foreach ($pdo->query($sql) as $row)
							{
								echo '<tr>';
								echo '<td><div class="checkbox"></div></td>';
								echo '<td>' . $row['name'] . '</td>';
								echo '<td>' . $row['type'] . '</td>';
								echo '<td>' . $row['quantity'] . '</td>';
								echo '<td>' . $row['expiration_date'] . '</td>';
								echo '</tr>';
							}
						}
						/* The user is undecided, default to show pantries. *
						else
						{

							$sql = 'SELECT * FROM foods ORDER BY id DESC';
							foreach ($pdo->query($sql) as $row)
							{
								echo '<tr>';
								echo '<td><div class="checkbox"></div></td>';
								echo '<td>' . $row['name'] . '</td>';
								echo '<td>' . $row['type'] . '</td>';
								echo '<td>' . $row['quantity'] . '</td>';
								echo '<td>' . $row['expiration_date'] . '</td>';
								echo '</tr>';
							}
						}

						/* Close the database. */
						Database::disconnect();

						?>

						</tbody>	

					</table>
				
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
