<?php

define( 'RAKSHAK_WEB_PAGE_TO_ROOT', '' );
require_once RAKSHAK_WEB_PAGE_TO_ROOT . 'RAKSHAK/includes/RAKSHAKPage.inc.php';

RAKSHAKPageStartup( array( 'authenticated', 'phpids' ) );

phpinfo();

?>
