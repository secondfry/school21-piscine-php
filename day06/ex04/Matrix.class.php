<?php

class Matrix {

  public static bool $verbose = false;

  public static function doc(): string {
    return file_get_contents(get_class() . '.doc.txt');
  }

  public const IDENTITY = 1;
  public const SCALE = 2;
  public const RX = 3;
  public const RY = 4;
  public const RZ = 5;
  public const TRANSLATION = 6;
  public const PROJECTION = 7;

  private array $_data;
  private int $type = 0;

  public function __construct(array $input) {
    $this->_type = $input['preset'];
    switch($input['preset']) {

      case Matrix::IDENTITY:
        $this->_initIdentity();
        break;

      case Matrix::TRANSLATION:
        $this->_initTranslation($input['vtc']);
        break;

      case Matrix::SCALE:
        $this->_initScale($input['scale']);
        break;

      case Matrix::RX:
        $this->_initRX($input['angle']);
        break;

      case Matrix::RY:
        $this->_initRY($input['angle']);
        break;

      case Matrix::RZ:
        $this->_initRZ($input['angle']);
        break;

      case Matrix::PROJECTION:
        $this->_initProjection(
          $input['fov'],
          $input['ratio'],
          $input['near'],
          $input['far']
        );
        break;

      default:
        $this->_initOther();
        break;

    }
    $this->_verboseConstruct();
    return $this;
  }

  public function __destruct() {
    $this->_verboseDestruct();
  }

  private function _initIdentity(): Matrix {
    $this->_data = [
      [1, 0, 0, 0],
      [0, 1, 0, 0],
      [0, 0, 1, 0],
      [0, 0, 0, 1]
    ];
    return $this;
  }

  private function _initTranslation(Vector $trans): Matrix {
    $tx = $trans->getX();
    $ty = $trans->getY();
    $tz = $trans->getZ();
    $this->_data = [
      [1, 0, 0, $tx],
      [0, 1, 0, $ty],
      [0, 0, 1, $tz],
      [0, 0, 0,   1]
    ];
    return $this;
  }

  private function _initScale(float $k): Matrix {
    $this->_data = [
      [$k,  0,  0, 0],
      [ 0, $k,  0, 0],
      [ 0,  0, $k, 0],
      [ 0,  0,  0, 1]
    ];
    return $this;
  }

  private function _initRX(float $p): Matrix {
    $this->_data = [
      [1,       0,        0, 0],
      [0, cos($p), -sin($p), 0],
      [0, sin($p),  cos($p), 0],
      [0,       0,        0, 1]
    ];
    return $this;
  }

  private function _initRY(float $p): Matrix {
    $this->_data = [
      [ cos($p), 0, sin($p), 0],
      [       0, 1,       0, 0],
      [-sin($p), 0, cos($p), 0],
      [       0, 0,       0, 1]
    ];
    return $this;
  }

  private function _initRZ(float $p): Matrix {
    $this->_data = [
      [cos($p), -sin($p), 0, 0],
      [sin($p),  cos($p), 0, 0],
      [      0,        0, 1, 0],
      [      0,        0, 0, 1]
    ];
    return $this;
  }

  private function _initProjection(
    float $fov,
    float $ratio,
    float $near,
    float $far
  ) {
    $top = tan($fov / 2) * $near;
    $bottom = -$top;
    $right = $top * $ratio;
    $left = -$right;

    $width = $right - $left;
    $height = $top - $bottom;

    $s = 1 / tan($fov / 2 * pi() / 180);

    $this->_data = [
      [0, 0, 0, 0],
      [0, 0, 0, 0],
      [0, 0, 0, 0],
      [0, 0, 0, 0]
    ];

    // https://www.scratchapixel.com/lessons/3d-basic-rendering/perspective-and-orthographic-projection-matrix/building-basic-perspective-projection-matrix
    // https://www.scratchapixel.com/lessons/3d-basic-rendering/perspective-and-orthographic-projection-matrix/opengl-perspective-projection-matrix

    // $this->_data[0][0] = 2 * $near / $width;
    $this->_data[0][0] = $s / $ratio;

    // This is a zero:
    // $this->_data[0][2] = ($right + $left) / ($right - $left);
    
    // $this->_data[1][1] = 2 * $near / $height;
    $this->_data[1][1] = $s;
    
    // This is a zero too:
    // $this->_data[1][2] = ($top + $bottom) / ($top - $bottom);

    $this->_data[2][2] = -1 * ($far + $near) / ($far - $near);

    $this->_data[2][3] = -1 * 2 * $far * $near / ($far - $near);

    $this->_data[3][2] = -1;

    return $this;
  }

