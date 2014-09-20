<?php

use Illuminate\Database\Eloquent\Model as Model;

class Curso extends Model {

	protected $table = 'curso';
	protected $primaryKey = 'curso_id';
	public $timestamps = false;

	public function unidades() {
		return $this->hasMany('Unidad');
	}

	public function tipoCurso() {
		return $this->belongsTo('TipoCurso', 'tipoCurso', 'tipoCurso');
	}

	public function perfiles () {

		return $this->belongsToMany('Perfil');
	}

	public function preguntas () {
		return $this->hasMany('Pregunta');
	}
}