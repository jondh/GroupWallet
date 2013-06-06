<?php
	session_start();

	$_SESSION['s_walletID'] = $_POST['walletID'];
	$_SESSION['s_URL'] = "wallet";
?>