<?php
	session_start();
	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	$total = 0;
	$query = "SELECT owed FROM userR WHERE oweUID = '$_SESSION[s_userID]'";
	$result = mysqli_query($con, $query);
	while($row = mysqli_fetch_row($result)){
		$total -= $row[0];
	}
	$query = "SELECT owed FROM userR WHERE owedUID = '$_SESSION[s_userID]'";
	$result = mysqli_query($con, $query);
	while($row = mysqli_fetch_row($result)){
		$total += $row[0];
	}
	
	echo json_encode($total);
	mysqli_close($con);
?>