<?php


class Airport
{
    public static function getAirport($id) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "SELECT * FROM letiste WHERE id_letiste = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function getAirportByICAO($icao) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "SELECT * FROM letiste WHERE icao = :icao";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':icao', $icao);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function getAirportColumn($column) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "SELECT ".$column." FROM letiste ORDER BY icao ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function getAll() {
        try {
            $conn = Connection::getPdoInstance();
            $stmt = $conn->prepare("
SELECT *
FROM letiste 
ORDER BY icao ASC");
            $stmt->execute();

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $dataset = $stmt->fetchAll();
            return $dataset;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function delete($id) {
        try {
            $conn = Connection::getPdoInstance();
            $stmt = $conn->prepare("DELETE FROM letiste WHERE id_letiste = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $validation["submit"]["ok"] = "Uspesne smazani";
        } catch (PDOException $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        } catch (Error $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        }
        return $validation;
    }
}