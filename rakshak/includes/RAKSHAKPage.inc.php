<?php

if( !defined( 'RAKSHAK_WEB_PAGE_TO_ROOT' ) ) {
	die( 'RAKSHAK System error- WEB_PAGE_TO_ROOT undefined' );
	exit;
}

session_start(); // Creates a 'Full Path Disclosure' vuln.

if (!file_exists(RAKSHAK_WEB_PAGE_TO_ROOT . 'config/config.inc.php')) {
	die ("RAKSHAK System error - config file not found. Copy config/config.inc.php.dist to config/config.inc.php and configure to your environment.");
}

// Include configs
require_once RAKSHAK_WEB_PAGE_TO_ROOT . 'config/config.inc.php';
require_once( 'RAKSHAKPhpIds.inc.php' );

// Declare the $html variable
if( !isset( $html ) ) {
	$html = "";
}

// Valid security levels
$security_levels = array('low', 'medium', 'high', 'impossible');
if( !isset( $_COOKIE[ 'security' ] ) || !in_array( $_COOKIE[ 'security' ], $security_levels ) ) {
	// Set security cookie to impossible if no cookie exists
	if( in_array( $_RAKSHAK[ 'default_security_level' ], $security_levels) ) {
		RAKSHAKSecurityLevelSet( $_RAKSHAK[ 'default_security_level' ] );
	}
	else {
		RAKSHAKSecurityLevelSet( 'impossible' );
	}

	if( $_RAKSHAK[ 'default_phpids_level' ] == 'enabled' )
		RAKSHAKPhpIdsEnabledSet( true );
	else
		RAKSHAKPhpIdsEnabledSet( false );
}

// RAKSHAK version
function RAKSHAKVersionGet() {
	return '1.0 *Development*';
}

// RAKSHAK release date
function RAKSHAKReleaseDateGet() {
	return '2019-10-08';
}


// Start session functions --

function &RAKSHAKSessionGrab() {
	if( !isset( $_SESSION[ 'RAKSHAK' ] ) ) {
		$_SESSION[ 'RAKSHAK' ] = array();
	}
	return $_SESSION[ 'RAKSHAK' ];
}


function RAKSHAKPageStartup( $pActions ) {
	if( in_array( 'authenticated', $pActions ) ) {
		if( !RAKSHAKIsLoggedIn()) {
			RAKSHAKRedirect( RAKSHAK_WEB_PAGE_TO_ROOT . 'login.php' );
		}
	}

	if( in_array( 'phpids', $pActions ) ) {
		if( RAKSHAKPhpIdsIsEnabled() ) {
			RAKSHAKPhpIdsTrap();
		}
	}
}


function RAKSHAKPhpIdsEnabledSet( $pEnabled ) {
	$RAKSHAKSession =& RAKSHAKSessionGrab();
	if( $pEnabled ) {
		$RAKSHAKSession[ 'php_ids' ] = 'enabled';
	}
	else {
		unset( $RAKSHAKSession[ 'php_ids' ] );
	}
}


function RAKSHAKPhpIdsIsEnabled() {
	$RAKSHAKSession =& RAKSHAKSessionGrab();
	return isset( $RAKSHAKSession[ 'php_ids' ] );
}


function RAKSHAKLogin( $pUsername ) {
	$RAKSHAKSession =& RAKSHAKSessionGrab();
	$RAKSHAKSession[ 'username' ] = $pUsername;
}


function RAKSHAKIsLoggedIn() {
	$RAKSHAKSession =& RAKSHAKSessionGrab();
	return isset( $RAKSHAKSession[ 'username' ] );
}


function RAKSHAKLogout() {
	$RAKSHAKSession =& RAKSHAKSessionGrab();
	unset( $RAKSHAKSession[ 'username' ] );
}


function RAKSHAKPageReload() {
	RAKSHAKRedirect( $_SERVER[ 'PHP_SELF' ] );
}

function RAKSHAKCurrentUser() {
	$RAKSHAKSession =& RAKSHAKSessionGrab();
	return ( isset( $RAKSHAKSession[ 'username' ]) ? $RAKSHAKSession[ 'username' ] : '') ;
}

// -- END (Session functions)

