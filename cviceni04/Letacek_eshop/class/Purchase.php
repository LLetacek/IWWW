<?php


class Purchase
{
    public static function getPurchase($id) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "
SELECT n.uzivatel_id_uzivatel, n.produkt_id_produkt, datum, jmeno, n.cena 
FROM eshop.eshop_nakup n 
INNER JOIN eshop.eshop_produkt p 
ON n.produkt_id_produkt = p.id_produkt 
WHERE uzivatel_id_uzivatel = :id
ORDER BY datum DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function buy($id) {
        try {
            $kosik = Cart::getCart($id);
            $conn = Connection::getPdoInstance();

            foreach ($kosik as $value) {
                $produkt = Product::getProduct($value["id_produkt"]);
                if(!isset($produkt["cena"])) {
                    continue;
                }
                $sql = "
INSERT INTO eshop.eshop_nakup (datum, produkt_id_produkt, uzivatel_id_uzivatel, cena)
VALUES (SYSDATE(), :id_pr, :id_uz, :cena)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id_pr', $value["id_produkt"]);
                $stmt->bindParam(':id_uz', $value["id_uzivatel"]);
                $cena = $produkt["cena"] * $value["mnozstvi"];
                $stmt->bindParam(':cena', $cena);
                $stmt->execute();

                $sql = "DELETE FROM eshop.eshop_kosik WHERE id_uzivatel = :id_uz AND id_produkt = :id_pr";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id_pr', $value["id_produkt"]);
                $stmt->bindParam(':id_uz', $value["id_uzivatel"]);
                $stmt->execute();
            }

        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }
}

?>