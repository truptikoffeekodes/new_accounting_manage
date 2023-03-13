<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5"><?= $title ?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><?= $type == 'pl' ? 'Profit/Loss' : 'Trading' ?></a></li>
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
            <form method="get" action="<?= url('Trading/generalPurchase_monthly_AcWise') ?>">

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
                                            <h5>Debit</h5>
                                        </th>
                                        <th>
                                            <h5>Credit</h5>
                                        </th>
                                        <th>
                                            <h5>Total Taxable</h5>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $closing = 0; ?>
                                    <tr>
                                        <td><a href="<?= url('Trading/generalPurchase_voucher_wise?month=4&year=' . @$generalPurchase[4]['year'] . '&id=' . $ac_id . '&type=' . $type) ?>">April</a></td>
                                        <td><?= isset($generalPurchase[4]['general']) ? number_format(@$generalPurchase[4]['general'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[4]['return']) ? '-' . number_format(@$generalPurchase[4]['return'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[4]['total']) ? number_format($closing += $generalPurchase[4]['total'], 2) : number_format(@$closing, 2); ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Trading/generalPurchase_voucher_wise?month=5&year=' . @$generalPurchase[5]['year'] . '&id=' . $ac_id . '&type=' . $type) ?>">May</a></td>
                                        <td><?= isset($generalPurchase[5]['general']) ? number_format(@$generalPurchase[5]['general'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[5]['return']) ? '-' . number_format(@$generalPurchase[5]['return'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[5]['total']) ? number_format($closing += $generalPurchase[5]['total'], 2) : number_format(@$closing, 2); ?></td>

                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Trading/generalPurchase_voucher_wise?month=6&year=' . @$generalPurchase[6]['year'] . '&id=' . $ac_id . '&type=' . $type) ?>">June</a></td>
                                        <td><?= isset($generalPurchase[6]['general']) ? number_format(@$generalPurchase[6]['general'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[6]['return']) ? '-' . number_format(@$generalPurchase[6]['return'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[6]['total']) ? number_format($closing += $generalPurchase[6]['total'], 2) : number_format(@$closing, 2); ?></td>

                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Trading/generalPurchase_voucher_wise?month=7&year=' . @$generalPurchase[7]['year'] . '&id=' . $ac_id . '&type=' . $type) ?>">July</a></td>
                                        <td><?= isset($generalPurchase[7]['general']) ? number_format(@$generalPurchase[7]['general'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[7]['return']) ? '-' . number_format(@$generalPurchase[7]['return'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[7]['total']) ? number_format($closing += $generalPurchase[7]['total'], 2) : number_format(@$closing, 2); ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Trading/generalPurchase_voucher_wise?month=8&year=' . @$generalPurchase[8]['year'] . '&id=' . $ac_id . '&type=' . $type) ?>">August</a></td>
                                        <td><?= isset($generalPurchase[8]['general']) ? number_format(@$generalPurchase[8]['general'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[8]['return']) ? '-' . number_format(@$generalPurchase[8]['return'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[8]['total']) ? number_format($closing += $generalPurchase[8]['total'], 2) : number_format(@$closing, 2); ?></td>

                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Trading/generalPurchase_voucher_wise?month=9&year=' . @$generalPurchase[9]['year'] . '&id=' . $ac_id . '&type=' . $type) ?>">September</a></td>
                                        <td><?= isset($generalPurchase[9]['general']) ? number_format(@$generalPurchase[9]['general'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[9]['return']) ? '-' . number_format(@$generalPurchase[9]['return'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[9]['total']) ? number_format($closing += $generalPurchase[9]['total'], 2) : number_format(@$closing, 2); ?></td>

                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Trading/generalPurchase_voucher_wise?month=10&year=' . @$generalPurchase[10]['year'] . '&id=' . $ac_id . '&type=' . $type) ?>">October</a></td>
                                        <td><?= isset($generalPurchase[10]['general']) ? number_format(@$generalPurchase[10]['general'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[10]['return']) ? '-' . number_format(@$generalPurchase[10]['return'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[10]['total']) ? number_format($closing += $generalPurchase[10]['total'], 2) : number_format(@$closing, 2); ?></td>

                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Trading/generalPurchase_voucher_wise?month=11&year=' . @$generalPurchase[11]['year'] . '&id=' . $ac_id . '&type=' . $type) ?>">November</a></td>
                                        <td><?= isset($generalPurchase[11]['general']) ? number_format(@$generalPurchase[11]['general'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[11]['return']) ? '-' . number_format(@$generalPurchase[11]['return'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[11]['total']) ? number_format($closing += $generalPurchase[11]['total'], 2) : number_format(@$closing, 2); ?></td>

                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Trading/generalPurchase_voucher_wise?month=12&year=' . @$generalPurchase[12]['year'] . '&id=' . $ac_id . '&type=' . $type) ?>">December</a></td>
                                        <td><?= isset($generalPurchase[12]['general']) ? number_format(@$generalPurchase[12]['general'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[12]['return']) ? '-' . number_format(@$generalPurchase[12]['return'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[12]['total']) ? number_format($closing += $generalPurchase[12]['total'], 2) : number_format(@$closing, 2); ?></td>

                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Trading/generalPurchase_voucher_wise?month=1&year=' . @$generalPurchase[1]['year'] . '&id=' . $ac_id . '&type=' . $type) ?>">January</a></td>
                                        <td><?= isset($generalPurchase[1]['general']) ? number_format(@$generalPurchase[1]['general'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[1]['return']) ? '-' . number_format(@$generalPurchase[1]['return'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[1]['total']) ? number_format($closing += $generalPurchase[1]['total'], 2) : number_format(@$closing, 2); ?></td>

                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Trading/generalPurchase_voucher_wise?month=2&year=' . @$generalPurchase[2]['year'] . '&id=' . $ac_id . '&type=' . $type) ?>">February</a></td>
                                        <td><?= isset($generalPurchase[2]['general']) ? number_format(@$generalPurchase[2]['general'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[2]['return']) ? '-' . number_format(@$generalPurchase[2]['return'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[2]['total']) ? number_format($closing += $generalPurchase[2]['total'], 2) : number_format(@$closing, 2); ?></td>

                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Trading/generalPurchase_voucher_wise?month=3&year=' . @$generalPurchase[3]['year'] . '&id=' . $ac_id . '&type=' . $type) ?>">March</a></td>
                                        <td><?= isset($generalPurchase[3]['general']) ? number_format(@$generalPurchase[3]['general'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[3]['return']) ? '-' . number_format(@$generalPurchase[3]['return'], 2) : 0; ?></td>
                                        <td><?= isset($generalPurchase[3]['total']) ? number_format($closing += $generalPurchase[3]['total'], 2) : number_format(@$closing, 2); ?></td>

                                    </tr>
                                </tbody>
                                <?php
                                $total = 0;
                                $credit = 0;
                                $debit = 0;
                                foreach ($generalPurchase as $row) {
                                    $debit += @$row['general'];
                                    $credit -= @$row['return'];
                                    $total += @$row['total'];
                                } ?>
                                <tfooter>
                                    <tr>
                                        <th>
                                            <h4>Total</h4>
                                        </th>
                                        <th>
                                            <h4><?= number_format($debit, 2) ?></h4>
                                        </th>
                                        <th>
                                            <h4><?= number_format($credit, 2) ?></h4>
                                        </th>
                                        <th>
                                            <h4><?= number_format($total, 2) ?></h4>
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