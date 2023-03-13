<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5"><?= $title ?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><?= $type == 'pl' ? 'Profit/loss' : 'Trading' ?></a></li>
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
            <form method="get" action="<?= url('Trading/generalSales_voucher_wise') ?>">
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
                                        <input class="form-control fc-datepicker" name="from" placeholder="DD-MM-YYYY" type="text">
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
                                        <input class="form-control fc-datepicker" name="to" placeholder="DD-MM-YYYY" type="text">
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
                                <span style="size:20px;"><b><?= $title ?></b></span>
                                </br>
                                <?php
                                $from = date_create($date['from']);
                                $to = date_create($date['to']);
                                ?>
                                <b><?= date_format($from, "d/m/Y"); ?></b> to
                                <b><?= date_format($to, "d/m/Y"); ?></b>

                            </td>
                        </tr>
                        <tr colspan="4">
                        </tr>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table main-table-reference mt-0 mb-0 text-center">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Party Name</th>
                                <th>Vch Type</th>
                                <th>Vch No</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Closing Bal.</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $closing = 0;
                            $debit = 0;
                            $credit = 0;
                            if (!empty($sales)) {
                                foreach (@$sales as $row) { ?>
                                    <tr>
                                        <td><?= user_date($row['date']) ?></td>
                                        <td><a href="<?= url('sales/add_ACinvoice/general/' . $row['id']) ?>"><?= $row['party_name'] ?></a></td>
                                        <td>General Sales <b><?= $row['pg_type'] ?></b></td>

                                        <td><?= $row['voucher_no'] ?></td>

                                        <?php if ($row['pg_type'] == 'return') { ?>
                                            <td>-<?= $row['taxable'] ?></td>
                                            <td></td>
                                        <?php } else { ?>
                                            <td></td>
                                            <td><?= $row['taxable'] ?></td>
                                        <?php } ?>
                                        <td><?= ($row['pg_type'] == 'return') ? '-' . $row['taxable'] : $row['taxable'] ?></td>
                                    </tr>
                            <?php
                                    if ($row['pg_type'] == 'general') {
                                        $credit += $row['taxable'];
                                    } else {
                                        $debit -= $row['taxable'];
                                    }
                                }
                            }
                            ?>

                        </tbody>

                        <tfooter>
                            <tr>
                                <th colspan="4">Closing</th>
                                <th><?= $debit ?></th>
                                <th><?= $credit ?></th>


                                <th colspan="3"><?= $credit + $debit ?></th>

                            </tr>
                            <tr>
                                <td colspan="7">
                                    <div class="paginate-section">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <!-- <div class="col-lg-3 col-md-3 col-sm-12 col-xl-3">
                                                                            <div class="page">
                                                                                <h5>Page 1 of 2</h5>
                                                                            </div>
                                                                        </div> -->
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                                                    <div class="pagination-list" id="pagination_link">
                                                        <ul class="pagination justify-content-center">
                                                            <?php

                                                            if ($page > 1) {
                                                            ?>
                                                                <li class="page-item"><a class="page-link" href="<?= url('Trading/generalSales_voucher_wise?from=' . @$date['from'] . '&to=' . @$date['to'] . '&page=' . ($page - 1)) ?>" data-ci-pagination-page="<?= $page - 1 ?>">PREV</a>
                                                                </li>
                                                            <?php
                                                            }
                                                            if ($page > 9) {
                                                            ?>
                                                                <li class="page-item"><a class="page-link" href="<?= url('Trading/generalSales_voucher_wise?from=' . @$date['from'] . '&to=' . @$date['to'] . '&page=' . ($page - 1)) ?>" data-ci-pagination-page="1">1</a></li>
                                                                <li class="blank">...</li>
                                                            <?php
                                                            }
                                                            if ($page - 2 > 0) {
                                                            ?>
                                                                <li class="page-item"><a class="page-link" href="<?= url('Trading/generalSales_voucher_wise?from=' . @$date['from'] . '&to=' . @$date['to'] . '&page=' . ($page - 2)) ?>" data-ci-pagination-page="<?= ($page - 2) ?>"><?= ($page - 2) ?></a>
                                                                </li>
                                                            <?php
                                                            }
                                                            if ($page - 1 > 0) {
                                                            ?>
                                                                <li class="page-item"><a class="page-link" href="<?= url('Trading/generalSales_voucher_wise?from=' . @$date['from'] . '&to=' . @$date['to'] . '&page=' . ($page - 1)) ?>" data-ci-pagination-page="<?= ($page - 1) ?>"><?= ($page - 1) ?></a>
                                                                </li>
                                                            <?php
                                                            }
                                                            ?>
                                                            <li class="page-item"><a class="page-link current" href="<?= url('Trading/generalSales_voucher_wise?from=' . @$date['from'] . '&to=' . @$date['to'] . '&page=' . ($page)) ?>" data-ci-pagination-page="<?= ($page) ?>"><?= $page ?></a>
                                                            </li>
                                                            <?php
                                                            if ($page + 1 < $number_of_page + 1) {
                                                            ?>
                                                                <li class="page-item"><a class="page-link" href="<?= url('Trading/generalSales_voucher_wise?from=' . @$date['from'] . '&to=' . @$date['to'] . '&page=' . ($page + 1)) ?>" data-ci-pagination-page="<?= ($page + 1) ?>"><?= ($page + 1) ?></a>
                                                                </li>
                                                            <?php
                                                            }
                                                            if ($page + 2 < $number_of_page + 1) {
                                                            ?>
                                                                <li class="page-item"><a class="page-link" href="<?= url('Trading/generalSales_voucher_wise?from=' . @$date['from'] . '&to=' . @$date['to'] . '&page=' . ($page + 2)) ?>" data-ci-pagination-page="<?= ($page + 2) ?>"><?= ($page + 2) ?></a>
                                                                </li>
                                                            <?php
                                                            }
                                                            if ($page < $number_of_page - 2) {

                                                            ?>
                                                                <li lass="page-item">...</li>
                                                                <li><a class="page-link" href="<?= url('Trading/generalSales_voucher_wise?from=' . @$date['from'] . '&to=' . @$date['to'] . '&page=' . ($number_of_page)) ?>" data-ci-pagination-page="<?= ($number_of_page) ?>"><?= $number_of_page ?></a>
                                                                </li>
                                                            <?php
                                                            }
                                                            if ($page < $number_of_page) {
                                                            ?>
                                                                <li class="page-item"><a class="page-link" href="<?= url('Trading/generalSales_voucher_wise?from=' . @$date['from'] . '&to=' . @$date['to'] . '&page=' . ($page + 1)) ?>" data-ci-pagination-page="<?= ($page + 1) ?>">Next</a>
                                                                </li>
                                                            <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfooter>
                    </table>
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