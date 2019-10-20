<?php

require_once __DIR__ . '/../interfaces/DBStorable.interface.php';

class GameField implements JsonSerializable, DBStorable
{

  private int $_width  = 20;
  private int $_height = 20;

  public static
  function recreate(
    $data
  ) {
    $ret = new GameField();

    $ret -> _width  = $data['width'];
    $ret -> _height = $data['height'];

    return $ret;
  }

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

  public
  function jsonSerialize()
  {
    return [
      'width'  => $this -> _width,
      'height' => $this -> _height,
    ];
  }

}
