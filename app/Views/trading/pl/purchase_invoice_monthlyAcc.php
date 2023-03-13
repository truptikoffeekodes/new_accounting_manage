<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5"><?= $title ?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"> Profitloss</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>

    <div class="btn btn-list">
        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
    </div>
</div>
<!--Start Navbar -->

<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="get" action="<?= url('Profitloss/pl_purchase_invoice_monthly_AcWise') ?>">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                FROM:
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" name="from" placeholder="YYYY-MM-DD" type="text">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <!-- <label class="">To :</label> -->
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                TO:
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" name="to" placeholder="YYYY-MM-DD" type="text">
                                        <input type="hidden" name="id" value="<?= @$ac_id ?>">
                                        <input type="hidden" name="type" value="<?= @$type ?>">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="#" id="SearchButtonReset" class="btn btn-secondary" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">Reset</a>

                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-fw-widget">
                        <tr>
                            <td>
                                <span style="size:20px;"><b>Expence Voucher</b></span>
                                </br>
                                <?php
                                $from = date_create($from);
                                $to = date_create($to);
                                ?>
                                <b><?= date_format($from, "d/m/Y"); ?></b> to
                                <b><?= date_format($to, "d/m/Y"); ?></b>

                            </td>
                        </tr>
                        <tr colspan="4">
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="table-responsive">
                            <table class="table main-table-reference mt-0 mb-0 text-center">
                                <thead>
                                    <tr>
                                        <th>
                                            <h5>Month</h5>
                                        </th>
                                        <th>
                                            <h5>CREDIT</h5>
                                        </th>
                                        <th>
                                            <h5>Closing Taxable</h5>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $closing = 0;
                                   // echo '<pre>';Print_r($purchase_invoice);exit;
                                    
                                    ?>
                                    <tr>
                                        <td><a href="<?= url('Profitloss/purchase_invoice_voucher_wise?month=4&year=' . @$purchase_invoice[4]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">April</a></td>
                                        <td><?= isset($purchase_invoice[4]['total']) ? @$purchase_invoice[4]['total'] : 0; ?></td>
                                        <td><?= isset($purchase_invoice[4]['total']) ? $closing += $purchase_invoice[4]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/purchase_invoice_voucher_wise?month=5&year=' . @$purchase_invoice[5]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">May</a></td>
                                        <td><?= isset($purchase_invoice[5]['total']) ? @$purchase_invoice[5]['total'] : 0; ?></td>
                                        <td><?= isset($purchase_invoice[5]['total']) ? $closing += $purchase_invoice[5]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/purchase_invoice_voucher_wise?month=6&year=' . @$purchase_invoice[6]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">June</a></td>
                                        <td><?= isset($purchase_invoice[6]['total']) ? @$purchase_invoice[6]['total'] : 0; ?></td>
                                        <td><?= isset($purchase_invoice[6]['total']) ? $closing += $purchase_invoice[6]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/purchase_invoice_voucher_wise?month=7&year=' . @$purchase_invoice[7]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">July</a></td>
                                        <td><?= isset($purchase_invoice[7]['general']) ? '-' . @$purchase_invoice[7]['general'] : 0; ?></td>
                                        <td><?= isset($purchase_invoice[7]['total']) ? $closing += $purchase_invoice[7]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/purchase_invoice_voucher_wise?month=8&year=' . @$purchase_invoice[8]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">August</a></td>
                                        <td><?= isset($purchase_invoice[8]['total']) ? @$purchase_invoice[8]['total'] : 0; ?></td>
                                        <td><?= isset($purchase_invoice[8]['total']) ? $closing += $purchase_invoice[8]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/purchase_invoice_voucher_wise?month=9&year=' . @$purchase_invoice[9]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">September</a></td>
                                        <td><?= isset($purchase_invoice[9]['total']) ? @$purchase_invoice[9]['total'] : 0; ?></td>
                                        <td><?= isset($purchase_invoice[9]['total']) ? $closing += $purchase_invoice[9]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/purchase_invoice_voucher_wise?month=10&year=' . @$purchase_invoice[10]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">October</a></td>
                                        <td><?= isset($purchase_invoice[10]['total']) ? @$purchase_invoice[10]['total'] : 0; ?></td>
                                        <td><?= isset($purchase_invoice[10]['total']) ? $closing += $purchase_invoice[10]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/purchase_invoice_voucher_wise?month=11&year=' . @$purchase_invoice[11]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">November</a></td>
                                        <td><?= isset($purchase_invoice[11]['total']) ? @$purchase_invoice[11]['total'] : 0; ?></td>
                                        <td><?= isset($purchase_invoice[11]['total']) ? $closing += $purchase_invoice[11]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/purchase_invoice_voucher_wise?month=12&year=' . @$purchase_invoice[12]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">December</a></td>
                                        <td><?= isset($purchase_invoice[12]['total']) ? @$purchase_invoice[12]['total'] : 0; ?></td>
                                        <td><?= isset($purchase_invoice[12]['total']) ? $closing += $purchase_invoice[12]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/purchase_invoice_voucher_wise?month=1&year=' . @$purchase_invoice[1]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">January</a></td>
                                        <td><?= isset($purchase_invoice[1]['total']) ? @$purchase_invoice[1]['total'] : 0; ?></td>
                                        <td><?= isset($purchase_invoice[1]['total']) ? $closing += $purchase_invoice[1]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/purchase_invoice_voucher_wise?month=2&year=' . @$purchase_invoice[2]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">February</a></td>
                                        <td><?= isset($purchase_invoice[2]['total']) ? @$purchase_invoice[2]['total'] : 0; ?></td>
                                        <td><?= isset($purchase_invoice[2]['total']) ? $closing += $purchase_invoice[2]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/purchase_invoice_voucher_wise?month=3&year=' . @$purchase_invoice[3]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">March</a></td>
                                        <td><?= isset($purchase_invoice[3]['total']) ? @$purchase_invoice[3]['total'] : 0; ?></td>
                                        <td><?= isset($purchase_invoice[3]['total']) ? $closing += $purchase_invoice[3]['total'] : @$closing; ?></td>
                                    </tr>
                                </tbody>
                               
                                <tfooter>
                                    <tr>
                                        <th colspan="2">
                                            Total
                                        </th>
                                        <th>
                                            <h4><?= @$closing ?></h4>
                                        </th>
                                    </tr>
                                </tfooter>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End Navbar -->




<?= $this->endSection() ?>
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