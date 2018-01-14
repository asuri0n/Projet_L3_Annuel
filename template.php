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
		<title><?php echo $titlePage ?>  | <?php echo WEB_TITLE ?></title>
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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php WEBROOT ?>assets/css/custom.css">
        <!-- Load JS files -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<!--[if lt IE 9] -->
		<script src="<?php WEBROOT ?>assets/js/html5shiv.js" type="text/javascript"></script>
		<!-- [endif]-->
	</head>
	<body>
        <div class="container">
            <header>
                <?php include 'includes/menu.php'; ?>
            </header>
            <main>
                <?php echo $content; ?>
            </main>
        </div>
	</body>
</html>