<?php
if(count($validation) != 0) {
    if (count($validation["submit"]) != 0) {
        if (empty($validation["submit"]["nok"])) {
            echo '<script>alert("' . $validation["submit"]["ok"] . '")</script>';
        } else {
            echo '<script>alert("' . $validation["submit"]["nok"] . '")</script>';
        }
    }
}
?>