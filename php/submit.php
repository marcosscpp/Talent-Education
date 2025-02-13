<?php

function sendJsonResponse($status, $message) {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

function formatPhoneNumber($phone) {
    $phone = preg_replace('/\D/', '', $phone);
    
    if (strlen($phone) < 3) {
        return null;
    }

    $ddi = "55";
    $ddd = substr($phone, 0, 2);
    $numeroSemDDD = substr($phone, 2);

    return "+{$ddi} {$ddd} {$numeroSemDDD}";
}

function sendLeadRequest($data, $headers) {
    $url = "https://talentedu.kommo.com/api/v4/leads/complex";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    $utm_source = isset($_POST['utm_source']) ? htmlspecialchars($_POST['utm_source']) : 'Desconhecido';
    $utm_medium = isset($_POST['utm_medium']) ? htmlspecialchars($_POST['utm_medium']) : 'Desconhecido';
    $utm_campaign = isset($_POST['utm_campaign']) ? htmlspecialchars($_POST['utm_campaign']) : 'Desconhecido';
    $utm_content = isset($_POST['utm_content']) ? htmlspecialchars($_POST['utm_content']) : 'Desconhecido';


    if (empty($name) || empty($email) || empty($phone)) {
        sendJsonResponse('error', 'Todos os campos são obrigatórios.');
    }

    $formattedPhone = formatPhoneNumber($phone);
    if (!$formattedPhone) {
        sendJsonResponse('error', 'Número de telefone inválido.');
    }

    $headers = [
        "accept: application/json",
        "content-type: application/json",
        "authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ2MmJmN2EyZmM4NGFiZDVkODIwNjUyNjNmY2VkYjlmNzEyZjk0MWExZTI5YjRkNzZjNGY4NzRiYWRlOWZlM2JlNzE0MmM1N2FlMjk4OGVjIn0.eyJhdWQiOiI1Zjk0MTUzYi05M2EwLTQ0YTUtYWI5Zi00YzhhMWQ0NGI4MDIiLCJqdGkiOiI0NjJiZjdhMmZjODRhYmQ1ZDgyMDY1MjYzZmNlZGI5ZjcxMmY5NDFhMWUyOWI0ZDc2YzRmODc0YmFkZTlmZTNiZTcxNDJjNTdhZTI5ODhlYyIsImlhdCI6MTczODI1NjU1OCwibmJmIjoxNzM4MjU2NTU4LCJleHAiOjE4OTU5NjE2MDAsInN1YiI6IjEyNjExMjU5IiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjM0MDgxNzA3LCJiYXNlX2RvbWFpbiI6ImtvbW1vLmNvbSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJjcm0iLCJmaWxlcyIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiLCJwdXNoX25vdGlmaWNhdGlvbnMiXSwiaGFzaF91dWlkIjoiZWEzMTdkOWYtYTc3Ni00ZDgzLWFmMDgtZmMyNGZkODgwNzc1IiwiYXBpX2RvbWFpbiI6ImFwaS1jLmtvbW1vLmNvbSJ9.X3bkxJ85r_H_tw6-Q_5IK44Alsr33bJ0-80babiFRhvjBFDu1YVEes7GNwajGidWNOY8DJwQVyZH0OXNVFfP3uf9qkrCn8HudEIPb8T6_sI3TjglR8bCa4bAx9TZbBVfZ8R0Amjs9mD2naElz2na1h2jbcc4JCG2zuTOCzU7PozDp9jfjb8cvUVlzza8blc7Fb6eoT_tqkrH0kZO0NUNShrE43cSdFb1jk4URBJ98kzZsxuSSxLdX0vUo3CUf1WrDN2JGy1Cz_4RpBQjxAuABo_mbK1FfHas_3KPUP_ped-uZXoufBRLAReKuY22EHh1cA7IOMV2sfqDOknhWFTXoQ"
    ];

    $data = [
        [
            "_embedded" => [
                "contacts" => [
                    [
                        "name" => $name,
                        "first_name" => explode(' ', $name)[0],
                        "last_name" => explode(' ', $name)[1] ?? '',
                        "custom_fields_values" => [
                            [
                                "field_id" => 542024,
                                "values" => [["value" => $formattedPhone]]
                            ],
                            [
                                "field_id" => 542026,
                                "values" => [["value" => $email]]
                            ],
                            [
                                "field_id" => 1006990,
                                "values" => [["value" => $utm_content]]
                            ],
                            [
                                "field_id" => 1006988,
                                "values" => [["value" => $utm_campaign]]
                            ],
                            [
                                "field_id" => 1006986,
                                "values" => [["value" => $utm_medium]]
                            ],
                            [
                                "field_id" => 1006984,
                                "values" => [["value" => $utm_source]]
                            ]
                        ]
                    ]
                ],
            ],
            "pipeline_id" => 10445363,
            "name" => $name
        ]
    ];

    $response = json_decode(sendLeadRequest($data, $headers));

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'Dados enviados com sucesso!',
        'response' => $response
    ]);

} else {
    sendJsonResponse('error', 'Método inválido. Use o método POST.');
}
