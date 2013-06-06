<?php
session_start();
ob_start();
$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$sql="INSERT INTO users (username, password, firstName, lastName, email)
VALUES
('$_POST[USER]','$_POST[PASS]','$_POST[FN]','$_POST[LN]','$_POST[EMAIL]')";

if (!mysqli_query($con,$sql))
{
	die('Error: ' . mysqli_error($con));
}

$query = "SELECT id FROM users WHERE username = '$_POST[USER]'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_row($result);
$userID = $row[0];

$_SESSION['login'] = 1;
$_SESSION['s_userID'] = $userID;
$_SESSION['fb'] = 0;

mysqli_close($con);
$returnurl = "http://jondh.com/GroupWallet/";
header("Location: $returnurl");
?>