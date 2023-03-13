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
            <a href="<?=url('Addbook/ledgeroutstanding_report_xls_export?'.'type='.$type.'&start_date='.$from.'&end_date='.$to)?>"  class="btn ripple btn-primary"><i class="fe fe-external-link"></i> Excel Export</a>
       
        </div>


    </div>
    <div class="responsive-background">
        <div class="collapse navbar-collapse show" id="navbarSupportedContent">
            <div class="advanced-search">
                <form method="post" action="<?=url('Addbook/Ledger_outstanding_report')?>">
                    <div class="row align-items-center">
                        <!-- <div class="col-md-6"> -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-lg-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    FROM:
                                                </div>
                                            </div>
                                            <input class="form-control dateMask" id="dateMask" name="start_date"
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
                                            <input class="form-control dateMask" id="" name="end_date"
                                                placeholder="DD-MM-YYYY" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- </div> -->

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
                                            <table class="table mg-b-0">
                                                <thead>
                                                    <tr>

                                                        <th>Account Id</th>
                                                        <th>Account_name</th>
                                                        <th>Receivable Amount</th>
                                                        <th>Paybale Amount</th>
                                                        <th>Outstandig Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                                    if(!empty($account))
                                                                    {
                                                                        foreach($account as $row)
                                                                        {
                                                                        
                                                                    ?>
                                                    <tr>

                                                        <td><?=@$row['id'];?></td>
                                                        <td><a href="<?=url('Addbook/Ledger_outstanding?account_id='.$row['id'].'&from='.$from.'&to='.$to);?>"><?=@$row['name'];?></a></td>
                                                        <td><?=@$row['receivable_amount'];?></td>
                                                        <td><?=@$row['payble_amount'];?></td>
                                                        <td><?=@$row['outstanding'];?></td>

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