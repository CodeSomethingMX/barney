<?php

class MaestroView extends AbstractView {

	public function __construct ( $user, $links, $logged ) {
		$this->layout 	=	'maestro.html.twig';
		$this->addVar( 'user', $user );
		$this->addVar( 'links', $links );
		$this->addVar( 'logged', $logged );
		
	}
}