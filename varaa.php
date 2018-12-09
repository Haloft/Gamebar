<?php require_once "inc/topvaraus.php"?>


<h1>GameBar Varausjärjestelmä</h1>
<table border="1" cellpadding="5" width="800">
	<tr>
		<td valign="top">
		<form action="book.php" method="post">
			<h3>Tee varaus</h3>
			<p><input checked="checked" name="item" type="radio" value="PS4" />PS4
			| <input name="item" type="radio" value="XBOX ONE" />XBOX ONE
			| <input name="item" type="radio" value="NES 8-bit" />NES 8-bit | 
		   	  <input name="item" type="radio" value="WII U" />WII U
			  <input name="item" type="radio" value="PWS" />Play With Strangers</p>
			  <table style="width: 70%">
				<tr>
					<td>Nimi:</td>
					<td> <input maxlength="50" name="name" required="" type="text" /></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Puhelin:</td>
					<td>
			  <input maxlength="20" name="phone" required="" type="number" step=none /></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Päivämäärä:</td>
					<td>
			  <input id="from" name="start_day" required="" type="date" /></td>
					
					
				</tr><tr><td>Kello:</td>
        <td><input type="time" name="start_hour" value="14:00" step="1800"
               min="14:00" max="23:00" required />-
       
    
        <input type="time" id="appt-time" name="end_hour" value="15:00" step="1800"
                min="15:00" max="23:59" required />
        </td>
    </tr>
			
			</table>
		
			<input name="book" type="submit" value="Varaa" />
		</form>
		</td>
		
		<td valign="top">
		<h3>Peruuta varauksesi</h3>
		<form action="cancel.php" method="post">
			<p></p>
			ID: <input name="id" required="" type="number" /><br />
			
			<p><input name="cancel" type="submit" value="Peruuta" /></p>
		</form>
		</td>
	</tr>
</table>


	
	
<?php
/* draws a calendar */


function draw_calendar($month,$year){

	include 'config.php';
	


	// Create connection
	$conn = mysqli_connect($servername, $username, $password,  $dbname);

	// Check connection
	if (!$conn) {
    	die("Connection failed: " . mysqli_connect_error());
	}

	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$calendar.= '<td class="calendar-day">';
			/* add in the day number */
			$calendar.= '<div class="day-number">'.$list_day.'</div>';
			

			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
			$calendar.= str_repeat('<p> </p>',2);
			$current_epoch = mktime(0,0,0,$month,$list_day,$year);
			
			$sql = "SELECT * FROM $tablename WHERE $current_epoch = start_day" ;
						
			$result = mysqli_query($conn, $sql);
    		
    		if (mysqli_num_rows($result) > 0) {
    			// output data of each row
    			while($row = mysqli_fetch_assoc($result)) {
					if($row["canceled"] == 1) $calendar .= "<font color=\"grey\"><s>";
    				$calendar .= "<b>" . $row["item"] . "</b>"  . "<br>" . $row["name"]   . "<br>";
    				if($current_epoch == $row["start_day"] ) {
    					$calendar .= "Varaus alkaa: " . sprintf("%02d:%02d", $row["start_time"]/60/60, ($row["start_time"]%(60*60)/60)) . "<br>";
    					$calendar .= "Varaus loppuu: " . sprintf("%02d:%02d", $row["end_time"]/60/60, ($row["end_time"]%(60*60)/60)) . "<br>";
    				}
    			
					if($row["canceled"] == 1) $calendar .= "</s></font>";
    			}
			} else {
    			$calendar .= "Ei varauksia";
			}
			
		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';
	
	mysqli_close($conn);
	
	/* all done, return result */
	return $calendar;
}

include 'config.php';

$d = new DateTime(date("Y-m-d"));
echo '<h3>' . $months[$d->format('n')-1] . ' ' . $d->format('Y') . '</h3>';
echo draw_calendar($d->format('m'),$d->format('Y'));

$d->modify( 'first day of next month' );
echo '<h3>' . $months[$d->format('n')-1] . ' ' . $d->format('Y') . '</h3>';
echo draw_calendar($d->format('m'),$d->format('Y'));

$d->modify( 'first day of next month' );
echo '<h3>' . $months[$d->format('n')-1] . ' ' . $d->format('Y') . '</h3>';
echo draw_calendar($d->format('m'),$d->format('Y'));


?>

<?php require_once "inc/bottom.php"?>

