<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once 'include/src/Google_Client.php';
require_once 'include/src/contrib/Google_PlusService.php';

session_start();

$client = new Google_Client();
$client->setApplicationName("Caarya");
$client->setClientId('670294255692.apps.googleusercontent.com');
$client->setClientSecret('YTaecHScBpubJ3aVRlo6mEZo');
$client->setRedirectUri('http://www.caarya.com/caarya/welcomepage.php');


$plus = new Google_PlusService($client);

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
}

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['access_token'])) {
  $client->setAccessToken($_SESSION['access_token']);
}

if ($client->getAccessToken()) {
  $me = $plus->people->get('me');
		
  // The access token may have been updated lazily.
  $_SESSION['access_token'] = $client->getAccessToken();
  } else {
  $authUrl = $client->createAuthUrl();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <link rel='stylesheet' href='style.css' />
</head>
<body>
<div class="box">


<?php
  if(isset($authUrl)) {
    print "<a class='login' href='$authUrl'><img src='./style/img/loginicons/google.png' alt='Not Found'></a>";
  } else {
  	 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=gmailconnect.php">';
  }
?>
</div>
</body>
</html>