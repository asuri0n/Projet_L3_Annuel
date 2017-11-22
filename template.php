<?php
	if(!isset($description))
		$description = "CHANGE ME";

	if(!isset($keywords))
		$keywords = "CHANGE ME";

	if(!isset($titlePage))
        $titlePage = "CHANGE ME";
?>		

<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php echo $titlePage ?>  | <?php echo WEB_TITLE ?></title>
		<meta name="format-detection" content="telephone=no" />
		<meta name="description" content="<?php echo $description; ?>">
		<meta name="keywords" content="<?php echo $keywords; ?>">
		<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">

		<base href="" />

		<!-- Google Analytics -->
		<script>

		</script>
		<!-- End Google Analytics -->

        <!-- Piwik -->
        <script>

        </script>
        <!-- Piwik -->

		<!-- Load CSS file -->
		<link rel="stylesheet" type="text/css" href="<?php WEBROOT ?>assets/js/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="<?php WEBROOT ?>assets/css/font-awesome/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="<?php WEBROOT ?>assets/css/stylesheet.css" />
		<link rel="stylesheet" type="text/css" href="<?php WEBROOT ?>assets/css/owl.carousel.css" />
		<link rel="stylesheet" type="text/css" href="<?php WEBROOT ?>assets/css/owl.transitions.css" />
		<link rel="stylesheet" type="text/css" href="<?php WEBROOT ?>assets/css/responsive.css" />
		<link rel="stylesheet" type="text/css" href="<?php WEBROOT ?>assets/css/stylesheet-skin3.css" />
		<link href='//fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
	</head>
	<body>
        <header>
        </header>
        <div id="container">
            <?php echo $content; ?>
        </div>
        <footer id="footer">
        </footer>

        <!-- JS Part Start-->
        <script type="text/javascript" src="<?php WEBROOT ?>assets/js/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="<?php WEBROOT ?>assets/js/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php WEBROOT ?>assets/js/jquery.easing-1.3.min.js"></script>
        <script type="text/javascript" src="<?php WEBROOT ?>assets/js/jquery.dcjqaccordion.min.js"></script>
        <script type="text/javascript" src="<?php WEBROOT ?>assets/js/owl.carousel.min.js"></script>
        <script type="text/javascript" src="<?php WEBROOT ?>assets/js/custom.js"></script>
        <!-- JS Part End-->
    </body>
</html>