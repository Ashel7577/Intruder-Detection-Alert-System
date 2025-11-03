<?php
require_once 'config.php';

// Get request parameters
$tracking_id = $_GET['id'] ?? '';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
$referer = $_SERVER['HTTP_REFERER'] ?? '';

// Security checks
$attack_indicators = [];
$malicious_patterns = [
    'sql_injection' => ['/union.*select/i', '/\'\s*or\s*\'/i', '/1=1/i'],
    'xss' => ['/<script/i', '/javascript:/i', '/onload=/i'],
    'scanner' => ['/sqlmap/i', '/nikto/i', '/nessus/i']
];

foreach ($malicious_patterns as $type => $patterns) {
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $user_agent) || 
            preg_match($pattern, $referer) || 
            preg_match($pattern, $tracking_id)) {
            $attack_indicators[] = $type;
        }
    }
}

// Get HTTP headers
$headers = apache_request_headers();
$http_headers = json_encode($headers);

// Get device info
$device_info = [];
$device_info['is_mobile'] = preg_match('/mobile|android|iphone/i', $user_agent);
$device_info['browser'] = get_browser_name($user_agent);
$device_info['os'] = get_os_name($user_agent);
$device_info_json = json_encode($device_info);

// Record visit data
$stmt = $pdo->prepare("SELECT id, original_url FROM tracked_links WHERE unique_id = ?");
$stmt->execute([$tracking_id]);
$link_data = $stmt->fetch();

if ($link_data) {
    $link_id = $link_data['id'];
    $original_url = $link_data['original_url'];
    
    // Insert visitor data
    $stmt = $pdo->prepare("INSERT INTO visitor_data (link_id, ip_address, user_agent, referer, http_headers, device_info, attack_indicators) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $link_id,
        $ip_address,
        $user_agent,
        $referer,
        $http_headers,
        $device_info_json,
        json_encode($attack_indicators)
    ]);
    
    // Log malicious activity
    if (!empty($attack_indicators)) {
        error_log("INTRUSION DETECTED: " . implode(', ', $attack_indicators) . " from IP: $ip_address, User-Agent: $user_agent", 3, "/var/log/intrusion_detection.log");
    }
    
    // Redirect to original URL
    header("Location: $original_url");
    exit;
} else {
    die("Invalid tracking link");
}

function get_browser_name($user_agent) {
    if (strpos($user_agent, 'Chrome') !== false) return 'Chrome';
    if (strpos($user_agent, 'Firefox') !== false) return 'Firefox';
    if (strpos($user_agent, 'Safari') !== false) return 'Safari';
    if (strpos($user_agent, 'Opera') !== false) return 'Opera';
    if (strpos($user_agent, 'MSIE') !== false) return 'Internet Explorer';
    return 'Unknown';
}

function get_os_name($user_agent) {
    if (strpos($user_agent, 'Windows') !== false) return 'Windows';
    if (strpos($user_agent, 'Mac') !== false) return 'MacOS';
    if (strpos($user_agent, 'Linux') !== false) return 'Linux';
    if (strpos($user_agent, 'Android') !== false) return 'Android';
    if (strpos($user_agent, 'iPhone') !== false) return 'iOS';
    return 'Unknown';
}
?>