function &RAKSHAKPageNewGrab() {
	$returnArray = array(
		'title'           => 'RAKSHAK Vulnerable Web Application (RVWA) v' . RAKSHAKVersionGet() . '',
		'title_separator' => ' :: ',
		'body'            => '',
		'page_id'         => '',
		'help_button'     => '',
		'source_button'   => '',
	);
	return $returnArray;
}


function RAKSHAKSecurityLevelGet() {
	return isset( $_COOKIE[ 'security' ] ) ? $_COOKIE[ 'security' ] : 'impossible';
}


function RAKSHAKSecurityLevelSet( $pSecurityLevel ) {
	if( $pSecurityLevel == 'impossible' ) {
		$httponly = true;
	}
	else {
		$httponly = false;
	}
	setcookie( session_name(), session_id(), null, '/', null, null, $httponly );
	setcookie( 'security', $pSecurityLevel, NULL, NULL, NULL, NULL, $httponly );
}


// Start message functions --

function RAKSHAKMessagePush( $pMessage ) {
	$RAKSHAKSession =& RAKSHAKSessionGrab();
	if( !isset( $RAKSHAKSession[ 'messages' ] ) ) {
		$RAKSHAKSession[ 'messages' ] = array();
	}
	$RAKSHAKSession[ 'messages' ][] = $pMessage;
}


function RAKSHAKMessagePop() {
	$RAKSHAKSession =& RAKSHAKSessionGrab();
	if( !isset( $RAKSHAKSession[ 'messages' ] ) || count( $RAKSHAKSession[ 'messages' ] ) == 0 ) {
		return false;
	}
	return array_shift( $RAKSHAKSession[ 'messages' ] );
}


function messagesPopAllToHtml() {
	$messagesHtml = '';
	while( $message = RAKSHAKMessagePop() ) {   // TODO- sharpen!
		$messagesHtml .= "<div class=\"message\">{$message}</div>";
	}

	return $messagesHtml;
}

// --END (message functions)

