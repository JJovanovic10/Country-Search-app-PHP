<?php

if(isset($_SESSION['message']))
{
    $message=$_SESSION['message'];
    unset($_SESSION['message']);
    echo $twig->render('message.twig',['message'=>"$message"]);
}


?>







