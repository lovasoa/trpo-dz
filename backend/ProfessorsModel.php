<?php
/**
* Represent a list of professors. Implements MogetOtvetchat, and thus can answer
* queries about professors
* \msc
*    professor,next;
*    |||;
*    professor box professor [label="ProfessorsModel object"];
*  \endmsc
*/
class ProfessorsModel extends BasicModel {
  public function __construct($db) {
    parent::__construct($db, "professor");
  }
}
