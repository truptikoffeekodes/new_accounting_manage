<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">General Sales Register Book</h2>
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
            <a href="<?=url('Addbook/Gnrl_sales_register_xls?from='.$start_date.'&to='.$end_date.'&ac_id='.@$account_id)?>"  class="btn ripple btn-primary"><i class="fe fe-external-link"></i>Excel Export</a>

        </div>

    </div>
    <div class="responsive-background">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="advanced-search">

                <form method="post" action="<?=url('Addbook/Gnrl_sales_register')?>">
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
                                        placeholder="YYYY-MM-DD" type="text">
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
                                                <?php
                                                $from = date_create($start_date) ;                                         
                                                $to = date_create($end_date);                              
                                                 
                                                ?>
                                                <b><?=date_format($from,"d/m/Y"); ?></b> to
                                                <b><?=date_format($to,"d/m/Y"); ?></b>

                                            </td>
                                        </tr>
                                        <tr colspan="4">
                                        </tr>
                                    </table>
                                </div>
                                <table class="table table-striped table-hover table-fw-widget" id="table_list_data" data-id=""
                                 data-module="" data-filter_data=''>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Voucher Type</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Closing</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $closing = 0;
                                            if(!empty($sales_invoice))
                                            {
                                                foreach($sales_invoice as $row)
                                                {
                                                    $closing +=(float)$row['net_amount'];
                                                     
                                                       
                                        ?>
                                        <tr>
                                            <td><?=@$row['id'];?></td>
                                            <td><?=user_date($row['date']);?></td>
                                            <td><?=@$row['account_name'];?></td>
                                            <td><?=@$row['voucher_name'];?></td>
                                            <td></td>
                                            <td><?=number_format(@$row['net_amount'],2);?></td>
                                            <td><?=number_format($closing,2);?></td>
                                        </tr>
                                        <?php
                                                    }
                                                }     
                                        ?>
                                    <tfoot>
                                        <th colspan="5">
                                            <center>Closing</center>
                                        </th>
                                        <th><?=number_format($closing,2);?> CR</th>
                                        <th><?=number_format($closing,2);?> CR</th>
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
                                            <span style="size:20px;"><b>General Sales Voucher</b></span>
                                            </br>
                                            <?php
                                                $from =date_create($date['from']) ;                                         
                                                $to = date_create($date['to']);
                                            ?>
                                            <b><?=date_format($from,"d/m/Y"); ?></b> to
                                            <b><?=date_format($to,"d/m/Y"); ?></b>

                                        </td>
                                    </tr>
                                    <tr colspan="4">
                                    </tr>
                                </table>
                            </div>
                            <?php 
                               $closing=0;
                            ?>
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
                                                            href="<?=url('Addbook/gnrl_sales_voucher_wise?month=4&year='.@$sales[4]['year'])?>">April</a>
                                                    </td>
                                                    <td><?=isset($sales[4]['voucher_count']) ? $sales[4]['voucher_count'] :0;?></td>
                                                    <td></td>
                                                    <td><?=isset($sales[4]['total_net']) ? number_format($sales[4]['total_net'],2) :0;?></td>
                                                    <td><?=isset($sales[4]['total_net']) ? number_format(($closing =$closing + (float)$sales[4]['total_net']),2) : number_format($closing,2);?></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="<?=url('Addbook/gnrl_sales_voucher_wise?month=5&year='.@$sales[5]['year'])?>">May</a>
                                                    </td>
                                                    <td><?=isset($sales[5]['voucher_count']) ? $sales[5]['voucher_count'] :0;?></td>
                                                    <td></td>
                                                    <td><?=isset($sales[5]['total_net']) ? number_format($sales[5]['total_net'],2) :0;?></td>
                                                    <td><?=isset($sales[5]['total_net']) ? number_format(($closing = $closing + (float)$sales[5]['total_net']),2) : number_format($closing,2);?></td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/gnrl_sales_voucher_wise?month=6&year='.@$sales[6]['year'])?>">June</a>
                                                    </td>
                                                    <td><?=isset($sales[6]['voucher_count']) ? $sales[6]['voucher_count'] :0;?></td>
                                                    <td></td>
                                                    <td><?=isset($sales[6]['total_net']) ? number_format($sales[6]['total_net'],2) :0;?></td>
                                                    <td><?=isset($sales[6]['total_net']) ? number_format(($closing = $closing+ (float)$sales[6]['total_net']),2) : number_format($closing,2);?></td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/gnrl_sales_voucher_wise?month=7&year='.@$sales[7]['year'])?>">July</a>
                                                    </td>
                                                    <td><?=isset($sales[7]['voucher_count']) ? $sales[7]['voucher_count'] :0;?></td>
                                                    <td></td>
                                                    <td><?=isset($sales[7]['total_net']) ? number_format($sales[7]['total_net'],2) :0;?></td>
                                                    <td><?=isset($sales[7]['total_net']) ? number_format(($closing = $closing + (float)$sales[7]['total_net']),2) : number_format($closing,2);?></td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/gnrl_sales_voucher_wise?month=8&year='.@$sales[8]['year'])?>">Auguest</a>
                                                    </td>
                                                    <td><?=isset($sales[8]['voucher_count']) ? $sales[8]['voucher_count'] :0;?></td>
                                                    <td></td>
                                                    <td><?=isset($sales[8]['total_net']) ? number_format($sales[8]['total_net'],2) :0;?></td>
                                                    <td><?=isset($sales[8]['total_net']) ? number_format(($closing =$closing + (float)$sales[8]['total_net']),2) : number_format($closing,2);?></td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/gnrl_sales_voucher_wise?month=9&year='.@$sales[9]['year'])?>">September</a>
                                                    </td>
                                                    <td><?=isset($sales[9]['voucher_count']) ? $sales[9]['voucher_count'] :0;?></td>
                                                    <td></td>
                                                    <td><?=isset($sales[9]['total_net']) ? number_format($sales[9]['total_net'],2) :0;?></td>
                                                    <td><?=isset($sales[9]['total_net']) ? number_format(($closing = $closing + (float)$sales[9]['total_net']),2) : number_format($closing,2);?></td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/gnrl_sales_voucher_wise?month=10&year='.@$sales[10]['year'])?>">October</a>
                                                    </td>
                                                    <td><?=isset($sales[10]['voucher_count']) ? $sales[10]['voucher_count'] :0;?></td>
                                                    <td></td>
                                                    <td><?=isset($sales[10]['total_net']) ? number_format($sales[10]['total_net'],2) :0;?></td>
                                                    <td><?=isset($sales[10]['total_net']) ? number_format(($closing = $closing + (float)$sales[10]['total_net']),2) : number_format($closing,2);?></td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/gnrl_sales_voucher_wise?month=11&year='.@$sales[11]['year'])?>">November</a>
                                                    </td>
                                                    <td><?=isset($sales[11]['voucher_count']) ? $sales[11]['voucher_count'] :0;?></td>
                                                    <td></td>
                                                    <td><?=isset($sales[11]['total_net']) ? number_format($sales[11]['total_net'],2) :0;?></td>
                                                    <td><?=isset($sales[11]['total_net']) ? number_format(($closing = $closing + (float)$sales[11]['total_net']),2) : number_format($closing,2);?></td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/gnrl_sales_voucher_wise?month=12&year='.@$sales[12]['year'])?>">December</a>
                                                    </td>
                                                    <td><?=isset($sales[12]['voucher_count']) ? $sales[12]['voucher_count'] :0;?></td>
                                                    <td></td>
                                                    <td><?=isset($sales[12]['total_net']) ? number_format($sales[12]['total_net'],2) :0;?></td>
                                                    <td><?=isset($sales[12]['total_net']) ? number_format(($closing = $closing+ (float)$sales[12]['total_net']),2) : number_format($closing,2);?></td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/gnrl_sales_voucher_wise?month=1&year='.@$sales[1]['year'])?>">January</a>
                                                    </td>
                                                    <td><?=isset($sales[1]['voucher_count']) ? $sales[1]['voucher_count'] :0;?></td>
                                                    <td></td>
                                                    <td><?=isset($sales[1]['total_net']) ? number_format($sales[1]['total_net'],2) :0;?></td>
                                                    <td><?=isset($sales[1]['total_net']) ? number_format(($closing = $closing + (float)$sales[1]['total_net']),2) : number_format($closing,2);?></td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/gnrl_sales_voucher_wise?month=2&year='.@$sales[2]['year'])?>">February</a>
                                                    </td>
                                                    <td><?=isset($sales[2]['voucher_count']) ? $sales[2]['voucher_count'] :0;?></td>
                                                    <td></td>
                                                    <td><?=isset($sales[2]['total_net']) ? number_format($sales[2]['total_net'],2) :0;?></td>
                                                    <td><?=isset($sales[2]['total_net']) ? number_format(($closing = $closing + (float)$sales[2]['total_net']) ,2) : number_format($closing,2) ;?></td>
                                                </tr>
                                                <tr>
                                                    <td><a
                                                            href="<?=url('Addbook/gnrl_sales_voucher_wise?month=3&year='.@$sales[3]['year'])?>">March</a>
                                                    </td>
                                                    <td><?=isset($sales[3]['voucher_count']) ? $sales[3]['voucher_count'] :0;?></td>
                                                    <td></td>
                                                    <td><?=isset($sales[3]['total_net']) ? number_format($sales[3]['total_net'],2) :0;?></td>
                                                    <td><?=isset($sales[3]['total_net']) ? number_format(($closing = $closing + (float)$sales[3]['total_net']),2) : number_format($closing,2);?></td>
                                                </tr>
                                            </tbody>
                                            <?php 
                                                $total = 0;
                                                foreach($sales as $row){
                                                    $total += $row['total_net'];
                                                } 
                                            ?>
                                            <tfooter>
                                                <tr>
                                                    <th colspan="3">
                                                        <h4>Total</h4>
                                                    </th>
                                                    <th>
                                                        <h4><?=number_format(@$total,2)?> CR</h4>
                                                    </th>
                                                    <th>
                                                        <h4><?=number_format(@$closing,2)?> CR</h4>
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