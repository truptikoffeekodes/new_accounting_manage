<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Purchase Gary/Finish Book</h2>
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
                <?php
            //  $request = \Config\Services::request();
            //  $uri = $request->uri;
            //  $c = $uri->getSegment(3);
            //  //print_r($c);exit;
                ?>
                <form method="post" action="<?=url('Addbook/purchase_gray_finish')?>">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="row">
                            <div class="col-lg-12 form-group">
                                    <label class="form-label">Type: <span class="tx-danger"></span></label>
                                    <div class="input-group">
                                        <select class="form-control select2" id="mode" onchange="" name="mode">
                                            <option value="">None</option>

                                            <option value="gray">Gray</option>
                                            <option value="finish">Finish</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-lg-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    FROM:
                                                </div>
                                            </div>
                                            <input class="form-control dateMask" id="dateMask" name="from"
                                                placeholder="DD-MM-YYYY" type="text">
                                            <!-- <input class="form-control" id="type" name="type" placeholder=""
                                                type="hidden" value="<?=@$type;?>"> -->
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
                                            <input class="form-control dateMask" id="" name="to"
                                                placeholder="DD-MM-YYYY" type="text">
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
    <!-- End Page Header -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card custom-card main-content-body-profile">
                <div class="card-header card-header-divider">
                    <nav class="nav main-nav-line">
                        <a class="nav-link active" data-toggle="tab" href="#all_data">All Data</a>
                        <a class="nav-link" data-toggle="tab" href="#month">Month</a>
                        <!-- <a class="nav-link" data-toggle="tab" href="#auction">Date</a>
                         -->

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
                                <table class="table mg-b-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Name</th>
                                           
                                            <th>Voucher Type</th>
                                            <th> Type</th>
                                           
                                            <th>Debit</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                
                                                if(!empty($purchase_grayfinish))
                                                {
                                                   // echo '<pre>';print_r($sales_invoice);
                                                    foreach($purchase_grayfinish as $row)
                                                    {
                                                       
                                                       // $date=$row['date'];
                                                        $date=date_create($row['date']);
                                                        $fdate=date_format($date,"d-m-y");

                                                       
                                        ?>
                                        <tr>
                                            <td><?=@$row['id'];?></td>
                                            <td><?=@$fdate;?></td>
                                            <td><?=@$row['account_name'];?></td>
                                            <td>Invoice</td>
                                           
                                            <td><?=@$row['purchase_type'];?></td>
                                            <td><?=@$row['net_amount'];?></td>
                                            <td></td>
                                        </tr>
                                        <?php
                                                    }
                                                }
                                                if(!empty($purchase_grayfinish_return))
                                                {
                                                    //echo '<pre>';print_r($salesinvoice_general);
                                                    foreach($purchase_grayfinish_return as $row)
                                                    {
                                                        $date=date_create($row['date']);
                                                        $fdate=date_format($date,"d-m-y");
                                                ?>
                                        <tr>
                                            <td><?=@$row['id'];?></td>
                                            <td><?=@$fdate;?></td>
                                            <td><?=@$row['account_name'];?></td>
                                            <td>Return</td>
                                            <td><?=@$row['purchase_type'];?></td>
                                            <td><?=@$row['net_amount'];?></td>



                                        </tr>
                                        <?php
                                                    }
                                                }
                                                    
                                        ?>
                                        <tr>
                                            <?php
                                                                $credit_total= @$total['purchase_grayfinish_total'] + @$total['purchase_grayfinishreturn_total'];
                                                               
                                                            ?>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total</td>
                                            <td></td>
                                            <td><b><?=@$credit_total;?></b></td>
                                        </tr>






                                    </tbody>
                                </table>

                                </tr>
                                </tbody>

                                </table>

                            </div>

                        </div>

                        <div class="tab-pane " id="month">
                            <div class="table-responsive">
                                <table class="table mg-b-0">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <!-- <th>Diffrence</th> -->
                                           
                                            <th>Debit</th>
                                           


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php 
                                                       // echo '<pre>';print_r($feb);
                                                       $jancredit = @$jan['total']['purchasegrayfinish_monthtotal']['purchasegrayfinish_monthtotal'] + @$jan['total']['purchasegrayfinishreturn_monthtotal']['purchasegrayfinishreturn_monthtotal'];
                                                       
                                                    ?>
                                            <td>January</td>
                                            
                                            <td><?=@$jancredit;?></td>
                                            
                                           



                                        </tr>

                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $febcredit = @$feb['total']['purchasegrayfinish_monthtotal']['purchasegrayfinish_monthtotal'] + @$feb['total']['purchasegrayfinishreturn_monthtotal']['purchasegrayfinishreturn_monthtotal'];
                                                        
                                                    ?>
                                            <td>February</td>
                                            <td><?=@$jancredit;?></td>

                                        </tr>
                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $marchcredit = @$march['total']['purchasegrayfinish_monthtotal']['purchasegrayfinish_monthtotal'] + @$march['total']['purchasegrayfinishreturn_monthtotal']['purchasegrayfinishreturn_monthtotal'];
                                                        
                                                    ?>
                                            <td>March</td>
                                            <td><?=@$marchcredit;?></td>

                                        </tr>

                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $aprcredit = @$apr['total']['purchasegrayfinish_monthtotal']['purchasegrayfinish_monthtotal'] + @$apr['total']['purchasegrayfinishreturn_monthtotal']['purchasegrayfinishreturn_monthtotal'];
                                                        
                                                    ?>
                                            <td>April</td>
                                            <td><?=@$aprcredit;?></td>

                                        </tr>
                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $maycredit = @$may['total']['purchasegrayfinish_monthtotal']['purchasegrayfinish_monthtotal'] + @$may['total']['purchasegrayfinishreturn_monthtotal']['purchasegrayfinishreturn_monthtotal'];
                                                        
                                                    ?>
                                            <td>May</td>
                                            <td><?=@$maycredit;?></td>

                                        </tr>

                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $juncredit = @$jun['total']['purchasegrayfinish_monthtotal']['purchasegrayfinish_monthtotal'] + @$jun['total']['purchasegrayfinishreturn_monthtotal']['purchasegrayfinishreturn_monthtotal'];
                                                        
                                                    ?>
                                            <td>Jun</td>
                                            <td><?=@$juncredit;?></td>

                                        </tr>
                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $julycredit = @$july['total']['purchasegrayfinish_monthtotal']['purchasegrayfinish_monthtotal'] + @$july['total']['purchasegrayfinishreturn_monthtotal']['purchasegrayfinishreturn_monthtotal'];
                                                        
                                                    ?>
                                            <td>July</td>
                                            <td><?=@$julycredit;?></td>

                                        </tr>

                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $ogstcredit = @$ogst['total']['purchasegrayfinish_monthtotal']['purchasegrayfinish_monthtotal'] + @$ogst['total']['purchasegrayfinishreturn_monthtotal']['purchasegrayfinishreturn_monthtotal'];
                                                        
                                                    ?>
                                            <td>August</td>
                                            <td><?=@$ogstcredit;?></td>

                                        </tr>
                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $sepcredit = @$sep['total']['purchasegrayfinish_monthtotal']['purchasegrayfinish_monthtotal'] + @$sep['total']['purchasegrayfinishreturn_monthtotal']['purchasegrayfinishreturn_monthtotal'];
                                                        
                                                    ?>
                                            <td>September</td>
                                            <td><?=@$sepcredit;?></td>

                                        </tr>

                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $octcredit = @$oct['total']['purchasegrayfinish_monthtotal']['purchasegrayfinish_monthtotal'] + @$oct['total']['purchasegrayfinishreturn_monthtotal']['purchasegrayfinishreturn_monthtotal'];
                                                        
                                                    ?>
                                            <td>October</td>
                                            <td><?=@$octcredit;?></td>

                                        </tr>
                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $novcredit = @$nov['total']['purchasegrayfinish_monthtotal']['purchasegrayfinish_monthtotal'] + @$nov['total']['purchasegrayfinishreturn_monthtotal']['purchasegrayfinishreturn_monthtotal'];
                                                        
                                                    ?>
                                            <td>Novenber</td>
                                            <td><?=@$novcredit;?></td>

                                        </tr>

                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $deccredit = @$dec['total']['purchasegrayfinish_monthtotal']['purchasegrayfinish_monthtotal'] + @$dec['total']['purchasegrayfinishreturn_monthtotal']['purchasegrayfinishreturn_monthtotal'];
                                                        
                                                    ?>
                                            <td>December</td>
                                            <td><?=@$deccredit;?></td>

                                        </tr>
                                    </tbody>
                                </table>

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
});
</script>

<?= $this->endSection() ?>