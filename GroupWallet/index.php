<?php
session_start();
ob_start();

if(!isset($_SESSION['login'])){
	$_SESSION['login'] = 0; 
	$_SESSION['s_userID'] = 0;
	$_SESSION['s_walletID'] = 0;
	$_SESSION['s_URL'] = "home";
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
	<link href='http://fonts.googleapis.com/css?family=Oswald:300' rel='stylesheet' type='text/css'>
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.1.1.min.js"></script>
	<script src="http://jondh.com/jquery-1.9.1.min.js"></script>
	
	<?php
		if($_SESSION['login'] == 0){
			$returnurl = "http://jondh.com/GroupWallet/login/";
			header("Location: $returnurl");
		}
	?>

	<script>
		//var fb_login = "<?php echo $_SESSION['fb']; ?>";
	
		function gotoWallet(walID){
			$.post("gotoWallet.php",{ walletID: walID }, function(){
				document.location.reload();
			});
		}
		
		function convert_to_money(amount){
			var totalAmnt = amount.toFixed(2); // put number to two decimal places
			var pos = 1;
			if(totalAmnt < 0){	// convert to positive and set as negitive
				totalAmnt = (-1 * amount).toFixed(2);
				pos = 0;
			}
			totalAmnt += ""; // convert to string
			var cash = ""; // return string
			var j = 0;
			for(j = 0; j < (totalAmnt.length-3)%3; j++){ // put digits before first comma into string
				cash += totalAmnt[j];
			}
			if(j != 0 && totalAmnt.length > 6){ cash += ','; } // insert comma if cash contains anything and number is big enough
			var k = 0;
			var i = j;
			for(i; i <= (totalAmnt.length-3) - (totalAmnt.length-3)%3 + j - 2; i++){
				cash += totalAmnt[i];
				k++;
				if(k == 3){ cash += ','; k = 0; }
			}
			cash += totalAmnt[i];
			i++;
			for(var m = 0; m < 3; m++){
				if(totalAmnt[i]){
					cash += totalAmnt[i];
					i++;
				}
			}
			if(pos == 0){
				cash = "<FONT COLOR='#981b1e'>$(-" + cash + ")</FONT>";
			}else cash = '$'+cash;
			return cash;
		} // END convert_to_money
	
		function insert_user_data(dArray){
			var info = "";
			if(dArray[2]){ 
				info = dArray[2];
				if(dArray[3]){ 
					info += " " + dArray[3];
					$('#userName').before("<div style='font-size:20px;'>Welcome, " + info + "</div>");
				}else { $('#userName').before("<div style='font-size:20px;'>Welcome, " + info + "</div>"); }
			}
			else if(dArray[3]){ info = dArray[3]; $('#userName').before("<div style='font-size:20px;'>Welcome, " + info + "</div>"); }
			else{ info = dArray[1]; $('#userName').before("<div style='font-size:20px;'>Welcome, " + info + "</div>"); }
			$.get("getTotalMoney.php", function(data){
				$("#profileInfo").append(info + "<br>" + dArray[4] + "<br>" + convert_to_money(JSON.parse(data)))
			});
		}
		
		function update_user_info(){
			$.get("updateNotify.php", function(data){
				var names = JSON.parse(data);
				var form_data = '';
				for(var i = 0; i < names.length; i++){
					var form_data = '<form id="walletnotifyform" action="walletnotifyaction.php" method="post">\
								You have been invited to join the wallet: '+names[i][0]+'<br>Please Choose: \
								Accept:<input type="radio" name="YN" value="accept"> \
								Decline:<input type="radio" name="YN" value="decline">\
								<input type="hidden" name="walletRid" value="'+names[i][1]+'">\
								<input type="submit"><br></form>';
								$("#inviteDD").append(form_data);
				}
				document.getElementById('inviteActivity').innerHTML = names.length;
			});
			$.get("updateWalletActions.php", function(data){
				var walletActivityList = JSON.parse(data);
				document.getElementById('numWalletActivity').innerHTML = walletActivityList.length;
				for(var i = 0; i < walletActivityList.length; i++){
					$("#activityDD").append('<button class="dropDown" id="viewWallet" onclick="gotoWallet('+walletActivityList[i][1]+')">' + walletActivityList[i][0] + '</button><br>');
				}
			});
		}
	</script>

</head>

<body>

<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '315826831884119', // App ID
      channelUrl : '/channel.html', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });
  };

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
</script>

<center>
<div id="header">
	<div id="userName">
		<button id="logout">Logout</button>
	</div>
</div>
</center>
<div id="pagewrap">
<div id="pageLeft"></div>
<div id="pageRight"></div>
<div id="pageTop"></div>
<div id="pageBottom"></div>

<div id="profile">
	<div id="profilePic"></div>
	<div id="profileInfo"></div>
	<div id="notInfo">
		<div class="walact" id="activity">
			<div class="walact" id="not1"></div>
			There is activity in (<span id="numWalletActivity">0</span>) wallets.
		</div>
		<div class="walinvite" id="join">
			<div class="walinvite" id="not2"></div>
			You are invited to join (<span id="inviteActivity">0</span>) wallets.
		</div>
	</div>
</div>

<iframe id="iframe"  src="http://jondh.com/GroupWallet/home"></iframe>
<center>
<div class="dropDown" id="activityDD"></div>
</center>
</div>
<center>
<div class="dropDown" id="inviteDD"></div>
</center>

<form action="upload_pic.php" method="post" enctype="multipart/form-data">
	<label for="file">Filename:</label>
	<input type="file" name="file" id="file"><br>
	<input type ="submit" name="submit" value="Upload">
</form>

<script>
	$("#activityDD").hide();
	$("#inviteDD").hide();

	$("#activity").click(function(event){
		event.stopPropagation();
		$("#activityDD").show();
	});
	$("#join").click(function(event){
		event.stopPropagation();
		$("#inviteDD").show();
	});
	$('html').click(function(e){
		if($(e.target).hasClass('dropDown')){
			return false;
		}
		$("#activityDD").hide();
		$("#inviteDD").hide();
	});
	
	var newurl = "<?php echo $_SESSION['s_URL']; ?>";
	$("#iframe").attr('src', newurl);
	
	$("#logout").click(function(){
		var fb_login = "<?php echo $_SESSION['fb']; ?>";
		$.post("logout.php",{ endsession_bool: 0}, function(){
			if(fb_login == 1) FB.logout(function(response){ document.location.reload(); }); 
			else document.location.reload();
			
		});
	});
	
	update_user_info();
	$.get("getInfo.php", function(data){
		var userinfoarr = JSON.parse(data);
		insert_user_data(userinfoarr);
	});
	
	$("#profilePic").css("background", "black url(userPhotos/user"+<?php echo $_SESSION['s_userID'] ?>+".jpg) no-repeat center");
	
</script>

</div> <!-- END pagewrap //-->
</body>

<html>
