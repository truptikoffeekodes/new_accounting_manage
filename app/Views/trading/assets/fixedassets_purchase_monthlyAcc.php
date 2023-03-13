<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Balancesheet</a></li>
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
            <form method="get" action="<?=url('Balancesheet/fixedassets_purchaseinvoice_monthly_AcWise')?>">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                FROM:
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" id="" name="from" required
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
                                        <input class="form-control fc-datepicker" id="" name="to" required
                                            placeholder="YYYY-MM-DD" type="text">
                                            <input type="hidden" name="id" value="<?=@$ac_id?>">
                                           
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
                                <span style="size:20px;"><b>Expence Voucher</b></span>
                                </br>
                                <?php
                                    $from =date_create($from) ;                                         
                                    $to = date_create($to);
                                ?>
                                <b><?=date_format($from,"d/m/Y"); ?></b> to
                                <b><?=date_format($to,"d/m/Y"); ?></b>

                            </td>
                        </tr>
                        <tr colspan="4">
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="table-responsive">
                            <table class="table main-table-reference mt-0 mb-0 text-center">
                                <thead>
                                    <tr>
                                        <th><h5>Month</h5></th>
                                        <th><h5>Debit</h5></th>
                                        <th><h5>Credit</h5></th>
                                        <th><h5>Total Taxable</h5></th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php $closing = 0;?>
                                    <tr>
                                        <td><a href="<?=url('Balancesheet/fixedassets_Purchase_voucher_wise?month=4&year='.@$generalPurchase[4]['year'].'&id='.$ac_id)?>">April</a></td>
                                        <td><?=isset($generalPurchase[4]['general']) ? @$generalPurchase[4]['general'] :0;?></td>
                                        <td><?=isset($generalPurchase[4]['return']) ? '-'.@$generalPurchase[4]['return'] :0;?></td>
                                        <td><?=isset($generalPurchase[4]['total']) ? $closing +=$generalPurchase[4]['total'] :@$closing;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/fixedassets_Purchase_voucher_wise?month=5&year='.@$generalPurchase[5]['year'].'&id='.$ac_id)?>">May</a></td>
                                        <td><?=isset($generalPurchase[5]['general']) ? @$generalPurchase[5]['general'] :0;?></td>
                                        <td><?=isset($generalPurchase[5]['return']) ? '-'.@$generalPurchase[5]['return'] :0;?></td>
                                        <td><?=isset($generalPurchase[5]['total']) ? $closing +=$generalPurchase[5]['total'] :@$closing;?></td>
      
                                    </tr>
                                    
                                    <tr>
                                        <td><a href="<?=url('Balancesheet/fixedassets_Purchase_voucher_wise?month=6&year='.@$generalPurchase[6]['year'].'&id='.$ac_id)?>">June</a></td>
                                        <td><?=isset($generalPurchase[6]['general']) ? @$generalPurchase[6]['general'] :0;?></td>
                                        <td><?=isset($generalPurchase[6]['return']) ? '-'.@$generalPurchase[6]['return'] :0;?></td>
                                        <td><?=isset($generalPurchase[6]['total']) ? $closing +=$generalPurchase[6]['total'] :@$closing;?></td>
      
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/fixedassets_Purchase_voucher_wise?month=7&year='.@$generalPurchase[7]['year'].'&id='.$ac_id)?>">July</a></td>
                                        <td><?=isset($generalPurchase[7]['general']) ? @$generalPurchase[7]['general'] :0;?></td>
                                        <td><?=isset($generalPurchase[7]['return']) ? '-'.@$generalPurchase[7]['return'] :0;?></td>
                                        <td><?=isset($generalPurchase[7]['total']) ? $closing +=$generalPurchase[7]['total'] :@$closing;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/fixedassets_Purchase_voucher_wise?month=8&year='.@$generalPurchase[8]['year'].'&id='.$ac_id)?>">August</a></td>
                                        <td><?=isset($generalPurchase[8]['general']) ? @$generalPurchase[8]['general'] :0;?></td>
                                        <td><?=isset($generalPurchase[8]['return']) ? '-'.@$generalPurchase[8]['return'] :0;?></td>
                                        <td><?=isset($generalPurchase[8]['total']) ? $closing +=$generalPurchase[8]['total'] :@$closing;?></td>
      
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/fixedassets_Purchase_voucher_wise?month=9&year='.@$generalPurchase[9]['year'].'&id='.$ac_id)?>">September</a></td>
                                        <td><?=isset($generalPurchase[9]['general']) ? @$generalPurchase[9]['general'] :0;?></td>
                                        <td><?=isset($generalPurchase[9]['return']) ? '-'.@$generalPurchase[9]['return'] :0;?></td>
                                        <td><?=isset($generalPurchase[9]['total']) ? $closing +=$generalPurchase[9]['total'] :@$closing;?></td>
      
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/fixedassets_Purchase_voucher_wise?month=10&year='.@$generalPurchase[10]['year'].'&id='.$ac_id)?>">October</a></td>
                                        <td><?=isset($generalPurchase[10]['general']) ? @$generalPurchase[10]['general'] :0;?></td>
                                        <td><?=isset($generalPurchase[10]['return']) ? '-'.@$generalPurchase[10]['return'] :0;?></td>
                                        <td><?=isset($generalPurchase[10]['total']) ? $closing +=$generalPurchase[10]['total'] :@$closing;?></td>
      
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/fixedassets_Purchase_voucher_wise?month=11&year='.@$generalPurchase[11]['year'].'&id='.$ac_id)?>">November</a></td>
                                        <td><?=isset($generalPurchase[11]['general']) ? @$generalPurchase[11]['general'] :0;?></td>
                                        <td><?=isset($generalPurchase[11]['return']) ? '-'.@$generalPurchase[11]['return'] :0;?></td>
                                        <td><?=isset($generalPurchase[11]['total']) ? $closing +=$generalPurchase[11]['total'] :@$closing;?></td>
      
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/fixedassets_Purchase_voucher_wise?month=12&year='.@$generalPurchase[12]['year'].'&id='.$ac_id)?>">December</a></td>
                                        <td><?=isset($generalPurchase[12]['general']) ? @$generalPurchase[12]['general'] :0;?></td>
                                        <td><?=isset($generalPurchase[12]['return']) ? '-'.@$generalPurchase[12]['return'] :0;?></td>
                                        <td><?=isset($generalPurchase[12]['total']) ? $closing +=$generalPurchase[12]['total'] :@$closing;?></td>
      
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/fixedassets_Purchase_voucher_wise?month=1&year='.@$generalPurchase[1]['year'].'&id='.$ac_id)?>">January</a></td>
                                        <td><?=isset($generalPurchase[1]['general']) ? @$generalPurchase[1]['general'] :0;?></td>
                                        <td><?=isset($generalPurchase[1]['return']) ? '-'.@$generalPurchase[1]['return'] :0;?></td>
                                        <td><?=isset($generalPurchase[1]['total']) ? $closing +=$generalPurchase[1]['total'] :@$closing;?></td>
      
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/fixedassets_Purchase_voucher_wise?month=2&year='.@$generalPurchase[2]['year'].'&id='.$ac_id)?>">February</a></td>
                                        <td><?=isset($generalPurchase[2]['general']) ? @$generalPurchase[2]['general'] :0;?></td>
                                        <td><?=isset($generalPurchase[2]['return']) ? '-'.@$generalPurchase[2]['return'] :0;?></td>
                                        <td><?=isset($generalPurchase[2]['total']) ? $closing +=$generalPurchase[2]['total'] :@$closing;?></td>
      
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/fixedassets_Purchase_voucher_wise?month=3&year='.@$generalPurchase[3]['year'].'&id='.$ac_id)?>">March</a></td>
                                        <td><?=isset($generalPurchase[3]['general']) ? @$generalPurchase[3]['general'] :0;?></td>
                                        <td><?=isset($generalPurchase[3]['return']) ? '-'.@$generalPurchase[3]['return'] :0;?></td>
                                        <td><?=isset($generalPurchase[3]['total']) ? $closing +=$generalPurchase[3]['total'] :@$closing;?></td>
      
                                    </tr>
                                </tbody>
                                <?php
                                $total = 0;
                                $credit = 0;
                                $debit = 0;
                                if(isset($generalPurchase)){
                                    foreach($generalPurchase as $row){
                                        $debit += @$row['general'];
                                        $credit -=@$row['return'];
                                        $total += @$row['total'];
                                    } 
                                } ?>
                                <tfooter>
                                    <tr>
                                        <th><h4>Total</h4></th>
                                        <th><h4><?=$debit?></h4></th>
                                        <th><h4><?=$credit?></h4></th>
                                        <th><h4><?=$total?></h4></th>
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
<!--End Navbar -->




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

});
</script>
<?= $this->endSection() ?>