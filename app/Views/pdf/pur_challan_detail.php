<html>

<head>
    <style>
    table,
    td,
    th {

        border: 1px solid;
        padding: 5px;

    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .center {
        margin-left: auto;
        margin-right: auto;
    }
    </style>
    <title>Invoice</title>
</head>

<body>

    <table class="center" border="1">
        <tr>
            <td colspan="9">
                <h4 style="text-align: center;">!! Shree Ganeshaya Namah !!</h4>
                <h1 style="text-align: center;"> <?=ucfirst(@$account['name']);?></h1>
                <h3 style="text-align: center;"><?=@$account['gst_add'] ? @$account['gst_add'] : @$account['address'];?>
                </h3>
                <h3 style="text-align: center;"> M.<?=@$account['mobile']?> / <?=@$account['whatspp']?></h3>
                <h3 style="text-align: center;">GSTIN : <?=@$account['gst']?></h3>
            </td>
        </tr>
        <tr>
            <td rowspan="4" colspan="5" style="text-align: right;">
                <h3>TAX INVOICE&ensp;&ensp;</h3>
            </td>
            <td colspan="1" style="border-bottom: 1px solid transparent;"></td>
            <td colspan="3" style="border-bottom: 1px solid transparent;"></td>
        </tr>
        <tr>
            <td colspan="1"></td>
            <td colspan="3">Original For Receipient</td>
        </tr>
        <tr>
            <td colspan="1"></td>
            <td colspan="3">Duplicate For Supplier/Transport</td>
        </tr>
        <tr>
            <td colspan="1"></td>
            <td colspan="3">Triplicate For Supplier</td>
        </tr>
        <tr>
            <td colspan="5">
                Billed To &ensp;:&ensp;<?=@$account['name']?>
                <br> &emsp;&emsp;&emsp;&emsp;&emsp;<?=@$account['address']?>
                <!-- <br> &emsp;&emsp;&emsp;&emsp;&emsp;DINDOLI KHARWASA ROAD -->
                <br> &emsp;&emsp;&emsp;&emsp;&emsp;<?=@$account['city']?>
                <br>GSTIN &ensp;:&ensp;<?=@$account['gst']?>
                <br>State Code &ensp;:&ensp;
                <br>State &ensp; : &ensp;<?=@$account['name']?>
            </td>
            <!-- <td colspan="3">
                Delivery AC :


                &ensp;:&ensp;<?=@$account['name']?>
                <br> &emsp;&emsp;&emsp;&emsp;&emsp;<?=@$delivery['address']?>
                <!-- <br> &emsp;&emsp;&emsp;&emsp;&emsp;DINDOLI KHARWASA ROAD 
                <br> &emsp;&emsp;&emsp;&emsp;&emsp;<?=@$delivery['city']?>
                <br>GSTIN &ensp;:&ensp;<?=@$delivery['gst']?>
                <br>State Code &ensp;:&ensp;
                <br>State &ensp; : &ensp;<?=@$delivery_state['name']?>
            </td> -->
            <td colspan="4">
                Challan No &ensp; : &ensp;<?=@$purchasechallan['challan_no']?>
                <br> Challan Date &ensp; : &ensp;<?=@$purchasechallan['challan_date']?>
              
                <br> Place Of Supply &ensp; : &ensp;<?=@$account['address']?>
            </td>
        </tr>
        <tr>

            <td colspan="9">Broker &ensp;:


                &ensp;<?=@$purchasechallan['broker_name']?>
            </td>
        </tr>
        <tr>

            <td colspan="3" style="border-right: 1px solid transparent;border-bottom: 1px solid transparent;">L.R.No
                &ensp;:&ensp;<?=@$purchasechallan['lr_no']?>
            </td>
            <td colspan="3" style="border-right: 1px solid transparent;border-bottom: 1px solid transparent;">L.R.Date
                &ensp;:&ensp;<?=@$purchasechallan['lr_date']?>
            </td>
            <td colspan="3" style="border-bottom: 1px solid transparent;">
            <!-- Weight
                &ensp;:&ensp;<?=@$purchasechallan['weight']?> -->
            </td>
        </tr>
        <tr>

            <td colspan="3" style="border-right: 1px solid transparent;border-top: 1px solid transparent;">Transport
                &ensp;:&ensp;<?=@$purchasechallan['transport_name']?>
            </td>
            <td colspan="3" style="border-right: 1px solid transparent;border-top: 1px solid transparent;">Transport
                Type &ensp;:&ensp;<?=@$purchasechallan['transport_mode']?>
            </td>
            <td colspan="3" style="border-top: 1px solid transparent;">
            <!-- Freight
                &ensp;:&ensp;<?=@$challan_detail['freight']?> -->
            </td>
        </tr>
        <tr>
            <td>SI No.</td>
            <td>Description of Goods</td>
            <td>HSN/SAC</td>
            <td>GST Rate</td>
            <td>Quantity</td>
            <td>Rate</td>
            <td>per</td>
            <td>Disc. %</td>
            <td>Amount</td>
        </tr>
        <?php 
              $i = 1;
              $total_qty = 0;
      
              $total = 0.0;
              $igst_amt = 0.0;
              $sub = 0;
      
              if($purchasechallan['discount'] > 0){
                  
              }
              
                foreach($item as $row)
                {
                    $total_qty +=$row['qty'];

                    $sub = $row['qty'] * $row['rate'];
                    $disc_amt = $sub * $row['item_disc'] / 100;
            
                    $final_sub = $sub - $disc_amt;
                    $total += $final_sub;
        
            ?>
        <tr>
            <td class="tdborder"><?=@$i?></td>
            <td class="tdborder"><b><?=@$row['name']?></b> </td>
            <td class="tdborder"><?=@$row['hsn']?></td>
            <td class="tdborder" style="text-align: right;"><?=@$row['igst']?>% </td>
            <td class="tdborder" style="text-align: right;"><b><?=@$row['qty']?><?=@$row['uom']?></b> </td>
            <td class="tdborder" style="text-align: right;"><?=@$row['rate']?></td>
            <td class="tdborder" style="text-align: right;"><?=@$row['uom']?></td>
            <td class="tdborder" style="text-align: right;"><?=@$row['item_disc']?></td>
            <td style="text-align: right;" class="tdborder"><b><?=@$final_sub?></b></td>
        </tr>
        <?php 
            $i++;
                   
                }
           
            ?>
        <tr>
            <td colspan="4" style="text-align: right;">Total</td>
            <td style="text-align: right;"><?=@$total_qty;?></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right;"><?=@$total;?></td>

        </tr>
        <tr>

            <td colspan="9">CHALLAN NO &ensp;-&ensp;<?=@$purchasechallan['challan_no']?>
                <br>DATE &ensp;-&ensp; <?=@$purchasechallan['challan_date']?>
            </td>
        </tr>
        <tr>
            <td colspan="5" rowspan="4">
                <?=@$bank_detail['name'];?>
                <br>ACC.No:<?=@$account['bank_ac_no'];?>
                <br>IFSC CODE:<?=@$account['bank_ifsc'];?>

            </td>
            <td colspan="2" style="border-bottom: 1px solid transparent;"></td>
            <td colspan="2" style="border-bottom: 1px solid transparent;"></td>
        </tr>


        <?php
                     if ($purchasechallan['disc_type'] == '%') {
                        $discount_amount = ($total * ($purchasechallan['discount'] / 100));
                        $disc_avg_per = $discount_amount / $total;
                    } else {
                        $disc_avg_per = $purchasechallan['discount'] / $total;
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
            
                    $total = 0;
                    $igst_amt = 0;
                    $grand_total =0;
                    $total_discount=0;
                    $total_add=0;
                
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
            
                        $total_discount +=$discount_amt; 
                        $total_add +=$add_amt; 
                
                    }
                    
                    $grand_total = $total;
                    $tax = json_decode($purchasechallan['taxes']);
            
                    $fin_igst = 0;
                    $fin_sgst = 0;
                    $fin_cgst = 0;
            
                    if(in_array('igst',$tax)){
                        $fin_igst = $igst_amt;
                    }
            
                    if(in_array('sgst',$tax)){
                        $fin_sgst = $igst_amt/2;
                        $fin_cgst = $igst_amt/2;
                    }
            
                    ?>
        <tr>
            <td colspan="2"> Less: Discount </td>
            <td colspan="2" style="text-align: right;"><b>(-)<?=@$total_discount?></b></td>

        </tr>
        <tr>
            <td colspan="2"> Add: Amount : </td>
            <td colspan="2" style="text-align: right;"><b>(+)<?=@$total_add?></b></td>

        </tr>
        <tr>
            <td colspan="2"> Amount : </td>
            <td colspan="2" style="text-align: right;"><?=@$total?></td>
        </tr>
        <tr>
            <td colspan="5" rowspan="5">
                <table>

                    <tr>
                        <td colspan="" style="border-bottom: 1px solid transparent;">
                            HSN/SAC
                        </td>
                        <td style="border-bottom: 1px solid transparent;">
                            Taxable Value
                        </td>

                        <?php if($fin_igst > 0 ) {?>
                        <td colspan="2">
                            Tax
                        </td>
                        <?php 
                    }else{
                    ?>
                        <td colspan="2">
                            Central Tax
                        </td>
                        <td colspan="2">
                            State Tax
                        </td>
                        <?php } ?>
                        <td style="border-bottom: 1px solid transparent;">
                            Total Tax Amount
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <?php if($fin_igst > 0 ) {?>
                        <td>
                            Rate
                        </td>
                        <td>
                            Amount
                        </td>
                        <?php }else{?>
                        <td>
                            Rate
                        </td>
                        <td>
                            Amount
                        </td>
                        <td>
                            Rate
                        </td>
                        <td>
                            Amount
                        </td>
                        <td>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                $total_igst =0;
                $total_cgst =0;
                $total_sgst =0;
                $total_sub =0;
                // foreach($item as $row)
                // {
                   
                //     $taxable_value = @$row['qty'] * @$row['rate'];
                //     $igst = $row['igst'];
                //     $igst_amount = @$taxable_value * @$igst/100;
                //     $total_igst +=  $igst_amount;
                //     $cgst = $row['cgst'];
                //     $cgst_amount = @$taxable_value * @$cgst/100;
                //     $total_cgst +=  $cgst_amount;
                //     $sgst = $row['sgst'];
                //     $sgst_amount = @$taxable_value * @$sgst/100;
                //     $total_sgst +=  $sgst_amount;

                    

                //     $total_qty +=$row['qty'];

                //     $sub = $row['qty'] * $row['rate'];
                //     $total_sub += $sub;
                //     $disc_amt = $sub * $row['item_disc'] / 100;
            
                //     $final_sub = $sub - $disc_amt;
                //     $total += $final_sub;
                $arr = array();
                $total_taxable = 0;
                foreach($item as $row){
                    if($row['hsn'] != ''){
                        
                        // $taxable = $row['qty'] * $row['rate'];
                        // $arr[$row['hsn']]['total_taxable'] += $taxable; 

                        $total_taxable = (@$arr[$row['hsn']]['taxable'] ? @$arr[$row['hsn']]['taxable'] : 0) + ($row['qty'] * $row['rate']);
                        // echo '<br>';print_r($row['hsn']);
                        // echo '<br>';print_r($total);
                        // echo '<br>';print_r(@$arr[$row['hsn']]['taxable']);
                        $arr[$row['hsn']]['taxable'] = $total_taxable;
                        $arr[$row['hsn']]['igst'] = $row['igst'];
                        $arr[$row['hsn']]['cgst'] = $row['cgst'];
                        $arr[$row['hsn']]['sgst'] = $row['sgst'];
                   
                        $arr[$row['hsn']]['igst_amount'] = @$total_taxable * $arr[$row['hsn']]['igst']/100;
                        $arr[$row['hsn']]['cgst_amount'] = @$total_taxable * $arr[$row['hsn']]['cgst']/100;
                        $arr[$row['hsn']]['sgst_amount'] = @$total_taxable * $arr[$row['hsn']]['sgst']/100;
                        $total_igst +=  $arr[$row['hsn']]['igst_amount'];
                        $total_cgst +=   $arr[$row['hsn']]['cgst_amount'];
                        $total_sgst +=   $arr[$row['hsn']]['sgst_amount'];
                        // $arr[$row['hsn']]['total_igst'] = $total_igst;
                        // $arr[$row['hsn']]['total_cgst'] = $total_cgst;
                        // $arr[$row['hsn']]['total_sgst'] = $total_sgst;
                        $sub = $row['qty'] * $row['rate'];
                        $total_sub += $sub;

                    }else{
                        // $arr['emty']
                    }
                }
               //print_r($arr);exit;

                // for($i=0;$i<count($item);$i++)
                // {
                //     $curren_hsn = $item[$i]['hsn'];
                //     $old_hsn = $item[$i-1]['hsn'];

                //     {
                //         $taxable_value = $item[$i]['qty'] *  $item[$i]['rate'];
                //         $total_taxable_value += $taxable_value;
                //     }
                foreach($arr as $key=>$value)
                {
                    //print_r($value['igst']);exit;
            ?>
                    <tr>
                        <td colspan="">
                            <?=@$key;?>
                        </td>

                        <td style="text-align: right;">
                            <?=@$value['taxable'];?>
                        </td>
                        <?php if($fin_igst > 0 ) {?>
                        <td style="text-align: right;">
                            <?=@$value['igst'];?>%
                        </td>
                        <td style="text-align: right;">
                            <?=@$value['igst_amount'];?>
                        </td>
                        <!-- <td style="text-align: right;">
            <?=@$value['igst_amount'];?>
            </td> -->
                        <?php }else{?>
                        <td style="text-align: right;">
                            <?=@$value['cgst'];?>%
                        </td>
                        <td style="text-align: right;">
                            <?=@$value['cgst_amount'];?>
                        </td>
                        <td style="text-align: right;">
                            <?=@$value['sgst'];?>%
                        </td>
                        <td style="text-align: right;">
                            <?=@$value['sgst_amount'];?>
                        </td>
                        <td style="text-align: right;">
                            <?=@$value['sgst_amount'] + @$value['cgst_amount'];?>
                        </td>
                        <?php } ?>

                    </tr>
                    <?php } ?>>
                    <tr>
                        <td colspan="" style="text-align: right;">
                            Total
                        </td>
                        <td style="text-align: right;">
                            <?=@$total_sub;?>
                        </td>
                        <td>

                        </td>
                        <?php if($fin_igst > 0 ) {?>
                        <td style="text-align: right;">
                            <?=@$total_igst;?>
                        </td>

                        <?php
                }else
                {
                ?>
                        <td style="text-align: right;">
                            <?=@$total_cgst;?>
                        </td>

                        <td>

                        </td>
                        <td style="text-align: right;">
                            <?=@$total_sgst;?>
                        </td>
                        <?php
                }
                ?>
                        <!-- <td>
                    71.28
                </td> -->
                        <td style="text-align: right;">
                            <?=@$total;?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan="2" style="border-bottom: 1px solid transparent;"></td>
            <td colspan="2" style="border-bottom: 1px solid transparent;"></td>
        </tr>
        <?php if($fin_igst > 0 ) {?>
        <tr>
            <td colspan="2">IGST : </td>
            <td colspan="2" style="text-align: right;"><b><?=@$fin_igst?></b></td>
        </tr>
        <?php }else{?>
        <tr>
            <td colspan="2"> CGST : </td>
            <td colspan="2" style="text-align: right;"><b><?=@$fin_cgst?></b></td>
        </tr>
        <tr>
            <td colspan="2">SGST : </td>
            <td colspan="2" style="text-align: right;"><b><?=@$fin_sgst?></b></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="2"> Round Off : </td>
            <td colspan="2" style="text-align: right;"><b><?=@$purchasechallan['round_diff']?></b></td>
        </tr>
        <tr>
            <td colspan="2"> Grand Total : </td>
            <td colspan="2" style="text-align: right;"><b><?=@$grand_total;?></b></td>
        </tr>

        <!-- <tr>
            <td colspan="9">
                Due Days:<?=@$challan['due_days'];?>
            </td>
        </tr> -->
        <tr>
            <td colspan="5" style="border-right: 1px solid transparent;">
                RS In Word : <?php echo inword($total);?>
            </td>
            <td colspan="4">
                Grand Total : <?= @$total;?>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="border-right: 1px solid transparent;">
                &ensp; <h5><u>TERMS & CONDITIONS:</u></h5><br>
                <?php
                for($i=0;$i<count($billterm);$i++)
                {
                echo $i+1;
                echo ')';
                echo $billterm[$i]['billterm'].'<br>';
            
                }
                ?>
                <!-- 1) SUBJECT TO SURAT JURISDICTION.<br>
                2) GOODS HAVE BEEN SOLD && DESPACHED AT THE ENTIRE RISK OF THE PURCHASER.<br>
                3) COMPLAINTS IF ANY REGARDING THIS INVOICE MUST BE INFORMED IN WRITING WITHIN 48 HOURS.<br>
                4) INTREST @24% WILL BE CHARGED AFTER DUE DATE.<br> -->
            </td>
            <td colspan="4">
                <b>FOR LAXMI ARUN CREATION</b>
            </td>

        </tr>
        <tr>
            <td colspan="3" style="border-right: 1px solid transparent;">
                <h3>&ensp;&ensp;CHECKED BY</h3>
            </td>
            <td colspan="3" style="border-right: 1px solid transparent;">
                <h3>DELIVERD BY</h3>
            </td>
            <td colspan="3">
                <h3>AUTH.SIGNATORY</h3>
            </td>
        </tr>

    </table>
</body>

</html>