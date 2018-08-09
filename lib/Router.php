<?php

	namespace lib;

	/** 
	 * Class Dedicated to the Access Routes of the Application
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 * @package lib
	 */
	Class Router extends Controller {

		/**
	     * Vector with in-app access routes
	     * @var Array
	     */
		protected $routers = Array(
			'' 			 => ['get', 'UploadController@index'],
			'home' 			 => ['get', 'UploadController@index'],
			'upload' 			 => ['get', 'UploadController@index'],
			'{$token}' => ['get', 'UploadTokenController@index'],

			'add-token'		 => ['post', 'UploadTokenController@createToken'], // aki é post
			'{$token}/record' => ['post', 'UploadController@upload'],
			// '{$token}/token' => ['get', 'UploadTokenController@getTokenByToken'],
			// 'token/list' => ['get', 'UploadTokenController@listToken'],
			
			// Subdomain Exemple
			// '{subdomain:EXEMPLE}.EXEMPLE' => ['put', 'admin/LoginController@get'],
			);

		/**
	     * Vector with page paths not found in the application
	     * @var Array
	     */
		protected $routers404 = Array(
			'*' => ['get', 'shared/ErrorController@page404'],
			);

	}

?>