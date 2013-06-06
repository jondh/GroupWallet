<?php
	session_start();
	ob_start();
	
	if(!$_POST[AMNT] || !$_POST[owe]){
		$returnurl = "http://jondh.com/GroupWallet/";
		header("Location: $returnurl");
	}
	else{
	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	if(is_numeric($_POST[otherUID])){
		$oUID = $_POST[otherUID];
	}
	else{
		$query = "SELECT id FROM users WHERE username = '$_POST[otherUID]'";
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_row($result);
		$oUID = $row[0];
	}
	
	if($_POST[owe] == "OWE"){
		$query = "INSERT INTO userR (oweUID, owedUID, owed, walletID, comments)
				VALUES ('$_SESSION[s_userID]' ,'$oUID' ,'$_POST[AMNT]' ,'$_SESSION[s_walletID]' ,'$_POST[comments_post]')";
	}
	if($_POST[owe] == "OWED"){
		$query = "INSERT INTO userR (oweUID, owedUID, owed, walletID, comments)
				VALUES ('$oUID' ,'$_SESSION[s_userID]' ,'$_POST[AMNT]' ,'$_SESSION[s_walletID]' ,'$_POST[comments_post]')";
	}
	$result = mysqli_query($con, $query);
	$query = "UPDATE walletR SET activity = 1 WHERE userID = '$oUID' AND walletID = '$_SESSION[s_walletID]'";
	$result = mysqli_query($con, $query);
	}
	mysqli_close($con);
	$returnurl = "http://jondh.com/GroupWallet/wallet";
	header("Location: $returnurl");
?>