  private function _initOther(): Matrix {
    $this->_data = [
      [0, 0, 0, 0],
      [0, 0, 0, 0],
      [0, 0, 0, 0],
      [0, 0, 0, 1]
    ];
    return $this;
  }

  public function mult( Matrix $that ): Matrix {
    $ret = clone $this;
    $i = 0;
    while ($i < 4) {
      $j = 0;
      while ($j < 4) {
        $ret->_data[$i][$j] = 0;
        $k = 0;
        while ($k < 4) {
          $ret->_data[$i][$j] += $this->_data[$i][$k] * $that->_data[$k][$j];
          $k++;
        }
        $j++;
      }
      $i++;
    }
    return $ret;
  }

  public function transformVertex( Vertex $v ): Vertex {
    $ret = clone $v;
    $ret->setX(
      $this->_data[0][0] * $v->getX()
      + $this->_data[0][1] * $v->getY()
      + $this->_data[0][2] * $v->getZ()
      + $this->_data[0][3] * $v->getW()
    );
    $ret->setY(
      $this->_data[1][0] * $v->getX()
      + $this->_data[1][1] * $v->getY()
      + $this->_data[1][2] * $v->getZ()
      + $this->_data[1][3] * $v->getW()
    );
    $ret->setZ(
      $this->_data[2][0] * $v->getX()
      + $this->_data[2][1] * $v->getY()
      + $this->_data[2][2] * $v->getZ()
      + $this->_data[2][3] * $v->getW()
    );
    $ret->setW(
      $this->_data[3][0] * $v->getX()
      + $this->_data[3][1] * $v->getY()
      + $this->_data[3][2] * $v->getZ()
      + $this->_data[3][3] * $v->getW()
    );
    return $ret;
  }

  public function mirror(): Matrix {
    $ret = clone $this;
    $i = 0;
    while ($i < 4) {
      $j = 0;
      while ($j < 4) {
        $ret->_data[$i][$j] = $this->_data[$j][$i];
        $j++;
      }
      $i++;
    }
    return $ret;
  }

  private function _verboseConstruct(): void {
    if (!Matrix::$verbose) {
      return;
    }

    switch ($this->_type) {

      case Matrix::IDENTITY:
        echo 'Matrix IDENTITY instance constructed' . "\n";
        break;

      case Matrix::TRANSLATION:
        echo 'Matrix TRANSLATION preset instance constructed' . "\n";
        break;

      case Matrix::SCALE:
        echo 'Matrix SCALE preset instance constructed' . "\n";
        break;

      case Matrix::RX:
        echo 'Matrix Ox ROTATION preset instance constructed' . "\n";
        break;

      case Matrix::RY:
        echo 'Matrix Oy ROTATION preset instance constructed' . "\n";
        break;

      case Matrix::RZ:
        echo 'Matrix Oz ROTATION preset instance constructed' . "\n";
        break;

      case Matrix::PROJECTION:
        echo 'Matrix PROJECTION preset instance constructed' . "\n";
        break;

    }
  }

  private function _verboseDestruct(): void {
    if (!Matrix::$verbose) {
      return;
    }

    echo 'Matrix instance destructed' . "\n";
  }

  public function __toString(): string {
    $line = ['x', 'y', 'z', 'w'];
    $ret = 'M | vtcX | vtcY | vtcZ | vtxO' . "\n";
    $ret .= '-----------------------------' . "\n";
    $i = 0;
    while ($i < 4) {
      $ret .= $line[$i] . ' ';
      $j = 0;
      while ($j < 4) {
        $ret .= sprintf('| %.02f', $this->_data[$i][$j]);
        if ($j != 3) {
          $ret .= ' ';
        }
        $j++;
      }
      if ($i != 3) {
        $ret .= "\n";
      }
      $i++;
    }
    return $ret;
  }

}
