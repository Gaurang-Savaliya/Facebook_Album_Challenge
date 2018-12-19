<?php
ob_start();
require_once 'config.php';

  
    if(isset($_SESSION['albummoveall']))
{
   
include_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('332327935943-416fjeevr8n1bsmfnsh4u15hvdp7r7m2.apps.googleusercontent.com');
$client->setClientSecret('RIzv0Qy7oypyLQsd_wnMsNF-');
$client->setRedirectUri('https://gaurangsavaliya4.000webhostapp.com/driveuploadall.php');
$client->setScopes(array('https://www.googleapis.com/auth/drive.file'));

if (isset($_GET['code']) || (isset($_SESSION['access_token']))) {
	
	//$ses_album=$_SESSION['albummoveall'];
	$service = new Google_Service_Drive($client);
	if (isset($_GET['code'])) {
		
		$client->authenticate($_GET['code']);
		$_SESSION['access_token'] = $client->getAccessToken();	
    } else
        $client->setAccessToken($_SESSION['access_token']);
        
        
    if(isset($_SESSION['fb_access_token']) && !empty($_SESSION['fb_access_token'])) {
		try{
			$responce=$fb->get('/me?fields=albums.fields(id,name,cover_photo,photos.fields(name,picture,source))', $_SESSION['fb_access_token']);
		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph return an error : '.$e->getMessage();
			exit();
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}


    
    foreach($_SESSION['albummoveall'] as $albummoveall)
    {   
          $username=$albummoveall[2];
    }
	$masterFolder = new Google_Service_Drive_DriveFile(array(
        'name' => 'facebook_'.$username.'_albums',
        'mimeType' => 'application/vnd.google-apps.folder'
    ));
    $mfid = $service->files->create($masterFolder, array(
        'fields' => 'id'));
    
    
	
    foreach($_SESSION['albummoveall'] as $albummoveall)
    {   
        
    $masterfolderId = $mfid->id;
	$subFolder = new Google_Service_Drive_DriveFile(array(
        'name' => $albummoveall[1],
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents' => array($masterfolderId)
    ));
    $sfid = $service->files->create($subFolder, array(
        'fields' => 'id'));
    
	
	$subfolderId = $sfid->id;
	
	$i=0;

			    
	$url="https://graph.facebook.com/v3.1/".$albummoveall[0]."/photos?fields=source,images,id,album&access_token=".$_SESSION['fb_access_token'];
	$pic=file_get_contents($url);
    $pictures=json_decode($pic);
    $page=(array)$pictures->paging;
    
    
        do
        {
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
			
        
    }
    unset($_SESSION['albummoveall']);
    }
    
} else {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
}

}
header('location:https://gaurangsavaliya4.000webhostapp.com/callback.php');
?>
