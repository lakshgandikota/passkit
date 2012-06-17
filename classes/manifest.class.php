<?php if ( ! defined('PRGM')) exit('No direct script access allowed');

/**
 * manifest file creator for passkit.
 *
 * @author Laks Gandikota <laks@wow.com>
 * @copyright Copyright (c) 2012, laksg.com
 * @license Dual licensed under the MIT or GPL Version 2 licenses.
 *
 */
  
class Manifest {
	
	public function arrayoffileswithsha1( $path = '.', $level = 0 )
	{ 
		$JSON_text = array();
		$ignore = array( 'cgi-bin', '.', '..','.DS_Store','manifest.json','signature' ); 
		$dh = @opendir( $path ); 

     
		while( false !== ( $file = readdir( $dh ) ) ){ 
		 
		    if( !in_array( $file, $ignore ) ){ 
		         
		        $spaces = str_repeat( '&nbsp;', ( $level * 4 ) ); 
		         
		        if( is_dir( "$path/$file" ) ){ 

		            $this->arrayoffileswithsha1( "$path/$file", ($level+1) ); 
		         
		        } else { 
		         
		            $JSON_text[$file] = sha1_file("$path/$file");
		         
		        } 
		     
		    } 
		 
		} 
		 
		closedir( $dh ); 
		return $JSON_text;
		// Close the directory handle 
		
    } 
}