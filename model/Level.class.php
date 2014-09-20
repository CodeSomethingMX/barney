<?php

use Illuminate\Database\Eloquent\Model as Model;

class Level extends Model {

	protected $table 	=	'level';
	protected $primaryKey = 'level_id';
	public $timestamps = false;

	public function users() {
		return $this->hasMany('User', 'level_id', 'level_id');
	}
}