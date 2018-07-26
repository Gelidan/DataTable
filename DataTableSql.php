<?php


class DataTableSql{

    private $db;
    private $buttontemplate = '<div class="btn-group" role="group">
					<button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						Művelet<span class="caret"></span>
					</button>
					<ul class="dropdown-menu pull-right" role="menu">
					    {links}
					</ul>
				</div>';
    private $litemplate = '<li><a class="{class}" href="{url}/{param}" data-id="{param}"> <i class="{icon}"></i> {name}</a></li>';

    function __construct() {
        $db = new PDODatabase(4);
        $this -> db = $db;
    }

    public function getDataForDataTable($title, $sql, $columnList, $buttons = null) {
        $dt = new DataTables();
        $dt->set_title($title);
        foreach($columnList as $value){
            $dt->addcolumn($value);
        }
        $this->db->query($sql);
        $this->db->execute();
        $rows = $this->db->resultset();
        $arr_row = array();
        foreach ($rows as $row) {
            for($i = 0; $i < count($columnList); $i++){
                $arr_row [$i] = $row[$i];
            }
            if(is_array($buttons)){
                $arr_row [$i-1] = $this->generateButtons($buttons, $row['id']);
            }
            $dt->addrow($arr_row);
        }
        return $dt;
    }

    private function generateButtons($buttons, $param){
        $template = $this->buttontemplate;
        $li = '';
        foreach ($buttons as $button) {
            unset($mit);
            unset($mire);
            foreach($button as $key => $value){
                $mit[] = '{'.$key.'}';
                if($key == 'param'){
                    $mire[] = $param;
                }else {
                    $mire[] = $value;
                }
            }
            $li .= str_replace($mit, $mire, $this->litemplate);
        }
        return str_replace('{links}', $li, $template);
    }

    public function getCountBySql($sql){
        $this->db->query($sql);
        $this->db->execute();
        return $this->db->rowCount();
    }

}