<?php

interface IHasName
{

  /**
   * @return string name
   */
  public
  function getName(): string;

  /**
   * @return string default name
   */
  public
  function getDefaultName(): string;

}
