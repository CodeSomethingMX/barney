<?php

class LeccionFileView extends AbstractView {


	public function __construct ( $action, $token, $attempt ) {
		$this->layout 	= 	'leccion_file_view.html.twig';
		$this->addVar( 'action', $action );
		$this->addVar( 'token', $token );
		$this->addVar( 'attempt', $attempt );
	
	}
}