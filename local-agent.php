<?php

/**
 * ASHA STABLES LOCAL PRINT AGENT
 * 
 * Instructions:
 * 1. Make sure PHP is installed on the computer running this script (the one in the same network as the printer).
 * 2. Run this script in the terminal/command prompt:
 *    php local-agent.php
 * 3. It will run forever. Keep the black window open. It will check the server every 3 seconds for new print jobs.
 */

// ==========================================
// CONFIGURATION
// ==========================================

// Your live Laravel server domain/IP (e.g., https://your-domain.com or http://system.your-domain.com)
$SERVER_URL = "http://localhost:8000"; // CHANGE THIS BEFORE MOVING TO A DIFFERENT COMPUTER!

// The security token to authorize against your Laravel application
$PRINT_TOKEN = "asha2025printkey"; // Matches PRINT_TOKEN in .env

// Your local Ethernet Printer IP and Port
$PRINTER_IP = "192.168.0.203";
$PRINTER_PORT = 9100;

// Polling interval in seconds
$POLL_INTERVAL = 3;

// ==========================================
// DO NOT EDIT BELOW UNLESS REQUIRED
// ==========================================

echo "=====================================\n";
echo " ASHA STABLES PRINT AGENT STARTING...\n";
echo "=====================================\n";
echo "Server URL: $SERVER_URL\n";
echo "Token: $PRINT_TOKEN\n";
echo "Printer: $PRINTER_IP:$PRINTER_PORT\n";
echo "Frequency: Every $POLL_INTERVAL seconds\n";
echo "=====================================\n\n";

while (true) {
    
    // 1. Fetch pending jobs from server
    $url = $SERVER_URL . "/api/print/pending?token=" . urlencode($PRINT_TOKEN);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    // Disable SSL verification for local dev or misconfigured certs
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200 && $response) {
        $jobs = json_decode($response, true);
        
        if (is_array($jobs) && count($jobs) > 0) {
            echo "[" . date('H:i:s') . "] Found " . count($jobs) . " pending jobs!\n";
            
            foreach ($jobs as $job) {
                echo " -> Processing Job ID: {$job['id']} ... ";
                
                $success = printToEthernet($PRINTER_IP, $PRINTER_PORT, base64_decode($job['payload_base64']));
                
                if ($success) {
                    echo "Printed! ";
                    // Report back to server as done
                    markJobDone($SERVER_URL, $PRINT_TOKEN, $job['id']);
                } else {
                    echo "FAILED to connect to printer.\n";
                }
            }
        }
    } else {
        if ($httpCode !== 0) {
            echo "[" . date('H:i:s') . "] Server error $httpCode when checking queue.\n";
        }
    }

    // Wait before checking again
    sleep($POLL_INTERVAL);
}

/**
 * Connect to the receipt printer via TCP Socket and write RAW binary bytes
 */
function printToEthernet($ip, $port, $content) {
    try {
        $socket = @fsockopen($ip, $port, $errno, $errstr, 3);
        if (!$socket) {
            return false;
        }

        fwrite($socket, $content);
        fclose($socket);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Send an API call back to the server to mark a job as completed
 */
function markJobDone($server_url, $token, $jobId) {
    $url = $server_url . "/api/print/done/" . $jobId . "?token=" . urlencode($token);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['status' => 'printed']));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "Marked done on server.\n";
    } else {
        echo "Failed to mark done (HTTP $httpCode).\n";
    }
}
