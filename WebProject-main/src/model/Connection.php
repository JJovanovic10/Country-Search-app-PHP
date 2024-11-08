<?php 
include('Database.php');

$database = new Database("mysql:host=localhost;dbname=drzave","root",'');
$database->connect();
$conn= $database->getDb();

?>