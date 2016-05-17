<?php
session_start();
ob_start();
include_once '../../connect.php';
if(!isset($_SESSION['user']))
{
	header("Location: ../index.php");
}
$res=mysql_query("SELECT * FROM users WHERE user_id=".$_SESSION['user']); //Hämtar raden från databasen "users" där "user_id" stämmer med den inloggade användaren
$userRow=mysql_fetch_array($res);
$res2=mysql_query("SELECT * FROM budget WHERE user_id=".$_SESSION['user']); //Hämtar raden från databasen "budget" där "user_id" stämmer med den inloggade användaren
$budgetRow=mysql_fetch_array($res2);
$res3=mysql_query("SELECT * FROM category WHERE user_id=".$_SESSION['user']); //Hämtar raden från databasen "category" där "user_id" stämmer med den inloggade användaren
$categoryRow=mysql_fetch_array($res3);

$percent = intval(($budgetRow['currentCash'] / $budgetRow['monthCash']) * 100); //Räknar ut hur mycket i procent man har kvar av sin budget genom att ta info från databasen.
$percentLeft = 100 - $percent; //Räknar ut hur mycket procent man har spenderat totalt.

$daysleft = date('t') - date('j'); //Kollar hur många dagar det är kvar tills månaden är slut.
if($daysleft == 0){ //Om det är sista dagen i månaden så finns det ett error då den dividerar med 0. Det fixar denna funktionen.
   $daysleft = 1;
}

$spendperday = intval($budgetRow['currentCash'] / $daysleft);


