<?php

require_once __DIR__ . '/../interfaces/ITurnBased.interface.php';
require_once __DIR__ . '/../interfaces/IHasHull.interface.php';
require_once __DIR__ . '/../interfaces/IHasPowerShields.interface.php';
require_once __DIR__ . '/../interfaces/IHasMovement.interface.php';
require_once __DIR__ . '/../interfaces/IHasLocationSize.interface.php';
require_once __DIR__ . '/../interfaces/IHasName.interface.php';

abstract
class AShip
  implements JsonSerializable, ITurnBased, IHasHull, IHasPowerShields,
             IHasMovement, IHasLocationSize, IHasName
{

  private int $_id = 0;

  private string $_name     = '';
  private int    $_width    = 0;
  private int    $_height   = 0;
  private int    $_rotation = 0;

  const FLAT     = 1;
  const VERTICAL = 2;

  private int $_locationX = 0;
  private int $_locationY = 0;

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

  private bool $_hasMoved    = false;
  private bool $_hasAttacked = false;

  private int $_playerID = 0;

  protected AWeapon $_weapon;

  public
  function __construct(
    int $id,
    int $playerID,
    int $x,
    int $y
  ) {
    $this -> _hullCore     = $this -> getDefaultHull();
    $this -> _shieldsCore  = $this -> getDefaultShields();
    $this -> _powerCore    = $this -> getDefaultPower();
    $this -> _handling     = $this -> getDefaultHandling();
    $this -> _movementCore = $this -> getDefaultMovement();
    $this -> _width        = $this -> getDefaultWidth();
    $this -> _height       = $this -> getDefaultHeight();
    $this -> _name         = $this -> getDefaultName();
    $this -> _locationX    = $x;
    $this -> _locationY    = $y;
    $this -> _playerID     = $playerID;
    $this -> _id           = $id;
    $this -> _rotation     = AShip::FLAT;
    $this -> _hullCur      = $this -> _hullCore;
    $this -> reset();
  }

  public
  function reset(): void
  {
    $this -> resetPower();
    $this -> resetShields();
    $this -> resetMovement();

    $this -> _hasMoved    = false;
    $this -> _hasAttacked = false;
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
  function resetShields(): void
  {
    $this -> _shieldsCur = $this -> _shieldsCore;
  }

  public
  function resetHull(): void
  {
    $this -> _hullCur = $this -> _hullCore;
  }

  public
  function resetMovement(): void
  {
    if ($this -> _movementCur === $this -> _movementCore) {
      $this -> _turnStartedStationary = true;
    } else {
      $this -> _turnStartedStationary = false;
    }
    $this -> _movementCur = $this -> _movementCore;
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

  public
  function getX(): int
  {
    return $this -> _locationX;
  }

  public
  function getY(): int
  {
    return $this -> _locationY;
  }

  public
  function isOnXY(
    int $x,
    int $y
  ): bool {
    $bounds = $this -> getBounds();
    if (
      $x < $bounds['xMin']
      || $x > $bounds['xMax']
      || $y < $bounds['yMin']
      || $y > $bounds['yMax']
    ) {
      return false;
    }

    return true;
  }

  public
  function getBounds(): array
  {
    return [
      'xMin' => $this -> _locationX,
      'xMax' => $this -> _locationX + $this -> _width - 1,
      'yMin' => $this -> _locationY,
      'yMax' => $this -> _locationY + $this -> _height - 1
    ];
  }

  public
  function getPlayerID(): int
  {
    return $this -> _playerID;
  }

  public
  function getID(): int
  {
    return $this -> _id;
  }

  public
  function canMoveTo(
    int $x,
    int $y,
    GameField $field
  ): bool {
    if ($this -> _hasMoved) {
      return false;
    }

    switch ($this -> _rotation) {
      case AShip::FLAT:
        if (abs($x - $this -> _locationX) < $this -> _handling) {
          return false;
        }
        break;
      case AShip::VERTICAL:
        if (abs($y - $this -> _locationY) < $this -> _handling) {
          return false;
        }
        break;
    }

    $movementRequired =
      abs($x - $this -> _locationX + $y - $this -> _locationY);
    if ($movementRequired > $this -> _movementCur) {
      return false;
    }

    if ($x > $field -> getWidth() - $this -> getWidth()) {
      return false;
    }

    if ($y > $field -> getHeight() - $this -> getHeight()) {
      return false;
    }

    return true;
  }

  public
  function moveTo(
    int $x,
    int $y,
    GameField $field
  ): bool {
    if (!$this -> canMoveTo($x, $y, $field)) {
      return false;
    }

    $this -> _locationX = $x;
    $this -> _locationY = $y;

    $this -> _hasMoved = true;

    return true;
  }

  public
  function canAttack(
    int $x,
    int $y
  ): bool {
    if ($this -> _hasAttacked) {
      return false;
    }

    if (!$this -> _weapon) {
      return false;
    }

    if ($this -> isOnXY($x, $y)) {
      return false;
    }

    return true;
  }

  public
  function attackAt(
    int $x,
    int $y,
    AShip $other
  ): bool {
    if (!$this -> canAttack($x, $y)) {
      return false;
    }

    $other -> receiveDamage(1);

    $this -> _hasAttacked = true;
    return true;
  }

  public
  function receiveDamage(
    int $damage
  ): void {
    $this -> _shieldsCur -= $damage;

    if ($this -> _shieldsCur < 0) {
      $this -> _hullCur    += $this -> _shieldsCur;
      $this -> _shieldsCur = 0;
    }

    if ($this -> _hullCur < 0) {
      // TODO remove ship
      $this -> _hullCur = 0;
    }
  }

  public
  function hasActions(): bool
  {
    return !$this -> _hasAttacked || !$this -> _hasMoved;
  }

}
