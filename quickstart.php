<?php

require_once __DIR__ . '/vendor/autoload.php';

define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');

# EAM: WHAT'S THIS?
# define('CREDENTIALS_PATH', '~/.credentials/calendar-php-quickstart.json');
define('CREDENTIALS_PATH', '/Users/hmdcadministrator/Downloads/APIProject-a4722b82e133.json.FILE_DOES_NOT_EXIST');
define('CLIENT_SECRET_PATH', '/Users/hmdcadministrator/php-oauth2-example/client_secret_631256725070-1voucmae0h2fs9ej0rc2hgol084lgead.apps.googleusercontent.com.json');

// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/calendar-php-quickstart.json
define('SCOPES', implode(' ', array(
  Google_Service_Calendar::CALENDAR )
));


# $redirectUri = 'http://localhost:8080/alldone.php';
$redirectUri = 'https://oauth-redirect.googleusercontent.com/r/core-stronghold-491';


if (php_sapi_name() != 'cli') {
  throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
  $client = new Google_Client();
  $client->setApplicationName(APPLICATION_NAME);
  $client->setScopes(SCOPES);
  $client->setAuthConfig(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');
  $redirectUri = 'https://oauth-redirect.googleusercontent.com/r/core-stronghold-491';
  $client->setState('1');
  $client->setRedirectUri($redirectUri);

  // Load previously authorized credentials from a file.
  $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = json_decode(file_get_contents($credentialsPath), true);
  } else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl(SCOPES);
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, json_encode($accessToken));
    printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
  }
  return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path) {
  $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
  }
  return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
$client = getClient();
$client->setRedirectUri($redirectUri);
$service = new Google_Service_Calendar($client);

// Print the next 10 events on the user's calendar.
$calendarId = 'primary';
$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => TRUE,
  'timeMin' => date('c'),
);

# $results = $service->events->listEvents($calendarId, $optParams);
$calendarService = new Google_Service_Calendar($client);
$calendar_calendar = new Google_Service_Calendar_Calendar();
$calendar_calendar->setDescription("EAM 1002 Description");
$calendar_calendar->setSummary("EAM 1002 Summary");
$calendar = $calendarService->calendars->insert($calendar_calendar);

$event = new Google_Service_Calendar_Event();

$dt1 = new DateTime("2017-05-16 05:00:00");
$dt2 = new DateTime("2017-05-16 07:00:00");
$calendarDateTime = new Google_Service_Calendar_EventDateTime();
$calendarDateTime->setDateTime($dt1->format(DateTime::RFC3339));
$event->setStart($calendarDateTime);
$calendarDateTime->setDateTime($dt2->format(DateTime::RFC3339));
$event->setEnd($calendarDateTime);
$event->setDescription("Felipe's tacos");
$calendarService->events->insert($calendar->getId(), $event);

if (count($results->getItems()) == 0) {
  print "No upcoming events found.\n";
} else {
  print "Upcoming events:\n";
  foreach ($results->getItems() as $event) {
    $start = $event->start->dateTime;
    if (empty($start)) {
      $start = $event->start->date;
    }
    printf("%s (%s)\n", $event->getSummary(), $start);
  }
}

