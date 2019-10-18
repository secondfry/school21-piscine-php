<?php

interface IHasShields
{

  public
  function getDefaultShields(): int;

  public
  function getCurrentShields(): int;

}
