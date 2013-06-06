<?php
ob_start();
session_start();
$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
// Check connection
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$sql="INSERT INTO wallets (name, createdBy) VALUES ('$_POST[NAME]', '$_SESSION[s_userID]')";
if (!mysqli_query($con,$sql))
{
	die('Error: ' . mysqli_error($con));
}

$query = "SELECT MAX(id) FROM wallets WHERE createdBy = '$_SESSION[s_userID]'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_row($result);
$walletID = $row[0];
  
  
$sql="INSERT INTO walletR (walletID, userID) VALUES ('$walletID', '$_SESSION[s_userID]')";

if (!mysqli_query($con,$sql))
  {
  die('Error: ' . mysqli_error($con));
  }

mysqli_close($con);
$returnurl = "http://jondh.com/GroupWallet/home";
header("Location: $returnurl");
?>