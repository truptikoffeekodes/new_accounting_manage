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
            <form method="post" action="<?=url('Trading/dashboard')?>">
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
                                        <input class="form-control dateMask" id="dateMask" name="from" required placeholder="DD-MM-YYYY"
                                            type="text">
                                    </div>
                                    <!-- <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div><input class="form-control fc-datepicker" name="from" id="from"
                                            placeholder="2020-12-12" type="text">
                                    </div> -->
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
                                        <input class="form-control dateMask" id="dateMask" name="to" required placeholder="DD-MM-YYYY"
                                            type="text">
                                    </div>
                                    <!-- <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div><input class="form-control fc-datepicker" name="to" id="to"
                                            placeholder="2020-12-12" type="text">
                                    </div> -->
                                </div>
                            </div>

                            <div class="col-md-6">
                                <br>
                                <div class="form-group mb-lg-0">
                                    <div class="input-group">
                                        <label class="custom-switch">
                                            <input type="checkbox" name="abc" id='abc' value="<?=session('is_stock')?>"
                                                class="custom-switch-input"
                                                <?=(session('is_stock') == 1) ? 'checked' : ''; ?>>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">I agree with terms and
                                                conditions</span>
                                        </label>
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
                                            <span style="size:20px;"><b>Trading A/c</b></span>
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
                                        
                                            if(session('is_stock') == 1 ){
                                                // $closing_stock = @$trading['opening_bal'] + ($trading['pur_total_rate'] -$trading['Purret_total_rate']) - ($trading['sale_total_rate'] - $trading['Saleret_total_rate']);
                                                $closing_stock = @$trading['closing'] ? @$trading['closing'] : @$trading['opening_bal'];
                                            }else{
                                                $closing_stock  = $trading['closing_bal'];
                                            }
                                            // print_r($closing_stock);
                                            $income_total = ($trading['sale_total_rate'] - $trading['Saleret_total_rate']) + $closing_stock +$trading['trading_income'];
                                            $expens_total = $trading['opening_bal'] + ($trading['pur_total_rate'] -$trading['Purret_total_rate']) + $trading['trading_expense'];
                                            
                                            if(($expens_total -  $income_total) < 0 ){
                                                $gross_profit = ($expens_total -  $income_total) * -1;
                                                
                                                $expens_total +=$gross_profit;
                                            }else{
                                                $gross_loss = $expens_total -  $income_total;
                                                $income_total +=$gross_loss; 
                                            }
                                            if((@$trading['sale_total_rate'] - @$trading['Saleret_total_rate'])  != 0){
                                                $per_base = 100 / (@$trading['sale_total_rate'] - @$trading['Saleret_total_rate']);
                                            }else{
                                                $per_base = 100/1;
                                            }
                                        ?>
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
                                                <td><b>Opening Stock</b></td>
                                                <td></td>
                                                <td><b><?=$trading['opening_bal']?></b> </td>
                                            </tr>
                                            <tr>
                                                <td>Stock In Hand </td>
                                                <td> <?=$trading['opening_bal']?></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td><b>Purchase Accounts </b></td>
                                                <td></td>
                                                <td><b><?=$trading['pur_total_rate'] -$trading['Purret_total_rate'] ?></b>
                                                    <br>(<?= number_format($per_base * ($trading['pur_total_rate'] -$trading['Purret_total_rate']) , 2)?>%)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Purchase Account </td>
                                                <td><?=$trading['pur_total_rate']?>
                                                    <br>(<?=$per_base * $trading['pur_total_rate'] ?>%)
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <tr>
                                                <td>Purchase Return</td>
                                                <td>-<?=$trading['Purret_total_rate']?>
                                                    <br>(-<?=$per_base * $trading['Purret_total_rate'] ?>%)
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <tr>
                                                <td><b>Trading Expense </b></td>
                                                <td></td>
                                                <td><b><?=$trading['trading_expense']?></b><br>
                                                    (<?=$per_base * $trading['trading_expense'] ?>%)
                                                </td>
                                            </tr>
                                            <?php foreach(@$trading['enpense_ac'] as $key => $value){ 
                                                    if($value['total'] != 0){
                                                ?>
                                            <tr>
                                                <td><?=$key ?></td>
                                                <td><?=$value['total'] ?>
                                                    (<?=$per_base * $value['total'] ?>%)
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <?php } } ?>

                                            <?php if(!empty($gross_profit)) {?>
                                            <tr>
                                                <td><b>Gross Profit</b> </td>
                                                <td></td>
                                                <td><b><?=$gross_profit?></b><br>
                                                    (<?=$per_base * $gross_profit ?>%)
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <!-- <tr>
                                                <td><b>Total</b></td>
                                                <td></td>
                                                <td><b><?=$expens_total ?></b>
                                                </td>
                                            </tr> -->
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
                                            <tr>
                                                <td><b>Sales Accounts</b> </td>
                                                <td></td>
                                                <td><b><?=$trading['sale_total_rate'] - $trading['Saleret_total_rate'] ?></b><br>
                                                    (<?=$per_base * ($trading['sale_total_rate'] - $trading['Saleret_total_rate']) ?>%)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="<?=url('Trading/sale_detail')?>">Sales Accounts</a></td>
                                                <td><?=$trading['sale_total_rate']?><br>
                                                    (<?=$per_base * $trading['sale_total_rate'] ?>%)
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td> Sales Return</td>
                                                <td>-<?=$trading['Saleret_total_rate']?><br>
                                                    (-<?=$per_base * $trading['Saleret_total_rate'] ?>%)</td>
                                                <td> </td>
                                            </tr>
                                            <tr>
                                                <td><b>Closing Stock</b> </td>
                                                <td></td>
                                                <?php
                                               
                                                if(session('is_stock') == 1 ) { ?>
                                                <td><b><?=@$trading['opening_bal'] + $closing_stock ?></b>
                                                    <a data-toggle="modal" href="<?=url('Trading/add_closing')?>"
                                                        data-target="#fm_model" data-title="Add Closing ">
                                                        <i class="btn btn-secondary btn-sm mb-1" style="float:right"><i
                                                                class="fa fa-plus"></i></i></a>
                                                </td>
                                                <?php }else {  ?>
                                                <td><b><?=@$trading['closing_bal']?></b></td>
                                                <?php } ?>
                                            </tr>

                                            <tr>
                                                <td><b>Trading Income</b></td>
                                                <td></td>
                                                <td><b><?=$trading['trading_income']?></b><br>
                                                    (<?=$per_base * $trading['trading_income'] ?>%)</td>
                                                </td>
                                            </tr>

                                            <?php foreach($trading['income_ac'] as $key => $value){ 
                                                    if($value['total'] != 0){
                                                ?>
                                            <tr>
                                                <td><?=$key?></td>
                                                <td><?=$value['total']?>
                                                    (<?=$per_base * $value['total'] ?>%)</td>
                                                <td> </td>
                                            </tr>
                                            <?php } } ?>

                                            <?php if(!empty($gross_loss)) {?>
                                            <tr>
                                                <td><b>Gross Loss</b> </td>
                                                <td></td>
                                                <td><b><?=$gross_loss?></b><br>
                                                    (<?=$per_base * $gross_loss ?>%)
                                                </td>
                                            </tr>
                                            <?php } ?>

                                            <!-- <tr>
                                                <td><b>Total</b></td>
                                                <td></td>
                                                <td><b><?=$income_total ?></b>
                                                </td>
                                            </tr> -->
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <td><b>Total</b></td>
                                                <td></td>
                                                <td><b><?=$expens_total ?></b>
                                                </td>
                                                <td></td>
                                                <td><b>Total</b></td>
                                                <td></td>
                                                <td><b><?=$income_total ?></b>
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

$('#abc').click(function() {
    abc = $('#abc').val();
    console.log(abc);
    if (abc == 1) {
        var data = 0;
        $('#abc').val('0');
    } else {
        var data = 1;
        $('#abc').val('1');
    }
    var url = PATH;
    $.ajax({
        url: PATH + '/company/update_company',
        type: 'POST',
        data: {
            'id': data
        },
        success: function(response) {
            if (response.st == 'success') {
                swal("success!", "Your update successfully..!!", "success");
                window.location = PATH + '/Trading/dashboard';
            } else {
                $('.error-msg').html(response.msg);
            }
        },
        error: function() {
            alert('Error');
        }
    });

});
</script>
<?= $this->endSection() ?>