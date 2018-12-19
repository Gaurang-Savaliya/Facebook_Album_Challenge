<?php
ob_start();
require_once 'config.php';

$txt ="|driveupload(".$_SESSION['albumid'].") - ".$_SESSION['usr']." |";
$myfile = file_put_contents('temp/logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

if(isset($_SESSION['albumid']))
{

include_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('332327935943-416fjeevr8n1bsmfnsh4u15hvdp7r7m2.apps.googleusercontent.com');
$client->setClientSecret('RIzv0Qy7oypyLQsd_wnMsNF-');
$client->setRedirectUri('https://gaurangsavaliya4.000webhostapp.com/driveupload.php');
$client->setScopes(array('https://www.googleapis.com/auth/drive.file'));


if (isset($_GET['code']) || (isset($_SESSION['access_token']))) {
	
	
	
	$service = new Google_Service_Drive($client);
	if (isset($_GET['code'])) {
		
		$client->authenticate($_GET['code']);
		$_SESSION['access_token'] = $client->getAccessToken();	
	
    } else
        $client->setAccessToken($_SESSION['access_token']);


    	try{
			$url="https://graph.facebook.com/v3.1/".$_SESSION['albumid'][0]."/photos?fields=source,images,id,album&access_token=".$_SESSION['fb_access_token'];
		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph return an error : '.$e->getMessage();
			exit();
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		
		
		
	$masterFolder = new Google_Service_Drive_DriveFile(array(
        'name' => 'facebook_'.$_SESSION['albumid'][2].'_albums',
        'mimeType' => 'application/vnd.google-apps.folder'
    ));
    $mfid = $service->files->create($masterFolder, array(
        'fields' => 'id'));
    
    
	$masterfolderId = $mfid->id;
	$subFolder = new Google_Service_Drive_DriveFile(array(
        'name' => $_SESSION['albumid'][1],
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents' => array($masterfolderId)
    ));
    $sfid = $service->files->create($subFolder, array(
        'fields' => 'id'));
   
	$subfolderId = $sfid->id;
	$i=0;
	
	$pic=file_get_contents($url);
    $pictures=json_decode($pic);
    $page=(array)$pictures->paging;
      
	
	do{
            foreach($pictures->data as $my)
            {
                
                $fileMetadata = new Google_Service_Drive_DriveFile(array(
                        'name' => $i.".jpg",
                        'parents' => array($subfolderId)
                    ));
                    $i++;
                    
					$content = file_get_contents($my->images[0]->source);
    
                    $file = $service->files->create($fileMetadata, array(
                        'data' => $content,
                        'mimeType' => 'image/jpeg',
                        'uploadType' => 'multipart',
                        'fields' => 'id'));
               
            }
            if(array_key_exists("next",$page))
            {
                $url=$page["next"];
                $pic=file_get_contents($url);
                $pictures=json_decode($pic);
                $page=(array)$pictures->paging;
            }
            else
            {
                $url='none';       
            }
        
        }while($url!='none');
	
    unset($_SESSION['albumid']);
} else {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
}
}
header('location:https://gaurangsavaliya4.000webhostapp.com/callback.php');

?>