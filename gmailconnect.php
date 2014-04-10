<?php

require_once 'gmailauth.php';
 
  $url = filter_var($me['url'], FILTER_VALIDATE_URL);
  $img = filter_var($me['image']['url'], FILTER_VALIDATE_URL);
  $name = filter_var($me['displayName'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
  $personMarkup = "<a rel='me' href='$url'>$name</a><div><img src='$img'></div>";

if(isset($personMarkup)): ?>
<div class="me"><?php print $personMarkup; ?></div>
<?php endif; 


 if(isset($authUrl)) {
print "<a class='logout' href='?logout'>Logout</a>";
}


?>