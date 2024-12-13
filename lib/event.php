<?php
header('Content-type: application/json');

if (isset($_GET["action"]) && $_GET["action"] == "post") {
    if (!file_exists('../data')) {
        mkdir('../data', 0777, true);
    }
    //echo $dadosJson;
    $path = '../data/events.txt';
    $number = 0;
    if (file_exists($path)) {
        $jsonString = file_get_contents($path);
        $jsonData = json_decode($jsonString, true);
        
        if (!is_null($jsonData) ) {
            $number = count($jsonData);
        }
        
    }
    $dados = array([
        "id" => $number+1,
        "title" => $_POST["title"],
        "date" => $_POST["date"],
        "color" => $_POST["color"],
    ]);
    $jsonString = '';
    if (file_exists($path)) {
        if (!is_null($jsonData) ) {
            $array = array_merge($jsonData,$dados);
            $jsonString = json_encode($array, JSON_PRETTY_PRINT);
        } else {
            $jsonString = json_encode($dados, JSON_PRETTY_PRINT);
        }
    } else {
        $jsonString = json_encode($dados, JSON_PRETTY_PRINT);
    }
    // Write in the file
    $fp = fopen($path, 'w+');
    fwrite($fp, $jsonString);
    fclose($fp);
    
    $arr = array('seccess'=> true);
    echo json_encode($arr);
} elseif (isset($_GET["action"]) && $_GET["action"] == "get") {
    # code...
    $path = '../data/events.txt';

    $jsonString = file_get_contents($path);
    $jsonData = json_decode($jsonString, true);

    var_dump($jsonData);
}  elseif (isset($_GET["action"]) && $_GET["action"] == "edit") {
    # code...
    $path = '../data/events.txt';
    $arr = array();

    if (file_exists($path)) {
        $jsonString = file_get_contents($path);
        $jsonData = json_decode($jsonString, true);

        foreach ($jsonData as $key => $entry) {
            if ($entry['id'] == $_POST["id"]) {
                $jsonData[$key]['title'] = $_POST["title"];
                $jsonData[$key]['date'] = $_POST["date"];
                $jsonData[$key]['color'] = $_POST["color"];
            }
        }

        $newJsonString = json_encode($jsonData);
        file_put_contents($path, $newJsonString);
        $arr = array('seccess'=> true);
    } else { $arr = array('error_file'=> true); }
    
    echo json_encode($arr);
} elseif (isset($_GET["action"]) && $_GET["action"] == "delete") {
    $path = '../data/events.txt';
    $arr = array();

    if (file_exists($path)) {
        $jsonString = file_get_contents($path);
        $jsonData = json_decode($jsonString, true);

        foreach ($jsonData as $key => $entry) {
            if ($entry['id'] == $_POST["id"]) {
                unset($jsonData[$key]);
            }
        }
        $jsonData = array_values($jsonData);
        $newJsonString = json_encode($jsonData);
        file_put_contents($path, $newJsonString);

        $arr = array('seccess'=> true);
    } else { $arr = array('error_file'=> true); }
    echo json_encode($arr);
} elseif (isset($_GET["action"]) && $_GET["action"] == "print") {
    // args[0] = action(printing/status)
    // args[1] = CCV
    // args[2] = B102
    // args[3] = #perioridade
    // args[4] = 12/11/2023 12:11:08
    // args[5] = Em Espera
    // args[6] = "ticket dv845afe"

    $date_time = (new \DateTime())->format('d/m/Y H:i:s');
    $res = shell_exec('start /b C:\\app\\silent-printing.exe "printing" "Casa do Cidadão" "B-104" "Criança de colo" "'.$date_time.'" 4 "\"ticket dv845afe\"" ');

    $arr = array(['seccess'=> true, 'result'=> $res]);
    var_dump($arr);
    echo json_encode(array('seccess'=> true));
}