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
  private string    $_status;
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

    $pid  = $ret -> getState() -> addPlayer(true);
    $ship = new AmarrFrigate(1, $pid, 1, 1);
    $ret -> getState() -> addShip($ship);

    $pid  = $ret -> getState() -> addPlayer(true);
    $ship = new AmarrFrigate(2, $pid, 5, 8);
    $ret -> getState() -> addShip($ship);

    $ret -> getState() -> setCurrentPlayerID(0);

    $ret -> _id     = new ObjectId();
    $ret -> _status = 'active';

    return $ret;
  }

  /**
   * @return bool if it is needed to call this method again
   * @throws GameException in case $_id is not set
   */
  public
  function play(): bool
  {
    $isFinished = $this -> getState() -> checkVictory();
    if ($isFinished) {
      $this -> _status = 'finished';
      $this -> getState() -> setPhase(GameState::PHASE_VICTORY);
      $this -> store();
      return false;
    }

    switch ($this -> getState() -> getPhase()) {
      case GameState::PHASE_SELECT_PLAYER:
        if ($this -> getState() -> checkPlayers()) {
          $this -> getState() -> nextPlayer();
          $this -> getState() -> setPhase(GameState::PHASE_SELECT_SHIP);
          return true;
        }

        $this -> getState() -> setPhase(GameState::PHASE_RESET);
        return true;

      case GameState::PHASE_SELECT_SHIP:
        if ($this -> getState() -> checkPlayer()) {
          return false;
        }

        $this -> getState() -> setPhase(GameState::PHASE_SELECT_PLAYER);
        return true;

      case GameState::PHASE_USE_SHIP:
        if ($this -> getState() -> checkShip()) {
          return false;
        }

        $this -> getState() -> setPhase(GameState::PHASE_SELECT_SHIP);
        return true;

      case GameState::PHASE_RESET:
        $this -> getState() -> reset();
        $this -> getState() -> setPhase(GameState::PHASE_SELECT_PLAYER);
        return true;
    }

    return false;
  }

  /**
   * @return ObjectId
   */
  public
  function getId(): ObjectId
  {
    return $this -> _id;
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

  public
  function store(): void
  {
    MDB ::get() -> games -> updateOne(
      [
        '_id' => $this -> _id,
      ],
      [
        '$set' => [
          '_id'    => $this -> _id,
          'status' => $this -> _status,
          'data'   => serialize($this)
        ]
      ],
      ['upsert' => true]
    );
  }

  public static
  function recreate(
    string $id
  ): Game {
    $objid = new ObjectId($id);
    $data  = MDB ::get() -> games -> findOne(['_id' => $objid]);
    $game  = unserialize($data['data']);
    return $game;
  }

  /**
   * @return array|mixed
   * @throws GameException in case $_id is not set
   */
  public
  function jsonSerialize()
  {
    return [
      '_id'    => $this -> _id,
      'field'  => $this -> getField() -> jsonSerialize(),
      'state'  => $this -> getState() -> jsonSerialize(),
      'status' => $this -> _status,
    ];
  }

}
