<?php

require_once 'Color.class.php';

class Vertex {

  static bool $verbose = false;

  static function doc(): string {
    return file_get_contents(get_class() . '.doc.txt');
  }

  private float $_x = 0;
  private float $_y = 0;
  private float $_z = 0;
  private float $_w = 1.0;
  private Color $_color;

  public function __construct(array $input) {
    $keys = array_keys($input);

    $this->setX(floatval($input['x']));
    $this->setY(floatval($input['y']));
    $this->setZ(floatval($input['z']));

    if (in_array('w', $keys)) {
      $this->setW(floatval($input['w']));
    } else {
      $this->setW(1.0);
    }

    if (in_array('color', $keys)) {
      $this->setColor($input['color']);
    } else {
      $color = new Color(['red' => 255, 'green' => 255, 'blue' => 255]);
      $this->setColor($color);
    }
    
    $this->_verboseConstruct();
    return $this;
  }

  public function __destruct() {
    $this->_verboseDestruct();
  }

  public function getX(): float {
    return $this->_x;
  }

  public function setX(float $val): Vertex {
    $this->_x = $val;
    return $this;
  }

  public function getY(): float {
    return $this->_y;
  }

  public function setY(float $val): Vertex {
    $this->_y = $val;
    return $this;
  }

  public function getZ(): float {
    return $this->_z;
  }

  public function setZ(float $val): Vertex {
    $this->_z = $val;
    return $this;
  }

  public function getW(): float {
    return $this->_w;
  }

  public function setW(float $val): Vertex {
    $this->_w = $val;
    return $this;
  }

  public function getColor(): Color {
    return $this->_color;
  }

  public function setColor(Color $val): Vertex {
    $this->_color = $val;
    return $this;
  }

  private function _verboseConstruct(): void {
    if (Vertex::$verbose) {
      echo $this->__toString() . ' constructed.' . "\n";
    }
  }

  private function _verboseDestruct(): void {
    if (Vertex::$verbose) {
      echo $this->__toString() . ' destructed.' . "\n";
    }
  }

  public function __toString(): string {
    $ret = sprintf(
      'Vertex( x: %.02f, y: %.02f, z: %.02f, w: %.02f',
      $this->_x,
      $this->_y,
      $this->_z,
      $this->_w
    );

    if (Vertex::$verbose) {
      $ret .= ', ' . $this->_color->__toString();
    }

    $ret .= ' )';
    return $ret;
  }

}