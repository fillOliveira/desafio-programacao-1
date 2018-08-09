<?php

	namespace lib;

	/** 
	 * Class Dedicated to Application Initialization, Routes, Controllers, Libs and all system
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 * @package lib
	 */
	Class System extends Router {

		private $url;
		private $method;
		private $exploder;
		private $area;
		private $controller;
		private $action;
		private $params;
		private $runController;

		private $isSubdomain;
		private $subdomain;

		public function __construct(){

			$this->getMethod();
			$this->setSubdomain();
			$this->setUrl();
			$this->setExploder();
			$this->setController();
			$this->setParams();

		}

		/** 
		 * Set Url on Link
		 * @access private
		 */
		private function setUrl(){
			$this->url = isset( $_GET['url'] ) ? $_GET['url'] : 'home';
		}

		/** 
		 * Catch the method REST
		 * @access private
		 */
		private function getMethod(){

			$_DELETE = array();
			$_PUT = array();

			if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE')) {
			    parse_str(file_get_contents('php://input'), $_DELETE);
			}
			if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT')) {
			    parse_str(file_get_contents('php://input'), $_PUT);
			}

			$this->method = $_SERVER['REQUEST_METHOD'];

		}

		/** 
		 * Transform Link in Array
		 * @access private
		 */
		private function setExploder(){

			$exploder = explode( '/', $this->url );
			if( end( $exploder ) == null ){
				array_pop( $exploder );
			}
			$this->exploder = $exploder;
		}

		/** 
		 * Check, Treats and Set Subdomain
		 * @access private
		 */
		private function setSubdomain(){

			$url = $_SERVER['SERVER_NAME'];
			$url = str_replace( ['https://', 'http://'], ['', ''], $url);
			if( substr( $url, 0, 4 ) == 'www.' ){
				$url = substr( $url, 4 );
			}

			$extensionsUrl = [
				'.br',
				'.com',
				'.net',
				'.org',
				'.au',
				'.edu',
				'.gov',
				'.co',
				'.nz',
				'.net',
				'.biz',
			];

			$auxExtensions = [];
			foreach( $extensionsUrl as $extension ){
				$auxExtensions[] = '';
			}

			$url = str_replace($extensionsUrl, $auxExtensions, $url);
			$arrUrl = explode('.', $url);
			if( isset($arrUrl[1]) ){
				$this->isSubdomain = true;

				$reverse = array_reverse($arrUrl);
				unset($reverse[0]);

				$this->subdomain = implode('.', array_reverse( $reverse ));

			} else {
				$this->isSubdomain = false;
			}

		}

		/** 
		 * Indetify, Treat and Initialing and Set Controller, Method and Params on Route
		 * @access private
		 */
		private function setController(){

			$controller = null;
			$method = null;


			foreach( $this->routers as $url => $arrController ){

				if( $controller == null && $method == null ){

					$paramsController = Array();

					$arrUrl = explode('/', $url);
					if( end( $arrUrl ) == null ){
						array_pop($arrUrl );
					}

					$nameSubdomain = null;

					if( $this->isSubdomain ){

						if( isset( explode('}.', $url )[1] ) && substr($url, 0, 11) == '{subdomain:' ){
							$subdominio = explode('}.', $url )[0];
							$nameSubdomain = substr( $subdominio, 11, strlen($subdominio) - 1 );
						}

						if( $nameSubdomain != null ){

							if( $this->subdomain == $nameSubdomain ){

								if( count( $arrUrl ) == count( $this->exploder ) ){

									$correctUrl = 1;
									foreach( $arrUrl as $indice => $itemUrl ){

										$url = isset( explode('}.', $itemUrl )[1]) ? explode('}.', $itemUrl )[1] : $itemUrl;
										if( $url == $this->exploder[$indice] OR ( $url[0] == '{' && substr( $url, (strlen($url) - 1) ) == '}' ) == isset($this->exploder[$indice]) ){
											// Encontrou a url

											if( ( $url[0] == '{' && substr( $url, (strlen($url) - 1) ) == '}' ) == isset($this->exploder[$indice]) ){
												$paramsController[] = $this->exploder[$indice];
											}

										} else {
											$correctUrl = 0;
										}
									}

									if( strtolower( $this->method ) == strtolower( $arrController[0] ) ){
										if( $correctUrl == 1 ){
											$arrController = explode('@', $arrController[1]);
											$controller = $arrController[0];
											$method = $arrController[1];
										}
									}

									$paramsController = implode( ',' , $paramsController );

								}

							}

						}

					} else {

						if( count( $arrUrl ) == count( $this->exploder ) ){

							$correctUrl = 1;
							foreach( $arrUrl as $indice => $itemUrl ){

								$url = $itemUrl;
								if( $url == $this->exploder[$indice] OR ( $url[0] == '{' && substr( $url, (strlen($url) - 1) ) == '}' ) == isset($this->exploder[$indice]) ){
									// Encontrou a url

									if( ( $url[0] == '{' && substr( $url, (strlen($url) - 1) ) == '}' ) == isset($this->exploder[$indice]) ){
										$paramsController[] = $this->exploder[$indice];
									}

								} else {
									$correctUrl = 0;
								}
							}

							if( strtolower( $this->method ) == strtolower( $arrController[0] ) ){
								if( $correctUrl == 1 ){
									$arrController = explode('@', $arrController[1]);
									$controller = $arrController[0];
									$method = $arrController[1];
								}
							}

							$paramsController = implode( ',' , $paramsController );

						}

					}

				}

			}

			if($controller == null OR $method == null ){

				foreach( $this->routers404 as $subdomain => $arrController ){

					if( $nameSubdomain != null ){
						if( str_replace(' ', '', $subdomain) == '{subdomain:subdominio}.*' ){
							if( strtolower( $this->method ) == strtolower( $arrController[0] ) ){
								$arrController = explode('@', $arrController[1]);
								$controller = $arrController[0];
								$method = $arrController[1];
							}
						}
					} else {

						if( $subdomain == '*' ){
							
							if( strtolower( $this->method ) == strtolower( $arrController[0] ) ){
								$arrController = explode('@', $arrController[1]);
								$controller = $arrController[0];
								$method = $arrController[1];
							}

						}
					}
				}
				
			}

			if($controller == null OR $method == null ){
				echo " 404 Not Found";
				die();
			}

			$path = "";
			if( isset( explode('/', $controller)[1] ) ){
				$arrController = array_reverse( explode('/', $controller) );
				$controller = $arrController[0];
				unset($arrController[0]);
				$path = implode('/', array_reverse( $arrController ) );
			}

			$this->pathTocontroller = $path;
			$this->controller = $controller;
			$this->method = $method;
			$this->paramsMethod = $paramsController;

		}

		/** 
		 * Indetify, Treat and Initialing $_GET params
		 * @access private
		 */
		private function setParams(){

			if( isset(explode('?', $_SERVER['REQUEST_URI'] )[1]) ){
				
				$paramsGet;
				$getString = explode('?', $_SERVER['REQUEST_URI'] )[1];

				parse_str($getString, $paramsGet);

				$_GET = $paramsGet;

			} else {}

		}

		/** 
		 * Run Controller
		 * @access public
		 */
		public function run(){

			$this->runController = 'controller/'. $this->pathTocontroller .'/'. $this->controller .'.class.php';
			file_exists($this->runController) ? require $this->runController : die('Controller não encontrado');

			$params = $this->paramsMethod;
			$nameClass = (String)$this->controller;
			$this->runController = new $nameClass();
			$act = (String)$this->method;
			$this->runController->$act( $params );

		}

	}

?>