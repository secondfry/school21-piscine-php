<?php

interface IHasShields
{

  /**
   * @return int default shield points
   */
  public
  function getDefaultShields(): int;

  /**
   * @return int current shield points
   */
  public
  function getCurrentShields(): int;

  public
  function resetShields(): void;

}
