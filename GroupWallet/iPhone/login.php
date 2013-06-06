<?php
if($_POST[USER] && $_POST[PASS]){
	$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
	// Check connection
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }

	$query = "SELECT id, password FROM users WHERE username = '$_POST[USER]'";
	  
	$result = mysqli_query($con, $query);
	mysqli_close($con);
	$row = mysqli_fetch_row($result);
	
	if($row[1] == $_POST[PASS]){
		echo json_encode($row[0]);
	}
	else{ echo json_encode("0"); }
}
else{ echo json_encode("0"); }
?>