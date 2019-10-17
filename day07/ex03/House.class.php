<?php

class House {

  public function introduce(): void {
    $str = sprintf(
      'House %s of %s : "%s"' . "\n",
      $this->getHouseName(),
      $this->getHouseSeat(),
      $this->getHouseMotto()
    );
    echo $str;
  }

}
