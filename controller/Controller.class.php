<?php

abstract class Controller {
	
	protected $app;
	protected $view;

	public function callAction ( $action, $params = null ) {

		if ( array_key_exists($action, static::$routes) ) {

			Connection::conecting();
			$method = static::$routes[$action];
			return $this->$method( $params );

		}else {
			$this->notFound();
		}

	}

	public function notFound () {
		echo 'Action no encontrada XD';
	}
}
