<?php

use Illuminate\Database\Eloquent\Model as Model;

class CursoPerfil extends Model {

	protected 	$table 		=	'curso_perfil';
	public 		$timestamps	=	false;

	public function perfil () {
		return $this->belongsTo('Perfil');
	}

	public function curso () {
		return $this->belongsTo('Curso');
	}
	
}