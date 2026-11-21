<?php
	require_once __DIR__ . '/env.php';

	// Settings come from .env (see .env.example). Outside Docker, DB_HOST=db falls back to localhost.
	$db_host = env('DB_HOST', 'localhost');
	if ($db_host === 'db' && gethostbyname('db') === 'db') $db_host = 'localhost';
	$db_user = env('DB_USER', 'root');
	$db_pass = env('DB_PASS', '');
	$db_name = env('DB_NAME', 'landuse_phuket');

	$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	mysqli_set_charset ( $link , "utf8" );
?>
