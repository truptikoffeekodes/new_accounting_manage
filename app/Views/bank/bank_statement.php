<?= $this->extend(THEME . 'templete') ?>
<?= $this->section('content') ?>

<!-- Page Header -->

<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> <?=$title?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
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

<!--Start Navbar -->

<div class="responsive-background">
    <div class="collapse navbar-collapse show" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="post" id="date_submit" class="">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">From :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control dateMask" id="dateMask" name="from"
                                            placeholder="DD-MM-YYYY" type="text">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">To :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control dateMask" id="dateMask" name="to"
                                            placeholder="DD-MM-YYYY" type="text">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">Select Bank:</label>
                                    <div class="input-group">
                                        <select class="form-control" id="account" name='account' required>
                                        </select>
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
        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-fw-widget">
                        <tbody>
                            <tr>
                                <td>
                                    <span style="size:20px;"><b><?=@$account_name?></b></span>
                                    <br>
                                    <b><?=@$from ? user_date(@$from) : '' ?></b> to
                                    <b><?=@$to ? user_date(@$to) : '' ?></b>
                                </td>
                            </tr>
                            <tr colspan="4">
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    

                    <table class="table table mg-b-0">
                        <?php 
                        $balance = (float)@$total['bankdebit_total'] - (float)$total['bankcredit_total'] + $opening_bal;
                        // print_r($balance);exit;
                        ?>
                        <tr>
                            <th>Balance As per Book</th>
                            <th colspan="3"></th>
                            <th><b><?=((float)@$total['bankdebit_total'] - (float)$total['bankcredit_total'] + $opening_bal) < 0 ? ((float)@$total['bankdebit_total'] - (float)$total['bankcredit_total'] + $opening_bal) * -1  .' CR' : (float)@$total['bankdebit_total'] - (float)$total['bankcredit_total'] + $opening_bal .' DB'?></b></th>

                        </tr>
                        <tr>
                            <th>Add : Cheques issued but not presented</th>
                            <th colspan="3"></th>
                            <th>0</th>

                        </tr>
                        <?php 
                                
                                if(!empty($bank)){ ?>
                        <tbody>
                            <?php
                                foreach($bank as $row) {
                                    if($row['mode'] == 'Payment'){        
                                ?>
                            <tr>
                                <td><?=@user_date($row['date']);?></td>
                                <td><?=@$row['mode'];?></td>
                                <td><?=@$row['account_name'];?></td>
                                <td><?=@$row['check_no'];?></td>
                                <td><?=@$row['amount'];?></td>
                                <td></td>
                            </tr>
                                <?php }  }
                            }?>
                            <tr>
                                <td colspan="4"></td>
                                <td><b><?=@$total['bankcredit_total'] ?><b></td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                               
                                <td><b><?=($balance + $total['bankcredit_total']) < 0 ? ($balance + $total['bankcredit_total']) * -1  : (@$balance + @$total['bankcredit_total']) ?><b></td>
                            </tr>
                        </tbody>
                       
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table mg-b-0">
                        <tr>
                            <th>Less : Cheques Deposite but not Cleared</th>
                            <th coslpan="4"></th>
                            <th>0</th>
                        </tr>
                        <?php 
                                
                                if(!empty($bank)){ ?>
                        <tbody>
                            <?php
                                foreach($bank as $row1) {
                                    if($row1['mode'] == 'Receipt'){              
                            ?>
                            <tr>
                                <td><?=@user_date($row1['date']);?></td>
                                <td><?=@$row1['mode'];?></td>

                                <td><?=@$row1['account_name'];?></td>
                                <td><?=@$row1['check_no'];?></td>

                                <td><?=@$row1['amount'];?></td>
                                <td></td>
                            </tr>
                            <?php }  } }?>

                            <tr>
                                <td colspan="4"></td>
                                <td><b><?=@$total['bankdebit_total'] ?><b></td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                
                                <td><b><?=(@$balance - @$total['bankdebit_total'] + @$total['bankcredit_total']) > 0 ? (@$balance - @$total['bankdebit_total'] + @$total['bankcredit_total']) . ' DB' : (@$balance - @$total['bankdebit_total'] + @$total['bankcredit_total']) * -1 . ' CR' ?><b></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection() ?>