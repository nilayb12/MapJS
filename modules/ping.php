<?php
header('Content-Type: application/json');

$host = $_POST['host'] ?? $_GET['host'] ?? null;
if (!$host) {
    echo json_encode(["status" => "error", "message" => "No host specified"]);
    exit;
}

$host = escapeshellarg($host);
$isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

// Build command
$cmd = $isWin
    ? "ping -n 1 -w 1000 $host"
    : "ping -c 1 -W 1 $host";

$output = shell_exec($cmd);

// Windows parsing
if ($isWin) {
    if (preg_match('/time[=<]\s*([0-9<]+)ms/i', $output, $m)) {
        $lat = ($m[1] === '<1') ? 1 : intval($m[1]);
        echo json_encode(["status" => "online", "latency" => $lat, "raw" => $output]);
        exit;
    }
}

// Linux/mac parsing
else {
    if (preg_match('/time=([\d.]+)\s*ms/i', $output, $m)) {
        $lat = floatval($m[1]);
        echo json_encode(["status" => "online", "latency" => $lat, "raw" => $output]);
        exit;
    }
}

// If parsing failed
echo json_encode(["status" => "offline", "latency" => null, "raw" => $output]);