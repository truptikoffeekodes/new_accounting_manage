<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Trading</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
            </ol>
        </div>
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
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="get" action="<?=url('Addbook/gnrl_purchase_voucher_wise')?>">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <!-- <label class="">From :</label> -->
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
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <!-- <label class="">To :</label> -->
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                TO:
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" id="dateMask" name="to"
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
        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-fw-widget">
                        <tr>
                            <td>
                                <span style="size:20px;"><b>General Purchase Voucher</b></span>
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
                
                <div class="table-responsive">
                <table class="table table-striped table-hover table-fw-widget" id="table_list_data" data-id=""
                                 data-module="" data-filter_data=''>
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
                            foreach($purchase as $row){ ?> 
                            <tr>
                                <td><?=user_date($row['date'])?></td>
                                <td><a href ="<?=url('purchase/add_general_pur/general/'.$row['id'])?>"><?=$row['party_name']?></a></td>
                                <td><?=@$row['voucher_name']?></td>
                                <td><?=$row['id']?></td>
                                <td><?=number_format($row['taxable'],2)?></td>
                                <td></td>
                                <td><?= number_format($closing +=$row['taxable'],2)?></td>
                                
                            </tr>
                            <?php 
                            $debit += $row['taxable'];
                            } ?>
                            
                        </tbody>

                        <tfooter>
                            <tr>
                                <th colspan="4">Closing</th>
                                <th><?=number_format($debit,2)?></th>
                                <th></th>
                                
                                <th colspan="3"><?=number_format($closing,2)?></th>
                                
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
    $('#table_list_data').DataTable();
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $('.dateMask').mask('99-99-9999');

});

</script>
<?= $this->endSection() ?>