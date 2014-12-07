<!DOCTYPE html>
<html>

	<head>

		<!-- Set basic metadata -->
		<meta charset="utf-8" />
		<title>My Pantry</title>

		<!-- Import Google Fonts -->
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>

		<!-- Import CSS -->
		<link rel="stylesheet" href="css/main.css" type="text/css" />

	</head>


	<body>

		<div id="wrapper" >
			
			<div id="sidebar" >

				<div id="logo" >
					
					<img src="img/logo.png" alt="fruit logo" />
					
				</div> <!-- logo -->

			

				<!-- This is a side panel to modify content -->
				<div id="contentOptions"  >			
				
					<ul>
						<li id="add"><button>|+| Add</button></li>
						<li>|%| Edit</li>
						<li id="delete">|-| Delete</li>
						<li>Something</li>
						<li>Something</li>
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
							<li>My Pantries</li>
							<li>|</li>
							<li>New Pantry</li>
							<li>|</li>
							<li>Sign Out</li>
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

					<?php
					 include 'php/Database.php';	
					 $pdo = Database::connect();
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
