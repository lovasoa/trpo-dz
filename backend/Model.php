<?php
require_once("utils.php");

abstract class Model implements MogetOtvetchat {
  /// The next element in the responsability chain
  private $next = NULL;

  /**
  * Answer to the query if its "api" parameter corresponds
  * to the "name" attribute of the object.
  * If not, pass the request to the next element in the chain.
  **/
  public function otvetchat(array $zaproc) {
    $type = (isset($zaproc["api"])) ? $zaproc["api"] : "";
    switch($type) {
      case $this->name:
        return json_encode($this->getValue($zaproc));
        break;
      default:
        if ($this->next !== NULL)
          return $this->next->otvetchat($zaproc);
        else
          throw new Exception("No such api: ".$type, 1);

    }
  }

  abstract public function getValue(array $zaproc);

  public function setNext(MogetOtvetchat $next) {
    $this->next = $next;
    return $next;
  }
  public function getNext() {
    return $this->next;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }
}
?>
