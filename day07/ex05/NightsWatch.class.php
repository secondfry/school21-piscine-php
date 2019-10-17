<?php

class NightsWatch {

  private array $figthers = [];

  public function recruit(object $person): void {
    if (!($person instanceof IFighter)) {
      return;
    }

    $this->figthers[] = $person;
  }

  public function fight(): void {
    foreach ($this->figthers as $fighter) {
      $fighter->fight();
    }
  }

}
