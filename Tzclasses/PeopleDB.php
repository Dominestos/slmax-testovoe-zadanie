<?php

namespace Tzclasses;

use Exception;
use PDO;

class PeopleDB
{
    public $id = '';
    public $name = '';
    public $surname = '';
    public $age = '';
    public $sex = '';
    public $birthCity = '';

    public function __construct(PDO $pdo, int $id = 0, string $name = '', string $surname = '', string $birthDate = '2000-01-15', int $sex = 1, string $birthCity = '')
    {

        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->age = $birthDate;
        $this->sex = $sex;
        $this->birthCity = $birthCity;
        
        if ($this->id !== 0) {
            $stmt = $pdo->prepare("SELECT * FROM peoples WHERE id = ?");
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            $arrDB = $stmt->fetch(PDO::FETCH_NUM);

            $i = 0;
            foreach ($this as $prop => $val) {
                $this->$prop = $arrDB[$i];
                $i++;
            }

        } elseif ($this->id === 0 && preg_match('/[А-ЯёЁа-яA-Za-z]+/', $this->name) && preg_match('/[А-ЯёЁа-яA-Za-z]+/', $this->surname) && ($this->sex === 0 || $this->sex === 1) && !empty($this->age) && !empty($this->birthCity)) {

            if ($this->saveToDB($pdo)) {
                $stmt = $pdo->prepare("SELECT id FROM peoples WHERE name = ? AND surname = ? AND birth_date = ? AND sex = ? AND birth_city = ?");

                $i = 0;
                foreach ($this as $property => $value) {
                    if ($i === 0) {
                    $i++;
                    continue;
                }
            
                $stmt->bindValue($i, $this->$property, PDO::PARAM_STR);
                $i++;

            }

                $stmt->execute();

                $arrDB = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->id = $arrDB['id'];

            } else {

                throw new Exception('Не удалось записать в БД');

            }
        } else {

            throw new Exception('Проверьте правильность введенных данных');

        }
        

    }

    public function saveToDB(PDO $pdo): bool
    {
        $stmt = $pdo->prepare("INSERT INTO peoples (name, surname, birth_date, sex, birth_city) values (?, ?, ?, ?, ?)");
        $i = 0;
        foreach ($this as $property => $value) {

            if ($i === 0) {
                $i++;
                continue;
            }

            $stmt->bindValue($i, $this->$property, PDO::PARAM_STR);
            $i++;
        }

        return $stmt->execute();
    }

    public function deleteFromDB(PDO $pdo): bool
    {
        $stmt = $pdo->prepare("DELETE FROM peoples WHERE id = ? ORDER BY id asc LIMIT 1");
        $stmt->bindValue(1, $this->id, PDO::PARAM_INT);
            
        return $stmt->execute();
    }

    public static function dateToAge(string $birthday = '2000-03-15'): int
    {
        $timestamp = (int) strtotime($birthday);
        $age = (int) date('Y', time()) - (int) date('Y', $timestamp);

        if ((int) date('md', $timestamp) > (int) date('md', time())) {

            $age--;
            
        }

        return $age;
    }

    public static function binaryToSex(int $sex = 0): string
    {

        return ($sex !== 0) ? 'муж.' : 'жен.';

    }

    public function newPeopleInfo()
    {
        $newArr = [];
        $this->sex = self::binaryToSex($this->sex);
        $this->age = self::dateToAge($this->age);

        foreach ($this as $prop => $val) {
            $newArr[$prop] = $val;
        }

        $stdObj = (object) $newArr;
        return $stdObj;
    }
}