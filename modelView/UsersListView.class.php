<?php

class UsersListView extends AbstractView {

	public function __construct ( $users, $links ) {
		$this->layout 	=	'users_list.html.twig';
		$this->addVar( 'users', $users );
		$this->addVar( 'links', $links );
	}
}