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
        <a href="<?=url('Profitloss/Profit_loss_xls?from='.$start_date.'&to='.$end_date)?>"  class="btn ripple btn-primary"><i class="fe fe-external-link"></i>Excel Export</a>

    </div>
</div>
<!--Start Navbar -->
<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="post" action="<?=url('Profitloss/pl_dashboard')?>" autocomplete = "off">
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
                                        <input class="form-control fc-datepicker"  name="from"
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
                                        <input class="form-control fc-datepicker1"  name="to"
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
                                            <span style="size:20px;"><b>Profit / Loss</b></span>
                                            </br>
                                            <?php
                                                $from =date_create($trading['from']) ;                                         
                                                $to = date_create($trading['to']);                              
                                                 
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
                                            
                                            // if(session('is_stock') == 1 ){
                                            //     $closing_stock = @$trading['closing'] ? @$trading['closing'] : @$trading['opening_bal'];
                                            // }else{
                                            //     $closing_stock  = @$trading['closing_bal'] ? @$trading['closing_bal'] : @$trading['opening_bal'] ;
                                            // }
                                            if(session('is_stock') == 1 ){
                                                $closing_stock = @$trading['manualy_closing_bal'];
                                            }else{
                                                $closing_stock  = $trading['closing_bal'] + @$trading['opening_bal_total'];
                                            }
                                            

                                            $all_purchase = $trading['pur_total_rate'] ;
                                            $all_purchase_return = $trading['Purret_total_rate'] ;
                                            
                                            $all_sale = $trading['sale_total_rate'] ;
                                            $all_sale_return = $trading['saleret_total_rate'] ;

                                            

                                            $income_total = (float)$all_sale - (float)$all_sale_return + $closing_stock +$trading['inc_total'];
                                            $expens_total = $trading['opening_bal'] + (float)$all_purchase  - (float)$all_purchase_return + $trading['exp_total'];


                                            if(($expens_total -  $income_total) < 0 ){
                                                $gross_profit = ($expens_total -  $income_total) * -1;
                                            }else{
                                                $gross_loss = $expens_total -  $income_total;
                                            }
                                            
                                            if((@$gross_loss + $pl['exp_total'])   >  ($pl['inc_total'] + @$gross_profit)){
                                                $net_loss = (@$gross_loss + $pl['exp_total']) - ($pl['inc_total'] + @$gross_profit);
                                            }else{
                                                $net_profit =($pl['inc_total'] + @$gross_profit)  - (@$gross_loss + $pl['exp_total']);
                                            }

                                            $pl_expens_total = $pl['exp_total'] + @$net_profit + @$gross_loss;
                                            $pl_income_total = $pl['inc_total'] + @$gross_profit + @$net_loss;
                                        ?>
                                        <table class="table">
                                            <tr>
                                                <td>
                                                    <span style="size:20px;"><b>Particulars </b> </span>
                                                </td>
                                                <td colspan="2">
                                                    <span style="size:20px;"><b><?=session('name')?></b></span>
                                                    </br>
                                                    as at <?=date_format($to,"d/m/Y")?>
                                                </td>
                                            </tr>
                                            <?php if(!empty($gross_loss)) {?>
                                            <tr>
                                                <td><b>Gross Loss</b> </td>
                                                <td></td>
                                                <td><b><?=number_format($gross_loss,2)?></b>
                                                </td>
                                            </tr>
                                            <?php } ?>

                                            <?php
                                            $total = 0;
                                            foreach($pl['exp'] as $key => $value) { ?>
                                            <tr>
                                                <td><b><?=@$value['name']?></b></td>
                                                <td></td>
                                                <td><b><?=number_format(@$pl['exp_total'],2)?></b><br>
                                                </td>
                                            </tr>

                                            <?php   
                                                    if(!empty($value['account'])) {
                                                        foreach(@$value['account'] as $ac_key => $ac_value){ ?>
                                                            <tr>
                                                                <td><a href="<?=url('Trading/get_expence_account_data?from='.$trading['from'].'&to='.$trading['to'].'&id='.$ac_value['account_id'].'&type=pl')?>"><?=$ac_key ?></a></td>
                                                                <td><?=number_format($ac_value['total'] ,2)?>
                                                                </td>
                                                                <td> </td>
                                                            </tr>
                                                            <?php 
                                                        }    
                                                    }
                                            ?>

                                            <?php if(!empty($value['sub_categories'])) {
                                                        foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                                                            $total = 0;
                                                            $arr[$sub_key] = $sub_value;
                                                            $total = subGrp_total($arr,0);
                                                            
                                                            ?>
                                                          <tr>
                                                                <td><a href = "<?=url('Profitloss/get_expence_sub_grp?'.'id='.$sub_key.'&name='.$sub_value['name'].'&from='.$trading['from'].'&to='.$trading['to'].'&type=pl')?>"><?=$sub_value['name']?></a></td>
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

                                            <?php if(isset($net_profit) && !empty($net_profit) ) { ?>
                                            <tr>
                                                <td><b>Net Profit </b></td>
                                                <td></td>
                                                <td><b><?=number_format($net_profit,2) ?></b>
                                                </td>
                                            </tr>
                                            <?php } 
                                           
                                            ?>
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
                                                    <span style="size:20px;"><b>Particulars </b></span>
                                                </td>
                                                <td colspan="2">
                                                    <span style="size:20px;"><b><?=session('name')?></b></span>
                                                    </br>
                                                    as at <?=date_format($to,"d/m/Y")?>
                                                </td>
                                            </tr>
                                            <tr>
                                            </tr>
                                            <tr>
                                            </tr>
                                            <?php if(!empty($gross_profit)) {?>
                                            <tr>
                                                <td><b>Gross Profit</b> </td>
                                                <td></td>
                                                <td><b><?=number_format($gross_profit,2)?></b>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            
                                            <?php
                                            $total = 0;
                                            foreach($pl['inc'] as $key => $value) { ?>
                                            <tr>
                                                <td><b><?=@$value['name']?></b></td>
                                                <td></td>
                                                <td><b><?=number_format(@$pl['inc_total'],2)?></b><br>
                                                </td>
                                            </tr>

                                            <?php   
                                                    if(!empty($value['account'])) {
                                                        foreach(@$value['account'] as $ac_key => $ac_value){ ?>
                                                            <tr>
                                                                <td><a href="<?=url('Trading/get_income_account_data?from='.$trading['from'].'&to='.$trading['to'].'&id='.$ac_value['account_id'].'&type=pl')?>"><?=$ac_key ?></a></td>
                                                                <td><?=number_format($ac_value['total'],2) ?>
                                                                </td>
                                                                <td> </td>
                                                            </tr>
                                                            <?php 
                                                        }    
                                                    }
                                            ?>

                                            <?php if(!empty($value['sub_categories'])) {
                                                        foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                                                            $total = 0;
                                                            $arr[$sub_key] = $sub_value;
                                                            $total = subGrp_total($arr,0);
                                                            
                                                            ?>
                                                          <tr>
                                                                <td><a href = "<?=url('Profitloss/get_income_sub_grp?id='.$sub_key.'&name='.$sub_value['name'].'&from='.$trading['from'].'&to='.$trading['to'].'&type=pl')?>"><?=$sub_value['name']?></a></td>
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
                                            
                                            <?php if(isset($net_loss) && !empty($net_loss) ) { ?>
                                            <tr>
                                                <td><b>Net Loss </b></td>
                                                <td></td>
                                                <td><b><?=number_format($net_loss,2) ?></b>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <td><b>Total</b></td>
                                                <td></td>
                                                <td><b><?=number_format($pl_expens_total,2) ?></b>
                                                </td>
                                                <td></td>
                                                <td><b>Total</b></td>
                                                <td></td>
                                                <td><b><?=number_format($pl_income_total,2) ?></b>
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
    $('.fc-datepicker1').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $('.dateMask').mask('99-99-9999');
});
</script>
<?= $this->endSection() ?>