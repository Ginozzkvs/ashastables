<?php
/**
 * ASHA STABLES - Local Print Agent
 *
 * Run this on a PC in the same network as the printer:
 *   php print-agent.php
 *
 * It polls the server for pending print jobs and sends them
 * to the Xprinter XP-80C at 192.168.0.203:9100.
 */

// ── Configuration ──────────────────────────────────────────
$serverUrl   = 'https://13.238.127.170';   // AWS server
$printToken  = 'asha2025printkey';          // Must match PRINT_TOKEN in server .env
$printerIp   = '192.168.0.203';            // Xprinter XP-80C IP
$printerPort = 9100;
$pollSeconds = 3;                          // How often to check for new jobs
// ────────────────────────────────────────────────────────────

echo "=== ASHA STABLES Print Agent ===\n";
echo "Server:  $serverUrl\n";
echo "Printer: $printerIp:$printerPort\n";
echo "Polling every {$pollSeconds}s...\n\n";

// Allow self-signed / mismatched SSL on dev
$streamContext = stream_context_create([
    'ssl' => [
        'verify_peer'      => false,
        'verify_peer_name' => false,
    ],
]);

while (true) {
    try {
        // 1. Fetch pending jobs
        $url  = "$serverUrl/api/print/pending?token=" . urlencode($printToken);
        $json = @file_get_contents($url, false, $streamContext);

        if ($json === false) {
            echo date('H:i:s') . " [WARN] Cannot reach server\n";
            sleep($pollSeconds);
            continue;
        }

        $jobs = json_decode($json, true);

        if (empty($jobs)) {
            sleep($pollSeconds);
            continue;
        }

        foreach ($jobs as $job) {
            $jobId = $job['id'];
            $data  = $job['receipt_data'];

            echo date('H:i:s') . " [JOB $jobId] Printing for {$data['member_name']}... ";

            // 2. Format receipt
            $receipt = formatReceipt($data);

            // 3. Send to printer
            $printed = sendToPrinter($receipt, $printerIp, $printerPort);

            // 4. Mark job done on server
            $status   = $printed ? 'printed' : 'failed';
            $doneUrl  = "$serverUrl/api/print/done/$jobId?token=" . urlencode($printToken);
            $postData = http_build_query(['status' => $status]);
            $opts     = stream_context_create([
                'http' => [
                    'method'  => 'POST',
                    'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => $postData,
                ],
                'ssl' => [
                    'verify_peer'      => false,
                    'verify_peer_name' => false,
                ],
            ]);
            @file_get_contents($doneUrl, false, $opts);

            echo ($printed ? "OK" : "FAILED") . "\n";
        }
    } catch (Exception $e) {
        echo date('H:i:s') . " [ERROR] " . $e->getMessage() . "\n";
    }

    sleep($pollSeconds);
}

// ── Receipt Formatter (ESC/POS) ────────────────────────────
function formatReceipt($data)
{
    $receipt = "";
    $divider = str_repeat("=", 42);

    // Reset printer
    $receipt .= "\x1B\x40";
    // Center align
    $receipt .= "\x1B\x61\x01";

    // Header
    $receipt .= "\x1B\x45\x01"; // Bold ON
    $receipt .= "ASHA STABLES\n";
    $receipt .= "Member Activity Receipt\n";
    $receipt .= "\x1B\x45\x00"; // Bold OFF
    $receipt .= $divider . "\n\n";

    // Receipt info - left align
    $receipt .= "\x1B\x61\x00";
    $receipt .= "Receipt ID: " . ($data['receipt_id'] ?? 'AUTO') . "\n";
    $receipt .= "Date/Time: " . ($data['timestamp'] ?? date('d-m-Y H:i')) . "\n";
    $receipt .= $divider . "\n\n";

    // Member info
    $receipt .= "\x1B\x45\x01";
    $receipt .= "Member Information\n";
    $receipt .= "\x1B\x45\x00";
    $receipt .= "Name: " . ($data['member_name'] ?? '-') . "\n";
    $receipt .= "Card ID: " . ($data['card_uid'] ?? '-') . "\n";
    $receipt .= "Type: " . ($data['membership_name'] ?? 'Standard Membership') . "\n";
    $receipt .= $divider . "\n\n";

    // Activity
    $receipt .= "\x1B\x45\x01";
    $receipt .= "Activity Details\n";
    $receipt .= "\x1B\x45\x00";
    $receipt .= "Activity: " . ($data['activity_name'] ?? '-') . "\n";
    $receipt .= "Sessions Used: 1\n";
    $receipt .= "Staff: Member Staff\n";
    $receipt .= $divider . "\n\n";

    // Session balance
    $used      = $data['used_count'] ?? $data['used_sessions'] ?? 0;
    $remaining = $data['remaining_count'] ?? $data['remaining_sessions'] ?? 0;
    $total     = $used + $remaining;

    $receipt .= "\x1B\x45\x01";
    $receipt .= "Session Balance\n";
    $receipt .= "\x1B\x45\x00";
    $receipt .= "Sessions Used: " . str_pad($used, 20, '.', STR_PAD_LEFT) . "\n";
    $receipt .= "Sessions Left: " . str_pad($remaining, 19, '.', STR_PAD_LEFT) . "\n";
    $receipt .= "Total Sessions: " . str_pad($total, 18, '.', STR_PAD_LEFT) . "\n";
    $receipt .= $divider . "\n\n";

    // Status - center
    $receipt .= "\x1B\x61\x01";
    $receipt .= "\x1B\x45\x01";
    $receipt .= "COMPLETED + APPROVED\n";
    $receipt .= "\x1B\x45\x00";
    $receipt .= $divider . "\n\n";

    // Footer
    $receipt .= "Thank you for using\n";
    $receipt .= "ASHA STABLES\n";
    $receipt .= "Please keep this receipt\n\n\n\n\n";

    // Cut paper
    $receipt .= "\x1D\x56\x01"; // Partial cut
    $receipt .= "\x1B\x40";     // Reset

    return $receipt;
}

// ── Send to Ethernet Printer ───────────────────────────────
function sendToPrinter($content, $ip, $port)
{
    $socket = @fsockopen($ip, $port, $errno, $errstr, 3);
    if (!$socket) {
        echo "[PRINTER ERROR: $errstr] ";
        return false;
    }
    fwrite($socket, $content);
    fclose($socket);
    return true;
}
