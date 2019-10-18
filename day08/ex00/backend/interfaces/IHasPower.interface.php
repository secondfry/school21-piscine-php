<?php

interface IHasPower
{

  public
  function getDefaultPower(): int;

  public
  function getCurrentPower(): int;

}