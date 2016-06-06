<?php
require_once("utils.php");

/**
* Represent a gateway to a database table.
* This should be used so that business logic classes didn't have to interact with the database
*/
class BasicGateway {
  private $db = NULL;
  private $tablename;

  /// Create a new gateway for the given table
  public function __construct(Database $db, $tablename) {
    $this->db = $db;
    $this->tablename = $tablename;
  }

  /// Get an array of all elements in the table
  public function getAll() {
    $stmt = $this->db->query("SELECT * FROM ".$this->tablename);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

/**
* Update a given line in the table
* @param $data The current line
* @param $col Name of the column to update
* @param $val New value to insert
* @throw Exception SQL error
* @return An array containing information about the database query (number of affected rows)
*/
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

/**
* Add an empty element in the table
*/
  public function create() {
    return $this->db->exec("INSERT INTO ".($this->tablename)." () VALUES ()");
  }

/**
* Remove an element from the table
* @param $data The line to remove
*/
  public function delete(array $data) {
    $idcol = $this->tablename . "_id";
    $val = $data[$idcol];
    return $this->db->exec("DELETE FROM ".($this->tablename)." WHERE `$idcol`=$val");
  }
}
?>
