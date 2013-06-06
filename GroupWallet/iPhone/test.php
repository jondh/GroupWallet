<?php
	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	
	$walID = 7;
	$addID = $_REQUEST['userID'];
	
	$query = "INSERT INTO walletR (walletID, userID)
			VALUES ('$walID' ,'$addID')";
	$result = mysqli_query($con, $query);

	mysqli_close($con);
	echo json_encode("1");
?>