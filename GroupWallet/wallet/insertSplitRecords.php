<?php
	session_start();
	
	if($_POST[value] || $_POST[idArray]){
	
		$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		$amount = $_POST[value]/count($_POST[idArray]);
		$oweIDs = $_POST[idArray];
		
		for($i = 0; $i < count($_POST[idArray]); $i++){
			$query = "INSERT INTO userR (oweUID, owedUID, owed, walletID, comments)
						VALUES ('$oweIDs[$i]' ,'$_SESSION[s_userID]' ,'$amount' ,'$_SESSION[s_walletID]' ,'$_POST[comments_post]')";
			$result = mysqli_query($con, $query);
			$query = "UPDATE walletR SET activity = 1 WHERE userID = '$oweIDs[$i]' AND walletID = '$_SESSION[s_walletID]'";
			$result = mysqli_query($con, $query);
		}
	}
	mysqli_close($con);
?>