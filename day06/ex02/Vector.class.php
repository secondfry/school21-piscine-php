<?php

class Vector {

  static bool $verbose = false;

  static function doc(): string {
    return file_get_contents(get_class() . '.doc.txt');
  }

  private float $_x = 0;
  private float $_y = 0;
  private float $_z = 0;
  private float $_w = 0;

  public function __construct(array $input) {
    $keys = array_keys($input);

    $dest = $input['dest'];
    $orig = $input['orig'] ?? new Vertex(['x' => 0, 'y' => 0, 'z' => 0, 'w' => 1]);

    $this->_x = $dest->getX() - $orig->getX();
    $this->_y = $dest->getY() - $orig->getY();
    $this->_z = $dest->getZ() - $orig->getZ();
    $this->_w = $dest->getW() - $orig->getW();

    $this->_verboseConstruct();
    return $this;
  }

  public function __destruct() {
    $this->_verboseDestruct();
  }

  public function getX(): float {
    return $this->_x;
  }

  public function getY(): float {
    return $this->_y;
  }

  public function getZ(): float {
    return $this->_z;
  }

  public function getW(): float {
    return $this->_w;
  }

  public function magnitude(): float {
    return sqrt(
      pow($this->_x, 2)
      + pow($this->_y, 2)
      + pow($this->_z, 2)
      + pow($this->_w, 2)
    );
  }

  public function normalize(): Vector {
    $ret = clone $this;
    $mag = $this->magnitude();
    $ret->_x = $this->_x / $mag;
    $ret->_y = $this->_y / $mag;
    $ret->_z = $this->_z / $mag;
    $ret->_w = $this->_w / $mag;
    return $ret;
  }

  public function add( Vector $that ): Vector {
    $ret = clone $this;
    $ret->_x += $that->_x;
    $ret->_y += $that->_y;
    $ret->_z += $that->_z;
    $ret->_w += $that->_w;
    return $ret;
  }

  public function sub( Vector $that ): Vector {
    $ret = clone $this;
    $ret->_x -= $that->_x;
    $ret->_y -= $that->_y;
    $ret->_z -= $that->_z;
    $ret->_w -= $that->_w;
    return $ret;
  }

  public function opposite(): Vector {
    $ret = clone $this;
    $ret->_x *= -1;
    $ret->_y *= -1;
    $ret->_z *= -1;
    $ret->_w *= -1;
    return $ret;
  }

  public function scalarProduct( float $k ): Vector {
    $ret = clone $this;
    $ret->_x *= $k;
    $ret->_y *= $k;
    $ret->_z *= $k;
    $ret->_w *= $k;
    return $ret;
  }

  public function dotProduct( Vector $that ): float {
    return
      $this->_x * $that->_x
      + $this->_y * $that->_y
      + $this->_z * $that->_z
      + $this->_w * $that->_w;
  }

  public function crossProduct ( Vector $that ): Vector {
    $ret = clone $this;
    $ret->_x =   $this->_y * $that->_z - $that->_y * $this->_z;
    $ret->_y = -($this->_x * $that->_z - $that->_x * $this->_z);
    $ret->_z =   $this->_x * $that->_y - $that->_x * $this->_y;
    return $ret;
  }

  public function cos( Vector $that ): float {
    return $this->dotProduct($that) / $this->magnitude() / $that->magnitude();
  }

  private function _verboseConstruct(): void {
    if (Vector::$verbose) {
      echo $this->__toString() . ' constructed' . "\n";
    }
  }

  private function _verboseDestruct(): void {
    if (Vector::$verbose) {
      echo $this->__toString() . ' destructed' . "\n";
    }
  }

  public function __toString(): string {
    $ret = sprintf(
      'Vector( x:%.02f, y:%.02f, z:%.02f, w:%.02f',
      $this->_x,
      $this->_y,
      $this->_z,
      $this->_w
    );

    $ret .= ' )';
    return $ret;
  }

}
