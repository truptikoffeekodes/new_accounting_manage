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
            <form method="get" action = "<?=url('Gst/b2c_large_detail')?>">
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
                                    style="float:right;"><?=@$sales_b2c_large['count'] + @$gnrl_sale_b2c_large['count']?></label>
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
                                <td><a href="<?=url('gst/b2c_large_sales_inv_vouchers?from='.$start_date.'&to='.$end_date)?>">Sales Invoice</a></td>
                                <td><?=@$sales_b2c_large['count']?></td>
                                <td><?=number_format(@$sales_b2c_large['taxable_amount'],2)?></td>
                                <td><?=number_format(@$sales_b2c_large['igst'],2)?></td>
                                <td><?=number_format(@$sales_b2c_large['cgst'],2)?></td>
                                <td><?=number_format(@$sales_b2c_large['sgst'],2)?></td>
                                <td><?=number_format(@$sales_b2c_large['cess'],2)?></td>
                                <td><?=number_format(@$sales_b2c_large['igst'] + $sales_b2c_large['cess'] + $sales_b2c_large['sgst'] + $sales_b2c_large['cgst'],2)?>
                                </td>
                                <td><?=number_format(@$sales_b2c_large['net_amount'],2)?>
                                </td>
                            </tr>
                            <tr>
                                <th>2</th>
                                <td><a href="<?=url('gst/b2c_large_gnrl_sales_inv_vouchers?from='.$start_date.'&to='.$end_date)?>">General Sales Invoice</a></td>
                                <td><?=@$gnrl_sale_b2c_large['count']?></td>
                                <td><?=number_format(@$gnrl_sale_b2c_large['taxable_amount'],2)?></td>
                                <td><?=number_format(@$gnrl_sale_b2c_large['igst'],2)?></td>
                                <td><?=number_format(@$gnrl_sale_b2c_large['cgst'],2)?></td>
                                <td><?=number_format(@$gnrl_sale_b2c_large['sgst'],2)?></td>
                                <td><?=number_format(@$gnrl_sale_b2c_large['cess'],2)?></td>
                                <td><?=number_format(@$gnrl_sale_b2c_large['igst'] + $gnrl_sale_b2c_large['cess'] + $gnrl_sale_b2c_large['sgst'] + $gnrl_sale_b2c_large['cgst'],2)?>
                                </td>
                                <td><?=number_format(@$gnrl_sale_b2c_large['net_amount'],2)?>
                                </td>
                            </tr>
                            
                        </tbody>
                        <tfooter>
                                <th>Total</th>
                                <th></th>
                                <th><?=@$sales_b2c_large['count'] + @$gnrl_sale_b2c_large['count']?></th>
                                
                                <th><?=number_format(@$sales_b2c_large['taxable_amount'] + @$gnrl_sale_b2c_large['taxable_amount'],2)?></th>
                                <th><?=number_format(@$sales_b2c_large['igst'] + @$gnrl_sale_b2c_large['igst'],2)?></th>
                                <th><?=number_format(@$sales_b2c_large['cgst'] + @$gnrl_sale_b2c_large['cgst'],2)?></th>
                                <th><?=number_format(@$sales_b2c_large['sgst'] + @$gnrl_sale_b2c_large['sgst'],2)?></th>
                                
                                <th><?=number_format(@$sales_b2c_large['cess'] + @$gnrl_sale_b2c_large['cess'],2)?></th>
                                <th><?=number_format(@$sales_b2c_large['igst'] + $sales_b2c_large['cess'] + $sales_b2c_large['sgst'] + $sales_b2c_large['cgst'] + @$gnrl_sale_b2c_large['igst'] + $gnrl_sale_b2c_large['cess'] + $gnrl_sale_b2c_large['sgst'] + $gnrl_sale_b2c_large['cgst'],2)?>
                                <th><?=number_format(@$sales_b2c_large['net_amount'] + @$gnrl_sale_b2c_large['net_amount'],2)?></th>
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
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.dateMask').mask('99-99-9999');
});
</script>
<?= $this->endSection() ?>