<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?= ASSETS ?>img/brand/favicon.ico">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="<?= ASSETS; ?>plugins/fontawesome-free/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="<?= ASSETS; ?>plugins/ionicons/css/ionicons.min.css" />
    <link rel="stylesheet" type="text/css" href="<?= ASSETS; ?>plugins/typicons.font/typicons.css" />
    <link rel="stylesheet" type="text/css" href="<?= ASSETS; ?>plugins/feather/feather.css" />
    <link rel="stylesheet" type="text/css" href="<?= ASSETS; ?>plugins/flag-icon-css/css/flag-icon.min.css" /> 
    <link rel="stylesheet" type="text/css" href="<?= ASSETS; ?>css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?= ASSETS; ?>css/custom-style.css" />
    <link href="<?= ASSETS; ?>css/skins.css" rel="stylesheet">
	<link href="<?= ASSETS; ?>css/dark-style.css" rel="stylesheet">
	<link href="<?= ASSETS; ?>css/custom-dark-style.css" rel="stylesheet">
</head>

<body class="main-body">


    <!-- Loader -->
    <div id="global-loader">
        <img src="<?= ASSETS; ?>img/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- End Loader -->

    <?= $this->renderSection('content') ?>

    <script src="<?= ASSETS ?>plugins/jquery/jquery.min.js" type="text/javascript"></script>
    <script src="<?= ASSETS ?>plugins/bootstrap/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <script src="<?= ASSETS ?>plugins/ionicons/ionicons.js" type="text/javascript"></script>
    <!-- Rating js-->
	<script src="<?= ASSETS; ?>plugins/rating/jquery.rating-stars.js"></script>
    <script src="<?= ASSETS ?>js/custom.js" type="text/javascript"></script>


    <script type="text/javascript">
        
    </script>
</body>

</html>