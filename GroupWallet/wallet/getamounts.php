<?php
	session_start();

	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$query = "SELECT owedUID, owed, comments, id, paid, paidBy FROM userR WHERE oweUID = '$_SESSION[s_userID]' AND walletID = '$_SESSION[s_walletID]'";
	$result = mysqli_query($con, $query);
	
	$index = 0;
	while($row = mysqli_fetch_row($result)){
		$myarr[0][$index][0] = $row[0];
		$myarr[0][$index][1] = $row[1];
		$myarr[0][$index][2] = $row[2];
		$myarr[0][$index][3] = $row[3];
		$myarr[0][$index][4] = $row[4];
		$myarr[0][$index][5] = $row[5];
		$index = $index + 1;
	}
	
	$query = "SELECT oweUID, owed, comments, id, paid, paidBy FROM userR WHERE owedUID = '$_SESSION[s_userID]' AND walletID = '$_SESSION[s_walletID]'";
	$result = mysqli_query($con, $query);
	
	$index = 0;
	while($row = mysqli_fetch_row($result)){
		$myarr[1][$index][0] = $row[0];
		$myarr[1][$index][1] = $row[1];
		$myarr[1][$index][2] = $row[2];
		$myarr[1][$index][3] = $row[3];
		$myarr[1][$index][4] = $row[4];
		$myarr[1][$index][5] = $row[5];
		$index = $index + 1;
	}
	
	mysqli_close($con);
	echo json_encode($myarr);
?>