<?= $this->extend(THEME . 'sub_templets') ?>

<?= $this->section('content') ?>

 <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5"> Dashboard </h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
            </ol>
        </div>
        <div class="btn btn-list">
            <a data-toggle="modal" data-target="#fm_model" data-title="Add JV Particular"  href="<?=url('master/add_jvparticular')?>"   class="btn ripple btn-primary"><i class="fe fe-external-link"></i>Add New</a>
        </div>
    </div>
<!-- End Page Header -->
<?= $this->endSection() ?>