<?php

class LoginView extends AbstractView {

	public function __construct ( $action, $token, $join, $attempt ) {
		$this->layout = 'loginForm.html.twig';
		$this->addVar( 'action', $action );
		$this->addVar( 'token', $token );
		$this->addVar( 'join', $join );
		$this->addVar( 'attempt', $attempt );
	}
}