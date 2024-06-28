<?php

function translateText($key, $endpoint, $location, $text, $from, $to) {
    //$url = $endpoint . '/translate?api-version=3.0&from=' . $from . '&to=' . implode('&to=', $to);
    $url = $endpoint . '/translate?api-version=3.0&to=' . implode('&to=', $to);

    $headers = [
        'Ocp-Apim-Subscription-Key: ' . $key,
        'Ocp-Apim-Subscription-Region: ' . $location,
        'Content-Type: application/json',
        'X-ClientTraceId: ' . generateGUID()
    ];

    $data = [
        [
            'text' => $text,
            'type' => 'html'
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function generateGUID() {
    if (function_exists('com_create_guid')) {
        return trim(com_create_guid(), '{}');
    } else {
        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
    }
}

// Your subscription key and endpoint
$key = "78ae9184c54049cc8bf472f29c23824f";
$endpoint = "https://api.cognitive.microsofttranslator.com";
$location = "southafricanorth";

// Text to translate and languages
$text = "<p>I wish to go home</p>";
$from = "en";
$to = ["fr","yo","fr","ar","ig"];

$response = translateText($key, $endpoint, $location, $text, $from, $to);

echo json_encode($response, JSON_PRETTY_PRINT);
?>
