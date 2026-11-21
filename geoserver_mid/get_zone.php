<?php
// Proxy for WMS GetFeatureInfo requests from js-map/map.js.
// Accepts either:
//   - a same-site path  /geoserver/phuket_lu_law/...  -> sent to the GeoServer container
//   - the remote GeoServer URL (used with ?gs=remote)
// Anything else is rejected.

$url   = $_POST['url'] ?? '';
$parts = parse_url($url);
$path  = $parts['path'] ?? '';
$query = isset($parts['query']) ? '?' . $parts['query'] : '';

if (strpos($path, '/geoserver/phuket_lu_law/') !== 0 || strpos($path, '..') !== false) {
	http_response_code(400);
	exit('Invalid GeoServer URL');
}

$host = strtolower($parts['host'] ?? '');
$port = $parts['port'] ?? 80;

if ($host === '') {
	// Same-site path: GeoServer container inside docker-compose,
	// or a GeoServer on localhost:8082 when running outside Docker (e.g. XAMPP).
	$base = gethostbyname('geoserver') !== 'geoserver' ? 'http://geoserver:8080' : 'http://localhost:8082';
	$target = $base . $path . $query;
} elseif ($host === '52.77.120.244' && $port == 8080) {
	$target = 'http://52.77.120.244:8080' . $path . $query;
} else {
	http_response_code(400);
	exit('Invalid GeoServer URL');
}

$ctx = stream_context_create(['http' => ['timeout' => 15]]);
$result = @file_get_contents($target, false, $ctx);
if ($result === false) {
	http_response_code(502);
	exit('GeoServer request failed');
}
echo $result;
