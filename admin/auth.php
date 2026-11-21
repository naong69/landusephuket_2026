<?php
// Admin session handling. Include at the very top of every admin page / endpoint.
//   admin_require_page() - for HTML pages: redirects to the login page when not logged in
//   admin_require_api()  - for AJAX endpoints: responds 401 when not logged in

const ADMIN_IDLE_TIMEOUT = 2 * 60 * 60; // log out after 2 hours without activity

function admin_session_start() {
	if (session_status() === PHP_SESSION_ACTIVE) return;
	$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
		|| (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
	session_name('luphuket_admin');
	session_set_cookie_params([
		'lifetime' => 0,
		'path'     => '/',
		'secure'   => $https,
		'httponly' => true,
		'samesite' => 'Lax',
	]);
	session_start();
}

function admin_is_logged_in() {
	admin_session_start();
	if (empty($_SESSION['admin_user'])) return false;
	if (time() - ($_SESSION['admin_last_seen'] ?? 0) > ADMIN_IDLE_TIMEOUT) {
		admin_logout();
		return false;
	}
	$_SESSION['admin_last_seen'] = time();
	return true;
}

function admin_login($user) {
	admin_session_start();
	session_regenerate_id(true); // prevent session fixation
	$_SESSION['admin_user'] = $user;
	$_SESSION['admin_last_seen'] = time();
}

function admin_logout() {
	admin_session_start();
	$_SESSION = [];
	$p = session_get_cookie_params();
	setcookie(session_name(), '', [
		'expires' => time() - 3600, 'path' => $p['path'],
		'secure' => $p['secure'], 'httponly' => $p['httponly'], 'samesite' => $p['samesite'],
	]);
	session_destroy();
}

function admin_require_page() {
	if (!admin_is_logged_in()) {
		header('Location: ./index.php');
		exit;
	}
}

function admin_require_api() {
	if (!admin_is_logged_in()) {
		http_response_code(401);
		header('Content-Type: application/json');
		exit('{"error":"not logged in"}');
	}
	session_write_close(); // release the session lock so parallel AJAX calls don't queue
}
