<?php

require_once __DIR__ . '/../interfaces/ITurnBased.interface.php';

abstract
class AWeapon implements JsonSerializable, ITurnBased
{

  private int  $_chargeCore = 0;
  private int  $_chargeCur  = 0;
  private bool $_hasShot    = false;

  public
  function increasePower(): bool
  {
    $this -> _chargeCur += 1;
    return true;
  }

  public
  function getHasShot(): bool
  {
    return $this -> _hasShot;
  }

  public
  function jsonSerialize()
  {
    return [
      'dice'    => $this -> _chargeCore,
      'hasShot' => $this -> _hasShot,
    ];
  }

  public
  function reset(): void
  {
    $this -> _chargeCur = $this -> _chargeCore;
    $this -> _hasShot   = false;
  }

}
