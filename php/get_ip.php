// Returns the visitor's IP address as JSON, in the same shape js/main.js
// expected from ip-api.com:  {"query": "1.2.3.4"}
// Replaces the call to http://ip-api.com/json, which has no HTTPS on its free plan.

header('Content-Type: application/json');
header('Cache-Control: no-store');

$ip = $_SERVER['REMOTE_ADDR'] ?? '';

// Behind Caddy (or another reverse proxy) REMOTE_ADDR is the proxy's address,
// and the real visitor IP is the first entry in X-Forwarded-For.
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $first = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    if (filter_var($first, FILTER_VALIDATE_IP)) {
        $ip = $first;
    }
}

echo json_encode(['query' => $ip]);