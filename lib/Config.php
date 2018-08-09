<?php

	namespace lib;

	/** 
	 * Class Dedicated to Configuration of Connection With SGBD
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 * @package lib
	 */
	Class Config {

		/** 
	     * @var Hostname on SGBD
	     */
		const srvHost = 'localhost';

		/** 
	     * @var User on SGBD
	     */
		const srvUser = 'root';

		/** 
	     * @var Password on SGBD
	     */
		const srvPass = '';

		/** 
	     * @var Database Name on SGBD
	     */
		const srvDbName = 'nexaas';

		/** 
	     * @var Charset on SGBD
	     */
		const charset = 'utf8';

	}

?>