<?php
define( 'FORMAT1', [
  'uid' => SQLITE3_INTEGER,
  'name' => [ SQLITE3_TEXT, NULL, SQLITE3_NULL ],
  'state' => [ [ 0, 1, 2 ], SQLITE3_INTEGER, 1 ],
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
  'pin' => 0,
  'state' => 5
] );

require( './dbrowformat.php' );

class Statement implements Countable {
  public $result = [ ];

  function count( ) { return count( $this->result ); }
  function bindValue( $name, $value, $type = NULL ) { $this->result[$name] = [ $value, $type ]; }
  function dump( ) {
    echo 'Núm. de elementos: ', count( $this ), "\n";

    foreach( $this->result as $k => $v )
      echo '  ', $k, '-> valor: ', print_r( $v[0], TRUE ), ', tipo: ', $v[1], "\n";
  }
}

echo "TEST 1: Todos los campos, ignorando los valores por defecto.\n";

try {
  $error = NULL;
  $test = new DBRowFormat( FORMAT1, DATA1, DBRowFormat::REQUIREALL, TRUE );
} catch( Exception $err ) {
  $error = $err->getMessage( );
}

if( empty( $error ) ){
  echo "FALLIDO !!\n";
  exit( );
} else {
  echo "PASADO\n";
  echo 'Error generado: ' . $error . "\n";
}

echo "\nTEST 2: Todos los campos, SIN IGNORAR los valores por defecto.\n";

try {
  $error = NULL;
  $test = new DBRowFormat( FORMAT1, DATA1, DBRowFormat::REQUIREALL, FALSE );
} catch( Exception $err ) {
  $error = $err->getMessage( );
}

if( empty( $error ) ){
  echo "FALLIDO !!\n";
  exit( );
} else {
  echo "PASADO\n";
  echo 'Error generado: ' . $error . "\n";
}

echo "\nTEST 3: Todos los campos, SIN IGNORAR los valores por defecto.\n";

try {
  $stm = new Statement( );
  $error = NULL;
  $test = new DBRowFormat( FORMAT1, DATA2, DBRowFormat::REQUIREALL, FALSE );
  $test->bind( $stm );
} catch( Exception $err ) {
  $error = $err->getMessage( );
}

if( empty( $error ) ) {
  echo "PASADO.\n";
  echo 'names->', $test->names( TRUE ), "\n";
  echo 'params->', $test->params( TRUE ), "\n";
  $stm->dump( );
} else {
  echo "FALLIDO !!\nError generado: " . $error . "\n";
}
