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
            <form method="get" action="<?=url('Balancesheet/currentassets_contra_monthly_PerWise')?>">
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
                                        <input type="hidden" name = "id" value="<?=$ac_id?>">
                                      
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
                                <span style="size:20px;"><b>Contra Transaction Voucher</b></span>
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
                                        <!-- <th><h5>DEBIT</h5></th>
                                        <th><h5>CREDIT</h5></th> -->
                                        <th><h5>Total Taxable</h5></th>
                                    </tr>
                                </thead>

                                <tbody>
                               
                                    <tr>
                                        <td><a href="<?=url('Balancesheet/currentassets_contra_voucher_Perwise?month=4&year='.@$per_contra[4]['year'].'&id='.$ac_id)?>">April</a></td>
                                        <td><?=isset($per_contra[4]['total']) ? $per_contra[4]['total'] :0;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/currentassets_contra_voucher_Perwise?month=5&year='.@$per_contra[5]['year'].'&id='.$ac_id)?>">May</a></td>
                                        <td><?=isset($per_contra[5]['total']) ? $per_contra[5]['total'] :0;?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td><a href="<?=url('Balancesheet/currentassets_contra_voucher_Perwise?month=6&year='.@$per_contra[6]['year'].'&id='.$ac_id)?>">June</a></td>
                                        <td><?=isset($per_contra[6]['total']) ? $per_contra[6]['total'] :0;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/currentassets_contra_voucher_Perwise?month=7&year='.@$per_contra[7]['year'].'&id='.$ac_id)?>">July</a></td>
                                        <td><?=isset($per_contra[7]['total']) ? $per_contra[7]['total'] :0;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/currentassets_contra_voucher_Perwise?month=8&year='.@$per_contra[8]['year'].'&id='.$ac_id)?>">August</a></td>
                                        <td><?=isset($per_contra[8]['total']) ? $per_contra[8]['total'] :0;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/currentassets_contra_voucher_Perwise?month=9&year='.@$per_contra[9]['year'].'&id='.$ac_id)?>">September</a></td>
                                        <td><?=isset($per_contra[9]['total']) ? $per_contra[9]['total'] :0;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/currentassets_contra_voucher_Perwise?month=10&year='.@$per_contra[10]['year'].'&id='.$ac_id)?>">October</a></td>
                                        <td><?=isset($per_contra[10]['total']) ? $per_contra[10]['total'] :0;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/currentassets_contra_voucher_Perwise?month=11&year='.@$per_contra[11]['year'].'&id='.$ac_id)?>">November</a></td>
                                        <td><?=isset($per_contra[11]['total']) ? $per_contra[11]['total'] :0;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/currentassets_contra_voucher_Perwise?month=12&year='.@$per_contra[12]['year'].'&id='.$ac_id)?>">December</a></td>
                                        <td><?=isset($per_contra[12]['total']) ? $per_contra[12]['total'] :0;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/currentassets_contra_voucher_Perwise?month=1&year='.@$per_contra[1]['year'].'&id='.$ac_id)?>">January</a></td>
                                        <td><?=isset($per_contra[1]['total']) ? $per_contra[1]['total'] :0;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/currentassets_contra_voucher_Perwise?month=2&year='.@$per_contra[2]['year'].'&id='.$ac_id)?>">February</a></td>
                                        <td><?=isset($per_contra[2]['total']) ? $per_contra[2]['total'] :0;?></td>
                                    </tr>

                                    <tr>
                                        <td><a href="<?=url('Balancesheet/currentassets_contra_voucher_Perwise?month=3&year='.@$per_contra[3]['year'].'&id='.$ac_id)?>">March</a></td>
                                        <td><?=isset($per_contra[3]['total']) ? $per_contra[3]['total'] :0;?></td>
                                    </tr>
                                </tbody>
                                <?php 
                                 
                                 $total = 0;
                                
                                 foreach($per_contra as $row){
                                     $total += @$row['total'];
                                    
                                 } ?>
                                 <tfooter>
                                     <tr>
                                         <th><h4>Total</h4></th>
                                        
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