<?php


require __DIR__ . '/vendor/autoload.php';


$client = new Google_Client();

$client->setScopes(Google_Service_Drive::DRIVE);

//credentials สร้าง app กากๆขึ้นมา
$client->setAuthConfig('credentials.json');
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

while (true) {
    
    $tokenPath = 'token.json';
    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $client->setAccessToken($accessToken);
    print_r("Refresh Token:" . $client->getRefreshToken() . PHP_EOL." Access Token:" . $client->getAccessToken()["access_token"]);
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    echo PHP_EOL . "########################################################" . PHP_EOL;
    file_put_contents($tokenPath, json_encode($client->getAccessToken()));
}
