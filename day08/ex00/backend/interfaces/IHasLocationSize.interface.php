<?php

require_once __DIR__ . '/IHasLocation.interface.php';
require_once __DIR__ . '/IHasSize.interface.php';

interface IHasLocationSize extends IHasLocation, IHasSize
{

  /**
   * @return array with xMin, xMax, yMin, Ymax coordinates for ship
   */
  public
  function getBounds(): array;

}
