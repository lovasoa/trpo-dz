<?php
require_once("utils.php");

abstract class Model implements MogetOtvetchat {
  private $next = NULL;

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

  public function setName($name) {
    $this->name = $name;
  }
}
?>
