<?php
session_start();
if(isset($_SESSION['user'])!="")
{
}
include_once 'connect.php';
if(isset($_POST['btn-signup']))
{
    $fname = mysql_real_escape_string($_POST['fname']);
    $lname = mysql_real_escape_string($_POST['lname']);
    $status = mysql_real_escape_string($_POST['status']);
    $email = mysql_real_escape_string($_POST['email']);
    $upass = md5(mysql_real_escape_string($_POST['pass']));

    if(mysql_query("INSERT INTO users(fname,lname,status,email,password) VALUES('$fname','$lname','$status','$email','$upass')"))
    {

        if(mysql_query("INSERT INTO budget(monthCash,currentCash,spentCash) VALUES('$fname','$lname','$status')"))
        {

            if(mysql_query("INSERT INTO category(mat,halsa,noje,transport,ovrigt) VALUES('0','0','0','0','0')"))
            {
                ?>
                <script> <script>alert('alla fungerade');</script>
                <?php
            }else{
                ?>
                <script>alert('fel i category');</script>
                <?php
            }
        }else{
            ?>
                <script>alert('fel i budget');</script>
            <?php
        }

    }
    else
    {
        ?>
        <script>alert('fel i users');</script>
        <?php
    }
}

?>
<html>
<head>
<title>GymnasieArbete</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<meta charset="utf-8">
<script type="text/javascript">
  if (screen.width <= 800) {
    window.location = "./mobil/index.php";
  }
</script>
<script>
	jQuery(document).ready(function($){
    //Öppnar popup/register diven
    $('.cd-popup-trigger').on('click', function(event){
        event.preventDefault();
        $('.cd-popup').addClass('is-visible');
    });
    
    //Stäng popup knappen
    $('.cd-popup').on('click', function(event){
        if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
            event.preventDefault();
            $(this).removeClass('is-visible');
        }
    });
    //Stänger popupen när man klickar på escape
    $(document).keyup(function(event){
        if(event.which=='27'){
            $('.cd-popup').removeClass('is-visible');
        }
    });
});
</script>
<script>
$(document).ready(function() {

  $('#fname').blur(function(){
    var fname = document.getElementById('fname').value;
    if (fname == null || fname == "") {
    	document.getElementById('fname').style.borderColor= "#ff969b";
        return false;
    }else{
    	document.getElementById('fname').style.borderColor= "#3adf4d";
    }
  });

  $('#lname').blur(function(){
    var fname = document.getElementById('lname').value;

    if (fname == null || fname == "") {
    	document.getElementById('lname').style.borderColor= "#ff969b";
        var lnameLegit = "no"
        return false;
    }else{
    	document.getElementById('lname').style.borderColor= "#3adf4d";
        var lnameLegit = "yes"
    }
  });

  $('#status').blur(function(){
    var status = document.getElementById('status').value;

    if (status == null || status == "") {
    	document.getElementById('status').style.borderColor= "#ff969b";
        var statusLegit = "no"
        return false;
    }else{
    	document.getElementById('status').style.borderColor= "#3adf4d";
        var statusLegit = "yes"
    }
  });

  $('#email').blur(function(){
    var email = document.getElementById('email').value;

    if (email == null || email == "" || email.indexOf("@") == -1 || email.indexOf(".") == -1) {
    	document.getElementById('email').style.borderColor= "#ff969b";
        var emailLegit = "no"
        return false;
    }else{
    	document.getElementById('email').style.borderColor= "#3adf4d";
        var emailLegit = "yes"
    }
  });

  $('#password').blur(function(){
    var password = document.getElementById('password').value;

    if (password.length <= 4 || password == "") {
    	document.getElementById('password').style.borderColor= "#ff969b";
        var passwordLegit = "no"
        return false;
    }else{
    	document.getElementById('password').style.borderColor= "#3adf4d";
        var passwordLegit = "yes"
    }
  });

  $('#password2').blur(function(){
    var password2 = document.getElementById('password2').value;
    var password = document.getElementById('password').value;

    if (password2 == password && password2.length >= 5) {
    	document.getElementById('password2').style.borderColor= "#3adf4d";
        var password2Legit = "yes"
    }else{
    	document.getElementById('password2').style.borderColor= "#ff969b";
        var password2Legit = "no"
        return false;
    }
  });

});
</script>
</head>
<body>
<div id="mobiles">
	<div id="completed"></div>
