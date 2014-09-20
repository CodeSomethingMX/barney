<?php

use Illuminate\Database\Eloquent\Model as Model;

class Leccion extends Model {

	protected 	$table		=	'leccion';
	protected 	$primaryKey =	'leccion_id';
	public 		$timestamps =	false;

	public function unidad() {
		return $this->belongsTo('Unidad', 'unidad_id', 'unidad_id');
	}

	public function archivos() {
		return $this->belongsToMany('Archivo');
	}
}