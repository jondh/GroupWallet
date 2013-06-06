<?php
ob_start();
session_start();

if($_POST[fbUserCheck] == "checkForUser"){
	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno()) {  echo "Failed to connect to MySQL: " . mysqli_connect_error(); }

	$query = "SELECT id FROM users WHERE username = '$_POST[fbUser]'";
	  
	$result = mysqli_query($con, $query);

	$row = mysqli_fetch_row($result);

	if($row[0]){
		$_SESSION['login'] = 1;
		$_SESSION['s_userID'] = $row[0];
		$_SESSION['fb'] = 1;
		echo 1;
	}
	else echo 0;
}
else if($_POST[fbUserCheck] == "newUser"){
	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno()) { echo "Failed to connect to MySQL: " . mysqli_connect_error(); }

	$sql="INSERT INTO users (username, password, firstName, lastName, email)
	VALUES
	('$_POST[fbUser]','fb','$_POST[fbFirst]','$_POST[fbLast]','none')";

	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}

	$query = "SELECT id FROM users WHERE username = '$_POST[fbUser]'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	$userID = $row[0];

	$_SESSION['login'] = 1;
	$_SESSION['s_userID'] = $userID;
	$_SESSION['fb'] = 1;
	echo 1;
}

mysqli_close($con);

?>