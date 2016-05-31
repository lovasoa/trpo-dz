<?php
interface MogetOtvetchat {
  public function otvetchat(array $zaproc);
  public function setNext(MogetOtvetchat $next);
}
?>
