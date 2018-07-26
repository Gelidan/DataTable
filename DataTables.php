<?php

class DataTables {
    private $str_title, $arrcolumns, $arrrows;

    function __construct() {
        $this->arrcolumns = array();
        $this->arrrows = array();
    }

    //Tábla title beállítása
    function set_title($str_title) {
        $this->str_title = $str_title;
    }

    //Tábla title lekérdezése
    function get_title() {
        return $this->str_title;
    }

    //Oszlop hozzáadása
    function addcolumn($columnname, $idxcolumn = null) {
        if (!in_array($columnname, $this->arrcolumns)) {
            array_push($this->arrcolumns, $columnname);
        }
    }

    //Oszlopok befűzése
    function columns($idx = null) {
        if (is_null($idx) || (string)$idx == "") {
            return $this->arrcolumns;
        } else {
            return $this->arrcolumns[$idx];
        }
    }

    //IDX oszlop lekérdezése
    function columnidx($columnname) {
        $array_keys = array_keys($this->arrcolumns, $columnname);
        return $array_keys[0];
    }

    //Sor hozzáadása
    function addrow($arrrow) {
        $this->arrrows[] = $arrrow;
    }

    //Sorok befűzése
    function rows($idx = null) {
        if (is_null($idx) || (string)$idx == "") {
            return $this->arrrows;
        } else {
            return $this->arrrows[$idx];
        }
    }

    //Adat lekérdezése sor és oszlop alapján
    function data($idxrow, $idxcolumn) {
        return $this->arrrows[$idxrow][$idxcolumn];
    }

    //Összes sorok száma
    function total_rows() {
        return count($this->arrrows);
    }

    //THEAD rajzolása
    function drawHeader($return = 'false') {
        $txt = "<thead><tr>";
        for ($j = 0; $j < count($this->columns()); $j++) {
            $txt .= "<th>" . $this->columns($j) . "</th>";
        }
        $txt .= "</tr></thead>";
        if ($return == 'true') {
            return $txt;
        } else {
            echo $txt;
        }
    }

    function drawHeaderOnly($columnlist, $return = 'false') {
        $txt = "<thead><tr>";
        foreach($columnlist as $column){
            $txt .= "<th>" . $column . "</th>";
        }
        $txt .= "</tr></thead>";
        if ($return == 'true') {
            return $txt;
        } else {
            echo $txt;
        }
    }

    //TBODY rajzolása
    function drawRows($return = 'false') {

        $txt = "<tbody>";
        for ($i = 0; $i < count($this->total_rows()); $i++) {
            $txt .= "<tr>";
            for ($j = 0; $j < count($this->columns()); $j++) {
                $txt .= "<td>" . $this->data($i, $j) . "</td>";
            }
            $txt .= "</tr>";
        }
        $txt .= "</tbody>";
        if ($return == 'true') {
            return $txt;
        } else {
            echo $txt;
        }
    }

    //Tábla kezdés
    function startTable($id, $return = 'false') {
        if ($return == 'true') {
            return '<table id="' . $id . '" class="dataTable table-responsive stripe no-footer">';
        } else {
            echo '<table id="' . $id . '" class="table-hover table-bordered">';
        }
    }

    //Tábla vég
    function endTable($return = 'false') {
        if ($return == 'true') {
            return '</table>';
        } else {
            echo '</table>';
        }
    }

    function drawTable($type, $return = 'false') {
        if ($return == 'true') {
            $txt = "<h3>" . $this->get_title() . "</h3>";
            $txt .= $this->startTable($type, 'true');
            $txt .= $this->drawHeader('true');
            $txt .= $this->drawRows('true');
            $txt .= $this->endTable('true');
            return $txt;
        } else {
            echo "<h3>" . $this->get_title() . "</h3>";
            $this->startTable($type);
            $this->drawHeader();
            $this->drawRows();
            $this->endTable();
        }
    }

    function drawEmptyTable($type, $return = 'false', $columnlist, $title) {
        if ($return == 'true') {
            $txt = "<h3>" . $title . "</h3>";
            $txt .= $this->startTable($type, 'true');
            $txt .= $this->drawHeaderOnly($columnlist, 'true');
            $txt .= $this->endTable('true');
            return $txt;
        } else {
            echo "<h3>" . $this->get_title() . "</h3>";
            $this->startTable($type);
            $this->drawHeader();
            $this->endTable();
        }
    }
}

?>