</div>
<a href="#0" class="cd-popup-trigger">Registrera dig</a>

<div class="cd-popup" role="alert">
    <div class="cd-popup-container">
        <div id="title">REGISTRERA DIG</div>
        <a href="#0" class="cd-popup-close img-replace">Close</a>
        <div id="container">
        	<div style=" width: 380px; height: 100px; position: absolute; margin-top: 300px;" id="clickable"></div>
        	<form class="form" name="my-form" method="post">
	        	<input name="fname" type="text" id="fname" class="input" style="width: 40%;" placeholder="Förnamn" autocomplete="off" required></input>
	        	<input name="lname" type="text" id="lname" class="input" style="width: 40%;" placeholder="Efternamn" autocomplete="off" required></input>
	        	<input name="status" type="text" id="status" class="input" placeholder="Nuvarande status" autocomplete="off" required></input>
	        	<input name="email" type="text" id="email" class="input" placeholder="Email Adress" autocomplete="off" required></input>
	        	<input name="pass" type="password" id="password" class="input" placeholder="Lösenord" autocomplete="off" required></input>
	        	<input type="password" id="password2" class="input" placeholder="Repetera Lösenord" autocomplete="off" required></input>
	        	<input type="submit" class="input-submit" value="Gå med" id="button" name="btn-signup"></input>
        	</form>
        </div>

    </div> <!-- cd-popup-container -->
</div> <!-- cd-popup -->
</body>
<script>
$(document).ready(function() {
document.getElementById('button').style.backgroundColor = "lightgray";
$('#button').css("cursor", "default");
  $('.input').blur(function(){
    var fnamelegit = document.getElementById('fname').style.borderColor;
    var lnamelegit = document.getElementById('lname').style.borderColor;
    var statuslegit = document.getElementById('status').style.borderColor;
    var emaillegit = document.getElementById('email').style.borderColor;
    var passwordlegit = document.getElementById('password').style.borderColor;
    var password2legit = document.getElementById('password2').style.borderColor;

    if(fnamelegit == "rgb(58, 223, 77)" && lnamelegit == "rgb(58, 223, 77)" && statuslegit == "rgb(58, 223, 77)" && emaillegit == "rgb(58, 223, 77)" && passwordlegit == "rgb(58, 223, 77)" && password2legit == "rgb(58, 223, 77)"){

    	document.getElementById('button').style.backgroundColor = "#58df6f";

    	$('#clickable').css("display", "none");

    	$(".input-submit").hover(function() {
		  $(this).css("background-color","#35d450")
		  $('#button').css("cursor", "pointer");
		});
		$( ".input-submit" ).mouseout(function() {
		  $(this).css("background-color","#58df6f")
		  $('#button').css("cursor", "default");
		});
    }
    else if(fnamelegit == "rgb(255, 150, 155)" || lnamelegit == "rgb(255, 150, 155)" || statuslegit == "rgb(255, 150, 155)" || emaillegit == "rgb(255, 150, 155)" || passwordlegit == "rgb(255, 150, 155)" || password2legit == "rgb(255, 150, 155)"){
    	
    	document.getElementById('button').style.backgroundColor = "lightgray";

    	$('#clickable').css("display", "block");

    	$(".input-submit").hover(function() {
    	$(this).css("background-color","lightgray")
		  $('#button').css("cursor", "default")
		});
		$( ".input-submit" ).mouseout(function() {
			$(this).css("background-color","lightgray")
		  $('#button').css("cursor", "default")
		});
    }
  });
});
</script>
</html>