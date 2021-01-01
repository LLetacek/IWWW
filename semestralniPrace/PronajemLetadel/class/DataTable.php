<?php
//https://github.com/petrfilip/KIT-IWWW/blob/4-php-classes/classes/DataTable.php

class DataTable
{
    private $dataSet;
    private $pictureColumns;
    private $editableColumns;
    private $selectionColumns;
    private $columns;

    public function __construct($dataSet)
    {
        $this->dataSet = $dataSet;
    }

    public function addPictureColumn($key, $humanReadableKey)
    {
        $this->pictureColumns[$key] = $humanReadableKey;
    }

    public function addEditableColumn($key, $humanReadableKey)
    {
        $this->editableColumns[$key] = $humanReadableKey;
    }

    public function addSelectionColumn($key, $humanReadableKey, $array)
    {
        $this->selectionColumns[$key]["title"] = $humanReadableKey;
        $this->selectionColumns[$key]["value"] = $array;
    }

    public function addColumn($key, $humanReadableKey)
    {
        $this->columns[$key] = $humanReadableKey;
    }

    public function renderWithButtons($idname, $action, $btn)
    {
        echo '<form action='.$action.' method="post">';
        echo '<table>';
        echo '<tr>';

        if(isset($this->pictureColumns)) {
            foreach ($this->pictureColumns as $value ) {
                echo '<th>'.$value.'</th>';
            }
        }

        if(isset($this->selectionColumns)) {
            foreach ($this->selectionColumns as $key => $value ) {
                echo '<th>'.$this->selectionColumns[$key]["title"].'</th>';
            }
        }

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

        foreach ($btn as $key => $value) {
            echo '<th class="btn-form-table">'.$key.'</th>';
        }

        echo '</tr>';
        if(isset($this->dataSet)) {
            foreach ($this->dataSet as $row) {
                echo ' <tr>';
                $id = $row[$idname];

                if (isset($this->pictureColumns)) {
                    foreach ($this->pictureColumns as $key => $value) {
                        $keyword = preg_split("/\//", $row[$key]);
                        echo '<td><img style="max-width: 100px;" src="'.$row[$key].'" alt="'.$keyword[sizeof($keyword)-1].'"></td>';
                    }
                }

                if (isset($this->selectionColumns)) {
                    foreach ($this->selectionColumns as $key => $value) {
                        echo '<td><select name="'.$key . $id.'">';
                        foreach ($this->selectionColumns[$key]["value"] as $select) {
                            echo '<option value="'.$select.'" ';
                            if ($row[$key] == $select)
                                echo 'selected';
                            echo '>'.$select.'</option>';
                        }
                        echo '</select></td>';
                    }
                }

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

                if (isset($btn)) {
                    foreach ($btn as $keyRow => $valueRow) {
                        echo '<td class="btn-form-table" style="text-align: center; width: 90px;">';
                        foreach ($btn[$keyRow] as $key => $value) {
                            if (isset($btn[$keyRow][$key])) {
                                echo '<input type="submit" name="' . $key . $id . '" value="' . $btn[$keyRow][$key] . '" /><br>';
                            }
                        }
                        echo '</td>';
                    }
                }
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