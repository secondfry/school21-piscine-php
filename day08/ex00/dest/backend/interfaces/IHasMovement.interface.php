<?php

interface IHasMovement
{

  /**
   * @return int default handling value
   */
  public
  function getDefaultHandling(): int;

  /**
   * @return int handling value
   */
  public
  function getHandling(): int;

  /**
   * @return int default movement points
   */
  public
  function getDefaultMovement(): int;

  /**
   * @return int current movement points
   */
  public
  function getCurrentMomement(): int;

  public
  function resetMovement(): void;

  /**
   * @param int $x x coordinate
   * @param int $y y coordinate
   * @param GameField $field game field
   * @return bool if entity can move to supplied coordinates
   */
  public
  function canMoveTo(
    int $x,
    int $y,
    GameField $field
  ): bool;

}
