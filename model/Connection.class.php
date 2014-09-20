<?php

use Illuminate\Database\Capsule\Manager as DB;

class Connection {

	public static function conecting () {

		$fileName 	=	'conf/conf.db.ini';
		$config		=	parse_ini_file( $fileName );
		$connection = new DB;
		$connection->addConnection( $config );
		$connection->setAsGlobal();
		$connection->bootEloquent();
	}

	public static function getDB() {

		$fileName 	=	'conf/conf.db.ini';
		$config		=	parse_ini_file( $fileName );
		$connection = new DB;
		$connection->addConnection( $config );
		$connection->setAsGlobal();
		$connection->bootEloquent();

		return $connection;	
	}
}