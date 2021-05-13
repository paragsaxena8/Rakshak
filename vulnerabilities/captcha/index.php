<?php

define( 'RAKSHAK_WEB_PAGE_TO_ROOT', '../../' );
require_once RAKSHAK_WEB_PAGE_TO_ROOT . 'RAKSHAK/includes/RAKSHAKPage.inc.php';
require_once RAKSHAK_WEB_PAGE_TO_ROOT . "external/recaptcha/recaptchalib.php";

RAKSHAKPageStartup( array( 'authenticated', 'phpids' ) );

$page = RAKSHAKPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Insecure CAPTCHA' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'captcha';
$page[ 'help_button' ]   = 'captcha';
$page[ 'source_button' ] = 'captcha';

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

$hide_form = false;
require_once RAKSHAK_WEB_PAGE_TO_ROOT . "vulnerabilities/captcha/source/{$vulnerabilityFile}";

// Check if we have a reCAPTCHA key
$WarningHtml = '';
if( $_RAKSHAK[ 'recaptcha_public_key' ] == "" ) {
	$WarningHtml = "<div class=\"warning\"><em>reCAPTCHA API key missing</em> from config file: " . realpath( getcwd() . DIRECTORY_SEPARATOR . RAKSHAK_WEB_PAGE_TO_ROOT . "config" . DIRECTORY_SEPARATOR . "config.inc.php" ) . "</div>";
	$html = "<em>Please register for a key</em> from reCAPTCHA: " . RAKSHAKExternalLinkUrlGet( 'https://www.google.com/recaptcha/admin/create' );
	$hide_form = true;
}

$page[ 'body' ] .= "
	<div class=\"body_padded\">
	<h1>Vulnerability: Insecure CAPTCHA</h1>

	{$WarningHtml}

	<div class=\"vulnerable_code_area\">
		<form action=\"#\" method=\"POST\" ";

if( $hide_form )
	$page[ 'body' ] .= "style=\"display:none;\"";

$page[ 'body' ] .= ">
			<h3>Change your password:</h3>
			<br />

			<input type=\"hidden\" name=\"step\" value=\"1\" />\n";

if( $vulnerabilityFile == 'impossible.php' ) {
	$page[ 'body' ] .= "
			Current password:<br />
			<input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password_current\"><br />";
}

$page[ 'body' ] .= "			New password:<br />
			<input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password_new\"><br />
			Confirm new password:<br />
			<input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password_conf\"><br />

			" . recaptcha_get_html( $_RAKSHAK[ 'recaptcha_public_key' ] );
if( $vulnerabilityFile == 'high.php' )
	$page[ 'body' ] .= "\n\n			<!-- **DEV NOTE**   Response: 'hidd3n_valu3'   &&   User-Agent: 'reCAPTCHA'   **/DEV NOTE** -->\n";

if( $vulnerabilityFile == 'high.php' || $vulnerabilityFile == 'impossible.php' )
	$page[ 'body' ] .= "\n			" . tokenField();

$page[ 'body' ] .= "
			<br />

			<input type=\"submit\" class=\"btn btn-dark\" value=\"Change\" name=\"Change\">
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . RAKSHAKExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/CAPTCHA' ) . "</li>
		<li>" . RAKSHAKExternalLinkUrlGet( 'https://www.google.com/recaptcha/' ) . "</li>
		<li>" . RAKSHAKExternalLinkUrlGet( 'https://www.owasp.org/index.php/Testing_for_Captcha_(OWASP-AT-012)' ) . "</li>
	</ul>
</div>\n";

RAKSHAKHtmlEcho( $page );

?>
