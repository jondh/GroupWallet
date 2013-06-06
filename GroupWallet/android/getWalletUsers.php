<?php
	session_start();

	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$query = "SELECT R.userID, U.username FROM walletR R, users U WHERE U.id = R.userID AND R.walletID = '$_POST[walletID]' AND R.accept = '1'";
	$result = mysqli_query($con, $query);
	
	$index = 0;
	while($row = mysqli_fetch_row($result)){
		$myarr[$index][0] = $row[0];
		$myarr[$index][1] = $row[1];
		$index = $index + 1;
	}
	
	mysqli_close($con);
	echo json_encode($myarr);
?>