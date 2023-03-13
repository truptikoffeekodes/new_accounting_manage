<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Payment Register Book</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('') ?>"><?=@$type;?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=@$title;?></li>
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

                <form method="post" action="<?=url('Addbook/payment')?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-lg-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            FROM:
                                        </div>
                                    </div>
                                    <input class="form-control dateMask" id="dateMask" name="from"
                                        placeholder="DD-MM-YYYY" type="text">
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
                                    <input class="form-control dateMask" id="" name="to" placeholder="DD-MM-YYYY"
                                        type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-lg-0">
                                <div class="input-group">
                                    <select class="form-control account" id="account" name='ac_id'>

                                    </select>
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

                    <nav class="nav main-nav-line">
                        <a class="nav-link active" data-toggle="tab" href="#all_data">All Data</a>
                        <a class="nav-link" data-toggle="tab" href="#month">Month</a>
                    </nav>

                    <div class="card-body tab-content h-100">
                        <div class="tab-pane active" id="all_data">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-fw-widget">
                                        <tr>
                                            <td>
                                                <span style="size:20px;"><b><?=@$type;?></b></span>
                                                </br>

                                                <b><?=user_date($start_date); ?></b> to
                                                <b><?=user_date($end_date); ?></b>

                                            </td>
                                        </tr>
                                        <tr colspan="4">
                                        </tr>
                                    </table>
                                </div>
                                <table class="table mg-b-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Particular</th>
                                            <th>Voucher Type</th>
                                            <th>Vch ID</th>
                                            <th>Payment Type</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Closing</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $closing = 0;
                                            if(!empty($payment_vch))
                                            {
                                                foreach($payment_vch as $row)
                                                {
                                                    $closing +=(float)$row['amount'];
                                                       
                                        ?>
                                        <tr>
                                            <td><?=user_date($row['date']);?></td>
                                            <td><?=@$row['account_name'];?></td>
                                            <td><?=@$row['mode'];?></td>
                                            <td><?=@$row['id'];?></td>
                                            <td><?=@$row['payment_type'];?></td>
                                            <td><?=@$row['amount'];?></td>
                                            <td> </td>
                                            <td><?=$closing;?></td>
                                        </tr>

                                        <?php
                                                }
                                            }
                                        ?>

                                    <tfoot>
                                        <th colspan="5">
                                            <center>Closing</center>
                                        </th>
                                        <th><?=$closing;?> DR</th>
                                        <th></th>
                                        <th><?=$closing;?> DR</th>
                                    </tfoot>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane " id="month">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-fw-widget">
                                    <tr>
                                        <td>
                                            <span style="size:20px;"><b>Payment Voucher</b></span>
                                            </br>
                                            <b><?=user_date($date['from']); ?></b> to
                                            <b><?=user_date($date['to']); ?></b>
                                        </td>
                                    </tr>
                                    <tr colspan="4">
                                    </tr>
                                </table>
                            </div>
                            <?php $closing = 0;?>
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
                                                        <h5>Voucher</h5>
                                                    </th>
                                                    <th>
                                                        <h5>Debit</h5>
                                                    </th>
                                                    <th>
                                                        <h5>Credit</h5>
                                                    </th>
                                                    <th>
                                                        <h5>Closing</h5>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/Payment_voucher_wise?month=4&year='.@$payment[4]['year'])?>">April</a>
                                                    </td>
                                                    <td><?=isset($payment[4]['voucher_count']) ? $payment[4]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($payment[4]['total']) ? number_format($payment[4]['total'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($payment[4]['total']) ? number_format(($closing = $closing + (float)$payment[4]['total']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/Payment_voucher_wise?month=5&year='.@$payment[5]['year'])?>">May</a>
                                                    </td>
                                                    <td><?=isset($payment[5]['voucher_count']) ? $payment[5]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($payment[5]['total']) ? number_format($payment[5]['total'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($payment[5]['total']) ? number_format(($closing = $closing+ (float)$payment[5]['total']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/Payment_voucher_wise?month=6&year='.@$payment[6]['year'])?>">June</a>
                                                    </td>
                                                    <td><?=isset($payment[6]['voucher_count']) ? $payment[6]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($payment[6]['total']) ? number_format($payment[6]['total'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($payment[6]['total']) ? number_format(($closing = $closing + (float)$payment[6]['total']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/Payment_voucher_wise?month=7&year='.@$payment[7]['year'])?>">July</a>
                                                    </td>
                                                    <td><?=isset($payment[7]['voucher_count']) ? $payment[7]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($payment[7]['total']) ? number_format($payment[7]['total'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($payment[7]['total']) ? number_format(($closing =$closing + (float)$payment[7]['total']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/Payment_voucher_wise?month=8&year='.@$payment[8]['year'])?>">Auguest</a>
                                                    </td>
                                                    <td><?=isset($payment[8]['voucher_count']) ? $payment[8]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($payment[8]['total']) ? number_format($payment[8]['total'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($payment[8]['total']) ? number_format(($closing = $closing + (float)$payment[8]['total']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/Payment_voucher_wise?month=9&year='.@$payment[9]['year'])?>">September</a>
                                                    </td>
                                                    <td><?=isset($payment[9]['voucher_count']) ? $payment[9]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($payment[9]['total']) ? number_format($payment[9]['total'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($payment[9]['total']) ? number_format(($closing = $closing+ (float)$payment[9]['total']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/Payment_voucher_wise?month=10&year='.@$payment[10]['year'])?>">October</a>
                                                    </td>
                                                    <td><?=isset($payment[10]['voucher_count']) ? $payment[10]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($payment[10]['total']) ? number_format($payment[10]['total'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($payment[10]['total']) ? number_format(($closing = $closing + (float)$payment[10]['total']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/Payment_voucher_wise?month=11&year='.@$payment[11]['year'])?>">November</a>
                                                    </td>
                                                    <td><?=isset($payment[11]['voucher_count']) ? $payment[11]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($payment[11]['total']) ? number_format($payment[11]['total'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($payment[11]['total']) ? number_format(($closing =$closing + (float)$payment[11]['total']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/Payment_voucher_wise?month=12&year='.@$payment[12]['year'])?>">December</a>
                                                    </td>
                                                    <td><?=isset($payment[12]['voucher_count']) ? $payment[12]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($payment[12]['total']) ? number_format($payment[12]['total'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($payment[12]['total']) ? number_format(($closing = $closing + (float)$payment[12]['total']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/Payment_voucher_wise?month=1&year='.@$payment[1]['year'])?>">January</a>
                                                    </td>
                                                    <td><?=isset($payment[1]['voucher_count']) ? $payment[1]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($payment[1]['total']) ? number_format($payment[1]['total'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($payment[1]['total']) ? number_format(($closing = $closing + (float)$payment[1]['total']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/Payment_voucher_wise?month=2&year='.@$payment[2]['year'])?>">February</a>
                                                    </td>
                                                    <td><?=isset($payment[2]['voucher_count']) ? $payment[2]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($payment[2]['total']) ? number_format($payment[2]['total'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($payment[2]['total']) ? number_format(($closing = $closing + (float)$payment[2]['total']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/Payment_voucher_wise?month=3&year='.@$payment[3]['year'])?>">March</a>
                                                    </td>
                                                    <td><?=isset($payment[3]['voucher_count']) ? $payment[3]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($payment[3]['total']) ? number_format($payment[3]['total'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($payment[3]['total']) ? number_format(($closing = $closing + (float)$payment[3]['total']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <?php 
                                                $total = 0;
                                                foreach($payment as $row){
                                                    $total += $row['total'];
                                                } 
                                            ?>
                                            <tfooter>
                                                <tr>
                                                    <th colspan="2">
                                                        <h4>Total</h4>
                                                    </th>
                                                    <th>
                                                        <h4><?=number_format(@$total,2)?> DR</h4>
                                                    </th>
                                                    <th></th>
                                                    <th>
                                                        <h4><?=number_format(@$closing,2)?> DR</h4>
                                                    </th>
                                                </tr>
                                            </tfooter>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane " id="auction">
                            <div class="table-responsive">

                            </div>
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
        width: 'resolve',
        placeholder: 'Type Account',
        ajax: {
            url: PATH + "Master/Getdata/search_sun_credit",
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