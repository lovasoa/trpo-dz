<?php
require_once("utils.php");

class Professor {
  public function __construct($db) {
    $this->gateway = new ProfessorGateway($db);
  }
  public function getAll() {
    return $this->gateway->getAll();
  }
}
?>
