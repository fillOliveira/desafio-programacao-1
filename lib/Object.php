<?php

	namespace lib;

	/** 
	 * Class Dedicated to Instantiating Received Parameters
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 * @package lib
	 */
	Class Object {

		/** 
		 * Constructor
		 * @access public 
		 * @param String $method
		 * @param Bool $all
		 */ 
		public function __construct( $method = null, $all = false ){

			if( $method == 'POST' OR $all ){
				foreach($_POST as $ind => $value){
					$this->$ind = trim($value);
				}
			}

			if( $method == 'PUT' OR $all ){
				foreach($_PUT as $ind => $value){
					$this->$ind = trim($value);
				}
			}

			if( $method == 'DELETE' OR $all ){
				foreach($_DELETE as $ind => $value){
					$this->$ind = trim($value);
				}
			}

			if( isset($_FILES) ){
				foreach($_FILES as $ind => $value){
					$this->$ind = $value;
				}
			}

		}


	}

?>