<?php
require_once("utils.php");

class BasicGateway {
  public function __construct($db, $tablename) {
    $this->db = $db;
    $this->tablename = $tablename;
  }
  public function getAll() {
    $stmt = $this->db->query("SELECT * FROM ".$this->tablename);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function update($data, $col, $val) {
    $idcol = $this->tablename . "_id";
    $sql = "UPDATE " . ($this->tablename) .
           " SET `" . $col . "` = ?" .
           " WHERE " . $idcol . " = ?" .
           " LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $res = $stmt->execute(array($val, $data[$idcol]));
    if ($res === FALSE) {
      $err = $stmt->errorInfo();
      throw new Exception("SQL Error: ".$err[2], 1);
    } else {
      return array("affected_rows" => $stmt->rowCount());
    }
  }

  public function create() {
    return $this->db->exec("INSERT INTO ".($this->tablename)." () VALUES ()");
  }

  public function delete($data) {
    $idcol = $this->tablename . "_id";
    $val = $data[$idcol];
    return $this->db->exec("DELETE FROM ".($this->tablename)." WHERE `$idcol`=$val");
  }
}
?>
