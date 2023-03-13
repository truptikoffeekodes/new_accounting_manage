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
            <td colspan="7">
                <h4 style="text-align: center;">!! Shree Ganeshaya Namah !!</h4>
                <h1 style="text-align: center;"> <?=ucfirst(@$account['name']);?></h1>
                <h3 style="text-align: center;"><?=@$account['gst_add'] ? @$account['gst_add'] : @$account['address'];?>
                </h3>
                <h3 style="text-align: center;"> M.<?=@$account['mobile']?> / <?=@$account['whatspp']?></h3>
                <h3 style="text-align: center;">GSTIN : <?=@$account['gst']?></h3>
            </td>
        </tr>
        <tr>
            <td rowspan="4" colspan="4" style="text-align: right;">
                <h3>TAX INVOICE&ensp;&ensp;</h3>
            </td>
            <td colspan="1" style="border-bottom: 1px solid transparent;"></td>
            <td colspan="2" style="border-bottom: 1px solid transparent;"></td>
        </tr>
        <tr>
            <td colspan="1"></td>
            <td colspan="2">Original For Receipient</td>
        </tr>
        <tr>
            <td colspan="1"></td>
            <td colspan="2">Duplicate For Supplier/Transport</td>
        </tr>
        <tr>
            <td colspan="1"></td>
            <td colspan="2">Triplicate For Supplier</td>
        </tr>
        <tr>
            <td colspan="3">
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


                &ensp;:&ensp;<?=@$emspdelivery['name']?>
                <br> &emsp;&emsp;&emsp;&emsp;&emsp;<?=@$delivery['address']?>
                 <br> &emsp;&emsp;&emsp;&emsp;&emsp;DINDOLI KHARWASA ROAD 
                <br> &emsp;&emsp;&;&emsp;&emsp;<?=@$delivery['city']?>
                <br>GSTIN &ensp;:&ensp;<?=@$delivery['gst']?>
                <br>State Code &ensp;:&ensp;
                <br>State &ensp; : &ensp;<?=@$delivery_state['name']?>
            </td> -->
            <td colspan="4">
                <!-- Return No &ensp; : &ensp;<?=@$s_return['return_no']?>
                <br> Return Date &ensp; : &ensp;<?=@$s_return['return_date']?> -->
                <br> Invoice No &ensp; : &ensp;<?=@$general['invoice_no']?>
                <br> Invoice Date &ensp;: &ensp;<?=@$general['doc_date']?>
                <br> Place Of Supply &ensp; : &ensp;<?=@$account['address']?>
            </td>
        </tr>
        <tr>

            <td colspan="7">Broker &ensp;:


                &ensp;<?=@$general['broker_name']?>
            </td>
        </tr>
        <!-- <tr>

            <td colspan="3" style="border-right: 1px solid transparent;border-bottom: 1px solid transparent;">L.R.No
                &ensp;:&ensp;<?=@$s_return['lr_no']?>
            </td>
            <td colspan="3" style="border-right: 1px solid transparent;border-bottom: 1px solid transparent;">L.R.Date
                &ensp;:&ensp;<?=@$s_return['lr_date']?>
            </td>
            <td colspan="3" style="border-bottom: 1px solid transparent;">Weight
                &ensp;:&ensp;<?=@$s_return['weight']?>
            </td>
        </tr> -->
        <!-- <tr>

            <td colspan="3" style="border-right: 1px solid transparent;border-top: 1px solid transparent;">Transport
                &ensp;:&ensp;<?=@$s_return['transport_name']?>
            </td>
            <td colspan="3" style="border-right: 1px solid transparent;border-top: 1px solid transparent;">Transport
                Type &ensp;:&ensp;<?=@$s_return['transport_mode']?>
            </td>
            <td colspan="3" style="border-top: 1px solid transparent;">Freight
                &ensp;:&ensp;<?=@$s_return['freight']?>
            </td>
        </tr> -->
        <tr>
            <td>SI No.</td>
            <td>Particular</td>
            <td>Amount</td>
            <td>Igst</td>
            <td>Cgst</td>
            <td>Sgst</td>
            <td>Total Amount</td>
        </tr>
        <?php 
              $i = 1;
              $total_qty = 0;
      
              $total = 0.0;
              $igst_amt = 0.0;
              $sub = 0;
      
              if($general['discount'] > 0){
                  
              }
              
                foreach($acc as $row)
                {
                    $sub = $row['amount'];
                   
                    // $disc_amt = $sub * $row['item_disc'] / 100;
            
                    // $final_sub = $sub - $disc_amt;
                     $total += $sub;
        
            ?>
        <tr>
            <td class="tdborder"><?=@$i?></td>
            <td class="tdborder"><b><?=@$row['account_name']?>(<?=@$row['code']?>)</b> </td>
            <td class="tdborder"><?=@$row['amount']?></td>
            <td class="tdborder" style="text-align: right;"><?=@$row['igst']?>% </td>
            <td class="tdborder" style="text-align: right;"><b><?=@$row['cgst']?>%</b> </td>
            <td class="tdborder" style="text-align: right;"><?=@$row['sgst']?>%</td>
            <td class="tdborder" style="text-align: right;"><?=@$row['amount']?></td>
           
        </tr>
        <?php 
            $i++;
                   
                }
           
            ?>
        <tr>
            <td colspan="3" style="text-align: right;">Total</td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right;"><?=@$total;?></td>

        </tr>
        <tr>

            <td colspan="7">INVOICE NO &ensp;-&ensp;<?=@$general['invoice_no']?>
                <br>DATE &ensp;-&ensp; <?=@$general['invoice_date']?>
            </td>
        </tr>
        <tr>
            <td colspan="3" rowspan="7">
                <?=@$bank_detail['name'];?>
                <br>ACC.No:<?=@$account['bank_ac_no'];?>
                <br>IFSC CODE:<?=@$account['bank_ifsc'];?>

            </td>
            <td colspan="2" style="border-bottom: 1px solid transparent;"></td>
            <td colspan="2" style="border-bottom: 1px solid transparent;"></td>
        </tr>


        <?php
                   
                
                    // for ($i = 0; $i < count($acc); $i++) {
                    //     $sub = $acc[$i]['amount'];
                
                    //     if ($invoice['discount'] > 0) {
                    //         $discount_amt = $sub * $disc_avg_per;
                    //         $final_sub = $sub - $discount_amt;
                    //         $add_amt = $sub * $add_amt_per;
                    //     } else {
                
                    //         $discount_amt = $sub * $acc[$i]['item_disc'] / 100;
                    //         $final_sub = $sub - $discount_amt;
                    //         $add_amt = $final_sub * $add_amt_per;
                    //     }
                
                    //     $final_tot = $final_sub + $add_amt;    
                    //     $igst_amt += $final_tot * $acc[$i]['igst'] / 100;    
                    //     $total += $final_tot;
            
                    //     $total_discount +=$discount_amt; 
                    //     $total_add +=$add_amt; 
                
                    // }
                    
                    //$grand_total = $total;
                    $tax = json_decode($general['taxes']);
            
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
                    $grand_total = $total - $general['discount'] + $general['amty'] + @$general['tot_igst'];
            
                    ?>
        <tr>
            <td colspan="2"> Less: Discount </td>
            <td colspan="2" style="text-align: right;"><b>(-)<?=@$general['discount'];?></b></td>

        </tr>
        <tr>
            <td colspan="2"> Add: Amount : </td>
            <td colspan="2" style="text-align: right;"><b>(+)<?=@$general['amty']?></b></td>

        </tr>
        <?php
        $tax = json_decode($general['taxes']);
        //foreach ($tax as $row) {

            if ($tax[0] == 'igst' && session('state') != @$general['acc_state']) {
            
        ?>
         <tr>
            <td rowspan="2" colspan="2"> Igst : </td>
            <td rowspan="2" colspan="2" style="text-align: right;"><?=@$general['tot_igst'];?></td>
        </tr>
        <?php
            }
            else
            {
        ?>
         <tr>
            <td colspan="2"> Cgst : </td>
            <td colspan="2" style="text-align: right;"><?=@$general['tot_cgst'];?></td>
        </tr>
        <tr>
            <td colspan="2"> Sgst : </td>
            <td colspan="2" style="text-align: right;"><?=@$general['tot_sgst'];?></td>
        </tr>
        <?php
            }
        ?>
        <tr>
            <td colspan="2"> Amount : </td>
            <td colspan="2" style="text-align: right;"><?=@$total?></td>
        </tr>
       
       
       
        <tr>
            <td colspan="2"> Grand Total : </td>
            <td colspan="2" style="text-align: right;"><b><?=@$grand_total;?></b></td>
        </tr>

        <!-- <tr>
            <td colspan="9">
                Due Days:<?=@$s_return['due_days'];?>
            </td>
        </tr> -->
        <tr>
            <td colspan="4" style="border-right: 1px solid transparent;">
                RS In Word : <?php echo inword($total);?>
            </td>
            <td colspan="3">
                Grand Total : <?= @$total;?>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="border-right: 1px solid transparent;">
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
            <td colspan="3">
                <b>FOR LAXMI ARUN CREATION</b>
            </td>

        </tr>
        <tr>
            <td colspan="2" style="border-right: 1px solid transparent;">
                <h3>&ensp;&ensp;CHECKED BY</h3>
            </td>
            <td colspan="2" style="border-right: 1px solid transparent;">
                <h3>DELIVERD BY</h3>
            </td>
            <td colspan="3">
                <h3>AUTH.SIGNATORY</h3>
            </td>
        </tr>

    </table>
</body>

</html>