<?php

/// Represents a class that permits the visualization of a TabularData Element
interface Viewer {
  public function view(Request $request);
}

/** visualization under the form of a list of text fields
* used to realize the bridge pattern
* \msc
*  hspace="2";
*  Main,Request,Viewer,ListViewer;
*  Main->ListViewer [label="instanciate"];
*  Main->Request [label="instantiate with the given viewer"];
*  Request=>Viewer [label="view()\n(call to the generic interface)"]
*  Viewer=>ListViewer [label="view()\n(call to the real implementation)"];
* \endmsc
*/
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


/** Controller of the whole UI
* \msc
*  UI,Main,Request,ListViewer,Server;
*  UI=>Main [label="do_request()"];
*  Main->Request;
*  Request abox Request [label="instanciation"];
*  Main=>Request [label="read()"];
*  Request->Server [label="API HTTP request"];
*  Request<-Server [label="json response"];
*  Request->ListViewer;
*  ListViewer abox ListViewer [label="instanciation"];
*  Request=>ListViewer [label="view(...)"];
*  ListViewer=>ListViewer [label="viewElem(...)"];
*  UI<-ListViewer [label="HTML element creation"];
*  Request<<ListViewer [label="return"];
*  Main<<Request [label="return"];
*  |||;
*  ...;
*  |||;
*  UI->ListViewer [label="HTML event"];
*  ListViewer=>ListViewer [label="event handler"];
*  Request<=ListViewer [label="update(...)"];
*  Request->Server [label="API HTTP update"];
* \endmsc
*/
class Main {
  public function __construct() {}

  /**
  * Create a Request and an associated ListViewer object, and display the result of the view
  */
  public function do_request($apiname) {}
}

/// Main controller of the application
var main = new Main;

?>