if(isset($_POST['spent']))
//Funktionen då användaren lägger till nya köp
//Den kollar vilken kategori som användaren har registrerat köpet på.
//Sedan adderar den det man nyss har lagt till med det som redan fanns på kategorin.
//Sedan laddar den om sidan i samma funktion så att användaren upplever att sidan reagerar direkt på det den nyss gjorde.
{
	$spent = mysql_real_escape_string($_POST['spentCash']);
	$mSpent = mysql_real_escape_string($_POST['spentCash']);
	$hSpent = mysql_real_escape_string($_POST['spentCash']);
	$nSpent = mysql_real_escape_string($_POST['spentCash']);
	$tSpent = mysql_real_escape_string($_POST['spentCash']);
	$oSpent = mysql_real_escape_string($_POST['spentCash']);
	$newCurrent = $budgetRow['currentCash'] - $spent;
	$totalspent = $budgetRow['spentCash'] + $spent;
	$newMat = $categoryRow['mat'] + $mSpent;
	$newHalsa = $categoryRow['halsa'] + $hSpent;
	$newNoje = $categoryRow['noje'] + $nSpent;
	$newTransport = $categoryRow['transport'] + $tSpent;
	$newOvrigt = $categoryRow['ovrigt'] + $oSpent;
	if(mysql_query("UPDATE budget SET currentCash='$newCurrent' WHERE user_id=".$_SESSION['user'])) {
		if (mysql_query("UPDATE budget SET spentCash='$totalspent' WHERE user_id=" . $_SESSION['user'])) {
			switch ($_POST['category']) {
				case "mTrue":
					mysql_query("UPDATE category SET mat='$newMat' WHERE user_id=" . $_SESSION['user']);
					?>
					<script>
						setTimeout(function(){
							window.location = "./logout.php";
						}, 100);
					</script>
					<?php
					break;
				case "hTrue":
					mysql_query("UPDATE category SET halsa='$newHalsa' WHERE user_id=" . $_SESSION['user']);
					?>
					<script>
						setTimeout(function(){
							window.location = "./logout.php";
						}, 100);
					</script>
					<?php
					break;
				case "nTrue":
					mysql_query("UPDATE category SET noje='$newNoje' WHERE user_id=" . $_SESSION['user']);
					?>
					<script>
						setTimeout(function(){
							window.location = "./logout.php";
						}, 100);
					</script>
					<?php
					break;
				case "tTrue":
					mysql_query("UPDATE category SET transport='$newTransport' WHERE user_id=" . $_SESSION['user']);
					?>
					<script>
						setTimeout(function(){
							window.location = "./logout.php";
						}, 100);
					</script>
					<?php
					break;
				case "oTrue":
					mysql_query("UPDATE category SET ovrigt='$newOvrigt' WHERE user_id=" . $_SESSION['user']);
					?>
					<script>
						setTimeout(function(){
							window.location = "./logout.php";
						}, 100);
					</script>
					<?php
					break;
				default:
					$nuller = $categoryRow['mat'];
					mysql_query("UPDATE category SET mat='$nuller' WHERE user_id=" . $_SESSION['user']);
					?>
					<script>
							window.location = "./logout.php";
					</script>
					<?php
			}
		} else {
			?>
			<script>
				alert("Error med spent");
			</script>
			<?php
		}
	}
	else
	{
		?>
		<script>alert('Fel');</script>
		<?php
	}
}
if(isset($_POST['added']))
//Detta händer då formen "added" har blivit summerad av användaren
//Den tar användarens input och lägger ihop det med användarens nuvarande balans
//Sedan om den nya balansen går över startbudgeten så sätter den ett nytt "tak"
//Som sedan stannar där som hela programmet sedan utgår ifrån.
{
	$added = mysql_real_escape_string($_POST['addedCash']);
	$newCurrent = $budgetRow['currentCash'] + $added;
	if(mysql_query("UPDATE budget SET currentCash='$newCurrent' WHERE user_id=".$_SESSION['user']))
	{
		if($budgetRow['currentCash'] >= $budgetRow['monthCash']){
			mysql_query("UPDATE budget SET monthCash='$newCurrent' WHERE user_id=".$_SESSION['user']);
			$reloader = $newCurrent + 0;
			mysql_query("UPDATE budget SET monthCash='$reloader' WHERE user_id=".$_SESSION['user']);
			header("Location: home.php");
		}
		else{
			header("Location: home.php");
		}
	}
	else
	{
		?>
		<script>alert('Fel');</script>
		<?php
	}
}
?>
<html>
<head>
	<title>Offic - Hem</title>

	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<link rel="stylesheet" href="style-portrait.css">

	<script type="text/javascript">
	//A general script that we use to hide/show objects when a specific id is pressed
	    function toggle_visibility(id) {
	       var e = document.getElementById(id);
	       if(e.style.display == 'block')
	          e.style.display = 'none';
	       else
	          e.style.display = 'block';
	    }
	</script>


	<link rel="apple-touch-icon" sizes="57x57" href="http://i.imgur.com/zsPu8aG.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="http://i.imgur.com/Jl3PUYp.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="http://i.imgur.com/r6cOnlW.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="http://i.imgur.com/X65XiZu.png" />


	<link rel="shortcut icon" type="image/x-icon" href="img/pageicon.ico" />

	<meta name="apple-mobile-web-app-capable" content="yes">

	<link rel="stylesheet" type="text/css" href="css/addtohomescreen.css">
	<script src="js/addtohomescreen.js"></script>
	<script>
	//Calls on the homescreen function so the user dont forgets to download our application.
		addToHomescreen();
	</script>

	<link rel="stylesheet" href="./css/bootstrap.css">
	<script type="text/javascript" src="./js/bootstrap.js"></script>


	<meta name="HandheldFriendly" content="true" />
	<meta name="viewport" content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" />
 


	<script type="text/javascript">
	//Checks the angel of the phone and with that info it decides if it should display "portrait" or "landscape"
		var supportsOrientationChange = "onorientationchange" in window,
		    orientationEvent = supportsOrientationChange ? "orientationchange" : "resize";
		window.addEventListener(orientationEvent, function() {
		    if(window.orientation==0)
		    {
		      document.getElementById('portrait').style.display = 'block';
		      document.getElementById('landscape').style.display = 'none';
		    }
		    else if(window.orientation== -90)
		    {
		      document.getElementById('portrait').style.display = 'none';
		      document.getElementById('landscape').style.display = 'block';
		    }
		    else if(window.orientation== 90)
		    {
		      document.getElementById('portrait').style.display = 'none';
		      document.getElementById('landscape').style.display = 'block';
		    }
		}, false);
	</script>
	<script>
	//This functions either display the "purchase" page where the user can spent money
	//Or it displays the "salary" page where the user can add money to theire account
		function purchase() {
			document.getElementById('purchase').style.display = "block";
		}
		function salary() {
			document.getElementById('salary').style.display = "block";
		}
	</script>
	<script>
	//jQuery function that displays how much the user has spent on each category
	//It gather information from the database with the php echo function
		$(document).ready(function() {
			$('.spentbar1').on('click touchstart', function() {
				$(".title1").html( "<?php echo $categoryRow['mat']?> kr" );
				$(".title1").css('color', 'gray');
				setTimeout(function(){
					$(".title1").html( "Mat" );
					$(".title1").css('color', '#c6cac8');
				}, 3000);
			});
			$('.spentbar2').on('click touchstart', function() {
				$(".title2").html( "<?php echo $categoryRow['halsa']?> kr" );
				$(".title2").css('color', 'gray');
				setTimeout(function(){
					$(".title2").html( "Hälsa" );
					$(".title2").css('color', '#c6cac8');
				}, 3000);
			});
			$('.spentbar3').on('click touchstart', function() {
				$(".title3").html( "<?php echo $categoryRow['noje']?> kr" );
				$(".title3").css('color', 'gray');
				setTimeout(function(){
					$(".title3").html( "Nöje" );
					$(".title3").css('color', '#c6cac8');
				}, 3000);
			});
			$('.spentbar4').on('click touchstart', function() {
				$(".title4").html( "<?php echo $categoryRow['transport']?> kr" );
				$(".title4").css('color', 'gray');
				setTimeout(function(){
					$(".title4").html( "Transport" );
					$(".title4").css('color', '#c6cac8');
				}, 3000);
			});
			$('.spentbar5').on('click touchstart', function() {
				$(".title5").html( "<?php echo $categoryRow['ovrigt']?> kr" );
				$(".title5").css('color', 'gray');
				setTimeout(function(){
					$(".title5").html( "Övrigt" );
					$(".title5").css('color', '#c6cac8');
				}, 3000);
			});
		});
	</script>
	<script language="javascript">
	//This script disables some basic features that IOS got implemented in their browser
	//Removes the rubberband effec etc.
		 document.body.addEventListener('touchmove', function(e){
				e.preventDefault();
		 });
		 document.body.addEventListener('touchstart', function(e){
			 e.preventDefault();
		 });
	</script>
	<script>
	//This script block displays and hides the checkboxes on the page when the user adds new purchases.
		$(document).ready(function() {
			$("#matstyle").click(function () {
				$("input[name=category][value=mTrue]").attr("checked","checked");
				$(".checked1").css("display", "block");
				$(".unchecked1").css("display", "none");
				$(".checked2").css("display", "none");
				$(".unchecked2").css("display", "block");
				$(".checked3").css("display", "none");
				$(".unchecked3").css("display", "block");
				$(".checked4").css("display", "none");
				$(".unchecked4").css("display", "block");
				$(".checked5").css("display", "none");
				$(".unchecked5").css("display", "block");
			});
			$("#halsastyle").click(function () {
				$("input[name=category][value=hTrue]").attr("checked","checked");
				$(".checked1").css("display", "none");
				$(".unchecked1").css("display", "block");
				$(".checked2").css("display", "block");
				$(".unchecked2").css("display", "none");
				$(".checked3").css("display", "none");
				$(".unchecked3").css("display", "block");
				$(".checked4").css("display", "none");
				$(".unchecked4").css("display", "block");
				$(".checked5").css("display", "none");
				$(".unchecked5").css("display", "block");
			});
			$("#nojestyle").click(function () {
				$("input[name=category][value=nTrue]").attr("checked","checked");
				$(".checked1").css("display", "none");
				$(".unchecked1").css("display", "block");
				$(".checked2").css("display", "none");
				$(".unchecked2").css("display", "block");
				$(".checked3").css("display", "block");
				$(".unchecked3").css("display", "none");
				$(".checked4").css("display", "none");
				$(".unchecked4").css("display", "block");
				$(".checked5").css("display", "none");
				$(".unchecked5").css("display", "block");
			});
			$("#transportstyle").click(function () {
				$("input[name=category][value=tTrue]").attr("checked","checked");
				$(".checked1").css("display", "none");
				$(".unchecked1").css("display", "block");
				$(".checked2").css("display", "none");
				$(".unchecked2").css("display", "block");
				$(".checked3").css("display", "none");
				$(".unchecked3").css("display", "block");
				$(".checked4").css("display", "block");
				$(".unchecked4").css("display", "none");
				$(".checked5").css("display", "none");
				$(".unchecked5").css("display", "block");
			});
			$("#ovrigtstyle").click(function () {
				$("input[name=category][value=oTrue]").attr("checked","checked");
				$(".checked1").css("display", "none");
				$(".unchecked1").css("display", "block");
				$(".checked2").css("display", "none");
				$(".unchecked2").css("display", "block");
				$(".checked3").css("display", "nome");
				$(".unchecked3").css("display", "block");
				$(".checked4").css("display", "none");
				$(".unchecked4").css("display", "block");
				$(".checked5").css("display", "block");
				$(".unchecked5").css("display", "none");
			});
		});
	</script>
