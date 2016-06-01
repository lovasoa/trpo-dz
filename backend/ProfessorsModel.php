<?php
class ProfessorsModel extends BasicModel {
  public function __construct($db) {
    parent::__construct($db, "professor");
  }
}
