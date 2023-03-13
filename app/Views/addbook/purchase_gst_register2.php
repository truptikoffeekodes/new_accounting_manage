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
        <a href="<?=url('Addbook/Purchase_gst_register2_xls?from='.$start_date.'&to='.$end_date)?>"  class="btn ripple btn-primary"><i class="fe fe-external-link"></i>Excel Export</a>

    </div>
</div>

<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">

            <form method="post" action="<?=url('Addbook/Purchase_gst_register2')?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-lg-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        FROM:
                                    </div>
                                </div>
                                <input class="form-control fc-datepicker" id="dateMask" name="from" placeholder="DD-MM-YYYY"
                                    type="text">
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
                                <input class="form-control fc-datepicker" id="" name="to" placeholder="DD-MM-YYYY"
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
                                    <th rowspan="2">Total Tax</th>
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
                                </tr>


                            </thead>
                            <tr>
                                <th colspan="9">&nbsp;</th>
                                <th>%age</th>
                                <th>Amount</th>
                                <th>%age</th>
                                <th>Amount</th>
                                <th>%age</th>
                                <th>Amount</th>
                                
                            </tr>
                            <tbody>
                                <?php

                                    
                                    if (!empty($purchase)) {

                                        foreach ($purchase as $row) {
                                            
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

                                            $total_tax= 0;
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

                                                $itm_igst = $final_sub * ($row['item'][$i]['igst'] /100);
                                                $total_tax +=$itm_igst; 
                                            }
                                    ?>
                              
                                <tr>
                                    <th><?=@$row['id'];?></th>
                                    <th><?=@$row['account_name'];?></th>
                                    <th><?=@$row['gst'];?></th>
                                    <th><?=user_date($row['invoice_date']);?></th>
                                    <th><?=number_format(@$row['net_amount'], 2);?></th>
                                    <th><?=number_format(@$total_tax, 2);?></th>
                                    <th colspan="9"></th>
                                </tr>

                                    <?php
                                    $new_array = array();
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

                                                    $new_array[$row['item'][$i]['hsn']]['hsn'] = $row['item'][$i]['hsn'];
                                                    $new_array[$row['item'][$i]['hsn']]['qty'] = (isset($new_array[$row['item'][$i]['hsn']]['qty']) ? $new_array[$row['item'][$i]['hsn']]['qty'] : 0) +   $row['item'][$i]['qty'];
                                                    $new_array[$row['item'][$i]['hsn']]['total'] = (isset($new_array[$row['item'][$i]['hsn']]['total']) ? $new_array[$row['item'][$i]['hsn']]['total'] : 0) +   $final_sub;
                                                    $new_array[$row['item'][$i]['hsn']]['igst'] = isset($row['item'][$i]['igst']) ? @$row['item'][$i]['igst'] : 0 ;
                                                    $new_array[$row['item'][$i]['hsn']]['sgst'] = isset($row['item'][$i]['sgst']) ? @$row['item'][$i]['sgst'] : 0 ;
                                                    $new_array[$row['item'][$i]['hsn']]['cgst'] = isset($row['item'][$i]['cgst']) ? @$row['item'][$i]['cgst'] : 0 ;

                                                    if(in_array("igst", $tax_arr)){
                                                        $new_array[$row['item'][$i]['hsn']]['igst_total'] = (isset($new_array[$row['item'][$i]['hsn']]['igst_total']) ? $new_array[$row['item'][$i]['hsn']]['igst_total'] : 0) +   $itm_igst;
                                                    }else{
                                                        $new_array[$row['item'][$i]['hsn']]['cgst_total'] = (isset($new_array[$row['item'][$i]['hsn']]['cgst_total']) ? $new_array[$row['item'][$i]['hsn']]['cgst_total'] : 0) +   $itm_cgst;
                                                        $new_array[$row['item'][$i]['hsn']]['sgst_total'] = (isset($new_array[$row['item'][$i]['hsn']]['sgst_total']) ? $new_array[$row['item'][$i]['hsn']]['sgst_total'] : 0) +   $itm_sgst;
                                                    }
                                                }
                                                    
                                                    foreach($new_array as $item){
                                        ?>
                            <tr>
                                <td colspan="6"></td>
                                <td><?=@$item['hsn'];?></td>
                                <td><?=@$item['qty'];?></td>
                                <td><?=@$item['total'];?></td>
                                <td><?=@$item['sgst'];?></td>
                                <td><?=number_format(@$item['sgst_total'],2);?></td>
                                <td><?=@$item['cgst'];?></td>
                                <td><?=number_format(@$item['sgst_total'],2);?></td>
                                <td><?=@$item['igst'];?></td>
                                <td><?=number_format(@$item['igst_total'],2);?></td>
                            </tr>
                               
                                <?php }
                                
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