<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Przelewy24 extends Controller {

	public function action_return()
	{
		if (isset($_POST['p24_error_code']))
		{
			$error = Przelewy24::$errors[$_POST['p24_error_code']];
		}
		else
		{
			if (($status = Przelewy24::verify($_POST['p24_session_id'], $_POST['p24_order_id'], $_POST['p24_kwota'])) !== TRUE)
			{
				$error = $status[2];
			}
		}
		
		$shop_id = Przelewy24::get_shop_id($_POST['p24_session_id']);
		
		if (isset($error))
		{
			$this->error($shop_id, $error);
		}
		else
		{
			$this->ok($shop_id);
		}
	}
	
	public function ok($id)
	{
		$this->response->body($id . ': OK');
	}
	
	public function error($id, $error)
	{
		$this->response->body($id . ': ERROR: ' . $error);
	}

}
