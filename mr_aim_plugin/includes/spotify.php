<?php
/**
 * Client ID for Spotify API authentication.
 *
 * @var string $client_id
 */
$client_id = 'fa5fe9ed9ae748579b4239bee89506fb';

/**
 * Client secret for Spotify API authentication.
 *
 * @var string $client_secret
 */
$client_secret = '3e195efd3c9141968ed5cee686bfdc2b';

/**
 * Authentication options for obtaining an access token.
 *
 * @var array $authOptions
 */
$authOptions = array(
    'url' => 'https://accounts.spotify.com/api/token',
    'headers' => array(
        'Authorization' => 'Basic ' . base64_encode("$client_id:$client_secret")
    ),
    'form_params' => array(
        'grant_type' => 'client_credentials'
    )
);

/**
 * Initializes a cURL request for obtaining an access token.
 *
 * @var resource $curl
 */
$curl = curl_init();

// Set cURL options
curl_setopt_array($curl, array(
    CURLOPT_URL => $authOptions['url'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($authOptions['form_params']),
    CURLOPT_HTTPHEADER => array(
        'Authorization: ' . $authOptions['headers']['Authorization'],
        'Content-Type: application/x-www-form-urlencoded'
    )
));

/**
 * Executes the cURL request and stores the response.
 *
 * @var mixed $response
 */
$response = curl_exec($curl);

/**
 * Decodes the JSON response to obtain the access token.
 *
 * @var array $tokenData
 */
$tokenData = json_decode($response, true);

/**
 * Access token obtained from the Spotify API.
 *
 * @var string $accessToken
 */
$accessToken = $tokenData['access_token'];

//echo $accessToken;

/**
 * Closes the cURL session.
 */
curl_close($curl);
