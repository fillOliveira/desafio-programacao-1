<?php
	
	/** 
	 * Autoload to Excentials Archives
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 */
	spl_autoload_register(function($class){

		$file = str_replace('\\', '/', $class);

		if( file_exists($file.'.php') ){
			require $file.'.php';
		}

	});

?>