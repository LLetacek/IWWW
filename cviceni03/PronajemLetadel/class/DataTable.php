<?php
//https://github.com/petrfilip/KIT-IWWW/blob/4-php-classes/classes/DataTable.php

class DataTable
{
    private $dataSet;
    private $columns;

    public function __construct($dataSet)
    {
        $this->dataSet = $dataSet;
    }

    public function addColumn($key, $humanReadableKey)
    {
        $this->columns[$key] = $humanReadableKey;
    }

    public function renderForManage($action)
    {
        echo '<form action='.$action.' method="post">';
        echo ' <table>';
        echo '<tr>';
        foreach ($this->columns as $key => $value) {
            echo '<th>'.$value.'</th>';
        }
        echo '<th>Správa</th>';
        echo '</tr>';
        foreach ($this->dataSet as $row) {
            echo ' <tr>';
            $id = "";
            foreach ($this->columns as $key => $value) {
                $id = $row["id_uzivatel"];
                echo '<td>' .
                    '<input name="'. $key . $id .'" type="input" value="'.$row[$key].'">'
                    . '</td>';
            }
            echo '<td>';
             echo '<input type="submit" name="updtUcet'.$id.'" value="Oprav" />';
             echo '<input type="submit" name="zrusUcet'.$id.'" value="Zrušit účet" />';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</form>';
    }
}