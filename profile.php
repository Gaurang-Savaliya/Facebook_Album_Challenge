<?php
ob_start();
require_once 'config.php';
if (isset($_SESSION['fb_access_token']) && !empty($_SESSION['fb_access_token'])) {
    try {

        $responce = $fb->get('/me?fields=id,name,birthday,email,gender,languages,hometown,location,quotes,link', $_SESSION['fb_access_token']);
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
    header('location:https://gaurangsavaliya4.000webhostapp.com/profile.php');
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
        <style>
            .datadisplay table th
{
    color: #fff;
    background: #39342e;
    padding: 5px;
}
.datadisplay table tr:nth-child(odd)
{
    color: #000;
    background: #b1b1b1;
    padding: 3px;
}
.datadisplay table tr:nth-child(even)
{
    color:#000;
    background: #fff;
    padding: 3px;
}
.datadisplay table td
{
    padding:1%;
}
        </style>
        
    </head>
    <body>
        <a href="https://gaurangsavaliya4.000webhostapp.com/callback.php" style="padding:25px 15px 15px 15px;color:#fff;background:darkblue;cursor:pointer;text-decoration:none;">Back to home</a>
        <div class="container-fluid">
            <div class="row">
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    
                    
                    <?php
                        $album_res = $responce->getGraphAlbum()->asArray();
                        $id=$album_res['id'];    
                        $profile="https://graph.facebook.com/".$id."/picture?type=large";
                       
                    ?>
                    <center>
                    <div style="margin:3%;box-shadow:1px 5px 15px black;padding:2%;">
                        <div style="border:1px solid #000; margin:2%;height:64.5%;">
                            <img src="temp/profilecover.png" class="img-responsive"/>
                            <div style="margin-top:-20%;">
                                <h3 style="color:#fff;"><?php echo $album_res['name']; ?></h3>
                            </div>
                        </div>
                        
                        <div style="margin-top:-55%;position:absolute;padding-left:27%;">
                            <img src="<?php echo $profile; ?>" style="border-radius:100%;box-shadow:4px 5px 15px black;padding:3%;" />
                        </div>
                        
                    </div>
                    </center>
                </div>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div style="margin:3%;box-shadow:1px 5px 15px black;padding:10px;" class="datadisplay">
                        <center><h2>User Information</h2></center>
                        <hr>
                        <table class="table table-responsive">
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td><?php echo $album_res['email']; ?></td>
                            </tr>
                            <tr>
                                <td>Date of Birth</td>
                                <td>:</td>
                                <td><?php
                                    $userbday = $responce->getDecodedBody();
                                    echo $userbday['birthday'];
                                ?></td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td>:</td>
                                <td><?php echo $album_res['gender']; ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td><?php echo $album_res['email']; ?></td>
                            </tr>
                            <tr>
                                <td>Hometown</td>
                                <td>:</td>
                                <td><?php echo $album_res['hometown']['name']; ?></td>
                            </tr>
                            <tr>
                                <td>Current Location</td>
                                <td>:</td>
                                <td><?php echo $album_res['location']['name']; ?></td>
                            </tr>
                            <tr>
                                <td>Languages Known</td>
                                <td>:</td>
                                <td>
                                            <?php 
                                            $languages=$album_res['languages'];
                                            foreach($languages as $lang)
                                            {
                                                echo $lang['name']." ,";        
                                            } 
                                            ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Quotes</td>
                                <td>:</td>
                                <td>
                                            <?php 
                                            
                                            echo $album_res['quotes'];
                                            
                                            ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                        $album_res = $responce->getGraphAlbum()->asArray();
                       
                    ?>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <center>
                        
                        <a href="<?php echo $album_res['link']; ?>" class="fab fa-facebook" style="color:darkblue;font-size:66px;text-decoration:none;border-radius:100%;" title="Users Facebook Profile"></a>
                        
                    </center>
                </div>
            </div>
        </div>
    </body>
</html>
