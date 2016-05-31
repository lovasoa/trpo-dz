<?php
require_once 'utils.php';
require_once 'student.php';

$db = new Database();
$modules = array(
    StudentAdapter::fabric($db, "discipline"),
    StudentAdapter::fabric($db, "material"),
    new BasicModel($db, "professor"),
    new BasicModel($db, "discipline"),
    new BasicModel($db, "material")
);
$api = new LayerCommunication($db, $modules);
echo $api->otvetchat($_GET);
?>
