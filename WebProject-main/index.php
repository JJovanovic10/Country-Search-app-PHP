<?php 
require_once 'vendor/autoload.php';

use src\Model\Guest;
use src\Model\User;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


require_once ('src/model/Guest.php');
require_once ('src/model/User.php');
require_once ('src/model/Connection.php');

$loader = new \Twig\Loader\FilesystemLoader('./src/templates');
$twig = new \Twig\Environment($loader, []);

$logFilePath = 'logs/user_actions.log';
$logDirectory = 'logs/';

// Napravi direktorijum za logove ako ne postoji
if (!is_dir($logDirectory)) 
{
    mkdir($logDirectory, 0777, true);
}

// Napravi log fajl ako ne postoji
if (!file_exists($logFilePath)) 
{
    touch($logFilePath);
}

include_once('src/view/header.php');

//ako je setovan user uzimamo njegovu informaciju kroz session
if (isset($_SESSION['user'])) 
{
    $userSession = $_SESSION['user'];
} 


//uzimamo promenljive iz forme
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$countrycode = filter_input(INPUT_POST, "countryCode", FILTER_SANITIZE_STRING);
$capitalCity = filter_input(INPUT_POST, "capitalCity", FILTER_SANITIZE_STRING);
$population = filter_input(INPUT_POST, "population", FILTER_SANITIZE_STRING);


// akcija predstavlja koju smo formu iskoristili
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
if (!$action) 
{
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    if (!$action) 
    {
        $action = 'read_form'; // ako nemamo action, npr kada prvi put pokrecemo index, onda prikazujemo read_form sto je forma za prikaz grada
    }
}


//post koristimo za forme koje ubacuju/menjaju/brisu grad, dok getujemo grad iz pocetne read_form-e
$countryName = filter_input(INPUT_POST, "countryName", FILTER_SANITIZE_STRING);
if (!$countryName) 
{
    $countryName = filter_input(INPUT_GET, "countryName", FILTER_SANITIZE_STRING);
}


//ako je zapocet session pravimo usera za njegovim informacijama, u suprotnom pravimo guesta
if(isset($_SESSION['user']))
{
    $user = new User($_SESSION['user']['username'],$_SESSION['user']['email'],$_SESSION['user']['password']);
}
else
{
    $user = new Guest();
}




