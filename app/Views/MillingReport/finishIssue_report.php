<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.voucher {
    width: 180%;
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
        <form action="<?=url('MillingReport/finish_issue_report')?>" method="POST">
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
                    <a class="btn btn-secondary" href="<?=url('MillingReport/finish_issue_report')?>">Reset</a>
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

                            foreach($gray as $rw){
                        ?>
                        <br>
                        <div class="row">
                            <table class="col-6 table table-hover table-fw-widget voucher">
                                <thead>
                                    <tr>
                                        <td colspan="2"><b>Party : </b></td>
                                        <td colspan="5"><?=$rw['name']?></td>
                                    <tr>
                                    <tr>
                                        <td colspan="2"><b>Address : </b></td>
                                        <td colspan="5"><?=$rw['address']?></td>
                                    <tr>
                                    <tr>
                                        <td colspan="2"><b>GST : </b></td>
                                        <td colspan="5"><?=$rw['gst']?></td>
                                    <tr>

                                </thead>
                            </table>
                            <!-- <table class="col-6 table table-hover table-fw-widget voucher">
                                <thead>
                                        <tr>
                                            <td colspan="2"><b>Delivery: </b></td>
                                            <td colspan="5"><?=@$rw['delivery_name']?></td>
                                        <tr>
                                        <tr>
                                            <td colspan="2"><b>Address : </b></td>
                                            <td colspan="5"><?=@$rw['delivery_add']?></td>
                                        <tr>
                                        <tr>
                                            <td colspan="2"><b>Warehouse : </b></td>
                                            <td colspan="5"><?=@$rw['warehouse_name']?></td>
                                        <tr>
                                            
                                </thead>
                            </table> -->
                        </div>
                        <table class="table  table-hover table-fw-widget voucher">

                            <thead>
                                <tr style="border-top:1px solid #e1e6f1;">
                                    <th style="width:8%;">Date</th>
                                    <th>SR. No.</th>
                                    <th style="width:12%;">Item</th>
                                    <th>HSN</th>
                                    <th style="width:7%;">WAREHOUSE</th>
                                    <th>TAKA</th>
                                    <th>Meter</th>
                                    <th>Cut</th>
                                    <th>Rate</th>
                                    <th style="width:6%;">Amount</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>GST AMT</th>
                                    <th>LESS (-)</th>
                                    <th>ADD (+)</th>
                                    <th style="width:7%;">DISCOUNT(-)</th>
                                    <th style="width:8%;">Net Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $total_pcs=0;
                                    $total_meter=0;
                                    $total_cut=0;
                                    $total_amount=0;
                                    $total_gst_amt=0;
                                    $total_igst=0;
                                    $total_sgst=0;
                                    $total_cgst=0;
                                    $total_net_amt=0;
                                    $total_less=0;
                                    $total_add=0;
                                    $total_discount=0;
                    
                                    foreach($rw['data'] as $row){
                                        $total_pcs +=$row['pcs'];     
                                        $total_meter +=$row['meter'];     
                                        $total_cut +=$row['cut'];     
                                        $total_amount +=$row['total_amount']; 
                                    
                                        if($row['tax_type'] == 'igst'){
                                            $total_igst +=$row['tot_igst'];
                                            $total_gst_amt +=$row['tot_igst'];
                                        }else{
                                            $total_cgst +=(float)$row['tot_cgst'];     
                                            $total_sgst +=(float)$row['tot_sgst'];  
                                            $total_gst_amt +=((float)$row['tot_sgst'] + (float)$row['tot_cgst']);
                                        }  
                                        $total_net_amt +=$row['net_amount'];     
                                        $total_less +=(float)$row['amtx'];     
                                        $total_add +=(float)$row['amtx'];     
                                        $total_discount +=(float)$row['discount'];     
                                    ?>

                                <tr>
                                    <td><?=user_date(@$row['inv_date'])?></td>
                                    <td><a href="<?=url('Milling/Add_grey/').$row['gray_id']?>"><?=@$row['sr_no']?></a>
                                    </td>
                                    <td><?=@$row['name']?></td>
                                    <td><?=@$row['hsn']?></td>
                                    <td><?=@$row['warehouse_name']?></td>
                                    <td><?=@$row['pcs']?></td>
                                    <td><?=@$row['meter']?></td>
                                    <td><?=@$row['cut']?></td>
                                    <td><?=@$row['price']?></td>
                                    <td><?=@$row['total_amount']?></td>
                                    <?php if($row['tax_type'] == 'igst'){ ?>
                                    <td><?=@$row['tot_igst']?><br>(<?=$row['igst']?>%)</td>
                                    <td></td>
                                    <td></td>
                                    <td><?=@$row['tot_igst']?></td>

                                    <?php }else{ ?>
                                    <td></td>
                                    <td><?=(float)$row['tot_cgst'] ?><br>(<?=(float)$row['igst'] / 2 ?>%)</td>
                                    <td><?=(float)$row['tot_sgst']?><br>(<?=(float)$row['igst'] / 2 ?>%)</td>
                                    <td><?=(float)$row['tot_sgst'] + (float)$row['tot_cgst'] ?></td>
                                    <?php } ?>

                                    <td><?=$row['amtx']?></td>
                                    <td><?=$row['amty']?></td>
                                    <td><?=$row['discount']?></td>
                                    <td><?=$row['net_amount']?></td>
                                </tr>
                                <?php } ?>

                            </tbody>
                            <tfoot>
                                <tr style="border-bottom:1px solid #e1e6f1;">
                                    <th colspan="5">
                                        <center>Total</center>
                                    </th>
                                    <th><?=$total_pcs?></th>
                                    <th><?=$total_meter?></th>
                                    <th><?=$total_cut?></th>
                                    <th></th>
                                    <th><?=$total_amount?></th>
                                    <th><?=$total_igst?></th>
                                    <th><?=$total_cgst?></th>
                                    <th><?=$total_sgst?></th>
                                    <th><?=$total_gst_amt?></th>
                                    <th><?=$total_less?></th>
                                    <th><?=$total_add?></th>
                                    <th><?=$total_discount?></th>
                                    <th><?=$total_net_amt?></th>
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
                var purchase_type = $('input[name="purchase_type"]:checked').val();

                if (purchase_type == 'Finish') {
                    var type = 'Finish';
                } else {
                    var type = 'Grey';
                }
                return {
                    searchTerm: params.term, // search term
                    type: type // search term
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