</head>
<body>
		<div id="portrait">
			<div id="purchase">
				<form class="form" method="post">
					<div style="height: 10%; width: 90%;">
						<input required name="spentCash" type="number" pattern="[0-9]*" inputmode="numeric" required placeholder="Handlade nyss för" id="moneyinput"></input>
					</div>
					<div id="matstyle" style="margin-top: 7.5%;">
						<div id="category-title">Mat</div>
						<input type="radio" id="radio1" name="category" value="mTrue" required style="display: none;"></input>
						<img src="../../img/unchecked.png" style="height: 60%; float: right; margin-top: 4%;margin-right: 5%; display: block;" class="unchecked1">
						<img src="../../img/checked.png" style="height: 60%; float: right; margin-top: 4%;margin-right: 5%; display: none;" class="checked1">
					</div>
					<div id="halsastyle">
						<div id="category-title">Hälsa</div>
						<input type="radio" name="category" value="hTrue" required style="display: none;"></input>
						<img src="../../img/unchecked.png" style="height: 60%; float: right; margin-right: 5%; margin-top: 4%; display: block;" class="unchecked2">
						<img src="../../img/checked.png" style="height: 60%; float: right; margin-right: 5%; margin-top: 4%; display: none;" class="checked2">
					</div>
					<div id="nojestyle">
						<div id="category-title">Nöje</div>
						<input type="radio" name="category" value="nTrue" required style="display: none;"></input>
						<img src="../../img/unchecked.png" style="height: 60%; float: right; margin-right: 5%; margin-top: 4%; display: block;" class="unchecked3">
						<img src="../../img/checked.png" style="height: 60%; float: right; margin-right: 5%; margin-top: 4%; display: none;" class="checked3">
					</div>
					<div id="transportstyle">
						<div id="category-title">Transport</div>
						<input type="radio" name="category" value="tTrue" required style="display: none;"></input>
						<img src="../../img/unchecked.png" style="height: 60%; float: right; margin-right: 5%; margin-top: 4%; display: block;" class="unchecked4">
						<img src="../../img/checked.png" style="height: 60%; float: right; margin-right: 5%; margin-top: 4%; display: none;" class="checked4">
					</div>
					<div id="ovrigtstyle">
						<div id="category-title">Övrigt</div>
						<input type="radio" name="category" value="oTrue" required style="display: none;"></input>
						<img src="../../img/unchecked.png" style="height: 60%; float: right; margin-right: 5%; margin-top: 4%; display: block;" class="unchecked5">
						<img src="../../img/checked.png" style="height: 60%; float: right; margin-right: 5%; margin-top: 4%; display: none;" class="checked5">
					</div>
					<input type="submit" id="button-cash" name="spent" style="width: 90%; height: 10%;"></input>
				</form>
			</div>
			<div id="salary">
				<form class="form" method="post">
					<div style="height: 10%; width: 90%;">
						<input name="addedCash" type="number" pattern="[0-9]*" inputmode="numeric" required placeholder="Tjänade nyss" id="moneyinput"</input>
					</div>
					<input type="submit" id="button-cash" name="added" style="width: 90%; height: 20%;"></input>
				</form>
			</div>
		<div id="bar">
			<p>Saldo</p>
			<a onclick="toggle_visibility('settings'); toggle_visibility('settings-title');">
				<div id="settings-btn">
					<img src="http://i.imgur.com/kgkmAPb.png" id="settings-icon">
				</div>
			</a>
			<a onclick="toggle_visibility('profile'); toggle_visibility('profile-title');">
				<div id="profile-btn">
					<img src="http://i.imgur.com/QkiKcHZ.png" id="settings-icon">
				</div>
			</a>
		</div>
		<div id="container">

			<div class="progress" onclick="mainSpent();">
				<div id="percent-position" onclick="mainSpent();"><?php echo $percent;?> %</div>
	  				<div id="moneyleftwidth" onclick="mainSpent();" class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "$percent"?>%; float: left;"></div>
	  				<div id="spentMainbar" style="height: 100%; float: left; display: none; width: <?php echo "$percentLeft"?>%;">

		  				<div id="bar1food" style="background-color: #4389ee; height: 100%; float: left;"></div>
		  				<div id="bar2halsa" style="background-color: #64af19; height: 100%; float: left;"></div>
		  				<div id="bar3noje" style="background-color: #e6c500; height: 100%; float: left;"></div>
		  				<div id="bar4trans" style="background-color: #df7a00; height: 100%; float: left;"></div>
		  				<div id="bar5ovrigt" style="background-color: #d44642; height: 100%; float: left;"></div>

	  				</div>
			</div>

			<div id="profile">
				<div id="profile-title">Profil</div>
				<div id="container">
					<div onclick="toggle_visibility('profile'); toggle_visibility('profile-title');">Tillbaka</div>

				</div>
			</div>

			<div id="settings">
				<div id="settings-title">Inställningar</div>
				<div id="container">

					<div onclick="toggle_visibility('settings'); toggle_visibility('settings-title');">Tillbaka</div>
					&nbsp;<a href="logout.php?logout">Sign Out</a>
				</div>
			</div>

			<div id="halfbox">
				<p id="leftMonth">Kvar denna månad:</p>
				<p id="infotext1"><?php echo $budgetRow['currentCash']; ?> kr</p>
			</div>

			<div id="halfbox" style="margin-left: 4%;">
				<p id="spendToday">Kan du spendera idag:</p>
				<p id="infotext2"><?php echo $spendperday; ?> kr</p>
			</div>
			<div id="fullbox" style="margin-top: 4%;">
				<div id="spentbars">
					<div id="spentbar" style="margin-left: 5%" class="spentbar1" onClick="showvalue()">
						<div id="bar1" class="spentbar1" onClick="showvalue()"></div>
					</div>
					<div id="spentbar" class="spentbar2">
						<div id="bar2" class="spentbar2"></div>
					</div>
					<div id="spentbar" class="spentbar3">
						<div id="bar3" class="spentbar3"></div>
					</div>
					<div id="spentbar" class="spentbar4">
						<div id="bar4" class="spentbar4"></div>
					</div>
					<div id="spentbar" class="spentbar5">
						<div id="bar5" class="spentbar5"></div>
					</div>
				</div>
				<div id="spenttitle">
					<div id="bartitle" class="title1" onClick="showvalue()">Mat</div>
					<div id="bartitle" class="title2" >Hälsa</div>
					<div id="bartitle" class="title3" >Nöje</div>
					<div id="bartitle" class="title4" >Transport</div>
					<div id="bartitle" class="title5" >Övrigt</div>
				</div>
			</div>
			<div id="halfbox2" class="addmoney" style="margin-top: 4%;" onclick="salary()">
				NY INKOMST
			</div>
			<div id="halfbox1" class="addpurchase" style="margin-left: 4%; margin-top: 4%;" onclick="purchase()">
				NY UTGIFT
			</div>


		</div>
	</div>


	<div id="landscape" style="display: none;">
		<div id="bar">
			<p>Saldo</p>
			
			
		</div>
		<div id="container">
			<div class="progress" style="margin-top: 2%;">
					<div id="percent-position">100 %</div>
		  			<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
		  			</div>
			</div>
		</div>
	</div>


