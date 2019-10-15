<?php

class Color {

  static /* bool */ $verbose = false;

  static function doc(): string {
    return file_get_contents('Color.doc.txt');
  }

  public /* int */ $red = 0;
  public /* int */ $green = 0;
  public /* int */ $blue = 0;

  public function __construct(array $input) {
    $keys = array_keys($input);
    if (in_array('rgb', $keys)) {
      $this->setRGB($input['rgb']);
      $this->verboseConstruct();
      return $this;
    }

    $this->set($input);
    $this->verboseConstruct();
    return $this;
  }

  public function __destruct() {
    $this->verboseDestruct();
  }

  public function setRGB($rgb): Color {
    $this->red    = ($rgb & (0b111111110000000000000000)) >> 16;
    $this->green  = ($rgb & (0b000000001111111100000000)) >> 8;
    $this->blue   =  $rgb & (0b000000000000000011111111);
    return $this;
  }

  public function set(array $input): Color {
    $keys = array_keys($input);
    if (in_array('red', $keys)) {
      $this->setRed($input['red']);
    }
    if (in_array('green', $keys)) {
      $this->setGreen($input['green']);
    }
    if (in_array('blue', $keys)) {
      $this->setBlue($input['blue']);
    }
    return $this;
  }

  public function setRed($red): Color {
    $this->setColorKeyed('red', $red);
    return $this;
  }

  public function setGreen($green): Color {
    $this->setColorKeyed('green', $green);
    return $this;
  }

  public function setBlue($blue): Color {
    $this->setColorKeyed('blue', $blue);
    return $this;
  }

  private function setColorKeyed($key, $value): Color {
    $this->$key = $value & 0b11111111;
    return $this;
  }

  public function add(Color $that): Color {
    return new Color([
      'red' => $this->red + $that->red,
      'green' => $this->green + $that->green,
      'blue' => $this->blue + $that->blue,
    ]);
  }

  public function sub(Color $that): Color {
    return new Color([
      'red' => $this->red - $that->red,
      'green' => $this->green - $that->green,
      'blue' => $this->blue - $that->blue,
    ]);
  }

  public function mult(float $mult): Color {
    return new Color([
      'red' => $this->red * $mult,
      'green' => $this->green * $mult,
      'blue' => $this->blue * $mult,
    ]);
  }

  private function verboseConstruct(): void {
    if (Color::$verbose) {
      echo $this->__toString() . ' constructed.' . "\n";
    }
  }

  private function verboseDestruct(): void {
    if (Color::$verbose) {
      echo $this->__toString() . ' destructed.' . "\n";
    }
  }

  public function __toString(): string {
    return sprintf(
      'Color( red: %3d, green: %3d, blue: %3d )',
      $this->red,
      $this->green,
      $this->blue
    );
  }

}
