<?php

require_once 'Vertex.class.php';

class Triangle {

  public static bool $verbose = false;

  public static function doc(): string {
    return file_get_contents(get_class() . '.doc.txt');
  }

  private Vertex $_A;
  private Vertex $_B;
  private Vertex $_C;

  public function __construct(Vertex $a, Vertex $b, Vertex $c) {
    $this->_A = $a;
    $this->_B = $b;
    $this->_C = $c;

    $this->_verboseConstruct();
    return $this;
  }

  public function __destruct() {
    $this->_verboseDestruct();
  }

  public function getA(): Vertex {
    return $this->_A;
  }

  public function getB(): Vertex {
    return $this->_B;
  }

  public function getC(): Vertex {
    return $this->_C;
  }

  static function edgeFunction(Vertex $a, Vertex $b, Vertex $c): float {
    return ($c->getX() - $a->getX()) * ($b->getY() - $a->getY()) - ($c->getY() - $a->getY()) * ($b->getX() - $a->getX());
  }

  public function isVertexInside(Vertex $point): bool {
    $w0 = Triangle::edgeFunction($this->_A, $this->_B, $point);
    $w1 = Triangle::edgeFunction($this->_B, $this->_C, $point);
    $w2 = Triangle::edgeFunction($this->_C, $this->_A, $point);
    if ($w0 >= 0 && $w1 >= 0 && $w2 >= 0) {
      return true;
    }

    return false;
  }

  public function isVertexOnEdge(Vertex $point): array {
    if (!$this->isVertexInside($point)) {
      return [
        'status' => false
      ];
    }

    $BC = $this->_isVertexOnEdgeInternal($this->_B, $this->_C, $point);
    if ($BC['status'] === true) {
      return $BC;
    }

    $CA = $this->_isVertexOnEdgeInternal($this->_C, $this->_A, $point);
    if ($CA['status'] === true) {
      return $CA;
    }

    $AB = $this->_isVertexOnEdgeInternal($this->_A, $this->_B, $point);
    return $AB;
  }

  private function _isVertexOnEdgeInternal(Vertex $a, Vertex $b, Vertex $point): array {
    $w = Triangle::edgeFunction($a, $b, $point);
    if (abs($w) < 10) {
      return [
        'status' => true,
        'weight' => $w,
        'vertexes' => [$a, $b]
      ];
    }

    return [
      'status' => false
    ];
  }

  public function isVisible(): bool {
    return 
      (
        ($this->getB()->getX() - $this->getA()->getX())
        * ($this->getC()->getY() - $this->getA()->getY())
      )
      - (
        ($this->getC()->getX() - $this->getA()->getX())
        * ($this->getB()->getY() - $this->getA()->getY())
      ) >= 0;
  }

  private function _verboseConstruct(): void {
    if (!Camera::$verbose) {
      return;
    }

    echo 'Triangle instance constructed' . "\n";
  }

  private function _verboseDestruct(): void {
    if (!Camera::$verbose) {
      return;
    }

    echo 'Triangle instance destructed' . "\n";
  }

  public function __toString(): string {
    $ret = 'Triangle( ' . "\n";
    $ret .= '  A: ' . $this->_A->__toString() . ',' . "\n";
    $ret .= '  B: ' . $this->_B->__toString() . ',' . "\n";
    $ret .= '  C: ' . $this->_C->__toString() . ',' . "\n";
    $ret .= ')';
    return $ret;
  }

}
