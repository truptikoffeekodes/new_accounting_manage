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
    </div>
</div>
<!--Start Navbar -->
<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="get" action = "<?=url('Gst/b2c_small_detail')?>">
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
                                    style="float:right;"><?=@$sale_b2c_small['count'] + @$gnrl_sale_b2c_small['count']?></label>
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
        <div class="card custom-card main-content-body-profile">
            <div class="card-header card-header-divider">
                <nav class="nav main-nav-line">
                    <a class="nav-link active" data-toggle="tab" href="#state_invoice">State</a>
                    <a class="nav-link" data-toggle="tab" href="#invoice">Invoice</a>
                </nav>
                <div class="card-body tab-content h-100">
                   

                    <div class="tab-pane active" id="state_invoice">
                        <div class="table-responsive">
                            <table class="table mg-b-0">
                                <thead>
                                    <tr>
                                        <!-- <th>SI NO.</th> -->
                                        <!-- <th>Type</th> -->
                                        <th>Place Of Supply</th>
                                        <th>Applicable % of Tax Rate</th>
                                        <th>Rate</th>
                                        <th>Taxable Values</th>
                                        <th>Cess Amount</th>
                                        <th>E-Commerce GSTIN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                //  echo '<pre>';print_r($state_data);exit;
                                  $state_taxable = 0;
                                  $state_cess = 0;
                                  foreach($state_data as $row1)
                                  {
                                    foreach($row1 as $row){
                                    // $state = $gmodel->get_data_table('states', array('id' => $row['acc_state']), '*');
                                    
                                    $state = get_state_data($row['acc_state']);
                                    $state_taxable += @$row['taxable'] ? (float)$row['taxable'] : 0 ;
                                    $state_cess += @$row['cess'] ? (float)$row['cess'] : 0;
                                  ?>
                                    <tr>
                                        <!-- <td>
                                            OE
                                        </td> -->
                                        <td>
                                        <a href="<?=url('gst/b2c_small_state_vouchers?from='.$start_date.'&to='.$end_date.'&state_code='.@$row['acc_state'].'&rate='.@$row['gst'])?>"> <?=@$state['state_code'] . '-' . @$state['name']?></a>
                                           
                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            <?=@$row['gst'];?>
                                        </td>
                                        <td>
                                            <?=@$row['taxable'];?>
                                        </td>
                                        <td>
                                            <?=@$row['cess'];?>
                                        </td>
                                        <td>

                                        </td>
                                    </tr>
                                   <?php
                                  }
                                }
                                  ?>
                                  <tr>
                                    <th colspan ="3">TOTAL</th>                                    
                                    <th><?=number_format($state_taxable,2)?></th>
                                    <th><?=$state_cess?></th>
                                  </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="tab-pane" id="invoice">
                        <div class="table-responsive">
                            <table class="table table mg-b-0">
                                <thead>
                                    <tr>
                                        <th>SI NO.</th>
                                        <th>Voucher</th>
                                        <th>Voucher Count</th>
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
                                    <tr>
                                        <th>1</th>
                                        <td><a href="<?=url('gst/b2c_small_sales_inv_vouchers?from='.$start_date.'&to='.$end_date)?>">Sales Invoice</a></td>
                                        <td><?=@$sale_b2c_small['count'] + @$sale_return_UnReg['count']?></td>
                                        <td><?=number_format(@$sale_b2c_small['taxable_amount']- @$sale_return_UnReg['taxable_return_amount'],2)?></td>
                                        <td><?=number_format(@$sale_b2c_small['igst'],2)?></td>
                                        <td><?=number_format(@$sale_b2c_small['cgst'],2)?></td>
                                        <td><?=number_format(@$sale_b2c_small['sgst'],2)?></td>
                                        <td><?=number_format(@$sale_b2c_small['cess'],2)?></td>
                                        <td><?=number_format(@$sale_b2c_small['igst'] + $sale_b2c_small['cess'] + $sale_b2c_small['sgst'] + $sale_b2c_small['cgst'],2)?>
                                        </td>
                                        <td><?=number_format(@$sale_b2c_small['net_amount'],2)?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>2</th>
                                        <td><a href="<?=url('gst/b2c_small_gnrl_sales_inv_vouchers?from='.$start_date.'&to='.$end_date)?>">General Sales Invoice</a></td>
                                        <td><?=@$gnrl_sale_b2c_small['count'] + @$ac_return_UnReg['count']?></td>
                                        <td><?=number_format(@$gnrl_sale_b2c_small['taxable_amount']-@$ac_return_UnReg['taxable_return_amount'],2)?></td>
                                        <td><?=number_format(@$gnrl_sale_b2c_small['igst'],2)?></td>
                                        <td><?=number_format(@$gnrl_sale_b2c_small['cgst'],2)?></td>
                                        <td><?=number_format(@$gnrl_sale_b2c_small['sgst'],2)?></td>
                                        <td><?=number_format(@$gnrl_sale_b2c_small['cess'],2)?></td>
                                        <td><?=number_format(@$gnrl_sale_b2c_small['igst'] + $gnrl_sale_b2c_small['cess'] + $gnrl_sale_b2c_small['sgst'] + $gnrl_sale_b2c_small['cgst'],2)?>
                                        </td>
                                        <td><?=number_format(@$gnrl_sale_b2c_small['net_amount'],2)?>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                                <tfooter>
                                        <th>Total</th>
                                        <th></th>
                                        <th><?=@$sale_b2c_small['count'] + @$gnrl_sale_b2c_small['count'] + @$sale_return_UnReg['count'] + @$ac_return_UnReg['count']?></th>
                                        
                                        <th><?=number_format(@$sale_b2c_small['taxable_amount'] + @$gnrl_sale_b2c_small['taxable_amount'] - @$sale_return_UnReg['taxable_return_amount'] - @$ac_return_UnReg['taxable_return_amount'],2)?></th>
                                        <th><?=number_format(@$sale['igst'] + @$gnrl_sale['igst'],2)?></th>
                                        <th><?=number_format(@$sale['cgst'] + @$gnrl_sale['cgst'],2)?></th>
                                        <th><?=number_format(@$sale['sgst'] + @$gnrl_sale['sgst'],2)?></th>
                                        
                                        <th><?=number_format(@$sale['cess'] + @$gnrl_sale['cess'],2)?></th>
                                        <th><?=number_format(@$sale['igst'] + $sale['cess'] + $sale['sgst'] + $sale['cgst'] + @$gnrl_sale['igst'] + $gnrl_sale['cess'] + $gnrl_sale['sgst'] + $gnrl_sale['cgst'],2)?>
                                        <th><?=number_format(@$sale['net_amount'] + @$gnrl_sale['net_amount'],2)?></th>
                                </tfooter>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection() ?>
<?= $this->section('scripts') ?>
<script type="text/javascript">


$(document).ready(function() {
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.dateMask').mask('99-99-9999');
});
</script>
<?= $this->endSection() ?>