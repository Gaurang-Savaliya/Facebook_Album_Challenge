<?php
$username=explode("_",$_GET['id']);



require_once 'config.php';

	if(isset($_SESSION['fb_access_token']) && !empty($_SESSION['fb_access_token'])) {
		try{
			$responce=$fb->get('/me?fields=name,first_name,albums.fields(id,name,cover_photo,photos.fields(name,picture,source))', $_SESSION['fb_access_token']);
		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph return an error : '.$e->getMessage();
			exit();
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

	//$fbUserData=$responce->getGraphUser();
	$album_res = $responce->getGraphAlbum()->asArray();
	
	$albums = $album_res['albums'];
   
    if($album_res['first_name']==$username[1])
    {
         if ( !file_exists( "temp/albumall/".$_SESSION['usr'] ) ) {
            mkdir("temp/albumall/".$_SESSION['usr'], 0777,true);
        }
		$zipname = __DIR__ . '/temp/albumall/'.$_SESSION['usr'].'/all_'.$album_res['name'].'.zip';
		$zip = new ZipArchive;
		$zip->open($zipname, ZipArchive::CREATE);
		foreach($albums as $album) 
		{
		    $url="https://graph.facebook.com/v3.1/".$album['id']."/photos?fields=source,images,id,album&access_token=".$_SESSION['fb_access_token'];
	        $pic=file_get_contents($url);
            $pictures=json_decode($pic);
            $page=(array)$pictures->paging;
            do
            {
                foreach($pictures->data as $my)
                {
                    
                    $filename=file_get_contents($my->images[0]->source);
                    $name=explode("?",$my->images[0]->source);
                    $zip->addFromString($album['name'].'/'.basename($name[0]), $filename);    
                    
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
		$zip->close();
		
		echo "temp/albumall/".$_SESSION['usr']."/all_".$_SESSION['usr'].".zip";
	}
	
    
	
} else {
	echo "Unable to download, please try again!";
}
