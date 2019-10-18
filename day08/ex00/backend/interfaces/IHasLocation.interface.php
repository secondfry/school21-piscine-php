<?php

interface IHasLocation
{

  /**
   * @return int x coordinate
   */
  public
  function getX(): int;

  /**
   * @return int y coordinate
   */
  public
  function getY(): int;

  /**
   * @param int $x x coordinate
   * @param int $y y coordinate
   * @return bool if entity is located on requested coordinates
   */
  public
  function isOnXY(
    int $x,
    int $y
  ): bool;

}
