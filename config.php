<?php
if(!session_id())
	session_start();
require_once 'Facebook/autoload.php';

$app_id='{app-id}'; // Replace {app-id} with your app id
$app_secret='{app-secret}'; // Replace {app-secret} with your app secret
$permissions = ['email,user_photos']; // Optional permissions
$callbackUrl='https://gaurangsavaliya4.000webhostapp.com/callback.php';
$logoutURL='https://gaurangsavaliya4.000webhostapp.com/logout.php';

$fb = new Facebook\Facebook([
  'app_id' => $app_id, 
  'app_secret' => $app_secret,
  'default_graph_version' => 'v3.1'
  ]);

$helper = $fb->getRedirectLoginHelper();


$loginUrl = $helper->getLoginUrl($callbackUrl,$permissions);
?>
