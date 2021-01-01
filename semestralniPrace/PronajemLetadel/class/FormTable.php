<?php
//https://github.com/petrfilip/KIT-IWWW/blob/4-php-classes/classes/DataTable.php

class FormTable
{
    private $title;
    private $btnName;
    private $columns;
    private $selectionColumns;

    public function __construct($title, $btnName)
    {
        $this->title = $title;
        $this->btnName = $btnName;
    }

    public function addColumn($name, $label, $type)
    {
        $this->columns[$name]["name"] = $label;
        $this->columns[$name]["type"] = $type;
    }

    public function addColumnSelect($name, $label, $array)
    {
        $this->selectionColumns[$name]["name"] = $label;
        $this->selectionColumns[$name]["value"] = $array;
    }

    public function addColumnValue($name, $label, $type, $value)
    {
        $this->columns[$name]["name"] = $label;
        $this->columns[$name]["type"] = $type;
        $this->columns[$name]["value"] = $value;
    }

    public function render($action)
    {
        echo '<form action='.$action.' method="post" enctype="multipart/form-data">';
        echo '<table>';
        echo '<tr>';
        echo '<th colspan="2"><label>' . $this->title . '</label></th>';
        echo '</tr>';

        if(isset($this->selectionColumns)) {
            foreach ($this->selectionColumns as $key => $value) {
                echo '<tr>';
                echo '<td class="tdr"><label>' . $value["name"] . ':</label></td>';
                echo '<td><select name="'.$key.'">';
                foreach ($value["value"] as $select) {
                    echo '<option value="' . $select . '" >' . $select . '</option>';
                }
                echo '</select></td>';
                echo '</tr>';
            }
        }

        if(isset($this->columns)) {
            foreach ($this->columns as $key => $value) {
                echo '<tr>';
                echo '<td class="tdr"><label>' . $value["name"] . ':</label></td>';
                echo '<td><input name="' . $key . '" type="' . $value["type"] . '"';

                if (!empty($value["value"])) {
                    echo 'value="' . $value["value"] . '"';
                }

                echo '></td>';
                echo '</tr>';
            }
        }

        echo '<tr>';
        echo '<td colspan="2" style="text-align: center" ><input type="submit" value="'.$this->btnName.'" name="'.$this->title.'"></td>';
        echo '</tr>';
        echo '</table>';
        echo '</form>';
    }
}