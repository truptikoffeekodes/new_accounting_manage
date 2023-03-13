<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.voucher {
    width: 130%;
    table-layout: fixed;
    border-collapse: collapse;
    margin-bottom: 5px;
}

.table-responsive::-webkit-scrollbar {
    width: 3px;
    height: 12px;
    transition: .3s background;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #e1e6f1;
}

</style>
<!-- Page Header -->
<div class="page-header">

    <div>
        <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>

    <div class="btn btn-list">
        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true"
            aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
    </div>
</div>
<div class="responsive-background">
    <div class="navbar-collapse collapse show" id="navbarSupportedContent" style="">
        <form action ="<?=url('MillingReport/job_return_report')?>" method="POST">
            <div class="advanced-search">
            <div class="row align-items-center mg-b-25">
                    <div class="col-md-4">
                        <div class="form-group mb-lg-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        FROM:
                                    </div>
                                </div>
                                <input class="form-control dateMask" id="from_date" name="from_date"
                                    placeholder="DD-MM-YYYY" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-lg-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        TO:
                                    </div>
                                </div>
                                <input class="form-control dateMask" name="to_date" id="to_date"
                                    placeholder="DD-MM-YYYY" type="text">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-lg-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        Party:
                                    </div>
                                </div>
                                <select class="form-control account" id="account" name='account'>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mg-b-25">
                    <div class="col-md-4">
                        <div class="form-group mb-lg-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        Item:
                                    </div>
                                </div>
                                <select class="form-control" id="item" name='item'>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-lg-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        Warehouse:
                                    </div>
                                </div>
                                <select class="form-control" id="warehouse" name='warehouse'>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a class="btn btn-secondary" href="<?=url('MillingReport/job_return_report')?>">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Page Header -->

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-fw-widget">
                                <tr>
                                    <td>
                                        <span style="size:20px;"><b><?=$title?></b></span>
                                    </td>
                                </tr>
                                <tr colspan="4">
                                </tr>
                            </table>
                        </div>

                        <?php
                            foreach($job as $rw){
                        ?>
                        <br>
                        <table class="table  table-hover table-fw-widget voucher">
                            <thead>
                                    <tr>
                                        <td><b>Item : </b></td>
                                        <td colspan="5"><?=$rw['name']?></td>
                                    <tr>
                                    <tr>
                                        <td><b>HSN : </b></td>
                                        <td colspan="5"><?=$rw['hsn']?></td>
                                    <tr>
                                        
                            </thead>
                        </table>
                        <table class="table  table-hover table-fw-widget voucher">
                               
                                <thead>
                                    <tr style="border-top:1px solid #e1e6f1;">
                                        <th style="width:12%;">Date</th>
                                        <th>SR. No.</th>
                                        <th style="width:30%;">Account</th>
                                        <th style="width:13%;">warehouse</th>
                                        <th>TAKA</th>
                                        <th>Meter</th>
                                        <th>CUT</th>
                                        <th>PCS</th>
                                        <th>Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_pcs=0;
                                    $total_meter=0;
                                    $total_taka=0;
                                   
                    
                                    foreach($rw['data'] as $row){
                                        $total_pcs +=$row['ret_taka'];     
                                        $total_meter +=$row['ret_meter'];
                                        $total_taka +=$row['unit'];
                                    ?>
                                    <tr>
                                        <td><?=user_date(@$row['date'])?></td>
                                        <td><a href ="<?=url('Milling/Add_return_jobwork/').$row['job_id']?>"><?=@$row['job_id']?></a></td>
                                        <td><?=@$row['party_name']?></td>
                                        <td><?=@$row['warehouse_name']?></td>
                                        <td><?=@$row['unit']?></td>
                                        <td><?=@$row['ret_meter']?></td>
                                        <td><?=@$row['cut']?></td>
                                        <td><?=@$row['ret_taka']?></td>
                                        <td><?=@$row['price']?></td>
                                    </tr>
                                    <?php } ?>
                                    
                                </tbody>
                                <tfoot>
                                    <tr style="border-bottom:1px solid #e1e6f1;">
                                        <th colspan="4"><center>Total</center></th>
                                        <th><?=$total_taka?></th>
                                        <th><?=$total_meter?></th>
                                        <th></th>
                                        <th><?=$total_pcs?></th>
                                        <th></th>
                                    </tr>
                                    
                                </tfoot>
                                
                        </table>
                        
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script type="text/javascript">
$(document).ready(function() {
    
    $('.dateMask').mask('99-99-9999');

    $("#account").select2({
        width: 'resolve',
        placeholder: 'Type Account Name',
        ajax: {
            url: PATH + "Master/Getdata/search_accountSundry_cred_debt",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {

                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $("#warehouse").select2({
        width: 'resolve',
        placeholder: 'Type Warehouse Account',
        ajax: {
            url: PATH + "Master/Getdata/search_warehouse",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });


    $("#item").select2({
        width: 'resolve',
        placeholder: 'Type Item Code ',
        ajax: {
            url: PATH + "Milling/Getdata/Item",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                
                return {
                    searchTerm: params.term, // search term
                    // type: type // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });
});



</script>

<?= $this->endSection() ?>