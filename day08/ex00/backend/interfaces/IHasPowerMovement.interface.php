<?php

require_once __DIR__ . '/IHasPower.interface.php';
require_once __DIR__ . '/IHasMovement.interface.php';

interface IHasPowerMovement extends IHasPower, IHasMovement
{

  /**
   * @return bool if transfer was successful
   */
  public
  function powerToMovement(): bool;

}
