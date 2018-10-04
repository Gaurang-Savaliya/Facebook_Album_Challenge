<?php
if(!session_id())
	session_start();
require_once 'Facebook/autoload.php';

$app_id='2079316965616123';
$app_secret='844fd17e189df932e3a3ea1327f855ce';
$permissions = ['email,user_photos']; // Optional permissions
$callbackUrl='https://gaurangsavaliyart.herokuapp.com/callback.php';
$logoutURL='https://gaurangsavaliyart.herokuapp.com/logout.php';

$fb = new Facebook\Facebook([
  'app_id' => $app_id, // Replace {app-id} with your app id
  'app_secret' => $app_secret,
  'default_graph_version' => 'v2.2'
  ]);

$helper = $fb->getRedirectLoginHelper();


$loginUrl = $helper->getLoginUrl($callbackUrl,$permissions);
?>