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
		
		
		$start_day = intval(strtotime(htmlspecialchars($_POST["start_day"])));
		$start_time = (60*60*intval(htmlspecialchars($_POST["start_hour"]))) + (60*intval(htmlspecialchars($_POST["start_minute"])));
		$end_time = (60*60*intval(htmlspecialchars($_POST["end_hour"]))) + (60*intval(htmlspecialchars($_POST["end_minute"])));
		$name = htmlspecialchars($_POST["name"]);
		$phone = htmlspecialchars($_POST["phone"]);
		$item = htmlspecialchars($_POST["item"]);
		
		$start_epoch = $start_day + $start_time;
		$end_epoch = $start_day + $end_time;
		
		// prevent double booking
		if($item != "PWS"){
		$sql = "SELECT * FROM $tablename WHERE item='$item' AND (start_day>=$start_day) AND canceled=0";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			// handle every row
			while($row = mysqli_fetch_assoc($result)) {
				// check overlapping at 10 minutes interval
				for ($i = $start_epoch; $i <= $end_epoch; $i=$i+600) {
						if ($i>($row["start_day"]+$row["start_time"]) && $i<($row["start_day"]+$row["end_time"])) {
						echo '<h3><font color="red">Valitettavasti ' . $item . ' on jo varattu haluamallesi ajalle.</font></h3>';
						goto end;
					}
				}
			}
		}
		
		}
		$sql = "INSERT INTO $tablename (name, phone, item, start_day, start_time,end_time, canceled)
			VALUES ('$name','$phone', '$item', $start_day, $start_time, $end_time, 0)";
		if (mysqli_query($conn, $sql)) {
		    echo "<h3>Varaus onnistui.</h3>";
		    echo "<p>ID:si on " . mysqli_insert_id($conn) .  "</p>";
		    echo "<p>Pidäthän ID:si muistissa, tarvitset sitä jos sinun täytyy perua varauksesi</p>";
		} else {
			echo "Tapahtui virhe: " . $sql . "<br>" . mysqli_error($conn);
		}
		
		end:
		mysqli_close($conn);
	
?>

<a href="varaa.php"><p>Takaisin varauskalenteriin</p></a>

<?php require_once "inc/bottom.php"?>