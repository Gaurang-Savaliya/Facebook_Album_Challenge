<?php
ob_start();
require_once 'config.php';
if(isset($_SESSION['fb_access_token']) && !empty($_SESSION['fb_access_token'])) {
	try{
	   
	    $responce=$fb->get('/me?fields=name,first_name,albums.fields(id,name,cover_photo,photos.fields(name,picture,source))', $_SESSION['fb_access_token']);
	
	   
	}
	catch(Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Graph return an error : '.$e->getMessage();
		exit();
	}
	catch(Facebook\Exceptions\FacebookSDKException $e) {
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
} else {
	try {
	  $accessToken = $helper->getAccessToken();
        echo "Accessis: ".$accessToken." Accessis: ";
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	  // When Graph returns an error
	  echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  // When validation fails or other local issues
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  exit;
	}

	if (! isset($accessToken)) {
	  if ($helper->getError()) {
		header('HTTP/1.0 401 Unauthorized');
		echo "Error: " . $helper->getError() . "\n";
		echo "Error Code: " . $helper->getErrorCode() . "\n";
		echo "Error Reason: " . $helper->getErrorReason() . "\n";
		echo "Error Description: " . $helper->getErrorDescription() . "\n";
	  } else {
		header('HTTP/1.0 400 Bad Request');
		echo 'Bad request';
	  }
	  exit;
	}
						
	// The OAuth 2.0 client handler helps us manage access tokens
	$oAuth2Client = $fb->getOAuth2Client();

	// Get the access token metadata from /debug_token
	$tokenMetadata = $oAuth2Client->debugToken($accessToken);
	
	// Validation (these will throw FacebookSDKException's when they fail)
	$tokenMetadata->validateAppId($app_id);
	
	// If you know the user ID this access token belongs to, you can validate it here	
	$tokenMetadata->validateExpiration();

	if (! $accessToken->isLongLived()) {
	  // Exchanges a short-lived access token for a long-lived one
	  try {
		$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
	  } catch (Facebook\Exceptions\FacebookSDKException $e) {
		echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
		exit;
	  }
	  echo '<h3>Long-lived</h3>';
	  var_dump($accessToken->getValue());
	}
$_SESSION['fb_access_token'] = (string) $accessToken;
header('location:https://gaurangsavaliyart.herokuapp.com/callback.php');
}


?>

<html>
	<head>
		<meta charset="UTF-8">
		<link href="temp/fbtitle.ico" rel="icon"/>
		<title>Login With Facebook</title>
		<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
		
		
		<link rel="stylesheet" href="css/style8.css"type="text/css"/>
		<script src="js/jquery-3.3.1.min.js"></script>
		<link href="css/lightbox.css" rel="stylesheet">
		<link href="css/lightbox.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="row maindiv">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="headpart">
						<div class="col-md-6 col-sm-6 col-xs-6 headpartleft">
							<font><font style="font-size:48px;">F</font>acebook <font style="font-size:38px;">A</font>lbum <font style="font-size:38px;">A</font>rchiver</font>
						</div>
						<div class="offset-md-4 col-md-2 col-sm-6 col-xs-6 headpartright">
							<font><a href="#">My Profile</a></font>
							<font><a href="https://gaurangsavaliyart.herokuapp.com/logout.php">Logout</a></font>
						</div>
						<div style="clear:both;"></div>
					</div>
					<hr>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
		
					<div class="bodyparthead">
					    <?php
					    $album_res = $responce->getGraphAlbum()->asArray();
					    $albums = $album_res['albums'];
					    $_SESSION['usr']=$album_res['name'];
						if(isset($_POST['movealbumall']))
						    {
					                    $arr=array();
					                    foreach($albums as $album) {
								            
					                        $arr[$album['id']][0]=$album['id'];
					                        $arr[$album['id']][1]=$album['name'];
					                        $arr[$album['id']][2]=$album_res['name'];
					                    
								            $_SESSION['albummoveall']=$arr;
								                
								        }
								        
								        
								        header('location:https://gaurangsavaliyart.herokuapp.com/driveuploadall.php');
								    }
						?>
						
						<div class="row">
						    <div class="col-md-8">
						<a href="javascript: void(0);" data-id="allalbum_<?php echo $album_res['first_name']; ?>" class="fas fa-file-archive btnlink zipall" style="text-decoration:none;"> Download All Zip</a>
					    
						<div id="progressall" class="progressbar">Downloading Progress Please wait..<span class="zipprogress"></span></div>
						</div>
						    <div class="col-md-4">
						<form action="#" method="post" name="movetodriveall">
						    <input type="hidden" name="aid" value="<?php echo $album['id']; ?>"/>
							<a class="fab fa-google-drive btnlink"><input type="submit" name="movealbumall" value="Move to Drive all Album" style="border:none;background:none;cursor:pointer;padding:2%;" /></a>
						</form>
					    
						
						</div>
						
					</div>
					<hr>
					
					<?php
					foreach($albums as $album) 
					{
						$url="https://graph.facebook.com/v3.1/".$album['id']."/photos?fields=source,images,id,album&access_token=".$_SESSION['fb_access_token'];
							        $pic=file_get_contents($url);
                                    $pictures=json_decode($pic);
                            
                        
                                    $page=(array)$pictures->paging;
					?>
					
					<div class="bodypart">
						<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
						    <div class="container-fuild">
						    <div class="row bodypartline">
						        <div class="col-md-6 col-sm-6 col-xs-6">
						            <h4><?php echo $album['name']; ?></h4>
						        </div>
						        <div class="offset-md-2 col-md-4 offset-sm-2 col-sm-4 col-xs-6">
						            <div style="margin:2%;">
						                <a href="https://gaurangsavaliyart.herokuapp.com/slideshow.php?id=<?php echo $album['id']; ?>" style="padding:4%;text-decoration:none;" class="btnlink">Slideshow</a>
						                
						        
						            <?php
							        
                                    $i=0;
							        do
                                    {
                                        foreach($pictures->data as $my)
                                        {
                                            if($i==0){
                                    ?>
                            
                                              <a class="example-image-link btnlink" href="<?php echo $my->images[0]->source ?>" data-lightbox="<?php echo $album['name'] ?>" data-title="<?php echo $album['name'] ?>" style="padding:4%;text-decoration:none;">Lightbox Slideshow</a>
                            
							            
							        <?php
							                $i++;
                                            }
                                            else{
                                                ?>
                                                <a class="example-image-link" href="<?php echo $my->images[0]->source ?>"  data-lightbox="<?php echo $album['name'] ?>" data-title="<?php echo $album['name'] ?>"></a>
                                                <?php
                                            }
							                
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
						            //echo $i;
						            ?>
						        
						            
						        </div>	
							    
						    </div>
						    </div>
							
							<fieldset>
							<div class="container-fuild">
							<div class="row">
							
							<?php
							$url="https://graph.facebook.com/v3.1/".$album['id']."/photos?fields=source,images,id,album&access_token=".$_SESSION['fb_access_token'];
							$pic=file_get_contents($url);
                            $pictures=json_decode($pic);
                            
                        
                            $page=(array)$pictures->paging;
                            $i=0;
							do
                            {
                                foreach($pictures->data as $my)
                                {
                                  $m=2;  
                            ?>
                            
							
								<div class="col-md-2 col-sm-3 col-xs-6">
									
										<img src="<?php echo $my->images[0]->source ?>" style="margin:2%;width:90%;height:90px;border:2px solid #0FABDD;"/>
								</div>		
							
							<?php
							$m=$m+2;
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
						    //echo $i;
						    ?>
						
							
							</div>
							</div>
							</fieldset>
							<div class="bodypart2">
								<center>
								    <?php
								        
								    if(isset($_POST['movealbum']))
								    {
								        if($album['id']==$_POST['aid'])
								        {
								            $arr=array();
					
					                        $arr[0]=$album['id'];
					                        $arr[1]=$album['name'];
					                        $arr[2]=$album_res['name'];
					                    
								            $_SESSION['albumid']=$arr;
								            //var_dump($_SESSION['albumid']);    
								        }
								        
								        header('location:https://gaurangsavaliyart.herokuapp.com/driveupload.php');
								    }
								    ?>
								<div class="row">
								    <div class="col-md-6">
								    <a href="javascript: void(0);" data-id="<?php echo $album['id'] ?>" class="fas fa-file-archive zip-album"> Download This Album</a>
									<div id="progress_<?php echo $album['id']?>" class="progressbar">Downloading Progress Please wait...<span class="zipprogress"></span></div>
									</div>


									<div class="col-md-6">
									
									<form action="#" method="post" name="movetodrive">
									    <input type="hidden" name="aid" value="<?php echo $album['id']; ?>"/>
									   <a class="fab fa-google-drive"><input type="submit" name="movealbum" value="Move to Drive" style="border:none;background:none;color:#000;cursor:pointer;"/></a>
									</form>
									</div>
								</div>
								</center>
							</div>
							<hr>
							<?php
					}
					?>
						</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 footer">
					<div>
						<center>
							Created By : Gaurang Savaliya
						</center>
					</div>
				</div>
			</div>
		</div>
<script src="js/lightbox.js"></script>
<script src="js/lightbox.min.js"></script>
<script type="text/javascript" src="css/lightbox-plus-jquery.min.js"></script>

<script>
$(document).ready(function() {
	$('.zip-album').click(function() {
		var album_id = $(this).attr('data-id');
		var url = 'https://gaurangsavaliyart.herokuapp.com/downloadzip.php?id=' + album_id;
		$('#progress_'+album_id).show();
		
		$.ajax({
			xhr: function() {
				var xhr = new window.XMLHttpRequest();
				// Upload progress
				xhr.upload.addEventListener("progress", function(evt){
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						//Do something with upload progress
						console.log(percentComplete);
					}
			   }, false);

			   // Download progress
			   xhr.addEventListener("progress", function(evt){
				   if (evt.lengthComputable) {
					   var percentComplete = evt.loaded / evt.total;
					   // Do something with download progress
					   console.log(percentComplete);
					   
					   $('#progress_'+album_id + ' .zipprogress').css('max-width', percentComplete+'%');
				   }
			   }, false);

			   return xhr;
			},
			type: 'GET',
			url: url,
			data: {},
			success: function(data){
			    
				$('#progress_'+album_id + ' .zipprogress').css('max-width', '100%');
				setTimeout(function() {
				    
					$('#progress_'+album_id + ' .zipprogress').css('max-width', '0%');
					$('#progress_'+album_id).hide();
					
					if(data) {
					    
					    
					    
						if( !($("#ziplink_"+album_id).length) ) {
							$('<a id="ziplink_'+album_id+'" class="zip_created" href="'+data+'">Download Zip on Local Device</a>').insertBefore('#progress_'+album_id);
						}
					}
				}, 1000);
				// Do something success-ish
				alert("Download Done!!");
			}
		});
	});
});


$(document).ready(function() {
	$('.zipall').click(function() {
		var album_id = $(this).attr('data-id');
		var url = 'https://gaurangsavaliyart.herokuapp.com/downloadall.php?id=' + album_id;
		$('#progressall').show();
		
		$.ajax({
			xhr: function() {
				var xhr = new window.XMLHttpRequest();

				// Upload progress
				xhr.upload.addEventListener("progress", function(evt){
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						//Do something with upload progress
						console.log(percentComplete);
					}
			   }, false);

			   // Download progress
			   xhr.addEventListener("progress", function(evt){
				   if (evt.lengthComputable) {
					   var percentComplete = evt.loaded / evt.total;
					   // Do something with download progress
					   console.log(percentComplete);
					   
					   $('#progressall .zipprogress').css('max-width', percentComplete+'%');
				   }
			   }, false);

			   return xhr;
			},
			type: 'GET',
			url: url,
			data: {},
			success: function(data){
			    
				$('#progressall .zipprogress').css('max-width', '100%');
				setTimeout(function() {
					$('#progressall .zipprogress').css('max-width', '0%');
					$('#progressall').hide();
					
					if(data) {
					    
						if( !($("#ziplink_"+album_id).length) ) {
							$('<a id="ziplink_'+album_id+'" class="zip_created" style="text-decoration:none;padding:1%;background:#0FABDD;color:#fff;border-radius:10px;" href="'+data+'">Download Zip on Local Device</a>').insertBefore('#progressall');
						}
					}
				}, 1000);
				// Do something success-ish
				alert("Download Done!!");
			}
		});
	});
});

</script>
	

</body>
</html>	