function RAKSHAKHtmlEcho( $pPage ) {
	$menuBlocks = array();

	$menuBlocks[ 'home' ] = array();
	if( RAKSHAKIsLoggedIn() )  {
		$menuBlocks[ 'home' ][] = array( 'id' => 'home', 'name' => 'Home', 'url' => 'main.php' );
		$menuBlocks[ 'home' ][] = array( 'id' => 'about', 'name' => 'About', 'url' => 'about.php' );
		$menuBlocks[ 'home' ][] = array( 'id' => 'instructions', 'name' => 'Instructions', 'url' => 'instructions.php' );
		
	} else {
		$menuBlocks[ 'home' ][] = array( 'id' => 'setup', 'name' => 'Setup RAKSHAK', 'url' => 'setup.php' );
		$menuBlocks[ 'home' ][] = array( 'id' => 'instructions', 'name' => 'Instructions', 'url' => 'instructions.php' );
	}

	if( RAKSHAKIsLoggedIn() ) {
		$menuBlocks[ 'vulnerabilities' ] = array();
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'brute', 'name' => 'Brute Force', 'url' => 'vulnerabilities/brute/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'exec', 'name' => 'Command Injection', 'url' => 'vulnerabilities/exec/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'csrf', 'name' => 'CSRF', 'url' => 'vulnerabilities/csrf/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'fi', 'name' => 'File Inclusion', 'url' => 'vulnerabilities/fi/.?page=include.php' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'upload', 'name' => 'File Upload', 'url' => 'vulnerabilities/upload/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'captcha', 'name' => 'Insecure CAPTCHA', 'url' => 'vulnerabilities/captcha/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'sqli', 'name' => 'SQL Injection', 'url' => 'vulnerabilities/sqli/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'sqli_blind', 'name' => 'SQL Injection (Blind)', 'url' => 'vulnerabilities/sqli_blind/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'weak_id', 'name' => 'Weak Session IDs', 'url' => 'vulnerabilities/weak_id/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'xss_d', 'name' => 'XSS (DOM)', 'url' => 'vulnerabilities/xss_d/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'xss_r', 'name' => 'XSS (Reflected)', 'url' => 'vulnerabilities/xss_r/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'xss_s', 'name' => 'XSS (Stored)', 'url' => 'vulnerabilities/xss_s/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'csp', 'name' => 'CSP Bypass', 'url' => 'vulnerabilities/csp/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'javascript', 'name' => 'JavaScript', 'url' => 'vulnerabilities/javascript/' );
	}
	if( RAKSHAKIsLoggedIn() )  {
		$menuBlocks[ 'Tools' ][] = array( 'id' => 'logout', 'name' => 'Logout', 'url' => 'logout.php' );
			
	}

	$menuHtml = '';

	foreach( $menuBlocks as $menuBlock ) {
		$menuBlockHtml = '';
		foreach( $menuBlock as $menuItem ) {
			$selectedClass = ( $menuItem[ 'id' ] == $pPage[ 'page_id' ] ) ? 'selected' : '';
			$fixedUrl = RAKSHAK_WEB_PAGE_TO_ROOT.$menuItem[ 'url' ];
			$menuBlockHtml .= "<li class=\"{$selectedClass}\"><a href=\"{$fixedUrl}\">{$menuItem[ 'name' ]}</a></li>\n";
		}
		$menuHtml .= "<ul class=\"menuBlocks\">{$menuBlockHtml}</ul>";
	}

	// Get security cookie --
	$securityLevelHtml = '';
	switch( RAKSHAKSecurityLevelGet() ) {
		case 'low':
			$securityLevelHtml = 'low';
			break;
		case 'medium':
			$securityLevelHtml = 'medium';
			break;
		case 'high':
			$securityLevelHtml = 'high';
			break;
		default:
			$securityLevelHtml = 'low';
			break;
	}
	// -- END (security cookie)

	$phpIdsHtml   = '<em>PHPIDS:</em> ' . ( RAKSHAKPhpIdsIsEnabled() ? 'enabled' : 'disabled' );
	$userInfoHtml = '<em>Username:</em> ' . ( RAKSHAKCurrentUser() );

	$messagesHtml = messagesPopAllToHtml();
	if( $messagesHtml ) {
		$messagesHtml = "<div class=\"body_padded\">{$messagesHtml}</div>";
	}

	$systemInfoHtml = "";
	if( RAKSHAKIsLoggedIn() )
		$systemInfoHtml = "<div align=\"left\">{$userInfoHtml}<br /><em>Security Level:</em> {$securityLevelHtml}<br />{$phpIdsHtml}</div>";
	if( $pPage[ 'source_button' ] ) {
		$systemInfoHtml = RAKSHAKButtonSourceHtmlGet( $pPage[ 'source_button' ] ) . " $systemInfoHtml";
	}
	if( $pPage[ 'help_button' ] ) {
		$systemInfoHtml = RAKSHAKButtonHelpHtmlGet( $pPage[ 'help_button' ] ) . " $systemInfoHtml";
	}

	// Send Headers + main HTML code
	Header( 'Cache-Control: no-cache, must-revalidate');   // HTTP/1.1
	Header( 'Content-Type: text/html;charset=utf-8' );     // TODO- proper XHTML headers...
	Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );    // Date in the past

	echo "
	<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"utf-8\">
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
        <title>{$pPage[ 'title']}</title>
        <meta name=\"description\" content=\"\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">
        <!-- Google Fonts -->
        <script src=\"https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js\"></script>
        <script>
          WebFont.load({
            google: {\"families\":[\"Montserrat:400,500,600,700\",\"Noto+Sans:400,700\"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>
        <!-- Favicon -->
        <link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"/Rakshak/assets/img/apple-touch-icon.png\">
        <link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"/Rakshak/assets/img/favicon-32x32.png\">
        <link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"/Rakshak/assets/img/favicon-16x16.png\">
        <!-- Stylesheet -->
        <link rel=\"stylesheet\" type=\"text/css\" href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "./assets/vendors/css/base/bootstrap.min.css\" />

        <link rel=\"icon\" type=\"\image/ico\" href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "favicon.ico\" />
        <link rel=\"stylesheet\" href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "./assets/vendors/css/base/bootstrap.min.css\">
        <link rel=\"stylesheet\" href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "./assets/vendors/css/base/elisyam-1.5.min.css\">
        <link rel=\"stylesheet\" href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "./assets/css/owl-carousel/owl.carousel.min.css\">
        <link rel=\"stylesheet\" href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "./assets/css/owl-carousel/owl.theme.min.css\">
        <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
        <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script><![endif]-->
    </head>
    <body id=\"page-top\">
        <div class=\"page\">
            <!-- Begin Header -->
            <header class=\"header\">
                <nav class=\"navbar fixed-top\">         
                    
                    <!-- Begin Topbar -->
                    <div class=\"navbar-holder d-flex align-items-center align-middle justify-content-between\">
                        <!-- Begin Logo -->
                        <div class=\"navbar-header\">
                            <a href=\"./\" class=\"navbar-brand\">
                                <div class=\"brand-image brand-big\">
                                    <img src=\"https://cybersquareinfo.com/storage/2018/10/footerlogo.png\" alt=\"logo\" class=\"logo-big\">
                                </div>
                                <div class=\"brand-image brand-small\">
                                    <img src=\"https://cybersquareinfo.com/storage/2018/10/weblogo.png\" alt=\"logo\" class=\"logo-small\">
                                </div>
                            </a>
                            <!-- Toggle Button -->
                            <a id=\"toggle-btn\" href=\"#\" class=\"menu-btn active\">
                                <span></span>
                                <span></span>
                                <span></span>
                            </a>
                            <!-- End Toggle -->
                        </div>
                        <!-- End Logo -->
                        <!-- Begin Navbar Menu -->
                        <ul class=\"nav-menu list-unstyled d-flex flex-md-row align-items-md-center pull-right\">
                            
                            <!-- User -->
                            <li class=\"nav-item dropdown\"><a id=\"user\" rel=\"nofollow\" data-target=\"#\" href=\"#\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" class=\"nav-link\"><img src=\"https://farm8.staticflickr.com/7436/10614551024_ef85c141f9_b.jpg\" alt=\"...\" class=\"avatar rounded-circle\"></a>
                                <ul aria-labelledby=\"user\" class=\"user-size dropdown-menu\">
                                    <li class=\"welcome\">
                                        <a href=\"/Rakshak/security.php\" class=\"edit-profil\"><i class=\"la la-gear\"></i></a>
                                        <img src=\"https://farm8.staticflickr.com/7436/10614551024_ef85c141f9_b.jpg\" alt=\"...\" class=\"rounded-circle\">
                                    </li>
                                    <li>
                                        <a href=\"/Rakshak/about.php\" class=\"dropdown-item text-center\"> 
                                             About RAKSHAK
                                        </a>
                                    </li>
                                    <li>
                                        <a href=\"/Rakshak/setup.php\" class=\"dropdown-item text-center\"> 
                                             Setup Rakshak/DB
                                        </a>
                                    </li>
                                    <li>
                                        <a href=\"/Rakshak/instructions.php\" class=\"dropdown-item text-center\"> 
                                             Instructions
                                        </a>
                                    </li>
                                    <li><a rel=\"nofollow\" href=\"/Rakshak/logout.php\" class=\"dropdown-item logout text-center\"><i class=\"ti-power-off\"></i></a></li>
                                </ul>
                            </li>
                            <!-- End User -->
                        </ul>
                        <!-- End Navbar Menu -->
                    </div>
                    <!-- End Topbar -->
                </nav>
            </header>
            <!-- End Header -->
            <!-- body -->
            <div class=\"page-content d-flex align-items-stretch\">
             <div class=\"page-content d-flex align-items-stretch\">
                <div class=\"default-sidebar\">
                    <!-- Begin Side Navbar -->
                    <nav class=\"side-navbar box-scroll sidebar-scroll\">
                        <!-- Begin Main Navigation -->
                        <ul class=\"list-unstyled\">
                        	 <li><a>{$menuHtml}</a></li>
                        </ul>
                        <!-- End Main Navigation -->
                    </nav>
                    <!-- End Side Navbar -->
                </div>
                <!-- End Left Sidebar -->
                <div class=\"content-inner\">
                    <div class=\"container-fluid\">
            {$pPage[ 'body' ]}
                <br /><br />
                {$messagesHtml}
            </div>  
        </div>
        <!-- Begin Vendor Js -->
        <script src=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "./assets/vendors/js/base/jquery.min.js\"></script>
        <script src=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "./assets/vendors/js/base/core.min.js\"></script>
        <!-- End Vendor Js -->
        <!-- Begin Page Vendor Js -->
        <script src=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "./assets/vendors/js/nicescroll/nicescroll.min.js\"></script>
        <!-- End Page Vendor Js -->
        <!-- Begin Page Snippets -->
        <script src=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "./assets/js/dashboard/db-default.js\"></script>
        <!-- End Page Snippets -->
    </body>
</html>";
}


function RAKSHAKHelpHtmlEcho( $pPage ) {
	// Send Headers
	Header( 'Cache-Control: no-cache, must-revalidate');   // HTTP/1.1
	Header( 'Content-Type: text/html;charset=utf-8' );     // TODO- proper XHTML headers...
	Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );    // Date in the past

	echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\">

	<head>

		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>{$pPage[ 'title' ]}</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "RAKSHAK/css/help.css\" />

		<link rel=\"icon\" type=\"\image/ico\" href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "favicon.ico\" />

	</head>

	<body>

	<div id=\"container\">

			{$pPage[ 'body' ]}

		</div>

	</body>

</html>";
}


function RAKSHAKSourceHtmlEcho( $pPage ) {
	// Send Headers
	Header( 'Cache-Control: no-cache, must-revalidate');   // HTTP/1.1
	Header( 'Content-Type: text/html;charset=utf-8' );     // TODO- proper XHTML headers...
	Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );    // Date in the past

	echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\">

	<head>

		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>{$pPage[ 'title' ]}</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "RAKSHAK/css/source.css\" />

		<link rel=\"icon\" type=\"\image/ico\" href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "favicon.ico\" />

	</head>

	<body>

		<div id=\"container\">

			{$pPage[ 'body' ]}

		</div>

	</body>

</html>";
}

