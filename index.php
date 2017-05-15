<?php
require_once __DIR__.'/vendor/autoload.php';
//  require_once '/Users/hmdcadministrator/php-oauth2-example/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('client_secret_631256725070-1voucmae0h2fs9ej0rc2hgol084lgead.apps.googleusercontent.com.json');
// $client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
$client->addScope(Google_Service_Calendar::CALENDAR);

error_log("EAM Entering " . __FILE__ . ":" . __LINE__);
error_log("EAM \$_SESSION = " . var_export($client, true));

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

  error_log("EAM Entering " . __FILE__ . ":" . __LINE__);
  error_log("EAM \$_SESSION = " . var_export($_SESSION, true));

  $client->setAccessToken($_SESSION['access_token']);
  $calendar = new Google_Service_Calendar($client);
  $new_cal = $calendar->calendars->insert("EAM 539 foo");
  echo json_encode($new_cal);

} else {

  error_log("EAM Entering " . __FILE__ . ":" . __LINE__);
  error_log("EAM \$_SESSION = " . var_export($_SESSION, true));

  # Too many redirects error
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
