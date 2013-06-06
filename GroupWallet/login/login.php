<?php
ob_start();
session_start();

if($_POST[USER] && $_POST[PASS]){
	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }

	$query = "SELECT id, password FROM users WHERE username = '$_POST[USER]'";
	  
	$result = mysqli_query($con, $query);

	$row = mysqli_fetch_row($result);

	echo $row[1];
	echo $_POST[PASS];

	if($row[1] == $_POST[PASS]){
		$_SESSION['login'] = 1;
		$_SESSION['s_userID'] = $row[0];
		$_SESSION['fb'] = 0;
	}
	else{ echo "not pass"; }
}

mysqli_close($con);
$returnurl = "http://jondh.com/GroupWallet/";
header("Location: $returnurl");
?>