<?php

use MongoDB\BSON\ObjectId;

require_once __DIR__ . '/../interfaces/DBStorable.interface.php';
require_once __DIR__ . '/GameField.class.php';
require_once __DIR__ . '/GameState.class.php';

class Game implements JsonSerializable, DBStorable
{

  private ObjectId  $_id;
  private GameField $_field;
  private GameState $_state;
  private           $_data;

  /**
   * @return array|object|null
   * @throws GameException in case $_id is not set
   */
  private
  function getData()
  {
    if (!isset($this -> _id)) {
      throw new GameException('Game.getData() requested, but no ID supplied!');
    }

    if (!isset($this -> _data)) {
      $this -> _data = MDB ::get() -> games -> findOne(['_id' => $this -> _id]);
    }

    return $this -> _data;
  }

  /**
   * @return GameField
   * @throws GameException in case $_id is not set
   */
  public
  function getField(): GameField
  {
    if (!isset($this -> _field)) {
      $this -> _field = GameField ::recreate($this -> getData()['field']);
    }

    return $this -> _field;
  }

  /**
   * @return GameState
   * @throws GameException in case $_id is not set
   */
  public
  function getState(): GameState
  {
    if (!isset($this -> _state)) {
      $this -> _state = GameState ::recreate($this -> getData()['state']);
    }

    return $this -> _state;
  }

  /**
   * @throws GameException never
   */
  public static
  function constructPreset(): Game
  {
    $ret = new Game();

    $ret -> _field = new GameField();
    $ret -> _state = new GameState();

    $pid  = $ret -> getState() -> addPlayer();
    $ship = new AmarrFrigate(1, $pid, 1, 1);
    $ret -> getState() -> addShip($ship);

    $pid  = $ret -> getState() -> addPlayer();
    $ship = new AmarrFrigate(2, $pid, 5, 8);
    $ret -> getState() -> addShip($ship);

    return $ret;
  }

  public static
  function recreate(
    $data
  ): Game {
    $ret = new Game();

    $ret -> _id    = $data['id'];
    $ret -> _field = $ret -> getField();
    $ret -> _state = $ret -> getState();

    return $ret;
  }

  /**
   * @throws GameException in case $_id is not set
   */
  public
  function play(): void
  {
    switch ($this -> getState() -> getPhase()) {
      case GameState::PHASE_SELECT_PLAYER:
        $this -> getState() -> nextPlayer();
        $this -> getState() -> setPhase(GameState::PHASE_SELECT_SHIP);
        break;
      case GameState::PHASE_SELECT_SHIP:

        break;
      case GameState::PHASE_RESET:
        $this -> getState() -> reset();
        break;
    }
  }

  /**
   * @param int $id
   * @throws GameException in case $_id is not set
   */
  public
  function selectShip(
    int $id
  ): void {
    $status = $this -> getState() -> setCurrentShipID($id);
    if (!$status) {
      return;
    }

    $this -> getState() -> setPhase(GameState::PHASE_USE_SHIP);
    return;
  }

  /**
   * @param int $x
   * @param int $y
   * @throws GameException in case $_id is not set
   */
  public
  function moveTo(
    int $x,
    int $y
  ): void {
    $ship = $this -> getState() -> getCurrentShip();
    $ship -> moveTo($x, $y, $this -> _field);
    $this -> getState() -> setStatus(
      sprintf(
        'Вы успешно перестили %s #%d',
        $ship -> getName(),
        $ship -> getID()
      )
    );
  }

  /**
   * @param int $x
   * @param int $y
   * @throws GameException in case $_id is not set
   */
  public
  function attackAt(
    int $x,
    int $y
  ): void {
    $ship      = $this -> getState() -> getCurrentShip();
    $shipOther = $this -> getState() -> getShipAtLocation($x, $y);

    if (!$shipOther) {
      $this -> getState() -> setStatus('Там нет корабля!');
      return;
    }

    $ship -> attackAt($x, $y, $shipOther);
    $this -> getState() -> setStatus(
      sprintf(
        'Вы успешно атаковали %s #%d',
        $shipOther -> getName(),
        $shipOther -> getID()
      )
    );
  }

  /**
   * @return array|mixed
   * @throws GameException in case $_id is not set
   */
  public
  function jsonSerialize()
  {
    $ret = [
      'field' => $this -> getField() -> jsonSerialize(),
      'state' => $this -> getState() -> jsonSerialize(),
    ];

    if (isset($this -> _id)) {
      $ret['id'] = $this -> _id;
    }

    return $ret;
  }
}
