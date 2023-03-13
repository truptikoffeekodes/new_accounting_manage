<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

 <!-- Page Header -->
 <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5"> Company </h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
            </ol>
        </div>
        <div class="btn btn-list">
        <a data-toggle="modal" href="<?= url('Account/add_glgrp') ?>" data-target="#fm_model" data-title="Add New Gl Group " class="btn ripple btn-primary"><i class="fe fe-external-link"></i> Add New</a>
        </div>
    </div>
    <!-- End Page Header -->

<?= $this->endSection() ?>
