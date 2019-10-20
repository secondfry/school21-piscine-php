<?php

require_once __DIR__ . '/IHasPower.interface.php';
require_once __DIR__ . '/IHasShields.interface.php';

interface IHasPowerShields extends IHasPower, IHasShields
{

  /**
   * @return bool if transfer was successful
   */
  public
  function powerToShields(): bool;

}
