<?php

class JoinView extends AbstractView {

	public function __construct ( $action, $token, $error = 0 ) {
		$this->layout 	=	'joinForm.html.twig';
		$this->addVar( 'action', $action );
		$this->addVar( 'token', $token );
		$this->addVar( 'error', $error );
	}
}