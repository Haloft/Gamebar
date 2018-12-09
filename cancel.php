<?php require_once "inc/topvaraus.php"?>
<?php
session_start();
?>

<?php

		include 'config.php';

		// Create connection
		$conn = mysqli_connect($servername, $username, $password,  $dbname);

		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}

		$id = intval(htmlspecialchars($_POST["id"]));

		$sql = "UPDATE $tablename SET canceled=1 WHERE id = $id";
		if (mysqli_query($conn, $sql)) {
			echo "<h3>Varauksesi on peruttu.</h3>";
		}
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		
		mysqli_close($conn);
	
?>

<a href="varaa.php"><p>Takaisin varauskalenteriin</p></a>

<?php require_once "inc/bottom.php"?>