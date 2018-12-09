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

		$sql = "DELETE FROM $tablename WHERE id = $id";
		if (mysqli_query($conn, $sql)) {
			echo "<h3>Booking deleted.</h3>";
		}
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		
		mysqli_close($conn);
	
?>

<a href="varaa.php"><p>Back to the calendar</p></a>

<?php require_once "inc/bottom.php"?>