<?php

	namespace object;
	use lib\Object;

	/** 
	 * Class of representation of the table "upload" in the database
	 * 
	 * @author Felipe Oliveira <felipe.wget@gmail.com>
	 * @version 0.1
	 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
	 * @access public 
	 * @package object
	 */
	Class Upload extends Object {

		/** 
	     * @var Represents the table name
	     */
		public $table_name = 'upload';

		/** 
	     * @var Represents the "purchaser_name" column of the table
	     */
		public $purchaser_name;

		/** 
	     * @var Represents the "item_description" column of the table
	     */
		public $item_description;

		/** 
	     * @var Represents the "item_price" column of the table
	     */
		public $item_price;

		/** 
	     * @var Represents the "purchase_count" column of the table
	     */
		public $purchase_count;

		/** 
	     * @var Represents the "merchant_address" column of the table
	     */
		public $merchant_address;

		/** 
	     * @var Represents the "merchant_name" column of the table
	     */
		public $merchant_name;

		/** 
	     * @var Represents the "token_id" column of the table
	     */
		public $token_id;

	}

?>