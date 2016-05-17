<?php
session_start();

if(!isset($_SESSION['user']))
{
    header("Location: ../index.php");
}
else if(isset($_SESSION['user'])!="")
{
    header("Location: home.php");
}

if(isset($_GET['logout']))
{
    session_destroy();
    unset($_SESSION['user']);
    header("Location: ../index.php");
}

/**
 * Created by PhpStorm.
 * User: Robbac
 * Date: 19/01/16
 * Time: 23:47
 */