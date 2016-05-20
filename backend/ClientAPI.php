<?php
require_once("utils.php");

class ClientAPI {
  private $db = NULL;
  public function __construct($db) {
    $this->db = $db;
  }
  public function getData($api_name) {
    $db = $this->db;
    switch ($api_name) {
      case 'materials':
        $m = new Material($db);
        return $m->getAll();
        break;
      case 'professors':
          $m = new Professor($db);
          return $m->getAll();
          break;
      default:
        throw new InvalidArgumentException("Invalid api name", 1);
        break;
    }
  }

  public function displayData($data) {
    echo json_encode($data);
  }
}
?>