// To be used on all external links --
function RAKSHAKExternalLinkUrlGet( $pLink,$text=null ) {
	if(is_null( $text )) {
		return '<a href="' . $pLink . '" target="_blank">' . $pLink . '</a>';
	}
	else {
		return '<a href="' . $pLink . '" target="_blank">' . $text . '</a>';
	}
}
// -- END ( external links)

function RAKSHAKButtonHelpHtmlGet( $pId ) {
	$security = RAKSHAKSecurityLevelGet();
	return "<input type=\"button\" value=\"View Help\" class=\"popup_button\" id='help_button' data-help-url='" . RAKSHAK_WEB_PAGE_TO_ROOT . "vulnerabilities/view_help.php?id={$pId}&security={$security}' )\">";
}


function RAKSHAKButtonSourceHtmlGet( $pId ) {
	$security = RAKSHAKSecurityLevelGet();
	return "<input type=\"button\" value=\"View Source\" class=\"popup_button\" id='source_button' data-source-url='" . RAKSHAK_WEB_PAGE_TO_ROOT . "vulnerabilities/view_source.php?id={$pId}&security={$security}' )\">";
}


// Database Management --

if( $DBMS == 'MySQL' ) {
	$DBMS = htmlspecialchars(strip_tags( $DBMS ));
	$DBMS_errorFunc = 'mysqli_error()';
}
elseif( $DBMS == 'PGSQL' ) {
	$DBMS = htmlspecialchars(strip_tags( $DBMS ));
	$DBMS_errorFunc = 'pg_last_error()';
}
else {
	$DBMS = "No DBMS selected.";
	$DBMS_errorFunc = '';
}

