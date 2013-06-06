<?php
	session_start();

	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$inputname = explode(" ", $_POST['NAME']);
	if(!$inputname[0]){
		echo json_encode("0");
	}
	else{
		if($inputname[1]){
			$query = "SELECT username, firstName, lastName, id FROM users WHERE firstName = '$inputname[0]' AND lastName = '$inputname[1]'";
			$done = 0;
		}
		else{
			$query = "SELECT username, firstName, lastName, id FROM users WHERE username = '$inputname[0]'";
			$result = mysqli_query($con, $query);
			$row = mysqli_fetch_row($result);
			$done = 1;
			$myarr[0] = $row;
			if(!$row){
				$query = "SELECT username, firstName, lastName, id FROM users WHERE email = '$inputname[0]'";
				$result = mysqli_query($con, $query);
				$row = mysqli_fetch_row($result);
				$done = 1;
				$myarr[0] = $row;
				if(!$row){
					$query = "SELECT username, firstName, lastName, id FROM users WHERE firstName = '$inputname[0]' OR lastName = '$inputname[0]'";
					$done = 0;
				}
			}
		}
		
		if($done == 0){
			$result = mysqli_query($con, $query);
			$index = 0;
			while($row = mysqli_fetch_row($result)){
				$myarr[$index][0] = $row[0];
				$myarr[$index][1] = $row[1];
				$myarr[$index][2] = $row[2];
				$myarr[$index][3] = $row[3];
				$index = $index + 1;
			}
		}
		
		echo json_encode($myarr);
	}
	mysqli_close($con);
?>