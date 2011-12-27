<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Model_Przelewy24 extends ORM {
	
	protected $_table_names_plural = FALSE;
	
	protected $_created_column = array(
		'column' => 'created',
		'format' => 'Y-m-d H:i:s',
	);
	
}
