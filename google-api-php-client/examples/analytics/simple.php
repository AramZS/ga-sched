<?php
require_once '../../src/apiClient.php';
require_once '../../src/contrib/apiAnalyticsService.php';
session_start();

$client = new apiClient();
$client->setApplicationName("Google Analytics PHP Starter Application");

// Visit https://code.google.com/apis/console?api=analytics to generate your
// client id, client secret, and to register your redirect uri.
 $client->setClientId('359543149974.apps.googleusercontent.com');
 $client->setClientSecret('XiingEWM-4ORy4AixPDAEM29');
 $client->setRedirectUri('http://localhost/xampp/wp-test/wp-content/plugins/ga-sched/google-api-php-client/examples/analytics/simple.php');
 $client->setDeveloperKey('AIzaSyDdoJ0dViLGLZ-LzQnpTN-mLvc8bsQ94uc');
$service = new apiAnalyticsService($client);

if (isset($_GET['logout'])) {
  unset($_SESSION['token']);
}

if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken()) {
	
	$queryobj = $service->data_ga->get(
       'ga:46304379',
       '2012-07-29',
       '2012-09-12',
	   'ga:visitors');

	print '<pre>' . print_r($queryobj, true) . '</pre>';
	print $queryobj['totalsForAllResults']['ga:visitors'];
	
	/**$querytime = $service->data_ga->get(
		'ga:46304379',
		'2012-08-29', 
		'2012-09-12',
		'ga:visitors',
		'ga:date',
		'ga:hour',
		'ga:day',
		'ga:dayOfWeek',
		1, 50);**/
		
	$querytime = $client->requestReportData('ga:46304379',array('hour','date'),array('visits'), null, null, '2012-08-29', '2012-09-12', 1, 48);
	
	print '<pre>' . print_r($querytime, true) . '</pre>';

  $props = $service->management_webproperties->listManagementWebproperties("~all");
  print "<h1>Web Properties</h1><pre>" . print_r($props, true) . "</pre>";

  $accounts = $service->management_accounts->listManagementAccounts();
  print "<h1>Accounts</h1><pre>" . print_r($accounts, true) . "</pre>";

  $segments = $service->management_segments->listManagementSegments();
  print "<h1>Segments</h1><pre>" . print_r($segments, true) . "</pre>";

  $goals = $service->management_goals->listManagementGoals("~all", "~all", "~all");
  print "<h1>Segments</h1><pre>" . print_r($goals, true) . "</pre>";

  $_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}