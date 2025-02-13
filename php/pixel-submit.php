<?php
include("get-location.php");

$pixelId = '4025322777790091';
$accessToken = 'EAANTSjM1VBkBO4fIYujWUAAeRdlbrIMnRZCtwaunqNIjN4ZBjfxUMSo7ZCjkLFTPd7CrPIZATZCaOkPCFHZCkrh6ZCz217NDzbbG6hkTGSpvJ0NmnX8Ol4qtEpy9WtWGMfAzq6OvH83QmUkGcQtAWhBXbgx7fAJCvK9qW4exoCUhd2yY9WUZCVqkylEQVGj4vArZCKgZDZD';
$url = "https://graph.facebook.com/v11.0/$pixelId/events";

$userIP = $_SERVER['REMOTE_ADDR'];
$geoData = getGeoLocation($userIP);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['name'] ?? '';
    $email = $_POST["email"] ?? '';
    $telefone = $_POST["phone"] ?? '';

    if (empty($nome) || empty($email) || empty($telefone)) {
        echo "Todos os campos são obrigatórios.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato de e-mail inválido.";
        exit;
    }

    $emailHash = hash('sha256', strtolower($email));
    $telefoneHash = hash('sha256', $telefone);
    $nomeHash = hash("sha256", $nome);
    $estadoHash = hash('sha256', $geoData['region']);
    $paisHash = hash('sha256', $geoData['country']);
    $cidadeHash = hash('sha256', $geoData['city']);
    $zipHash = hash("sha256", $geoData['zip']);

    $data = [
        'data' => [
            [
                'event_name' => 'Cadastro',
                'event_time' => time(),
                'action_source' => 'website',
                'event_source_url' => 'https://lp.talenteducation.com.br/',
                'user_data' => [
                    'em' => $emailHash,
                    'ph' => $telefoneHash,
                    'fn' => $nomeHash,

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
        // 'test_event_code' => $testEventCode,
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
}
?>