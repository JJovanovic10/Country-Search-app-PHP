<?php

use src\Model\User;

include('header.php');
include ('../model/User.php');
include ('../model/Connection.php');

if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) 
{
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

    if ($password !== $confirm_password)
    {
        $_SESSION['message'] = 'Passwords do not match.';
       header("Location: ../../index.php");
   }
   else
   {
    $user = new User($username,$email,$password);

    try
    {
        $user->insertUser($conn);
        $user->logUserAction("registered", " ");
        $user->login($conn);
        header("Location:../../index.php");
        
    } catch(PDOException $e)
    {
        $_SESSION['message']= "Username or Email is taken";
        header("Location: ../../index.php");
    }
   }

   
   
}


?>
<section>
    <div class='flex-container'>
    <form action="Register.php" method="POST" class="marginTop">
        <h2>Register</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password:</label>
        <span class="toggle-password" onclick="togglePasswordVisibility()">
        <i class="fa-solid fa-eye"></i>
        </span>
        <input type="password" id="Password" name="password" required>
        <label for="confirm_password">Confirm password:</label>
        <span class="toggle-confirmPassword" onclick="toggleConfirmPasswordVisibility()">
        <i class="fa-solid fa-eye"></i>
        </span>
        <input type="password" id="confirmPassword" name="confirm_password" required>
        <button type="submit">Register</button>
    </form>
    </div>
</section>
<?php include('footer.php') ?>