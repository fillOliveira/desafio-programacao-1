<?php

	/** 
	 * Init System
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 */
	session_start();
	
	define('MODO_DEV', true);
	if( MODO_DEV ){
		define('APP_ROOT', 'localhost/nexaas/');
	}

	require 'helper/Bootstrap.php';
	use lib\System;
	$System = new System();
	$System->run();

?>