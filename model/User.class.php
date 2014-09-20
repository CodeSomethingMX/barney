<?php

use Illuminate\Database\Eloquent\Model as Model;

class User extends Model {

	protected $table = 'user';
	protected $primaryKey = 'user_id';
	public $timestamps = false;

	public function level() {
		return $this->belongsTo('Level');
	}

	public function perfil () {
		return $this->hasOne( 'Perfil', 'perfil_id', 'user_id' );
	}
}