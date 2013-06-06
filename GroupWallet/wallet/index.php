<?php
session_start();
ob_start();

$_SESSION['s_URL'] = "wallet";
if(!isset($_SESSION['login'])){
	$_SESSION['login'] = 0; 
	$_SESSION['s_userID'] = 0;
	$_SESSION['s_walletID'] = 0;
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
		$con=mysqli_connect("GroupWallet.db.10824701.hostedresource.com","GroupWallet","P1@neCamer@","GroupWallet");
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$query = "UPDATE walletR SET activity = 0 WHERE userID = '$_SESSION[s_userID]' AND walletID = '$_SESSION[s_walletID]'";
		$result = mysqli_query($con, $query);
		mysqli_close($con);
	?>
	
	<script>
		var paymentID;
		var alreadyMarked;
		var inFun = 0;
		var upPage = 1;
		var upMax = 0;
		var numUsers = 0;
		var walletUsers = new Array();
	
		function paidButton(id){
			$.post("markasPaid.php",{ recordID: id, type: 'mark' }, function(data){
				location.reload();
			});
		}
		
		function paidAccept(id){
			$.post("markasPaid.php",{ recordID: id, type: 'accept' }, function(data){
				location.reload();
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
		
		function clickableTable(tabletype, o_d){
			var ttype = tabletype + " tr";
			var curHTML;
			var curID;
			$(ttype).not($("#toprow")).click(function(event){
				if(inFun == 1) return;
				$(this).click(function(){
					if(curID != $(this).attr('id')){
						document.getElementById(curID).innerHTML = curHTML;
						inFun = 0;
					}
				});
				$('html').click(function(){
					document.getElementById(curID).innerHTML = curHTML;
					inFun = 0;
				});
				event.stopPropagation();
				inFun = 1;
				curHTML = this.innerHTML;
				curID = $(this).attr('id');
				
				var divID;
				if($(this).attr('name')[0] == '1'){
					divID = "Caccept";
					if($(this).attr('name')[1] == o_d){ 
						divID = "Cpaid";
					}
				}
				else{ divID = "Cpay"; }
				
				this.innerHTML = '<tr><td colspan="3"><center>\
								  <div id="payment"><div id="'+divID+'"></div> \
									<button id="pay">Pay</button> \
									<button id="accept">Accept</button> \
									<div id="paid">Already Marked as Paid</div>\
								  </div></center></td></tr>';
				$("#payment").show();
				$("#accept").show();
				$("#pay").show();
				$("#paid").hide();
				if($(this).attr('name')[0] == '1'){
					$("#pay").hide(); 
					if($(this).attr('name')[1] == o_d){ 
						alreadyMarked = 1;
						$("#paid").show();
						$("#accept").hide();
					}
				}
				else{ $("#accept").hide(); }
				paymentID = $(this).attr('id');
				$("#pay").click(function(){
					paidButton(window.paymentID);
				});
				$("#accept").click(function(){
					paidAccept(window.paymentID);
				});
			});
		}
		
		function update_money_table(){
			var minTable = 12;
			$.get("getamounts.php", function(data){
				if(data != 'null'){
				var amntlist = JSON.parse(data);
				var names0 = "";
				var names1 = "";

				if(amntlist[0]){ //OWE
				for(var i = 0; i < amntlist[0].length; i++){ names0 = names0 + amntlist[0][i][0] + ","; }
				$.post("getAssociatedUsers.php",{ userID_post: JSON.stringify(names0) }, function(data){
					var names = JSON.parse(data);
					var oweTableBody = "";
					for(var i = 0; i < amntlist[0].length; i++){
						var comments = "";
						var state = "";
						var amount = convert_to_money(parseFloat(amntlist[0][i][1]));
						if(amntlist[0][i][2]){ comments = amntlist[0][i][2]; }
						if(amntlist[0][i][4] == '0'){ state = "pay"; }
						if(amntlist[0][i][4] == '1' && amntlist[0][i][5] == '0'){ state = "paid"; }
						if(amntlist[0][i][4] == '1' && amntlist[0][i][5] == '1'){ state = "accept"; }
						var oweTableBody = oweTableBody+'<tr height="30" class="tRow '+state+'" id="'+amntlist[0][i][3]+'" name="'+amntlist[0][i][4]+amntlist[0][i][5]+'">\
															<td style="width:30%"><div><div class="picDiv" id="'+state+'1"></div><center> <div id="nameDiv">'+names[i][2]+'</div></center></div></td>\
															<td style="width:20%"><center> <div id="amntDiv">'+amount+'</div></center></td>\
															<td style="width:50%"><center> <div id="commentsDiv">'+comments+'</div></center></td>\
														 </tr>';
					}
					var table_rem = minTable - amntlist[0].length;
					oweTableBody = oweTableBody+'<tr id="moneytopExtra"><td colspan="3"></td></tr>';
					for(var i = 0; i < table_rem; i++){
						oweTableBody = oweTableBody+'<tr id="moneyExtra"><td colspan="3"></td></tr>';
					}
					
					$("#moneytableowe").append('<div id="moneyHeader"></div><table class="mTable" id="owetable" style="width:100%" cellspacing="0" cellpadding="0">\
													</tr>'+oweTableBody+'</table>');	
					
					clickableTable('#owetable', '0');

				});
				}else{
					var oweTableBody = "";
					oweTableBody = oweTableBody+'<tr id="moneytopExtra"><td colspan="3"></td></tr>';
					for(var i = 0; i < minTable; i++){
						oweTableBody = oweTableBody+'<tr id="moneyExtra"><td colspan="3"></td></tr>';
					}
					$("#moneytableowe").append('<div id="moneyHeader"></div><table class="mTable" id="owedtable" style="width:100%" cellspacing="0" cellpadding="0">\
												'+oweTableBody+'</table>');
				}
				
				if(amntlist[1]){ //OWED
				for(var i = 0; i < amntlist[1].length; i++){ names1 = names1 + amntlist[1][i][0] + ","; }
				$.post("getAssociatedUsers.php",{ userID_post: JSON.stringify(names1) }, function(data){
					var names = JSON.parse(data);
					var oweTableBody = "";
					var num_payments = amntlist[1].length;
					for(var i = 0; i < num_payments; i++){
						var comments = "";
						var state = "";
						var amount = convert_to_money(parseFloat(amntlist[1][i][1]));
						if(amntlist[1][i][2]){ comments = amntlist[1][i][2]; }
						if(amntlist[1][i][4] == '0'){ state = "pay"; }
						if(amntlist[1][i][4] == '1' && amntlist[1][i][5] == '1'){ state = "paid"; }
						if(amntlist[1][i][4] == '1' && amntlist[1][i][5] == '0'){ state = "accept"; }
						var oweTableBody = oweTableBody+'<tr height="30" class="tRow '+state+'" id="'+amntlist[1][i][3]+'" name="'+amntlist[1][i][4]+amntlist[1][i][5]+'">\
															<td style="width:30%"><div><div class="picDiv" id="'+state+'1"></div><center> <div id="nameDiv">'+names[i][2]+'</div></center></div></td>\
															<td style="width:20%"><center> <div id="amntDiv">'+amount+'</div></center></td>\
															<td style="width:50%"><center> <div id="commentsDiv">'+comments+'</div></center></td>\
														</tr>';
										
					}
					if(num_payments == 0){ oweTableBody = '<tr><td style="width:30%">asfd</td><td style="width:20%">adf</td><td style="width:50%">sdaf</td></tr>'; }
					oweTableBody = oweTableBody+'<tr id="moneytopExtra"><td colspan="3"></td></tr>';
					for(var i = 0; i < minTable - num_payments; i++){
						oweTableBody = oweTableBody+'<tr id="moneyExtra"><td colspan="3"></td></tr>';
					}
					$("#moneytableowed").append('<div id="moneyHeader"></div><table class="mTable" id="owedtable" style="width:100%" cellspacing="0" cellpadding="0">\
												'+oweTableBody+'</table>');
					
					clickableTable('#owedtable', '1');
					
				});		
				}else{
					var oweTableBody = "";
					oweTableBody = oweTableBody+'<tr id="moneytopExtra"><td colspan="3"></td></tr>';
					for(var i = 0; i < minTable; i++){
						oweTableBody = oweTableBody+'<tr id="moneyExtra"><td colspan="3"></td></tr>';
					}
					$("#moneytableowed").append('<div id="moneyHeader"></div><table class="mTable" id="owedtable" style="width:100%" cellspacing="0" cellpadding="0">\
												'+oweTableBody+'</table>');
				}
				} //end -> if(data!='null')
				else{
					var oweTableBody = "";
					oweTableBody = oweTableBody+'<tr id="moneytopExtra"><td colspan="3"></td></tr>';
					for(var i = 0; i < minTable; i++){
						oweTableBody = oweTableBody+'<tr id="moneyExtra"><td colspan="3"></td></tr>';
					}
					$("#moneytableowe").append('<div id="moneyHeader"></div><table class="mTable" id="owedtable" style="width:100%" cellspacing="0" cellpadding="0">\
												'+oweTableBody+'</table>');
					$("#moneytableowed").append('<div id="moneyHeader"></div><table class="mTable" id="owedtable" style="width:100%" cellspacing="0" cellpadding="0">\
												'+oweTableBody+'</table>');
				}
			});
		}
	
		function newPerson(){
			$("#adduserform").show();
		}
	
		function user_page(direction){
			var upArray = new Array();
			var hideArray = new Array();
			for(var i = 0; i < 5; i++){
				upArray[i] = "#up"+((5*upPage)-5+i);
			}
			if(direction == -1){
				for(var i = 0; i < 5; i++){
					hideArray[i] = "#up"+((5*upPage)+i);
				}	
				for(var i = 0; i < 5; i++){
					$(hideArray[i]).hide('slow');
				}
			}
			if(direction == 1){
				for(var i = 0; i < 5; i++){
					hideArray[i] = "#up"+((5*upPage)-10+i);
				}	
				for(var i = 0; i < 5; i++){
					$(hideArray[i]).hide('slow');
				}
			}
			for(var i = 0; i < 5; i++){
				$(upArray[i]).show('slow');
			}
		}
		
		function right_page_arrow(){
			$("#pgBack").hide();
			if(upPage > 1){
				$("#pgBack").show();
				$("#pgBack").click(function(){
					$("#pgForward").off('click');
					$("#pgBack").off('click');
					upPage--;
					user_page(-1);
					page_arrows();
					return;
				});
			}
		}
		
		function left_page_arrow(){
			$("#pgForward").hide();
			if(upPage < upMax){
				$("#pgForward").show();
				$("#pgForward").click(function(){
					$("#pgForward").off('click');
					$("#pgBack").off('click');
					upPage++;
					user_page(1);
					page_arrows(0);
					
					return;
				});
			}
		}
		
		function page_arrows(){
			right_page_arrow();
			left_page_arrow();
		}
		
		function user_search(){
			$.post('searchusers.php', $("#adduserform").serialize(),function(data){
				var matched = JSON.parse(data);
				if(matched == '0') return 0;
				var nextPg = 0;
				var isHidden = "";
				var k = 0; var found = 0;
				var matchedUsers = new Array();
				for(var i = 0; i < matched.length; i++){
					for(var j = 0; j < walletUsers.length; j++){
						if(matched[i][3] == walletUsers[j]){ found = 1; break; }
					}
					if(found == 0){ matchedUsers[k] = matched[k]; k++; }
				}
				
				var savedUsers = numUsers;
				for(var i=0; i < matchedUsers.length; i++){
					if(numUsers%5 == 0){ 
						upMax++;
						nextPg = 1;
					}
					if(nextPg) isHidden = 'hidden="true"';
					$("#walletusers").append('<div class="userProfiles searched clickOff search" id="up'+numUsers+'" '+isHidden+'>\
					<form class="clickOff" name="'+matchedUsers[i][0]+'" id="user_'+matchedUsers[i][0]+'" action="adduser.php" method="post">\
					<div class="clickOff" id="names">'+matchedUsers[i][1] + ' ' + matchedUsers[i][2] + '<br>('+ matchedUsers[i][0] +')</div> \
					<div class="clickOff" id="picture" style="background: black url(../userPhotos/user'+ matchedUsers[i][3]+') no-repeat center;"></div>\
					<div class="clickOff" id="money_amount"><input type="hidden" name="NAME" value = "'+matchedUsers[i][0]+'"><input class="clickOff" type="submit" /></div></form></div>');
					numUsers++;
				}
				if(upPage < upMax){
					$("#pgForward").show();
				}
				$('html').click(function(e){
					if($(e.target).hasClass('searched')){
						return;
					}
					$(".search").remove();
					numUsers = savedUsers;
				});
			});
		}
		
		function selectUser(id, user){
			if($('.splitForm').is(':visible')){
				var userStrC = "#checkbox_" + user.getAttribute('id');
				if(id.style.backgroundColor == 'blue'){
					id.style.backgroundColor = 'transparent';
					$(userStrC).prop('checked',false);
				}else{
					id.style.backgroundColor = 'blue';
					$(userStrC).prop('checked',true);
				}
			}
		}
	
	</script>
	
</head>

<body>
<center>
<div id="header"></div>
<div id="topButtons">
	<button id="back">Back to Wallet List</button>
	<button id="payfor">Pay for</button>
	<button id="paidfor">Paid for</button>
</div>

<div id="moneytables">
	<div id="oweOutside">
		<div id="moneytableowe"></div>
		<div id="moneyBottom"></div>
	</div>
	<div id="owedOutside">
		<div id="moneytableowed"></div>
		<div id="moneyBottom"></div>
	</div>
</div>
<div id="payment">
	<button id="pay">Pay</button>
	<button id="accept">Accept</button>
</div>

<div class="clickOff" id="addpayment">
	<div class="clickOff" id="innerpayment">
		<div class="clickOff" id="walletusers">
		</div>
	</div>
	<button id="x"></button>
	<div class="clickOff searched" id="pgBack"></div>
	<div class="clickOff searched" id="pgForward"></div>
	<button class="clickOff" id="multipleSubmit">Submit All</button>
	<button class="clickOff" id="splitCosts">Split Costs</button>
	<div class="clickOff splitForm" id="splitPopup">
		<div class="clickOff splitForm" id="splitters"></div>
		<div class="clickOff splitForm" id="splitee"></div>
	</div>
	<form class="clickOff splitForm" name="splitAmntform" id="splitAmntform"><input type="text" class="clickOff splitForm" id="splitAmnt" name ="splitAmnt"></form>
	<button class="clickOff splitForm" id="splitGo">Go</button>
	<button class="clickOff splitForm" id="splitCancel">Cancel</button>
</div>



<script>
	$("#back").click(function(){
		window.location = "http://jondh.com/GroupWallet/home";
	});

	$("#pgBack").hide();
	$("#pgForward").hide();
	$("#adduserform").hide();
	$("#payment").hide();
	$("#addpayment").hide();
	
	update_money_table();
		
	$("#payfor").click(function(event){
		$("#addpayment").show();
		event.stopPropagation();
	});
	$("#paidfor").click(function(event){
		$("#addpayment").show();
		event.stopPropagation();
	});
	
	$("#adduserform").click(function(event){
		event.stopPropagation();
	});
	$("#splitCancel").click(function(event){
		$(".splitForm").hide();
	});
	$('html').click(function(e){
		if($(e.target).hasClass('clickOff')){
			return;
		}
		$("#addpayment").hide();
		$("#adduserform").hide();
		$("#adduser").show();
		$(".splitForm").hide();
	});
	
	$.get("getWalletUsers.php", function(data){
		$.post("getAssociatedUsers.php",{ userID_post: data }, function(data1){
			var walletNames = JSON.parse(data1);
			for(var i=0; i < walletNames.length; i++){
				$("#walletusers").append('<div class="clickOff userProfiles upArea" id="up'+numUsers+'" hidden="true" onclick="selectUser(up'+numUsers+', '+walletNames[i][2]+')">\
				<form class="clickOff upArea" name="'+walletNames[i][2]+'" id="user_'+walletNames[i][2]+'" action="insertrecords.php" method="post">\
				<div class="clickOff upArea" id="names">'+walletNames[i][0] + ' ' + walletNames[i][1] + '<br>('+ walletNames[i][2] +')</div> \
				<div class="clickOff upArea" id="picture" style="background: black url(../userPhotos/user'+walletNames[i][3]+') no-repeat center;"></div>\
				<div class="clickOff upArea" id="money_amount"><input id="money_amnt_text" class="clickOff" type="text" name="AMNT"/></div> \
				<div class="clickOff upArea" id="owe_owed">OWE: <input class="clickOff" type="radio" name="owe" value="OWE"/><br> \
				OWED: <input class="clickOff upArea" type="radio" name="owe" value="OWED"/> \
				<input type="hidden" name="otherUID" value="'+walletNames[i][2]+'"/></div> \
				<div class="clickOff upArea" id="comments">Comments: <textarea class="clickOff" id="comments_text" name="comments_post" cols="11" rows="5"></textarea>\
				<br><input class="clickOff splitForm ckBox" id="checkbox_user_'+walletNames[i][2]+'" type="checkbox" name="checked"></div></form></div>');
				walletUsers[i] = walletNames[i][3];
				if(numUsers%5 == 0) upMax++;
				numUsers++;
			}
			$("#walletusers").append('<div class="clickOff userProfiles upArea" id="up'+numUsers+'" hidden="true">\
										<div class="clickOff upArea" id="names">Add new <br>PERSON</div>\
										<div onclick="newPerson()" class="clickOff upArea" id="picture" style="background: blue"></div>\
										<div class="clickOff" id="money_amount"><form class="clickOff upArea" id="adduserform">\
											Name: <br><input class="clickOff upArea" id="adduser_text" type="text" name="NAME"><br>\
										</form><button class="clickOff upArea" onclick="user_search()">Search</button></div>\
									</div>');
			if(numUsers%5 == 0) upMax++;
			numUsers++;
			user_page(0);
			page_arrows();
			$(".splitForm").hide();
			
			$("#multipleSubmit").click(function(){
				var recordsAdded = 0;
				var totalRecords = walletNames.length;
				for(var i=0; i < walletNames.length; i++){
					var notValid = 0;
					var userString = "#user_" + walletNames[i][2];
					var serForm = $(userString).serialize();
					if(serForm[5] == '&'){ notValid = 1; }
					if(serForm.indexOf("owe") < 0 || serForm.indexOf("owe") > 35){ notValid = 1; } //max amount of 25 digits
					if(notValid == 0){
						$.post("insertrecords.php", serForm, function(){ 
							recordsAdded++; 
							for(var j=0; j < walletNames.length; j++){
								var userStr = "#user_" + walletNames[j][2];
								$(userStr).find('input:text, textarea').val('');
								$(userStr).find('input:radio').removeAttr('checked');
							}
						});
					}else{ totalRecords--; }
				}
			});
		
			$("#splitCosts").click(function(){
				$(".splitForm").show();
				$(".ckBox").hide();
				$("#splitGo").click(function(){
					var checkedUIDs = new Array();
					var j = 0;
					var inputValue = document.splitAmntform.splitAmnt.value;
					for(var i=0; i < walletNames.length; i++){
						if(document.forms[walletNames[i][2]].checked.checked){
							checkedUIDs[j] = walletNames[i][3];
							j++;
						}
					}
					if($.isNumeric(inputValue) && checkedUIDs){ //need to fix click repeat on html exit
						$.post("insertSplitRecords.php",{ value: inputValue, idArray: checkedUIDs }, function(){
							for(var i=0; i < walletNames.length; i++){
								var userStrC = "#checkbox_user_"+walletNames[i][2];
								var ckedid = "up" + i;
								document.getElementById(ckedid).style.backgroundColor = 'transparent';
								$(userStrC).prop('checked',false);
							}
							$(".splitForm").hide();
						});
					}
				});
			});
		});
	});	
	
</script>
</center>
</body>

<html>