<?php

interface IHasSize
{

  public
  function getDefaultWidth(): int;

  public
  function getWidth(): int;

  public
  function getDefaultHeight(): int;

  public
  function getHeight(): int;

}