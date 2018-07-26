<?php
ini_set('display_errors', 1);

require_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/configuration/configuration.php';
$functions = new Functions();

//Task specifikus beállítások
switch ($_POST['task']) {
    case 'userlist':
        $sql = "SELECT column1, column2, column3 FROM table";
        $title = "Datatables Title";
        $columnlist = [ 'Column 1 name', 'Column 2 name', 'Column 3 name'];
        $ordercolumns = ['table.column1', 'table.column2', 'table.column3'];
        $searchcolumns = $ordercolumns;
        $buttons = array(
            array('name' => 'Edit', 'url' => 'URLTOEDIT', 'param' => 'id', 'icon' => 'fa fa-edit', 'class' => ''),
            array('name' => 'Delete', 'url' => 'URLTODELETE', 'param' => 'id', 'icon' => 'fa fa-trash-alt', 'class' => 'delete')
        );
        break;
}
//Lapozó
if ($_REQUEST['length'] == -1) {
    $limit = '';
} else {
    $limit = " LIMIT " . $_REQUEST['start'] . ", " . $_REQUEST['length'];
}

//Keresés és sorba rendezés átvétele
$search = $_REQUEST['search']['value'];
$ordercolumn = $ordercolumns[$_REQUEST['order'][0]['column']];
$orderdirection = $_REQUEST['order'][0]['dir'];

//Ha volt keresés, akkor a keresési oszlopokban keresünk
if (strlen($search) > 0) {
    $searchexp = array();
    foreach ($searchcolumns as $column) {
        $searchexp[] = $column . " LIKE '%" . $search . "%' ";
    }
    $where = " AND (" . implode(' OR ', $searchexp) . ") ";
}

//Majd sorba rendezzük
if ($ordercolumn != '' && $orderdirection != '')
    $order = "ORDER BY " . $ordercolumn . " " . $orderdirection;

//Export válasz előkészítése
$export = array();
$export['draw'] = $_REQUEST['draw'];

//Eredeti sorok inicializálása
$dts_o = new DataTableSql();

//Teljes találati lista inicializálása
$dts_t = new DataTableSql();

//Végső SQL
$final_sql = $sql . " " . $where . " " . $order . " " . $limit;

//A keresés után maradt sorok lekérdezése limittel
$dts_f = new DataTableSql();
$dt_filter = $dts_f->getDataForDataTable($title, $final_sql, $columnlist, $buttons);

//Export adatok betöltése
$export['draw'] = $_REQUEST['draw'];
$export['recordsTotal'] = $dts_o->getCountBySql($sql);
$export['recordsFiltered'] = $dts_t->getCountBySql($sql . " " . $where . " " . $order);
$export['data'] = $dt_filter->rows();

//Export visszaadása
echo json_encode($export);