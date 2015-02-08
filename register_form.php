<?php

$file = 'groups.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= htmlspecialchars($_POST['email']) . "," . htmlspecialchars($_POST['groupname']) . "\n";
// Write the contents back to the file
file_put_contents($file, $current, FILE_APPEND | LOCK_EX);

$server = "tcp:kjmxxr7pc2.database.windows.net,1433";
$user = "snapyak@kjmxxr7pc2";
$pwd = "h@rv@rdy4k!";
$db = "snapyak_db";

$conn = sqlsrv_connect($server, array("UID"=>$user, "PWD"=>$pwd, "Database"=>$db));

if($conn === false){
    die(print_r(sqlsrv_errors()));
}
else echo("connected to db");

echo ('<!DOCTYPE HTML>
<!--
	Big Picture by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>SnapYak</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.poptrox.min.js"></script>
		<script src="js/jquery.scrolly.min.js"></script>
		<script src="js/jquery.scrollex.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-wide.css" />
			<link rel="stylesheet" href="css/style-normal.css" />
		</noscript>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie/v9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
	</head>
	<body>

		<!-- Intro -->
			<section id="intro" class="main style3 dark fullscreen">
				<div class="content container 75%">
					<header>
						<h2>Thanks for registering!</h2>
					</header>
					<p>An email will be sent to you shortly when your channel is ready to go. <strong></strong>
				</div>
				<br>
			</section>
			
	</body>
</html>');
?>