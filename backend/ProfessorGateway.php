<?php
require_once("utils.php");

class ProfessorGateway {
  public function __construct($db) {
    $this->db = $db;
  }
  public function getAll() {
    $res = array();
    foreach($this->db->query("SELECT * FROM professors") as $prof) {
      $res[] = array(
        "professor_id" => (int) $prof["professor_id"],
        "name" => $prof["name"]
      );
    }
    return $res;
  }
}
?>
