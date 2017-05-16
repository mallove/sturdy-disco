<?php

require_once __DIR__ . '/vendor/autoload.php';

define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');

# EAM: WHAT'S THIS?
# define('CREDENTIALS_PATH', '~/.credentials/calendar-php-quickstart.json');
define('CREDENTIALS_PATH', '/Users/hmdcadministrator/Downloads/APIProject-a4722b82e133.json');
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
$calendarList = $service->calendarList;

// Print the next 10 events on the user's calendar.
$calendarId = 'primary';
$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => TRUE,
  'timeMin' => date('c'),
);

$calendarService = new Google_Service_Calendar($client);

# Create a test calendar
$calendar_calendar = new Google_Service_Calendar_Calendar();
$calendar_calendar->setDescription("EAM 1002 Description");
$calendar_calendar->setSummary("EAM 1002 Summary");
$calendar = $calendarService->calendars->insert($calendar_calendar);

# Create test event and add it to the calendar
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

# List events to verify this is the one we want to delete
# $l = $calendarList->listCalendarList();
# var_dump($l);
# $calendarId = "u9t25rgktomt5s98lkv49cmon0@group.calendar.google.com";
# $r = $service->events->listEvents($calendarId, $optParams);
# var_dump($r);

#########################
#
# Clean up test calendars
#
#########################


# $calendarIdsToDelete = array(
#   "u9t25rgktomt5s98lkv49cmon0@group.calendar.google.com",
#   "v7nja0am0d9qdm524iuaeu3t0c@group.calendar.google.com",
#   "ktkvnni9k7p2iv4lct7kkr0sp8@group.calendar.google.com",
#   "m2dvpfgu4ufp8n5mtvhsdlg4us@group.calendar.google.com",
#   "ac7rpujj9mc906r34rk3i741e4@group.calendar.google.com",
#   "rcda73oii0c2pd15eq3uppvjk0@group.calendar.google.com",
#   "ot72mtphkrm36q9b6hobm3ftlc@group.calendar.google.com",
#   "0k85ucandjl676lbe9j5bgs5o4@group.calendar.google.com",
#   "tepvdd9r7kqprvb6iquag32nss@group.calendar.google.com",
#   "1ce3jr3lji4ncicni7nqi83ljo@group.calendar.google.com",
#   "qb0bm0sdk117756ng81e5n0vp0@group.calendar.google.com",
#   "lgbnv4244q4add89j91k9fsjbg@group.calendar.google.com"
# );
# 
# 
# foreach ($calendarIdsToDelete as $i) {
#   try {
#     $calendarService->calendars->delete($i);
#   } catch (Exception $e) {
#     print "e = " . var_export($e, true);
#   }
# }
# 
# var_dump($r);
