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
        "professor_id" => (int) $prof["professor_id"],
        "filename" => $prof["filename"]
      );
    }
    return $res;
  }
}
?>
