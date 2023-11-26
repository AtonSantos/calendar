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
}

