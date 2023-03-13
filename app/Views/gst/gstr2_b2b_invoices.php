<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> <?=$title?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">GSTR1</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
    <div class="btn btn-list">
        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
        <a href="<?=url('gst/gstr2_b2b_invoices_excel_export?from='.$start_date.'&to='.$end_date.'&type='.$type)?>"
            class="btn ripple btn-primary"><i class="fe fe-external-link"></i> Excel Export</a>
    </div>
</div>
<!--Start Navbar -->
<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">
        <?php if($type == 'sales'){ ?>
            <form method="get" action = "<?=url('Gst/b2b_sales_inv_vouchers')?>">
            <?php }else{ ?>
                <form method="get" action = "<?=url('Gst/b2b_gnrl_sales_inv_vouchers')?>">
                <?php } ?>
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <label class="">From :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" name="from"
                                            placeholder="YYYY-MM-DD" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <label class="">To :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" name="to"
                                            placeholder="YYYY-MM-DD" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="#" id="SearchButtonReset" class="btn btn-secondary" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">Reset</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--End Navbar -->

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-fw-widget">
                        <tbody>
                            <tr>
                                <td>
                                    <span style="size:20px;"><b><?=$title?></b></span>
                                    <br>
                                    <b id="start_date"><?=user_date($start_date)?></b> to
                                    <b id="end_date"><?=user_date($end_date,2)?></b>

                                </td>
                            </tr>
                            <tr colspan="4">
                            </tr>
                        </tbody>
                    </table>
                </div>
                

                <div aria-multiselectable="true" class="accordion" id="accordion" role="tablist">
                    <div class="card">

                        <div class="card-header" id="headingOne" role="tab">
                            <a aria-controls="collapseOne" aria-expanded="false" data-toggle="collapse"
                                href="#collapseOne" class="collapsed">Total Voucher<label
                                    style="float:right;"><?=@$sale['count']?></label>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-fw-widget" id="table_list_data" data-id=""
                        data-module="" data-filter_data=''>
                        <thead>
                            <tr>
                                <th>SI NO.</th>
                                <th>Accounts</th>
                                <th>Taxable Amount</th>
                                <th>Integrated Tax Amount</th>
                                <th>Central Tax Amount</th>
                                <th>State Tax Amount</th>
                                <th>Cess Amount</th>
                                <th>Tax Amount</th>
                                <th>Invoice Amount</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php 
                            if($type == 'purchase'){
                                $result = $purchase_b2b;
                            }else{
                                $result = $gnrl_purchase_b2b;
                            }
                            foreach($result['data'] as $row ) { ?>
                            <tr>
                                <th><?=$row['invoice_no']?></th>
                                <?php if($type == 'purchase'){ ?>
                                <td><a href="<?=url('purchase/add_purchaseinvoice/'.$row['id'])?>"><?=$row['name']?></a></td>
                                <?php }else{ ?>
                                <td><a href="<?=url('purchase/sales/add_general_pur/general/'.$row['id'])?>"><?=$row['name']?></a></td>
                                <?php } ?>
                                <td><?=number_format(@$row['taxable'],2)?></td>
                                <?php 
                                    $taxes = json_decode($row['taxes']);

                                    if(in_array('igst',$taxes)){
                                ?>
                                <td><?=number_format(@$row['tot_igst'],2)?></td>
                                <td></td>
                                <td></td>
                                <?php }else{ ?>
                                <td></td>
                                <td><?=number_format(@$row['tot_cgst'],2)?></td>
                                <td><?=number_format(@$row['tot_sgst'],2)?></td>
                                <?php } ?>
                                <td><?=number_format(@$row['tot_cess'],2)?></td>
                                <td><?=number_format(@$row['tot_igst'],2)?></td>
                                <td><?=number_format(@$row['net_amount'],2)?></td>
                            </tr>
                            <?php } ?>
                            
                        </tbody>
                                <hr>

                        <tfooter>
                                <th>Total</th>
                                <th><?=@$result['count']?></th>
                                
                                <th><?=number_format(@$result['taxable_amount'],2)?></th>
                                <th><?=number_format(@$result['igst'],2)?></th>
                                <th><?=number_format(@$result['cgst'],2)?></th>
                                <th><?=number_format(@$result['sgst'],2)?></th>
                                
                                <th><?=number_format(@$result['cess'],2)?></th>
                                <th><?=number_format(@$result['igst'] + $result['cess'] + $result['sgst'] + $result['cgst'],2)?>
                                <th><?=number_format(@$result['net_amount'],2)?></th>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endsection() ?>
<?= $this->section('scripts') ?>
<script type="text/javascript">

$(document).ready(function() {
    $('#table_list_data').DataTable();
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.dateMask').mask('99-99-9999');
});

</script>
<?= $this->endSection() ?>