<?php

use Illuminate\Database\Eloquent\Model as Model;

class Pregunta extends Model {

	protected 	$table		=	'pregunta';
	protected 	$primaryKey =	'pregunta_id';
	public 		$timestamps =	false;

	public function respuestas() {
		return $this->hasMany('Respuesta', 'pregunta_id');
	}

	public function perfil () {
		return $this->belognsTo('Perfil');
	}
}