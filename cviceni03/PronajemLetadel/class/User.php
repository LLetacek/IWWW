<?php


class User
{
    public static function getUser($id) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "SELECT * FROM uzivatel WHERE id_uzivatel = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function update($pid,$pusername,$pheslo,$pjmeno,$pprijmeni,$pemail,$ptelefon) {
        try {
            $conn = Connection::getPdoInstance();

            $user = User::getUser($pid);

            if (empty($pusername)) {
                $userName = $user["username"];
            } else {
                $userName = $pusername;
            }

            if (empty($pheslo)) {
                $heslo = $user["heslo"];
            } else {
                $heslo = md5($pheslo);
            }

            if (empty($pjmeno)) {
                $jmeno = $user["jmeno"];
            } else {
                $jmeno = $pjmeno;
            }

            if (empty($pprijmeni)) {
                $prijmeni = $user["prijmeni"];
            } else {
                $prijmeni = $pprijmeni;
            }

            if (empty($pemail) && empty($ptelefon)) {
                $email = $user["email"];
                $telefon = $user["telefon"];
            } else {
                $email = $pemail;
                $telefon = $ptelefon;
            }


            $sql = "UPDATE uzivatel SET 
                username = :userName, heslo = :heslo, email = :email, telefon = :telefon, jmeno = :jmeno, prijmeni = :prijmeni 
                WHERE id_uzivatel = :id";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':userName', $userName);
            $stmt->bindParam(':heslo', $heslo);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefon', $telefon);
            $stmt->bindParam(':jmeno', $jmeno);
            $stmt->bindParam(':prijmeni', $prijmeni);
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

    public static function deleteUser($id) {
        try {
            $conn = Connection::getPdoInstance();
            $sql = "DELETE FROM uzivatel WHERE id_uzivatel = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $validation["submit"]["ok"] = "Uzivatel byl smazan";
        } catch (PDOException $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        }
        return $validation;
    }

    public static function getAll($id) {
        try {
            $conn = Connection::getPdoInstance();
            $stmt = $conn->prepare("SELECT id_uzivatel, username, jmeno, prijmeni, email, telefon FROM uzivatel WHERE id_uzivatel <> :id ORDER BY username ASC");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $dataset = $stmt->fetchAll();
            return $dataset;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }
}