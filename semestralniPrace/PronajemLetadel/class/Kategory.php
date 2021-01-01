<?php


class Kategory
{
    public static function getAllColumn($column) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "SELECT ".$column." FROM kategorie";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);;
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function getKategory($id) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "SELECT * FROM kategorie WHERE id_kategorie = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }
}