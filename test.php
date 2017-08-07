<?php
define( 'FORMAT1', [
  'uid' => SQLITE3_INTEGER,
  'name' => [ SQLITE3_TEXT, NULL, SQLITE3_NULL ],
  'state' => 
] );
define( 'DATA1', [
  'uid' => 1,
  'name' => 'Juanjo',
  'created' => 0
] );

require( './dbrowformat.php' );

class Statement {
  public $result = [ ];
  public $binds = 0;

  function bindValue( $name, $value, $type = NULL ) {
    $this->result[$name] = [ $value, $type ];
    ++$this->binds;
  }
}

$test = new DBRowFormat( FORMAT1, DATA1, DBRowFormat::REQUIREALL, TRUE );

echo 'names: ', $test->names( ), "\n";
echo 'params: ', $test->params( ), "\n";