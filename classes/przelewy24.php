<?php defined('SYSPATH') or die('No direct script access.');

class Przelewy24 extends Kohana_Przelewy24 {
	
	public static $errors = array(
		"err00" => "Nieprawidłowe wywołanie skryptu",
		"err01" => "Nie uzyskano od sklepu potwierdzenia odebrania odpowiedzi autoryzacyjnej",
		"err02" => "Nie uzyskano odpowiedzi autoryzacyjnej",
		"err03" => "To zapytanie było już przetwarzane",
		"err04" => "Zapytanie autoryzacyjne niekompletne lub niepoprawne",
		"err05" => "Nie udało się odczytać konfiguracji sklepu internetowego",
		"err06" => "Nieudany zapis zapytania autoryzacyjnego",
		"err07" => "Inna osoba dokonuje płatności",
		"err08" => "Nieustalony status połączenia ze sklepem",
		"err09" => "Przekroczono dozwoloną liczbę poprawek danych",
		"err10" => "Nieprawidłowa kwota transakcji",
		"err49" => "Zbyt wysoki wynik oceny ryzyka transakcji przeprowadzonej przez PolCard",
		"err51" => "Nieprawidłowe wywołanie strony",
		"err52" => "Błędna informacja zwrotna o sesji",
		"err53" => "Błąd transakcji",
		"err54" => "Niezgodność kwoty transakcji",
		"err55" => "Nieprawidłowy kod odpowiedzi",
		"err56" => "Nieprawidłowa karta",
		"err57" => "Niezgodność flagi TEST",
		"err58" => "Nieprawidłowy numer sekwencji",
		"err101" => "W żądaniu transakcji brakuje któregoś z wymaganych parametrów lub pojawiła się niedopuszczalna wartość",
		"err102" => "Minął czas na dokonanie transakcji",
		"err103" => "Nieprawidłowa kwota przelewu",
		"err104" => "Transakcja oczekuje na potwierdzenie",
		"err105" => "Transakcja dokonana po dopuszczalnym czasie",
		"err161" => "Klient przerwał procedurę płatności wybierając przycisk Powrót na stronie wyboru formy płatności.",
		"err162" => "Klient przerwał procedurę płatności wybierając przycisk Rezygnuj na stronie z instrukcją płatności.",
	);
	
	public static function get_id($id, $price, $email)
	{
		$model = ORM::factory('przelewy24')->where('shop_id', '=', $id)->where('created', '>', DB::expr('DATE_ADD(NOW(), INTERVAL -10 MINUTE)'))->find();
		if (!$model->loaded())
		{
			$model->shop_id = $id;
			$model->price = $price;
			$model->save();
		}
		return $model->pk();
	}
	
	public static function mark_payed($id, $card)
	{
		$model = ORM::factory('przelewy24', $id);
		if ($model->loaded())
		{
			$model->payed = 1;
			$model->card = $card;
			$model->save();
		}
		return $model;
	}
	
	public static function verify($session_id, $order_id)
	{
		$price = ORM::factory('przelewy24', $session_id)->price;
		$data = array();
		$data["p24_id_sprzedawcy"] = Kohana::$config->load('przelewy24.id');
		$data["p24_session_id"] = $session_id;
		$data["p24_order_id"] = $order_id;
		$data["p24_kwota"] = $price;
		$data["p24_crc"] = md5($session_id . "|" . $order_id . "|" . $price . "|" . Kohana::$config->load('przelewy24.key_crc'));
		
		$request = Request::factory('https://secure.przelewy24.pl/transakcja.php');
		$request->client()->options(CURLOPT_SSL_VERIFYHOST, 2);
		$request->client()->options(CURLOPT_SSL_VERIFYPEER, FALSE);
		$request->post($data);
		$request->method(Request::POST);
		
		$response = $request->execute();
		
		$result = array();
		$is_result = false;
		foreach(explode(chr(13).chr(10), $response->body()) as $line){
			$line = ereg_replace("[\n\r]", "", $line);
			if($line != "RESULT" and !$is_result) continue;
			if($is_result)
				$result[] = $line;
			else
				$is_result = true;
		}
		return $result[0] == "TRUE" ? TRUE : $result;
	}
	
	public static function get_shop_id($session_id)
	{
		return ORM::factory('przelewy24', $session_id)->shop_id;
	}
	
}
