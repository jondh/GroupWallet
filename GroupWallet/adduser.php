<?php
	session_start();
	ob_start();

	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$query = "SELECT id FROM users WHERE username = '$_POST[NAME]'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	$addID = $row[0];
	if(!$addID){
		mysqli_close($con);
		$returnurl = "http://jondh.com/GroupWallet/";
		header("Location: $returnurl");
	}
	else{
		$query = "SELECT id FROM walletR WHERE walletID = '$_SESSION[s_walletID]' AND userID = '$addID'";
		$result = mysqli_query($con, $query);
		if(mysqli_fetch_row($result)){
			mysqli_close($con);
			$returnurl = "http://jondh.com/GroupWallet/";
			header("Location: $returnurl");
		}
		else{
		$query = "INSERT INTO walletR (walletID, userID, accept)
				VALUES ('$_SESSION[s_walletID]' ,'$addID' ,'0')";
		$result = mysqli_query($con, $query);
		}
	}
	mysqli_close($con);
	$returnurl = "http://jondh.com/GroupWallet/";
	header("Location: $returnurl");
?>