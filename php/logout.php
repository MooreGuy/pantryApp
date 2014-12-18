<?PHP
	session_start();
	
	$removeSession = 'DELETE FROM sessions WHERE id = ?';
	
	include 'Database.php';
	$pdo = Database::connect();
	$remove = $pdo->prepare( $removeSession );
	$remove->execute( array( session_id() ) );
	
	echo session_id();
	header("Location: http://guymoore.me");
?>
