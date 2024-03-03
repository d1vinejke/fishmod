<?php
require_once "RequestHandler.php";
if(isset($_GET['axios']) || isset($_POST['axios'])){
    header('Content-Type: application/json; charset=utf-8');
    $data = $_GET['axios']['DATA'] ?? $_POST['axios']['DATA'];
    $typeQ = $_GET['axios']['TYPE'] ?? $_POST['axios']['TYPE'];
    $tableN = $_GET['axios']['TABLE_NAME'] ?? $_POST['axios']['TABLE_NAME'];
    $conditions = $_GET['axios']['CONDITIONS'] ?? $_POST['axios']['CONDITIONS'];
    $leftJoins = $_GET['axios']['LEFT_JOINS'] ?? $_POST['axios']['LEFT_JOINS'];

    if(!empty($data) && !empty($typeQ) && !empty($tableN)){
        $init = new RequestHandler();
        $res = $init->ExecuteRequest($data, $typeQ, $tableN, $conditions, $leftJoins);
        echo json_encode([$res], JSON_UNESCAPED_UNICODE);
    }
    exit;
}else header('Location: /index.php');