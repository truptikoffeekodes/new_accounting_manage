<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Cash Book</h2>
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
                <form method="post" action="<?=url('Addbook/cash')?>">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label class="form-label">Payment Type: <span class="tx-danger"></span></label>
                                    <div class="input-group">
                                        <select class="form-control select2" id="mode" onchange="" name="mode">
                                            <option value="">None</option>

                                            <option value="Payment">Payment</option>
                                            <option value="Receipt">Receipt</option>
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
                                            <th>Payment Mode</th>
                                           
                                            <th>Credit</th>
                                            <th>Debit</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                
                                                if(!empty($cash))
                                                {
                                                   // echo '<pre>';print_r($sales_return);
                                                    foreach($cash as $row)
                                                    {
                                                        $date=date_create($row['date']);
                                                        $fdate=date_format($date,"d-m-y");
                                                ?>
                                        <tr>
                                            <td><?=@$row['id'];?></td>
                                            <td><?=@$fdate;?></td>
                                            <td><?=@$row['account_name'];?></td>
                                            <td><?=@$row['mode'];?></td>


                                            <?php 
                                                if($row['mode']=='Receipt')
                                                {
                                            ?>
                                            <td><?=@$row['amount'];?></td>
                                            <td>0</td>
                                            <?php
                                                }else{
                                            ?>
                                            <td>0</td>
                                            <td><?=@$row['amount'];?></td>
                                            <?php
                                                }
                                            ?>
                                        </tr>
                                        <?php
                                                    }
                                                }
                                        ?>
                                        <tr>
                                            <?php
                                                                $credit_total=@$total['cashcredit_total'];
                                                                $debit_total=@$total['cashdebit_total'];
                                                               
                                                            ?>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total</td>
                                            <td><b><?=@$credit_total;?></b></td>
                                            <td><b><?=@$debit_total;?></b></td>
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
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <th>Debit</th>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank' OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <th>Credit</th>
                                            <th>Debit</th>
                                            <?php
                                               }else{
                                               ?>
                                            <th>Credit</th>
                                            <?php
                                              }
                                              ?>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php 
                                                       // echo '<pre>';print_r($feb);
                                                        $jancredit = @$jan['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] + @$jan['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] + @$jan['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] +  @$jan['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  + @$jan['total']['receipt_monthtotal']['receipt_monthtotal'] + @$jan['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$jan['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] + @$jan['total']['journalcredit_monthtotal']['journalcredit_monthtotal']
                                                                    + @$jan['ledger']['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] +@$jan['ledger']['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] +@$jan['ledger']['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + @$jan['ledger']['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  +@$jan['ledger']['total']['receipt_monthtotal']['receipt_monthtotal'] + @$jan['ledger']['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$jan['ledger']['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] +@$jan['ledger']['total']['journalcredit_monthtotal']['journalcredit_monthtotal'] ;
                                                        $jandebit = @$jan['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$jan['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$jan['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] + @$jan['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$jan['total']['payment_monthtotal']['payment_monthtotal'] + @$jan['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$jan['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$jan['total']['journaldebit_monthtotal']['journaldebit_monthtotal'] ;
                                                                    + @$jan['ledger']['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$jan['ledger']['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$jan['ledger']['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] +@$jan['ledger']['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$jan['ledger']['total']['payment_monthtotal']['payment_monthtotal'] + @$jan['ledger']['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$jan['ledger']['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$jan['ledger']['total']['journaldebit_monthtotal']['journaldebit_monthtotal'];
                                                        
                                                        //$jandiffrence = @$jancredit - @$jandebit;
                                                        // echo  $jancredit;
                                                        // echo  $jandebit;
                                                    ?>
                                            <td>January</td>
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <td><?=@$jandebit;?></td>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank' OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <td><?=@$jancredit;?></td>
                                            <td><?=@$jandebit;?></td>
                                            <?php
                                               }else{
                                               ?>
                                            <td><?=@$jancredit;?></td>
                                            <?php
                                              }
                                              ?>



                                        </tr>

                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $febcredit = @$feb['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] + @$feb['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] + @$feb['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + + @$feb['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  + @$feb['total']['receipt_monthtotal']['receipt_monthtotal'] + @$feb['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$feb['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] + @$feb['total']['journalcredit_monthtotal']['journalcredit_monthtotal']
                                                                    + @$feb['ledger']['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] +@$feb['ledger']['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] +@$feb['ledger']['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + @$feb['ledger']['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  +@$feb['ledger']['total']['receipt_monthtotal']['receipt_monthtotal'] + @$feb['ledger']['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$feb['ledger']['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] +@$feb['ledger']['total']['journalcredit_monthtotal']['journalcredit_monthtotal'] ;
                                                        $febdebit = @$feb['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$feb['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$feb['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] + @$feb['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$feb['total']['payment_monthtotal']['payment_monthtotal'] + @$feb['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$feb['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$feb['total']['journaldebit_monthtotal']['journaldebit_monthtotal']
                                                                    + @$feb['ledger']['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$feb['ledger']['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$feb['ledger']['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] +@$feb['ledger']['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$feb['ledger']['total']['payment_monthtotal']['payment_monthtotal'] + @$feb['ledger']['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$feb['ledger']['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$feb['ledger']['total']['journaldebit_monthtotal']['journaldebit_monthtotal'];
                                                        //$febdiffrence = @$febcredit - @$febdebit;
                                                        
                                                    ?>
                                            <td>February</td>
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <td><?=@$febdebit;?></td>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank'  OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <td><?=@$febcredit;?></td>
                                            <td><?=@$febdebit;?></td>
                                            <?php
                                               }else{
                                               ?>
                                            <td><?=@$febcredit;?></td>
                                            <?php
                                              }
                                              ?>

                                        </tr>
                                        <tr>
                                            <?php 
                                                        $marchcredit = @$march['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] + @$march['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] + @$march['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + + @$march['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  + @$march['total']['receipt_monthtotal']['receipt_monthtotal'] + @$march['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$march['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] + @$march['total']['journalcredit_monthtotal']['journalcredit_monthtotal']
                                                                     + @$march['ledger']['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] +@$march['ledger']['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] +@$march['ledger']['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + @$march['ledger']['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  +@$march['ledger']['total']['receipt_monthtotal']['receipt_monthtotal'] + @$march['ledger']['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$march['ledger']['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] +@$march['ledger']['total']['journalcredit_monthtotal']['journalcredit_monthtotal'] ;
                                                        
                                                        $marchdebit = @$march['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$march['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$march['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] + @$march['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$march['total']['payment_monthtotal']['payment_monthtotal'] + @$march['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$march['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$march['total']['journaldebit_monthtotal']['journaldebit_monthtotal']
                                                                    + @$march['ledger']['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$march['ledger']['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$march['ledger']['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] +@$march['ledger']['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$march['ledger']['total']['payment_monthtotal']['payment_monthtotal'] + @$march['ledger']['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$march['ledger']['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$march['ledger']['total']['journaldebit_monthtotal']['journaldebit_monthtotal'];
                                                        
                                                    ?>
                                            <td>March</td>
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <td><?=@$marchdebit;?></td>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank' OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <td><?=@$marchcredit;?></td>
                                            <td><?=@$marchdebit;?></td>
                                            <?php
                                               }else{
                                               ?>
                                            <td><?=@$marchcredit;?></td>
                                            <?php
                                              }
                                              ?>

                                        </tr>

                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $aprcredit = @$apr['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] + @$apr['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] + @$apr['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + + @$apr['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  + @$apr['total']['receipt_monthtotal']['receipt_monthtotal'] + @$apr['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$apr['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] + @$apr['total']['journalcredit_monthtotal']['journalcredit_monthtotal']
                                                                    + @$apr['ledger']['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] +@$apr['ledger']['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] +@$apr['ledger']['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + @$apr['ledger']['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  +@$apr['ledger']['total']['receipt_monthtotal']['receipt_monthtotal'] + @$apr['ledger']['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$apr['ledger']['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] +@$apr['ledger']['total']['journalcredit_monthtotal']['journalcredit_monthtotal'] ;
                                                        $aprdebit = @$apr['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$apr['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$apr['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] + @$apr['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$apr['total']['payment_monthtotal']['payment_monthtotal'] + @$apr['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$apr['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$apr['total']['journaldebit_monthtotal']['journaldebit_monthtotal']
                                                                     + @$apr['ledger']['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$apr['ledger']['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$apr['ledger']['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] +@$apr['ledger']['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$apr['ledger']['total']['payment_monthtotal']['payment_monthtotal'] + @$apr['ledger']['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$apr['ledger']['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$apr['ledger']['total']['journaldebit_monthtotal']['journaldebit_monthtotal'];
                                                        
                                                    ?>
                                            <td>April</td>
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <td><?=@$aprdebit;?></td>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank' OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <td><?=@$aprcredit;?></td>
                                            <td><?=@$aprdebit;?></td>
                                            <?php
                                               }else{
                                               ?>
                                            <td><?=@$aprcredit;?></td>
                                            <?php
                                              }
                                              ?>
                                        </tr>
                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $maycredit = @$may['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] + @$may['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] + @$may['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + + @$may['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  + @$may['total']['receipt_monthtotal']['receipt_monthtotal'] + @$may['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$may['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] + @$may['total']['journalcredit_monthtotal']['journalcredit_monthtotal']
                                                                    + @$may['ledger']['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] +@$may['ledger']['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] +@$may['ledger']['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + @$may['ledger']['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  +@$may['ledger']['total']['receipt_monthtotal']['receipt_monthtotal'] + @$may['ledger']['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$may['ledger']['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] +@$may['ledger']['total']['journalcredit_monthtotal']['journalcredit_monthtotal'] ;  
                                                        $maydebit = @$may['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$may['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$may['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] + @$may['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$may['total']['payment_monthtotal']['payment_monthtotal'] + @$may['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$may['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$may['total']['journaldebit_monthtotal']['journaldebit_monthtotal']
                                                                  + @$may['ledger']['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$may['ledger']['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$may['ledger']['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] +@$may['ledger']['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$may['ledger']['total']['payment_monthtotal']['payment_monthtotal'] + @$may['ledger']['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$may['ledger']['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$may['ledger']['total']['journaldebit_monthtotal']['journaldebit_monthtotal'];
                                                        
                                                    ?>
                                            <td>May</td>
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <td><?=@$maydebit;?></td>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank' OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <td><?=@$maycredit;?></td>
                                            <td><?=@$maydebit;?></td>
                                            <?php
                                               }else{
                                               ?>
                                            <td><?=@$maycredit;?></td>
                                            <?php
                                              }
                                              ?>

                                        </tr>

                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $juncredit = @$jun['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] + @$jun['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] + @$jun['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + + @$jun['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  + @$jun['total']['receipt_monthtotal']['receipt_monthtotal'] + @$jun['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$jun['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] + @$jun['total']['journalcredit_monthtotal']['journalcredit_monthtotal']
                                                                    + @$jun['ledger']['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] +@$jun['ledger']['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] +@$jun['ledger']['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + @$jun['ledger']['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  +@$jun['ledger']['total']['receipt_monthtotal']['receipt_monthtotal'] + @$jun['ledger']['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$jun['ledger']['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] +@$jun['ledger']['total']['journalcredit_monthtotal']['journalcredit_monthtotal'] ;
                                                        $jundebit = @$jun['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$jun['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$jun['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] + @$jun['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$jun['total']['payment_monthtotal']['payment_monthtotal'] + @$jun['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$jun['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$jun['total']['journaldebit_monthtotal']['journaldebit_monthtotal']
                                                                    + @$jun['ledger']['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$jun['ledger']['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$jun['ledger']['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] +@$jun['ledger']['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$jun['ledger']['total']['payment_monthtotal']['payment_monthtotal'] + @$jun['ledger']['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$jun['ledger']['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$jun['ledger']['total']['journaldebit_monthtotal']['journaldebit_monthtotal'];
                                                        
                                                    ?>
                                            <td>June</td>
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <td><?=@$jundebit;?></td>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank' OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <td><?=@$juncredit;?></td>
                                            <td><?=@$jundebit;?></td>
                                            <?php
                                               }else{
                                               ?>
                                            <td><?=@$juncredit;?></td>
                                            <?php
                                              }
                                              ?>

                                        </tr>
                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $julycredit = @$july['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] + @$july['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] + @$july['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + + @$july['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  + @$july['total']['receipt_monthtotal']['receipt_monthtotal'] + @$july['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$july['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] + @$july['total']['journalcredit_monthtotal']['journalcredit_monthtotal']
                                                                    + @$july['ledger']['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] +@$july['ledger']['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] +@$july['ledger']['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + @$july['ledger']['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  +@$july['ledger']['total']['receipt_monthtotal']['receipt_monthtotal'] + @$july['ledger']['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$july['ledger']['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] +@$july['ledger']['total']['journalcredit_monthtotal']['journalcredit_monthtotal'] ;
                                                        
                                                        $julydebit = @$july['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$july['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$july['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] + @$july['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$july['total']['payment_monthtotal']['payment_monthtotal'] + @$july['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$july['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$july['total']['journaldebit_monthtotal']['journaldebit_monthtotal']
                                                                    + @$july['ledger']['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$july['ledger']['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$july['ledger']['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] +@$july['ledger']['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$july['ledger']['total']['payment_monthtotal']['payment_monthtotal'] + @$july['ledger']['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$july['ledger']['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$july['ledger']['total']['journaldebit_monthtotal']['journaldebit_monthtotal'];
                                                        
                                                    ?>
                                            <td>July</td>
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <td><?=@$julydebit;?></td>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank' OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <td><?=@$julycredit;?></td>
                                            <td><?=@$julydebit;?></td>
                                            <?php
                                               }else{
                                               ?>
                                            <td><?=@$julycredit;?></td>
                                            <?php
                                              }
                                              ?>

                                        </tr>

                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $ogstcredit = @$ogst['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] + @$ogst['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] + @$ogst['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + + @$ogst['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  + @$ogst['total']['receipt_monthtotal']['receipt_monthtotal'] + @$ogst['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$ogst['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] + @$ogst['total']['journalcredit_monthtotal']['journalcredit_monthtotal']
                                                                    + @$ogst['ledger']['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] +@$ogst['ledger']['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] +@$ogst['ledger']['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + @$ogst['ledger']['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  +@$ogst['ledger']['total']['receipt_monthtotal']['receipt_monthtotal'] + @$ogst['ledger']['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$ogst['ledger']['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] +@$ogst['ledger']['total']['journalcredit_monthtotal']['journalcredit_monthtotal'] ;
                                                        
                                                        $ogstdebit = @$ogst['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$ogst['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$ogst['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] + @$ogst['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$ogst['total']['payment_monthtotal']['payment_monthtotal'] + @$ogst['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$ogst['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$ogst['total']['journaldebit_monthtotal']['journaldebit_monthtotal']
                                                                    + @$ogst['ledger']['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$ogst['ledger']['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$ogst['ledger']['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] +@$ogst['ledger']['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$ogst['ledger']['total']['payment_monthtotal']['payment_monthtotal'] + @$ogst['ledger']['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$ogst['ledger']['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$ogst['ledger']['total']['journaldebit_monthtotal']['journaldebit_monthtotal'];
                                                        
                                                    ?>
                                            <td>August</td>
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <td><?=@$ogstdebit;?></td>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank' OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <td><?=@$ogstcredit;?></td>
                                            <td><?=@$ogstdebit;?></td>
                                            <?php
                                               }else{
                                               ?>
                                            <td><?=@$ogstcredit;?></td>
                                            <?php
                                              }
                                              ?>

                                        </tr>
                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $sepcredit = @$sep['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] + @$sep['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] + @$sep['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + + @$sep['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  + @$sep['total']['receipt_monthtotal']['receipt_monthtotal'] + @$sep['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$sep['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] + @$sep['total']['journalcredit_monthtotal']['journalcredit_monthtotal']
                                                                    + @$sep['ledger']['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] +@$sep['ledger']['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] +@$sep['ledger']['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + @$sep['ledger']['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  +@$sep['ledger']['total']['receipt_monthtotal']['receipt_monthtotal'] + @$sep['ledger']['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$sep['ledger']['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] +@$sep['ledger']['total']['journalcredit_monthtotal']['journalcredit_monthtotal'] ;
                                                        
                                                        $sepdebit = @$sep['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$sep['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$sep['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] + @$sep['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$sep['total']['payment_monthtotal']['payment_monthtotal'] + @$sep['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$sep['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$sep['total']['journaldebit_monthtotal']['journaldebit_monthtotal']
                                                                    + @$sep['ledger']['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$sep['ledger']['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$sep['ledger']['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] +@$sep['ledger']['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$sep['ledger']['total']['payment_monthtotal']['payment_monthtotal'] + @$sep['ledger']['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$sep['ledger']['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$sep['ledger']['total']['journaldebit_monthtotal']['journaldebit_monthtotal'];
                                                        
                                                    ?>
                                            <td>September</td>
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <td><?=@$sepdebit;?></td>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank' OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <td><?=@$sepcredit;?></td>
                                            <td><?=@$sepdebit;?></td>
                                            <?php
                                               }else{
                                               ?>
                                            <td><?=@$sepcredit;?></td>
                                            <?php
                                              }
                                              ?>

                                        </tr>

                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $octcredit = @$oct['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] + @$oct['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] + @$oct['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + + @$oct['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  + @$oct['total']['receipt_monthtotal']['receipt_monthtotal'] + @$oct['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$oct['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] + @$oct['total']['journalcredit_monthtotal']['journalcredit_monthtotal']
                                                                    + @$oct['ledger']['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] +@$oct['ledger']['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] +@$oct['ledger']['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + @$oct['ledger']['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  +@$oct['ledger']['total']['receipt_monthtotal']['receipt_monthtotal'] + @$oct['ledger']['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$oct['ledger']['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] +@$oct['ledger']['total']['journalcredit_monthtotal']['journalcredit_monthtotal'] ;
                                                        
                                                        $octdebit = @$oct['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$oct['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$oct['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] + @$oct['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$oct['total']['payment_monthtotal']['payment_monthtotal'] + @$oct['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$oct['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$oct['total']['journaldebit_monthtotal']['journaldebit_monthtotal']
                                                                    + @$oct['ledger']['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$oct['ledger']['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$oct['ledger']['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] +@$oct['ledger']['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$oct['ledger']['total']['payment_monthtotal']['payment_monthtotal'] + @$oct['ledger']['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$oct['ledger']['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$oct['ledger']['total']['journaldebit_monthtotal']['journaldebit_monthtotal'];
                                                        
                                                    ?>
                                            <td>October</td>
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <td><?=@$octdebit;?></td>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank' OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <td><?=@$octcredit;?></td>
                                            <td><?=@$octdebit;?></td>
                                            <?php
                                               }else{
                                               ?>
                                            <td><?=@$octcredit;?></td>
                                            <?php
                                              }
                                              ?>

                                        </tr>
                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $novcredit = @$nov['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] + @$nov['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] + @$nov['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + + @$nov['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  + @$nov['total']['receipt_monthtotal']['receipt_monthtotal'] + @$nov['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$nov['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] + @$nov['total']['journalcredit_monthtotal']['journalcredit_monthtotal']
                                                                    + @$nov['ledger']['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] +@$nov['ledger']['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] +@$nov['ledger']['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + @$nov['ledger']['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  +@$nov['ledger']['total']['receipt_monthtotal']['receipt_monthtotal'] + @$nov['ledger']['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$nov['ledger']['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] +@$nov['ledger']['total']['journalcredit_monthtotal']['journalcredit_monthtotal'] ;
                                                        
                                                        $novdebit = @$nov['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$nov['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$nov['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] + @$nov['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$nov['total']['payment_monthtotal']['payment_monthtotal'] + @$nov['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$nov['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$nov['total']['journaldebit_monthtotal']['journaldebit_monthtotal']
                                                                     + @$nov['ledger']['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$nov['ledger']['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$nov['ledger']['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] +@$nov['ledger']['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$nov['ledger']['total']['payment_monthtotal']['payment_monthtotal'] + @$nov['ledger']['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$nov['ledger']['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$nov['ledger']['total']['journaldebit_monthtotal']['journaldebit_monthtotal'];
                                                        
                                                    ?>
                                            <td>November</td>
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <td><?=@$novdebit;?></td>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank' OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <td><?=@$novcredit;?></td>
                                            <td><?=@$novdebit;?></td>
                                            <?php
                                               }else{
                                               ?>
                                            <td><?=@$novcredit;?></td>
                                            <?php
                                              }
                                              ?>

                                        </tr>

                                        <tr>
                                            <?php 
                                                        //echo '<pre>';print_r($feb);
                                                        $deccredit = @$dec['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] + @$dec['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] + @$dec['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + + @$dec['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  + @$dec['total']['receipt_monthtotal']['receipt_monthtotal'] + @$dec['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$dec['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] + @$dec['total']['journalcredit_monthtotal']['journalcredit_monthtotal']
                                                                    + @$dec['ledger']['total']['purchaseinvoice_monthtotal']['purchaseinvoice_monthtotal'] +@$dec['ledger']['total']['purchasegeneralinvoice_monthtotal']['purchasegeneralinvoice_monthtotal'] +@$dec['ledger']['total']['salesreturn_monthtotal']['salesreturn_monthtotal'] + @$dec['ledger']['total']['salesreturn_general_monthtotal']['salesreturn_general_monthtotal']  +@$dec['ledger']['total']['receipt_monthtotal']['receipt_monthtotal'] + @$dec['ledger']['total']['cashcredit_monthtotal']['cashcredit_monthtotal'] + @$dec['ledger']['total']['bankcredit_monthtotal']['bankcredit_monthtotal'] +@$dec['ledger']['total']['journalcredit_monthtotal']['journalcredit_monthtotal'] ;
                                                        
                                                        $decdebit = @$dec['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$dec['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$dec['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] + @$dec['total']['purchasereturn_general_monthtotal']['purchasereturn_general_monthtotal'] + @$dec['total']['payment_monthtotal']['payment_monthtotal'] + @$dec['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$dec['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$dec['total']['journaldebit_monthtotal']['journaldebit_monthtotal']
                                                                  + @$dec['ledger']['total']['salesinvoice_monthtotal']['salesinvoice_monthtotal'] + @$dec['ledger']['total']['salesgeneralinvoice_monthtotal']['salesgeneralinvoice_monthtotal'] + @$dec['ledger']['total']['purchasereturn_monthtotal']['purchasereturn_monthtotal'] +@$dec['ledger']['total']['purchasegeneralreturn_monthtotal']['purchasegeneralreturn_monthtotal'] + @$dec['ledger']['total']['payment_monthtotal']['payment_monthtotal'] + @$dec['ledger']['total']['cashdebit_monthtotal']['cashdebit_monthtotal'] + @$dec['ledger']['total']['bankdebit_monthtotal']['bankdebit_monthtotal'] + @$dec['ledger']['total']['journaldebit_monthtotal']['journaldebit_monthtotal'];
                                                        
                                                    ?>
                                            <td>December</td>
                                            <?php 
                                               if($type=='sales' OR $type=='payment' OR $type=='debitnote')
                                               {
                                              ?>
                                            <td><?=@$decdebit;?></td>
                                            <?php
                                               }
                                               else if($type=='cash' OR $type=='bank' OR $type=='journal' OR $type=='ledger')
                                               {
                                              ?>
                                            <td><?=@$deccredit;?></td>
                                            <td><?=@$decdebit;?></td>
                                            <?php
                                               }else{
                                               ?>
                                            <td><?=@$deccredit;?></td>
                                            <?php
                                              }
                                              ?>

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