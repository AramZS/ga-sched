<?php
define('ga_email','azs.gmu@gmail.com');
define('ga_password','text%4gM');
define('ga_profile_id','46304379');

require 'gapi.class.php';

$ga = new gapi(ga_email,ga_password);

$ga->requestReportData(ga_profile_id,array('hour','date','day','dayOfWeek'),array('visits'), null, null, '2012-01-11', '2012-01-12', 1, 48);

print '<pre>' . print_r($ga->getResults(), true) . '</pre>';
?>