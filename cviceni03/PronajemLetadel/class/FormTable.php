<?php


class FormTable
{
    private $title;
    private $btnName;
    private $columns;

    public function __construct($title, $btnName)
    {
        $this->title = $title;
        $this->btnName = $btnName;
    }

    public function addColumn($name, $label, $type)
    {
        $this->columns[$name]["jmeno"] = $label;
        $this->columns[$name]["typ"] = $type;
    }

    public function addColumnValue($name, $label, $type, $value)
    {
        $this->columns[$name]["jmeno"] = $label;
        $this->columns[$name]["typ"] = $type;
        $this->columns[$name]["hodnota"] = $value;
    }

    public function render($action)
    {
        echo '<form action='.$action.' method="post">';
        echo '<table>';
        echo '<tr>';
        echo '<th colspan="2"><label>' . $this->title . '</label></th>';
        echo '</tr>';
        foreach ($this->columns as $key => $value) {
            echo '<tr>';
            echo '<td class="tdr"><label>'.$value["jmeno"].':</label></td>';
            echo '<td><input name="'.$key.'" type="'.$value["typ"].'"';

            if(!empty($value["hodnota"])) {
                echo 'value="'.$value["hodnota"].'"';
            }

            echo '></td>';
            echo '</tr>';
        }

        echo '<tr>';
        echo '<td colspan="2" style="text-align: center" ><input type="submit" value="'.$this->btnName.'"></td>';
        echo '</tr>';
        echo '</table>';
        echo '</form>';
    }
}