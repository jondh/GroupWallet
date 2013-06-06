<?php
	session_start();
	ob_start();

	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	if($_POST['YN'] == 'accept'){
		$query = "UPDATE walletR SET accept = '1' WHERE id = '$_POST[walletRid]'";
	}
	else if($_POST['YN'] == 'decline'){
		$query = "DELETE FROM walletR WHERE id = '$_POST[walletRid]'";
	}
	$result = mysqli_query($con, $query);
	
	mysqli_close($con);
	$returnurl = "http://jondh.com/GroupWallet/";
	header("Location: $returnurl");
?>