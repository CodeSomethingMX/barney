<?php

use Illuminate\Database\Eloquent\Model as Model;

class Respuesta extends Model {

	protected 	$table		=	'respuesta';
	protected 	$primaryKey =	'respuesta_id';
	public 		$timestamps =	false;

	public function pregunta() {
		return $this->belongsTo('Pregunta', 'pregunta_id', 'pregunta_id');
	}

	public function perfil () {
		$this->belongsTo('Perfil');
	}
}