<?php
require_once 'utils.php';
require_once 'student.php';

$db = new Database();
$modules = array(
    StudentAdapter::fabric($db, "discipline"),
    StudentAdapter::fabric($db, "material"),
    new ProfessorsModel($db),
    new DisciplinesModel($db),
    new MaterialsModel($db)
);
$api = new LayerCommunication($db, $modules);
echo $api->otvetchat($_GET);
?>
