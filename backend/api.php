<?php
header("Content-type: application/json");
require_once 'utils.php';

$db = new Database();
$api = new LayerCommunication($db);
echo $api->otvetchat($_GET);
?>
