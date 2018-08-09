<?php
	
	namespace api;
	use lib\Model;
	use object\UploadToken;

	/** 
	 * API class with collection of methods related to the Upload_token table and tokens management
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 * @package api
	 */
	Class UploadTokenApi extends Model {

		/** 
		 * Method creates a new token or if it is available but not used, reuses it
		 * @access public 
		 * @param Object UploadToken $obj
		 * @return Json $response
		 */
		public function createToken( UploadToken $obj )
		{
			
			$response = [
				'cod' => 200,
				'token' => $obj->token,
				'timestamp' => $obj->created_at,
				'recycle' => false,
			];

			$table_name = $obj->table_name;
			unset( $obj->table_name );

			$oldToken = $this->recycleToken();	

			if( $oldToken == null && $this->getLastId($table_name) > 0 ){

				$query = $this->getLastRow( $table_name );
				$response['token'] = $query->token;
				$response['timestamp'] = $query->created_at;
				$response['recycle'] = true;

			} else {

				$query = $this->post( $obj, $table_name );
				if( !( $query->success && $query->id > 0 ) ){
					die(' Erro ao criar token de upload ');
				}

			}

			return json_encode( $response );
			
		}

		/** 
		 * Method verifies that the token is valid
		 * @access public 
		 * @param Object UploadToken $token
		 * @param Bool [$returnId]
		 * @return Json $response
		 */
		public function getByToken( UploadToken $token, $returnId = false )
		{

			$response = [
				'cod' => 200,
				'token' => null,
				'is_valid' => true,
			];

			$token->token = addslashes($token->token);
			$table_name = $token->table_name;
			unset($token->table_name);
			$sql = "SELECT * FROM {$table_name} WHERE token='{$token->token}'";
			$query = $this->get( $sql );

			if( !isset($query->token) ){
				$response = [
					'cod' => 400,
					'is_valid' => false,
				];
			} else {
				$response['token'] = $query->token;
				if( $returnId ){
					$response['id_token'] = $query->id;
				}
			}

			return json_encode($response);
			
		}

		/** 
		 * Method list all tokens
		 * @access public 
		 * @param Object UploadToken $obj
		 * @return Json $response
		 */
		public function listTokens( UploadToken $obj )
		{

			$response = $this->listAll( $obj->table_name );
			$response = [
				'cod' => 200,
				'tokens' => $response
			];

			return json_encode($response);
		}

		/** 
		 * Method verifies that the token must create a new token or reuse the old one
		 * @access public 
		 * @return Null/Int $response
		 */
		private function recycleToken(){

			$response = null;
			$rowByLastToken = $this->countRowsByLastToken();
			if( $rowByLastToken > 0 ){
				$response = $rowByLastToken;
			}

			return $response;

		}

	}

?>