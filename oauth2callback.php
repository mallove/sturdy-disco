<?php
require_once __DIR__.'/vendor/autoload.php';
# require_once '/Users/hmdcadministrator/php-oauth2-example/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfigFile('client_secret_631256725070-1voucmae0h2fs9ej0rc2hgol084lgead.apps.googleusercontent.com.json');
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
#$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
$client->addScope(Google_Service_Calendar::CALENDAR);

error_log("EAM Entering " . __FILE__ . ":" . __LINE__);
error_log("EAM \$_SESSION = " . var_export($client, true));

if (! isset($_GET['code'])) {

  error_log("EAM Entering " . __FILE__ . ":" . __LINE__);
  error_log("EAM \$_SESSION = " . var_export($_SESSION, true));

  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));

} else {

  error_log("EAM Entering " . __FILE__ . ":" . __LINE__);
  error_log("EAM \$_SESSION = " . var_export($_SESSION, true));

  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();

  # $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
  # header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
