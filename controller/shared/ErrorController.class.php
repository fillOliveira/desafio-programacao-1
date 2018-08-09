<?php

	use lib\Controller;

	/** 
	 * Class with collection of methods for errors page
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 */
	Class ErrorController extends Controller {

		/** 
		 * Error 404 page, start the view
		 * @access public 
		 */ 
		public function page404(){

			$this->setTitle('Token Inválido');
			$this->view('shared/page404');

		}

	}

?>