//$DBMS_connError = '
//	<div align="center">
//		<img src="' . RAKSHAK_WEB_PAGE_TO_ROOT . 'RAKSHAK/images/logo.png" />
//		<pre>Unable to connect to the database.<br />' . $DBMS_errorFunc . '<br /><br /></pre>
//		Click <a href="' . RAKSHAK_WEB_PAGE_TO_ROOT . 'setup.php">here</a> to setup the database.
//	</div>';

function RAKSHAKDatabaseConnect() {
	global $_RAKSHAK;
	global $DBMS;
	//global $DBMS_connError;
	global $db;

	if( $DBMS == 'MySQL' ) {
		if( !@($GLOBALS["___mysqli_ston"] = mysqli_connect( $_RAKSHAK[ 'db_server' ],  $_RAKSHAK[ 'db_user' ],  $_RAKSHAK[ 'db_password' ] ))
		|| !@((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $_RAKSHAK[ 'db_database' ])) ) {
			//die( $DBMS_connError );
			RAKSHAKLogout();
			RAKSHAKMessagePush( 'Unable to connect to the database.<br />' . $DBMS_errorFunc );
			RAKSHAKRedirect( RAKSHAK_WEB_PAGE_TO_ROOT . 'setup.php' );
		}
		// MySQL PDO Prepared Statements (for impossible levels)
		$db = new PDO('mysql:host=' . $_RAKSHAK[ 'db_server' ].';dbname=' . $_RAKSHAK[ 'db_database' ].';charset=utf8', $_RAKSHAK[ 'db_user' ], $_RAKSHAK[ 'db_password' ]);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}
	elseif( $DBMS == 'PGSQL' ) {
		//$dbconn = pg_connect("host={$_RAKSHAK[ 'db_server' ]} dbname={$_RAKSHAK[ 'db_database' ]} user={$_RAKSHAK[ 'db_user' ]} password={$_RAKSHAK[ 'db_password' ])}"
		//or die( $DBMS_connError );
		RAKSHAKMessagePush( 'PostgreSQL is not yet fully supported.' );
		RAKSHAKPageReload();
	}
	else {
		die ( "Unknown {$DBMS} selected." );
	}
}

