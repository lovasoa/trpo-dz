<?php

/// Represents a class that permits the visualization of a TabularData Element
interface Viewer {
  public function view(Request $request);
}

/// visualization under the form of a list of text fields
class ListViewer implements Viewer{
  public function view(Request $request) {}

  private function viewElem(Request $r, array $headers, array $data) {}

  private function viewInput(Request $r, array $header, $data) {}
}

/// Represents the data and headers of a table
abstract class TabularData {
  public function __construct(Viewer $viewer) {}
}

/// Represents a table of data, linked to the server
class Request extends TabularData {
  public function __construct(Viewer $viewer, string $apiname, HTMLElement $viewEl) {}
  public function read() {}
  public function update(array $data, string $column, $value, array $options) {}
  /// Ask for the creation of a new element in the current table
  public function create() {}
  public function delete(array $data) {}
  public function fetch($params, $options) {}
  public function view($data) {}
}


/// Controller of the whole UI
class Main {
  public function __construct() {}

  public function do_request($apiname) {}
}

/// Main controller of the application
var main = new Main;

?>
