<?php

interface IHasPower
{

  /**
   * @return int default power
   */
  public
  function getDefaultPower(): int;

  /**
   * @return int current power
   */
  public
  function getCurrentPower(): int;

  public
  function resetPower(): void;

}
