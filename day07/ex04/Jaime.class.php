<?php

class Jaime extends Lannister {

  public function sleepWith(object $person): void {
    if ($person instanceof Cersei) {
      echo 'With pleasure, but only in a tower in Winterfell, then.' . "\n";
      return;
    }

    parent::sleepWith($person);
  }

}
