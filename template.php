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
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

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
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo WEBROOT ?>assets/css/custom.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">

        <!-- Load JS files -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
        <script type="text/javascript" src="<?php echo WEBROOT ?>assets/js/custom.js"></script>

        <!--[if lt IE 9] -->
		<script src="<?php echo WEBROOT ?>assets/js/html5shiv.js" type="text/javascript"></script>
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
        <footer class="page-footer font-small blue pt-4 mt-4">
            <br><br>
            <div class="footer-copyright py-3 text-center">
                <div class="container-fluid">
                    © Université de Caen -
                    <a href="mailto:<?php echo ADMINEMAIL ?>?subject=[AUTOEVALUATION] Signalement d'un bogue">Signaler un bogue</a> -
                    <a href="mailto:<?php echo ADMINEMAIL ?>?subject=[AUTOEVALUATION] Proposition suggestion">Proposer une suggestion</a>
                </div>
            </div>
        </footer>
	</body>
    <?php include 'includes/notification.php'; ?>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "lengthChange":         false,
                "info":                 false,
                language: {
                    "decimal":          "",
                    "emptyTable":       "Aucun exercice disponible",
                    "infoPostFix":      "",
                    "thousands":        ",",
                    "loadingRecords":   "Chargement...",
                    "processing":       "Traitement...",
                    "search":           "Rechercher:",
                    "zeroRecords":      "Aucun enregistrements correspondants trouvés",
                    "paginate": {
                        "first":        "Premier",
                        "last":         "Dernier",
                        "next":         "Suivant",
                        "previous":     "Précédent"
                    }
                }
            });
        });
    </script>
</html>