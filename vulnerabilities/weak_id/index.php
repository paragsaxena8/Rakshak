<?php

define( 'RAKSHAK_WEB_PAGE_TO_ROOT', '../../' );
require_once RAKSHAK_WEB_PAGE_TO_ROOT . 'RAKSHAK/includes/RAKSHAKPage.inc.php';

RAKSHAKPageStartup( array( 'authenticated', 'phpids' ) );

$page = RAKSHAKPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Weak Session IDs' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'weak_id';
$page[ 'help_button' ]   = 'weak_id';
$page[ 'source_button' ] = 'weak_id';
RAKSHAKDatabaseConnect();

$method            = 'GET';
$vulnerabilityFile = '';
switch( $_COOKIE[ 'security' ] ) {
	case 'low':
		$vulnerabilityFile = 'low.php';
		break;
	case 'medium':
		$vulnerabilityFile = 'medium.php';
		break;
	case 'high':
		$vulnerabilityFile = 'high.php';
		break;
	default:
		$vulnerabilityFile = 'impossible.php';
		$method = 'POST';
		break;
}

require_once RAKSHAK_WEB_PAGE_TO_ROOT . "vulnerabilities/weak_id/source/{$vulnerabilityFile}";


$page[ 'body' ] .= <<<EOF
<div class="body_padded">
	<h1>Vulnerability: Weak Session IDs</h1>
	<p>
		This page will set a new cookie called RAKSHAKSession each time the button is clicked.<br />
	</p>
	<form method="post">
		<input class=\"form-control\"  type="submit" class=\"btn btn-dark\" value="Generate" />
	</form>
$html

EOF;

/*
Maybe display this, don't think it is needed though
if (isset ($cookie_value)) {
	$page[ 'body' ] .= <<<EOF
	The new cookie value is $cookie_value
EOF;
}
*/

RAKSHAKHtmlEcho( $page );

?>
