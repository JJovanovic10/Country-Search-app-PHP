<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projekat</title>
    <link rel='stylesheet' href="/Jovan Jovanovic 351/WebProject-main/src/view/css/main.css">
    <script src="https://kit.fontawesome.com/37c6cb7999.js" crossorigin="anonymous"></script>
    <script src="/Jovan Jovanovic 351/WebProject-main/script.js"></script>
</head>
<body>
    <main>
        <div class="topnav ">
            <a class="active" href="/Jovan Jovanovic 351/WebProject-main/index.php">Home</a>

            <?php
            session_start();
            if (isset($_SESSION['user'])) 
            {
                $username = $_SESSION['user']['username'];

                // Ako je korisnik ulogovan samo mu je potrebno Logout i Profile dugme
                echo '<a href="/Jovan Jovanovic 351/WebProject-main/src/view/Logout.php">Logout</a>';
                echo '<a href="/Jovan Jovanovic 351/WebProject-main/src/view/Profile.php">' . htmlspecialchars($username) . '</a>';
            } 
            else 
            {
                // A ako nije onda mu dajemo linkove za Login i Register
                echo '<a href="/Jovan Jovanovic 351/WebProject-main/src/view/Register.php">Register</a>';
                echo '<a href="/Jovan Jovanovic 351/WebProject-main/src/view/Login.php">Login</a>';
            }
            ?>
        </div>
        <div class="flex-container">
