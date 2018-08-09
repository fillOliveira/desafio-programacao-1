<?php
	
	namespace api;
	use lib\Model;
	use object\Upload;

	/** 
	 * API class with collection of methods related to the Upload table and the file processing for the upload table
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 * @package api
	 */
	Class UploadApi extends Model {

		/** 
		 * Method traverses the rows of files and sends to other methods such as table validations and insertions
		 * @access public 
		 * @param Int $idToken
		 * @param Object-Array $rows
		 * @return Array $response
		 */ 
		public function processArchive( $idToken, $rows){
			unset($rows[0]);

			foreach( $rows as $row ){

				$objTableUpload = new Upload();
				if( isset($objTableUpload->upload)){
					unset($objTableUpload->upload);
				}

				$cont = -1;
				foreach( $objTableUpload as $key => $value ){
					if( $cont > -1 ){
						if( $key == 'token_id'){
							$objTableUpload->$key = $idToken;
						} else {
							$objTableUpload->$key = $row[$cont];
						}
					}
					$cont++;
				}

				if( isset($objTableUpload->table_name) ){
					$this->addRecord($objTableUpload);
				}
			}

			$objTableUpload->id_token = $idToken;
			$grossRevenue = number_format( $this->countGrossRevenueByIdToken($objTableUpload) , 2 );

			$response = [
				'cod' => 200,
				'success' => true,
				'gross_revenue' => $grossRevenue,
			];

			return $response;

		}

		/** 
		 * Method List Records By Token Id
		 * @access public 
		 * @param Object Upload $obj
		 * @return Json $response
		 */
		public function listRecords( Upload $obj ){

			$response = $this->listRowsByTokenId( $obj->id_token );
			return json_encode($response);
			
		}

		/** 
		 * Method calculates gross revenue per token id
		 * @access public 
		 * @param Object Upload $obj
		 * @return Int $response
		 */
		public function countGrossRevenueByIdToken( Upload $obj ){			
			$response = $this->getGrossRevenueByIdToken( $obj->id_token );
			return $response;
		}

		/** 
		 * Method inserts record into table "Upload"
		 * @access public 
		 * @param Object Upload $objTable
		 */
		private function addRecord( $objTable ){

			$tableName = $objTable->table_name;
			unset( $objTable->table_name );

			foreach( $objTable as $key => $value ){
				$objTable->$key = addslashes(trim($value));
			}
			$this->post( $objTable, $tableName );

		}
		
	}

?>