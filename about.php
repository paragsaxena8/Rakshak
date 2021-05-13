<?php

define( 'RAKSHAK_WEB_PAGE_TO_ROOT', '' );
require_once RAKSHAK_WEB_PAGE_TO_ROOT . 'RAKSHAK/includes/RAKSHAKPage.inc.php';

RAKSHAKPageStartup( array( 'phpids' ) );

$page = RAKSHAKPageNewGrab();
$page[ 'title' ]   = 'About' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'about';

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h2>About</h2>
	<p>Version " . RAKSHAKVersionGet() . " (Release date: " . RAKSHAKReleaseDateGet() . ")</p>
	<p>RAKSHAK Vulnerable Web Application (RAKSHAK) is a PHP/MySQL web application that is RAKSHAK vulnerable. Its main goals are to be an aid for security professionals to test their skills and tools in a legal environment, help web developers better understand the processes of securing web applications and aid teachers/students to teach/learn web application security in a class room environment</p>
	<p>The official documentation for RAKSHAK can be found <a href=\"docs/RAKSHAK_v1.3.pdf\">here</a>.</p>
	<p>All material is copyright 2008-2015 RandomStorm & Ryan Dewhurst.</p>

	<h2>Links</h2>
	<ul>
		<li>Homepage: " . RAKSHAKExternalLinkUrlGet( 'http://www.cybersquareinfo.com/' ) . "</li>
		<li>Project Home: " . RAKSHAKExternalLinkUrlGet( 'https://github.com/ParagSaxena08/RAKSHAK' ) . "</li>
		<li>Bug Tracker: " . RAKSHAKExternalLinkUrlGet( 'https://github.com/ParagSaxena08/RAKSHAK/issues' ) . "</li>
		<li>Souce Control: " . RAKSHAKExternalLinkUrlGet( 'https://github.com/ParagSaxena08/RAKSHAK/commits/master' ) . "</li>
		<li>Wiki: " . RAKSHAKExternalLinkUrlGet( 'https://github.com/ParagSaxena08/RAKSHAK/wiki' ) . "</li>
	</ul>

	<h2>Credits</h2>
	<ul>
		<li>Brooks Garrett: " . RAKSHAKExternalLinkUrlGet( 'http://brooksgarrett.com/','www.brooksgarrett.com' ) . "</li>
		<li>Craig</li>
		<li>g0tmi1k: " . RAKSHAKExternalLinkUrlGet( 'https://blog.g0tmi1k.com/','g0tmi1k.com' ) . "</li>
		<li>Jamesr: " . RAKSHAKExternalLinkUrlGet( 'https://www.creativenucleus.com/','www.creativenucleus.com' ) . " / " . RAKSHAKExternalLinkUrlGet( 'http://www.designnewcastle.co.uk/','www.designnewcastle.co.uk' ) . "</li>
		<li>Jason Jones: " . RAKSHAKExternalLinkUrlGet( 'http://www.linux-ninja.com/','www.linux-ninja.com' ) . "</li>
		<li>RandomStorm: " . RAKSHAKExternalLinkUrlGet( 'https://www.randomstorm.com/','www.randomstorm.com' ) . "</li>
		<li>Ryan Dewhurst: " . RAKSHAKExternalLinkUrlGet( 'https://www.dewhurstsecurity.com/','www.dewhurstsecurity.com' ) . "</li>
		<li>Shinkurt: " . RAKSHAKExternalLinkUrlGet( 'http://www.paulosyibelo.com/','www.paulosyibelo.com' ) . "</li>
		<li>Tedi Heriyanto: " . RAKSHAKExternalLinkUrlGet( 'http://tedi.heriyanto.net/','tedi.heriyanto.net' ) . "</li>
		<li>Tom Mackenzie: " . RAKSHAKExternalLinkUrlGet( 'https://www.tmacuk.co.uk/','www.tmacuk.co.uk' ) . "</li>
	</ul>
	<ul>
		<li>PHPIDS - Copyright (c) 2007 " . RAKSHAKExternalLinkUrlGet( 'http://github.com/PHPIDS/PHPIDS', 'PHPIDS group' ) . "</li>
	</ul>

	<h2>License</h2>
	<p>RAKSHAK Vulnerable Web Application (RAKSHAK) is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.</p>
	<p>The PHPIDS library is included, in good faith, with this RAKSHAK distribution. The operation of PHPIDS is provided without support from the RAKSHAK team. It is licensed under <a href=\"" . RAKSHAK_WEB_PAGE_TO_ROOT . "instructions.php?doc=PHPIDS-license\">separate terms</a> to the RAKSHAK code.</p>

	<h2>Development</h2>
	<p>Everyone is welcome to contribute and help make RAKSHAK as successful as it can be. All contributors can have their name and link (if they wish) placed in the credits section. To contribute pick an Issue from the Project Home to work on or submit a patch to the Issues list.</p>
</div>\n";

RAKSHAKHtmlEcho( $page );

exit;

?>
