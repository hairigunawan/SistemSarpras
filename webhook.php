<?php

const VERIFY_TOKEN     = 'token-saya';
const APP_SECRET       = 'b988277c2aacd294613ab9b1a3872c58';
const ACCESS_TOKEN     = 'EAASuZBf54mZBMBPzLyqAZCAZAMJKrULKEzv05SFt2321qtg0zBrskFxzogZCfYYMQWAk8KuZAAPzQyQ0LCaTg3PebgJ07UNH9ZClMSYEOwiRyZALSmTaETkEhNsRiWJ9fdmodrTgYszcxnb4VmCp3PZBIg8f3MAWcVTg8EDn0OLU2PapxuDpgC0aacnsBTuUW5dE2mBh0H6NZA3Q4LIiJh5cTHjZCsJ36j5BwuIRbFZC1fP53QZCb8QZDZD';
const PHONE_NUMBER_ID  = '819120471293431';
// ------------------------------------------

function respond($code, $body = ''){
    http_response_code($code);
    if ($body !== '') echo $body;
    exit;
}

$rawBody = file_get_contents('php://input');
$method  = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {

    $mode      = $_GET['hub_mode']        ?? $_GET['hub.mode']        ?? null;
    $token     = $_GET['hub_verify_token']?? $_GET['hub.verify_token']?? null;
    $challenge = $_GET['hub_challenge']   ?? $_GET['hub.challenge']   ?? null;

    if ($mode === 'subscribe' && $token === VERIFY_TOKEN) {
        respond(200, $challenge);
    }

    respond(403, 'Verification failed');
}


$signatureHeader = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? null;

if ($signatureHeader) {
    if (strpos($signatureHeader, 'sha256=') === 0) {
        $hash     = substr($signatureHeader, 7);
        $expected = hash_hmac('sha256', $rawBody, APP_SECRET);
        if (!hash_equals($expected, $hash)) {
            respond(401, 'Invalid signature');
        }
    }
}

file_put_contents(__DIR__.'/whatsapp_log.json',
    date('c') . "\n" . $rawBody . "\n\n",
    FILE_APPEND
);

$data = json_decode($rawBody, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    respond(400, 'Invalid JSON');
}

$entries = $data['entry'] ?? [];

foreach ($entries as $entry) {

    foreach ($entry['changes'] ?? [] as $change) {
        $value = $change['value'] ?? [];

        // Incoming messages
        if (!empty($value['messages'])) {

            foreach ($value['messages'] as $message) {

                $from     = $message['from'] ?? null;
                $type     = $message['type'] ?? null;

                if ($type === 'text') {

                    $text = $message['text']['body'] ?? '';

                    $reply = "Terima kasih! Pesan kamu sudah saya terima:\n\n" . $text;

                    sendTextMessage($from, $reply);
                }
            }
        }
    }
}


// Always return 200 OK
respond(200, 'EVENT_RECEIVED');



// ----------------- SEND TEXT MESSAGE -----------------

function sendTextMessage($toNumber, $text) {

    $url = "https://graph.facebook.com/v20.0/" . PHONE_NUMBER_ID . "/messages";

    $payload = [
        'messaging_product' => 'whatsapp',
        'to'   => formatE164($toNumber),
        'type' => 'text',
        'text' => ['body' => $text]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . ACCESS_TOKEN,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $resp   = curl_exec($ch);
    $err    = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Log sending activity
    file_put_contents(__DIR__.'/whatsapp_send.log',
        date('c') .
        "\nSTATUS: $status" .
        "\nREQUEST: " . json_encode($payload) .
        "\nRESPONSE: $resp" .
        "\nERROR: $err\n\n",
        FILE_APPEND
    );

    return ($err === '' && $status >= 200 && $status < 300);
}



// ----------------- FORMAT NUMBER -----------------

function formatE164($number){
    if (strpos($number, '+') === 0) return $number;
    if (strpos($number, '00') === 0) return '+' . substr($number, 2);
    return '+' . $number;
}

?>
