<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Przelewy24 extends Controller {
	
	public $przelewy24;
	
	public function before()
	{
		parent::before();
		
		$this->przelewy24 = new Przelewy24();
	}

	public function action_return()
	{
		var_dump($this->przelewy24->verify('asdasdasd', '43534', 100));
	}

}
