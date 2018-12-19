<?php
if(!session_id())
	session_start();
require_once 'Facebook/autoload.php';

$app_id='453697758442568';
$app_secret='3df6b2164cf31fb90951e5591ce1c197';
$permissions = ['email,user_photos']; // Optional permissions
$callbackUrl='https://gaurangsavaliya4.000webhostapp.com/callback.php';
$logoutURL='https://gaurangsavaliya4.000webhostapp.com/logout.php';

$fb = new Facebook\Facebook([
  'app_id' => $app_id, // Replace {app-id} with your app id
  'app_secret' => $app_secret,
  'default_graph_version' => 'v3.1'
  ]);

$helper = $fb->getRedirectLoginHelper();


$loginUrl = $helper->getLoginUrl($callbackUrl,$permissions);
?>