<?php

class Targaryen {

  public function resistsFire() {
    return false;
  }

  public function getBurned(): string {
    if ($this->resistsFire()) {
      return 'emerges naked but unharmed';
    }

    return 'burns alive';
  }

}
