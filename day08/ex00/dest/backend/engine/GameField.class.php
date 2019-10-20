<?php

class GameField
{

  private int $_width  = 20;
  private int $_height = 20;

  /**
   * @return int
   */
  public
  function getWidth(): int
  {
    return $this -> _width;
  }

  /**
   * @return int
   */
  public
  function getHeight(): int
  {
    return $this -> _height;
  }

}