</body>
<script>
	//Checks if the user is over theire budget
	//Depending to that it changes the value of the 2 boxes in the second section
	var currentMoney = "<?php echo $budgetRow['currentCash']?>";
	if(currentMoney < 10){
		document.getElementById('leftMonth').innerHTML = "Över din budget:";
		document.getElementById('infotext1').style.color = "#ff6262";
		document.getElementById('spendToday').innerHTML = "Måste du tjäna ihop idag:";
		document.getElementById('infotext2').style.color = "#ff6262";
	}else{
		document.getElementById('leftMonth').innerHTML = "Kvar denna månad:";
		document.getElementById('infotext1').style.color = "#40D65D";
		document.getElementById('spendToday').innerHTML = "Kan du spendera idag:";
		document.getElementById('infotext2').style.color = "#40D65D";
	}
</script>
<script>
	//Function retrived from the internet
	//Converts a percentage value to a color (Green to Red) depending if the number is low or high.
	function hsl_col_perc(percent,start,end) {
		var a = percent/100,
			b = end*a;
			c = b+start;
		return 'hsl('+c+',100%,50%)';
	}
	var percent = <?php echo "$percent"?>;
	$('.progress-bar').css('background-color',hsl_col_perc(percent,0,120));
	$('#infotext1').css('color',hsl_col_perc(percent,0,120));
	$('#infotext2').css('color',hsl_col_perc(percent,0,120));
