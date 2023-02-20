<?php
namespace Tzclasses;

use Exception;
use PDO;

class PeopleList
{
    public $idArray = [];

    public function __construct(PDO $pdo, int $idparam, string $operator)
    {
        
        if (!class_exists('\Tzclasses\PeopleDB')) {

            throw new Exception('Отсутствует подключение к классу PeopleDB');

        } else {

            $stmt = '';

            if ($operator === '>') {

                $stmt = $pdo->prepare("SELECT id from peoples WHERE id > ?");

            } elseif ($operator === '<') {

                $stmt = $pdo->prepare("SELECT id from peoples WHERE id < ?"); 

            } elseif ($operator === '!=') {

                $stmt = $pdo->prepare("SELECT id from peoples WHERE id != ?");

            } else {

                throw new Exception('Поддерживаются операторы: "больше", "меньше", "не равно"');

            }

            $stmt->bindParam(1, $idparam, PDO::PARAM_INT);
            $stmt->execute();
            $arrDB = $stmt->fetchAll(PDO::FETCH_NUM);
            
            if ($arrDB) {

                foreach($arrDB as $key => $value) {

                    $this->idArray[$key] = $value[0];

                }

            } else {

                throw new Exception('Таких значений не существует');

            } 
        }
    }

    public function searchPeoples(PDO $pdo): array
    {
        $peopleArr = [];

        foreach ($this->idArray as $id) {
            
            $peopleArr[] = new PeopleDB($pdo, $id);

        }
        
        return $peopleArr;
    }

    public function deleteUsers(PDO $pdo): bool
    {
        $peopleArr = $this->searchPeoples($pdo);

        foreach ($peopleArr as $people) {

            $result = $people->deleteFromDB($pdo);

        }

        return $result;
    }
} 