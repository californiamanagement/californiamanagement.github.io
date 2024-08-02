<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    $action = $data['action'];

    switch ($action) {
        case 'forwardToDiscord':
            forwardToDiscord($data);
            break;
        // Add more cases for different actions here
        default:
            echo json_encode(['error' => 'Unknown action']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

function forwardToDiscord($data) {
    $message = $data['message'];
    $playerName = $data['playerName'];
    $webhookUrl = 'https://discord.com/api/webhooks/1269076552684015691/Fc5zYCkWCNYrPEBM9Hzqbp4k70xbCj-WQ7HgapElKfK65AWrr_FCdsGK_6-KByxkRV_g';

    $discordData = [
        'content' => "Message from $playerName: $message"
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($discordData),
        ],
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($webhookUrl, false, $context);

    if ($result === FALSE) {
        http_response_code(500);
        echo json_encode(['error' => 'Error forwarding message to Discord']);
    } else {
        echo json_encode(['success' => 'Message forwarded to Discord']);
    }
}
?>
