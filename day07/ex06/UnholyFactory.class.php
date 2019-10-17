<?php

class UnholyFactory {

  private array $_entities = [];
  private array $_types = [];

  public function absorb(object $entity) {
    if (!($entity instanceof Fighter)) {
      echo '(Factory can\'t absorb this, it\'s not a fighter)' . "\n";
      return;
    }

    $type = $entity->getType();
    if (in_array($type, $this->_types)) {
      echo '(Factory already absorbed a fighter of type ' . $type . ')' . "\n";
      return;
    }

    $this->_entities[$type] = $entity;
    $this->_types[] = $type;
    echo '(Factory absorbed a fighter of type ' . $type . ')' . "\n";
  }

  public function fabricate(string $type): ?Fighter {
    if (!in_array($type, $this->_types)) {
      echo '(Factory hasn\'t absorbed any fighter of type ' . $type . ')' . "\n";
      return null;
    }

    $ret = clone $this->_entities[$type];
    echo '(Factory fabricates a fighter of type ' . $type . ')' . "\n";
    return $ret;
  }

}
