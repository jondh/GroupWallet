<?php
session_start();
ob_start();

if(!isset($_SESSION['login'])){
	$_SESSION['login'] = 0; 
	$_SESSION['s_userID'] = 0;
	$_SESSION['s_walletID'] = 0;
	$_SESSION['fb'] = 0;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta  http-equiv="Content-Type" content="text/html; chairset=UTF-8" />
	<title>Jonathan Harrison</title>
	<meta name="description" content="The official site Jonathan Harrison." />
	<meta name="keywords" content="Jonathan Harrison" />
	<link rel="icon" href="/favicon.ico" type="image/x-icon" /> 
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type ="text/css" href="style.css" />
	<link href="screen.css" rel="stylesheet" type="text/css" media="screen" />
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.1.1.min.js"></script>
	<script src="http://jondh.com/jquery-1.9.1.min.js"></script>
	
	<?php
		if($_SESSION['login'] == 1){
			$returnurl = "http://jondh.com/GroupWallet/";
			header("Location: $returnurl");
		}
	?>
	
	<script>
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
			alert("mobile");
			document.location = "http://m.jondh.com/GroupWallet/login/";
		}
	</script>
	
</head>

<body>

<div id="fb-root"></div>
<script>
  // Additional JS functions here
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '315826831884119', // App ID
      channelUrl : '../channel.html', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });

    // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
  // for any authentication related change, such as login, logout or session refresh. This means that
  // whenever someone who was previously logged out tries to log in again, the correct case below 
  // will be handled. 
  FB.Event.subscribe('auth.authResponseChange', function(response) {
    // Here we specify what we do with the response anytime this event occurs. 
    if (response.status === 'connected') {
      // The response object is returned with a status field that lets the app know the current
      // login status of the person. In this case, we're handling the situation where they 
      // have logged in to the app.
      login_with_fb();
    } else if (response.status === 'not_authorized') {
      // In this case, the person is logged into Facebook, but not into the app, so we call
      // FB.login() to prompt them to do so. 
      // In real-life usage, you wouldn't want to immediately prompt someone to login 
      // like this, for two reasons:
      // (1) JavaScript created popup windows are blocked by most browsers unless they 
      // result from direct interaction from people using the app (such as a mouse click)
      // (2) it is a bad experience to be continually prompted to login upon page load.
      FB.login();
    } else {
      // In this case, the person is not logged into Facebook, so we call the login() 
      // function to prompt them to do so. Note that at this stage there is no indication
      // of whether they are logged into the app. If they aren't then they'll see the Login
      // dialog right after they log in to Facebook. 
      // The same caveats as above apply to the FB.login() call here.
      FB.login();
    }
  });

  };

  // Load the SDK asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
   
   // Here we run a very simple test of the Graph API after login is successful. 
  // This testAPI() function is only called in those cases. 
  function login_with_fb() {
    FB.api('/me', function(response) {
		//window.location.href = response.picture;
		// $.post("fblogin.php",{ fbUserCheck: 'checkForUser', fbUser: response.id }, function(data){
			// if(data == 1){
				// alert(response.picture);
				// document.location.reload();
			// }else if(data == 0){
				// $.post("fblogin.php",{ fbUserCheck: 'newUser', fbUser: response.id, fbFirst: response.first_name, fbLast: response.last_name }, function(data){
					// document.location.reload();
				// });
			// }
		// });
    });
  }
  function signup_with_fb(){
	FB.getLoginStatus(function(response0){
		if(response0.status != 'connected'){
			FB.login(function(response1){});
		}
	});
  }
</script>

<div id="pagewrap"> 
<div id="textcolor">

<div id="login"></div>
<div id="newuser"></div>
<div id="fbLogin">
<fb:login-button show-faces="false" width="200" max-rows="1"></fb:login-button>
</div>
<div id="signUpFb"></div>

<form id="logininfo" action="login.php" method = "post">
	<input type="text" placeholder="Username" name="USER"><br>
	<input type="password" placeholder="Password" name="PASS"><br>
	<input type="submit">
</form>

<form id="newuserinfo" action="newuser.php" method = "post">
	<input type="text" placeholder="Username" name="USER"><br>
	<input type="password" placeholder="Password" name="PASS"><br>
	<input type="text" placeholder="First Name" name="FN"><br>
	<input type="text" placeholder="Last Name" name="LN"><br>
	<input type="text" placeholder="email" name="EMAIL"><br>
	<input type="submit">
</form>

<script>
	$("#fbLogin").hide();
	$("#logininfo").hide();
	$("#newuserinfo").hide();
	$("#signUpFb").hide();
	
	$("#fbLogin").click(function(event){
		$("#login").hide();
		$("#newuser").hide();
		$("#fbLogin").show();
		$("#logininfo").show();
		event.stopPropagation();
	});
	
	$("#signUpFb").click(function(event){
		event.stopPropagation();
		signup_with_fb();
	});
	
	$("#login").click(function(event){
		$("#login").hide();
		$("#newuser").hide();
		$("#fbLogin").show();
		$("#logininfo").show();
		event.stopPropagation();
	});
	$("#newuser").click(function(event){
		$("#login").hide();
		$("#newuser").hide();
		$("#newuserinfo").show();
		$("#signUpFb").show();
		event.stopPropagation();
	});
	$("#newuserinfo").click(function(event){
		event.stopPropagation();
	});
	$("#logininfo").click(function(event){
		event.stopPropagation();
	});
	$('html').not('#newuserinfo').click(function(){
		$("#logininfo").hide();
		$("#newuserinfo").hide();
		$("#fbLogin").hide();
		$("#signUpFb").hide();
		$("#login").show();
		$("#newuser").show();
	});

</script>

</div>
</div>
</body>

<html>