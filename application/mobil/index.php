<?php
session_start();
include_once '../connect.php';

if(isset($_SESSION['user'])!="")
{
    header("Location: ./logged/home.php");
}
if(isset($_POST['btn-login'])) {
    $email = mysql_real_escape_string($_POST['email']);
    $upass = mysql_real_escape_string($_POST['pass']);
    $res = mysql_query("SELECT * FROM users WHERE email='$email'");
    $row = mysql_fetch_array($res);

    setcookie("email", $_POST['email'], time() + 3600);
    setcookie("pass", $_POST['pass'], time() +  3600);

    if (isset($_COOKIE['email']) && $_COOKIE['pass'] == 'yes') {

        $emailcookie = "";
        $passcookie = "";

    }else{
        $emailcookie = $_COOKIE["email"];
        $passcookie = $_COOKIE["pass"];
    }

    $emailputs = "";
    $passputs = "";

    if ($row['password'] == md5($upass)) {
        $_SESSION['user'] = $row['user_id'];
        header("Location: ./logged/home.php");
    } else {
        ?>
        <script>alert('Fel inloggning');</script>
        <?php
    }
}
?>
<html>
<head>
	<title>Offic</title>
	<link rel="stylesheet" href="style.css">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<script> //Gömmer webläsarens addressbar när appen är sparad till hemskärmen
		function hideAddressBar()
			{
			  if(!window.location.hash)
			  {
			      if(document.height < window.outerHeight)
			      {
			          document.body.style.height = (window.outerHeight + 50) + 'px';
			      }

			      setTimeout( function(){ window.scrollTo(0, 1); }, 50 );
			  }
			}

			window.addEventListener("load", function(){ if(!window.pageYOffset){ hideAddressBar(); } } );
			window.addEventListener("orientationchange", hideAddressBar );
	</script>
    <script> //Stänger av Rubberband effekten i iPhone så att sidan blir stilla.
        var firstMove;

        window.addEventListener('touchstart', function (e) {
            firstMove = true;
        });

        window.addEventListener('touchmove', function (e) {
            if (firstMove) {
                e.preventDefault();

                firstMove = false;
            }
        });

    </script>
    <script type="text/javascript"> //Kollar om vilken vinkel mobilen har och avgör om "portrait" eller "landscape ska synas"
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
</head>
<body>
<div id="portrait">
    <div id="title">OFFIC</div>
    <center><img src="../img/logo.png" alt="" id="logo"></center>
	<div id="container">
		<div id="login-container">
            <div id="login-form">
                <form method="post">
                    <input type="text" name="email" placeholder="Din Email" id="input" value="<?php echo $_COOKIE["email"] ?>"required />
                    <input type="password" name="pass" placeholder="Ditt Lösenord" id="input" value="<?php echo $_COOKIE["pass"] ?>" required />
                    <button type="submit" name="btn-login" id="submit">Logga in</button>
                </form>
            </div>
		</div>
	</div>
</div>
</body>
</html>