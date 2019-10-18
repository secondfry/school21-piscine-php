<?php

interface IHasSize
{

  /**
   * @return int default width
   */
  public
  function getDefaultWidth(): int;

  /**
   * @return int current width
   */
  public
  function getWidth(): int;

  /**
   * @return int default height
   */
  public
  function getDefaultHeight(): int;

  /**
   * @return int current height
   */
  public
  function getHeight(): int;

}
