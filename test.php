<?php
define( 'FORMAT1', [
  'uid' => SQLITE3_INTEGER,
  'name' => [ SQLITE3_TEXT, NULL, SQLITE3_NULL ],
  'state' => [ SQLITE3_INTEGER, 1 ],
  'pin' => SQLITE3_INTEGER,
  'notes' => [ SQLITE3_TEXT, NULL, SQLITE3_NULL ]
] );
define( 'DATA1', [
  'uid' => 1,
  'name' => 'Juanjo'
] );
define( 'DATA2', [
  'uid' => 1,
  'name' => 'Juanjo',
  'pin' => 0
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

try {
  $error1 = NULL;
  $test1 = new DBRowFormat( FORMAT1, DATA1, DBRowFormat::REQUIREALL, TRUE );
} catch( Exception $err ) {
  $error1 = $err->getMessage( );
}

echo "TEST 1:\n";

if( empty( $error1 ) ){
  echo "FALLIDO !!\n";
  exit( );
} else {
  echo "PASADO\n";
  echo 'Error generado: ' . $error1 . "\n";
}

echo "\nTEST 2:";

try {
  $error2 = NULL;
  $test2 = new DBRowFormat( FORMAT1, DATA2, DBRowFormat::REQUIREALL, FALSE );
} catch( Exception $err ) {
  $error2 = $err->getMessage( );
}

if( empty( $error2 ) ) {
  echo "PASADO.\n";
  echo 'names->', $test2->names( TRUE ), "\n";
  echo 'params->', $test2->params( TRUE ), "\n";
} else {
  echo "FALLIDO !!\nError generado: " . $error2 . "\n";
}
