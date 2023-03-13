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
            <form method="get" action="<?=url('Trading/purchase_jv_monthly_AcWise')?>">
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
                                        <input type="hidden" name = "type" value="<?=$type?>">
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
                                        <td><a href="<?=url('Trading/purchase_jv_voucher_wise?month=4&year='.@$jv[4]['year'].'&id='.$ac_id.'&type='.$type)?>">April</a></td>
                                        <td><?=isset($jv[4]['dr']) ? @$jv[4]['dr'] :0;?></td>
                                        <td><?=isset($jv[4]['cr']) ? '-'.@$jv[4]['cr'] :0;?></td>
                                        <td><?=isset($jv[4]['total']) ? $closing +=$jv[4]['total'] :@$closing;?></td>
                                   
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_jv_voucher_wise?month=5&year='.@$jv[5]['year'].'&id='.$ac_id.'&type='.$type)?>">May</a></td>
                                        <td><?=isset($jv[5]['dr']) ? @$jv[5]['dr'] :0;?></td>
                                        <td><?=isset($jv[5]['cr']) ? '-'.@$jv[5]['cr'] :0;?></td>
                                        <td><?=isset($jv[5]['total']) ? $closing +=$jv[5]['total'] :@$closing;?></td>
                                   
                                    </tr>
                                    
                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_jv_voucher_wise?month=6&year='.@$jv[6]['year'].'&id='.$ac_id.'&type='.$type)?>">June</a></td>
                                        <td><?=isset($jv[6]['dr']) ? @$jv[6]['dr'] :0;?></td>
                                        <td><?=isset($jv[6]['cr']) ? '-'.@$jv[6]['cr'] :0;?></td>
                                        <td><?=isset($jv[6]['total']) ? $closing +=$jv[6]['total'] :@$closing;?></td>
                                   
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/jv_voucher_wise?month=7&year='.@$jv[7]['year'].'&id='.$ac_id.'&type='.$type)?>">July</a></td>
                                        <td><?=isset($jv[7]['dr']) ? @$jv[7]['dr'] :0;?></td>
                                        <td><?=isset($jv[7]['cr']) ? '-'.@$jv[7]['cr'] :0;?></td>
                                        <td><?=isset($jv[7]['total']) ? $closing +=$jv[7]['total'] :@$closing;?></td>
                                   
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_jv_voucher_wise?month=8&year='.@$jv[8]['year'].'&id='.$ac_id.'&type='.$type)?>">August</a></td>
                                        <td><?=isset($jv[8]['dr']) ? @$jv[8]['dr'] :0;?></td>
                                        <td><?=isset($jv[8]['cr']) ? '-'.@$jv[8]['cr'] :0;?></td>
                                        <td><?=isset($jv[8]['total']) ? $closing +=$jv[8]['total'] :@$closing;?></td>
                                   
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_jv_voucher_wise?month=9&year='.@$jv[9]['year'].'&id='.$ac_id.'&type='.$type)?>">September</a></td>
                                        <td><?=isset($jv[9]['dr']) ? @$jv[9]['dr'] :0;?></td>
                                        <td><?=isset($jv[9]['cr']) ? '-'.@$jv[9]['cr'] :0;?></td>
                                        <td><?=isset($jv[9]['total']) ? $closing +=$jv[9]['total'] :@$closing;?></td>
                                   
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_jv_voucher_wise?month=10&year='.@$jv[10]['year'].'&id='.$ac_id.'&type='.$type)?>">October</a></td>
                                        <td><?=isset($jv[10]['dr']) ? @$jv[10]['dr'] :0;?></td>
                                        <td><?=isset($jv[10]['cr']) ? '-'.@$jv[10]['cr'] :0;?></td>
                                        <td><?=isset($jv[10]['total']) ? $closing +=$jv[10]['total'] :@$closing;?></td>
                                   
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_jv_voucher_wise?month=11&year='.@$jv[11]['year'].'&id='.$ac_id.'&type='.$type)?>">November</a></td>
                                        <td><?=isset($jv[11]['dr']) ? @$jv[11]['dr'] :0;?></td>
                                        <td><?=isset($jv[11]['cr']) ? '-'.@$jv[11]['cr'] :0;?></td>
                                        <td><?=isset($jv[11]['total']) ? $closing +=$jv[11]['total'] :@$closing;?></td>
                                   
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_jv_voucher_wise?month=12&year='.@$jv[12]['year'].'&id='.$ac_id.'&type='.$type)?>">December</a></td>
                                        <td><?=isset($jv[12]['dr']) ? @$jv[12]['dr'] :0;?></td>
                                        <td><?=isset($jv[12]['cr']) ? '-'.@$jv[12]['cr'] :0;?></td>
                                        <td><?=isset($jv[12]['total']) ? $closing +=$jv[12]['total'] :@$closing;?></td>
                                   
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_jv_voucher_wise?month=1&year='.@$jv[1]['year'].'&id='.$ac_id.'&type='.$type)?>">January</a></td>
                                        <td><?=isset($jv[1]['dr']) ? @$jv[1]['dr'] :0;?></td>
                                        <td><?=isset($jv[1]['cr']) ? '-'.@$jv[1]['cr'] :0;?></td>
                                        <td><?=isset($jv[1]['total']) ? $closing +=$jv[1]['total'] :@$closing;?></td>
                                   
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_jv_voucher_wise?month=2&year='.@$jv[2]['year'].'&id='.$ac_id.'&type='.$type)?>">February</a></td>
                                        <td><?=isset($jv[2]['dr']) ? @$jv[2]['dr'] :0;?></td>
                                        <td><?=isset($jv[2]['cr']) ? '-'.@$jv[2]['cr'] :0;?></td>
                                        <td><?=isset($jv[2]['total']) ? $closing +=$jv[2]['total'] :@$closing;?></td>
                                   
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Trading/purchase_jv_voucher_wise?month=3&year='.@$jv[3]['year'].'&id='.$ac_id.'&type='.$type)?>">March</a></td>
                                        <td><?=isset($jv[3]['dr']) ? @$jv[3]['dr'] :0;?></td>
                                        <td><?=isset($jv[3]['cr']) ? '-'.@$jv[3]['cr'] :0;?></td>
                                        <td><?=isset($jv[3]['total']) ? $closing +=$jv[3]['total'] :@$closing;?></td>
                                   
                                    </tr>
                                </tbody>
                                <?php 
                                $total = 0;
                                $credit = 0;
                                $debit = 0;
                                foreach($jv as $row){
                                    $total += @$row['total'];
                                    $debit += @$row['dr'];
                                    $credit -= @$row['cr'];
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