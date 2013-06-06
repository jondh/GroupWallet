<?php
	session_start();

	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$query = "SELECT oweUID, owedUID FROM userR WHERE id = '$_POST[recordID]'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	if($_POST['type'] == 'accept'){
		$query = "DELETE FROM userR WHERE id = '$_POST[recordID]'";
		$result = mysqli_query($con, $query);
		if($row[0] == $_SESSION['s_userID']){
			$query0 = "UPDATE walletR SET activity = 1 WHERE userID = '$row[1]' AND walletID = '$_SESSION[s_walletID]'";
		}
		else{
			$query0 = "UPDATE walletR SET activity = 1 WHERE userID = '$row[0]' AND walletID = '$_SESSION[s_walletID]'";
		}
	}
	else if($_POST['type'] == 'mark'){
		
		if($row[0] == $_SESSION['s_userID']){
			$query = "UPDATE userR SET paid = '1', paidBy = '0' WHERE id = '$_POST[recordID]'";
			$query0 = "UPDATE walletR SET activity = 1 WHERE userID = '$row[1]' AND walletID = '$_SESSION[s_walletID]'";
		}
		else{
			$query = "UPDATE userR SET paid = '1', paidBy = '1' WHERE id = '$_POST[recordID]'";
			$query0 = "UPDATE walletR SET activity = 1 WHERE userID = '$row[0]' AND walletID = '$_SESSION[s_walletID]'";
		}
		$result = mysqli_query($con, $query);
		
		
	}
	$result = mysqli_query($con, $query0);
	mysqli_close($con);
?>