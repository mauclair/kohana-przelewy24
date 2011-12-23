<?php defined('SYSPATH') or die('No direct script access.');

Route::set('p24_return_ok', 'przelewy24/ok')
	->defaults(array(
		'controller' => 'przelewy24',
		'action'     => 'return',
		'error'      => FALSE,
	));

Route::set('p24_return_error', 'przelewy24/error')
	->defaults(array(
		'controller' => 'przelewy24',
		'action'     => 'return',
		'error'      => TRUE,
	));
