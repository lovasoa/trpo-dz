<?php
require_once("utils.php");

class Material {
  public function __construct($db) {
    $this->gateway = new MaterialGateway($db);
  }
  public function getAll() {
    return $this->gateway->getAll();
  }
}


/*
$db = new Database();
$p = new Material($db);
var_dump($p->getAll())
*/
?>
