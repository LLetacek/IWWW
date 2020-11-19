<?php
//https://github.com/petrfilip/KIT-IWWW/blob/4-php-classes/classes/DataTable.php

class DataTable
{
    private $dataSet;
    private $editableColumns;
    private $columns;

    public function __construct($dataSet)
    {
        $this->dataSet = $dataSet;
    }

    public function addEditableColumn($key, $humanReadableKey)
    {
        $this->editableColumns[$key] = $humanReadableKey;
    }

    public function addColumn($key, $humanReadableKey)
    {
        $this->columns[$key] = $humanReadableKey;
    }

    public function renderWithButtons($title, $idname, $action, $btn)
    {
        echo '<form action='.$action.' method="post">';
        echo ' <table>';
        echo '<tr>';

        if(isset($this->editableColumns)) {
            foreach ($this->editableColumns as $value ) {
                echo '<th>'.$value.'</th>';
            }
        }

        if(isset($this->columns)) {
            foreach ($this->columns as $value ) {
                echo '<th>'.$value.'</th>';
            }
        }

        echo '<th>'.$title.'</th>';
        echo '</tr>';
        if(isset($this->dataSet)) {
            foreach ($this->dataSet as $row) {
                echo ' <tr>';
                $id = $row[$idname];

                if (isset($this->editableColumns)) {
                    foreach ($this->editableColumns as $key => $value) {
                        echo '<td>' .
                            '<input name="' . $key . $id . '" type="input" value="' . $row[$key] . '">'
                            . '</td>';
                    }
                }

                if (isset($this->columns)) {
                    foreach ($this->columns as $key => $value) {
                        echo '<td style="text-align: center">' . $row[$key] . '</td>';
                    }
                }

                echo '<td style="text-align: center; width: 90px;">';
                foreach ($btn as $key => $value) {
                    if (isset($btn[$key]["jmeno"]) && isset($btn[$key]["hodnota"])) {
                        echo '<input type="submit" name="' . $btn[$key]["jmeno"] . $id . '" value="' . $btn[$key]["hodnota"] . '" /><br>';
                    }
                }

                echo '</td>';
                echo '</tr>';
            }
        }
        echo '</table>';
        echo '</form>';
    }

    public function render()
    {
        echo ' <table>';
        echo '<tr>';

        if(isset($this->columns)) {
            foreach ($this->columns as $value ) {
                echo '<th>'.$value.'</th>';
            }
        }

        echo '</tr>';
        if(isset($this->dataSet)) {
            foreach ($this->dataSet as $row) {
                echo ' <tr>';

                if (isset($this->columns)) {
                    foreach ($this->columns as $key => $value) {
                        echo '<td style="text-align: center">' . $row[$key] . '</td>';
                    }
                }

                echo '</td>';
                echo '</tr>';
            }
        }
        echo '</table>';
    }
}