// -- END (Database Management)


function RAKSHAKRedirect( $pLocation ) {
	session_commit();
	header( "Location: {$pLocation}" );
	exit;
}

// XSS Stored guestbook function --
function RAKSHAKGuestbook() {
	$query  = "SELECT name, comment FROM guestbook";
	$result = mysqli_query($GLOBALS["___mysqli_ston"],  $query );

	$guestbook = '';

	while( $row = mysqli_fetch_row( $result ) ) {
		if( RAKSHAKSecurityLevelGet() == 'impossible' ) {
			$name    = htmlspecialchars( $row[0] );
			$comment = htmlspecialchars( $row[1] );
		}
		else {
			$name    = $row[0];
			$comment = $row[1];
		}

		$guestbook .= "<div id=\"guestbook_comments\">Name: {$name}<br />" . "Message: {$comment}<br /></div>\n";
	}
	return $guestbook;
}
// -- END (XSS Stored guestbook)


// Token functions --
function checkToken( $user_token, $session_token, $returnURL ) {  # Validate the given (CSRF) token
	if( $user_token !== $session_token || !isset( $session_token ) ) {
		RAKSHAKMessagePush( 'CSRF token is incorrect' );
		RAKSHAKRedirect( $returnURL );
	}
}

function generateSessionToken() {  # Generate a brand new (CSRF) token
	if( isset( $_SESSION[ 'session_token' ] ) ) {
		destroySessionToken();
	}
	$_SESSION[ 'session_token' ] = md5( uniqid() );
}

function destroySessionToken() {  # Destroy any session with the name 'session_token'
	unset( $_SESSION[ 'session_token' ] );
}

function tokenField() {  # Return a field for the (CSRF) token
	return "<input type='hidden' name='user_token' value='{$_SESSION[ 'session_token' ]}' />";
}
// -- END (Token functions)


// Setup Functions --
$PHPUploadPath    = realpath( getcwd() . DIRECTORY_SEPARATOR . RAKSHAK_WEB_PAGE_TO_ROOT . "hackable" . DIRECTORY_SEPARATOR . "uploads" ) . DIRECTORY_SEPARATOR;
$PHPIDSPath       = realpath( getcwd() . DIRECTORY_SEPARATOR . RAKSHAK_WEB_PAGE_TO_ROOT . "external" . DIRECTORY_SEPARATOR . "phpids" . DIRECTORY_SEPARATOR . RAKSHAKPhpIdsVersionGet() . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "IDS" . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . "phpids_log.txt" );
$PHPCONFIGPath       = realpath( getcwd() . DIRECTORY_SEPARATOR . RAKSHAK_WEB_PAGE_TO_ROOT . "config");


