<?php
require_once 'config.php';
//echo '<a href="' . htmlspecialchars($loginUrl) . '"><img src="temp/fbbutton.png"/></a>';

$txt = "| Not Login |";
$myfile = file_put_contents('temp/logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

?>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="temp/fbtitle.ico" rel="icon"/>
		<title>Login With Facebook</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" href="css/style4.css"type="text/css"/>
	</head>
	<body style="background-image:url('temp/backwallpaper1.jpg');" >
		<header>
        <div class="container-fluid">
            <br><br>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12" style="color:#fff;">
                    <center><h1 style="font-family:DancingScript-Regular;font-size:46px;">Facebook Album Archiver</h1></center>
			    </div>
			</div>
			<div class="row">
			    <div class="offset-md-4 col-md-4 offset-sm-4 col-sm-4 col-xs-12"> 
			        <center>
			            <a href="https://www.facebook.com/app_scoped_user_id/YXNpZADpBWEhKOGVsR1NNNlpYb3M3cmotMUJvZA3NkYTY3VUxMTmg0czAwNTdadlptQlMtUXBLa2N1bXRxcFl2bWNGUW1veDNES2MtRlFEREUzMnBvdmFfSGU2aGlIMGU3M0p0eV9pb2s3c1dSV1FqT2VnZAngw" style="text-decoration:none;">
			            <div style="background:#04af82;padding:10px;color:#fff;margin-top:150px;width:78%;border-radius:10px;cursor:pointer;">
			                <h4>Developer Profile</h4>
			            </div>
			            </a>
			            <br>
                        <a href="<?php echo htmlspecialchars($loginUrl); ?>">
                            <img src="temp/fbbutton.png" style="width:80%;" />
                        </a>
                        <br/><br/><br/><br/>
                        <h4 style="color:#fff;">Developed By : Gaurang Savaliya</h4>
                    </center>
                </div>
			</div>
        </div>
    </header>
	</body>
</html>