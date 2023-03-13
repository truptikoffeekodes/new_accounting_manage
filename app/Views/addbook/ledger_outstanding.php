<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Account Book</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('') ?>"><?=@$title;?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=@$type;?></li>
            </ol>
        </div>
        <div class="btn btn-list">
            <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true"
                aria-label="Toggle navigation">
                <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
            </a>
            <a href="<?=url('Addbook/ledgeroutstanding_xls_export?'.'account_id='.@$account_id.'&from='.$from.'&to='.$to)?>"
                class="btn ripple btn-primary"><i class="fe fe-external-link"></i> Excel Export</a>

        </div>


    </div>
    <div class="responsive-background">
        <div class="collapse navbar-collapse show" id="navbarSupportedContent">
            <div class="advanced-search">
                <form method="post" action="<?=url('Addbook/Ledger_outstanding')?>">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="row">


                                <div class="col-lg-12 form-group">
                                    <label class="form-label">Account: <span class="tx-danger"></span></label>
                                    <div class="input-group">
                                        <select class="form-control ac_select2" id="" onchange="" name="account_id">
                                            <option value="">None</option>

                                            <?php
                                            if(!empty($account))
                                                foreach($account as $row)
                                                {
                                            ?>

                                            <option value="<?=@$row['id']?>">
                                                <?=@$row['name']?>(<?=@$row['id']?>)
                                            </option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                    <input type="hidden" name="type" value="<?=@$type;?>">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-lg-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    FROM:
                                                </div>
                                            </div>
                                            <input class="form-control fc-datepicker" name="start_date"
                                                placeholder="YYYY-MM-DD" type="text">
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
                                            <input class="form-control fc-datepicker" id="" name="end_date"
                                                placeholder="YYYY-MM-DD" type="text">
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card custom-card main-content-body-profile">
                <div class="card-header card-header-divider">


                    <div class="responsive-background">
                        <div>
                            <div class="advanced-search">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered table-fw-widget">
                                                <tr>
                                                    <td>
                                                        <span style="size:20px;"><b><?=@$type;?></b></span>
                                                        </br>
                                                        <?php
                                                // $from = date_create($start_date) ;                                         
                                                 //$to = date_create($end_date);
                                                 if(!empty($accountname))
                                                 {
                                                     //print_r($ledger['accountname']->name);exit;
                                                    
                                                      // $lname=$data['accountname'];
                                            ?>
                                                        <span style="size:20px;"><b>Ledger Name:
                                                                <?=@$accountname;?></b></span>
                                                        </br>
                                                        <?php
                                                      
                                                 }  

                                                 
                                            ?>



                                                    </td>
                                                </tr>
                                                <tr colspan="4">
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table mg-b-0">
                                                <thead>
                                                    <tr>

                                                        <th>Invoice Id</th>
                                                        <th>Invoice Date</th>
                                                        <th>Account_name</th>
                                                        <th>Receivable Amount</th>
                                                        <th>Paybale Amount</th>
                                                        <th>Outstandig Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                                    if(!empty($sales_invoice))
                                                                    {
                                                                        foreach($sales_invoice as $row)
                                                                        {
                                                                            // $debit_total= @$value['payment_total'] + @$value['cashdebit_total'] + @$value['bankdebit_total'] + @$value['journaldebit_total'];
                                                                                
                                                                            // $credit_total= @$value['receipt_total'] + @$value['cashcredit_total'] + @$value['bankcredit_total'] + @$value['journalcredit_total'];
                                                                            // $diffrence = $credit_total - $debit_total; 
                                                            
                                                                    ?>
                                                    <tr>
                                                        <td><?=@$row['inv_id'];?></td>
                                                        <td><?=@$row['inv_date'];?></td>
                                                        <td><?=@$row['account_name'];?></td>
                                                        <td><?=@$row['net_amount'];?></td>
                                                        <td><?=@$row['amount'];?></td>
                                                        <td><?=@$row['panding_amount'];?></td>

                                                    </tr>
                                                    <?php 
                                                                        }
                                                                    }

                                                                    if(!empty($sales_ACinvoice))
                                                                    {
                                                                        foreach($sales_ACinvoice as $row)
                                                                        {
                                                                            // $debit_total= @$value['payment_total'] + @$value['cashdebit_total'] + @$value['bankdebit_total'] + @$value['journaldebit_total'];
                                                                                
                                                                            // $credit_total= @$value['receipt_total'] + @$value['cashcredit_total'] + @$value['bankcredit_total'] + @$value['journalcredit_total'];
                                                                            // $diffrence = $credit_total - $debit_total; 
                                                            
                                                                    ?>
                                                    <tr>
                                                        <td><?=@$row['ginv_id'];?></td>
                                                        <td><?=@$row['ginv_date'];?></td>
                                                        <td><?=@$row['gaccount_name'];?></td>
                                                        <td><?=@$row['gnet_amount'];?></td>
                                                        <td><?=@$row['gamount'];?></td>
                                                        <td><?=@$row['gpanding_amount'];?></td>

                                                    </tr>
                                                    <?php 
                                                                        }
                                                                    }
                                                                    if(!empty($purchase_invoice))
                                                                    {
                                                                        foreach($purchase_invoice as $row)
                                                                        {
                                                                            // $debit_total= @$value['payment_total'] + @$value['cashdebit_total'] + @$value['bankdebit_total'] + @$value['journaldebit_total'];
                                                                                
                                                                            // $credit_total= @$value['receipt_total'] + @$value['cashcredit_total'] + @$value['bankcredit_total'] + @$value['journalcredit_total'];
                                                                            // $diffrence = $credit_total - $debit_total; 
                                                            
                                                                    ?>
                                                    <tr>
                                                        <td><?=@$row['pinv_id'];?></td>
                                                        <td><?=@$row['pinv_date'];?></td>
                                                        <td><?=@$row['paccount_name'];?></td>

                                                        <td><?=@$row['pamount'];?></td>
                                                        <td><?=@$row['pnet_amount'];?></td>
                                                        <td><?=@$row['ppanding_amount'];?></td>

                                                    </tr>
                                                    <?php 
                                                                        }
                                                                    }

                                                                    if(!empty($purchase_general))
                                                                    {
                                                                        foreach($purchase_general as $row)
                                                                        {
                                                                            // $debit_total= @$value['payment_total'] + @$value['cashdebit_total'] + @$value['bankdebit_total'] + @$value['journaldebit_total'];
                                                                                
                                                                            // $credit_total= @$value['receipt_total'] + @$value['cashcredit_total'] + @$value['bankcredit_total'] + @$value['journalcredit_total'];
                                                                            // $diffrence = $credit_total - $debit_total; 
                                                            
                                                                    ?>
                                                    <tr>
                                                        <td><?=@$row['pginv_id'];?></td>
                                                        <td><?=@$row['pginv_date'];?></td>
                                                        <td><?=@$row['pgaccount_name'];?></td>

                                                        <td><?=@$row['pgamount'];?></td>
                                                        <td><?=@$row['pgnet_amount'];?></td>
                                                        <td><?=@$row['pgpanding_amount'];?></td>

                                                    </tr>
                                                    <?php 
                                                                        }
                                                                    }
                                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
    $('.ac_select2').select2({
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