$phpDisplayErrors = 'PHP function display_errors: <em>' . ( ini_get( 'display_errors' ) ? 'Enabled</em> <i>(Easy Mode!)</i>' : 'Disabled</em>' );                                                  // Verbose error messages (e.g. full path disclosure)
$phpSafeMode      = 'PHP function safe_mode: <span class="' . ( ini_get( 'safe_mode' ) ? 'failure">Enabled' : 'success">Disabled' ) . '</span>';                                                   // DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0
$phpMagicQuotes   = 'PHP function magic_quotes_gpc: <span class="' . ( ini_get( 'magic_quotes_gpc' ) ? 'failure">Enabled' : 'success">Disabled' ) . '</span>';                                     // DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0
$phpURLInclude    = 'PHP function allow_url_include: <span class="' . ( ini_get( 'allow_url_include' ) ? 'success">Enabled' : 'failure">Disabled' ) . '</span>';                                   // RFI
$phpURLFopen      = 'PHP function allow_url_fopen: <span class="' . ( ini_get( 'allow_url_fopen' ) ? 'success">Enabled' : 'failure">Disabled' ) . '</span>';                                       // RFI
$phpGD            = 'PHP module gd: <span class="' . ( ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ) ? 'success">Installed' : 'failure">Missing' ) . '</span>';                    // File Upload
$phpMySQL         = 'PHP module mysql: <span class="' . ( ( extension_loaded( 'mysqli' ) && function_exists( 'mysqli_query' ) ) ? 'success">Installed' : 'failure">Missing' ) . '</span>';                // Core RAKSHAK
$phpPDO           = 'PHP module pdo_mysql: <span class="' . ( extension_loaded( 'pdo_mysql' ) ? 'success">Installed' : 'failure">Missing' ) . '</span>';                // SQLi
$RAKSHAKRecaptcha    = 'reCAPTCHA key: <span class="' . ( ( isset( $_RAKSHAK[ 'recaptcha_public_key' ] ) && $_RAKSHAK[ 'recaptcha_public_key' ] != '' ) ? 'success">' . $_RAKSHAK[ 'recaptcha_public_key' ] : 'failure">Missing' ) . '</span>';

$RAKSHAKUploadsWrite = '[User: ' . get_current_user() . '] Writable folder ' . $PHPUploadPath . ': <span class="' . ( is_writable( $PHPUploadPath ) ? 'success">Yes' : 'failure">No' ) . '</span>';                                     // File Upload
$bakWritable = '[User: ' . get_current_user() . '] Writable folder ' . $PHPCONFIGPath . ': <span class="' . ( is_writable( $PHPCONFIGPath ) ? 'success">Yes' : 'failure">No' ) . '</span>';   // config.php.bak check                                  // File Upload
$RAKSHAKPHPWrite     = '[User: ' . get_current_user() . '] Writable file ' . $PHPIDSPath . ': <span class="' . ( is_writable( $PHPIDSPath ) ? 'success">Yes' : 'failure">No' ) . '</span>';                                              // PHPIDS

$RAKSHAKOS           = 'Operating system: <em>' . ( strtoupper( substr (PHP_OS, 0, 3)) === 'WIN' ? 'Windows' : '*nix' ) . '</em>';
$SERVER_NAME      = 'Web Server SERVER_NAME: <em>' . $_SERVER[ 'SERVER_NAME' ] . '</em>';                                                                                                          // CSRF

$MYSQL_USER       = 'MySQL username: <em>' . $_RAKSHAK[ 'db_user' ] . '</em>';
$MYSQL_PASS       = 'MySQL password: <em>' . ( ($_RAKSHAK[ 'db_password' ] != "" ) ? '******' : '*blank*' ) . '</em>';
$MYSQL_DB         = 'MySQL database: <em>' . $_RAKSHAK[ 'db_database' ] . '</em>';
$MYSQL_SERVER     = 'MySQL host: <em>' . $_RAKSHAK[ 'db_server' ] . '</em>';
// -- END (Setup Functions)

?>
