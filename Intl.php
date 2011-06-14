<?php
        error_reporting( 0 );
        
        /* Copyright (c) Kenan Sulayman. All Rights reserved.
         * The NextShell implementation is based on the .Core-Kernel by Kenan Sulayman.
         */
        
        final class NextShell {
                // C-Scope Variables.
                private $cache, $ext, $a, $i = array(  );
		
		// Load the extensions into...
		private $eligibility, $loaded, $conn = array();
		
		// Private vars.
                private $v = "\x49\x2d\x72\x31\x38\x2d\x31\x33\x38\x36\x2d\x36\x34\x0";
                private $g = array( "", "\x2d\x2d\x20\x57\x65\x6c\x63\x6f\x6d\x65\x20\x74\x6f\x20\x4e\x65\x78\x74\x53\x68\x65\x6c\x6c\x20%v%\x2d\x2d", "\x43\x6f\x70\x79\x72\x69\x67\x68\x74\x20\x28\x63\x29\x20\x4b\x65\x6e\x61\x6e\x20\x53\x75\x6c\x61\x79\x6d\x61\x6e\x2e\x20\x41\x75\x74\x68\x6f\x72\x69\x7a\x65\x64\x20\x75\x73\x65\x20\x6f\x6e\x6c\x79\x2e", "" );
		private $initialized = false;
                
                // Kernel Functions
                public function __construct(  ) {
                        $this->a[ 0 ] = time(  );
                        $this->a[ 1 ] = true & $this->loaded = array();
			$this->cache["shellc"] = $this->a;
			$this->cache["shellc"]["ext_dir"] = "ext";
                }
                
                public function __init(  ) {
                        foreach ( $this->g as $p )
                                print( strstr( $p, "%v%" ) ? str_replace( "%v%", $this->v, $p ) : $p ) . "\r\n";
			if ( $this->__handle(array($x="Volumes_i"),$x) ) $this->initialized = true;
                        while ( !!( 1 & true ) ) {
                                print "$ " . getcwd(  ) . " >> ";
                                $x = $this->__inpt();
                                print( ( !is_null( $x ) && $x && $x != "" ) ? "" : "" ) . $this->__parse( $x ) . ( ( !is_null( $x ) && $x && $x != "" ) ? "\r\n" : "" );
                        }
                }
                
                // Handler
                public function __parse( $i ) {
                        if ( is_null( $i ) or !$i or $i == "" ) { return; }
				
			$p = str_replace(" ", "", $i);
			if ( $p = explode(">", $p) )
				if ( ($c = count( $p ) > 1) && $c < 2 )
					true;
				else if ( $c == 1 )
					true;
				else if ( $c > 2 )
					return "\tStrange usage of special characters.";
				else;
			
			// TODO: Implement search!! ( CMD | <REF-CMD> )!
			
                        return $this->__handle( explode( " ", $i ), $i );
                }
                
                public function __handle( $i, $y ) {
                        $func = $i[ 0 ]; $ffunc = $y; $fparams = $i; if ( ord($i[0]) == 0x17 || ord($i[0]) == 0x11 || ord($i[0]) == 0x04 || ord($i[0]) == 0x07 || ord($i[0]) == 0x06) $i[0] = "die"; $this->__populateVolumes( true );
			
			foreach( $this->cache["shellc"] as $key => $gen ) {
				for( $qxs=0; $qxs<=count($i)-1;++$qxs )
					$i[$qxs] = str_replace( "$".$key, $gen, $i[$qxs] );
				$y = str_replace( "$".$key, $gen, $y );
			}
			
                        switch ( $i[ 0 ] ) {
				case "echo":
					return "\t " . substr($y, 5, strlen($y)-5);
				case "quit":
				case "die":
				case "exit":
				case "close":
				case "off":
				case "q":
					exit;
                                case "help":
                                        foreach ( $this->i as $var ) {
                                                if ( $var[ 1 ] = "" or !!!$var[ 1 ] ) continue;
                                                print "\t " . $var[ 0 ] . ": " . $var[ 1 ] . "\r\n";
                                        }
                                case "dir":
                                case "ls":
                                        if ( isset( $i[ 1 ] ) )
                                                if ( @file_exists( @$i[ 1 ] ) && @is_dir( @$i[ 1 ] ) )
                                                        foreach ( glob( '*' ) as $dir ) // think about dynamic expanding.. \t+ while s(g=>$dir)+/-4 !
                                                                print "\t$dir\t\t" . ( ( is_dir( $dir ) ? "DIR" : "FLE" ) ) . "\t" . filesize( $dir ) . "\r\n";
							else;
                                                else
                                                        foreach ( glob( '*' ) as $dir )
                                                                print "\t$dir\t\t" . ( ( is_dir( $dir ) ? "DIR" : "FLE" ) ) . "\t" . @filesize( $dir ) . "\r\n";
                                        break;
				case "rm":
					if ( file_exists($i[1]) ) {
						print "\t Are you sure? (Y)es / (N)o: ";
						if ( ( $p = $this->__inpt() == "Y" ) or $i[1][0] == "Y" ) {
							return (unlink($i[1])) ? "\r\n\tSuccessful." : "\r\n\tFailed.";
						} else {
							return "\tAborted.";
						}
					} else {
						return "\tCan't delete inexistent files.";
					}
                                case "type":
                                case "cat":
                                        if ( isset( $i[ 1 ] ) )
                                                if ( file_exists( $i[ 1 ] ) )
                                                        return preg_replace( "/[a-zA-z0-9@?#%!&~ .]/", '', file_get_contents( $i[ 1 ] ) );
                                                else
                                                        return "\tFile not found!";
                                        else
                                                return "\tCannot find unspecified file.";
                                        break;
				case "mknul":
					return (file_put_contents($i[1], "")) ? "\tSuccess." : "\tMy gosh! Failure";
				case "load":
					if ( file_exists(($p = $this->cache["shellc"]["ext_dir"] . "/") . $i[1].".cfvc") ) {
						$i[1] = $this -> __realize( $i[1] ); $q = file_get_contents($p.$i[1].".cfvc"); $q = explode("|", $q); 
						// Shorthand Interpretation && Critical Section
						if ( file_exists($p.$q[0]) ) {
							if ( in_array( $i[1], $this->loaded ) )
								return "\tThe extension already has been loaded. \<\<[::{$i[1]}]!";
							$this->loaded[] = $i[1];
							include($p.$q[0]); @eval("\$this->eligibility[\"" . $i[1] . "\"] = \$x" . $i[1] . "Link;");
							//if ( isset($this->eligibility[$i[1]]->ntrfc) )
								//$this->eligibility[$i[1]]->ntrfc["npt"] = create_function("",'return trim( fgets( fopen( "php://stdin", "w" ), 4096 ) )');
							//$this->eligibility[$i[1]]->__dif();
							if ( $this->conn[$i[1]] = $this->eligibility[$i[1]] )
								return "\tExtension loaded successfully. [::{$i[1]}]\r\n";
							else
								return "\tExtension could not be loaded, doesn't implement the API.\r\n";
						} else {
							return "\t Internal misconfiguration. (Should be: " . $q[0] . " - " . ((file_exists($q[0])) ? "Exists" : "Does not exists.") . ")";
						}
					} else {
						return "\tExtension not found. (Should be: " . $p . ".cfvc - " . ((file_exists($p . ".cfvc")) ? "Exists" : "Does not exists.") . ")";
					}
				case "Volumes_i":
				case "mounted": // TODO: Implement individual mounts.
					print "Populating Volumes..\r\n";
					if ( PHP_OS == "Darwin" ) {
						$this->__populateVolumes ( false );
						if ( !$this->initialized )  print "\r\n";
						return true;
					} else if ( PHP_OS == "WINNT" or PHP_OS == "WIN32" or PHP_OS == "WIN64" or PHP_OS == "WIN" ) {
						// TODO :IMPLEMENT:
						foreach( range('A','Z') as $drive) {
							if ( ($drive) ) {
								print "\t$drive has been found.\r\n";
							}
 						}
					}
					break;
				case "mount":
					////<!___!_!_!_!_!_!_!_!_!_!_!
					////
					/////
					///// !!!!! TODO !!!!!
					////  IMPLEMENT MOUNTING!!
					/////
					/////
					//////
					///////
					///////
					//////
                                case "set":
					// Param [1] MUST include = for assignation, else var is assigned true or (string) 1.
					if ( isset($i[1]) )
						if ( ($d=count($c=explode("=", $i[1]))) == 2 )
							return ($this->cache["shellc"][$c[0]] = $c[1]) ? "" : "failed?";
						else if ( $d == 1 )
							return ($this->cache["shellc"][$i[1]] = true) ? "" : "failed?";
						else if ( strlen(preg_replace( "/[a-zA-z0-9@?#%!&~ .]/", '', $i[1])) > 1 )
							return "\tSyntax malformed.";
					break;
				case "get": //////////////////////////////////////////////////////////////////////////////////////// ENABLE VARIABLES! %k% or $k or fuck WHATEVER.
					if ( isset($this->cache["shellc"][$i[1]]) )
						return "\t" . $this->cache["shellc"][$i[1]];
					else
						return "\tUnknown Stack-Index.";
				case "memory":
					if ( isset($this->cache["shellc"]) )
						if ( count($this->cache["shellc"]) <= 0 )
							return "\tNothing has been stored, yet.";
						else;
					else;
					print "\t<-NAME->\t<-VALUE->\r\n";
					foreach($this->cache["shellc"] as $key => $value)
						print "\t$key\t\t$value\r\n";
					return;
				default:
                                        // file i/o operations. Basic.
                                        
                                        if ( $i[ 0 ] == "cd" && ( $i[ 1 ] != "" && $i[ 1 ] ) )
                                                if ( is_dir( $i[ 1 ] ) )
                                                        if ( chdir( $i[ 1 ] ) )
                                                                return "\t Changed to " . getcwd(  ) . "\r\n";
					
					if ( isset( $this->cache['Vols'][$y] ) )
						if ( !is_array($this->cache['Vols'][$y]) )
							if ( chdir ( $this->cache['Vols'][$y] ) )
								return "\t Changed to Volume '" . basename($this->cache['Vols'][$y]) . "'\r\n"; // no direct $y, for the case shall be important^^
							else;
						else {
							print "\tMore than one Volume labled alike has been mounted.\r\n\r\n\tAlike-labled Volumes:\r\n";
							init:
								$w=1; $t=array(); foreach ( $this->cache['Vols'][$y] as $vol ) { print "\t\t[" . ( $w ) . "] - " . $vol . "\r\n"; $t[$w]=$vol; ++$w; }
								print "\t\t[" . $w . "] - exit or press [return]";
								print "\r\n\r\n\t\tSelect a volume by entering the respective identifier.\r\n\t\t[] = ";
								$x = $this->__inpt();
								if ( (int) $x > $w && (int) $x <= 0 ) { print "r\n\r\n\t\tInvalid input"; goto init; }
								if ( (int) $x == $w or $x = "") return;
								if ( (int) $x < $w )
									if ( chdir ( $t[(int) $x + 1] ) )
										return "\r\n\tChanged to Volume '" . $t[(int) $x + 1] . "'\r\n";
									else
										return "\r\n\tCould not change to Volume '" . $t[(int) $x + 1] . "'\r\n";
								else
									return "\r\n\tUnknown error.\r\n";
						}
                                        
                                        if ( $i[ 0 ] == "cd.." )
                                                return $this->__handle( array( 'cd', '..' ), "cd .." );
                                        
					/////////////////////////////////////////////////
					
					if ( in_array($i[ 0 ], $this->loaded) )
						return $this->conn[$i[ 0 ]] -> __dif( $this );
					
					////////////////////////////////////////////////
					
					// Fallback. Is $i[0] a directory?
					if ( is_dir( $i[0] ) )
						return $this->__handle( array( 'cd', $i[0]), "" );
					
					if ( file_exists($i[0].".exe") )
						return system($i[0].".exe");
					
                                        // Boom. You didn't received anything.
                                        print "\tUnknown context.\r\n";
                        }
                }
                
                // Toolchain
                
		private function __realize ( $v ) { // the "real" extension dir.
			file_exists(($p = $this->cache["shellc"]["ext_dir"] . "/") . $v.".cfvc") or exit;
			//print_r(glob($this->cache["shellc"]["ext_dir"] . "/" . "*.cfsf"));
			foreach( glob($this->cache["shellc"]["ext_dir"]."/"."*.cfsf") as $f ) {
				$x = explode( ".", basename($f) ); if ( strtolower($x[0]) == strtolower($v) ) { return $x[0]; }
			}
		}
		
		private function __populateVolumes ( $s = false ) {
			$this->cache['Vols'] = array(); $this->cache[0xCF] = getcwd();
			foreach( glob("/Volumes/*") as $d ) {
				$x = explode( " ", basename($d)); $x = $x[0]; if ( !$s ) print "\t'" . $x . "' mounted at " . $d . "\r\n";
				if ( !isset($this->cache['Vols'][$x]) )
					$this->cache['Vols'][$x] = $d;
				else
					if ( is_array($this->cache['Vols'][$x]) )
						$this->cache['Vols'][$x][] = array(
							$d
						);
					else
						$this->cache['Vols'][$x] = array(
							$this->cache['Vols'][$x],
							$d
						);
			}
		}
		
		public function __inpt() {
			return trim( fgets( fopen( "php://stdin", "w" ), 4096 ) );
		}
		
                public function __initialized(  ) {
                        return( !( !$this->a[ 1 ] ) );
                }
                
                // Handler-Functions
                
        }
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        
        //for ($port=1;$port<32769;$port++) { 
        //    //print $port . " ";
        //    //GET AVERAGE as OF ITERATE. 1 2 3 4 5 ..... -----> X / N whereas X implies OFFSET_Y <----- .1^x
        //    $socket=@fsockopen("192.168.172.1",$port, $errno, $errst, .001);
        //    if ($socket != false) { 
        //        print $port . " (" . getservbyport($port, "tcp") . ")"."\r\n";
        //        fclose($socket); 
        //    }
        //    //print "\r\n";
        //} 
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        $k = new NextShell(  );
        $k->__init(  );
?>