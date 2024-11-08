<?php

namespace src\Model;

class Guest 
{
    public function selectCountryByName($db, $countryName) // Vraca sve zemlje sa datim imenom
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
    

}