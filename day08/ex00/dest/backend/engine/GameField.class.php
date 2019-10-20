<?php

use MongoDB\BSON\ObjectId;

require_once __DIR__ . '/../interfaces/DBStorable.interface.php';

class GameField implements JsonSerializable, DBStorable
{

  const CELL_EMPTY = <<< EOF
<td>
  <div class="cell">
    <div class="cell_ship">X</div>
    <div class="cell_select disabled"></div>
    <div class="cell_move disabled"></div>
    <div class="cell_attack disabled"></div>
  </div>
</td>
EOF;

  const HEADER = <<< EOF
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style>
body {
  background: #333;
  color: #eee;
}
.cell {
  display: grid;
  grid-template: 'ship ship ship' 'select move attack' / 1fr 1fr 1fr;
  
  border: 1px solid #000;
}
.cell_ship,
.cell_select,
.cell_move,
.cell_attack {
  display: grid;
  justify-content: center;
  align-items: center;
}
.cell_ship {
  grid-area: ship;
  justify-self: center;
  align-self: center;
  text-align: center;
  
  width: 90px;
  height: 90px;
}
.cell_select,
.cell_move,
.cell_attack {
  justify-self: center;
  align-self: center;
  text-align: center;
  
  width: 30px;
  height: 30px;
  
  border: 1px solid #000;
  
  background: #444;
}
.cell_select {
  grid-area: select;
}
.cell_move {
  grid-area: move;
}
.cell_attack {
  grid-area: attack;
}
.cell_select.disabled,
.cell_move.disabled,
.cell_attack.disabled {
  background: #333;
}
</style>
EOF;

  const NAVIGATION = <<< EOF
<a href="/list">Список игр</a><br>
<a href="/create">Создать игру</a><br>
EOF;


  private ObjectId $_id;
  private int      $_width  = 20;
  private int      $_height = 20;

  public
  function __construct()
  {
    $this -> _id = new ObjectId();
  }

  public
  function store(): void
  {
    MDB ::get() -> fields -> updateOne(
      [
        '_id' => $this -> _id,
      ],
      [
        '$set' => [
          '_id'  => $this -> _id,
          'data' => serialize($this)
        ]
      ],
      ['upsert' => true]
    );
  }

  public static
  function recreate(
    string $id
  ): GameField {
    $data  = MDB ::get() -> fields -> findOne(['_id' => $id]);
    $field = unserialize($data['data']);
    return $field;
  }

  /**
   * @return int
   */
  public
  function getWidth(): int
  {
    return $this -> _width;
  }

  /**
   * @return int
   */
  public
  function getHeight(): int
  {
    return $this -> _height;
  }

  public
  function jsonSerialize()
  {
    return [
      'width'  => $this -> _width,
      'height' => $this -> _height,
    ];
  }

  public static
  function constructCell(
    int $x,
    int $y,
    array $data
  ): string {
    return sprintf(
      '<td>
        <div class="cell">
          <div class="cell_ship">%s</div>
          <div class="cell_select %s">%s</div>
          <div class="cell_move %s">%s</div>
          <div class="cell_attack %s">%s</div>
        </div>
      </td>',
      $data['flagShip']
        ? $data['ship']->getCurrentHull() > 0
        ? sprintf(
          '%s #%d<br>SP: %d/%d<br>HP: %d/%d',
          $data['ship'] -> getName(),
          $data['ship'] -> getID(),
          $data['ship'] -> getCurrentShields(),
          $data['ship'] -> getDefaultShields(),
          $data['ship'] -> getCurrentHull(),
          $data['ship'] -> getDefaultHull(),
        )
        : sprintf(
          'Wreck of<br>%s #%d',
          $data['ship'] -> getName(),
          $data['ship'] -> getID(),

        ) : 'X',
      $data['flagSelect'] ? '' : 'disabled',
      $data['flagSelect'] ? sprintf(
        '<a href="/play/%s/select/%d">S</a>',
        $data['game'] -> getID(),
        $data['ship'] -> getID(),
      ) : '',
      $data['flagMove'] ? '' : 'disabled',
      $data['flagMove'] ? sprintf(
        '<a href="/play/%s/move/x/%d/y/%d">M</a>',
        $data['game'] -> getID(),
        $x,
        $y
      ) : '',
      $data['flagAttack'] ? '' : 'disabled',
      $data['flagAttack'] ? sprintf(
        '<a href="/play/%s/attack/x/%d/y/%d">A</a>',
        $data['game'] -> getID(),
        $x,
        $y
      ) : '',
    );
  }

  public
  function render(
    Game $game,
    GameState $state
  ): string {
    $ret = '';
    $ret .= GameField::HEADER;
    $ret .= '<div class="px-1">';
    $ret .= GameField::NAVIGATION;
    $ret .= '<div>' . $state -> getStatus() . '</div>';
    $ret .= '<table>';
    $j   = 0;
    while ($j < $this -> _height) {
      $i   = 0;
      $ret .= '<tr>';
      while ($i < $this -> _width) {
        $ret .= $this -> _renderCell($game, $state, $i, $j);
        $i++;
      }
      $ret .= '</tr>';
      $j++;
    }
    $ret .= '</table>';
    $ret .= '</div>';
    return $ret;
  }

  public
  function _renderCell(
    Game $game,
    GameState $state,
    int $x,
    int $y
  ): string {
    $ship      = $state -> getCurrentShip();
    $shipAtLoc = $state -> getShipAtLocation($x, $y);

    switch ($state -> getPhase()) {

      case GameState::PHASE_SELECT_SHIP:

        if ($shipAtLoc === null) {
          return GameField::CELL_EMPTY;
        }

        if ($shipAtLoc -> getPlayerID() !== $state -> getCurrentPlayerID()) {
          return GameField ::constructCell(
            $x,
            $y,
            [
              'ship'     => $shipAtLoc,
              'flagShip' => true,
              'game'     => $game
            ]
          );
        }

        return GameField ::constructCell(
          $x,
          $y,
          [
            'ship'       => $shipAtLoc,
            'flagShip'   => true,
            'flagSelect' => true,
            'game'       => $game
          ]
        );

      case GameState::PHASE_USE_SHIP:

        if ($shipAtLoc && $ship -> canAttack($x, $y)) {
          return GameField ::constructCell(
            $x,
            $y,
            [
              'ship'       => $shipAtLoc,
              'flagShip'   => true,
              'flagAttack' => true,
              'game'       => $game
            ]
          );
        }

        if ($shipAtLoc !== null) {
          return GameField ::constructCell(
            $x,
            $y,
            [
              'ship'     => $shipAtLoc,
              'flagShip' => true,
              'game'     => $game
            ]
          );
        }

        if ($ship -> canMoveTo($x, $y, $this)) {
          return GameField ::constructCell(
            $x,
            $y,
            [
              'ship'       => $ship,
              'flagMove'   => true,
              'game'       => $game
            ]
          );
        }

        return GameField ::constructCell($x, $y, []);

      case GameState::PHASE_VICTORY:
        if ($shipAtLoc !== null) {
          return GameField ::constructCell(
            $x,
            $y,
            [
              'ship'     => $shipAtLoc,
              'flagShip' => true,
              'game'     => $game
            ]
          );
        }

        return GameField ::constructCell($x, $y, []);
    }

    return '<td>ERROR</td>';
  }

}
