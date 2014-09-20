<?php

use Illuminate\Database\Eloquent\Model as Model;

class Unidad extends Model {

	protected 	$table		=	'unidad';
	protected 	$primaryKey =	'unidad_id';
	public 		$timestamps =	false;

	public function curso() {
		return $this->belongsTo('Curso', 'curso_id', 'curso_id');
	}

	public function lecciones() {
		return $this->hasMany('Leccion', 'unidad_id', 'unidad_id');
	}

	public function archivos () {
		return $this->belongsToMany('Archivo', 'archivoUnidad');
	}
}