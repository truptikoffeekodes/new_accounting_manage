<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><?=$type=='pl' ? 'Profit/Loss' : 'Trading' ?></a></li>
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
            <form method="get" action="<?=url('Trading/purchase_bank_cash_monthly_AcWise')?>">
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
                                        <input class="form-control fc-datepicker" name="from" placeholder="DD-MM-YYYY" type="text">
                                  
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
                                        <input class="form-control fc-datepicker" name="to" placeholder="DD-MM-YYYY" type="text">
                                  
                                        <input type="hidden" name = "id" value="<?=$ac_id?>">
                                        <input type="hidden" name="type" value="<?=@$type?>">
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
                                        <th><h5>DEBIT</h5></th>
                                        <th><h5>CREDIT</h5></th>
                                        <th><h5>Total Taxable</h5></th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php $closing = 0;?>
                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_bankcash_voucher_wise?month=4&year='.@$bankcash[4]['year'].'&id='.$ac_id.'&type='.$type)?>">April</a></td>
                                        <td><?=isset($bankcash[4]['Receipt']) ? '-'.@$bankcash[4]['Receipt'] :0;?></td>
                                        <td><?=isset($bankcash[4]['Payment']) ? @$bankcash[4]['Payment'] :0;?></td>
                                        <td><?=isset($bankcash[4]['total']) ? $closing +=$bankcash[4]['total'] :@$closing;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_bankcash_voucher_wise?month=5&year='.@$bankcash[5]['year'].'&id='.$ac_id.'&type='.$type)?>">May</a></td>
                                        <td><?=isset($bankcash[5]['Receipt']) ? '-'.@$bankcash[5]['Receipt'] :0;?></td>
                                        <td><?=isset($bankcash[5]['Payment']) ? @$bankcash[5]['Payment'] :0;?></td>
                                        <td><?=isset($bankcash[5]['total']) ? $closing +=$bankcash[5]['total'] :@$closing;?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_bankcash_voucher_wise?month=6&year='.@$bankcash[6]['year'].'&id='.$ac_id.'&type='.$type)?>">June</a></td>
                                        <td><?=isset($bankcash[6]['Receipt']) ? '-'.@$bankcash[6]['Receipt'] :0;?></td>
                                        <td><?=isset($bankcash[6]['Payment']) ? @$bankcash[6]['Payment'] :0;?></td>
                                        <td><?=isset($bankcash[6]['total']) ? $closing +=$bankcash[6]['total'] :@$closing;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_bankcash_voucher_wise?month=7&year='.@$bankcash[7]['year'].'&id='.$ac_id.'&type='.$type)?>">July</a></td>
                                        <td><?=isset($bankcash[7]['Receipt']) ? '-'.@$bankcash[7]['Receipt'] :0;?></td>
                                        <td><?=isset($bankcash[7]['Payment']) ? @$bankcash[7]['Payment'] :0;?></td>
                                        <td><?=isset($bankcash[7]['total']) ? $closing +=$bankcash[7]['total'] :@$closing;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_bankcash_voucher_wise?month=8&year='.@$bankcash[8]['year'].'&id='.$ac_id.'&type='.$type)?>">August</a></td>
                                        <td><?=isset($bankcash[8]['Receipt']) ? '-'.@$bankcash[8]['Receipt'] :0;?></td>
                                        <td><?=isset($bankcash[8]['Payment']) ? @$bankcash[8]['Payment'] :0;?></td>
                                        <td><?=isset($bankcash[8]['total']) ? $closing +=$bankcash[8]['total'] :@$closing;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_bankcash_voucher_wise?month=9&year='.@$bankcash[9]['year'].'&id='.$ac_id.'&type='.$type)?>">September</a></td>
                                        <td><?=isset($bankcash[9]['Receipt']) ? '-'.@$bankcash[9]['Receipt'] :0;?></td>
                                        <td><?=isset($bankcash[9]['Payment']) ? @$bankcash[9]['Payment'] :0;?></td>
                                        <td><?=isset($bankcash[9]['total']) ? $closing +=$bankcash[9]['total'] :@$closing;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_bankcash_voucher_wise?month=10&year='.@$bankcash[10]['year'].'&id='.$ac_id.'&type='.$type)?>">October</a></td>
                                        <td><?=isset($bankcash[10]['Receipt']) ? '-'.@$bankcash[10]['Receipt'] :0;?></td>
                                        <td><?=isset($bankcash[10]['Payment']) ? @$bankcash[10]['Payment'] :0;?></td>
                                        <td><?=isset($bankcash[10]['total']) ? $closing +=$bankcash[10]['total'] :@$closing;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_bankcash_voucher_wise?month=11&year='.@$bankcash[11]['year'].'&id='.$ac_id.'&type='.$type)?>">November</a></td>
                                        <td><?=isset($bankcash[11]['Receipt']) ? '-'.@$bankcash[11]['Receipt'] :0;?></td>
                                        <td><?=isset($bankcash[11]['Payment']) ? @$bankcash[11]['Payment'] :0;?></td>
                                        <td><?=isset($bankcash[11]['total']) ? $closing +=$bankcash[11]['total'] :@$closing;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_bankcash_voucher_wise?month=12&year='.@$bankcash[12]['year'].'&id='.$ac_id.'&type='.$type)?>">December</a></td>
                                        <td><?=isset($bankcash[12]['Receipt']) ? '-'.@$bankcash[12]['Receipt'] :0;?></td>
                                        <td><?=isset($bankcash[12]['Payment']) ? @$bankcash[12]['Payment'] :0;?></td>
                                        <td><?=isset($bankcash[12]['total']) ? $closing +=$bankcash[12]['total'] :@$closing;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_bankcash_voucher_wise?month=1&year='.@$bankcash[1]['year'].'&id='.$ac_id.'&type='.$type)?>">January</a></td>
                                        <td><?=isset($bankcash[1]['Receipt']) ? '-'.@$bankcash[1]['Receipt'] :0;?></td>
                                        <td><?=isset($bankcash[1]['Payment']) ? @$bankcash[1]['Payment'] :0;?></td>
                                        <td><?=isset($bankcash[1]['total']) ? $closing +=$bankcash[1]['total'] :@$closing;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_bankcash_voucher_wise?month=2&year='.@$bankcash[2]['year'].'&id='.$ac_id.'&type='.$type)?>">February</a></td>
                                        <td><?=isset($bankcash[2]['Receipt']) ? '-'.@$bankcash[2]['Receipt'] :0;?></td>
                                        <td><?=isset($bankcash[2]['Payment']) ? @$bankcash[2]['Payment'] :0;?></td>
                                        <td><?=isset($bankcash[2]['total']) ? $closing +=$bankcash[2]['total'] :@$closing;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_bankcash_voucher_wise?month=3&year='.@$bankcash[3]['year'].'&id='.$ac_id.'&type='.$type)?>">March</a></td>
                                        <td><?=isset($bankcash[3]['Receipt']) ? '-'.@$bankcash[3]['Receipt'] :0;?></td>
                                        <td><?=isset($bankcash[3]['Payment']) ? @$bankcash[3]['Payment'] :0;?></td>
                                        <td><?=isset($bankcash[3]['total']) ? $closing +=$bankcash[3]['total'] :@$closing;?></td>
                                    </tr>
                                </tbody>
                                <?php 
                                 
                                 $total = 0;
                                 $credit = 0;
                                 $debit = 0;
                                 foreach($bankcash as $row){
                                     $total += @$row['total'];
                                     $debit -= @$row['Receipt'];
                                     $credit += @$row['Payment'];
                                 } ?>
                                 <tfooter>
                                     <tr>
                                         <th><h4>Total</h4></th>
                                         <th><h4><?=$debit?></h4></th>
                                         <th><h4><?=$credit?></h4></th>
                                         <th><h4><?= $total?></h4></th>
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