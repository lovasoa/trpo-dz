<?php
/**
* Manage communication between client and server layers
* \msc
*    Request,LayerCommunication,MogetOtvetchat;
*    |||;
*    Request->LayerCommunication [label="HTTP request"];
*    LayerCommunication=>MogetOtvetchat [label="MogetOtvetchat::otvetchat($zaproc)"];
*    LayerCommunication<<MogetOtvetchat [label="return string"];
*    Request<-LayerCommunication [label="HTTP response"];
*  \endmsc
*/
class LayerCommunication {
  /**
  * @var MogetOtvetchat first handler of the incoming query
  */
  private $operator;
  public function __construct(Database $db, $modules) {
    // $tables = array("professor", "discipline", "material")
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
