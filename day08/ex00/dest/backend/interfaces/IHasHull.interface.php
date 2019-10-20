<?php

interface IHasHull
{

  /**
   * @return int default hull points
   */
  public
  function getDefaultHull(): int;

  /**
   * @return int current hull points
   */
  public
  function getCurrentHull(): int;

  public
  function resetHull(): void;

}
