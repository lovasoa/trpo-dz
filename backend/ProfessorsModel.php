<?php
/**
* Represent a list of professors. Implements MogetOtvetchat, and thus can answer
* queries about professors.
* Part of the chain of responsability.
* \msc
*    LayerCommunication,professor,next;
*    |||;
*    professor box professor [label="ProfessorsModel object"], next box next [label="ProfessorsModel object"];
*    LayerCommunication=>professor [label="otvechat($professor_zaproc)"];
*    professor=>professor [label="getValue($professor_zaproc)", url="\ref BasicModel"];
*    LayerCommunication<<professor [label="return string"];
*    |||;
*    LayerCommunication=>professor [label="otvechat($drugoï_zaproc)"];
*    professor=>next [label="next->otvechat($drugoï_zaproc)"];
*    next=>next [label="getValue($drugoi_zaproc)"];
*    professor<<next [label="return string"];
*    LayerCommunication<<professor [label="return string"];
*  \endmsc
*/
class ProfessorsModel extends BasicModel {
  public function __construct($db) {
    parent::__construct($db, "professor");
  }
}
