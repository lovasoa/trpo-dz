<?php

/**
 * Buisines Logic: table module pattern
 */
abstract class TableModule {
  private $rows;
  function __construct() {
    $this->rows = array();
  }
  function add(array $row) {
    $this->rows[] = $row;
  }
  protected function getRows() {
    return $this->rows;
  }
  abstract public function view();
}

class Disciplines extends TableModule {
  function view() {
    $ret = "";
    foreach ($this->getRows() as $discipline) {
      $name = $discipline["имя"];
      $id   = $discipline["discipline_id"];
      $ret .= "<p>" .
             "<a href='api.php?student=material&discipline=$id'>$name</a>".
             "</p>";
    }
    return $ret;
  }
}

class Materials extends TableModule {
  private $filter = "";
  function __construct($discipline, $search) {
    $this->filter = $discipline;
    $this->search = $search;
    parent::__construct();
  }
  function view() {
    $ret = "<form action='api.php'>
		<input type='hidden' name='student' value='material'>
		<input type='hidden' name='discipline' value='$this->filter'>
		<input placeholder='поиск' name='search' value='$this->search'>
	   ";
    foreach ($this->getRows() as $material) {
      $name = $material["filename"];
      $ret .= "<p>" .
             "<a href='materials/$name' download='$name'>$name</a>".
             "</p>";
    }
    return $ret;
  }
  function add(array $row) {
    if($row["discipline_id"]==$this->filter && ($this->search === '' || strpos($row["filename"],$this->search) !== FALSE)) {
      parent::add($row);
    }
  }
}


abstract class Mapper {
  private $db = NULL;
  private $table = NULL;
  function __construct(Database $db, $table) {
    $this->db = $db;
    $this->table = $table;
  }
  protected function fillModel($module){
    foreach($this->db->query("SELECT * FROM $this->table") as $row) {
      if ($row) $module->add($row);
    }
    return $module;
  }
  static public function fabric(Database $db, $name) {
    switch ($name) {
      case 'discipline':
        return new DisciplineMapper($db);
        break;
      case 'material':
        return new MaterialsMapper($db);
        break;
      default:
        throw new Exception("Invalid name: $name", 1);
        break;
    }
  }
  abstract public function read(array $zaproc);
}

class DisciplineMapper extends Mapper{
  /**
  * @var Disciplines The discipline to create
  */
  private $discipline;
  function __construct($db) {
    parent::__construct($db, "discipline");
  }
  /**
  * @return Disciplines a discipline table module object
  */
  public function read(array $zapoc) {
    return $this->fillModel(new Disciplines);
  }
}

class MaterialsMapper extends Mapper{
  /**
  * @var $materials Materials
  */
  private $materials;
  function __construct($db) {
    parent::__construct($db, "material");
  }
  /**
  * @return Disciplines : a discipline table module object
  */
  public function read(array $zaproc) {
    $filter = $zaproc["discipline"];
    $search = (isset($zaproc["search"])) ? $zaproc["search"] : '';
    return $this->fillModel(new Materials($filter, $search));
  }
}

/** Client interface code
* \msc
*    LayerCommunication,StudentAdapter,Mapper,TableModule,Database;
*    |||;
*    LayerCommunication=>StudentAdapter [label="MogetOtvetchat::otvetchat($zaproc)"];
*    StudentAdapter=>Mapper [label="read()"];
*    Mapper->TableModule [label="instanciate"];
*    TableModule note TableModule [label="instanciation"];
*    Mapper=>Mapper [label="fillModel"];
*    Mapper->Database [label="SELECT * FROM ..."];
*    Mapper<-Database [label="SQL query results"];
*    Mapper=>TableModule [label="add(array $row)"];
*    ...;
*    Mapper=>TableModule [label="add(array $row)"];
*    Mapper=>TableModule [label="view()"];
*    TableModule>>Mapper [label="return string"];
*    Mapper>>StudentAdapter;
*    StudentAdapter>>LayerCommunication;
*  \endmsc
**/
class StudentAdapter extends Model {
  private $mapper = NULL;
  function __construct($name, Mapper $mapper) {
    $this->setName($name);
    $this->mapper = $mapper;
  }
  public function otvetchat(array $zaproc) {
    if (isset($zaproc["student"])  && $zaproc["student"] === $this->getName()) {
      return $this->getValue($zaproc);
    } else {
      return $this->getNext()->otvetchat($zaproc);
    }
  }
  function getValue(array $zaproc) {
     return $this->mapper->read($zaproc)->view();
  }
  static function fabric(Database $db, $name) {
    return new StudentAdapter($name, Mapper::fabric($db, $name));
  }
}
?>
