<?php

abstract class AWeapon implements JsonSerializable, ITurnBased {

  private int _chargeCore = 0;
  private int _chargeCur = 0;
  private bool _hasShot = false;

  public function increasePower(): bool {
    $this->_chargeCur += 1;
    return true;
  }

  public function getHasShot(): bool {
    return $this->_hasShot;
  }

  public function jsonSerialize() {
    return [
      'dice' => $this->_dice,
      'hasShot' => $this->_hasShot,
    ];
  }

  puclic function reset(): void {
    $this->_chargeCur = $this->_chargeCore;
    $this->_hasShot = false;
  }

}
