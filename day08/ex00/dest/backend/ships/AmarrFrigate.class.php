<?php

require_once 'AShip.class.php';
require_once __DIR__ . '/../weapons/SmallPulseLaser.class.php';

class AmarrFrigate extends AShip
{

  public
  function __construct(
    $id,
    $pid,
    $x,
    $y
  ) {
    $this -> _weapon = new SmallPulseLaser();
    parent ::__construct($id, $pid, $x, $y);
  }


  public
  function getDefaultName(): string
  {
    return 'Honorable Duty';
  }

  public
  function getDefaultHull(): int
  {
    return 5;
  }

  public
  function getDefaultHandling(): int
  {
    return 4;
  }

  public
  function getDefaultMovement(): int
  {
    return 15;
  }

  public
  function getDefaultPower(): int
  {
    return 10;
  }

  public
  function getDefaultShields(): int
  {
    return 0;
  }

  public
  function getDefaultWidth(): int
  {
    return 4;
  }

  public
  function getDefaultHeight(): int
  {
    return 1;
  }

}
