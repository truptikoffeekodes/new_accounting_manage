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
            <form method="get" action="<?= url('Profitloss/pl_generalSales_monthly_AcWise') ?>">
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
                                            <h5>DEBIT</h5>
                                        </th>
                                        <th>
                                            <h5>Closing Taxable</h5>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $closing = 0;
                                   // echo '<pre>';Print_r($generalSale);exit;
                                    
                                    ?>
                                    <tr>
                                        <td><a href="<?= url('Profitloss/generalSales_voucher_wise?month=4&year=' . @$generalSale[4]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">April</a></td>

                                        <td><?= isset($generalSale[4]['return']) ? @$generalSale[4]['return'] : 0; ?></td>
                                        <td><?= isset($generalSale[4]['general']) ? '-' . @$generalSale[4]['general'] : 0; ?></td>
                                        <td><?= isset($generalSale[4]['total']) ? $closing += $generalSale[4]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/generalSales_voucher_wise?month=5&year=' . @$generalSale[5]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">May</a></td>
                                        <td><?= isset($generalSale[5]['return']) ? @$generalSale[5]['return'] : 0; ?></td>
                                        <td><?= isset($generalSale[5]['general']) ? '-' . @$generalSale[5]['general'] : 0; ?></td>
                                        <td><?= isset($generalSale[5]['total']) ? $closing += $generalSale[5]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/generalSales_voucher_wise?month=6&year=' . @$generalSale[6]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">June</a></td>
                                        <td><?= isset($generalSale[6]['return']) ? @$generalSale[6]['return'] : 0; ?></td>
                                        <td><?= isset($generalSale[6]['general']) ? '-' . @$generalSale[6]['general'] : 0; ?></td>
                                        <td><?= isset($generalSale[6]['total']) ? $closing += $generalSale[6]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/generalSales_voucher_wise?month=7&year=' . @$generalSale[7]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">July</a></td>
                                        <td><?= isset($generalSale[7]['return']) ? @$generalSale[7]['return'] : 0; ?></td>
                                        <td><?= isset($generalSale[7]['general']) ? '-' . @$generalSale[7]['general'] : 0; ?></td>
                                        <td><?= isset($generalSale[7]['total']) ? $closing += $generalSale[7]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/generalSales_voucher_wise?month=8&year=' . @$generalSale[8]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">August</a></td>

                                        <td><?= isset($generalSale[8]['return']) ? @$generalSale[8]['return'] : 0; ?></td>
                                        <td><?= isset($generalSale[8]['general']) ? '-' . @$generalSale[8]['general'] : 0; ?></td>
                                        <td><?= isset($generalSale[8]['total']) ? $closing += $generalSale[8]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/generalSales_voucher_wise?month=9&year=' . @$generalSale[9]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">September</a></td>
                                        <td><?= isset($generalSale[9]['return']) ? @$generalSale[9]['return'] : 0; ?></td>
                                        <td><?= isset($generalSale[9]['general']) ? '-' . @$generalSale[9]['general'] : 0; ?></td>
                                        <td><?= isset($generalSale[9]['total']) ? $closing += $generalSale[9]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/generalSales_voucher_wise?month=10&year=' . @$generalSale[10]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">October</a></td>
                                        <td><?= isset($generalSale[10]['return']) ? @$generalSale[10]['return'] : 0; ?></td>
                                        <td><?= isset($generalSale[10]['general']) ? '-' . @$generalSale[10]['general'] : 0; ?></td>
                                        <td><?= isset($generalSale[10]['total']) ? $closing += $generalSale[10]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/generalSales_voucher_wise?month=11&year=' . @$generalSale[11]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">November</a></td>
                                        <td><?= isset($generalSale[11]['return']) ? @$generalSale[11]['return'] : 0; ?></td>
                                        <td><?= isset($generalSale[11]['general']) ? '-' . @$generalSale[11]['general'] : 0; ?></td>
                                        <td><?= isset($generalSale[11]['total']) ? $closing += $generalSale[11]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/generalSales_voucher_wise?month=12&year=' . @$generalSale[12]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">December</a></td>
                                        <td><?= isset($generalSale[12]['return']) ? @$generalSale[12]['return'] : 0; ?></td>
                                        <td><?= isset($generalSale[12]['general']) ? '-' . @$generalSale[12]['general'] : 0; ?></td>
                                        <td><?= isset($generalSale[12]['total']) ? $closing += $generalSale[12]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/generalSales_voucher_wise?month=1&year=' . @$generalSale[1]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">January</a></td>
                                        <td><?= isset($generalSale[1]['return']) ? @$generalSale[1]['return'] : 0; ?></td>
                                        <td><?= isset($generalSale[1]['general']) ? '-' . @$generalSale[1]['general'] : 0; ?></td>
                                        <td><?= isset($generalSale[1]['total']) ? $closing += $generalSale[1]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/generalSales_voucher_wise?month=2&year=' . @$generalSale[2]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">February</a></td>
                                        <td><?= isset($generalSale[2]['return']) ? @$generalSale[2]['return'] : 0; ?></td>
                                        <td><?= isset($generalSale[2]['general']) ? '-' . @$generalSale[2]['general'] : 0; ?></td>
                                        <td><?= isset($generalSale[2]['total']) ? $closing += $generalSale[2]['total'] : @$closing; ?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?= url('Profitloss/generalSales_voucher_wise?month=3&year=' . @$generalSale[3]['year'] . '&id=' . $ac_id . '&type=' . @$type) ?>">March</a></td>
                                        <td><?= isset($generalSale[3]['return']) ? @$generalSale[3]['return'] : 0; ?></td>
                                        <td><?= isset($generalSale[3]['general']) ? '-' . @$generalSale[3]['general'] : 0; ?></td>
                                        <td><?= isset($generalSale[3]['total']) ? $closing += $generalSale[3]['total'] : @$closing; ?></td>
                                    </tr>
                                </tbody>
                                <?php
                                $total = 0;
                                $credit = 0;
                                $debit = 0;
                                if(!empty($generalSale)){
                                foreach ($generalSale as $row) {
                                    $debit += @$row['general'];
                                    $credit += @$row['return'];
                                    $total += @$row['total'];
                                }} ?>
                                <tfooter>
                                    <tr>
                                        <th>
                                            <h4>Total</h4>
                                        </th>
                                        <th>
                                            <h4><?= @$credit ?></h4>
                                        </th>
                                        <th>
                                            <h4><?= @$debit ?></h4>
                                        </th>
                                        <th>
                                            <h4><?= @$total ?></h4>
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