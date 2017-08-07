<?php

/*
FORMATO A EMPLEAR:

  'nombre_de_columna' => TIPO
  'nombre_de_columna' => [ TIPO [ , valor_por_defecto [ , tipo_del_valor_por_defecto ] ]

EJEMPLOS
  [
    'uid' => SQLITE3_INTEGER
    'name' => [ SQLITE3_TEXT ],
    'lastlogin' => [ SQLITE3_TEXT, time( ) ],
    'notes' => [ SQLITE3_TEXT, NULL, SQLITE3_NULL ]
  ]

*/

class DBRowFormat implements Countable {
  private $realValues;

  const REQUIREALL = 0; // Todo lo que esté en '$format'.
  const WITHVALUES = 1; // Todo lo que esté en '$values'.

  private static function getValue( $format, $values, $name, $ignoreDefault ) {
    if( !array_key_exists( $name, $format ) )
      throw new Exception( 'Campo \'' . $name . '\' no existe en el formato' );

    $curr = $format[$name];

    // Lo convertimos a Array[ tipo, valor_por_defecto, tipo_del_valor_por_defecto ].
    if( !is_array( $curr ) )
      $curr = [ $curr ];

    // Si no se pasó un valor explícito, y, además,
    // se ignora el valor por defecto,
    // o dicho valor por defecto no existe,
    // devolvemos FALSE.
    if( !array_key_exists( $name, $values ) ) {
      if( $ignoreDefault || ( count( $curr ) < 3 ) )
        throw new Exception( 'Campo obligatorio \'' . $name .'\' sin valor' );

      return [ $curr[1], $curr[0] ];
    }

    return [ $values[$name], $curr[0] ];
  }

  function __construct( $format, $values, $list = 0, $ignoreDefaults = FALSE ) {
    if( is_integer( $list ) ) {
      switch( $list ) {
      case 0:
        $list = [ ];

        foreach( $format as $k => $v )
          $list[] = $k;

        break;

      case 1:
        $list = [ ];

        foreach( $format as $k => $v )
          if( array_key_exists( $k, $values ) )
            $list[] = $k;

        break;

      default:
        throw new Exception( 'Argumento \'$list\' inválido' );
      }
    }

    // $list es una lista [ ] de las claves a utilizar.
    // La recorremos para generar nuestro $this->realValues;

    $this->realValues = [ ];

    foreach( $list as $idx )
      $this->realValues[$idx] = self::getValue( $format, $values, $idx, $ignoreDefaults );
  }

  function count( ) { return count( $this->realValues ); }

  function names( $phars = TRUE ) {
    $ret = '';

    foreach( $this->realValues as $k => $v ) {
      if( $ret !== '' )
        $ret .= ',';

      $ret .= $k;
    }

    return $phars ? ( '(' . $ret . ')' ) : $ret;
  }

  function params( $phars = TRUE ) {
    $ret = '';

    foreach( $this->realValues as $k => $v ) {
      if( $ret !== '' )
        $ret .= ',:';
      else
        $ret .= ':';

      $ret .= $k;
    }

    return $phars ? ( '(' . $ret . ')' ) : $ret;
  }

  function bind( $stm ) {
    foreach( $this->realValues as $k => $v )
      if( $stm->bindValue( ':' . $k, $v[0], $v[1] ) === FALSE )
        throw new Exception( 'Error en bindValue( \'' . $k . '\' )' );
  }
}
