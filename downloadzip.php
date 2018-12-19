<?php
require_once 'config.php';
error_reporting(0);
$access_token=$_SESSION['fb_access_token'];
$albumid = $_REQUEST['id'];



if($albumid != '') {

		
		$url="https://graph.facebook.com/v3.1/".$albumid."/photos?fields=source,images,id,album&access_token=".$access_token;
		$pic=file_get_contents($url);
        $pictures=json_decode($pic);
        $page=(array)$pictures->paging;
        
        
        $i=1;
        
        if ( !file_exists( "temp/albumns/".$_SESSION['usr'] ) ) {
            mkdir("temp/albumns/".$_SESSION['usr'], 0777,true);
        }
		$zipname = __DIR__ . '/temp/albumns/'.$_SESSION['usr'].'/'.$albumid. '.zip';
				
		$zip = new ZipArchive;
		$zip->open($zipname, ZipArchive::CREATE);
		
        do
        {
            foreach($pictures->data as $my)
            {
                $filename=file_get_contents($my->images[0]->source);
                $name=explode("?",$my->images[0]->source);
                $zip->addFromString(basename($name[0]), $filename);
                $i++;
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
		$zip->close();
        echo "temp/albumns/".$_SESSION['usr']."/". $albumid . ".zip";
           
        
	
} else {
	echo "Unable to download, please try again!";
}

?>
