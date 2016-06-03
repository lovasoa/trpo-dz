<?php
/**
* Interface that modules entering the chain of responsability have to implement.
* Defines a class that can answer to an HTTP request.
*/
interface MogetOtvetchat {
/**
* Answer the given query. If the current class cannot answer the query,
* it will be transmitted to the next one in the chain.
* @param query query parameters
* @return A character string that will be transmitted to the client
*/
  public function otvetchat(array $zaproc);

/**
* Define the next element in the chain
* @return The object itself
*/
  public function setNext(MogetOtvetchat $next);
}
?>
