<?php
	session_start();

	$_SESSION['login'] = 0;
	$_SESSION['s_userID'] = 0;
	
	
	if($_POST[endsession_bool] == 1){
		session_destroy();
	}

?>