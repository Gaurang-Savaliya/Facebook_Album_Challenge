<?php
require_once 'config.php';
//echo '<a href="' . htmlspecialchars($loginUrl) . '"><img src="temp/fbbutton.png"/></a>';
?>
<html>
	<head>
		<meta charset="UTF-8">
		<link href="temp/fbtitle.ico" rel="icon"/>
		<title>Login With Facebook</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" href="css/style8.css"type="text/css"/>
	</head>
	<body style="background-image:url('temp/backwallpaper.jpg');" >
		<header>
        <div class="container-fuild">
            
            <div class="col-md-12 col-sm-12 col-xs-12" style="background:#4c5560;color:#fff;">
                <div style="float:left;padding:1%;"><h3 class="">Facebook Album Archiver</h3></div>
				<div style="float:left;padding:1%;border-left:1px solid #000;"><h3 class="">Developer Profile</h3></div>
				<div style="clear:both;"></div>
			</div>
			<hr>
			<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12"> 
			    <center>
                <a href="<?php echo htmlspecialchars($loginUrl); ?>">
                    <img src="temp/fbbutton.png" width="350px"  style="margin-top:200px;"/>
                </a>
                <br/><br/><br/><br/>
                <h3 style="color:#fff;">Developed By : Gaurang Savaliya</h3>
			    </center>
            </div>
			</div>
			
        </div>
    </header>
	</body>
</html>