<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5">Reporting</h2>
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
            <form method="post" action="<?=url('Trading/balacesheet')?>">
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
                                        <input class="form-control dateMask" id="dateMask" name="from"
                                            placeholder="DD-MM-YYYY" type="text">
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
                                        <input class="form-control dateMask" id="dateMask" name="to"
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
<!--End Navbar -->
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="card custom-card">
                    <div class="card-header card-header-divider">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-fw-widget">
                                    <tr>
                                        <td>
                                            <span style="size:20px;"><b>Balance Sheet</b></span>
                                            </br>
                                            <?php
                                                $from =date_create($bl_sheet['from']) ;                                         
                                                $to = date_create($bl_sheet['to']);                              
                                                 
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
                                <div class="col-md-5">
                                    <div class="table-responsive">
                                        <?php     
                                            if(session('is_stock') == 1 ){
                                                // $closing_stock = @$trading['opening_bal'] + ($trading['pur_total_rate'] -$trading['Purret_total_rate']) - ($trading['sale_total_rate'] - $trading['Saleret_total_rate']);
                                                $closing_stock = @$trading['closing'] ? @$trading['closing'] : @$trading['opening_bal'];
                                            }else{
                                                $closing_stock  = $trading['closing_bal'];
                                            }
                                        ?>
                                        <table class="table">
                                            <tr>
                                                <td>
                                                    <span style="size:20px;"><b>Liabilities </b></span>
                                                </td>
                                                <td colspan="2">
                                                    <span style="size:20px;"><b><?=session('name')?></b></span>
                                                    </br>
                                                    as at <?=date_format($to,"d/m/Y"); ?>
                                                </td>
                                            </tr>
                                            <?php if(!empty($bl_sheet['Capital'])) {?>
                                            <tr>
                                                <td><b>Capital</b> </td>
                                                <td></td>
                                                <td><b><?=@$bl_sheet['Capital']['total']?></b>
                                                </td>
                                            </tr>

                                            <?php foreach($bl_sheet['Capital']['data'] as $key => $row){ ?>
                                            <tr>
                                                <td><?=$key?></td>
                                                <td><?=$row['total']?></td>
                                                <td></td>
                                            </tr>
                                            <?php } } ?>

                                            <?php if(!empty($bl_sheet['Loans'])) {
                                                // echo '<pre>';print_r($bl_sheet);exit;
                                                ?>
                                            <tr>
                                                <td><b>Loan ( Liabilities )</b> </td>
                                                <td></td>
                                                <td><b><?=$bl_sheet['Loans']['total']?></b>
                                                </td>
                                            </tr>

                                            <?php foreach($bl_sheet['Loans']['data'] as $key => $value) { ?>
                                            <tr>
                                                <td><?=$key ?> </td>
                                                <td><?=$value['total'] ?></td>
                                                <td>
                                                </td>
                                            </tr>
                                            <?php } } ?>

                                            <?php if(!empty($bl_sheet['Current Liabilities'])) {
                                                // echo '<pre>';print_r($bl_sheet);exit;
                                                ?>
                                            <tr>
                                                <td><b>Current Liabilities</b> </td>
                                                <td></td>
                                                <td><b><?=$bl_sheet['Current Liabilities']['total']?></b>
                                                </td>
                                            </tr>

                                            <?php foreach($bl_sheet['Current Liabilities']['data'] as $key => $value) { ?>
                                            <tr>
                                                <td><?=$key ?> </td>
                                                <td><?=$value['total'] ?></td>
                                                <td>
                                                </td>
                                            </tr>
                                            <?php } } ?>


                                            <?php if(isset($net_profit) && !empty($net_profit) ) { ?>
                                            <tr>
                                                <td><b>Net Profit </b></td>
                                                <td></td>
                                                <td><b><?=$net_profit ?></b>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>

                                <div style="width: 3%; float: left;">&nbsp;</div>
                                <div style="width: 3%;border-right: 1px solid #1c1c38;height: 394px;">&nbsp;</div>
                                <div style="width: 3%; float: left;">&nbsp;</div>

                                <div class="col-md-5">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <td>
                                                    <span style="size:20px;"><b>ASSETS </b></span>
                                                </td>
                                                <td colspan="2">
                                                    <span style="size:20px;"><b><?=session('name')?></b></span>
                                                    </br>
                                                    as at <?=date_format($to,"d/m/Y"); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                            </tr>
                                            <tr>
                                            </tr>
                                            <?php if(!empty($bl_sheet['Fixed Assets']['total'])) {?>
                                            <tr>
                                                <td><b>Fiexed Assets</b> </td>
                                                <td></td>
                                                <td><b><?=$bl_sheet['Fixed Assets']['total']?></b>
                                                </td>
                                            </tr>

                                            <?php foreach($bl_sheet['Fixed Assets']['data'] as $key => $row){ ?>
                                            <tr>
                                                <td><?=$key?></td>
                                                <td><?=$row['total']?></td>
                                                <td></td>
                                            </tr>
                                            <?php } } ?>


                                            <?php if(!empty($bl_sheet['Current Assets']['total'])) {?>
                                            <tr>
                                                <td><b>Current Assets</b> </td>
                                                <td></td>
                                                <td><b><?=$bl_sheet['Current Assets']['total'] + $closing_stock?></b>
                                                </td>
                                            </tr>
                                            
                                            <?php foreach($bl_sheet['Current Assets']['data'] as $key => $value){ ?>

                                            <tr>
                                                <td><?=$key?></td>
                                                <td><?=$value['total']?></td>
                                                <td></td>
                                            </tr>
                                            <?php } } ?>
                                            <tr>
                                                <td>Colsing Stock </td>
                                                <td><?=$closing_stock?></td>
                                                <td></td>
                                            </tr>


                                            <!-- <?php foreach($pl['income_ac'] as $key => $value){ ?>
                                            <tr>
                                                <td><?=$key?></td>
                                                <td><?=$value['total']?></td>
                                                <td> </td>
                                            </tr>
                                            <?php } ?>
                                            <?php if(isset($net_loss) && !empty($net_loss) ) { ?>
                                            <tr>
                                                <td><b>Net Loss </b></td>
                                                <td></td>
                                                <td><b><?=$net_loss ?></b>
                                                </td>
                                            </tr>
                                          <?php } ?> -->
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <td><b>Total</b></td>
                                                <td></td>
                                                <!-- <td><b></b> -->
                                                </td>
                                                <td></td>
                                                <td><b>Total</b></td>
                                                <td></td>
                                                <!-- <td><b></b> -->
                                                </td>
                                            </tr>
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