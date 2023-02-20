<pre>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Tzclasses/PeopleDB.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Tzclasses/PeopleList.php';

use \Tzclasses\PeopleDB as People;
use \Tzclasses\PeopleList as PeopleList;

function dbConnect(string $user = 'root', string $password = '123', string $dsn = 'mysql:host=localhost;dbname=mydb'): PDO
{

    static $connection = null;

    if ($connection === null) {
        $connection = new PDO($dsn, $user, $password);
    }

    return $connection;
}

$user = 'root';
$password = '123';
$dsn = 'mysql:host=localhost;dbname=mydb';

$id = 1;
$name = 'Анжела';
$surname = 'Львова';
$birthDate = '2001-05-13';
$sex = false;
$birthCity = 'Cлоним';

try {

    $pdo = dbConnect($user, $password, $dsn);

    // $people = new People($pdo, $id, $name, $surname, $birthDate, $sex, $birthCity);
    $peoples = new PeopleList($pdo, 2, '>');

    // var_dump($peoples->searchPeoples($pdo));
    
} catch (Exception $exception){

    echo $exception->getMessage();

}
?>
</pre>