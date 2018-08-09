<?php

	use helper\File;
	use api\UploadApi;
	use api\UploadTokenApi;
	use lib\Controller;
	use controller\UploadTokenController;
	use object\Upload;
	use object\UploadToken;

	/** 
	 * Class with collection of methods for managing file uploads and file records
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 */
	Class UploadController extends Controller {

		/** 
		 * Main page, view where the user can view created tokens and upload files
		 * @access public 
		 */ 
		public function index(){

			$tokenApi = new UploadTokenApi();
			$tokenList = $tokenApi->listTokens( new UploadToken() );
			$this->tokenList = ( $tokenList == null) ? [] : json_decode($tokenList)->tokens;

			$this->setTitle('Upload de Arquivos .Tab');
			$this->view('upload');
			
		}

		/** 
		 * Method dedicated to validate upload $ _FILE or base64, validate and upload
		 * @access public 
		 * @param String $token
		 * @return Json $response
		 */
		public function upload( $token ){

			$obj = new UploadToken();
			$obj->token = $token;

			$uploadApi = new UploadApi();
			$tokenApi = new UploadTokenApi();
			
			$checkToken = json_decode($tokenApi->getByToken( $obj, true ));
			if( !$checkToken->is_valid ){
				$response = [
					'cod' => 400,
					'message' => 'Não foi possível adicionar registros pois o token inválido'
				];
				echo json_encode($response);
				return null;
			}


			$helperFile = new File();
			if( isset($_FILES['upload']) ){
				$responseUpload = $helperFile->uploadToPath();
			} else {
				// Create do .tab
				if( isset($_POST['base64_file']) ){
					try {
						$arrBase64 = explode('data:;base64,', $_POST['base64_file'] );
						if( isset( $arrBase64[1] ) ){

							$text = base64_decode( $arrBase64[1] );
							$archiveName = base64_encode( Date('dmYH:i:s') );
							$responseUpload = $helperFile->createFileTab( $archiveName, $text );

						} else {

							$response = [
								'cod' => 400,
								'message' => 'Arquivo inválido'
							];
							echo json_encode($response);
							return null;
							
						}
						
					} catch( Exception $e ){
						$response = [
							'cod' => 400,
							'message' => 'Arquivo enviado via drop não está em base64'
						];
						echo json_encode($response);
						return null;
					}
				} else {
					$response = [
						'cod' => 400,
						'message' => 'Envie um arquivo para o upload'
					];
					echo json_encode($response);
					return null;
				}
			}

			$response = $helperFile->readFileTab( $responseUpload['fullPath'] );
			if( $response['is_valid'] ){
				echo json_encode($uploadApi->processArchive( $checkToken->id_token, $response['rows'] ));
			} else {
				unset( $responseUpload['fullPath'] );
				echo json_encode($response);
			}

		}

	}

?>