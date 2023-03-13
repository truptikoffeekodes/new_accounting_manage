<?php
$request = \Config\Services::request();

$uri = $request->uri;
$c = $uri->getSegment(1);

if (!session('uid')) {

    header("Location: " . url('auth') . "");
    exit;
} else {

    // if (!(@$_COOKIE['gcode'] && $_COOKIE['gcode'] == md5(GCODE)) && $c != 'auth') {

    //     header("Location: " . url('auth/google') . "");
    //     exit;
    // }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="description" content="Dashlead -  Admin Panel HTML Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="sales dashboard, admin dashboard, bootstrap 4 admin template, html admin template, admin panel design, admin panel design, bootstrap 4 dashboard, admin panel template, html dashboard template, bootstrap admin panel, sales dashboard design, best sales dashboards, sales performance dashboard, html5 template, dashboard template">

    <!-- Favicon -->
    <link rel="icon" href="<?= ASSETS; ?>img/brand/favicon.ico" type="image/x-icon" />

    <!-- Title -->
    <title><?= TITLE; ?></title>

    <!---Fontawesome css-->
    <link href="<?= ASSETS; ?>plugins/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!---Ionicons css-->
    <link href="<?= ASSETS; ?>plugins/ionicons/css/ionicons.min.css" rel="stylesheet">

    <!---Typicons css-->
    <link href="<?= ASSETS; ?>plugins/typicons.font/typicons.css" rel="stylesheet">

    <!---Feather css-->
    <link href="<?= ASSETS; ?>plugins/feather/feather.css" rel="stylesheet">

    <!---Falg-icons css-->
    <link href="<?= ASSETS; ?>plugins/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">

    <!---Style css-->
    <link href="<?= ASSETS; ?>css/style.css?v=0.1" rel="stylesheet">
    <link href="<?= ASSETS; ?>css/custom-style.css" rel="stylesheet">
    <link href="<?= ASSETS; ?>css/skins.css" rel="stylesheet">
    <link href="<?= ASSETS; ?>css/dark-style.css" rel="stylesheet">
    <link href="<?= ASSETS; ?>css/custom-dark-style.css" rel="stylesheet">

    <!---Select2 css-->
    <link href="<?= ASSETS; ?>plugins/select2/css/select2.min.css" rel="stylesheet">

    <!--Mutipleselect css-->
    <link rel="stylesheet" href="<?= ASSETS; ?>plugins/multipleselect/multiple-select.css">

    <!--sweetalert2 css-->
    <link rel="stylesheet" type="text/css" href="<?= ASSETS; ?>plugins/sweet-alert/sweetalert2.min.css">

    <!--summernote css-->
    <link rel="stylesheet" type="text/css" href="<?= ASSETS; ?>plugins/summernote/summernote-bs4.css">

    <!---Sidebar css-->
    <link href="<?= ASSETS; ?>plugins/sidebar/sidebar.css" rel="stylesheet">

    <!---Jquery.mCustomScrollbar css-->
    <link href="<?= ASSETS; ?>plugins/jquery.mCustomScrollbar/jquery.mCustomScrollbar.css" rel="stylesheet">

    <!---Fileupload css-->
    <link href="<?= ASSETS ?>plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css" />

    <!---SUMO css-->
    <link rel="stylesheet" href="<?= ASSETS ?>plugins/sumoselect/sumoselect.css">

    <!---Sidemenu css-->
    <link href="<?= ASSETS; ?>plugins/sidemenu/sidemenu.css" rel="stylesheet">

    <!---DataTables css-->
    <link href="<?= ASSETS; ?>plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="<?= ASSETS; ?>plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
    <link href="<?= ASSETS; ?>plugins/datatable/fileexport/buttons.bootstrap4.min.css" rel="stylesheet" />

    <style>
        .dnone {
            display: none;
        }
    </style>

</head>

<body class="main-body dark-leftmenu">

    <!-- Loader -->
    <div id="global-loader">
        <img src="<?= ASSETS; ?>img/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- End Loader -->
    <div class="page">
        <?php if (!session('cid')) { ?>
            <?= $this->include(THEME . 'block/sidebar') ?>
        <?php } else { ?>
            <?= $this->include(THEME . 'block/sub_sidebar') ?>
        <?php } ?>

        <div class="main-content side-content pt-0">
            <?= $this->include(THEME . 'block/header') ?>

            <div class="container-fluid">
                <?= $this->include(THEME . 'block/flashmsg') ?>
                <?= $this->renderSection('content') ?>
            </div>


            <div class="modal fade colored-header colored-header-primary" id="fm_model" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-header-colored">
                            <h3 class="modal-title "><span class="model_title"></span></h3>
                            <button class="close md-close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"> </span></button>
                        </div>
                        <div class="modal-body">

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?= $this->include(THEME . 'block/footer') ?>
    </div>

    <?= $this->include(THEME . 'block/scripts') ?>
    <?= $this->renderSection('scripts') ?>
    <script>
        $('body').on('click', '[data-toggle="modal"]', function() {
            $($(this).data("target") + ' .modal-body').load($(this).attr("href"), function() {
                afterload();
            });

            $('.model_title').text($(this).data("title"));

        });
        


        function code_generate(name) {
            var year = new Date().getFullYear();
            var substr = name.substring(0, 3)

            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            var random = '';

            for (var i = 0; i < 4; i++) {
                random += characters.charAt(Math.floor(Math.random() * charactersLength));
            }

            var join = substr.concat(year);
            var finalstr = join.concat(random);

            var code = finalstr.toUpperCase();
            var rand = code.replace(/ /g, '')

            $('#code').val(rand);
        }

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }
    </script>
</body>

</html>