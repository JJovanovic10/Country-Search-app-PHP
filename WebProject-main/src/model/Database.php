<?php
   class Database
   {
       private $dsn;
       private $username;
       private $password;
       private $db;
   
       public function __construct($dsn, $username, $password = '')
       {
           $this->dsn = $dsn;
           $this->username = $username;
           $this->password = $password;
       }
   
       public function connect()
       {
           try 
           {
               $this->db = new PDO($this->dsn, $this->username, $this->password);
               $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           } catch (PDOException $e)
            {
               $error_message = "Database Error: ";
               $error_message .= $e->getMessage();
               include('view/error.php');
               exit();
           }
       }
   
       public function getDB()
       {
           return $this->db;
       }
   }
?>