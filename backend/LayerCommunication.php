<?php
class LayerCommunication {
  public function __construct($db) {
    // $tables = array("professor", "discipline", "material")
    $modules = array(
        new BasicModel($db, "professor"),
        new BasicModel($db, "discipline"),
        new BasicModel($db, "material"));
    $this->operator = $modules[0];
    foreach ($modules as $k=>$m) {
      if($k>0) $modules[$k-1]->setNext($modules[$k]);
    }
  }
  public function otvetchat($query) {
    try {
      return $this->operator->otvetchat($query);
    } catch (Exception $e) {
      http_response_code(500);
      die(json_encode(array("error" => $e->getMessage())));
    }
  }
}
 ?>
