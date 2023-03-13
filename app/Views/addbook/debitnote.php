<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="container">

    <!-- Page Header -->
    <div class="page-header">
        <div> 
            <h2 class="main-content-title tx-24 mg-b-5">Debit Note Register Book</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('') ?>">Addbook</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=@$title;?></li>
            </ol>
        </div>
        <div class="btn btn-list">
            <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
            </a>
            <a href="<?=url('Addbook/Purchase_return_register_xls?from='.$start_date.'&to='.$end_date.'&ac_id='.@$account_id)?>"  class="btn ripple btn-primary"><i class="fe fe-external-link"></i>Excel Export</a>

        </div>

    </div>
    <div class="responsive-background">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="advanced-search">

                <form method="post" action="<?=url('Addbook/Purchase_return_register')?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-lg-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            FROM:
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" id="dateMask" name="from"
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
                                    <input class="form-control fc-datepicker" id="" name="to" placeholder="DD-MM-YYYY"
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
                                                <span style="size:20px;"><b>Debit Note</b></span>
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
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Narration</th>
                                            <th>Taxable</th>
                                            <th>IGST</th>
                                            <th>CGST</th>
                                            <th>SGST</th>
                                            <th>Total Tax</th>
                                            <th>Invoice Value</th>
                                            <th>Closing</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $closing = 0;
                                            if(!empty($purchase_return))
                                            {
                                                foreach($purchase_return as $row)
                                                {
                                                    $closing +=(float)$row['net_amount'];
                                                    $igst =0;
                                                    $cgst =0;
                                                    $sgst =0;
                                                       
                                        ?>
                                        <tr>
                                            <td><?=@$row['id'];?></td>
                                            <td><?=user_date($row['date']);?></td>
                                            <td><a href = "<?=url('/purchase/add_purchasereturn/'.$row['id'])?>"><?=@$row['account_name'];?></a></td>
                                            <td><?=@$row['other'];?></td>
                                            <td><?=@$row['total_amount'];?></td>
                                            <?php 
                                                $taxes = json_decode($row['taxes']);
                                                if(in_array('igst',$taxes)){
                                                    $igst = $row['tot_igst'];
                                                }else{
                                                    $cgst = $row['tot_igst']/2;
                                                    $sgst = $row['tot_igst']/2;
                                                }
                                            ?>
                                            <td><?=@$igst;?></td>
                                            <td><?=@$cgst;?></td>
                                            <td><?=@$sgst;?></td>
                                            <td><?=@$row['tot_igst'];?></td>
                                            <td><?=@$row['net_amount'];?></td>
                                            <td><?=$closing;?></td>
                                        </tr>

                                        <?php
                                                }
                                            }
                                        ?>

                                    <tfoot>
                                        <th colspan="9">
                                            <center>Closing</center>
                                        </th>
                                        <th><?=$closing;?> DR</th>
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
                                            <span style="size:20px;"><b>Purchase Voucher</b></span>
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
                                                            href="<?=url('Addbook/purchaseReturnItem_voucher_wise?month=4&year='.@$purchase[4]['year'])?>">April</a>
                                                    </td>
                                                    <td><?=isset($purchase[4]['voucher_count']) ? $purchase[4]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($purchase[4]['total_net']) ? number_format($purchase[4]['total_net'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($purchase[4]['total_net']) ? number_format(($closing = $closing + (float)$purchase[4]['total_net']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/purchaseReturnItem_voucher_wise?month=5&year='.@$purchase[5]['year'])?>">May</a>
                                                    </td>
                                                    <td><?=isset($purchase[5]['voucher_count']) ? $purchase[5]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($purchase[5]['total_net']) ? number_format($purchase[5]['total_net'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($purchase[5]['total_net']) ? number_format(($closing = $closing+ (float)$purchase[5]['total_net']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/purchaseReturnItem_voucher_wise?month=6&year='.@$purchase[6]['year'])?>">June</a>
                                                    </td>
                                                    <td><?=isset($purchase[6]['voucher_count']) ? $purchase[6]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($purchase[6]['total_net']) ? number_format($purchase[6]['total_net'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($purchase[6]['total_net']) ? number_format(($closing = $closing + (float)$purchase[6]['total_net']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/purchaseReturnItem_voucher_wise?month=7&year='.@$purchase[7]['year'])?>">July</a>
                                                    </td>
                                                    <td><?=isset($purchase[7]['voucher_count']) ? $purchase[7]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($purchase[7]['total_net']) ? number_format($purchase[7]['total_net'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($purchase[7]['total_net']) ? number_format(($closing =$closing + (float)$purchase[7]['total_net']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/purchaseReturnItem_voucher_wise?month=8&year='.@$purchase[8]['year'])?>">Auguest</a>
                                                    </td>
                                                    <td><?=isset($purchase[8]['voucher_count']) ? $purchase[8]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($purchase[8]['total_net']) ? number_format($purchase[8]['total_net'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($purchase[8]['total_net']) ? number_format(($closing = $closing + (float)$purchase[8]['total_net']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/purchaseReturnItem_voucher_wise?month=9&year='.@$purchase[9]['year'])?>">September</a>
                                                    </td>
                                                    <td><?=isset($purchase[9]['voucher_count']) ? $purchase[9]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($purchase[9]['total_net']) ? number_format($purchase[9]['total_net'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($purchase[9]['total_net']) ? number_format(($closing = $closing+ (float)$purchase[9]['total_net']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/purchaseReturnItem_voucher_wise?month=10&year='.@$purchase[10]['year'])?>">October</a>
                                                    </td>
                                                    <td><?=isset($purchase[10]['voucher_count']) ? $purchase[10]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($purchase[10]['total_net']) ? number_format($purchase[10]['total_net'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($purchase[10]['total_net']) ? number_format(($closing = $closing + (float)$purchase[10]['total_net']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/purchaseReturnItem_voucher_wise?month=11&year='.@$purchase[11]['year'])?>">November</a>
                                                    </td>
                                                    <td><?=isset($purchase[11]['voucher_count']) ? $purchase[11]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($purchase[11]['total_net']) ? number_format($purchase[11]['total_net'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($purchase[11]['total_net']) ? number_format(($closing =$closing + (float)$purchase[11]['total_net']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/purchaseReturnItem_voucher_wise?month=12&year='.@$purchase[12]['year'])?>">December</a>
                                                    </td>
                                                    <td><?=isset($purchase[12]['voucher_count']) ? $purchase[12]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($purchase[12]['total_net']) ? number_format($purchase[12]['total_net'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($purchase[12]['total_net']) ? number_format(($closing = $closing + (float)$purchase[12]['total_net']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/purchaseReturnItem_voucher_wise?month=1&year='.@$purchase[1]['year'])?>">January</a>
                                                    </td>
                                                    <td><?=isset($purchase[1]['voucher_count']) ? $purchase[1]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($purchase[1]['total_net']) ? number_format($purchase[1]['total_net'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($purchase[1]['total_net']) ? number_format(($closing = $closing + (float)$purchase[1]['total_net']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/purchaseReturnItem_voucher_wise?month=2&year='.@$purchase[2]['year'])?>">February</a>
                                                    </td>
                                                    <td><?=isset($purchase[2]['voucher_count']) ? $purchase[2]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($purchase[2]['total_net']) ? number_format($purchase[2]['total_net'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($purchase[2]['total_net']) ? number_format(($closing = $closing + (float)$purchase[2]['total_net']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/purchaseReturnItem_voucher_wise?month=3&year='.@$purchase[3]['year'])?>">March</a>
                                                    </td>
                                                    <td><?=isset($purchase[3]['voucher_count']) ? $purchase[3]['voucher_count'] :0;?>
                                                    </td>
                                                    <td><?=isset($purchase[3]['total_net']) ? number_format($purchase[3]['total_net'],2) :0;?>
                                                    </td>
                                                    <td></td>
                                                    <td><?=isset($purchase[3]['total_net']) ? number_format(($closing = $closing + (float)$purchase[3]['total_net']),2) : number_format($closing,2);?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <?php 
                                                $total = 0;
                                                foreach($purchase as $row){
                                                    $total += $row['total_net'];
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