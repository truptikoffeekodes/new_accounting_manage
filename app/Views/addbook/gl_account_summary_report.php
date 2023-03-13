<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Gl Group</h2>
            <ol class="breadcrumb">

                <li class="breadcrumb-item active" aria-current="page"><?= @$title; ?></li>
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
    <div class="responsive-background">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="advanced-search">

                <form method="get" action="<?= url('Addbook/closing_bal_account_report') ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-lg-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            FROM:
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" id="" name="from" placeholder="YYYY-MM-DD"
                                        type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-lg-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            TO:
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" id="" name="to" placeholder="YYYY-MM-DD"
                                        type="text">
                                    <input id="" name="account_id" value="<?= @$account_id; ?>" type="hidden">
                                    <input id="" name="type" value="<?= @$type; ?>" type="hidden">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-2">
                        <button type="submit" class="btn btn-primary">Apply</button>
                        <a href="#" id="SearchButtonReset" class="btn btn-secondary" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- End Page Header -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card custom-card main-content-body-profile">
                <div class="card-header card-header-divider">
                    <div class="card-body tab-content h-100">
                        <div class="table-responsive">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-fw-widget">
                                    <tr>
                                        <td>
                                            <b><?= @$account_name; ?></b>
                                            </br>
                                            <?php
                                            $from = date_create(@$start_date);
                                            $to = date_create(@$end_date);

                                            ?>
                                            <b><?= date_format(@$from, "d/m/Y"); ?></b> to
                                            <b><?= date_format(@$to, "d/m/Y"); ?></b>

                                        </td>
                                    </tr>
                                    <tr colspan="4">
                                    </tr>
                                </table>
                            </div>
                            <table class="table table-striped table-hover table-fw-widget" id="table_list_data"
                                data-id="" data-module="" data-filter_data=''>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Party Name</th>
                                        <th>Vch Type</th>
                                        <th>Vch No</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $closing = 0;
                                    $credit = 0;
                                    $debit = 0;
                                    //echo '<pre>';Print_r($gl_account_summary);exit;
                                  
                                    ?>

                                    <tr>
                                        <td>Opening Bal</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                            <?php
                                            if ($gl_account_summary['opening_type'] == 'Debit') {
                                                $debit += $gl_account_summary['opening_bal'];
                                            ?>
                                        <td><?= @$gl_account_summary['opening_bal']; ?></td>
                                        <td></td>
                                            <?php
                                            }
                                            else
                                            {
                                                ?>
                                        <td></td>
                                        <td><?= @$gl_account_summary['opening_bal']; ?></td>
                                            <?php
                                            }
                                            ?>
                                    </tr>
                                   
                                        
                                    <?php
                                    
                                    $closing += $gl_account_summary['opening_bal'];
                                    
                                    if (isset($gl_account_summary['bank'])) {
                                        foreach ($gl_account_summary['bank'] as $row) {
                                    ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>
                                                <?php if ($row['payment_type'] == 'bank') { ?>
                                                    <td><a href="<?= url('Bank/add_banktrans/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                    </td>
                                                <?php } else {  ?>
                                                    <td><a href="<?= url('Bank/add_cashtrans/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                    </td>
                                                <?php } ?>
                                                <td> <b><?= strtoupper($row['payment_type']) . ' ' . $row['mode'] ?></b></td>
                                                <td><?= $row['id'] ?></td>
                                                <?php if ($row['mode'] == 'Receipt') {
                                                    $debit += $row['taxable'];
                                                    $closing += $row['taxable'];
                                                ?>
                                                    <td></td>
                                                    <td><?= number_format($row['taxable'], 2) ?></td>
                                                <?php } else {
                                                    $credit += $row['taxable'];
                                                    $closing -= $row['taxable'];
                                                ?>
                                                    <td>-<?= number_format($row['taxable'], 2) ?></td>
                                                    <td></td>
                                                <?php } ?>
                                            </tr>
                                        <?php
                                        }
                                    }

                                    if (isset($gl_account_summary['jv'])) {
                                        foreach ($gl_account_summary['jv'] as $row) { ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>
                                                <td><a href="<?= url('Bank/add_jvparticular/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>
                                                <td><b> Jounral Voucher</b> </td>
                                                <td><?= $row['id'] ?></td>
                                                <?php if ($row['dr_cr'] == "dr") {
                                                    $debit += $row['taxable'];
                                                    $closing -= $row['taxable'];
                                                ?>
                                                    <td> -<?= number_format($row['taxable'], 2) ?></td>
                                                    <td></td>
                                                <?php } else {
                                                    $credit += $row['taxable'];
                                                    $closing += $row['taxable'];
                                                ?>
                                                    <td></td>
                                                    <td><?= number_format($row['taxable'], 2) ?></td>
                                                <?php } ?>


                                            </tr>

                                        <?php

                                        }
                                    }
                                    if (isset($gl_account_summary['curr_jv'])) {
                                        foreach ($gl_account_summary['curr_jv'] as $row) { ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>
                                                <td><a href="<?= url('Bank/add_jvparticular/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>
                                                <td><b> Jounral Voucher</b> </td>
                                                <td><?= $row['id'] ?></td>
                                                <?php if ($row['dr_cr'] == "dr") {
                                                    $credit += $row['taxable'];
                                                    $closing += $row['taxable'];
                                                ?>
                                                    <td></td>
                                                    <td> <?= number_format($row['taxable'], 2) ?></td>

                                                <?php } else {
                                                    $debit += $row['taxable'];
                                                    $closing -= $row['taxable'];
                                                ?>

                                                    <td>-<?= number_format($row['taxable'], 2) ?></td>
                                                    <td></td>
                                                <?php } ?>


                                            </tr>

                                        <?php

                                        }
                                    }
                                    if (isset($gl_account_summary['purchase_invoice'])) {
                                        foreach ($gl_account_summary['purchase_invoice'] as $row) {
                                            $credit += $row['taxable'];
                                            $closing += $row['taxable'];
                                        ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>
                                                <td><a href="<?= url('purchase/add_purchaseinvoice/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>
                                                <td> <b>Purchase Voucher</b></td>
                                                <td><?= $row['id'] ?></td>
                                                <td></td>
                                                <td><?= number_format($row['taxable'], 2) ?></td>
                                            </tr>
                                        <?php
                                        }
                                    }
                                    if (isset($gl_account_summary['purchase_return'])) {
                                        foreach ($gl_account_summary['purchase_return'] as $row) {
                                            $debit += $row['taxable'];
                                            $closing -= $row['taxable'];
                                        ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>
                                                <td><a href="<?= url('purchase/add_purchaseinvoice/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>
                                                <td> <b>Purchase Return Voucher</b></td>
                                                <td><?= $row['id'] ?></td>
                                                <td><?= number_format($row['taxable'], 2) ?></td>
                                                <td></td>
                                            </tr>
                                        <?php
                                        }
                                    }
                                    if (isset($gl_account_summary['purchase_general'])) {
                                        foreach ($gl_account_summary['purchase_general'] as $row) {

                                        ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>
                                                <td><a href="<?= url('purchase/add_general_pur/' . $row['pg_type'] . '/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>
                                                <td>General Purchase <b><?= $row['pg_type'] ?></b></td>

                                                <td><?= $row['voucher_no'] ?></td>

                                                <?php if ($row['pg_type'] == 'return') {
                                                    $debit += $row['pg_amount'];
                                                    $closing -= $row['pg_amount'];
                                                ?>
                                                    <td></td>
                                                    <td>-<?= number_format($row['pg_amount'], 2) ?></td>

                                                <?php } else {
                                                    $credit += $row['pg_amount'];
                                                    $closing += $row['pg_amount'];
                                                ?>

                                                    <td><?= number_format($row['pg_amount'], 2) ?></td>
                                                    <td></td>
                                                <?php
                                                }
                                                ?>
                                            </tr>
                                        <?php

                                        }
                                    }
                                    if (isset($gl_account_summary['sales_invoice'])) {
                                        foreach ($gl_account_summary['sales_invoice'] as $row) {
                                            $credit += $row['taxable'];
                                            $closing += $row['taxable'];
                                        ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>
                                                <td><a href="<?= url('sales/add_salesinvoice/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>
                                                <td> <b>Sales Voucher</b></td>
                                                <td><?= $row['id'] ?></td>
                                                <td></td>
                                                <td><?= number_format($row['taxable'], 2) ?></td>

                                            </tr>
                                        <?php
                                        }
                                    }
                                    if (isset($gl_account_summary['sales_return'])) {
                                        foreach ($gl_account_summary['sales_return'] as $row) {
                                            $debit += $row['taxable'];
                                            $closing -= $row['taxable'];
                                        ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>
                                                <td><a href="<?= url('sales/add_salesreturn/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>
                                                <td> <b>Sales Return</b></td>
                                                <td><?= $row['id'] ?></td>

                                                <td><?= '-' . number_format($row['taxable'], 2) ?></td>
                                                <td></td>
                                            </tr>
                                        <?php
                                        }
                                    }
                                    if (isset($gl_account_summary['sales_general'])) {
                                        foreach ($gl_account_summary['sales_general'] as $row) {

                                        ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>
                                                <td><a href="<?= url('sales/add_ACinvoice/' . $row['pg_type'] . '/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>
                                                <td>General Sales <b><?= $row['pg_type'] ?></b></td>

                                                <td><?= $row['voucher_no'] ?></td>

                                                <?php if ($row['pg_type'] == 'return') {
                                                    $debit += $row['pg_amount'];
                                                    $closing -= $row['pg_amount'];
                                                ?>

                                                    <td>-<?= number_format($row['pg_amount'], 2) ?></td>
                                                    <td></td>

                                                <?php } else {
                                                    $credit += $row['pg_amount'];
                                                    $closing += $row['pg_amount'];
                                                ?>
                                                    <td></td>
                                                    <td><?= number_format($row['pg_amount'], 2) ?></td>

                                                <?php } ?>
                                            </tr>
                                        <?php
                                        }
                                    }
                                    if (isset($gl_account_summary['curr_sales_general'])) {
                                        foreach ($gl_account_summary['curr_sales_general'] as $row) {
                                            $credit += $row['taxable'];
                                            $closing += $row['taxable'];
                                        ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>
                                                <td><a href="<?= url('sales/add_ACinvoice/general/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>
                                                <td> <b>General Sales</b></td>
                                                <td><?= $row['id'] ?></td>
                                                <td></td>
                                                <td><?= number_format($row['taxable'], 2) ?></td>

                                            </tr>
                                        <?php
                                        }
                                    }
                                    if (isset($gl_account_summary['sales_general_return'])) {
                                        foreach ($gl_account_summary['sales_general_return'] as $row) {
                                            $debit += $row['taxable'];
                                            $closing -= $row['taxable'];
                                        ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>
                                                <td><a href="<?= url('sales/add_ACinvoice/return/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>
                                                <td> <b>General Sales Return</b></td>
                                                <td><?= $row['id'] ?></td>

                                                <td><?= '-' . number_format($row['taxable'], 2) ?></td>
                                                <td></td>
                                            </tr>
                                        <?php
                                        }
                                    }
                                    if (isset($gl_account_summary['bank_per'])) {
                                        foreach ($gl_account_summary['bank_per'] as $row) {
                                        ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>
                                                <td><a href="<?= url('Bank/add_banktrans/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>
                                                <td> <b><?= strtoupper($row['payment_type']) . ' ' . $row['mode'] ?></b></td>
                                                <td><?= $row['id'] ?></td>
                                                <?php if ($row['mode'] == 'Receipt') {
                                                    $debit += $row['taxable'];
                                                    $closing -= $row['taxable'];
                                                ?>
                                                    <td></td>
                                                    <td>-<?= number_format($row['taxable'], 2) ?></td>
                                                <?php } else {
                                                    $credit += $row['taxable'];
                                                    $closing += $row['taxable'];
                                                ?>
                                                    <td><?= number_format($row['taxable'], 2) ?></td>
                                                    <td></td>
                                                <?php } ?>
                                            </tr>
                                        <?php
                                        }
                                    }
                                    if (isset($gl_account_summary['bank_acc'])) {
                                        foreach ($gl_account_summary['bank_acc'] as $row) {
                                        ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>

                                                <td><a href="<?= url('Bank/add_banktrans/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>
                                                <td> <b><?= strtoupper($row['payment_type']) . ' ' . $row['mode'] ?></b></td>
                                                <td><?= $row['id'] ?></td>
                                                <?php if ($row['mode'] == 'Receipt') {
                                                    $debit += $row['total'];
                                                    $closing += $row['total'];
                                                ?>
                                                    <td></td>
                                                    <td><?= number_format($row['total'], 2) ?></td>
                                                <?php } else {
                                                    $credit += $row['total'];
                                                    $closing -= $row['total'];
                                                ?>
                                                    <td>-<?= number_format($row['total'], 2) ?></td>
                                                    <td></td>
                                                <?php } ?>
                                            </tr>
                                        <?php
                                        }
                                    }
                                    if (isset($gl_account_summary['contra_per'])) {
                                        foreach ($gl_account_summary['contra_per'] as $row) {
                                        ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>

                                                <td><a href="<?= url('Bank/add_cashtrans/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>

                                                <td> <b><?= strtoupper($row['payment_type']) . ' ' . $row['mode'] ?></b></td>
                                                <td><?= $row['id'] ?></td>

                                                <?php
                                                $debit += $row['taxable'];
                                                $closing -= $row['taxable'];
                                                ?>
                                                <td>-<?= number_format($row['taxable'], 2) ?></td>
                                                <td></td>

                                            </tr>
                                        <?php
                                        }
                                    }
                                    if (isset($gl_account_summary['cash_acc'])) {
                                        foreach ($gl_account_summary['cash_acc'] as $row) {
                                        ?>
                                            <tr>
                                                <td><?= user_date($row['date']) ?></td>

                                                <td><a href="<?= url('Bank/add_cashtrans/' . $row['id']) ?>"><?= $row['party_name'] ?></a>
                                                </td>

                                                <td> <b><?= strtoupper($row['payment_type']) . ' ' . $row['mode'] ?></b></td>
                                                <td><?= $row['id'] ?></td>


                                                <?php
                                                $credit += $row['taxable'];
                                                $closing += $row['taxable'];
                                                ?>
                                                <td></td>
                                                <td>-<?= number_format($row['taxable'], 2) ?></td>


                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>

                                </tbody>
                                <tfooter>
                                    <tr>
                                        <th colspan="4">Total</th>
                                        <th>-<?= number_format($debit, 2) ?></th>
                                        <th><?= number_format($credit, 2) ?></th>
                                    </tr>
                                    <tr>

                                        <th colspan="4">Closing</th>
                                        <th></th>
                                        <th>
                                            <?= number_format($closing, 2) ?>
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


<?= $this->endSection() ?>

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

    $('.select2').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });

    $('#bills').on('select2:select', function(e) {
        var data = e.params.data;

        $('#bill_tb').val(data.table);
    });

    $("#account").select2({
        width: '100%',
        placeholder: 'Type Account',
        ajax: {
            url: PATH + "Master/Getdata/search_sun_debtor",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });
});
</script>

<?= $this->endSection() ?>