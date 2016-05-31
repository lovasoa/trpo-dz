<?php
require_once("utils.php");

class MaterialGateway {
  public function __construct($db) {
    $this->db = $db;
  }
  public function getAll() {
    $res = array();
    foreach($this->db->query("SELECT * FROM materials") as $prof) {
      $res[] = array(
        "material_id" => (int) $prof["material_id"],
        "discipline_id" => (int) $prof["discipline_id"],
        "filename" => $prof["filename"]
      );
    }
    return $res;
  }
}
?>
