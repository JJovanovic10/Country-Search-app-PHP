<?php 

require_once("header.php");


use src\Model\Guest;
use src\Model\User;


require_once ('../model/Guest.php');
require_once ('../model/User.php');
require_once ('../model/Connection.php');


$user= new User($_SESSION['user']['username'],$_SESSION['user']['email'],$_SESSION['user']['password']);

$user->logUserAction("logged out",'../../logs/user_actions.log');
$user->logout();
header("Location: ../../index.php");
exit();

require_once("footer.php");

?>