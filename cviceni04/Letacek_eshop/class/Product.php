<?php


class Product
{
    public static function getProduct($id) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "SELECT * FROM eshop.eshop_produkt WHERE id_produkt = :id";
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