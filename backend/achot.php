<?php
require_once("utils.php");

interface MogetOtvetchat {
  public function otvetchat(array $zaproc);
  public function setNext(MogetOtvetchat $next);
}

abstract class Zaproc implements MogetOtvetchat {
  private $next = NULL;

  public function otvetchat(array $zaproc) {
    $type = (isset($zaproc["type"])) ? $zaproc["type"] : "";
    switch($type) {
      case $this->name:
        return $this->getValue($zaproc);
        break;
      default:
        if ($this->next !== NULL)
          return $this->next->otvetchat($zaproc);
        else
          throw new Exception("Nielzia otvitchat na etot zaproc", 1);

    }
  }

  abstract public function getValue(array $zaproc);

  public function setNext(MogetOtvetchat $next) {
    $this->next = $next;
  }

  public function setName($name) {
    $this->name = $name;
  }
}

class PrepodavatelZaproc extends Zaproc {
  public function __construct() {
    $this->setName("prepodavatel");
    $this->shliuz = new PrepodavatelShluz();
  }
  public function getValue(array $zaproc) {
    $res = "<ul>";
    foreach ($this->shliuz->cpicok() as $prepod) {
      $name = $prepod["name"];
      $res .= "<li><a href='achot.php?type=disziplin&prepodavatel=$name'>$name</a></li>";
    }
    $res .= "</ul>";
    return $res;
  }
}

class PrepodavatelShluz {
  public function __construct () {
    $this->db = new Database();
  }
  public function cpicok() {
    $sql = "SELECT * FROM professors";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }
}

class DisziplinZaproc extends Zaproc {
  public function __construct() {
    $this->setName("disziplin");
    $this->shliuz = new DisziplinShluz();
  }
  public function getValue(array $zaproc) {
    $predpod = $zaproc["prepodavatel"];
    $res = "<h1>Сприсок Преподавателя $predpod</h1>" .
            "<ul>";
    foreach ($this->shliuz->cpicok($predpod) as $disz) {
      $name = $disz["name"];
      $res .= "<li>$name</li>";
    }
    $res .= "</ul>";
    return $res;
  }
}

class DisziplinShluz {
  public function __construct () {
    $this->db = new Database();
  }
  public function cpicok($predpod) {
    $sql = "SELECT disciplines.name
            FROM disciplines, professors
            WHERE    professors.professor_id = disciplines.professor_id ";
    if ($predpod) $sql .= "AND professors.name = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(array($predpod));
    return $stmt->fetchAll();
  }
}

class Glavnoe {
  public function __construct() {
    $this->operator = new PrepodavatelZaproc();
    $this->operator->setNext(new DisziplinZaproc);
  }
  public function otvetchat($zaproc) {
    try {
      return $this->operator->otvetchat($zaproc);
    } catch (Exception $e) {
      return "<h1>Добро пожаловать</h1>".
              "<p><a href='achot.php?type=prepodavatel'>Преподаватели</a></p>";
    }
  }
}

$glavno = new Glavnoe();
echo $glavno->otvetchat($_GET);
?>
