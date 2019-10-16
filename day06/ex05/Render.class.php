<?php

class Render {

  public static bool $verbose = false;

  public static function doc(): string {
    return file_get_contents(get_class() . '.doc.txt');
  }

  public const VERTEX = 1;
  public const EDGE = 2;
  public const RASTERIZE = 3;

  private float $_width;
  private float $_height;
  private string $_filename;

  private $_image;

  public function __construct(int $width, int $height, string $filename) {
    $this->_width = $width;
    $this->_height = $height;
    $this->_filename = $filename;

    $this->_initImage();

    $this->_verboseConstruct();
    return $this;
  }

  public function __destruct() {
    $this->_verboseDestruct();
  }

  private function _initImage() {
    $this->_image = imagecreatetruecolor($this->_width, $this->_height);
    $background_color = imagecolorallocate($this->_image, 0, 0, 0);
  }

  private function _setPixel(int $x, int $y, Color $color): void {
    imagesetpixel(
      $this->_image,
      $x,
      $y,
      $color->toPngColor($this->_image)
    );
  }

  public function renderVertex(Vertex $screenVertex): void {
    $this->_setPixel(
      $screenVertex->getX(),
      $screenVertex->getY(),
      $screenVertex->getColor()
    );
  }

  public function renderTriangle(Triangle $triangle, int $mode): void {
    switch ($mode) {
      case Render::VERTEX:
        $this->_renderTriangleByVertex($triangle);
        return;
      case Render::EDGE:
        $this->_renderTriangleByEdge($triangle);
        return;
    }
  }

  private function _renderTriangleByVertex(Triangle $triangle): void {
    $this->renderVertex($triangle->getA());
    $this->renderVertex($triangle->getB());
    $this->renderVertex($triangle->getC());
  }

  private function _renderTriangleByEdge(Triangle $triangle): void {
    $y = 0;
    while ($y < $this->_height) {
      $x = 0;
      while ($x < $this->_width) {
        $vertex = new Vertex(['x' => $x, 'y' => $y]);
        $onEdge = $triangle->isVertexOnEdge($vertex);
        if (!$onEdge['status']) {
          $x++;
          continue;
        }

        $vertex->setColor(
          $onEdge['vertexes'][0]->getColor()->average(
            $onEdge['vertexes'][1]->getColor()
          )
        );

        $this->renderVertex($vertex);
        unset($vertex, $onEdge);

        $x++;
      }
      $y++;
    }
  }

  public function renderMesh(array $triangles, int $mode): void {
    foreach ($triangles as $triangle) {
      if (!$triangle->isVisible()) {
        continue;
      }

      $this->renderTriangle($triangle, $mode);
    }
  }

  public function develop(): void {
    imagepng(
      $this->_image,
      $this->_filename
    );
  }

  private function _verboseConstruct(): void {
    if (!Camera::$verbose) {
      return;
    }

    echo 'Render instance constructed' . "\n";
  }

  private function _verboseDestruct(): void {
    if (!Camera::$verbose) {
      return;
    }

    echo 'Render instance destructed' . "\n";
  }

  public function __toString(): string {
    $ret = 'Render( ' . "\n";
    $ret .= '  A: ' . $this->_A->__toString() . ',' . "\n";
    $ret .= '  B: ' . $this->_B->__toString() . ',' . "\n";
    $ret .= '  C: ' . $this->_C->__toString() . ',' . "\n";
    $ret .= ')';
    return $ret;
  }

}
