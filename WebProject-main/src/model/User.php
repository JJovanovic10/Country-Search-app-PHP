<?php

namespace src\Model;
use PDOException;
use PDO;

require_once('Guest.php');

class User extends Guest
{
    protected $username;
    protected $email;
    protected $password;

    public function __construct(string $username, string $email, string $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    public function insertUser($db) 
    {
        try 
        {
            // pravimo tranzakciju zato sto ubacujemo podatke u dve tabele
            $db->beginTransaction();
    
            // ubacujemo u user tabelu
            $query = "INSERT INTO users (username, email, password) 
                      VALUES (:username, :email, :password)";
            $statement = $db->prepare($query);
            $statement->bindValue(':username', $this->username);
            $statement->bindValue(':email', $this->email);
            $statement->bindValue(':password', $this->password);
            $statement->execute();
            $id = $db->lastInsertId();
    
            // ubacujemo level usera
            $userLevel = "user";
            $query = "INSERT INTO userLevel (userId, userLevel) 
                      VALUES (:userId, :userLevel)";
            $statement = $db->prepare($query);
            $statement->bindValue(':userId', $id);
            $statement->bindValue(':userLevel', $userLevel);
            $statement->execute();
    
            // komitujemo tranzakciju
            $db->commit();
            $_SESSION['message'] = "User succesfuly registered!";
        } 
        
        catch (PDOException $e) 
        
        {
            // ako je doslo do greske rollbackujemo podatke
            $db->rollback();
            throw $e;
        }
        
    }

    public function login($db) 
    {
                
        $query = "SELECT u.*, ul.userLevel FROM users u 
        INNER JOIN userLevel ul ON u.ID = ul.userId 
        WHERE u.username = :username AND u.password = :password";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $this->username);
        $statement->bindValue(':password', $this->password);
        $statement->execute();

        // uzimamo podatke o userima
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) 
        {

        $_SESSION['user']=$user;   
        return true;
        }

        return false;
    }


    public static function logout()
    {
        session_destroy();
    }


    public function selectCountryByName($db, $countryName)
{
    $query = 'SELECT * FROM country 
              WHERE Name = :countryName 
              ORDER BY Population DESC';
    $statement = $db->prepare($query);
    $statement->bindValue(':countryName', $countryName);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}


    public function insert_country($db,$newcountry,$countrycode,$capitalCity,$population)
    {
        try
        {
            $countryQuery = "SELECT * FROM country WHERE Code = :countrycode";
            $countryStatement = $db->prepare($countryQuery);
            $countryStatement->bindValue(':countrycode', $countrycode);
            $countryStatement->execute();
            $countryExists = $countryStatement->rowCount() > 0;

            // ako ne postoji drzava koju ubacujemo onda je false
            if ($countryExists) 
            {
                throw new PDOException("Country with code $countrycode already exists.");
            }
    
            // ubacujemo drzavu
            $query = "INSERT INTO country
                    (Name, Code, CapitalCity, Population) 
                    VALUES 
                    (:newcountry, :countrycode, :capitalCity, :population)";

            $statement = $db->prepare($query);
            $statement->bindValue(':newcountry', $newcountry);
            $statement->bindValue(':countrycode', $countrycode);
            $statement->bindValue(':capitalCity', $capitalCity);
            $statement->bindValue(':population', $population);
            $statement->execute();

            return true;
        }

        catch(PDOException $e)
        {
            throw $e;
        }
                   
    }

    public function update_country($db, $id, $newcountry, $countrycode, $capitalCity, $population)
{
    try
    {
        // Provera da li postoji drzava sa datim countrycode
        $countryQuery = "SELECT * FROM country WHERE Code = :countrycode";
        $countryStatement = $db->prepare($countryQuery);
        $countryStatement->bindValue(':countrycode', $countrycode);
        $countryStatement->execute();
        $countryExists = $countryStatement->rowCount() > 0;

        // Ako drzava ne postoji, vracamo false
        if (!$countryExists) 
        {
            return false;
        }

        // Azuriranje drzave
        $query = 'UPDATE country 
                  SET Name = :newcountry, Code = :countrycode, CapitalCity = :capitalCity, 
                      Population = :population 
                  WHERE ID = :id';
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id);
        $statement->bindValue(':newcountry', $newcountry);
        $statement->bindValue(':countrycode', $countrycode);
        $statement->bindValue(':capitalCity', $capitalCity);
        $statement->bindValue(':population', $population);
        $statement->execute();

        return true; // Uspesno azuriranje drzave
    }
    catch(PDOException $e)
    {
        throw $e; // U sluzaju greske, prosledjujemo izuzetak dalje
    }
}


    public function delete_country($db,$id)
    {
        $query = 'DELETE FROM country  
              WHERE ID = :id';
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id);
        if($statement->execute())
        {
            $statement->closeCursor();
            return true;
        }
        $statement->closeCursor();
        return false;
    }

    public function update_profile($db,$id,$newUsername, $email,$password) 
    {
        try
        {
            $query = "UPDATE users SET email = :email, password = :password, username = :newusername WHERE ID = :ID";
            
            $statement = $db->prepare($query);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':password', $password);
            $statement->bindParam(':ID', $id);
            $statement->bindParam(':newusername', $newUsername);
            $statement->execute();
        }
        catch(PDOException $e)
        {
            throw $e;
        }
    
    }


public function update_userLevel($db,$username, $newUserLevel)
{

    $checkQuery = "SELECT ID FROM users WHERE username = :username";
    $checkStatement = $db->prepare($checkQuery);
    $checkStatement->bindValue(':username', $username);
    $checkStatement->execute();

    if ($checkStatement->rowCount() == 0) 
    {
        // User ne postoji u bazi
        return false;
    }


    // updateujemo user level
    $query = "UPDATE userLevel
              SET userLevel = :newUserLevel
              WHERE userId = (
                SELECT ID
                FROM users
                WHERE username = :username
              )";
    $statement = $db->prepare($query);
    $statement->bindValue(':newUserLevel', $newUserLevel);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $statement->closeCursor();
    return true;
}

public function logUserAction($action,$path)
{
    // uzimamo trenutno vreme
    $timestamp = date('Y-m-d H:i:s');

    // formatiramo string za log fajl
    $logEntry = "[$timestamp] User $this->username: $action";

    // ubacujemo string u log fajl
    file_put_contents($path, $logEntry . PHP_EOL, FILE_APPEND);
}

    public function getUsername() 
    {
        return $this->username;
    }

    public function getEmail() 
    {
        return $this->email;
    }

    public function getPassword() 
    {
        return $this->password;
    }

    public function setUsername($username) 
    {
         $this->username=$username;
    }

    public function setEmail($email) 
    {
         $this->email=$email;
    }

    public function setPassword($password) 
    {
         $this->password=$password;
    }
}