<?php
ob_start();
require_once 'config.php';
if (isset($_SESSION['fb_access_token']) && !empty($_SESSION['fb_access_token'])) {
    try {

        $responce = $fb->get('/me?fields=name,first_name,albums.fields(id,name,cover_photo,picture,photos.fields(name,picture,source))', $_SESSION['fb_access_token']);
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph return an error : ' . $e->getMessage();
        exit();
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
} else {
    try {
        $accessToken = $helper->getAccessToken();
        echo "Accessis: " . $accessToken . " Accessis: ";
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    if (!isset($accessToken)) {
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

    if (!$accessToken->isLongLived()) {
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
    header('location:https://gaurangsavaliya4.000webhostapp.com/callback.php');
}


?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="temp/fbtitle.ico" rel="icon"/>
        <title>Login With Facebook</title>

        <link rel="stylesheet" href="css/style4.css" type="text/css" />
        <script src="js/jquery-3.3.1.min.js"></script>

        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


        <link href="css/lightbox.css" rel="stylesheet">
        <link href="css/lightbox.min.css" rel="stylesheet">
    </head>
    <body>


        <!--Header start-->
        <header>
            <div class="head">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-xs-12">
                            <font class="head1">
                            <font class="fa fa-envelope-open">&nbsp;&nbsp;</font>Email : gaurangsavaliya8@gmail.com
                            </font>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4  col-sm-12 col-xs-12">
                            <div class="head2">
                                <ul>
                                    <li><a href="https://gaurangsavaliya4.000webhostapp.com/profile.php"><font class="fa fa-address-card">&nbsp;&nbsp;</font>Profile</a></li>
                                    <li><a href="https://gaurangsavaliya4.000webhostapp.com/logout.php"><font class="fa fa-user">&nbsp;&nbsp;</font>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header">
                <div class="container">
                    <div class="logo">
                        <h1 style="font-family:DancingScript-Regular">Facebook Album Archiever</h1>
                    </div>
                </div>
            </div>
        </header>
        <hr style="color:#04af82;">
        <!--Header end-->
        
		<!--Main part start-->
	
	<section>
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
								        
								        
								        header('location:https://gaurangsavaliya4.000webhostapp.com/driveuploadall.php');
								    }
						?>
    <div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
                
					<div class="row">
					    <div class="col-md-2 col-sm-2 col-xs-6">	    
						        <a href="javascript: void(0);" data-id="allalbum_<?php echo $album_res['first_name']; ?>" class="fas fa-file-archive zipall" style="background:#04af82;text-decoration:none;color:#fff;padding:6% 21% 6% 21%;font-size:18px;" title="Download All Albums as Archive on local Device"> Archive All</a>
						        <div id="progressall" class="progressbar">Downloading Progress Please wait..<span class="zipprogress"></span></div>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-6">
						   
						        <form action="#" method="post" name="movetodriveall">
						            <input type="hidden" name="aid" value="<?php echo $album['id']; ?>"/>
							        <button type="submit" name="movealbumall" class="" style="border:none;background:#04af82;color:#fff;padding:6% 21% 6% 21%;font-size:18px;font-weight:bold;"><a class="fab fa-google-drive" style="text-decoration:none;color:#fff;" title="Move all Albums in Google Drive"> Move All</a></button>
						        </form>
					            <div style=""></div>	        
						 </div>       
						        
						        
						    
					</div>
					
					
        </div>
    </div>
    </div>
	<div class="container-fluid">		
	<div class="row">
	    
	    <?php
	    
	    foreach ($albums as $album) {
            $url = "https://graph.facebook.com/v3.1/" . $album['id'] . "/photos?fields=source,images,id,album&access_token=" . $_SESSION['fb_access_token'];
            $pic = file_get_contents($url);
            $pictures = json_decode($pic);
            $page = (array) $pictures->paging;
        ?>
		<div class="col-md-3 col-sm-12 col-xs-12 albumbox">
			<div class="album">
			    <div class="albumname">
					<?php echo $album['name']; ?>
				</div>
				<div class="albumtitle">
				    <?php
                                                if (isset($_REQUEST['form2'])) {
                                                    if ($album['id'] == $_POST['aid']) {
                                                        $arr = array();

                                                        $arr[0] = $album['id'];
                                                        $arr[1] = $album['name'];
                                                        $arr[2] = $album_res['name'];

                                                        $_SESSION['albumid'] = $arr;
                                                    }

                                                    header('location:https://gaurangsavaliya4.000webhostapp.com/driveupload.php');
                                                }
                                                ?>
					<a href="javascript: void(0);" data-id="<?php echo $album['id'] ?>" class="fas fa-file-archive  zip-album titleiconancher" title="Download Album as zip file" style="float:left;"></a>
					
					
					<form action="#" method="post" name="f2" style="margin-bottom:-1px;">
                        <input type="hidden" name="aid" value="<?php echo $album['id']; ?>"/>
					        
					   <button type="submit" name="form2" class="titleiconbutton"><a class="fab fa-google-drive" title="Move to Drive Album"></a></button>
                    </form>
                    <div style="clear:both;"></div>
				</div>
				<div id="progress_<?php echo $album['id']; ?>" class="progressbar">Downloading Progress Please wait...<span class="zipprogress"></span></div>
				<div class="albumimg" style="padding:5px;border:1px solid #04af82;">
				    <img src="<?php echo $album['picture']['url']; ?>" style="width:100%;height:40%; "/>
				</div>
				<div class="albumfooter">
					
  					<?php
                                                    $i = 0;
                                                    do {
                                                        foreach ($pictures->data as $my) {
                                                            if ($i == 0) {
                                                                ?>

					<a style="padding: 3% 45.6% 3% 45.6%;border: 1px solid #04af82;" class="fa fa-image" title="Slideshow of the Album" href="<?php echo $my->images[0]->source ?>" data-lightbox="<?php echo $album['name'] ?>" data-title="<?php echo $album['name'] ?>"></a>
					<?php
                                                                $i++;
                                                            } else {
                                                                ?>
                    <a class="example-image-link" title="Slideshow of the Album" href="<?php echo $my->images[0]->source ?>" data-lightbox="<?php echo $album['name'] ?>" data-title="<?php echo $album['name'] ?>"></a>
					<?php
                                                            }
                                                        }
                                                        if (array_key_exists("next", $page)) {
                                                            $url = $page["next"];
                                                            $pic = file_get_contents($url);
                                                            $pictures = json_decode($pic);
                                                            $page = (array) $pictures->paging;
                                                        } else {
                                                            $url = 'none';
                                                        }
                                                    } while ($url != 'none');
                                                    //echo $i;
                                                    ?>
				</div>
			</div>
		</div>
		<?php
            }
        ?>
		
		
	</div>
	</div>
	</section>
			<!--Main part end-->        

        <!--Footer start-->
        <footer>
            <div class="foot">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 footer">
                            <center><font style="font-size:14px;">Created By : Gaurang Savaliya</font></center>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--Footer end-->


        <script src="js/lightbox.js"></script>
        <script src="js/lightbox.min.js"></script>
        <script type="text/javascript" src="js/lightbox-plus-jquery.min.js"></script>

        <script>
            $(document).ready(function () {
                $('.zip-album').click(function () {
                    var album_id = $(this).attr('data-id');
                    var url = 'https://gaurangsavaliya4.000webhostapp.com/downloadzip.php?id=' + album_id;
                    $('#progress_' + album_id).show();

                    $.ajax({
                        xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            // Upload progress
                            xhr.upload.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    //Do something with upload progress
                                    console.log(percentComplete);
                                }
                            }, false);

                            // Download progress
                            xhr.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    // Do something with download progress
                                    console.log(percentComplete);

                                    $('#progress_' + album_id + ' .zipprogress').css('max-width', percentComplete + '%');
                                }
                            }, false);

                            return xhr;
                        },
                        type: 'GET',
                        url: url,
                        data: {},
                        success: function (data) {

                            $('#progress_' + album_id + ' .zipprogress').css('max-width', '100%');
                            setTimeout(function () {

                                $('#progress_' + album_id + ' .zipprogress').css('max-width', '0%');
                                $('#progress_' + album_id).hide();

                                if (data) {



                                    if (!($("#ziplink_" + album_id).length)) {
                                        $('<a id="ziplink_' + album_id + '" style="position:absolute;background:yellowgreen;text-decoration:none;color:#fff;" class="zip_created" href="' + data + '">Download Zip on Local Device</a>').insertBefore('#progress_' + album_id);
                                    }
                                }
                            }, 1000);
                            // Do something success-ish
                            alert("Download Done!!");
                        }
                    });
                });
            });


            $(document).ready(function () {
                $('.zipall').click(function () {
                    var album_id = $(this).attr('data-id');
                    var url = 'https://gaurangsavaliya4.000webhostapp.com/downloadall.php?id=' + album_id;
                    $('#progressall').show();

                    $.ajax({
                        xhr: function () {
                            var xhr = new window.XMLHttpRequest();

                            // Upload progress
                            xhr.upload.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    //Do something with upload progress
                                    console.log(percentComplete);
                                }
                            }, false);

                            // Download progress
                            xhr.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    // Do something with download progress
                                    console.log(percentComplete);

                                    $('#progressall .zipprogress').css('max-width', percentComplete + '%');
                                }
                            }, false);

                            return xhr;
                        },
                        type: 'GET',
                        url: url,
                        data: {},
                        success: function (data) {

                            $('#progressall .zipprogress').css('max-width', '100%');
                            setTimeout(function () {
                                $('#progressall .zipprogress').css('max-width', '0%');
                                $('#progressall').hide();

                                if (data) {

                                    if (!($("#ziplink_" + album_id).length)) {
                                        $('<a id="ziplink_' + album_id + '" class="zip_created" style="text-decoration:none;padding:1%;background:#0FABDD;color:#fff;border-radius:10px;" href="' + data + '">Download Zip on Local Device</a>').insertBefore('#progressall');
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
