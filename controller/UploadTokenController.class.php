<?php

	use api\UploadApi;
	use api\UploadTokenApi;
	use lib\Controller;
	use object\Upload;
	use object\UploadToken;

	/** 
	 * Class with collection of methods to manage tokens and their other dependencies
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 */
	Class UploadTokenController extends Controller {

		/** 
		 * Record listing page by token page
		 * @param String $token
		 * @access public 
		 */ 
		public function index( $token ){

			$api = new UploadTokenApi();
			$obj = new UploadToken();
			$obj->token = $token;
			$reponseToken = json_decode( $api->getByToken( $obj, true ) );
			$this->validToken = $reponseToken->is_valid;
			$this->token = $token;

			if( $this->validToken ){
				
				$objTableUpload = new Upload();
				$this->keyCollums = $objTableUpload;
				$objTableUpload->id_token = $reponseToken->id_token;


				$uploadApi = new UploadApi();
				$this->grossRevenue = number_format( $uploadApi->countGrossRevenueByIdToken($objTableUpload) , 2);
				$this->records = json_decode( $uploadApi->listRecords( $objTableUpload ) );

				foreach( $this->records as $record){
					unset($record->id);
					unset($record->token_id);
					unset($record->created_at);
					unset($record->deleted_at);
				}

				unset( $this->keyCollums->table_name );
				unset( $this->keyCollums->id_token );
				unset( $this->keyCollums->token_id );
				$this->setTitle('Registros do Token: ' . $token);
				$this->view('token');

			} else {

				$this->setTitle('Token Inválido');
				$this->view('shared/token404');

			}

		}

		/** 
		 * Method dedicated to get one token for upload
		 * @access public 
		 * @return Json $response
		 */
		public function createToken(){

			$api = new UploadTokenApi();
			$response = $api->createToken( new UploadToken('POST', null, true) );
			echo $response;

		}

		/** 
		 * Method dedicated to validate token, return token if exist by token
		 * @access public 
		 * @param String [$token]
		 * @return Json $response
		 */
		public function getTokenByToken( $token = "" ){

			$api = new UploadTokenApi();
			$obj = new UploadToken();
			$obj->token = $token;
			$response = $api->getByToken( $obj );
			echo $response;

		}

		/** 
		 * Method dedicated to list all tokens
		 * @access public 
		 * @return Json $response
		 */
		public function listToken(){

			$api = new UploadTokenApi();
			$response = $api->listTokens( new UploadToken() );
			echo $response;

		}

	}

?>