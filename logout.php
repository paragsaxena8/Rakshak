<?php

define( 'RAKSHAK_WEB_PAGE_TO_ROOT', '' );
require_once RAKSHAK_WEB_PAGE_TO_ROOT . 'RAKSHAK/includes/RAKSHAKPage.inc.php';

RAKSHAKPageStartup( array( 'phpids' ) );

if( !RAKSHAKIsLoggedIn() ) {	// The user shouldn't even be on this page
	// RAKSHAKMessagePush( "You were not logged in" );
	RAKSHAKRedirect( 'login.php' );
}

RAKSHAKLogout();
RAKSHAKMessagePush( "You have logged out" );
RAKSHAKRedirect( 'login.php' );

?>
