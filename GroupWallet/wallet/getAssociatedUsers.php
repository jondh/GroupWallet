<?php
	session_start();

	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
// $_POST[userID_post] is expected to be a string with the userID inserted throughout
	$i = 0;
	for($index = 0; $index < strlen($_POST[userID_post]); $index++){
		$j = 0;
		$userID = 0;
		while(($_POST[userID_post][$index] >= '0') && ($_POST[userID_post][$index] <= '9')){
			$userID *= pow(10, $j);
			$userID += $_POST[userID_post][$index];
			++$j;
			$index++;
		}
		if($j > 0){ 
			if($userID != $_SESSION['s_userID']){
				$query = "SELECT firstName, lastName, username, id FROM users WHERE id = '$userID'";
				$result = mysqli_query($con, $query);
				$row = mysqli_fetch_row($result);
				$userArray[$i][0] = $row[0];
				$userArray[$i][1] = $row[1];
				$userArray[$i][2] = $row[2];
				$userArray[$i][3] = $row[3];
				$i++;
			}
		}
	}
	
	mysqli_close($con);
	echo json_encode($userArray);
?>