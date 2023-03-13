<?=$this->extend(THEME . 'templete')?>

<?=$this->section('content')?>

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
        <!-- <a href="<?=url('gst/gstr2_b2b_invoices_excel_export?from=' . @$from . '&to=' . @$to . '&type=' . @$type)?>"
            class="btn ripple btn-primary"><i class="fe fe-external-link"></i> Excel Export</a> -->

    </div>
</div>
<!--Start Navbar -->
<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">

            <form method="get" action = "<?=url('Gst/b2c_small_state_vouchers')?>">

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
                                            <input class="form-control" name="state_code"
                                            value="<?=@$state_code;?>" type="hidden">
                                            <input class="form-control" name="rate"
                                            value="<?=@$rate;?>" type="hidden">
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
                                    <b id="start_date"><?=user_date(@$from)?></b> to
                                    <b id="end_date"><?=user_date(@$to)?></b>

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
                                    style="float:right;"><?php
$count = count($state_data['new_b2c_small']);
$count1 = count($state_data['new_cdnur']);
print_r($count + $count1);
?></label>
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
                    <table class="table table mg-b-0">
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
// if($type == 'purchase'){
//     $result = $purchase_b2b;
// }else{
//     $result = $gnrl_purchase_b2b;
// }
$total_taxable = 0;
$total_igst = 0;
$total_igst1 = 0;
$total_cgst = 0;
$total_sgst = 0;
$total_cess = 0;
$total_tax = 0;
$total_invoice = 0;
foreach ($state_data['new_b2c_small'] as $row) {?>
                            <tr>
                                <th><?=$row['invoice_no']?></th>
                                <?php if (isset($row['v_type'])) {?>
                                <td><a href="<?=url('sales/add_ACinvoice/general/' . $row['id'])?>"><?=$row['name']?></a></td>
                                <?php } else {?>
                                <td><a href="<?=url('sales/add_salesinvoice/' . $row['id'])?>"><?=$row['name']?></a></td>
                                <?php }?>
                                <td><?=number_format(@$row['taxable'], 2)?></td>
                                <?php
$taxes = json_decode($row['taxes']);

    if (in_array('igst', $taxes)) {
        ?>
                                <td><?=number_format(@$row['tot_igst'], 2)?></td>
                                <td></td>
                                <td></td>
                                <?php } else {?>
                                <td></td>
                                <td><?=number_format(@$row['tot_cgst'], 2)?></td>
                                <td><?=number_format(@$row['tot_sgst'], 2)?></td>
                                <?php }?>
                                <td><?=number_format(@$row['tot_cess'], 2)?></td>
                                <td><?=number_format(@$row['tot_igst'], 2)?></td>
                                <td><?=number_format(@$row['net_amount'], 2)?></td>

                            </tr>
                            <?php
$total_taxable += $row['taxable'];
    if (in_array('igst', $taxes)) {
        $total_igst += @$row['tot_igst'];
    }
    $total_cgst += @$row['tot_cgst'];
    $total_sgst += @$row['tot_sgst'];
    $total_cess += @$row['tot_cess'];
    $total_igst1 += @$row['tot_igst'];
    $total_invoice += @$row['net_amount'];

}
foreach ($state_data['new_cdnur'] as $row) {?>
                            <tr>
                                <th><?=isset($row['invoice_no']) ? $row['invoice_no'] : $row['return_no'];?></th>
                                <?php if (isset($row['v_type'])) {?>
                                <td><a href="<?=url('sales/add_ACinvoice/return/' . $row['id'])?>"><?=$row['name']?></a></td>
                                <?php } else {?>
                                <td><a href="<?=url('sales/add_salesreturn/' . $row['id'])?>"><?=$row['name']?></a></td>
                                <?php }?>
                                
                                <td><?='-'.number_format((float)$row['taxable'], 2)?></td>
                                <?php
$taxes = json_decode($row['taxes']);

    if (in_array('igst', $taxes)) {
        ?>
                                <td><?=number_format(@$row['tot_igst'], 2)?></td>
                                <td></td>
                                <td></td>
                                <?php } else {?>
                                <td></td>
                                <td><?=number_format(@$row['tot_cgst'], 2)?></td>
                                <td><?=number_format(@$row['tot_sgst'], 2)?></td>
                                <?php }?>
                                <td><?=number_format(@$row['tot_cess'], 2)?></td>
                                <td><?=number_format(@$row['tot_igst'], 2)?></td>
                                <td><?=number_format(@$row['net_amount'], 2)?></td>

                            </tr>
                            <?php
$total_taxable -= $row['taxable'];
    if (in_array('igst', $taxes)) {
        $total_igst += $row['tot_igst'];
    }
    $total_cgst += @$row['tot_cgst'];
    $total_sgst += @$row['tot_sgst'];
    $total_cess += @$row['tot_cess'];
    $total_igst1 += @$row['tot_igst'];
    $total_invoice += @$row['net_amount'];
}?>

                        </tbody>
                                <hr>
                        <tfooter>
                            <b>
                                <th><b>Total</b></th>
                                <th></th>
                                <th><b><?=number_format(@$total_taxable, 2)?></b> </th>
                                <th><b><?=number_format(@$total_igst, 2)?></b></th>
                                <th><b><?=number_format(@$total_cgst, 2)?></b></th>
                                <th><b><?=number_format(@$total_sgst, 2)?></b></th>
                                <th><b><?=number_format(@$total_cess, 2)?></b></th>
                                <th><b><?=number_format(@$total_igst1, 2)?></b></th>
                                <th><b><?=number_format(@$total_invoice, 2)?></b></th>
                        </tfooter>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?=$this->endsection()?>
<?=$this->section('scripts')?>
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
<?=$this->endSection()?>