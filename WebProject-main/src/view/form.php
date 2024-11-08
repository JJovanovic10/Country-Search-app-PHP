<?php

if(isset($_SESSION['user']))
{
    $userLevel=$_SESSION['user']['userLevel'];
}
else
{
    $userLevel='guest';
}


if ($results) 
{
    echo "<div class='flex-container'>";
    foreach ($results as $result) 
    {
        $id = $result['ID'];
        $countryName = $result['Name'];
        $countryCode = $result['Code'];
        $capitalCity = $result['CapitalCity'];
        $population = $result['Population'];
        ?>

            <form action="index.php" method="POST">
            <input type="hidden" name="id" value="<?= $id ?>">
            <label for="countryName-<?= $id ?>">Country Name:</label>
            <input type="text" id="countryName-<?= $id ?>" name="countryName" value="<?= $countryName ?>" <?= ($userLevel === 'guest' ? 'disabled' : '') ?> required>
            <label for="countryCode-<?= $id ?>">Country Code:</label>
            <input type="text" id="countryCode-<?= $id ?>" name="countryCode" maxlength="3" value="<?= $countryCode ?>" <?= ($userLevel === 'guest' ? 'disabled' : '') ?> required>
            <label for="capitalCity-<?= $id ?>">Capital City:</label>
            <input type="text" id="capitalCity-<?= $id ?>" name="capitalCity" value="<?= $capitalCity ?>" <?= ($userLevel === 'guest' ? 'disabled' : '') ?>>
            <label for="population-<?= $id ?>">Population:</label>
            <input type="number" min="0" id="population-<?= $id ?>" name="population" value="<?= $population ?>" <?= ($userLevel === 'guest' ? 'disabled' : '') ?>>
             
            <?php if ($userLevel === 'manager' || $userLevel === 'admin' || $userLevel === 'user') { ?>
                <button type="submit" id="button1" name="action" value="insert">Insert</button>
            <?php } ?>
            
            <?php if ($userLevel === 'manager' || $userLevel === 'admin') { ?>
                <button type="submit" id="button2" name="action" value="update">Update</button>
            <?php } ?>
            
            <?php if ($userLevel === 'admin') { ?>
                <button type="submit" id="button3" name="action" value="delete">Delete</button>
            <?php } ?>
        </form>
        <?php
    }
} 
else 
{
    echo "<div class='flex-container'><h1 class='marginTop'>Sorry, no results.</h1></div><br>";
}
echo "</div>";
?>
