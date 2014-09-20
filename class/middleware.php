<?php

function isValidateToken () {

	$app 		= 	\Slim\Slim::getInstance();
	$token_post	=	$app->request->post('token');
	$client_ip 	=	$_SERVER['REMOTE_ADDR'];
	$token 		=	 Utilities::getSessionToken();

	if ( $token_post == $token['token']) echo 'lol';
}