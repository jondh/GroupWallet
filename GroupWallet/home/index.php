<?php
session_start();
ob_start();

$_SESSION['s_URL'] = "home";
if(!isset($_SESSION['login'])){
	$_SESSION['login'] = 0; 
	$_SESSION['s_userID'] = 0;
	$_SESSION['s_walletID'] = 0;
}
?>

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
	
	<script>
		var curPage = 1;
		var maxPage = 1;
		var done = true;
		var skipped = false;
		
		function card_hover(){
			$(".wallbutt").hover(
				function(){
					if(done){
						skipped = false;
						done = true;
						$(this).animate({
							top: '-=120'
						}, 200, function(){ });
					}else{
						skipped = true;
					}
				},
				function(){
					if(!skipped){
						$(this).animate({
							top: '+=120'
						}, 200, function(){ done = true; });
					}
				} 
			);
		}
		
		function wallet_page(direction){
			var walArray = new Array();
			var hideArray = new Array();
			for(var i = 0; i < 6; i++){
				walArray[i] = ".wal"+((6*curPage)-6+i);
			}
			if(direction == -1){
				for(var i = 0; i < 6; i++){
					hideArray[i] = ".wal"+((6*curPage)+i);
				}	
				for(var i = 0; i < 6; i++){
					$(hideArray[i]).animate({
						top: '+=50'
					}, 600, function(){ $(this).hide(); });
				}
			}
			if(direction == 1){
				for(var i = 0; i < 6; i++){
					hideArray[i] = ".wal"+((6*curPage)-12+i);
				}	
				for(var i = 0; i < 6; i++){
					$(hideArray[i]).animate({
						top: '+=50'
					}, 600, function(){ $(this).hide(); });
				}
			}
			for(var i = 0; i < 6; i++){
				$(walArray[i]).show();
				$(walArray[i]).animate({
					top: '-=50'
				}, 600, function(){ });
			}
		}
		
		function right_page_arrow(){
			$("#pgBack").hide();
			if(curPage > 1){
				$("#pgBack").show();
				$("#pgBack").click(function(){
					$("#pgForward").off('click');
					$("#pgBack").off('click');
					curPage--;
					wallet_page(-1);
					page_arrows();
					return;
				});
			}
		}
		
		function left_page_arrow(){
			$("#pgForward").hide();
			if(curPage < maxPage){
				$("#pgForward").show();
				$("#pgForward").click(function(){
					$("#pgForward").off('click');
					$("#pgBack").off('click');
					curPage++;
					wallet_page(1);
					page_arrows(0);
					
					return;
				});
			}
		}
		
		function page_arrows(){
			right_page_arrow();
			left_page_arrow();
		}
	</script>
	
</head>

<body>
<center>
<div id="wallet">
<div id="walletHead"></div>
<div id="bottomWallets"><div id="walletPocket"></div></div>
<div id="middleWallets"><div id="walletPocket"></div></div>
<div id="topWallets"><div id="walletPocket"></div></div>
<div id="pgBack"></div>
<div id="pgForward"></div>
</div>
<div id="walletFoot"></div>

<div id="walletlist"></div>

<script>
	$("#pgForward").hide();
	$("#pgBack").hide();

	$.get("getWalletInfo.php", function(data){
		var retwalletinfo = JSON.parse(data);
		var stagger = -10;
		var the_z = 3;
		var i;
		var infoLength = retwalletinfo.length + 1;
		if(!retwalletinfo) infoLength = 1;
		for(i=0; i < infoLength; i++){
			var horizontal = 300;
			the_z += 2;
			if(i%2){ horizontal += 215; stagger += 0; }
			else{ horizontal += -215; stagger += 0; stagger += 150; }
			if( i < retwalletinfo.length){
				$("#walletlist").append('<div class="wallbutt wal'+i+'" name="'+retwalletinfo[i][1]+'" id="wallet'+retwalletinfo[i][0]+'" style="top:'+stagger+'px; left:'+horizontal+'px">\
										<div id="cardTop" style="z-index: '+the_z+'"><p>'+retwalletinfo[i][1]+'</p></div>\
										<div id="cardBottom" style="z-index: '+the_z+'">Date Created: '+retwalletinfo[i][2]+'</div>\
										</div>');
				if(i > 5) $("#wallet" + retwalletinfo[i][0]).hide();
			}else{
				$("#walletlist").append('<div class="wallbutt wal'+i+'" id="addWallet" style="top:'+stagger+'px; left:'+horizontal+'px">\
							<div id="cardTop" style="z-index: '+the_z+'"><p>Add Wallet</p></div>\
							<div id="cardBottom" style="z-index: '+the_z+'">\
								<form id="newwalletform" action="newwallet.php" method = "post">\
									Name: <input type="text" name="NAME"><br>\
									<input type="submit">\
								</form>\
							</div>\
							</div>');
				if(i > 5) $("#addWallet").hide();
			}
			if(!((i+1)%6)){ maxPage++; the_z = 3; stagger = 40;}
		}
		page_arrows();
		$(".wallbutt").click(function(){
			if($(this).attr('id') != "addWallet"){
				$.post("storeWallID.php",{ wallID_post: this.id.replace("wallet","") }, function(){
					window.location = "http://jondh.com/GroupWallet/wallet";
				});
			}
		});
		card_hover();
		
	});			
</script>
</center>
</body>

<html>