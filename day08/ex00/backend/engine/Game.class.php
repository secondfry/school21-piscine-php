<?php

require_once __DIR__ . '/GameField.class.php';
require_once __DIR__ . '/GameState.class.php';

class Game
{

  private GameField $_field;
  private GameState $_state;

  public
  function __construct()
  {
    $this -> _field = new GameField();
    $this -> _state = new GameState();

    $pid  = $this -> _state -> addPlayer();
    $ship = new AmarrFrigate(1, $pid, 1, 1);
    $this -> _state -> addShip($ship);

    $pid  = $this -> _state -> addPlayer();
    $ship = new AmarrFrigate(2, $pid, 5, 8);
    $this -> _state -> addShip($ship);
  }

  public
  function play(): void
  {
    switch ($this -> _state -> getPhase()) {
      case GameState::PHASE_SELECT_PLAYER:
        $this -> _state -> nextPlayer();
        $this -> _state -> setPhase(GameState::PHASE_SELECT_SHIP);
        break;
      case GameState::PHASE_SELECT_SHIP:

        break;
      case GameState::PHASE_RESET:
        $this -> _state -> reset();
        break;
    }
  }

  public
  function render(): string
  {
    $ret = $this -> _state -> getStatus();
    $ret .= $this -> _field -> render($this -> _state);
    return $ret;
  }

  public
  function selectShip(
    int $id
  ): void {
    $status = $this -> _state -> setCurrentShipID($id);
    if (!$status) {
      return;
    }

    $this -> _state -> setPhase(GameState::PHASE_USE_SHIP);
    return;
  }

  public
  function moveTo(
    int $x,
    int $y
  ): void {
    $ship = $this -> _state -> getCurrentShip();
    $ship -> moveTo($x, $y);
    $this -> _state -> setStatus(
      sprintf(
        'Вы успешно перестили %s #%d',
        $ship -> getName(),
        $ship -> getID()
      )
    );
  }

  public
  function attackAt(
    int $x,
    int $y
  ): void {
    $ship = $this -> _state -> getCurrentShip();
    $shipOther = $this->_state->getShipAtLocation($x, $y);

    if (!$shipOther) {
      $this -> _state -> setStatus('Там нет корабля!');
      return;
    }

    $ship -> attackAt($x, $y, $shipOther);
    $this -> _state -> setStatus(
      sprintf(
        'Вы успешно атаковали %s #%d',
        $shipOther -> getName(),
        $shipOther -> getID()
      )
    );
  }

}
