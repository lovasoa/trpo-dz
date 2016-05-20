<?php
header("Content-type: application/json");
require_once 'utils.php';

try {
  if (isset($_GET["api"])) {
    $apiname = $_GET["api"];
  } else {
    throw new Exception("Missing parameter: api", 1);
  }
  $db = new Database();
  $api = new ClientAPI($db);
  $api->displayData($api->getData($apiname));
} catch (Exception $e) {
  echo json_encode(array(
    "error" => $e->getMessage()
  ));
}
?>