</script>
<script>
	//This script section takes the value spent on each category
	//Then divides it by the total spent cash and converts it to percantage
	//Then it implements to the category section and the main-bar section to display the correct width
	var matBar = (<?php echo $categoryRow['mat'];?> / <?php echo $budgetRow['spentCash'];?>) * 100 + "%";
	var halsaBar = (<?php echo $categoryRow['halsa'];?> / <?php echo $budgetRow['spentCash'];?>) * 100 + "%";
	var nojeBar = (<?php echo $categoryRow['noje'];?> / <?php echo $budgetRow['spentCash'];?>) * 100 + "%";
	var transportBar = (<?php echo $categoryRow['transport'];?> / <?php echo $budgetRow['spentCash'];?>) * 100 + "%";
	var ovrigtBar = (<?php echo $categoryRow['ovrigt'];?> / <?php echo $budgetRow['spentCash'];?>) * 100 + "%";

	document.getElementById('bar1').style.height = matBar;
	document.getElementById('bar2').style.height = halsaBar;
	document.getElementById('bar3').style.height = nojeBar;
	document.getElementById('bar4').style.height = transportBar;
	document.getElementById('bar5').style.height = ovrigtBar;

	document.getElementById('bar1food').style.width = matBar;
	document.getElementById('bar2halsa').style.width = halsaBar;
	document.getElementById('bar3noje').style.width = nojeBar;
	document.getElementById('bar4trans').style.width = transportBar;
	document.getElementById('bar5ovrigt').style.width = ovrigtBar;
</script>
<script>
	//This function displays how much the user spent on each category in the main bar.
	function mainSpent() {
		document.getElementById("spentMainbar").style.display = "block";
		setTimeout(function(){
			document.getElementById("spentMainbar").style.display = "none";
		}, 3000);
	}
</script>

</html>