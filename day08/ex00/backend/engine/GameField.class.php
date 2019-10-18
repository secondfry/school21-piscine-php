<?php

class GameField
{

  private int $_width  = 10;
  private int $_height = 10;

  public
  function render(
    GameState $state
  ): string {
    $ret = '<table>';
    $j   = 0;
    while ($j < $this -> _height) {
      $i   = 0;
      $ret .= '<tr>';
      while ($i < $this -> _width) {
        $ret .= $this -> _renderCell($state, $i, $j);
        $i++;
      }
      $ret .= '</tr>';
      $j++;
    }
    $ret .= '</table>';
    return $ret;
  }

  public
  function _renderCell(
    GameState $state,
    int $x,
    int $y
  ): string {
    $ship      = $state -> getCurrentShip();
    $shipAtLoc = $state -> getShipAtLocation($x, $y);

    switch ($state -> getPhase()) {
      case GameState::PHASE_SELECT_SHIP:
        if ($shipAtLoc === null) {
          return '<td>.</td>';
        }

        if ($shipAtLoc -> getPlayerID() !== $state -> getCurrentPlayer()) {
          return sprintf(
            '<td><div class="ship">%s #%d</div></td>',
            $shipAtLoc -> getName(),
            $shipAtLoc -> getID()
          );
        }

        return sprintf(
          '<td><a href="/select/%d">%s</a></td>',
          $shipAtLoc -> getID(),
          $shipAtLoc -> getName()
        );

      case GameState::PHASE_USE_SHIP:
        if ($shipAtLoc && $ship -> canAttack($x, $y)) {
          return sprintf(
            '<td><div class="ship">%s #%d</div><a href="/attack/%d/%d">Атаковать</a></td>',
            $shipAtLoc -> getName(),
            $shipAtLoc -> getID(),
            $x,
            $y
          );
        }

        if ($ship -> canMoveTo($x, $y)) {
          return sprintf(
            '<td><a href="/move/%d/%d">Переместиться</a></td>',
            $x,
            $y
          );
        }

        if ($shipAtLoc !== null) {
          return sprintf(
            '<td><div class="ship">%s #%d</div></td>',
            $shipAtLoc -> getName(),
            $shipAtLoc -> getID()
          );
        }
    }

    return '<td>ERROR</td>';
  }

}
