<?php
header('Content-type: application/json');

$action = $_REQUEST["action"];
$return = array();
echo var_dump($_POST);
if ($action === "config") {
    $return['status'] = 'success';
    $return['printer'] = array(
        'config'=> true, 
        'name'=> (isset($_POST["name"])) ? $_POST["name"] : 'EPSON PL', 
        'pagesize'=> (isset($_POST["pagesize"])) ? $_POST["pagesize"] : '80mm',

        '_shAg'=> (isset($_POST["agHash"])) ? $_POST["agHash"] : '49692F28-8E9A-43F7-40FF-6339E7C6B4D0', 
        'agencyName'=> (isset($_POST["agName"])) ? $_POST["agName"] : '2º CARTÓRIO NOTARIAL - A.S. ANTÓNIO', 
        'url'=> (isset($_POST["url"])) ? $_POST["url"] : 'http://localhost/workspace/calendar/lib/printing', 

    );
}
if ($action === "status") {
    $return = array('status'=> true);
}
if ($action === "print") {
    $return = array('print'=> true);
}

echo json_encode($return);