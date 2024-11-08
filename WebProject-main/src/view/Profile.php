<?php
include('header.php');


if (!isset($_SESSION['user'])) 
{
     header("Location: ../index.php");
     exit();
}

$user = $_SESSION['user'];
$id = $user['ID'];
$username = $user['username'];
$email = $user['email'];
$userLevel = $user['userLevel'];
$password=$user['password'];

?>

<br>
<br>
<br>
<div class='flex-container'>
    
    <form action="../../index.php" method="POST">
        <h2>Profile</h2>
        <input type="hidden" name="action" value="update_user">

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>">
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>">
        
        <label for="password">Password:</label>
        <span class="toggle-password" onclick="togglePasswordVisibility()">
        <i class="fa-solid fa-eye"></i>
        </span>
        <input type="password" id="Password" name="newPassword" value="<?php echo $password; ?>">
        
        
        <button type="submit" value="Update">Update</button>
    </form>
</div>
<?php include('footer.php') ?>
