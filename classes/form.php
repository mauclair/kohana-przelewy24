<?php defined('SYSPATH') or die('No direct script access.');

class Form extends Kohana_Form {
	
	public static function open_przelewy24()
	{
		return Form::open('https://secure.przelewy24.pl/index.php');
	}
	
	public static function close_przelewy24()
	{
		return Form::close();
	}
	
	public static function przelewy24($id, $price, $email, $description = NULL)
	{
		$price = $price * 100;
		$id = Przelewy24::get_id($id, $price, $email);
		$crc = array($id, Kohana::$config->load('przelewy24.id'), $price, Kohana::$config->load('przelewy24.key_crc'));
		$crc = md5(implode('|', $crc));
		
		$result = NULL;
		$result .= Form::hidden('p24_session_id', $id);
		$result .= Form::hidden('p24_id_sprzedawcy', Kohana::$config->load('przelewy24.id'));
		$result .= Form::hidden('p24_kwota', $price);
		$result .= Form::hidden('p24_email', $email);
		$result .= Form::hidden('p24_return_url_ok', Route::url('p24_return_ok', NULL, 'http'));
		$result .= Form::hidden('p24_return_url_error', Route::url('p24_return_error', NULL, 'http'));
		$result .= Form::hidden('p24_crc', $crc);
		if ($description !== NULL)
			$result .= Form::hidden('p24_opis', $description);
		return $result;
	}
	
	public static function przelewy24_test($ok, $id, $price, $email, $description = NULL)
	{
		return Form::przelewy24($id, $price, $email, $ok?"TEST_OK":"TEST_ERR");
	}
	
}
