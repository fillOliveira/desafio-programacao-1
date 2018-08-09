<?php

	namespace lib;

	/** 
	 * Class with collection of methods for connection and requisitions in SGBD
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 * @package lib
	 */
	Class Model extends Config {

		/**
	     * Connection on SGBD
	     */
		protected $con;
		
		/** 
		 * Constructor, initiates the connection with SGBD
		 * @access public 
		 */ 
		public function __construct(){

			try {
				$this->con = new \PDO("mysql:host=". self::srvHost. ";dbname=". self::srvDbName, self::srvUser, self::srvPass );
				$this->con->exec( "SET NAMES ". self::charset );
			} catch( \PDOException $e ){
				die( $e->getMessage() );
			}

		}

		/** 
		 * Get row on SGBD
		 * @access public 
		 * @param SQL $sql
		 * @return Object/null
		 */ 
		public function get( $sql ){

			try {
				$sql = $this->con->prepare($sql);
				$sql->execute();
			} catch( \PDOException $e ){
				die( "Model: " . $e->getMessage() );
			}

			$arr = Array();
			while( $row = $sql->fetchObject() ){
				return $row;
			}
			return null;
			
		}

		/** 
		 * List rows on SGBD
		 * @access public 
		 * @param SQL $sql
		 * @return Array
		 */ 
		public function list( $sql ){
			
			$response = false;
			try {
				$sql = $this->con->prepare($sql);
				$response = $sql->execute();
			} catch( \PDOException $e ){
				die( "Model: " . $e->getMessage() );
			}

			if( $response ){
				$arr = Array();
				while( $row = $state->fetchObject() ){
					$arr[] = $row;
				}
				return $arr;
			} else {
				echo "\nPDOStatement::errorInfo():\n";
				echo '<pre>';
				print_r( $sql->errorInfo() );
				return null;
			}

		}

		public function listAll( $table ){

			$table = addslashes($table);
			
			$sql = "SELECT token, created_at as timestamp FROM {$table} ORDER BY id DESC";
			$response = false;
			try {
				$sql = $this->con->prepare($sql);
				$response = $sql->execute();
			} catch( \PDOException $e ){
				die( "Model: " . $e->getMessage() );
			}

			if( $response ){
				$arr = Array();
				while( $row = $sql->fetchObject() ){
					$arr[] = $row;
				}
				return $arr;
			} else {
				echo "\nPDOStatement::errorInfo():\n";
				echo '<pre>';
				print_r( $sql->errorInfo() );
				return null;
			}

		}

		/** 
		 * Insert row on SGBD
		 * @access public 
		 * @param Array $obj
		 * @param String $table
		 * @return Object
		 */ 
		public function post( $obj, $table ){

			foreach( $obj as $key => $value ){
				if( $value != null ){
					$obj->$key = "'". $value ."'";
				} else {
					$obj->$key = 'NULL';
				}
			}

			try {
				$sql = "INSERT INTO {$table} ( `". implode( "`,`", array_keys( (array)$obj ) ) ."` ) VALUES (". implode( ",", array_values( (array)$obj ) ) .")";
				$sql = $this->con->prepare($sql);
				$sql->execute();
			} catch( \PDOException $e ){
				die( "Model: " . $e->getMessage() );
			}

			return (Object)[
				'success' => true,
				'id' => $this->getLastId( $table ),
			];
			
		}

		/** 
		 * Update rows on SGBD
		 * @access public 
		 * @param Array $obj
		 * @param Array $condition
		 * @param String $table
		 * @return Array
		 */ 
		public function put( $obj, $condition, $table ){

			try {

				$data = [];
				$where = [];

				foreach( $obj as $key => $value ){
					$data[] = "`{$key}` = '{$value}'";
				}

				foreach( $condition as $key => $value ){
					$where[] = "`{$key}` ". (is_null($value) ? " IS NULL " : " = '"{$value} )."'";
				}

				$sql = "UPDATE {$table} SET ". implode(",", $data) ." WHERE ". implode(' AND ', $where )	;
				$sql = $this->con->prepare($sql);
				$sql->execute();

			} catch( \PDOException $e ){
				die( "Model: " . $e->getMessage() );
			}

			return [
				'success' => true,
			];
			
		}

		/** 
		 * Get Last Id Row on SGBD
		 * @access public 
		 * @param String $table
		 * @return Int
		 */ 
		public function getLastId( $table ){

			try {
				$sql = "SELECT id as last FROM {$table} ORDER BY id DESC LIMIT 1;";
				$sql = $this->con->prepare($sql);
				$sql->execute();
				$response = $sql->fetchObject();
			} catch( \PDOException $e ){
				die( "Model: " . $e->getMessage() );
			}

			return isset($response->last) ? $response->last : 0;

		}

		/** 
		 * Get Last Row on SGBD
		 * @access public 
		 * @param String $table
		 * @return Object $response
		 */ 
		public function getLastRow( $table ){

			try {
				$sql = "SELECT * FROM {$table} ORDER BY id DESC LIMIT 1;";
				$sql = $this->con->prepare($sql);
				$sql->execute();
				$response = $sql->fetchObject();
			} catch( \PDOException $e ){
				die( "Model: " . $e->getMessage() );
			}

			return $response;

		}

		/** 
		 * Count Rows in Table Upload by Token Id
		 * @access public 
		 * @param Int $id
		 * @return Object $response
		 */ 
		public function countRowsByTokenId( $id ){

			$id = addslashes($id);

			try {
				$sql = "SELECT count(*) as rows FROM upload WHERE token_id='{$id}'";
				$sql = $this->con->prepare($sql);
				$sql->execute();
				$response = $sql->fetchObject();
			} catch( \PDOException $e ){
				die( "Model: " . $e->getMessage() );
			}

			return $response;

		}

		/** 
		 * List Rows in Table Upload by Token Id
		 * @access public 
		 * @param Int $id
		 * @return Object $response
		 */ 
		public function listRowsByTokenId( $id ){

			$id = addslashes($id);

			try {
				$sql = "SELECT * FROM upload WHERE token_id='{$id}'";
				$sql = $this->con->prepare($sql);
				$sql->execute();
			} catch( \PDOException $e ){
				die( "Model: " . $e->getMessage() );
			}
			if( $sql ){
				$arr = Array();
				while( $row = $sql->fetchObject() ){
					$arr[] = $row;
				}
				return $arr;
			} else {
				echo "\nPDOStatement::errorInfo():\n";
				echo '<pre>';
				print_r( $sql->errorInfo() );
				return null;
			}

			return $response;

		}

		/** 
		 * Count Rows in Table Upload by Last Token Created
		 * @access public 
		 * @param String $table
		 * @return Int
		 */
		public function countRowsByLastToken( $table = 'upload_token' ){

			$lastId = $this->getLastId( $table );
			$rows = $this->countRowsByTokenId($lastId);
			return $rows->rows;

		}

		/** 
		 * Calculate Gross Revenue by Token Id
		 * @access public 
		 * @param Int $id
		 * @return Int
		 */
		public function getGrossRevenueByIdToken( $id ){

			$id = addslashes($id);
			
			try {
				$sql = "SELECT SUM(item_price * purchase_count) as count FROM upload WHERE token_id='{$id}'";
				$sql = $this->con->prepare($sql);
				$sql->execute();
				$response = $sql->fetchObject();
			} catch( \PDOException $e ){
				die( "Model: " . $e->getMessage() );
			}

			return $response->count;

		}

	}

?>