<?php


class Cart
{
    public static function getCart($id) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "
SELECT id_uzivatel, k.id_produkt, jmeno, p.cena, mnozstvi 
FROM eshop.eshop_kosik k 
INNER JOIN eshop.eshop_produkt p 
ON k.id_produkt = p.id_produkt 
WHERE id_uzivatel = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function addItem($id_uz, $id_pr) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "SELECT * FROM eshop.eshop_kosik WHERE id_uzivatel = :id_uz AND id_produkt = :id_pr";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_uz', $id_uz);
            $stmt->bindParam(':id_pr', $id_pr);
            $stmt->execute();
            $result = $stmt->fetch();

            if(!isset($result["mnozstvi"])) {
                $mnozstvi = 1;
                $sql = "INSERT INTO eshop.eshop_kosik (id_uzivatel, id_produkt, mnozstvi) VALUES (:id_uz, :id_pr, :mnozstvi)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id_uz', $id_uz);
                $stmt->bindParam(':id_pr', $id_pr);
                $stmt->bindParam(':mnozstvi', $mnozstvi);
                $stmt->execute();
                $validation["submit"]["ok"] = "Vlozeno do kosiku";
                return;
            }

            $mnozstvi = $result["mnozstvi"]+1;
            $sql = "UPDATE eshop.eshop_kosik SET mnozstvi = :mnozstvi WHERE id_uzivatel = :id_uz AND id_produkt = :id_pr";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':mnozstvi',$mnozstvi);
            $stmt->bindParam(':id_uz', $id_uz);
            $stmt->bindParam(':id_pr', $id_pr);
            $stmt->execute();
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function remItem($id_uz, $id_pr) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "SELECT * FROM eshop.eshop_kosik WHERE id_uzivatel = :id_uz AND id_produkt = :id_pr";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_uz', $id_uz);
            $stmt->bindParam(':id_pr', $id_pr);
            $stmt->execute();
            $result = $stmt->fetch();

            if(!isset($result["mnozstvi"])) {
                return;
            } else if($result["mnozstvi"] <= 1) {
                Cart::delItem($id_uz,$id_pr);
                return;
            }

            $mnozstvi = $result["mnozstvi"]-1;
            $sql = "UPDATE eshop.eshop_kosik SET mnozstvi = :mnozstvi WHERE id_uzivatel = :id_uz AND id_produkt = :id_pr";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':mnozstvi',$mnozstvi);
            $stmt->bindParam(':id_uz', $id_uz);
            $stmt->bindParam(':id_pr', $id_pr);
            $stmt->execute();
        } catch (PDOException $e) {
            print_r($e->getMessage());
            $validation["submit"]["nok"] = "Produkt se nepodarilo odebrat";
        }
    }

    public static function delItem($id_uz, $id_pr) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "DELETE FROM eshop.eshop_kosik WHERE id_uzivatel = :id_uz AND id_produkt = :id_pr";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_uz', $id_uz);
            $stmt->bindParam(':id_pr', $id_pr);
            $stmt->execute();
            $validation["submit"]["ok"] = "Produkt byl smazan";
        } catch (PDOException $e) {
            print_r($e->getMessage());
            $validation["submit"]["nok"] = "Produkt se nepodarilo smazat";
        }
    }
}