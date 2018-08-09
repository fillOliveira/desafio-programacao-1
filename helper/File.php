<?php

	namespace helper;

	/** 
	 * Call of Collection Methos to File
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 * @package helper
	 */
	Class File {

		/** 
	     * @var path to upload TAB file
	     */
		public $uploaddir = 'public/upload/';

		/** 
		 * Create Tab File by Base64
		 * @access public 
		 * @param String $nameArchive
		 * @param String base64 $content
		 * @return Array $response
		 */ 
		public function createFileTab( $nameArchive, $content ){

			$uploaddir = $this->uploaddir;
			$content = $content;
			$fp = fopen( $uploaddir . $nameArchive .".tab","wb");
			fwrite($fp,$content);
			fclose($fp);

			$response = [
				'cod' => 200,
				'fullPath' => $uploaddir . $nameArchive .".tab",
			];

			return $response;

		}

		/** 
		 * Upload archive to path
		 * @access public 
		 * @return Array $response
		 */ 
		public function uploadToPath(){

			$uploaddir = $this->uploaddir;
			$uploadfile = $uploaddir . basename(Date('YmdHis').'_'.$_FILES['upload']['name']);
			
			// Validar extensão, 
			// Mimetype
			// Não deixar executar o arquivo

			$response = [
				'cod' => 200,
				'fullPath' => $uploadfile
			];

			if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploadfile)) {
			    // echo "Arquivo válido e enviado com sucesso.\n";
			} else {
			    $response = [
					'cod' => 400,
					'message' => 'Erro ao fazer o Upload',
				];
			}

			return $response;

		}

		/** 
		 * Method scroll line by line by checking file
		 * @access public 
		 * @param String $fullPath
		 * @return Array
		 */ 
		public function readFileTab( $fullPath ){

			$dataCollums = [];
			$count = 0;
			$fh = fopen( $fullPath ,'r');
			while ($line = fgets($fh)) {
				
				$arrCollums = explode('	', $line);
				$response = $this->validateTabRow( $count , $arrCollums );
				$count++;

				if( !$response['is_valid'] ){
					return $response;
				}

				$dataCollums[] = $response['row'];

			}

			return [
					'is_valid' => true,
					'rows' => $dataCollums,
				];

		}

		/** 
		 * Checks a line of the tab file
		 * @access private
		 * @param Int $nFile
		 * @param Array $arrCollums
		 * @return Array
		 */ 
		private function validateTabRow( $nLine, $arrCollums ){

			if( count( $arrCollums ) != 6 ){
				return [
						'is_valid' => false,
						'message' => 'Todos os registros do arquivo devem ter 6 colunas',
					];
			}
			if( $nLine == 0 ){

				if( trim($arrCollums[0]) == 'purchaser name' &&
					trim($arrCollums[1]) == 'item description' &&
					trim($arrCollums[2]) == 'item price' &&
					trim($arrCollums[3]) == 'purchase count' &&
					trim($arrCollums[4]) == 'merchant address' &&
					trim($arrCollums[5]) == 'merchant name'
					){
					return [
						'is_valid' => true,
						'row' => $arrCollums,
					];
				} else {
					return [
						'is_valid' => false,
						'message' => 'A primeira linha(do cabeçalho) está com valores incorretos',
					];
				}

			} else {

				if( is_numeric($arrCollums[2]) && is_numeric($arrCollums[3]) ){

					return [
						'is_valid' => true,
						'row' => $arrCollums,
					];

				} else {

					return [
						'is_valid' => false,
						'message' => 'Registro Inválido, a coluna "item price" e "purchase count" deve ser numérico',
					];

				}

			}

		}

	}

?>