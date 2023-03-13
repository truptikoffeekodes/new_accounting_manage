<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        
    table {
        border: 1px solid black;
        border-collapse: collapse;
    }

    .border td {
        border: 1px solid black;
        border-collapse: collapse;
    }


    .T-border2 {
        width: 100% !important;
        border-right: 1px solid black;
        /* text-align: center; */
    }

    .lastline td {
        border-top: 1px solid black;
    }


    .border td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    h4 {
        text-align: center;
        font: bold;
    }

    .T-border2 th,
    td {
        border-left: 1px solid black !important;
    }

    .T-border2 th {
        text-align: center;
        border-bottom: 1px solid black !important;
    }

    .border-2 td {
        border-top: 1px solid black !important;
        text-align: end;
    }

    </style>

</head>

<body>
    <table style="width:100%" class="border">
            <thead style="text-align:center;">
                <tr>
                    <th colspan="9">
                        <b>
                            <h3 style="margin: 0;padding-top: 15px;">
                                !! Shree Ganeshaya Namah !!</h3>
                        </b>
                        <br>
                        <h1 style="margin:0;padding:0px"><?=ucfirst(session('name'));?></h1>

                        <br>
                        <h3 style="margin: 0;padding: 0px;"><?=@session('address') ? @session('address') : '';?> <?=!empty(@session('city')) ? ' , ' . session('city') : ''?><?=!empty(@session('pin')) ? ' - ' . @session('pin') : ''?> <?=!empty(@session('state_name')) ? ' , ' . @session('state_name') : ''?><?=!empty(@session('country')) ? ' , ' . @session('country') : ''?>  </h3>
                        <br>
                        <h3 style="margin: 0;padding:0;">GSTIN : <?=@session('gst')?>&nbsp; / PAN : <?=@session('incomtax_pan')?></h3>
                        <h3 style="margin: 0;padding-bottom: 5px;"></h3>
                    </th>
                </tr>
            </thead>
        <tbody>
            <tr>
                <td rowspan="3" colspan="6"><center><h3>Proforma Invoice</h3></center> <br>

                </td>
                <td>&nbsp;</td>
                <td colspan="2" style="border-bottom:  1px solid black;"> Original For Receipient </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2" style="border-bottom:  1px solid black;"> Duplicate For Supplier/Transport </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2" style="border-bottom:  1px solid black;">Triplicate For Supplier</td>
            </tr>

            <tr>
                <td colspan="3">Billed To : <?=@$account['name']?>
                    <br><?=@$account['address']?>
                    <br><?=@$billing_city['name']?> &nbsp;<?=@$account['pin']?>
                    <br><?=@$billing_state['name']?>
                    <br><?=@$billing_country['name']?>
                    <br>GSTIN :<?=@$account['gst']?>
                    <br>State Code : <?=@$billing_state['state_code']?>
                    <br>State : <?=@$billing_state['name']?>
                </td>

                <td colspan="3">Ship To : <?=@$delivery['name']?>
                    <br><?=@$account['gst_add']?>
                    <br><?=@$purchasechallan['ship_city']?> &nbsp;<?=@$purchasechallan['ship_pin']?>
                    <br><?=@$purchasechallan['ship_state']?>
                    <br><?=@$purchasechallan['ship_country']?>
                    <br>GSTIN :<?=@$delivery['gst']?>
                    <br>State Code : <?=@$ship_state['state_code']?>
                    <br>State :<?=@$ship_state['name']?>

                </td>
                <td colspan="3">
                    <b>Challan No : <?=@$purchasechallan['challan_no']?></b><br>
                    <b>Voucher No : <?=@$purchasechallan['id']?></b>
                    <br><b>Challan Date : <?=user_date(@$purchasechallan['challan_date'])?></b>
                    <br>Place Of Supply : <?=@$ship_state['name']?>
                </td>

            </tr>



            <tr>
                <td colspan="4">Broker :<?=@$purchasechallan['broker_name']?></td>
                <td colspan="5">Transporter Name :<?=@$transport['name']?></td>
            </tr>
            <tr>
                <td colspan="3">
                    L.R.No :<?=@$purchasechallan['lr_no']?>
                    <br>Transport :
                </td>
                <td colspan="3">L.R.Date : <?=@$purchasechallan['lr_date']?>
                    <br>Transport Type : <?=@$purchasechallan['transport_mode']?>
                </td>
                <td colspan="3">Weight :<?=@$purchasechallan['weight']?>
                    <br>Freight :<?=@$purchasechallan['freight']?>
                </td>

            </tr>
        </tbody>
    </table>

    <table class="T-border2" style="width:100%">
        <tbody>
            <tr>
                <td style="border-right: 1px solid black; text-align: center;">SI No.</td>
                <td style="border-right: 1px solid black; text-align: center;">Description of Goods</td>
                <td style="border-right: 1px solid black; text-align: center;">HSN/SAC</td>
                <td style="border-right: 1px solid black; text-align: center;">GST<br>Rate</td>
                <td style="border-right: 1px solid black; text-align: center;"> Quantity</td>
                <td style="border-right: 1px solid black; text-align: center;"> Rate</td>
                <td style="border-right: 1px solid black;text-align: center;"> per</td>
                <td colspan="2" style="border-right: 1px solid black;text-align: center;">Amount</td>
            </tr>

            <?php
                $i = 1;
                $total_qty = 0;

                $total = 0.0;
                $igst_amt = 0.0;
                $sub = 0;

                if ($purchasechallan['discount'] > 0) {

                }

                foreach ($item as $row) {
                    $total_qty += $row['qty'];

                    $sub = $row['qty'] * $row['rate'];
                    $disc_amt = $sub * $row['item_disc'] / 100;

                    $final_sub = $sub - $disc_amt;
                    $total += $final_sub;

            ?>

            <tr>
                <td style="border-right: 1px solid black; border-top: 1px solid black;"><?=@$i?></td>
                <td style="border-right: 1px solid black; border-top: 1px solid black;width: 167px;">
                <?=@$row['name']?>
                </td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;"><?=@$row['hsn']?>
                </td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;"><?=@$row['igst']?>%</td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;"><b><?=@$row['qty']?></b></td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;"><?=number_format(@$row['rate'], 2)?></td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;"><?=@$row['uom']?></tds>
                <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;">
                <b><?=number_format(@$final_sub, 2)?></b></td>
            </tr>
            <?php
                    $i++;
                }
            ?>
            <tr>
                <td colspan="4" style="border-right: 1px solid black; border-top: 1px solid black; text-align: right;">
                    <b>Total</b></td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;">
                <b><?=@$total_qty;?></b></td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;"> </td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;"> </td>
                <td
                    colspan="2" style="border-right: 1px solid black; border-top: 1px solid black;  border-bottom: 1px solid black; text-align: center;">
                    <b><?=@$total;?></b> </td>
            </tr>
    </table>
    <?php
        if ($total != 0) {
            if ($purchasechallan['disc_type'] == '%') {
                $discount_amount = ($total * ($purchasechallan['discount'] / 100));
                $disc_avg_per = $discount_amount / $total;
            } else {
                $disc_avg_per = ($purchasechallan['discount'] ? (float) $purchasechallan['discount'] : 0) / $total ? $total : 0;
            }

            if ($purchasechallan['amty'] > 0) {
                if ($purchasechallan['amty_type'] == '%') {
                    $amty_amount = ($total * ($purchasechallan['amty'] / 100));
                    $add_amt_per = $amty_amount / $total;
                } else {
                    $add_amt_per = $purchasechallan['amty'] / $total;
                }
            } else {
                $add_amt_per = 0;
            }

        } else {
            $add_amt_per = 0;
            $disc_avg_per = 0;
        }

        $total = 0;
        $igst_amt = 0;
        $grand_total = 0;
        $total_discount = 0;
        $total_add = 0;

        for ($i = 0; $i < count($item); $i++) {
            $sub = $item[$i]['qty'] * $item[$i]['rate'];

            if ($purchasechallan['discount'] > 0) {
                $discount_amt = $sub * $disc_avg_per;
                $final_sub = $sub - $discount_amt;
                $add_amt = $sub * $add_amt_per;
            } else {

                $discount_amt = $sub * $item[$i]['item_disc'] / 100;
                $final_sub = $sub - $discount_amt;
                $add_amt = $final_sub * $add_amt_per;
            }

            $final_tot = $final_sub + $add_amt;
            $igst_amt += $final_tot * $item[$i]['igst'] / 100;
            $total += $final_tot;

            $total_discount += $discount_amt;
            $total_add += $add_amt;

        }

        $grand_total = $total;
        $tax = json_decode($purchasechallan['taxes']);

        $fin_igst = 0;
        $fin_sgst = 0;
        $fin_cgst = 0;

        if (in_array('igst', $tax)) {
            $fin_igst = $igst_amt;

        }

        if (in_array('sgst', $tax)) {
            $fin_sgst = $igst_amt / 2;
            $fin_cgst = $igst_amt / 2;
        }

        $grand_total += $igst_amt;

        $grand_total += $purchasechallan['round_diff'];
    ?>
    <table class="T-border2" style="width:100%">
        <tr>
            <td colspan="6"
                style="border-right: 1px solid black; border-bottom: 1px solid black; border-top: 1px solid black; text-align: start;">
                CHALLAN NO - <?=@$purchasechallan['challan_no']?></td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">
                Less:Discount <br></td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
            <b>(-)<?=@$total_discount?></b><br></td>

        </tr>
        <tr>
            <td colspan="6"
                style="border-right: 1px solid black; border-bottom: 1px solid black; border-top: 1px solid black; text-align: start;">
                DATE : <?=@$challan_detail['challan_date']?></td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">Add
                Amount: <br>

            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
            <b>(+)<?=@$total_add?></b><br>

            </td>
        </tr>
        <tr>
            <td colspan="6" style="border-right: 1px solid black; border-top: 1px solid black; text-align: start;">
                Bank Name: <?=@$purchasechallan['bank_name'];?>
            </td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">Amount:
                <br>

            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> <?=@$total?><br></td>
        </tr>
        <tr>

            <td colspan="6" style="border-right: 1px solid black; border-top: 1px solid white; text-align: start;">
                ACC.No: <?=@$purchasechallan['bank_ac'];?>
            </td>
            <?php if ($fin_igst > 0) {?>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">IGST:
                <br>
            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> <b><?=number_format(@$fin_igst, 2)?></b><br>
            <?php } else {?>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">CGST:<br>
            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> <b><?=number_format(@$fin_cgst, 2)?></b><br>

            <?php }?>

            </td>
        </tr>
        <tr>

            <td colspan="6" style="border-right: 1px solid black; border-top: 1px solid white; text-align: start;">
                IFSC CODE: <?=@$purchasechallan['bank_ifsc'];?>
            </td>
            <?php if ($fin_igst > 0) {?>

            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;"></td>
            <td colspan="1" style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> 0</td>
            <?php } else {?>

            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">SGST:<br>
            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> <b><?=number_format(@$fin_sgst, 2)?></b><br>
            <?php }?>
        </tr>

        <tr>
            <td colspan="6" style="border-right: 1px solid black; border-top: 1px solid black; text-align: start;">
                Due Days: <?=@$purchasechallan['due_days'];?>
            </td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">Round
                Off:: <br>

            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> <b><?=@$purchasechallan['round_diff']?></b><br>
            </td>

        </tr>
        <tr>
            <td colspan="6" style="border-right: 1px solid black; border-top: 1px solid black; text-align: start;">
                RS In Word: <b><?php echo strtoupper(inword($grand_total)); ?></b>
            </td>
            <td colspan="2"
                style="border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; font-size:15px;">
                <b>Grant total:</b>
            </td>
            <td colspan="1"
                style=" border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;text-align: right; font-size:20px;">
                <b><?=number_format(@$grand_total, 2);?></b>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid black; border-top: 1px solid black;"> <b>HSN/SAC</b></td>
            <td style="text-align: start; border-top: 1px solid black; border-bottom: 1px solid black;"><b>Taxable Value</b>
            </td>
            <?php if ($fin_igst > 0) {?>

            <td colspan="2" style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
            <b>Tax</b>
            </td>
            <?php } else {?>
            <td colspan="2" style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
            <b>Central Tax</b>
            </td>
            <td colspan="2" style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
            <b>State Tax<b>
            </td>
            <?php }?>
            <td style=" border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;"><b>Tax
             Amount</b>
            </td>
            <td colspan = "4" rowspan="4">&nbsp;</td>



        </tr>
        <tr>
            <td style="border-bottom: 1px solid black; border-right: 1px solid black;"></td>
            <td style="text-align: center; border-bottom: 1px solid black;"></td>
            <?php if ($fin_igst > 0) {?>
            <td style="text-align: center; border-bottom: 1px solid black;">
                Rate
            </td>
            <td style="text-align: center; border-bottom: 1px solid black;">
                Amount
            </td>
            <?php } else {?>
            <td style="text-align: center; border-bottom: 1px solid black;">
                Rate
            </td>
            <td style="text-align: center; border-bottom: 1px solid black;">
                Amount
            </td>
            <td style="text-align: center; border-bottom: 1px solid black;">
                Rate
            </td>
            <td style="text-align: center; border-bottom: 1px solid black;">
                Amount
            </td>
            <?php }?>
            <td style="border-bottom: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
        </tr>
        <?php
            $total_igst = 0;
            $total_cgst = 0;
            $total_sgst = 0;
            $total_sub = 0;

            $arr = array();
            $total_taxable = 0;

            foreach ($item as $row) {
                if ($row['hsn'] != '') {

                    $total_taxable = ($row['qty'] * $row['rate']);

                    $arr[$row['hsn']]['taxable'] = (@$arr[$row['hsn']]['taxable'] ? @$arr[$row['hsn']]['taxable'] : 0) + $total_taxable;
                    $arr[$row['hsn']]['igst'] = $row['igst'];
                    $arr[$row['hsn']]['cgst'] = $row['cgst'];
                    $arr[$row['hsn']]['sgst'] = $row['sgst'];

                    $arr[$row['hsn']]['igst_amount'] = (@$arr[$row['hsn']]['igst_amount'] ? $arr[$row['hsn']]['igst_amount'] : 0)+@$total_taxable * $arr[$row['hsn']]['igst'] / 100;
                    $arr[$row['hsn']]['cgst_amount'] = (@$arr[$row['hsn']]['cgst_amount'] ? $arr[$row['hsn']]['cgst_amount'] : 0)+@$total_taxable * $arr[$row['hsn']]['cgst'] / 100;
                    $arr[$row['hsn']]['sgst_amount'] = (@$arr[$row['hsn']]['sgst_amount'] ? $arr[$row['hsn']]['sgst_amount'] : 0)+@$total_taxable * $arr[$row['hsn']]['sgst'] / 100;

                    $total_igst += $arr[$row['hsn']]['igst_amount'];
                    $total_cgst += $arr[$row['hsn']]['cgst_amount'];
                    $total_sgst += $arr[$row['hsn']]['sgst_amount'];

                    $total_taxable = 0;

                    $sub = $row['qty'] * $row['rate'];
                    $total_sub += $sub;

                } else {

                }

            }
            $total_igst = 0;
            $total_cgst = 0;
            $total_sgst = 0;
            foreach ($arr as $key => $value) {

        ?>
        <tr>
            <td style="border-bottom: 1px solid black; border-top: 1px solid black;"><?=@$key;?></td>
            <td style="text-align: start; border-top: 1px solid black; border-bottom: 1px solid black;"><?=@$value['taxable'];?></td>

            <?php if ($fin_igst > 0) {?>

            <td style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
            <?=@$value['igst'];?>%</td>

            <td style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
            <?=number_format(@$value['igst_amount'], 2);?>
            </td>

            <?php
                $total_igst += @$value['igst_amount'];
            ?>

            <?php } else {?>

            <td style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
            <?=@$value['cgst'];?>%</td>
            <td style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
            <?=number_format(@$value['cgst_amount'], 2);?>
            </td>


            <td style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
            <?=@$value['sgst'];?>%</td>

            <td style=" border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
            <?=number_format(@$value['sgst_amount'], 2);?>
            </td>

            <?php
                $total_cgst += @$value['cgst_amount'];
                $total_sgst += @$value['sgst_amount'];
            ?>

            <?php }?>
            <td  style="border-bottom: 1px solid black; border-right: 1px solid black;">&nbsp;</td>

        </tr>
        <?php }?>
        <tr>
            <td style="border-bottom: 1px solid black; border-top: 1px solid black;">
                <b>Total</b></td>
            <td style="text-align: start; border-top: 1px solid black; border-bottom: 1px solid black;">
            <b><?=@$total_sub;?></b>
            </td>

            <?php if ($fin_igst > 0) {?>

            <td colspan="2" style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
                <b><?=number_format(@$total_igst, 2);?></b>
            </td>
            <td style=" border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
                <?=number_format(@$total_igst, 2);?></td>

            <?php } else {?>

            <td colspan="2" style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
                <b><?=number_format(@$total_cgst, 2);?></b>
            </td>

            <td colspan="2" style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
                <b><?=number_format(@$total_sgst, 2);?></b>
            </td>
            <td style=" border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
                <b><?=number_format((@$total_cgst+@$total_sgst), 2);?></b></td>

            <?php }?>


        </tr>

    </table>
    <table class="T-border2" style="width:100%">
        <tr>
            <td colspan="3"
                style=" border-right: 1px solid black; border-top: 1px solid black;  padding-top: 0px; padding-bottom: 30px;">
                <u><b>Declaration:</b></u><br>
                <?php
                    for ($i = 0; $i < count($billterm); $i++) {

                        echo $billterm[$i]['billterm'] . '<br>';

                    }
                ?>
            </td>
            <td colspan="6"  rowspan="2" style="border-bottom:  1px solid black; border-top:  1px solid black;">
                <p style="margin-bottom:40px; text-align:center;"><b>FOR <?=ucfirst(session('name'));?></b></p><br>
                <div class="text">
                    <span style="float:left; font-size:10px;"><b>DELIVERD BY</b></span>
                    <span style="float:right; font-size:10px;"><b>AUTH. SIGNATORY</b></span>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3"
                style=" border-top: 1px solid black;border-left: none;border-right: none; padding-top: 15px; padding-bottom: 30px;">
                <b>CHECKED BY</b>
            </td>

        </tr>
    </table>
</body>

</html>