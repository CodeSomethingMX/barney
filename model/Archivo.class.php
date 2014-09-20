<?php

use Illuminate\Database\Eloquent\Model as Model;

class Archivo extends Model {

	protected 	$table		=	'archivo';
	protected 	$primaryKey =	'archivo_id';
	public 		$timestamps =	false;

	public function tipoArchivo() {
		return $this->belongsTo('TipoCurso');
	}

	public function lecciones(){
		return $this->belongsToMany('Leccion');
	}
}