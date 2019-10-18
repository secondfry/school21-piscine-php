<?php

interface IHasHull
{

  public
  function getDefaultHull(): int;

  public
  function getCurrentHull(): int;

}
