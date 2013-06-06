<?php
	session_start();

	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$query = "SELECT * FROM users WHERE id = '$_SESSION[s_userID]'";
	
	$result = mysqli_query($con, $query);
	
	$row = mysqli_fetch_row($result);
	
	unset($row[2]);
	$row = array_values($row);
	
	echo json_encode($row);
?>