<?php

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: File Inclusion</h1>
	<div class=\"vulnerable_code_area\">
		<h3>File 1</h3>
		<hr />
		Hello <em>" . RAKSHAKCurrentUser() . "</em><br />
		Your IP address is: <em>{$_SERVER[ 'REMOTE_ADDR' ]}</em><br /><br />
		[<em><a href=\"?page=include.php\">back</a></em>]
	</div>

	<h2>More info</h2>
	<ul>
		<li>" . RAKSHAKExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Remote_File_Inclusion' ) . "</li>
		<li>" . RAKSHAKExternalLinkUrlGet( 'https://www.owasp.org/index.php/Top_10_2007-A3' ) . "</li>
	</ul>
</div>\n";

?>
