<?php

interface IHasMovement
{

  public
  function getDefaultHandling(): int;

  public
  function getHandling(): int;

  public
  function getDefaultMovement(): int;

  public
  function getCurrentMomement(): int;

}