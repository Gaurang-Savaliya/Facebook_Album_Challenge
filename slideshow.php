<?php
require_once 'config.php';

$txt = "|Slideshow|".$_GET['id'];
$myfile = file_put_contents('logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

$album_id=$_GET['id'];

?>
<html>
<head>
	<title>Album Slideshow</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="css/style8.css"type="text/css"/>
	<link href="css/lightbox.min.css" rel="stylesheet">
</head>
<body>
	<div class="container">
	    <div class="row">
	        <div class="col-md-12 col-sm-12 col-xs-12">
	            <a href="https://gaurangsavaliya4.000webhostapp.com/callback.php" style="padding:2%;background:darkblue;color:#fff;text-decoration:none;">Back to Home</a>
	            
	        </div>
	    </div>
	</div>
	
	
<?php
if($album_id != '') {
	if(isset($_SESSION['fb_access_token']) && !empty($_SESSION['fb_access_token'])) {
		try{
			$url="https://graph.facebook.com/v3.1/".$album_id."/photos?fields=source,images,id,album&access_token=".$_SESSION['fb_access_token'];
		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph return an error : '.$e->getMessage();
			exit();
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
        $pic=file_get_contents($url);
        $pictures=json_decode($pic);
        $page=(array)$pictures->paging;
        
        do
        {
            foreach($pictures->data as $my)
            {
                $name=explode("?",$my->images[0]->source);
                
                ?>
                <center>
                
						    <img class="mySlides" src="<?php echo $my->images[0]->source; ?>" style="transition:0.3;border:5px solid #000;height:auto;max-height: 560px;width:auto;max-width:900;" />
						
                </center>
                <?php
               
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
} else {
	echo "Unable to SlideShow, please try again!";
}
?>

	
	
<script>
	
var slideIndex = 0;
SlideShow();

function SlideShow() {
    var i;
    var x = document.getElementsByClassName("mySlides");
    for (i = 0; i < x.length; i++) {
      x[i].style.display = "none"; 
    }
    slideIndex++;
    if (slideIndex > x.length) {slideIndex = 1} 
    x[slideIndex-1].style.display = "block";
    setTimeout(SlideShow, 2000);
}
</script>
</body>
</html>
