<?php


class Plane
{
    public static function getPlane($id) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "SELECT * FROM letadlo WHERE id_letadlo = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function getPlaneImatrikulace($imatrikulace) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "SELECT * FROM letadlo WHERE imatrikulace = :imatrikulace";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':imatrikulace', $imatrikulace);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function getAllByKategory($id, $kategorie) {
        try {
            $conn = Connection::getPdoInstance();
            $stmt = $conn->prepare("
SELECT l.id_letadlo, l.cena_hodiny, l.imatrikulace, l.nazev, k.nazev as kategorie, l.obrazek, u.username as majitel, l.stav, aprt.icao as letiste
FROM letadlo l 
JOIN uzivatel u
ON l.majitel = u.id_uzivatel 
JOIN letiste aprt
ON l.letiste_id_letiste = aprt.id_letiste
JOIN kategorie k
ON l.kategorie_id_kategorie = k.id_kategorie
WHERE majitel <> :id AND k.nazev = :kategorie
ORDER BY id_letadlo DESC");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':kategorie', $kategorie);
            $stmt->execute();

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $dataset = $stmt->fetchAll();
            return $dataset;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function getAll($id) {
        try {
            $conn = Connection::getPdoInstance();
            $stmt = $conn->prepare("
SELECT l.id_letadlo, l.cena_hodiny, l.imatrikulace, l.nazev, k.nazev as kategorie, l.obrazek, u.username as majitel, l.stav, aprt.icao as letiste
FROM letadlo l 
JOIN uzivatel u
ON l.majitel = u.id_uzivatel 
JOIN letiste aprt
ON l.letiste_id_letiste = aprt.id_letiste
JOIN kategorie k
ON l.kategorie_id_kategorie = k.id_kategorie
WHERE majitel <> :id
ORDER BY id_letadlo DESC");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $dataset = $stmt->fetchAll();
            return $dataset;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function getMyAll($id) {
        try {
            $conn = Connection::getPdoInstance();
            $stmt = $conn->prepare("
SELECT l.id_letadlo, l.cena_hodiny, l.imatrikulace, l.nazev, k.nazev as kategorie, l.obrazek, l.stav, aprt.icao as letiste
FROM letadlo l 
JOIN uzivatel u
ON l.majitel = u.id_uzivatel 
JOIN letiste aprt
ON l.letiste_id_letiste = aprt.id_letiste
JOIN kategorie k
ON l.kategorie_id_kategorie = k.id_kategorie
WHERE majitel = :id
ORDER BY id_letadlo ASC");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $dataset = $stmt->fetchAll();
            return $dataset;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function getMyAllByColumn($id, $column) {
        try {
            $conn = Connection::getPdoInstance();
            $stmt = $conn->prepare("
SELECT ".$column."
FROM letadlo
WHERE majitel = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $dataset = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $dataset;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function getFiveLast() {
        try {
            $conn = Connection::getPdoInstance();
            $stmt = $conn->prepare("
SELECT imatrikulace, k.nazev as kategorie
FROM letadlo l 
JOIN kategorie k 
ON l.kategorie_id_kategorie = k.id_kategorie 
ORDER BY id_letadlo DESC LIMIT 5");
            $stmt->execute();

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $dataset = $stmt->fetchAll();
            return $dataset;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function getFiveTop() {
        try {
            $conn = Connection::getPdoInstance();
            $stmt = $conn->prepare("
SELECT l.id_letadlo, l.imatrikulace, k.nazev as kategorie, COUNT(*) as pocet
FROM letadlo l 
JOIN kategorie k 
ON l.kategorie_id_kategorie = k.id_kategorie
JOIN rezervace r
ON r.letadlo_id_letadlo = l.id_letadlo
GROUP BY l.id_letadlo
ORDER BY pocet DESC  LIMIT 5");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $dataset = $stmt->fetchAll();
            return $dataset;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function insert($majitel, $imatrikulace, $nazev, $icao, $kategorie, $obrazek, $stav, $cena) {
        try {
            $conn = Connection::getPdoInstance();

            $stmt = $conn->prepare("SELECT id_kategorie FROM kategorie WHERE nazev = :kategorie");
            $stmt->bindParam(':kategorie', $kategorie);
            $stmt->execute();
            $result = $stmt->fetch();
            $kategorie = $result;

            $stmt = $conn->prepare("SELECT id_letiste FROM letiste WHERE icao = :icao");
            $stmt->bindParam(':icao', $icao);
            $stmt->execute();
            $result = $stmt->fetch();
            $icao = $result;

            $sql = "INSERT INTO letadlo 
(imatrikulace, nazev, letiste_id_letiste, majitel, obrazek, kategorie_id_kategorie, stav, cena_hodiny) 
VALUES 
(:imatrikulace, :nazev, :letiste, :majitel, :obrazek, :kategorie, :stav, :cena)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':imatrikulace', $imatrikulace);
            $stmt->bindParam(':nazev', $nazev);
            $stmt->bindParam(':letiste', $icao[0]);
            $stmt->bindParam(':majitel', $majitel);
            $stmt->bindParam(':obrazek', $obrazek);
            $stmt->bindParam(':kategorie', $kategorie[0]);
            $stmt->bindParam(':stav', $stav);
            $stmt->bindParam(':cena', $cena);

            $stmt->execute();
            $validation["submit"]["ok"] = "Uspesne vlozeni";

        } catch (PDOException $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        } catch (Error $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        }
        return $validation;
    }

    public static function insertRepair($imatrikulace, $duvod ,$datum) {
        try {
            $conn = Connection::getPdoInstance();

            $stmt = $conn->prepare("SELECT id_letadlo FROM letadlo WHERE imatrikulace = :imatrikulace");
            $stmt->bindParam(':imatrikulace', $imatrikulace);
            $stmt->execute();
            $result = $stmt->fetch();
            $imatrikulace = $result;

            $sql = "INSERT INTO opravy 
(duvod, letadlo_id_letadlo, datum) 
VALUES 
(:duvod, :imatrikulace, :datum)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':imatrikulace', $imatrikulace[0]);
            $stmt->bindParam(':duvod', $duvod);
            $stmt->bindParam(':datum', $datum);

            $stmt->execute();
            $validation["submit"]["ok"] = "Uspesne vlozeni";

        } catch (PDOException $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        } catch (Error $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        }
        return $validation;
    }

    public static function delete($id) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "DELETE FROM letadlo WHERE id_letadlo = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $validation["submit"]["ok"] = "Letadlo bylo smazano";
        } catch (PDOException $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        }
        return $validation;
    }

    public static function update($pid, $letiste, $dostupnost ,$cena) {
        try {
            $conn = Connection::getPdoInstance();

            $stmt = $conn->prepare("SELECT id_letiste FROM letiste WHERE icao = :icao");
            $stmt->bindParam(':icao', $letiste);
            $stmt->execute();
            $result = $stmt->fetch();
            $letiste = $result;

            $plane = Plane::getPlane($pid);
            if (empty($cena) || !is_numeric($cena)) {
                $cena = $plane["cena_hodiny"];
            }

            $sql = "UPDATE letadlo SET 
                letiste_id_letiste = :letiste, cena_hodiny = :cena, stav = :stav 
                WHERE id_letadlo = :id";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':letiste', $letiste[0]);
            $stmt->bindParam(':cena', $cena);
            $stmt->bindParam(':stav', $dostupnost);
            $stmt->bindParam(':id', $pid);

            $stmt->execute();
            $validation["submit"]["ok"] = "Uspesna uprava";
        } catch (PDOException $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        } catch (Error $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        }
        return $validation;
    }

    public static function getRepair($imatrikulace) {
        try {
            $conn = Connection::getPdoInstance();

            $stmt = $conn->prepare("SELECT id_letadlo FROM letadlo WHERE imatrikulace = :imatrikulace");
            $stmt->bindParam(':imatrikulace', $imatrikulace);
            $stmt->execute();
            $result = $stmt->fetch();
            $imatrikulace = $result;

            $sql = "SELECT * FROM opravy WHERE letadlo_id_letadlo = :letadlo";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':letadlo', $imatrikulace[0]);

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $dataset = $stmt->fetchAll();
            return $dataset;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        } catch (Error $e) {
            print_r($e->getMessage());
        }
    }


    public static function insertReservation($iduser, $idplane ,$cash ,$timestamp, $hours) {
        try {
            $conn = Connection::getPdoInstance();

            $totime = strtotime('+'.$hours.' hours', strtotime($timestamp));
            $totime = date("Y-m-d H:i:s",$totime);

            $stmt = $conn->prepare("
SELECT * 
FROM rezervace 
WHERE letadlo_id_letadlo = :letadlo 
AND (
     (datum BETWEEN :od AND :do)
     OR
    ((datum + INTERVAL pocet_hodin HOUR) BETWEEN :od AND :do)
     OR
     (:od BETWEEN datum AND (datum + INTERVAL pocet_hodin HOUR))
     OR
     (:do BETWEEN datum AND (datum + INTERVAL pocet_hodin HOUR))
     )");
            $stmt->bindParam(':letadlo', $idplane);
            $stmt->bindParam(':od', $timestamp);
            $stmt->bindParam(':do', $totime);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();

            if(!empty($result)) {
                $validation["submit"]["nok"] = "Na tuto dobu je již rezervováno.";
                return $validation;
            }


            $sql = "INSERT INTO rezervace
(uzivatel_id_uzivatel, letadlo_id_letadlo, datum, cena, pocet_hodin) 
VALUES 
(:uzivatel, :letadlo, :datum, :cena, :hodin)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':uzivatel', $iduser);
            $stmt->bindParam(':letadlo', $idplane);
            $stmt->bindParam(':datum', $timestamp);
            $total = $hours * $cash;
            $stmt->bindParam(':cena', $total);
            $stmt->bindParam(':hodin', $hours);
            $stmt->execute();
            $validation["submit"]["ok"] = "Uspesne vlozeni";

        } catch (PDOException $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        } catch (Error $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        }
        return $validation;
    }


    public static function getReservationUserHist($iduser) {
        try {
            $conn = Connection::getPdoInstance();

            $stmt = $conn->prepare("
SELECT * 
FROM rezervace 
JOIN letadlo
ON letadlo_id_letadlo = id_letadlo
JOIN letiste
ON letiste_id_letiste = id_letiste
WHERE uzivatel_id_uzivatel = :uzivatel 
AND (datum - INTERVAL 1 DAY) <= CURRENT_TIMESTAMP() ");
            $stmt->bindParam(':uzivatel', $iduser);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        } catch (Error $e) {
            print_r($e->getMessage());
        }
    }

    public static function getReservationUserNext($iduser) {
        try {
            $conn = Connection::getPdoInstance();

            $stmt = $conn->prepare("
SELECT * 
FROM rezervace 
JOIN letadlo
ON letadlo_id_letadlo = id_letadlo
JOIN letiste
ON letiste_id_letiste = id_letiste
WHERE uzivatel_id_uzivatel = :uzivatel 
AND (datum - INTERVAL 1 DAY) > CURRENT_TIMESTAMP() ");
            $stmt->bindParam(':uzivatel', $iduser);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        } catch (Error $e) {
            print_r($e->getMessage());
        }
    }

    public static function getReservationPlaneHist($imatrikulace) {
        try {
            $conn = Connection::getPdoInstance();

            $imatrikulace = Plane::getPlaneImatrikulace($imatrikulace);

            $stmt = $conn->prepare("
SELECT * 
FROM rezervace 
JOIN uzivatel
ON uzivatel_id_uzivatel = id_uzivatel
WHERE letadlo_id_letadlo = :letadlo 
AND (datum - INTERVAL 1 DAY) <= CURRENT_TIMESTAMP() ");
            $stmt->bindParam(':letadlo', $imatrikulace["id_letadlo"]);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        } catch (Error $e) {
            print_r($e->getMessage());
        }
    }

    public static function getReservationPlaneNext($imatrikulace) {
        try {
            $conn = Connection::getPdoInstance();

            $imatrikulace = Plane::getPlaneImatrikulace($imatrikulace);

            $stmt = $conn->prepare("
SELECT * 
FROM rezervace
JOIN uzivatel
ON uzivatel_id_uzivatel = id_uzivatel 
WHERE letadlo_id_letadlo = :letadlo 
AND (datum - INTERVAL 1 DAY) > CURRENT_TIMESTAMP() ");
            $stmt->bindParam(':letadlo', $imatrikulace["id_letadlo"]);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        } catch (Error $e) {
            print_r($e->getMessage());
        }
    }

}