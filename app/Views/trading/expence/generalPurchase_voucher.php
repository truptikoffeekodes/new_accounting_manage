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
            <form method="get" action="<?= url('Trading/generalPurchase_voucher_wise') ?>">
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
                <table class="table main-table-reference mt-0 mb-0 text-center my_table" style="width: 100%;">
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
                            if (!empty($purchase)) {
                                foreach (@$purchase as $row) { ?>
                                    <tr>
                                        <td><?= user_date($row['date']) ?></td>
                                        <td><a href="<?= url('purchase/add_general_pur/' . $row['pg_type'] . '/' . $row['id']) ?>"><?= $row['party_name'] ?></a></td>
                                        <td>General Purchase <b><?= $row['pg_type'] ?></b></td>

                                        <td><?= $row['voucher_no'] ?></td>

                                        <?php if ($row['pg_type'] == 'return') { ?>
                                            <td></td>
                                            <td>-<?= number_format($row['pg_amount'], 2) ?></td>

                                        <?php } else { ?>

                                            <td><?= number_format($row['pg_amount'], 2) ?></td>
                                            <td></td>
                                        <?php } ?>
                                        <td><?= ($row['pg_type'] == 'return') ? '' . number_format($row['taxable'], 2) : $row['taxable'] ?></td>
                                    </tr>
                            <?php
                                    if ($row['pg_type'] == 'general') {
                                        $debit += (float)$row['pg_amount'];
                                    } else {
                                        $credit -= (float)$row['pg_amount'];
                                    }
                                    $closing = $debit + $credit;
                                }
                            }
                            ?>

                        </tbody>

                        <tfooter>
                            <tr>
                                <th colspan="4">Closing</th>
                                <th><?= number_format($debit, 2) ?></th>
                                <th><?= number_format($credit, 2) ?></th>



                                <th colspan="3"><?= number_format($closing, 2) ?></th>

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
        $(".my_table").DataTable({
            "order": [
                [3, "asc"]
            ],
        });
        $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            showOtherMonths: true,
            selectOtherMonths: true
        });

        $('.dateMask').mask('99-99-9999');

    });
</script>
<?= $this->endSection() ?>