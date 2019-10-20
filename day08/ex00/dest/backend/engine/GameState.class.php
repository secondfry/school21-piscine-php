<?php

require_once __DIR__ . '/../ships/AShip.class.php';

class GameState implements ITurnBased
{

  const PHASE_SELECT_PLAYER = 1;
  const PHASE_SELECT_SHIP   = 2;
  const PHASE_USE_SHIP      = 3;
  const PHASE_RESET         = 4;

  private int   $_phase = GameState::PHASE_SELECT_PLAYER;

  private array $_players       = [];
  private int   $_currentPlayer = 0;
  private int   $_currentShipID = 0;

  private string $_status = '';

  public
  function addPlayer(
    bool $setCurrent = false
  ): int {
    $id                    = count($this -> _players) + 1;
    $this -> _players[$id] = [
      'id'    => $id,
      'ships' => [],
    ];

    if ($setCurrent) {
      $this -> _currentPlayer = $id;
    }

    return $id;
  }

  public
  function getCurrentPlayer(): int
  {
    return $this -> _currentPlayer;
  }

  public
  function nextPlayer(): GameState
  {
    foreach ($this -> _players as $player) {
      if ($player['id'] === $this -> _currentPlayer) {
        continue;
      }

      $this -> _currentPlayer = $player['id'];
      return $this;
    }

    return $this;
  }

  public
  function getPhase(): int
  {
    return $this -> _phase;
  }

  public
  function setPhase(
    int $value
  ): GameState {
    $this -> _phase = $value;

    switch ($this -> _phase) {
      case GameState::PHASE_SELECT_SHIP:
        $this -> _status = sprintf(
          'Игрок #%d выберите свой корабль.',
          $this -> _currentPlayer
        );
        break;
    }

    return $this;
  }

  public
  function getShipAtLocation(
    int $x,
    int $y
  ): ?AShip {
    foreach ($this -> _players as $player) {
      foreach ($player['ships'] as $ship) {
        /** @var AShip $ship */
        if (!$ship -> isOnXY($x, $y)) {
          continue;
        }

        return $ship;
      }
    }

    return null;
  }

  public
  function addShip(
    AShip $ship
  ): GameState {
    $this -> _players[$this -> _currentPlayer]['ships'][] = $ship;
    return $this;
  }

  public
  function getStatus(): string
  {
    return $this -> _status;
  }

  public
  function setStatus(
    string $value
  ): GameState {
    $this -> _status = $value;
    return $this;
  }

  public
  function setCurrentShipID(
    int $id
  ): bool {
    foreach ($this -> _players as $player) {
      foreach ($player['ships'] as $ship) {
        /** @var AShip $ship */
        if ($ship -> getID() === $id) {
          if ($ship -> getPlayerID() !== $this -> _currentPlayer) {
            $this -> _status = 'Вы не можете выбрать чужой корабль.';
            return false;
          }

          $this -> _currentShipID = $id;
          $this -> _status        = sprintf(
            'Вы выбрали корабль %s #%d.',
            $ship -> getName(),
            $ship -> getID()
          );
          return true;
        }
      }
    }

    $this -> _status = 'Такого корабля нет в игре.';
    return false;
  }

  public
  function getCurrentShip(): ?AShip
  {
    foreach ($this -> _players as $player) {
      foreach ($player['ships'] as $ship) {
        /** @var AShip $ship */
        if ($ship -> getID() === $this -> _currentShipID) {
          return $ship;
        }
      }
    }

    return null;
  }

  public
  function reset(): void
  {
    foreach ($this -> _players as $player) {
      foreach ($player['ships'] as $ship) {
        /** @var AShip $ship */
        $ship -> reset();
      }
    }
  }

}
