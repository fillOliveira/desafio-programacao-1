<?php

	namespace object;
	use lib\Object;

	/** 
	 * Class of representation of the table "upload_token" in the database
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 * @package object
	 */
	Class UploadToken extends Object {

		/** 
	     * @var Represents the table name
	     */
		public $table_name = 'upload_token';

		/** 
	     * @var Represents the "token" column of the table
	     */
		public $token;

		/** 
	     * @var Represents the "created_at" column of the table
	     */
		public $created_at;

		/** 
	     * @var Represents the "deleted_at" column of the table
	     */
		public $deleted_at;

		/** 
		 * Constructor
		 * @access public 
		 * @param Bool $createToken
		 */ 
		public function __construct( $methods = null, $all = null, $createToken = false ){

			if( $createToken ){
				$this->token = $this->setToken();
				$this->created_at = $this->setCreated();
			}

		}

		/** 
		 * Adds a dynamic token to the table representation
		 * @access public 
		 */ 
		public function setCreated(){
			return Date('Y-m-d H:i:s');
		}

		/** 
		 * Adds the timestamp representing the table
		 * @access public 
		 */ 
		public function setToken(){
			return bin2hex(random_bytes(10)).Date('HidY');;
		}

	}

?>