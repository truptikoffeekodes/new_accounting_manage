<?= $this->extend(THEME . 'home') ?>

<?= $this->section('content') ?>


<!-- Page -->
<div class="page main-signin-wrapper">
    <div class="be-content">
        <div class="row text-center pl-0 pr-0 ml-0 mr-0">
            <div class="col-lg-4 d-block mx-auto">
                <div class="text-center mb-2">
                    <img src="<?= LOGODARK; ?>" class="header-brand-img" alt="logo">
                    <img src="<?= LOGODARK; ?>" class="header-brand-img theme-logos" alt="logo">
                </div>
                <div class="card custom-card">
                    <div class="card-body">
                        <?php if (!empty($msg) && $msg['st'] == 'failed') { ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                <div class="icon"> <span class="mdi mdi-close-circle-o"></span></div>
                                <div class="message"><strong>Failed!</strong> <?= $msg['msg']; ?></div>
                            </div>
                        <?php } ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <input class="form-control" name="username" type="text" placeholder="Username" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="password" type="password" placeholder="Password">
                            </div>
                            <div class="form-group row login-tools">
                                <div class="col-6 login-remember">

                                </div>
                            </div>
                            <div class="form-group login-submit"><button type="submit" class="btn btn-primary btn-xl" data-dismiss="modal">Sign me in</button></div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>