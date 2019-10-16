<?php

require_once 'Vertex.class.php';
require_once 'Matrix.class.php';

class Camera {

  public static bool $verbose = false;

  public static function doc(): string {
    return file_get_contents(get_class() . '.doc.txt');
  }

  private Vertex $_origin;
  private Matrix $_orientation;
  private float $_width = 0;
  private float $_height = 0;
  private float $_ratio = 0;
  private float $_fov = 0;
  private float $_near = 0;
  private float $_far = 0;

  private Matrix $_tT;
  private Matrix $_tR;
  private Matrix $_view;
  private Matrix $_projection;

  public function __construct(array $input) {
    $keys = array_keys($input);

    $this->_origin = $input['origin'];
    $this->_orientation = $input['orientation'];

    if (in_array('ratio', $keys)) {
      $this->_ratio = $input['ratio'];
    } else {
      $this->_width = $input['width'];
      $this->_height = $input['height'];
    }

    $this->_fov = $input['fov'];
    $this->_near = $input['near'];
    $this->_far = $input['far'];

    $this->_checkWidthHeight();
    $this->_checkRatio();
    $this->_initMatrixes();

    $this->_verboseConstruct();
    return $this;
  }

  public function __destruct() {
    $this->_verboseDestruct();
  }

  public function _checkWidthHeight() {
    if ($this->_width && $this->_height) {
      return;
    }

    $this->_width = 640;
    $this->_height = $this->_width / $this->_ratio;
  }

  public function _checkRatio() {
    if ($this->_ratio) {
      return;
    }

    $this->_ratio = $this->_width / $this->_height;
  }

  private function _initMatrixes() {
    $this->_initTranslation();
    $this->_initRotation();
    $this->_initView();
    $this->_initProjection();
  }

  private function _initTranslation() {
    $v = new Vector(['dest' => $this->_origin]);
    $this->_tT = new Matrix([
      'preset' => Matrix::TRANSLATION,
      'vtc' => $v->opposite()
    ]);
  }

  private function _initRotation() {
    $this->_tR = $this->_orientation->mirror();
  }

  private function _initView() {
    $this->_view = $this->_tR->mult($this->_tT);
  }

  private function _initProjection() {
    $this->_projection = new Matrix([
      'preset' => Matrix::PROJECTION,
      'fov' => $this->_fov,
      'ratio' => $this->_ratio,
      'near' => $this->_near,
      'far' => $this->_far
    ]);
  }

  public function watchVertex(Vertex $worldVertex): Vertex {
    $camVertex = $this->_view->transformVertex($worldVertex);
    $NDCVertex = $this->_projection->transformVertex($camVertex);
    $ret = clone $NDCVertex;
    $ret->setX(($ret->getX() + 1) * $this->_width / 2);
    $ret->setY(($ret->getY() - 1) * -1 * $this->_height / 2);
    return $ret;
  }

  private function _verboseConstruct(): void {
    if (!Camera::$verbose) {
      return;
    }

    echo 'Camera instance constructed' . "\n";
  }

  private function _verboseDestruct(): void {
    if (!Camera::$verbose) {
      return;
    }

    echo 'Camera instance destructed' . "\n";
  }

  public function __toString(): string {
    $ret = 'Camera( ' . "\n";
    $ret .= '+ Origine: ' . $this->_origin->__toString() . "\n";
    $ret .= '+ tT:' . "\n";
    $ret .= $this->_tT->__toString() . "\n";
    $ret .= '+ tR:' . "\n";
    $ret .= $this->_tR->__toString() . "\n";
    $ret .= '+ tR->mult( tT ):' . "\n";
    $ret .= $this->_view->__toString() . "\n";
    $ret .= '+ Proj:' . "\n";
    $ret .= $this->_projection->__toString() . "\n";
    $ret .= ')';
    return $ret;
  }

}
