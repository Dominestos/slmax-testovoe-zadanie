<pre>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Tzclasses/PeopleDB.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Tzclasses/PeopleList.php';

use \Tzclasses\PeopleDB as People;
use \Tzclasses\PeopleList as PeopleList;

$user = 'root';
$password = '123';
$dsn = 'mysql:host=localhost;dbname=mydb';

$id = 0;
$name = 'Александра';
$surname = 'Николаева';
$birthDate = '1993-08-01';
$sex = false;
$birthCity = 'Гомель';

$searchIdVal = 3;
$operator = '>';

try {

    $pdo = dbConnect($user, $password, $dsn);

    // $people = new People($pdo, $id, $name, $surname, $birthDate, $sex, $birthCity);
    $peoples = new PeopleList($pdo, $searchIdVal, $operator);

    var_dump($peoples);
    
} catch (Exception $exception){

    echo $exception->getMessage();

}

function dbConnect(string $user = 'root', string $password = '123', string $dsn = 'mysql:host=localhost;dbname=mydb'): PDO
{

    static $connection = null;

    if ($connection === null) {
        $connection = new PDO($dsn, $user, $password);
    }

    return $connection;
}
?>
</pre>