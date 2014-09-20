<?php

use Illuminate\Database\Eloquent\Model as Model;

class Perfil extends Model {

	protected 	$table 		=	'perfil';
	protected	$primaryKey =	'perfil_id';
	public 		$timestamps	=	false;

	public function user() {
		return $this->belongsTo('User', 'perfil_id');
	}

	public function cursos () {
		return $this->belongsToMany('Curso');
	}

	public function preguntas () {
		return $this->hasMany('Pregunta');
	}

	public function respuestas () {
		return $this->hasMany('Respuesta');
	}

	public function cursoPerfil () {
		return $this->hasMany('CursoPerfil');
	}
	
}