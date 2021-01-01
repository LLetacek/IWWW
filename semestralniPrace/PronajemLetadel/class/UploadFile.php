<?php


class UploadFile
{
    public static function upload($target_dir, $imageKey) {
        $target_file = $target_dir . basename($_FILES[$imageKey]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $check = getimagesize($_FILES[$imageKey]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        if (file_exists($target_file) || $_FILES[$imageKey]["size"] > 600000) {
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            $uploadOk = 0;
        }

        if ($uploadOk != 0 && move_uploaded_file($_FILES[$imageKey]["tmp_name"], $target_file)) {
            return $target_file;
        }
    }
}