<?php
        class _emathk {
                public $suppress_errors = false;
                public $last_error = null;
                public $v = array( 'e' => 2.71, 'pi' => 3.14 );
                public $f = array(  );
                public $vb = array( 'e', 'pi' );
                public $fb = array( 'sin', 'sinh', 'arcsin', 'asin', 'arcsinh', 'asinh', 'cos', 'cosh', 'arccos', 'acos', 'arccosh', 'acosh', 'tan', 'tanh', 'arctan', 'atan', 'arctanh', 'atanh', 'sqrt', 'abs', 'ln', 'log' );
                public function _emath(  ) {
                        $this->v[ 'pi' ] = pi(  );
                        $this->v[ 'e' ] = exp( 1 );
                }
                public function e( $expr ) {
                        return $this->evaluate( $expr );
                }
                public function evaluate( $expr ) {
                        $this->last_error = null;
                        $expr = trim( $expr );
                        if ( substr( $expr, -1, 1 ) == ';' )
                                $expr = substr( $expr, 0, strlen( $expr ) - 1 );
                        if ( preg_match( '/^\s*([a-z]\w*)\s*=\s*(.+)$/', $expr, $matches ) ) {
                                if ( in_array( $matches[ 1 ], $this->vb ) ) {
                                        return $this->trigger( "cannot assign to constant '$matches[1]'" );
                                }
                                if ( ( $tmp = $this->pfx( $this->nfx( $matches[ 2 ] ) ) ) === false )
                                        return false;
                                $this->v[ $matches[ 1 ] ] = $tmp;
                                return $this->v[ $matches[ 1 ] ];
                        } elseif ( preg_match( '/^\s*([a-z]\w*)\s*\(\s*([a-z]\w*(?:\s*,\s*[a-z]\w*)*)\s*\)\s*=\s*(.+)$/', $expr, $matches ) ) {
                                $fnn = $matches[ 1 ];
                                if ( in_array( $matches[ 1 ], $this->fb ) ) {
                                        return $this->trigger( "cannot redefine built-in public function '$matches[1]()'" );
                                }
                                $args = explode( ",", preg_replace( "/\s+/", "", $matches[ 2 ] ) );
                                if ( ( $stack = $this->nfx( $matches[ 3 ] ) ) === false )
                                        return false;
                                for ( $i = 0; $i < count( $stack ); $i++ ) {
                                        $token = $stack[ $i ];
                                        if ( preg_match( '/^[a-z]\w*$/', $token ) and !in_array( $token, $args ) ) {
                                                if ( array_key_exists( $token, $this->v ) ) {
                                                        $stack[ $i ] = $this->v[ $token ];
                                                } else {
                                                        return $this->trigger( "undefined publiciable '$token' in public function definition" );
                                                }
                                        }
                                }
                                $this->f[ $fnn ] = array( 'args' => $args, 'func' => $stack );
                                return true;
                        } else {
                                return $this->pfx( $this->nfx( $expr ) );
                        }
                }
                public function publics(  ) {
                        $output = $this->v;
                        unset( $output[ 'pi' ] );
                        unset( $output[ 'e' ] );
                        return $output;
                }
                public function funcs(  ) {
                        $output = array(  );
                        foreach ( $this->f as $fnn => $dat )
                                $output[  ] = $fnn . '(' . implode( ',', $dat[ 'args' ] ) . ')';
                        return $output;
                }
                public function nfx( $expr ) {
                        $index = 0;
                        $stack = new _emathStack;
                        $output = array(  );
                        $expr = trim( strtolower( $expr ) );
                        $ops = array( '+', '-', '*', '/', '^', '_' );
                        $ops_r = array( '+' => 0, '-' => 0, '*' => 0, '/' => 0, '^' => 1 );
                        $ops_p = array( '+' => 0, '-' => 0, '*' => 1, '/' => 1, '_' => 1, '^' => 2 );
                        $expecting_op = false;
                        if ( preg_match( "/[^\w\s+*^\/()\.,-]/", $expr, $matches ) ) {
                                return $this->trigger( "illegal character '{$matches[0]}'" );
                        }
                        while ( 1 ) {
                                $op = substr( $expr, $index, 1 );
                                $ex = preg_match( '/^([a-z]\w*\(?|\d+(?:\.\d*)?|\.\d+|\()/', substr( $expr, $index ), $match );
                                if ( $op == '-' and !$expecting_op ) {
                                        $stack->push( '_' );
                                        $index++;
                                } elseif ( $op == '_' ) {
                                        return $this->trigger( "illegal character '_'" );
                                } elseif ( ( in_array( $op, $ops ) or $ex ) and $expecting_op ) {
                                        if ( $ex ) {
                                                $op = '*';
                                                $index--;
                                        }
                                        while ( $stack->count > 0 and ( $o2 = $stack->last(  ) ) and in_array( $o2, $ops ) and ( $ops_r[ $op ] ? $ops_p[ $op ] < $ops_p[ $o2 ] : $ops_p[ $op ] <= $ops_p[ $o2 ] ) ) {
                                                $output[  ] = $stack->pop(  );
                                        }
                                        $stack->push( $op );
                                        $index++;
                                        $expecting_op = false;
                                } elseif ( $op == ')' and $expecting_op ) {
                                        while ( ( $o2 = $stack->pop(  ) ) != '(' ) {
                                                if ( is_null( $o2 ) )
                                                        return $this->trigger( "unexpected ')'" );
                                                else
                                                        $output[  ] = $o2;
                                        }
                                        if ( preg_match( "/^([a-z]\w*)\($/", $stack->last( 2 ), $matches ) ) {
                                                $fnn = $matches[ 1 ];
                                                $arg_count = $stack->pop(  );
                                                $output[  ] = $stack->pop(  );
                                                if ( in_array( $fnn, $this->fb ) ) {
                                                        if ( $arg_count > 1 )
                                                                return $this->trigger( "too many arguments ($arg_count given, 1 expected)" );
                                                } elseif ( array_key_exists( $fnn, $this->f ) ) {
                                                        if ( $arg_count != count( $this->f[ $fnn ][ 'args' ] ) )
                                                                return $this->trigger( "wrong number of arguments ($arg_count given, " . count( $this->f[ $fnn ][ 'args' ] ) . " expected)" );
                                                } else {
                                                        return $this->trigger( "internal error" );
                                                }
                                        }
                                        $index++;
                                } elseif ( $op == ',' and $expecting_op ) {
                                        while ( ( $o2 = $stack->pop(  ) ) != '(' ) {
                                                if ( is_null( $o2 ) )
                                                        return $this->trigger( "unexpected ','" );
                                                else
                                                        $output[  ] = $o2;
                                        }
                                        if ( !preg_match( "/^([a-z]\w*)\($/", $stack->last( 2 ), $matches ) )
                                                return $this->trigger( "unexpected ','" );
                                        $stack->push( $stack->pop(  ) + 1 );
                                        $stack->push( '(' );
                                        $index++;
                                        $expecting_op = false;
                                } elseif ( $op == '(' and !$expecting_op ) {
                                        $stack->push( '(' );
                                        $index++;
                                        $allow_neg = true;
                                } elseif ( $ex and !$expecting_op ) {
                                        $expecting_op = true;
                                        $val = $match[ 1 ];
                                        if ( preg_match( "/^([a-z]\w*)\($/", $val, $matches ) ) {
                                                if ( in_array( $matches[ 1 ], $this->fb ) or array_key_exists( $matches[ 1 ], $this->f ) ) {
                                                        $stack->push( $val );
                                                        $stack->push( 1 );
                                                        $stack->push( '(' );
                                                        $expecting_op = false;
                                                } else {
                                                        $val = $matches[ 1 ];
                                                        $output[  ] = $val;
                                                }
                                        } else {
                                                $output[  ] = $val;
                                        }
                                        $index += strlen( $val );
                                } elseif ( $op == ')' ) {
                                        return $this->trigger( "unexpected ')'" );
                                } elseif ( in_array( $op, $ops ) and !$expecting_op ) {
                                        return $this->trigger( "unexpected operator '$op'" );
                                } else {
                                        return $this->trigger( "an unexpected error occured" );
                                }
                                if ( $index == strlen( $expr ) ) {
                                        if ( in_array( $op, $ops ) ) {
                                                return $this->trigger( "operator '$op' lacks operand" );
                                        } else {
                                                break;
                                        }
                                }
                                while ( substr( $expr, $index, 1 ) == ' ' ) {
                                        $index++;
                                }
                        }
                        while ( !is_null( $op = $stack->pop(  ) ) ) {
                                if ( $op == '(' )
                                        return $this->trigger( "expecting ')'" );
                                $output[  ] = $op;
                        }
                        return $output;
                }
                public function pfx( $tokens, $publics = array(  ) ) {
                        if ( $tokens == false )
                                return false;
                        $stack = new _emathStack;
                        foreach ( $tokens as $token ) {
                                if ( in_array( $token, array( '+', '-', '*', '/', '^' ) ) ) {
                                        if ( is_null( $op2 = $stack->pop(  ) ) )
                                                return $this->trigger( "internal error" );
                                        if ( is_null( $op1 = $stack->pop(  ) ) )
                                                return $this->trigger( "internal error" );
                                        switch ( $token ) {
                                                case '+':
                                                        $stack->push( $op1 + $op2 );
                                                        break;
                                                case '-':
                                                        $stack->push( $op1 - $op2 );
                                                        break;
                                                case '*':
                                                        $stack->push( $op1 * $op2 );
                                                        break;
                                                case '/':
                                                        if ( $op2 == 0 )
                                                                return $this->trigger( "division by zero" );
                                                        $stack->push( $op1 / $op2 );
                                                        break;
                                                case '^':
                                                        $stack->push( pow( $op1, $op2 ) );
                                                        break;
                                        }
                                } elseif ( $token == "_" ) {
                                        $stack->push( -1 * $stack->pop(  ) );
                                } elseif ( preg_match( "/^([a-z]\w*)\($/", $token, $matches ) ) {
                                        $fnn = $matches[ 1 ];
                                        if ( in_array( $fnn, $this->fb ) ) {
                                                if ( is_null( $op1 = $stack->pop(  ) ) )
                                                        return $this->trigger( "internal error" );
                                                $fnn = preg_replace( "/^arc/", "a", $fnn );
                                                if ( $fnn == 'ln' )
                                                        $fnn = 'log';
                                                eval( '$stack->push(' . $fnn . '($op1));' );
                                        } elseif ( array_key_exists( $fnn, $this->f ) ) {
                                                $args = array(  );
                                                for ( $i = count( $this->f[ $fnn ][ 'args' ] ) - 1; $i >= 0; $i-- ) {
                                                        if ( is_null( $args[ $this->f[ $fnn ][ 'args' ][ $i ] ] = $stack->pop(  ) ) )
                                                                return $this->trigger( "internal error" );
                                                }
                                                $stack->push( $this->pfx( $this->f[ $fnn ][ 'func' ], $args ) );
                                        }
                                } else {
                                        if ( is_numeric( $token ) ) {
                                                $stack->push( $token );
                                        } elseif ( array_key_exists( $token, $this->v ) ) {
                                                $stack->push( $this->v[ $token ] );
                                        } elseif ( array_key_exists( $token, $publics ) ) {
                                                $stack->push( $publics[ $token ] );
                                        } else {
                                                return $this->trigger( "undefined publiciable '$token'" );
                                        }
                                }
                        }
                        if ( $stack->count != 1 )
                                return $this->trigger( "internal error" );
                        return $stack->pop(  );
                }
                public function trigger( $msg ) {
                        $this->last_error = $msg;
                        if ( !$this->suppress_errors )
                                trigger_error( $msg, E_USER_WARNING );
                        return false;
                }
        }
        class _emathStack {
                public $stack = array(  );
                public $count = 0;
                public function push( $val ) {
                        $this->stack[ $this->count ] = $val;
                        $this->count++;
                }
                public function pop(  ) {
                        if ( $this->count > 0 ) {
                                $this->count--;
                                return $this->stack[ $this->count ];
                        }
                        return null;
                }
                public function last( $n = 1 ) {
                        return $this->stack[ $this->count - $n ];
                }
        }
?>