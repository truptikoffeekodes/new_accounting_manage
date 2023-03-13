<?=$this->extend(THEME . 'templete')?>

<?=$this->section('content')?>
<style>
.purchase_gst {
    width: 150%;
    table-layout: fixed;
    border-collapse: collapse;
    margin-bottom: 5px;
}

.table-responsive::-webkit-scrollbar {
    width: 3px;
    height: 12px;
    transition: .3s background;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #e1e6f1;
}
</style>

<div class="container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Purchase GST Register Book</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=url('')?>">Addbook</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=@$title;?></li>
            </ol>
        </div>
        <div class="btn btn-list">
            <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
            </a>
        </div>
    </div>

    <div class="responsive-background">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="advanced-search">

                <form method="post" action="<?=url('Addbook/Purchase_gst_register')?>">
                    <div class="row">
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <div class="form-group mb-lg-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            TO:
                                        </div>
                                    </div>
                                    <input class="form-control dateMask" id="" name="to" placeholder="DD-MM-YYYY"
                                        type="text">
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="text-right mt-2">
                        <button type="submit" class="btn btn-primary">Apply</button>
                        <a href="#" id="SearchButtonReset" class="btn btn-secondary" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">Reset</a>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- End Page Header -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card custom-card main-content-body-profile">
                <div class="card-header card-header-divider">

                    <div class="card-body tab-content h-100">
                        <div class="table-responsive">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-fw-widget">
                                    <tr>
                                        <td>
                                            <span style="size:20px;"><b><?=@$title;?></b></span>
                                            </br>
                                            <?php
                                                $from = date_create($start_date);
                                                $to = date_create($end_date);
                                            ?>
                                            <b><?=date_format($from, "d/m/Y");?></b> to
                                            <b><?=date_format($to, "d/m/Y");?></b>

                                        </td>
                                    </tr>
                                    <tr colspan="4">
                                    </tr>
                                </table>
                            </div>
                            <table class="table dataTable border table-hover table-fw-widget purchase_gst">
                                <thead>
                                    <tr>
                                        <th rowspan="2">ID</th>
                                        <th rowspan="2">Name</th>
                                        <th rowspan="2">GST</th>
                                        <th rowspan="2">Invoice Date</th>
                                        <th rowspan="2">Invoice Value</th>
                                        <th rowspan="2">HSN</th>
                                        <th rowspan="2">QTY</th>
                                        <th rowspan="2">Taxable Amount</th>
                                        <th colspan="2">
                                            <center>SGST</center>
                                        </th>
                                        <th colspan="2">
                                            <center>CGST</center>
                                        </th>
                                        <th colspan="2">
                                            <center>IGST</center>
                                        </th>
                                        <th rowspan="2">Total GST</th>
                                    </tr>


                                </thead>
                                <tr>
                                    <th colspan="8">&nbsp;</th>
                                    <th>%age</th>
                                    <th>Amount</th>
                                    <th>%age</th>
                                    <th>Amount</th>
                                    <th>%age</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                                <tbody>
                                    <?php

                                    
                                    if (!empty($purchase)) {

                                        foreach ($purchase as $row) {
                                            $k = 0;
                                            $total = 0;
                                            $tax_arr = json_decode($row['taxes']);

                                            for ($j = 0; $j < count($row['item']); $j++) {
                                                $total += $row['item'][$j]['qty'] * $row['item'][$j]['rate'];
                                            }

                                            if ($row['discount'] > 0) {
                                                if ($row['disc_type'] == '%') {
                                                    $discount_amount = ($total * ($row['discount'] / 100));
                                                    $disc_avg_per = $discount_amount / $total;
                                                } else {
                                                    $disc_avg_per = $row['discount']/ $total;
                                                }
                                            } else {
                                                $disc_avg_per = 0;
                                            }

                                            if ($row['amty'] > 0) {
                                                if ($row['amty_type'] == '%') {
                                                    $amty_amount = ($total * ($row['amty'] / 100));
                                                    $add_amt_per = $amty_amount / $total;
                                                } else {
                                                    $add_amt_per = $row['amty'] / $total;
                                                }
                                            } else {
                                                $add_amt_per = 0;
                                            }

                                            for ($i = 0; $i < count($row['item']); $i++) {
                                                $sub = $row['item'][$i]['qty'] * $row['item'][$i]['rate'];

                                                if ($row['discount'] > 0) {
                                                    $discount_amt = $sub * $disc_avg_per;
                                                    $final_sub = $sub - $discount_amt;
                                                    $add_amt = $sub * $add_amt_per;

                                                    $final_sub += $add_amt;
                                                } else {
                                                    $disc_amt = $sub * $row['item'][$i]['item_disc'] / 100;
                                                    $final_sub = $sub - $disc_amt;
                                                    $add_amt = $final_sub * $add_amt_per;

                                                    $final_sub += $add_amt;
                                                }
                                            }
                                    ?>
                                    <?php if($k == 0) {?>
                                    <tr>
                                        <td><?=@$row['id'];?></td>
                                        <td><?=@$row['account_name'];?></td>
                                        <td><?=@$row['gst'];?></td>
                                        <td><?=user_date($row['return_date']);?></td>
                                        <td><?=number_format(@$row['net_amount'], 2);?></td>
                                        <?php 

                                        $sub = $row['item'][0]['qty'] * $row['item'][0]['rate'];
                                       
                                        if ($row['discount'] > 0) {

                                            $discount_amt = $sub * $disc_avg_per;
                                            $final_sub = $sub - $discount_amt;

                                            $add_amt = $sub * $add_amt_per;
                                            $final_sub += $add_amt;

                                        } else {
                                            $disc_amt = $sub * $row['item'][0]['item_disc'] / 100;
                                            $final_sub = $sub - $disc_amt;
                                            
                                            $add_amt = $final_sub * $add_amt_per;
                                            $final_sub += $add_amt;
                                        } 
                                        $itm_igst = $final_sub * ($row['item'][0]['igst'] /100);
                                        $itm_cgst = $itm_igst/2;
                                        $itm_sgst = $itm_igst/2;

                                        $k++;


                                        ?>

                                            <td><?=@$row['item'][0]['hsn'];?></td>
                                            <td><?=@$row['item'][0]['qty'];?></td>
                                            <td><?=number_format(@$final_sub,2)?></td>

                                            <?php if(in_array("igst", $tax_arr)){ ?>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><?=@$row['item'][0]['igst'];?></td>
                                            <td><?=number_format(@$itm_igst,2);?></td>

                                            <?php }else{ ?>

                                            <td><?=@$row['item'][0]['sgst'];?></td>
                                            <td><?=number_format(@$itm_sgst,2);?></td>
                                            <td><?=@$row['item'][0]['cgst'];?></td>
                                            <td><?=number_format(@$itm_cgst,2);?></td>
                                            <td></td>
                                            <td></td>

                                            <?php } ?>
                                            <td><?=number_format(@$row['tot_igst'],2)?></td>
                                        </tr>

                                    <?php } ?>
                                   
                                        <?php
                                            for ($i = 1; $i < count($row['item']); $i++) {
                                                    $sub = $row['item'][$i]['qty'] * $row['item'][$i]['rate'];
                                                    
                                                    if ($row['discount'] > 0) {
                                                        $discount_amt = $sub * $disc_avg_per;
                                                        $final_sub = $sub - $discount_amt;
                                                        $add_amt = $sub * $add_amt_per;

                                                        $final_sub += $add_amt;
                                                    } else {
                                                        $disc_amt = $sub * $row['item'][$i]['item_disc'] / 100;
                                                        $final_sub = $sub - $disc_amt;
                                                        $add_amt = $final_sub * $add_amt_per;

                                                        $final_sub += $add_amt;
                                                    } 
                                                    $itm_igst = $final_sub * ($row['item'][$i]['igst'] /100);
                                                    $itm_cgst = $itm_igst/2;
                                                    $itm_sgst = $itm_igst/2;
                                                    ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><?=@$row['item'][$i]['hsn'];?></td>
                                                    <td><?=@$row['item'][$i]['qty'];?></td>
                                                    <td><?=number_format(@$final_sub,2)?></td>

                                                    <?php if(in_array("igst", $tax_arr)){ ?>

                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><?=@$row['item'][$i]['igst'];?></td>
                                                    <td><?=number_format(@$itm_igst,2);?></td>
                                                    <td></td>

                                                    <?php }else{ ?>

                                                    <td><?=@$row['item'][$i]['sgst'];?></td>
                                                    <td><?=number_format(@$itm_sgst,2);?></td>
                                                    <td><?=@$row['item'][$i]['cgst'];?></td>
                                                    <td><?=number_format(@$itm_cgst,2);?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                
                                        <?php } ?>
                                        </tr>
                                        <?php } ?>   
                                    <?php
                                        }
                                    }
                                    ?>
                                <tfoot>
                                   
                                </tfoot>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?=$this->endSection()?>

<?=$this->section('scripts')?>
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

    $('#bills').on('select2:select', function(e) {
        var data = e.params.data;

        $('#bill_tb').val(data.table);
    });

});
</script>

<?=$this->endSection()?>