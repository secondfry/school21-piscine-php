<?php

require_once __DIR__ . '/../interfaces/ITurnBased.interface.php';
require_once __DIR__ . '/../interfaces/IHasHull.interface.php';
require_once __DIR__ . '/../interfaces/IHasShields.interface.php';
require_once __DIR__ . '/../interfaces/IHasPower.interface.php';
require_once __DIR__ . '/../interfaces/IHasMovement.interface.php';
require_once __DIR__ . '/../interfaces/IHasSize.interface.php';
require_once __DIR__ . '/../interfaces/IHasName.interface.php';

abstract
class AShip
  implements JsonSerializable, ITurnBased, IHasHull, IHasShields, IHasPower,
             IHasMovement, IHasSize, IHasName
{

  private string $_name     = '';
  private int    $_width    = 0;
  private int    $_height   = 0;
  private int    $_rotation = 0;

  private int $_powerCur  = 0;
  private int $_powerCore = 0;

  private int $_shieldsCore = 0;
  private int $_shieldsCur  = 0;

  private int $_hullCore = 0;
  private int $_hullCur  = 0;

  private bool $_turnStartedStationary = true;
  private int  $_handling              = 0;
  private int  $_movementCore          = 0;
  private int  $_movementCur           = 0;

  private AWeapon $_weapon;

  public
  function __construct()
  {
    $this -> _hullCore     = $this -> getDefaultHull();
    $this -> _shieldsCore  = $this -> getDefaultShields();
    $this -> _powerCore    = $this -> getDefaultPower();
    $this -> _handling     = $this -> getDefaultHandling();
    $this -> _movementCore = $this -> getDefaultMovement();
    $this -> _width        = $this -> getDefaultWidth();
    $this -> _height       = $this -> getDefaultHeight();
    $this -> reset();
  }

  public
  function reset(): void
  {
    $this -> _powerCur    = $this -> _powerCore;
    $this -> _shieldsCur  = $this -> _shieldsCore;
    $this -> _hullCur     = $this -> _hullCore;
    $this -> _movementCur = $this -> _movementCore;
  }

  public
  function getName(): string
  {
    return $this -> _name;
  }

  public
  function resetPower(): void
  {
    $this -> _powerCur = $this -> _powerCore;
  }

  public
  function powerToShields(): bool
  {
    if ($this -> _powerCur < 1) {
      return false;
    }

    $this -> _powerCur   -= 1;
    $this -> _shieldsCur += 1;
    return true;
  }

  public
  function powerToMovement(): bool
  {
    if ($this -> _powerCur < 1) {
      return false;
    }

    $this -> _powerCur    -= 1;
    $this -> _movementCur += $this -> _roll();
    return true;
  }

  private
  function _roll(): int
  {
    return rand(1, 6);
  }

  public
  function powerToWeapons(): bool
  {
    if ($this -> _powerCur < 1) {
      return false;
    }

    $this -> _powerCur    -= 1;
    $this -> _movementCur += $this -> _roll();
    return true;
  }

  public
  function getCurrentHull(): int
  {
    return $this -> _hullCur;
  }

  public
  function getCurrentShields(): int
  {
    return $this -> _shieldsCur;
  }

  public
  function getCurrentPower(): int
  {
    return $this -> _powerCur;
  }

  public
  function getHandling(): int
  {
    return $this -> _handling;
  }

  public
  function getCurrentMomement(): int
  {
    return $this -> _movementCur;
  }

  public
  function getWidth(): int
  {
    return $this -> _width;
  }

  public
  function getHeight(): int
  {
    return $this -> _height;
  }

  public
  function jsonSerialize()
  {
    return [
      'name'     => $this -> _name,
      'width'    => $this -> _width,
      'height'   => $this -> _height,
      'rotation' => $this -> _rotation,
      'power'    => $this -> _powerCur,
      'hull'     => $this -> _hullCur,
      'shields'  => $this -> _shieldsCur,
      'handling' => $this -> _handling,
      'movement' => $this -> _movementCur,
      'weapon'   => $this -> _weapon,
    ];
  }

}
