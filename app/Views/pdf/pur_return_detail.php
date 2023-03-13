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
                    <?php
                    //echo '<pre>';Print_r($purchase);exit;
                    
                    ?>
                    <th colspan="9">
                        <b>
                            <h3 style="margin: 0;padding-top: 15px;">
                                !! Shree Ganeshaya Namah !!</h3>
                        </b>
                        <br>
                        <h1 style="margin:0;padding:0px"><?=ucfirst(session('name'));?></h1>

                        <br>
                        <h3 style="margin: 0;padding: 0px;"><?=@session('address') ? @session('address') : '';?> <?= !empty(@session('city')) ? ' , '.session('city') : ''?><?= !empty(@session('pin')) ? ' - '.@session('pin') : '' ?> <?= !empty(@session('state_name')) ? ' , '.@session('state_name').'('.session('state_code').')' : '' ?><?=!empty(@session('country')) ? ' , '.@session('country') : '' ?>  </h3>
                        <br>
                        <h3 style="margin: 0;padding:0;">GSTIN : <?=@session('gst')?>&nbsp; / PAN : <?=@session('incomtax_pan')?></h3>
                        <h3 style="margin: 0;padding-bottom: 5px;"></h3>
                    </th>
                </tr>
            </thead>
        <tbody>
            <tr>
                <td rowspan="3" colspan="6"><center><h3>DEBIT NOTE</h3></center> <br>

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
                    <br><?=@$p_return['ship_city']?> &nbsp;<?=@$p_return['ship_pin']?>
                    <br><?=@$p_return['ship_state']?>
                    <br><?=@$p_return['ship_country']?>
                    <br>GSTIN :<?=@$delivery['gst']?>
                    <br>State Code : <?=@$ship_state['state_code']?>
                    <br>State :<?=@$ship_state['name']?>

                </td>
                <td colspan="3">
                    <b>Invoice No : <?=@$p_return['supp_inv']?></b><br>
                    <b>Voucher No : <?=@$p_return['return_no']?></b>
                    <br><b>Invoice Date : <?=user_date(@$p_return['return_date'])?></b>
                    <br>Place Of Supply : <?=@$ship_state['name']?>
                </td>
            </tr>

            <tr>
                <td colspan="4">Broker :<?=@$p_return['broker_name']?></td>
                <td colspan="5">Transporter Name :<?=@$transport['name']?></td>
            </tr>
            <tr>
                <td colspan="3">
                    L.R.No :<?=@$p_return['lr_no']?>
                    <br>Transport :
                </td>
                <td colspan="3">L.R.Date : <?=@$p_return['lr_date']?>
                    <br>Transport Type : <?=@$p_return['transport_mode']?>
                </td>
                <td colspan="3">Weight :<?=@$challan_detail['weight']?>
                    <br>Freight :<?=@$challan_detail['freight']?>
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
             
              $total_qty = 0;
              $i = 1;
              $total = 0.0;
              $igst_amt = 0.0;
              $sub = 0;
              $total_disc_amt = 0.00;
              $total_added_amt = 0.00;
              $discountable_amt = 0.00;

             
                foreach($item as $row)
                {
                    
                    $total_qty +=$row['qty'];
                    $sub = $row['qty'] * $row['rate'];
                    $disc_amt = $sub * $row['item_disc'] / 100;
                    if($row['is_expence'] == 1){
                        $final_sub = $row['rate'];
                    }else{
                        $final_sub = $sub - $disc_amt;                        
                    }
            
                    $total += $final_sub;


                    if($p_return['discount'] > 0)
                    {
                        $total_disc_amt += $row['divide_disc_item_amt'];
                    }
                    else
                    {
                        $total_disc_amt += $row['discount'];
                    }
                    if($p_return['amty'] > 0)
                    {
                        $total_added_amt += $row['added_amt'];
                    }
                    $discountable_amt += $row['sub_total'];


                    

        
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
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;"><?=number_format(@$row['rate'],2)?></td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;"><?=@$row['uom']?></tds>
                <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;">
                <b><?=number_format(@$final_sub,2)?></b></td>
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
                    <b><?=number_format(@$total,2);?></b> </td>
            </tr>
    </table>
        <?php
                            $gst_amt = $p_return['tot_igst'];
                            $round_amt = $p_return['round_diff'];
                            $taxable_amt = $discountable_amt + round($total_added_amt);
                            $grand_total = $taxable_amt + $gst_amt + $round_amt;
                            $tax = json_decode($p_return['taxes']);
            
                            $fin_igst = 0;
                            $fin_sgst = 0;
                            $fin_cgst = 0;
                            if(in_array('igst',$tax)){
                                $fin_igst = $p_return['tot_igst'];
        
                            }
                    
                            if(in_array('sgst',$tax)){
                                $fin_sgst = $p_return['tot_sgst'];
                                $fin_cgst = $p_return['tot_sgst'];
                            }
           
               
                ?>
    <table class="T-border2" style="width:100%">
        <tr>
            <td colspan="6"
                style="border-right: 1px solid black; border-bottom: 1px solid black; border-top: 1px solid black; text-align: start;">
                Narration - <?=@$p_return['other']?></td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">
                Less:Discount <br></td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
            <b>(-)<?=@$total_disc_amt?></b><br></td>

        </tr>
        <tr>
            <td colspan="6"
                style="border-right: 1px solid black; border-bottom: 1px solid black; border-top: 1px solid black; text-align: start;">
               &nbsp;</td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">Add
                Amount: <br>

            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
            <b>(+)<?=round(@$total_added_amt)?></b><br>

            </td>
        </tr>
        <tr>
            <td colspan="6" style="border-right: 1px solid black; border-top: 1px solid black; text-align: start;">
                Bank Name: <?=@$p_return['bank_name'];?>
            </td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">Amount:
                <br>

            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> <?=number_format(@$taxable_amt,2)?><br></td>
        </tr>
        <tr>

            <td colspan="6" style="border-right: 1px solid black; border-top: 1px solid white; text-align: start;">
                ACC.No: <?=@$p_return['bank_ac'];?>
            </td>
            <?php if($fin_igst > 0 ) {?>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;"><?=@$p_return['igst_acc_name']?>:
                <br>
            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> <b><?=number_format(@$p_return['tot_igst'],2)?></b><br>
            <?php }else{?>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;"><?=@$p_return['cgst_acc_name']?>:<br>
            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> <b><?=number_format(@$p_return['tot_cgst'],2)?></b><br>

            <?php }?>

            </td>
        </tr>
        <tr>

            <td colspan="6" style="border-right: 1px solid black; border-top: 1px solid white; text-align: start;">
                IFSC CODE: <?=@$p_return['bank_ifsc'];?>
            </td>
            <?php if($fin_igst > 0 ) {?>

            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;"></td>
            <td colspan="1" style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> 0</td>
            <?php }else {?>

            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;"><?=@$p_return['sgst_acc_name']?>:<br>
            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> <b><?=number_format(@$p_return['tot_sgst'],2)?></b><br>
            <?php } ?>
        </tr>
        
        <tr>
            <td colspan="6" style="border-right: 1px solid black; border-top: 1px solid black; text-align: start;">
                Due Days: <?=@$p_return['due_days'];?>
            </td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">Round
                Off:: <br>

            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> <b><?=@$p_return['round_diff']?></b><br>
            </td>

        </tr>
        
        <tr>
            <td colspan="6" style="border-right: 1px solid black; border-top: 1px solid black; text-align: start;">
                RS In Word: <b><?php 
                    $gtotal  = round($grand_total); 
                    echo strtoupper(inword($grand_total)); 
                ?></b>
            </td>
            <td colspan="2"
                style="border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; font-size:15px;">
                Grand total:
            </td>
            <td colspan="1"
                style=" border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;text-align: right; font-size:20px;">
                <b><?=number_format(@$grand_total,2);?></b>


            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid black; border-top: 1px solid black;"> <b>HSN/SAC</b></td>
            <td style="text-align: start; border-top: 1px solid black; border-bottom: 1px solid black;"><b>Taxable Value</b>
            </td>
            <?php if($fin_igst > 0 ) {?>

            <td colspan="2" style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
            <b>Tax</b>
            </td>
            <?php }else{ ?>
            <td colspan="2" style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
            <b>Central Tax</b>
            </td>
            <td colspan="2" style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
            <b>State Tax<b>
            </td>
            <?php } ?>
            <td style=" border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;"><b>Tax
             Amount</b>
            </td>
            <td colspan = "4" rowspan="4">&nbsp;</td>

          

        </tr>
        <tr>
            <td style="border-bottom: 1px solid black; border-right: 1px solid black;"></td>
            <td style="text-align: center; border-bottom: 1px solid black;"></td>
            <?php if($fin_igst > 0 ) {?>
            <td style="text-align: center; border-bottom: 1px solid black;">
                Rate
            </td>
            <td style="text-align: center; border-bottom: 1px solid black;">
                Amount
            </td>
            <?php }else {?>
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
            <?php } ?>
            <td style="border-bottom: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
        </tr>
        <?php
                        $total_igst =0;
                        $total_cgst =0;
                        $total_sgst =0;
                        $total_sub =0;
                        
                        $arr = array();
                        $total_taxable = 0;

                        foreach($item as $row){
                            if($row['hsn'] != ''){
                                
                                // $item_disc = 0;
                                // $total_taxable = ($row['qty'] * $row['rate']);

                                // if(!empty($row['item_disc'])){
                                //     $item_disc = $total_taxable * ($row['item_disc']/100) ;
                                // }
                                
                                $total_taxable = $row['sub_total']; 
                            
                                $arr[$row['hsn']]['taxable'] = (@$arr[$row['hsn']]['taxable'] ? @$arr[$row['hsn']]['taxable'] : 0) + $total_taxable;
                                $arr[$row['hsn']]['igst'] = $row['igst'];
                                $arr[$row['hsn']]['cgst'] = $row['cgst'];
                                $arr[$row['hsn']]['sgst'] = $row['sgst'];
                        
                                $arr[$row['hsn']]['igst_amount'] =  (@$arr[$row['hsn']]['igst_amount'] ? $arr[$row['hsn']]['igst_amount'] : 0) + (float)$row['igst_amt'];
                                $arr[$row['hsn']]['cgst_amount'] = (@$arr[$row['hsn']]['cgst_amount'] ? $arr[$row['hsn']]['cgst_amount'] : 0) + (float)$row['cgst_amt'];
                                $arr[$row['hsn']]['sgst_amount'] = (@$arr[$row['hsn']]['sgst_amount'] ? $arr[$row['hsn']]['sgst_amount'] : 0) + (float)$row['sgst_amt'];
                            
                                $total_igst +=  $arr[$row['hsn']]['igst_amount'];
                                $total_cgst +=   $arr[$row['hsn']]['cgst_amount'];
                                $total_sgst +=   $arr[$row['hsn']]['sgst_amount'];

                                //$total_taxable = 0;
                               
                               // $sub = $row['qty'] * $row['rate'] - $item_disc - $row['discount'];
                                $total_sub += $total_taxable;

                            }else{
                            
                            }

                        }
                        $total_igst =0;
                        $total_cgst =0;
                        $total_sgst =0;
                        
                        foreach($arr as $key=>$value)
                        {
                            
                    ?>
        <tr>
            <td style="border-bottom: 1px solid black; border-top: 1px solid black;"><?=@$key;?></td>
            <td style="text-align: start; border-top: 1px solid black; border-bottom: 1px solid black;"><?=@$value['taxable'];?></td>

            <?php if($fin_igst > 0 ) {?>

            <td style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
            <?=@$value['igst'];?>%</td>

            <td style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
            <?=number_format(@$value['igst_amount'],2);?>
            </td>

            <?php 
            $total_igst +=@$value['igst_amount'];
            ?>

            <?php }else{ ?>
            
            <td style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
            <?=@$value['cgst'];?>%</td>
            <td style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
            <?=number_format(@$value['cgst_amount'],2);?>
            </td>


            <td style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
            <?=@$value['sgst'];?>%</td>

            <td style=" border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
            <?=number_format(@$value['sgst_amount'],2);?>
            </td>

            <?php 
            $total_cgst +=@$value['cgst_amount'];
            $total_sgst +=@$value['sgst_amount'];
            ?>

            <?php } ?>
            <td  style="border-bottom: 1px solid black; border-right: 1px solid black;">&nbsp;</td>

        </tr>
        <?php } ?>
        <tr>
            <td style="border-bottom: 1px solid black; border-top: 1px solid black;">
                <b>Total</b></td>
            <td style="text-align: start; border-top: 1px solid black; border-bottom: 1px solid black;">
            <b><?=number_format(@$total_sub,2);?></b>
            </td>
            
            <?php if($fin_igst > 0 ) {?>

            <td colspan="2" style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
                <b><?=number_format(@$total_igst,2);?></b>
            </td>
            <td style=" border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
                <?=number_format(@$total_igst,2);?></td>

            <?php }else{?>

            <td colspan="2" style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
                <b><?=number_format(@$total_cgst,2);?></b>
            </td>

            <td colspan="2" style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
                <b><?=number_format(@$total_sgst,2);?></b>
            </td>
            <td style=" border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
                <b><?=number_format((@$total_cgst + @$total_sgst) ,2);?></b></td>

            <?php } ?>


        </tr>

    </table>

    <table class="T-border2" style="width:100%">
        <tr>
            <td colspan="3"
                style=" border-right: 1px solid black; border-top: 1px solid black;  padding-top: 0px; padding-bottom: 30px;">
                <u><b>Declaration: </b></u><br>
                <?php
                for($i=0;$i<count($billterm);$i++)
                {
                    echo $billterm[$i]['billterm'].'<br>';
                }
                ?>
            </td>
            <td colspan="6"  rowspan="2" style="border-bottom:  1px solid black; border-top:  1px solid black;">
                <p style="margin-bottom:40px; text-align:center;"><b>FOR <?=ucfirst(session('name'));?></b></p><br>
                <div class="text">
                    <span style="float:center; margin-left:30%; font-size:10px;"><b>AUTH. SIGNATORY</b></span>
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