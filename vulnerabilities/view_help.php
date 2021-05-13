<?php

define( 'RAKSHAK_WEB_PAGE_TO_ROOT', '../' );
require_once RAKSHAK_WEB_PAGE_TO_ROOT . 'RAKSHAK/includes/RAKSHAKPage.inc.php';

RAKSHAKPageStartup( array( 'authenticated', 'phpids' ) );

$page = RAKSHAKPageNewGrab();
$page[ 'title' ] = 'Help' . $page[ 'title_separator' ].$page[ 'title' ];

$id       = $_GET[ 'id' ];
$security = $_GET[ 'security' ];

ob_start();
eval( '?>' . file_get_contents( RAKSHAK_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/help/help.php" ) . '<?php ' );
$help = ob_get_contents();
ob_end_clean();

$page[ 'body' ] .= "
<div class=\"body_padded\">
	{$help}
</div>\n";

RAKSHAKHelpHtmlEcho( $page );

?>
