<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> <?=$title?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">GST</a></li>
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
            <form method="post" id="date_submit">
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
                                    <b id="start_date"><?=$start_date?></b> to
                                    <b id="end_date"><?=$end_date?></b>

                                </td>
                            </tr>
                            <tr colspan="4">
                            </tr>
                        </tbody>
                    </table>
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
                                <th>Title</th>
                                <th>Voucher Count</th>
                                <th>Taxable Amount</th>
                                <th>Invoice Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>1</th>
                                <td><a href="<?=url('gst/nill_invoices?from='.$start_date.'&to='.$end_date.'&type=inter_reg')?>">Inter State Supply to Register</a></td>
                                <td><?=@$inter_reg['count']?></td>
                                <td><?=number_format(@$inter_reg['taxable_amount'],2)?></td>
                                <td><?=number_format(@$inter_reg['net_amount'],2)?></td>
                               
                            </tr>
                            <tr>
                                <th>2</th>
                                <td><a href="<?=url('gst/nill_invoices?from='.$start_date.'&to='.$end_date.'&type=intera_reg')?>">Intra State Supply to Register</a></td>
                                <td><?=@$intera_reg['count']?></td>
                                <td><?=number_format(@$intera_reg['taxable_amount'],2)?></td>
                                <td><?=number_format(@$intera_reg['net_amount'],2)?></td>
                               
                            </tr>
                            <tr>
                                <th>3</th>
                                <td><a href="<?=url('gst/nill_invoices?from='.$start_date.'&to='.$end_date.'&type=inter_unreg')?>">Inter State Supply to UnRegister</a></td>
                                <td><?=@$inter_unreg['count']?></td>
                                <td><?=number_format(@$inter_unreg['taxable_amount'],2)?></td>
                                <td><?=number_format(@$inter_unreg['net_amount'],2)?></td>
                               
                            </tr>
                            <tr>
                                <th>4</th>
                                <td><a href="<?=url('gst/nill_invoices?from='.$start_date.'&to='.$end_date.'&type=intera_unreg')?>">Intra State Supply to UnRegister</a></td>
                                <td><?=@$intera_unreg['count']?></td>
                                <td><?=number_format(@$intera_unreg['taxable_amount'],2)?></td>
                                <td><?=number_format(@$intera_unreg['net_amount'],2)?></td>
                               
                            </tr>
                           
                        </tbody>
                        <tfooter>
                                <th>Total</th>
                                <th></th>
                                <th><?=@$inter_reg['count'] + @$intera_reg['count'] + @$inter_unreg['count'] + @$intera_unreg['count']?></th>
                                
                                <th><?=number_format(@$inter_reg['taxable_amount'] + @$intera_reg['taxable_amount'] + @$inter_unreg['taxable_amount'] + @$intera_unreg['taxable_amount'],2)?></th>
                                <th><?=number_format(@$inter_reg['net_amount'] + @$intera_reg['net_amount'] + @$inter_unreg['net_amount'] + @$intera_unreg['net_amount'],2)?></th>
                               
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