switch($action)
{
    // uzimamo sve gradove sa tim imenom zato sto postoji mogucnost za vise gradova sa istim imenom
    case 'select':
        if ($countryName) 
        {
            $results = $user->selectCountryByName($conn,$countryName);
            
            if($user instanceof User)
            {
                $user->LogUserAction("Viewed country $countryName",'logs/user_actions.log');
            }
            
            include('src/view/form.php');
        } 
        else 
        {
            $_SESSION['message'] = 'Invalid country data. Check all fields and resubmit.';
            header("Location:index.php");
            exit();
        }
    break;

    case 'insert':
        if ($countryName && $countrycode && $capitalCity && $population) 
        {
            if($user instanceof User)
            {
                
                try
                {
                    $user->insert_country($conn,$countryName,$countrycode,$capitalCity,$population);
                    $user->LogUserAction("Inserted country $countryName,$countrycode,$capitalCity,$population",'logs/user_actions.log');
                    $_SESSION['message']="Country succesfully inserted";
                    header("Location:index.php");
                    exit();
                }
                catch(PDOException $e)
                {
                    $_SESSION['message'] = 'Cannot enter Duplicate country!';
                    header("Location:index.php");
                    exit(); 
                }
            }
        
        } 
        else 
        {
            $_SESSION['message'] = 'Invalid country data. Check all fields and resubmit.';
            header("Location:index.php");
            exit();
        }
    break;    

    case 'update':
        if ($countryName && $countrycode && $capitalCity && $population && $id) 
        {
            if($user instanceof User)
            {
                try
                {
                    $user->update_country($conn,$id,$countryName,$countrycode,$capitalCity,$population);
                    $user->LogUserAction("Updated country $countryName,$countrycode,$capitalCity,$population",'logs/user_actions.log');
                    $_SESSION['message']="Country succesfully updated";
                    header("Location:index.php");
                    exit();
                }
                catch(PDOException $e)
                {
                    $_SESSION['message'] = 'Cannot update country, country with this data already exists';
                    header("Location:index.php");
                    exit(); 
                }
            }
                
        } 
        else 
        {
            $_SESSION['message'] = 'Invalid country data. Check all fields and resubmit.';
            header("Location:index.php");
            exit();
        }
    break;
                       
    case 'delete':
        if ($id) 
        {
            if($user instanceof User)
            {
                if($user->delete_country($conn,$id))
            {
                $user->LogUserAction("Deleted country $countryName,$countrycode,$capitalCity,$population",'logs/user_actions.log');
                $_SESSION['message']="Country succesfully deleted";
                header("Location:index.php");
                exit();
            }
            else
            {
                $_SESSION['message'] = 'Invalid country data. Check all fields and resubmit.';
                header("Location:index.php");
                exit(); 
            }
            }
        
        } 
        else 
        {
            $_SESSION['message'] = 'Invalid country data. Check all fields and resubmit.';
            header("Location:index.php");
            exit();
        }
    break;
        
    case 'update_user':

        $id=$userSession['ID'];
        $newEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $newUsername = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $newPassword = filter_input(INPUT_POST, 'newPassword', FILTER_SANITIZE_STRING);

        
        $validEmail = !empty($newEmail) && filter_var($newEmail, FILTER_VALIDATE_EMAIL);
        $validUsername = !empty($newUsername);
        $validPassword = !empty($newPassword);

        if ($validEmail && $validPassword && $validUsername) 
        {
            if($user instanceof User)
            {
                try
                {
                $user->update_profile($conn,$id,$newUsername, $newEmail,$newPassword);
                $user->setUsername($newUsername);
                $user->setEmail($newEmail);
                $user->setPassword($newPassword);
                $user->LogUserAction("Changed his data to $newUsername, $newEmail and $newPassword",'logs/user_actions.log');

                $_SESSION['user']['username']=$newUsername;
                $_SESSION['user']['email']=$newEmail;
                $_SESSION['user']['password']=$newPassword;
                
                $_SESSION['message'] = "Profile updated successfully!";
                header("Location:index.php");
                exit();
                }
                catch(PDOException $e)
                {
                    $_SESSION['message'] = 'Username or Email already taken!';
                    header("Location:index.php");
                    exit();  
                }
            }

        } 
        else 
        {
            $_SESSION['message'] = "Invalid email username or password. Please enter valid values.";
            header("Location:index.php");
            exit();
        }
    break;

    case 'update_userLevel':

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $newUserLevel = filter_input(INPUT_POST, 'userlevel', FILTER_SANITIZE_STRING);
        
        if ($username && $newUserLevel) 
        {
            if($user instanceof User)
            {
                if($user->update_userLevel($conn,$username,$newUserLevel))
                {
                    $user->LogUserAction("Updated $username to $newUserLevel",'logs/user_actions.log');
                    $_SESSION['message'] = "User $username has been changed to $newUserLevel";
                    header("Location: index.php");
                    exit();
                }
                else
                {
                    $_SESSION['message'] = "User $username doesn't exist";
                    header("Location: index.php");
                    exit();
                }
            }
        }
        else 
        {
            $_SESSION['message'] = 'Invalid data. Check all fields and resubmit.';
            header("Location:index.php");
            exit();
        }
    break;

    case 'Export':

            if($user instanceof User)
            {
                $user->LogUserAction("Exported userdata",'logs/user_actions.log');
                header("Location: src/model/Export.php");
                exit();
            }
            
    break;

    case 'loggedIn':
        if($user instanceof User)
        {
            $user->LogUserAction("Logged in",'logs/user_actions.log');
            include('src/view/read_form.php');
        }
    break;


    default:
    include('src/view/read_form.php');
}

// ako je korisnik admin onda ima dodatne forme 
if(isset($_SESSION['user']) && $_SESSION['user']['userLevel']==='admin')
{
    include_once("src/view/userLevel.php");
    include_once("src/view/userSheet.php");
}



?>

<!-- koristimo session da pamtimo poruku koju zelimo da prikazemo korisniku -->
<?php include_once('src/view/Message.php');?>

<?php include_once('src/view/footer.php');?>