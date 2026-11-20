<?php
	$db_host = getenv('DB_HOST') ?: (gethostbyname('db') !== 'db' ? 'db' : 'localhost');
	$db_user = getenv('DB_USER') ?: 'root';
	$db_pass = getenv('DB_PASS') ?: 'landuse@phuket';
	$db_name = getenv('DB_NAME') ?: 'landuse_phuket';

	$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	mysqli_set_charset ( $link , "utf8" );
?>