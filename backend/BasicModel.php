<?php
require_once("utils.php");

/**
* Represent a the business-logic model of a table in the database
*/
class BasicModel extends Model {
  /**
  * Gateway to the database
  * @var BasicGateway $gateway
  */
  private $gateway = NULL;

  public function __construct(Database $db, $tablename) {
    $this->gateway = new BasicGateway($db, $tablename);
    $this->setName($tablename);
  }
  public function getValue(array $query) {
    $type = $query["type"];
    switch($type) {
      case "list":
        return $this->gateway->getAll();
        break;
      case "update":
        $data = json_decode($query["data"], TRUE);
        $column = $query["column"];
        $value = $query["value"];
        $this->gateway->update($data, $column, $value);
        if ($column === "filename") {
          chdir(dirname(__FILE__)."/materials");
          stream_copy_to_stream(
              fopen("php://input",'r'),
              fopen($value, 'w')
          );
        }
        break;
      case "create":
        $this->gateway->create();
        break;
      case "delete":
        $data = json_decode($query["data"], TRUE);
        $this->gateway->delete($data);
      default:
        throw new Exception("Model: Invalid request type: ".$type, 1);
    }
  }
}
?>
