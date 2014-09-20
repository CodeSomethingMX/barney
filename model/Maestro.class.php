<?php

use Illuminate\Database\Eloquent\Model as Model;

class Maestro extends Model {

	protected 	$table			=	'maestro';
	protected 	$primaryKey		=	'maestro_id';
	public 		$timestamps 	=	false;

	public function cursos () {
		return $this->belongsToMany('Curso');
	}

	public function perfil () {
		return $this->belongsTo('Perfil', 'maestro_id', 'perfil_id');
	}

	
}