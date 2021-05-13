<?php

define( 'RAKSHAK_WEB_PAGE_TO_ROOT', '../../' );
require_once RAKSHAK_WEB_PAGE_TO_ROOT . 'RAKSHAK/includes/RAKSHAKPage.inc.php';

RAKSHAKPageStartup( array( 'authenticated', 'phpids' ) );

$page = RAKSHAKPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: File Upload' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'upload';
$page[ 'help_button' ]   = 'upload';
$page[ 'source_button' ] = 'upload';

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

require_once RAKSHAK_WEB_PAGE_TO_ROOT . "vulnerabilities/upload/source/{$vulnerabilityFile}";

// Check if folder is writeable
$WarningHtml = '';
if( !is_writable( $PHPUploadPath ) ) {
	$WarningHtml .= "<div class=\"warning\">Incorrect folder permissions: {$PHPUploadPath}<br /><em>Folder is not writable.</em></div>";
}
// Is PHP-GD installed?
if( ( !extension_loaded( 'gd' ) || !function_exists( 'gd_info' ) ) ) {
	$WarningHtml .= "<div class=\"warning\">The PHP module <em>GD is not installed</em>.</div>";
}

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: File Upload</h1>

	{$WarningHtml}

	<div class=\"vulnerable_code_area\">
		<form enctype=\"multipart/form-data\" action=\"#\" method=\"POST\">
			<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000\" />
			Choose an image to upload:<br /><br />
			<input name=\"uploaded\" type=\"file\" /><br />
			<br />
			<input type=\"submit\" class=\"btn btn-dark\" name=\"Upload\" value=\"Upload\" />\n";

if( $vulnerabilityFile == 'impossible.php' )
	$page[ 'body' ] .= "			" . tokenField();

$page[ 'body' ] .= "
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . RAKSHAKExternalLinkUrlGet( 'https://www.owasp.org/index.php/Unrestricted_File_Upload' ) . "</li>
		<li>" . RAKSHAKExternalLinkUrlGet( 'https://blogs.securiteam.com/index.php/archives/1268' ) . "</li>
		<li>" . RAKSHAKExternalLinkUrlGet( 'https://www.acunetix.com/websitesecurity/upload-forms-threat/' ) . "</li>
	</ul>
</div>";

RAKSHAKHtmlEcho( $page );

?>
