<?php

require_once('header.php');

use src\Model\Guest;
use src\Model\User;


require_once ('../model/Guest.php');
require_once ('../model/User.php');
require_once ('../model/Connection.php');

if (isset($_POST['username'],$_POST['password'])) 
{
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $user = new User($username,'',$password);

    if($user->login($conn))
    {
       $_SESSION['message']="uspesno ste loginovani!";
       header("Location: ../../index.php?action=loggedIn");
       exit();
    }
    else
    {
        $_SESSION['message'] = "Invalid username or password.";
        header("Location: ../../index.php");
        exit();
    }
}
?>


        <section>
        <div class='flex-container'>
            <form action="Login.php" method="POST" class="marginTop">
                <h2>Login</h2>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <span class="toggle-password" onclick="togglePasswordVisibility()">
                <i class="fa-solid fa-eye"></i>
                </span>
                <input type="password" id="Password" name="password" required>
                <button type="submit">Login</button>
            </form>
            </div>
        </section>
 <?php include('footer.php') ?>