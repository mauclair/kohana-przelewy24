<?php defined('SYSPATH') or die('No direct script access.');

class Przelewy24 extends Kohana_Przelewy24 {
	
	public function verify($session_id, $order_id, $price)
	{
		$data = array();
		$data["p24_id_sprzedawcy"] = Kohana::$config->load('przelewy24.id');
		$data["p24_session_id"] = $session_id;
		$data["p24_order_id"] = $order_id;
		$data["p24_kwota"] = $price;
		$data["p24_crc"] = md5($session_id . "|" . $order_id . "|" . $price . "|" . Kohana::$config->load('przelewy24.key_crc'));
		
		$request = Request::factory("https://secure.przelewy24.pl/transakcja.php");
		$request->post($data);
		$request->method(Request::POST);
		
		$response = $request->execute();
		var_dump($response->body());
		exit;
		
		$T = explode(chr(13).chr(10),$result);
		$res = false;
		foreach($T as $line){
			$line = ereg_replace("[\n\r]","",$line);
			if($line != "RESULT" and !$res) continue;
			if($res) $RET[] = $line;
			else $res = true;
		}
		return $RET;
	}
	
}
