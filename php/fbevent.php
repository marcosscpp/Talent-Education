<?php
    include("get-location.php");
    $json = file_get_contents('php://input');
    $userIP = $_SERVER['REMOTE_ADDR'];
    $geoData = getGeoLocation($userIP);

    $pixelId = '4025322777790091';
    $accessToken = 'EAANTSjM1VBkBO4fIYujWUAAeRdlbrIMnRZCtwaunqNIjN4ZBjfxUMSo7ZCjkLFTPd7CrPIZATZCaOkPCFHZCkrh6ZCz217NDzbbG6hkTGSpvJ0NmnX8Ol4qtEpy9WtWGMfAzq6OvH83QmUkGcQtAWhBXbgx7fAJCvK9qW4exoCUhd2yY9WUZCVqkylEQVGj4vArZCKgZDZD';
    $url = "https://graph.facebook.com/v11.0/$pixelId/events";

    $estadoHash = isset($geoData['region']) ? hash('sha256', $geoData['region']) : '';
    $paisHash = isset($geoData['country']) ? hash('sha256', $geoData['country']) : '';
    $cidadeHash = isset($geoData['city']) ? hash('sha256', $geoData['city']) : '';
    $zipHash = isset($geoData['zip']) ? hash("sha256", $geoData['zip']) : '';

    $eventData = json_decode($json, true);

    $data = [
        'data' => [
            [
                'event_name' => $eventData["eventName"],
                'event_time' => time(),
                'action_source' => 'website',
                'event_source_url' => 'https://lp.talenteducation.com.br/',
                'user_data' => [
                    'external_id' => $_SERVER['REMOTE_ADDR'],
                    'client_ip_address' => $_SERVER['REMOTE_ADDR'],
                    'client_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'fbc' => filter_input(INPUT_COOKIE, '_fbc') ? filter_input(INPUT_COOKIE, '_fbc') : null,
                    'fbp' => filter_input(INPUT_COOKIE, '_fbp'),
                    'st' => $estadoHash,
                    'country' => $paisHash,
                    'ct' => $cidadeHash, 
                    'zp' => $zipHash,
                ],
            ],
        ],
        'access_token' => $accessToken,
    ];


    $jsonData = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if ($response === false) {
            echo "Erro ao enviar o evento: " . curl_error($ch);
        } else {
            echo "Evento enviado com sucesso: " . $response;
        }

        curl_close($ch);
?>