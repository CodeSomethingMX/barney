<?php

use Illuminate\Database\Eloquent\Model as Model;

class TipoCurso extends Model {

	protected 	$table		=	'tipoCurso';
	protected 	$primaryKey =	'tipoCurso';
	public 		$timestamps =	false;

	public function cursos() {
		return $this->hasMany('Curso');
	}
}