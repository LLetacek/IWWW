<?php


class Json
{
    public static function importJSON($imageKey){
        $imageFileType = strtolower(pathinfo($_FILES[$imageKey]["name"],PATHINFO_EXTENSION));
        if($imageFileType!="json") {
            $validation["submit"]["nok"] = "Není soubor JSON!";
        }

        $str = file_get_contents($_FILES[$imageKey]["tmp_name"]);
        $json = json_decode($str, true);

        foreach ($json as $value) {
            $tmp = Plane::getPlane($value["id_letadlo"]);
            if(!isset($tmp["id_letadlo"])) {
                Plane::insert(
                    $_SESSION["uzivatel"],
                    $value["imatrikulace"],
                    $value["nazev"],
                    $value["letiste"],
                    $value["kategorie"],
                    $value["obrazek"],
                    $value["stav"],
                    $value["cena_hodiny"]);
            }
            else {
                Plane::update($value["id_letadlo"],$value["letiste"],$value["stav"],$value["cena_hodiny"]);
            }
        }

        $validation["submit"]["ok"] = "Obnova úspěšná";
        return $validation;
    }

    public static function exportJSON($userid) {
        try {
            $array = Plane::getMyAll($userid);
        }
        catch (PDOException $e) {
            $array = Array("ERROR");
        }

        $json = json_encode($array, JSON_PRETTY_PRINT);
        $fileName = "SeznamMychLetadel";
        header("Content-disposition: attachment; filename=" . $fileName . "Export.json");
        header("Content-type: application/json");
        return ($json);
    }
}