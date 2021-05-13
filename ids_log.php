<?php

define( 'RAKSHAK_WEB_PAGE_TO_ROOT', '' );
require_once RAKSHAK_WEB_PAGE_TO_ROOT . 'RAKSHAK/includes/RAKSHAKPage.inc.php';

define( 'RAKSHAK_WEB_ROOT_TO_PHPIDS_LOG', 'external/phpids/' . RAKSHAKPhpIdsVersionGet() . '/lib/IDS/tmp/phpids_log.txt' );
define( 'RAKSHAK_WEB_PAGE_TO_PHPIDS_LOG', RAKSHAK_WEB_PAGE_TO_ROOT.RAKSHAK_WEB_ROOT_TO_PHPIDS_LOG );

RAKSHAKPageStartup( array( 'authenticated', 'phpids' ) );

$page = RAKSHAKPageNewGrab();
$page[ 'title' ]   = 'PHPIDS Log' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'log';
// $page[ 'clear_log' ]; <- Was showing error.

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>PHPIDS Log</h1>

	<p>" . RAKSHAKReadIdsLog() . "</p>
	<br /><br />

	<form action=\"#\" method=\"GET\">
		<input type=\"submit\" value=\"Clear Log\" name=\"clear_log\">
	</form>

	" . RAKSHAKClearIdsLog() . "
</div>";

RAKSHAKHtmlEcho( $page );

?>
