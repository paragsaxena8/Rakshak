<?php

define( 'RAKSHAK_WEB_PAGE_TO_ROOT', '' );
require_once RAKSHAK_WEB_PAGE_TO_ROOT . 'RAKSHAK/includes/RAKSHAKPage.inc.php';

RAKSHAKPageStartup( array( 'phpids' ) );

RAKSHAKDatabaseConnect();

if( isset( $_POST[ 'Login' ] ) ) {
	// Anti-CSRF
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'login.php' );

	$user = $_POST[ 'username' ];
	$user = stripslashes( $user );
	$user = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $user ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	$pass = $_POST[ 'password' ];
	$pass = stripslashes( $pass );
	$pass = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $pass ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$pass = md5( $pass );

	$query = ("SELECT table_schema, table_name, create_time
				FROM information_schema.tables
				WHERE table_schema='{$_RAKSHAK['db_database']}' AND table_name='users'
				LIMIT 1");
	$result = @mysqli_query($GLOBALS["___mysqli_ston"],  $query );
	if( mysqli_num_rows( $result ) != 1 ) {
		RAKSHAKMessagePush( "First time using WebVuln.<br />Need to run 'setup.php'." );
		RAKSHAKRedirect( RAKSHAK_WEB_PAGE_TO_ROOT . 'setup.php' );
	}

	$query  = "SELECT * FROM `users` WHERE user='$user' AND password='$pass';";
	$result = @mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '.<br />Try <a href="setup.php">installing again</a>.</pre>' );
	if( $result && mysqli_num_rows( $result ) == 1 ) {    // Login Successful...
		RAKSHAKMessagePush( "You have logged in as '{$user}'" );
		RAKSHAKLogin( $user );
		RAKSHAKRedirect( RAKSHAK_WEB_PAGE_TO_ROOT . 'main.php' );
	}

	// Login failed
	RAKSHAKMessagePush( 'Login failed' );
	RAKSHAKRedirect( 'login.php' );
}

$messagesHtml = messagesPopAllToHtml();

Header( 'Cache-Control: no-cache, must-revalidate');    // HTTP/1.1
Header( 'Content-Type: text/html;charset=utf-8' );      // TODO- proper XHTML headers...
Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );     // Date in the past

// Anti-CSRF
generateSessionToken();

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\">

	<head>

		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>Login :: WebVuln v" . RAKSHAKVersionGet() . "</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "assets/vendors/css/base/elisyam-1.5.min.css\" />
        <link rel=\"stylesheet\" type=\"text/css\" href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "assets/vendors/css/base/bootstrap.min.css\" />


	</head>

	<body class=\"bg-white\">
        <!-- Begin Container -->
        <div class=\"container-fluid no-padding h-100\">
            <div class=\"row flex-row h-100 bg-white\">
                <!-- Begin Left Content -->
                <div class=\"col-xl-8 col-lg-6 col-md-5 no-padding\">
                    <div class=\"elisyam-bg background-01\">
                        <div class=\"elisyam-overlay overlay-01\"></div>
                        <div class=\"authentication-col-content mx-auto\">
                            <h1 class=\"gradient-text-01\">
                                Welcome To WebVuln
                            </h1>
                            <span class=\"description\">
                                Login In to WebVuln Portal 
                            </span>
                        </div>
                    </div>
                </div>
                <!-- End Left Content -->
                <!-- Begin Right Content -->
                <div class=\"col-xl-4 col-lg-6 col-md-7 my-auto no-padding\">
                    <!-- Begin Form -->
                    <div class=\"authentication-form mx-auto\">
                        <div class=\"logo-centered\" style=\" display:flex;justify-item:center \">
                            <a href=\"#\">
                                <img src=\"./assets/images/logo.png\" alt=\"logo\">
                            </a>
                        </div>
                        <h3>Sign In To Rakshak</h3>
                       <form action=\"login.php\" method=\"post\">
                            <div class=\"group material-input\">
                                <input type=\"text\" name=\"username\" required>
                                <span class=\"highlight\"></span>
                                <span class=\"bar\"></span>
                                <label>Username</label>
                            </div>
                            <div class=\"group material-input\">
                                <input type=\"password\" name=\"password\" required>
                                <span class=\"highlight\"></span>
                                <span class=\"bar\"></span>
                                <label>Password</label>
                            </div>
                        
                        <div class=\"row\">
                            <div class=\"col text-left\">
                                <div class=\"styled-checkbox\">
                                    <input type=\"checkbox\" name=\"checkbox\" id=\"remeber\">
                                    <label for=\"remeber\">Remember me</label>
                                </div>
                            </div>
                            <div class=\"col text-right\">
                                <a href=\"pages-forgot-password.html\">Forgot Password ?</a>
                            </div>
                        </div>
                        <div class=\"sign-btn text-center\">
                            <input type=\"submit\" class=\"btn btn-lg btn-gradient-01\" value=\"Login\" name=\"Login\"></p>
                        </div>" . tokenField() . "

    </form>

    <br />

    {$messagesHtml}

                        
                    </div>
                    <!-- End Form -->                        
                </div>
                <!-- End Right Content -->
            </div>
            <!-- End Row -->
        </div>
        <!-- End Container -->    
        <!-- Begin Vendor Js -->
        <script src=\"assets/vendors/js/base/jquery.min.js\"></script>
        <script src=\"assets/vendors/js/base/core.min.js\"></script>
        <!-- End Vendor Js -->
        <!-- Begin Page Vendor Js -->
        <script src=\"assets/vendors/js/app/app.min.js\"></script>
        <!-- End Page Vendor Js -->
    </body>

</html>";

?>
