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
        <button id="pdf" onclick="javascript:createPdf()" class="btn ripple btn-primary">
            PDF
        </button>

        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
        <a href="<?=url('Trading/Balancesheet_xls?from='.$start_date.'&to='.$end_date)?>"  class="btn ripple btn-primary"><i class="fe fe-external-link"></i>Excel Export</a>


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
                        <div class="card-body" id="pdf_div">
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


                                            <!------------------------ Capital START ----------------------->

                                            <?php 
                                            $total = 0;

                                            $lib_total = @$bl['capital_total'] + @$bl['loan_total'] + @$bl['current_lib_total'];
                                            $assets_total = @$bl['otherassets_total'] + @$bl['currentassets_total'] + @$bl['fixedassets_total'];
                                            
                                            foreach($bl['capital'] as $key => $value) { ?>
                                            <tr>
                                                <td><b><?=@$value['name']?></b></td>
                                                <td></td>
                                                <td><b><?=@$bl['capital_total']?></b><br>
                                                </td>
                                            </tr>

                                            <?php   
                                                    if(!empty($value['account'])) {
                                                        foreach(@$value['account'] as $ac_key => $ac_value){ ?>
                                            <tr>
                                                <td><a
                                                        href="<?=url('Trading/get_capital_account_data?from='.$trading['from'].'&to='.$trading['to'].'&id='.$ac_value['account_id'])?>"><?=$ac_key ?></a>
                                                </td>
                                                <td><?=number_format($ac_value['total'],2) ?>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php 
                                                        }    
                                                    }
                                            ?>

                                            <?php 
                                                    if(!empty($value['sub_categories'])) {
                                                        foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                                                            $total = 0;
                                                            $arr[$sub_key] = $sub_value;
                                                            $total = subGrp_total($arr,0);                
                                            ?>
                                            <tr>
                                                <td><a
                                                        href="<?=url('Trading/get_capital_sub_grp?'.'id='.$sub_key.'&name='.$sub_value['name'].'&from='.$trading['from'].'&to='.$trading['to'])?>"><?=$sub_value['name']?></a>
                                                </td>
                                                <td><?=number_format($total,2) ?>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php 
                                                            unset($arr);
                                                        }
                                                    }
                                                }
                                            ?>

                                            <!------------------------ Capital End ----------------------->


                                            <!------------------------ Loans  START ----------------------->

                                            <?php 
                                            $total = 0;
                                            foreach($bl['loan'] as $key => $value) { ?>
                                            <tr>
                                                <td><b><?=@$value['name']?></b></td>
                                                <td></td>
                                                <td><b><?=number_format(@$bl['loan_total'],2)?></b><br>
                                                </td>
                                            </tr>

                                            <?php   
                                                    if(!empty($value['account'])) {
                                                        foreach(@$value['account'] as $ac_key => $ac_value){ ?>
                                            <tr>
                                                <td><a
                                                        href="<?=url('Trading/get_loan_account_data?from='.$trading['from'].'&to='.$trading['to'].'&id='.$ac_value['account_id'])?>"><?=$ac_key ?>
                                                </td>
                                                <td><?=number_format($ac_value['total'],2) ?>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php 
                                                        }    
                                                    }
                                            ?>

                                            <?php 
                                                    if(!empty($value['sub_categories'])) {
                                                        foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                                                            $total = 0;
                                                            $arr[$sub_key] = $sub_value;
                                                            $total = subGrp_total($arr,0);
                                                            
                                                            ?>
                                            <tr>
                                                <td><a
                                                        href="<?=url('Trading/get_loan_sub_grp?'.'id='.$sub_key.'&name='.$sub_value['name'].'&from='.$trading['from'].'&to='.$trading['to'])?>"><?=$sub_value['name']?></a>
                                                </td>
                                                <td><?=number_format($total,2) ?>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php 
                                                            unset($arr);
                                                        }
                                                    }
                                                }
                                            ?>

                                            <!------------------------ Loans  End ----------------------->


                                            <!------------------------ Current Liabilities Start ----------------------->
                                            <?php 
                                            $total = 0;
                                            foreach($bl['current_lib'] as $key => $value) { ?>
                                            <tr>
                                                <td><b><?=@$value['name']?></b></td>
                                                <td></td>
                                                <td><b><?=number_format(@$bl['current_lib_total'],2)?></b><br>
                                                </td>
                                            </tr>

                                            <?php   
                                                    if(!empty($value['account'])) {
                                                        foreach(@$value['account'] as $ac_key => $ac_value){ ?>
                                            <tr>
                                                <td><a
                                                        href="<?=url('Trading/get_current_lib_account_data?from='.$trading['from'].'&to='.$trading['to'].'&id='.$ac_value['account_id'])?>"><?=$ac_key ?></a>
                                                </td>
                                                <td><?=number_format($ac_value['total'],2) ?>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php 
                                                        }    
                                                    }
                                            ?>

                                            <?php 
                                                    if(!empty($value['sub_categories'])) {
                                                        foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                                                            $total = 0;
                                                            $arr[$sub_key] = $sub_value;
                                                            $total = subGrp_total($arr,0);
                                                            
                                                            ?>

                                            <tr>
                                                <td><a
                                                        href="<?=url('Trading/get_current_lib_sub_grp?'.'id='.$sub_key.'&name='.$sub_value['name'].'&from='.$trading['from'].'&to='.$trading['to'])?>"><?=$sub_value['name']?></a>
                                                </td>

                                                <td><?=number_format($total,2) ?>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php 
                                                            unset($arr);
                                                        }
                                                    }
                                                }
                                            ?>
                                            <!------------------------ Current Liabilities End ----------------------->


                                            <?php if(isset($net_profit) && !empty($net_profit) ) { ?>
                                            <tr>
                                                <td><b>Net Profit </b></td>
                                                <td></td>
                                                <td><b><?=number_format($net_profit,2) ?></b>
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

                                            <!------------------------ Fixed ASSETS START ----------------------->

                                            <?php 
                                            $total = 0;
                                            foreach($bl['fixedassets'] as $key => $value) { ?>
                                            <tr>
                                                <td><b><?=@$value['name']?></b></td>
                                                <td></td>
                                                <td><b><?=number_format(@$bl['fixedassets_total'],2)?></b><br>
                                                </td>
                                            </tr>

                                            <?php   
                                                    if(!empty($value['account'])) {
                                                        foreach(@$value['account'] as $ac_key => $ac_value){ ?>
                                            <tr>
                                                <td><a
                                                        href="<?=url('Trading/get_fixedassets_account_data?from='.$trading['from'].'&to='.$trading['to'].'&id='.$ac_value['account_id'])?>"><?=$ac_key ?></a>
                                                </td>
                                                <td><?=number_format($ac_value['total'],2) ?>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php 
                                                        }    
                                                    }
                                            ?>

                                            <?php 
                                                    if(!empty($value['sub_categories'])) {
                                                        foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                                                            $total = 0;
                                                            $arr[$sub_key] = $sub_value;
                                                            $total = subGrp_total($arr,0);
                                                            
                                                            ?>
                                            <tr>
                                                <td><a
                                                        href="<?=url('Trading/get_fixed_assets_sub_grp?'.'id='.$sub_key.'&name='.$sub_value['name'].'&from='.$trading['from'].'&to='.$trading['to'])?>"><?=$sub_value['name']?></a>
                                                </td>
                                                <td><?=number_format($total,2) ?>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php 
                                                            unset($arr);
                                                        }
                                                    }
                                                }
                                            ?>

                                            <!------------------------ Fixed ASSETS END ----------------------->


                                            <!------------------------ Current ASSETS START ----------------------->

                                            <?php 
                                            
                                            $total = 0;
                                            
                                            foreach($bl['currentassets'] as $key => $value) { ?>
                                            <tr>
                                                <td><b><?=@$value['name']?></b></td>
                                                <td></td>
                                                <td><b><?=number_format(@$bl['currentassets_total'],2)?></b><br>
                                                </td>
                                            </tr>

                                            <?php   
                                                    if(!empty($value['account'])) {
                                                        foreach(@$value['account'] as $ac_key => $ac_value){ ?>
                                            <tr>
                                                <td><a
                                                        href="<?=url('Trading/get_currentassets_account_data?from='.$trading['from'].'&to='.$trading['to'].'&id='.$ac_value['account_id'])?>"><?=$ac_key ?></a>
                                                </td>
                                                <td><?=number_format($ac_value['total'],2) ?>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php 
                                                        }    
                                                    }
                                            ?>

                                            <?php 
                                                    if(!empty($value['sub_categories'])) {
                                                        foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                                                            $total = 0;
                                                            $arr[$sub_key] = $sub_value;
                                                            $total = subGrp_total($arr,0);
                                                            
                                                            ?>
                                            <tr>
                                                <td><a
                                                        href="<?=url('Trading/get_current_assets_sub_grp?'.'id='.$sub_key.'&name='.$sub_value['name'].'&from='.$trading['from'].'&to='.$trading['to'])?>"><?=$sub_value['name']?></a>
                                                </td>
                                                <td><?=number_format($total,2) ?>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php 
                                                            unset($arr);
                                                        }
                                                    }
                                                }
                                            ?>


                                            <?php 
                                            
                                            $total = 0;
                                            
                                            foreach($bl['otherassets'] as $key => $value) { ?>
                                            <tr>
                                                <td><b><?=@$value['name']?></b></td>
                                                <td></td>
                                                <td><b><?=number_format(@$bl['otherassets_total'],2)?></b><br>
                                                </td>
                                            </tr>

                                            <?php   
                                                    if(!empty($value['account'])) {
                                                        foreach(@$value['account'] as $ac_key => $ac_value){ ?>
                                            <tr>
                                                <td><a href=""><?=$ac_key ?></a></td>
                                                <td><?=number_format($ac_value['total'],2) ?>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php 
                                                        }    
                                                    }
                                            ?>

                                            <?php 
                                                    if(!empty($value['sub_categories'])) {
                                                        foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                                                            $total = 0;
                                                            $arr[$sub_key] = $sub_value;
                                                            $total = subGrp_total($arr,0);
                                                            
                                                            ?>
                                            <tr>
                                                <td><a href=""><?=$sub_value['name']?></a></td>
                                                <td><?=number_format($total,2) ?>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php 
                                                            unset($arr);
                                                        }
                                                    }
                                                }
                                            ?>

                                            <tr>
                                                <td>Colsing Stock </td>
                                                <td><?=number_format($closing_stock,2)?></td>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <?php 
                                                if($lib_total < $assets_total ){
                                                    $is_color = 1;
                                                }else{
                                                    $is_color = 0;
                                                }
                                            ?>
                                            <tr>
                                                <td><b>Total</b></td>
                                                <td style="color:<?=($is_color == 1) ? 'red' : ''?>;"><b><?=$lib_total?></b></td>
                                                <!-- <td><b></b> -->
                                                </td>
                                                <td></td>
                                                <td><b>Total</b></td>
                                                <td><b><?=$assets_total?></b></td>
                                                <!-- <td><b></b> -->
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="editor"></div>
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