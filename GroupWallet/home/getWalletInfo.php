<?php
	session_start();

	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$query = "SELECT R.walletID, W.name, W.date, W.id FROM walletR R, wallets W WHERE userID = '$_SESSION[s_userID]' AND W.id = R.walletID AND R.accept = '1'";

	
	$result = mysqli_query($con, $query);
	
	$index = 0;
	while($row = mysqli_fetch_row($result)){
		$myarr[$index] = $row;
		$index = $index + 1;
	}
	if(!$myarr) $myarr = 0;
	
	mysqli_close($con);
	echo json_encode($myarr);
?>