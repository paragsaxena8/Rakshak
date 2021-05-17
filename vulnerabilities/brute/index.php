<?php

define( 'RAKSHAK_WEB_PAGE_TO_ROOT', '../../' );
require_once RAKSHAK_WEB_PAGE_TO_ROOT . 'RAKSHAK/includes/RAKSHAKPage.inc.php';

RAKSHAKPageStartup( array( 'authenticated', 'phpids' ) );

$page = RAKSHAKPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Brute Force' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'brute';
$page[ 'help_button' ]   = 'brute';
$page[ 'source_button' ] = 'brute';
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

require_once RAKSHAK_WEB_PAGE_TO_ROOT . "vulnerabilities/brute/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: Brute Force</h1>

	<div class=\"vulnerable_code_area\">
		<h2>Login</h2>

		<form action=\"#\" method=\"{$method}\">
			Username:<br />
			<input class=\"form-control\"  type=\"text\" name=\"username\"><br />
			Password:<br />
			<input class=\"form-control\"  type=\"password\" AUTOCOMPLETE=\"off\" name=\"password\"><br />
			<br />
			<input class=\"form-control\"  type=\"submit\" class=\"btn btn-dark\" value=\"Login\" name=\"Login\">\n";

if( $vulnerabilityFile == 'high.php' || $vulnerabilityFile == 'impossible.php' )
	$page[ 'body' ] .= "			" . tokenField();

$page[ 'body' ] .= "
		</form>
		{$html}
	</div>

</div>\n";

RAKSHAKHtmlEcho( $page );

?>
