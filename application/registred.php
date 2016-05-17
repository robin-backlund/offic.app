<html>
<head>
<title>GymnasieArbete</title>
<link rel="stylesheet" type="text/css" href="style-2.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<meta charset="utf-8">
<script>
window.onload = function () { //Startar funktionen när hela sidan är inladdad.
    setTimeout(function(){ //Lägger på 2 sekunder så att användaren ska kunna läsa meddelandet innan sidan laddas.
            document.getElementById('loader').style.opacity = "0"; //Tar bort Laddningsidan
            document.getElementById('step1').style.display = "block";
    }, 000);
    setTimeout(function(){
        document.getElementById('loader').style.display = "none";
    }, 300);
}
</script>
<script>
$(document).ready(function(){
    $("#right").click(function(){
        $("#step1").animate({right: '100%'});
        $("#step2").animate({left: '0%'});
        document.getElementById('step2').style.display = "block";
    }); 

    $("#bottom").click(function(){
        $("#step2").animate({bottom: '100%'});
        $("#step3").animate({top: '0%'});
        document.getElementById('step3').style.display = "block";
    }); 

    $("#left").click(function(){
        $("#step3").animate({left: '100%'});
        $("#step4").animate({right: '0%'});
        document.getElementById('step4').style.display = "block";
    }); 
});
</script>
<script type="text/javascript">
  if (screen.width <= 800) {
    window.location = "/mobil/logged/index.html";
  }
</script>
</head>
<body onload="popup()">
<div id="loader">
    <div id="load">
        <p id="title">Grattis! Din registrering lyckades</p>
        <p id="under">Följ kommande stegen för att komma igång:</p>
        <img src="img/loader.gif"></img>
    </div>
</div>
<div id="step1">
    <div id="content">
        <img src="img/step1.gif" alt="step1" style="height: 500px;"/>
    </div>
    <div class="arrow" id="right"></div>
</div>
<div id="step2">
    <div id="steptitle">STEG 2</div>
    <div class="arrow" id="bottom"></div>
</div>
<div id="step3">
    <div id="steptitle">STEG 3</div>
    <div class="arrow" id="left"></div>
</div>
<div id="step4">
    <div id="steptitle">STEG 4</div>
    <div id="restart"></div>
</div>
<div id="main-container">
</div>
</body>
</html>