<?php
	
	namespace lib;

	/** 
	 * Class with collection of methods to start and support controller
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 * @package lib
	 */
	Class Controller {

		/** 
	     * @var path to view file
	     */
		private $path;

		/** 
	     * @var Setting the title tag in html
	     */
		protected $title = null;

		/** 
	     * @var Setting the description tag in html
	     */
		protected $description = null;

		/** 
	     * @var Setting the keywords tag in html
	     */
		protected $keywords;

		/** 
	     * @var Setting the flavicon tag in html
	     */
		protected $flavicon;

		/** 
		 * Set local path
		 * @access private 
		 * @param String $path
		 */ 
		private function setPath( $path ){
			
			$this->path = 'view/'.$path.'.phtml';
			$this->fileExists( $this->path );

		}

		/** 
		 * Check if file view exist
		 * @access private 
		 * @param String $file
		 */ 
		private function fileExists( $file ){

			if( !file_exists($file) ){
				die('Não foi localizado o arquivo: ' . $file );
			}

		}

		/** 
		 * Include Controller
		 * @access public 
		 */ 
		public function renderController(){
			include $this->path;
		}

		/** 
		 * Set Tag Title on HTML
		 * @access public 
		 */ 
		public function setTitle( $title ){
			$this->title = $title;
		}

		/** 
		 * Set Tag Keywords on HTML
		 * @access public 
		 */ 
		public function setKeywords( $keywords ){
			$this->keywords = $keywords;
		}

		/** 
		 * Set Tag Description on HTML
		 * @access public 
		 */ 
		public function setDescription( $description ){
			$this->description = $description;
		}

		/** 
		 * Set View File
		 * @access public 
		 */ 
		public function view( $render ){
			$this->setPath( $render );
			$this->renderController();
		}


	}

?>