<?php
session_start();
include_once 'connect.php';

if(!isset($_SESSION['user']))
{
    header("Location: ./index.php");
}
$res=mysql_query("SELECT * FROM users WHERE user_id=".$_SESSION['user']);
$userRow=mysql_fetch_array($res);

$res2=mysql_query("SELECT * FROM budget WHERE user_id=".$_SESSION['user']);
$budgetRow=mysql_fetch_array($res2);



if(isset($_POST['setmonth']))
{
    $month = mysql_real_escape_string($_POST['monthCash']);

    if(mysql_query("UPDATE budget SET monthCash='$month' WHERE user_id=".$_SESSION['user']))
    {
        ?>
            <script>alert('Månaden tillagd');</script>
        <?php
    }
    else
    {
        ?>
            <script>alert('Fel');</script>
        <?php
    }
}
if(isset($_POST['setcurrent']))
{
    $current = mysql_real_escape_string($_POST['currentCash']);

    if(mysql_query("UPDATE budget SET currentCash='$current' WHERE user_id=".$_SESSION['user']))
    {
        ?>
        <script>alert('Tillagt');</script>
        <?php
    }
    else
    {
        ?>
        <script>alert('Fel');</script>
        <?php
    }
}
if(isset($_POST['spent']))
{
    $spent = mysql_real_escape_string($_POST['spentCash']);
    $newCurrent = $budgetRow['currentCash'] - $spent;

    if(mysql_query("UPDATE budget SET currentCash='$newCurrent' WHERE user_id=".$_SESSION['user']))
    {
        ?>
        <script>alert('Updaterat');</script>
        <?php
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
<title>GymnasieArbete</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<meta charset="utf-8">
</head>
<body>
    <form class="form" method="post">
        <input name="monthCash" type="number" required placeholder="Totalt att spendera"></input>
        <input type="submit" id="button" name="setmonth"></input>
    </form>
    <br>
    <form class="form" method="post">
        <input name="currentCash" type="number" required placeholder="Har kvar just nu"></input>
        <input type="submit" id="button" name="setcurrent"></input>
    </form>
    <br>
    <form class="form" method="post">
        <input name="spentCash" type="number" required placeholder="Handlade nyss för"></input>
        <input type="submit" id="button" name="spent"></input>
    </form>
</body>
</html>