<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"><?= $title ?></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- End Page Header -->

<!-- Row -->
<div class="row">
    
        <div class="col-lg-12">
            <div class="card custom-card main-content-body-profile">
                <nav class="nav main-nav-line">
					<a class="nav-link active" data-toggle="tab" href="#website">Website</a>
					<a class="nav-link" data-toggle="tab" href="#mail">Mail</a>
                    <a class="nav-link" data-toggle="tab" href="#invoice">Invoice</a>
				</nav>
                <div class="card-body tab-content h-100">
                    <div class="tab-pane active" id="website">
                        <form method="POST" action="<?= url('Setting/Update') ?>" enctype="multipart/form-data">
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="">Website Title</label>
                                        <input class="form-control" name="webstitle" value="<?= (!empty($setting)) ? $setting['website_title'] : '' ?>" required="" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="">Website Discription</label>
                                        <input class="form-control" name="webdescription" value="<?= (!empty($setting)) ? $setting['website_description'] : '' ?>"  type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="">Website Keyword</label>
                                        <input class="form-control" name="webkeyword" value="<?= (!empty($setting)) ? $setting['website_keyword'] : '' ?>"  type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="">Sms URL</label>
                                     <input class="form-control" name="smsurl" value="<?= (!empty($setting)) ? $setting['smsurl'] : '' ?>"  type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="">Website Logo</label>
                                        <input type="file" name="weblogo" value="<?= (!empty($setting)) ? $setting['logo'] : '' ?>" data-default-file="<?= (!empty($setting)) ? $setting['logo'] : '' ?>" class="dropify" data-height="100" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="">Website Logo Icon</label>
                                        <input type="file" name="weblogoicon" value="<?= (!empty($setting)) ? $setting['logo_icon'] : '' ?>" data-default-file="<?= (!empty($setting)) ? $setting['logo_icon'] : '' ?>" class="dropify" data-height="100" />
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-3" >
                                    <button class="btn ripple btn-main-primary btn-block" name="web" value="web" type="submit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="mail">
                        <form method="POST" action="<?= url('Setting/Update') ?>" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="">Mail From Name</label>
                                     <input class="form-control" name="mailname" value="<?= (!empty($setting)) ? $setting['mail_from_name'] : '' ?>" required="" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="">Mail From Email</label>
                                        <input class="form-control" name="mailemail" value="<?= (!empty($setting)) ? $setting['mail_from_email'] : '' ?>" required="" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="">Mail SMTP Host</label>
                                        <input class="form-control" name="mailhost" value="<?= (!empty($setting)) ? $setting['mail_smtp_host'] : '' ?>" required="" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="">Mail SMTP Port</label>
                                        <input class="form-control" name="mailport" value="<?= (!empty($setting)) ? $setting['mail_smtp_port'] : '' ?>" required="" type="text" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="">Mail SMTP User</label>
                                        <input class="form-control" name="mailusername" value="<?= (!empty($setting)) ? $setting['mail_smtp_user'] : '' ?>" required="" type="text" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="">Mail SMTP Password</label>
                                        <input class="form-control" name="mailpassword" value="<?= (!empty($setting)) ? $setting['mail_smtp_pass'] : '' ?>" required="" type="text" >
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-3" >
                                    <button class="btn ripple btn-main-primary btn-block" name="mail" value="mail"  type="submit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="invoice">
                        <form method="POST" action="<?= url('Setting/Update') ?>" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="">Invoice Title</label>
                                     <input class="form-control" name="invoicetitle" value="<?= (!empty($setting)) ? $setting['invoice_title'] : '' ?>"  type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="">Invoice Address</label>
                                        <input class="form-control" name="invoiceaaddress" value="<?= (!empty($setting)) ? $setting['invoice_address'] : '' ?>"  type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="">Invoice Logo</label>
                                        <input type="file" name="invoicelogo" value="<?= (!empty($setting)) ? $setting['invoice_logo'] : '' ?>" data-default-file="<?= (!empty($setting)) ? $setting['invoice_logo'] : '' ?>" class="dropify" data-height="100" />
                                    </div>
                                </div>
                                
                                <div class="col-md-3" >
                                 
                                    <button class="btn ripple btn-main-primary btn-block" type="submit" value="invoice" name="invoice" style="margin-top:95px!important;margin-left:147px!important;">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>        
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->

    <?= $this->endSection() ?>

    <?= $this->section('scripts') ?>
    <!---Fileupload css-->
    <link href="<?= ASSETS ?>plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css" />
    <!--Fileuploads js-->
    <script src="<?= ASSETS ?>plugins/fileuploads/js/fileupload.js"></script>
    <script src="<?= ASSETS ?>plugins/fileuploads/js/file-upload.js"></script>
    <script>
        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop a file here or click',
                'replace': 'Drag and drop or click to replace',
                'remove': 'Remove',
                'error': 'Ooops, something wrong appended.'
            },
            error: {
                'fileSize': 'The file size is too big (2M max).'
            }
        });

        </script>
     
    <?= $this->endSection() ?>