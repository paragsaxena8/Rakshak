<?php

define( 'RAKSHAK_WEB_PAGE_TO_ROOT', '../../' );
require_once RAKSHAK_WEB_PAGE_TO_ROOT . 'RAKSHAK/includes/RAKSHAKPage.inc.php';

RAKSHAKPageStartup( array( 'authenticated', 'phpids' ) );

$page = RAKSHAKPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Reflected Cross Site Scripting (XSS)' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'xss_r';
$page[ 'help_button' ]   = 'xss_r';
$page[ 'source_button' ] = 'xss_r';

RAKSHAKDatabaseConnect();

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
		break;
}

require_once RAKSHAK_WEB_PAGE_TO_ROOT . "vulnerabilities/xss_r/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: Reflected Cross Site Scripting (XSS)</h1>

	<div class=\"vulnerable_code_area\">
		<form name=\"XSS\" action=\"#\" method=\"GET\">
			<p>
				What's your name?
				<input type=\"text\" name=\"name\">
				<input type=\"submit\" class=\"btn btn-dark\" value=\"Submit\">
			</p>\n";

if( $vulnerabilityFile == 'impossible.php' )
	$page[ 'body' ] .= "			" . tokenField();

$page[ 'body' ] .= "
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . RAKSHAKExternalLinkUrlGet( 'https://www.owasp.org/index.php/Cross-site_Scripting_(XSS)' ) . "</li>
		<li>" . RAKSHAKExternalLinkUrlGet( 'https://www.owasp.org/index.php/XSS_Filter_Evasion_Cheat_Sheet' ) . "</li>
		<li>" . RAKSHAKExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Cross-site_scripting' ) . "</li>
		<li>" . RAKSHAKExternalLinkUrlGet( 'http://www.cgisecurity.com/xss-faq.html' ) . "</li>
		<li>" . RAKSHAKExternalLinkUrlGet( 'http://www.scriptalert1.com/' ) . "</li>
	</ul>
</div>\n";

RAKSHAKHtmlEcho( $page );

?>
