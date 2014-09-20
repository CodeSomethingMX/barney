<?php

use Illuminate\Database\Eloquent\Model as Model;

class TipoArchivo extends Model {

	protected 	$table		=	'tipoArchivo';
	protected 	$primaryKey =	'tipo_id';
	public 		$timestamps =	false;

	public function archivos() {
		return $this->hasMany('Archivo', 'tipo_id', 'tipo_id');
	}
}