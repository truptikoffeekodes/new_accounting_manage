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
                <th colspan="8">
                    <b>
                        <h3 style="margin: 0;padding-top: 15px;">
                            !! Shree Ganeshaya Namah !!</h3>
                    </b>
                    <br>
                    <h1 style="margin:0;padding:0px"><?=ucfirst(session('name'));?></h1>

                    <br>
                    <h3 style="margin: 0;padding: 0px;"><?=@session('address') ? @session('address') : '';?>
                        <?= !empty(@session('city')) ? ' , '.session('city') : ''?><?= !empty(@session('pin')) ? ' - '.@session('pin') : '' ?>
                        <?= !empty(@session('state_name')) ? ' , '.@session('state_name').'('.session('state_code').')' : '' ?><?=!empty(@session('country')) ? ' , '.@session('country') : '' ?>
                    </h3>
                    <br>
                    <h3 style="margin: 0;padding:0;">GSTIN : <?=@session('gst')?>&nbsp; / PAN :
                        <?=@session('incomtax_pan')?></h3>
                    <h3 style="margin: 0;padding-bottom: 5px;"></h3>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="3" colspan="5">
                    <center>
                        <?php 
                        if($invoice['v_type'] == 'return')
                        {
                        ?>
                        <h3>CREDIT NOTE</h3>
                        <?php
                        }
                        else
                        {
                        ?>
                        <h3>TAX INVOICE</h3>
                        <?php
                        }
                        ?>
                    </center> <br>

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
                    <br><?=@$invoice['ship_city']?> &nbsp;<?=@$invoice['ship_pin']?>
                    <br><?=@$invoice['ship_state']?>
                    <br><?=@$invoice['ship_country']?>
                    <br>GSTIN :<?=@$delivery['gst']?>
                    <br>State Code : <?=@$ship_state['state_code']?>
                    <br>State :<?=@$ship_state['name']?>

                </td>
                <td colspan="2">
                    <b>Invoice No : <?=@$invoice['supp_inv']?></b><br>
                    <b>Voucher No : <?=@$invoice['invoice_no']?></b>
                    <br><b>Invoice Date : <?=user_date(@$invoice['invoice_date'])?></b>
                    <br>Place Of Supply : <?=@$ship_state['name']?>
                </td>

            </tr>



            <tr>
                <td colspan="4">Broker :<?=@$invoice['broker_name']?></td>
                <td colspan="4">Transporter Name :<?=@$transport['name']?></td>
            </tr>
            <tr>
                <td colspan="3">
                    L.R.No :<?=@$invoice['lr_no']?>
                    <br>Transport :
                </td>
                <td colspan="3">L.R.Date : <?=@$invoice['lr_date']?>
                    <br>Transport Type : <?=@$invoice['transport_mode']?>
                </td>
                <td colspan="2">Weight :<?=@$challan_detail['weight']?>
                    <br>Freight :<?=@$challan_detail['freight']?>
                </td>

            </tr>
        </tbody>
    </table>

    <table class="T-border2" style="width:100%">
        <tbody>
            <tr>
                <th>#</th>
                <th>Particular</th>
                <th>Amount</th>
                <th>IGST</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>Total Amount</th>
                <th>Remark</th>

            </tr>

            <?php 
              $i = 1;
              $total_qty = 0;
      
              $total = 0.0;
              $igst_amt = 0.0;
              $sub = 0;
      
              if($invoice['discount'] > 0){
                  
              }

                foreach($acc as $row)
                {
                    $sub_total=$row['amount'];
                    $total += $sub_total;
        
            ?>

            <tr>
                <td style="border-right: 1px solid black; border-top: 1px solid black;"><?=@$i?></td>
                <td style="border-right: 1px solid black; border-top: 1px solid black;width: 167px;">
                    <?=@$row['account_name']?>(<?=@$row['code']?>)
                </td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;">
                    <?=@$row['amount']?>
                </td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;">
                    <?=@$row['igst']?>%</td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;">
                    <b><?=@$row['cgst']?></b>
                </td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;">
                    <b><?=@$row['sgst']?></b>
                </td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;">
                    <?=number_format(@$sub_total,2)?></td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;">
                    <?=$row['remark'];?></td>

            </tr>
            <?php 
            $i++;
                }
            ?>
            <tr>
                <td colspan="4" style="border-right: 1px solid black; border-top: 1px solid black; text-align: right;">
                    <b>Total</b>
                </td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;">
                    <b><?=@$total_qty;?></b>
                </td>
                <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;"> </td>
                <!-- <td style="border-right: 1px solid black; border-top: 1px solid black; text-align: center;"> </td> -->
                <td colspan="2"
                    style="border-right: 1px solid black; border-top: 1px solid black;  border-bottom: 1px solid black; text-align: center;">
                    <b><?=number_format(@$total,2);?></b>
                </td>
            </tr>
    </table>
    <?php
                if($total != 0 ){
                    if ($invoice['disc_type'] == '%') {
                        $discount_amount = ($total * ($invoice['discount'] / 100));
                        $disc_avg_per = $discount_amount / $total;
                    } else {
                        $disc_avg_per = ($invoice['discount'] ? (float)$invoice['discount'] : 0) / ($total ? $total : 0);
                    }
                    if ($invoice['amty'] > 0) {
                        if ($invoice['amty_type'] == '%') {
                            $amty_amount = ($total * ($invoice['amty'] / 100));
                            $add_amt_per = $amty_amount / $total;
                        } else {
                            $add_amt_per = $invoice['amty'] / $total;
                        }
                    } else {
                        $add_amt_per = 0;
                    }

                }else{
                    $add_amt_per = 0;
                    $disc_avg_per = 0;
                }
            
                    $total = 0;
                    $igst_amt = 0;
                    $grand_total =0;
                    $total_discount=0;
                    $total_add=0;
                
                    for ($i = 0; $i < count($acc); $i++) {
                       // $sub = $item[$i]['qty'] * $item[$i]['rate'];
                        $sub=$acc[$i]['amount'];
                        // $total += $sub_total;
                
                        if ($invoice['discount'] > 0) {
                            $discount_amt = $sub * $disc_avg_per;
                            $final_sub = $sub - $discount_amt;
                            $add_amt = $final_sub * $add_amt_per;
                        } else {
                             $discount_amt = 0;
                            $final_sub = $sub ;
                            $add_amt = $final_sub * $add_amt_per;
                        }
                
                        $final_tot = $final_sub + $add_amt;    
                        $igst_amt += $final_tot * $acc[$i]['igst'] / 100;    
                        $total += $final_tot;
            
                        $total_discount +=$discount_amt; 
                        $total_add +=$add_amt; 
                
                    }
                    
                    $grand_total = $total;
                    $tax = json_decode($invoice['taxes']);
                    
                    $fin_igst = 0;
                    $fin_sgst = 0;
                    $fin_cgst = 0;
            
                    if(in_array('igst',$tax)){
                        $fin_igst = $invoice['tot_igst'];
                        $grand_total +=$fin_igst ;
                    }
            
                    if(in_array('sgst',$tax)){
                        $fin_sgst = $invoice['tot_sgst'];
                        $fin_cgst = $invoice['tot_cgst'];
                        $total_tax = $fin_sgst + $fin_cgst;
                        $grand_total +=$total_tax ;
                    }
            
                   
                    
                    $grand_total +=$invoice['round_diff'];
                ?>
    <table class="T-border2" style="width:100%">
        <tr>
            <td colspan="5"
                style="border-right: 1px solid black; border-bottom: 1px solid black; border-top: 1px solid black; text-align: start;">
                Narration - <?=@$invoice['other']?></td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">
                Less:Discount <br></td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
                <b>(-)<?=@$total_discount?></b><br>
            </td>

        </tr>
        <tr>
            <td colspan="5"
                style="border-right: 1px solid black; border-bottom: 1px solid black; border-top: 1px solid black; text-align: start;">
                &nbsp;</td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">Add
                Amount: <br>

            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
                <b>(+)<?=number_format(@$total_add,2)?></b><br>

            </td>
        </tr>
        <tr>
            <td colspan="5" style="border-right: 1px solid black; border-top: 1px solid black; text-align: start;">
                Bank Name: <?=@$invoice['bank_name'];?>
            </td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">Amount:
                <br>

            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
                <?=number_format(@$total,2)?><br></td>
        </tr>
        <tr>

            <td colspan="5" style="border-right: 1px solid black; border-top: 1px solid white; text-align: start;">
                ACC.No: <?=@$invoice['bank_ac'];?>
            </td>
            <?php if(in_array('igst',$tax)){?>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">IGST:
                <br>
            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
                <b><?=number_format(@$fin_igst,2)?></b><br>
                <?php }else{?>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">
                CGST:<br>
            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
                <b><?=number_format(@$fin_cgst,2)?></b><br>

                <?php }?>

            </td>
        </tr>
        <tr>

            <td colspan="5" style="border-right: 1px solid black; border-top: 1px solid white; text-align: start;">
                IFSC CODE: <?=@$invoice['bank_ifsc'];?>
            </td>
            <?php if(in_array('igst',$tax)){?>

            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;"></td>
            <td colspan="1" style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;"> 0
            </td>
            <?php }else {?>

            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">
                SGST:<br>
            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
                <b><?=number_format(@$fin_sgst,2)?></b><br>
                <?php } ?>
        </tr>

        <tr>
            <td colspan="5" style="border-right: 1px solid black; border-top: 1px solid black; text-align: start;">
                Due Days: <?=@$invoice['due_days'];?>
            </td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">Round
                Off:: <br>

            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
                <b><?=@$invoice['round_diff']?></b><br>
            </td>

        </tr>

        <tr>
            <td colspan="5" style="border-right: 1px solid black; border-top: 1px solid black; text-align: start;">
                RS In Word: <b><?php 
                    $gtotal  = round($grand_total); 
                    echo strtoupper(inword($gtotal)); 
                ?></b>
            </td>
            <td colspan="2"
                style="border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; font-size:15px;">
                <b>Grant total:</b>
            </td>
            <td colspan="1"
                style=" border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;text-align: right; font-size:20px;">
                <b><?=number_format(@$grand_total,2);?></b>


            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid black; border-top: 1px solid black;"> <b>HSN/SAC</b></td>
            <td style="text-align: start; border-top: 1px solid black; border-bottom: 1px solid black;"><b>Taxable
                    Value</b>
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
            <td style=" border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
                <b>Tax
                    Amount</b>
            </td>
            <td colspan="3" rowspan="4">&nbsp;</td>



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
                        //echo '<pre>';print_r($acc);exit;
                        foreach($acc as $row){

                            if($row['hsn'] != ''){
                                
                                $item_disc = 0;
                                $total_taxable = $row['amount'];

                                // if(!empty($row['item_disc'])){
                                //     $item_disc = $total_taxable * ($row['item_disc']/100) ;
                                // }
                                
                                // $total_taxable = $total_taxable - $item_disc; 
                                $arr[$row['hsn']]['taxable'] =$total_taxable;
                               // $arr[$row['hsn']]['taxable'] = (@$arr[$row['hsn']]['taxable'] ? @$arr[$row['hsn']]['taxable'] : 0) + $total_taxable;
                                $arr[$row['hsn']]['igst'] = $row['igst'];
                                $arr[$row['hsn']]['cgst'] = $row['cgst'];
                                $arr[$row['hsn']]['sgst'] = $row['sgst'];
                        
                                $arr[$row['hsn']]['igst_amount'] =  (@$arr[$row['hsn']]['igst_amount'] ? $arr[$row['hsn']]['igst_amount'] : 0) + @$total_taxable * $arr[$row['hsn']]['igst']/100;
                                $arr[$row['hsn']]['cgst_amount'] = (@$arr[$row['hsn']]['cgst_amount'] ? $arr[$row['hsn']]['cgst_amount'] : 0) + @$total_taxable * $arr[$row['hsn']]['cgst']/100;
                                $arr[$row['hsn']]['sgst_amount'] = (@$arr[$row['hsn']]['sgst_amount'] ? $arr[$row['hsn']]['sgst_amount'] : 0) + @$total_taxable * $arr[$row['hsn']]['sgst']/100;
                            
                                $total_igst +=  $arr[$row['hsn']]['igst_amount'];
                                $total_cgst +=   $arr[$row['hsn']]['cgst_amount'];
                                $total_sgst +=   $arr[$row['hsn']]['sgst_amount'];

                               // $total_taxable = 0;
                               
                               // $sub = $row['qty'] * $row['rate'] - $item_disc;
                                $total_sub += $total_taxable;

                            }else{

                               // $arr[$row['igst']] = $row['igst_amount']
                                // if($row['igst'] == $row['igst'])
                                // {
                                    $total_taxable = $row['amount'];
                                    $arr[$row['igst']]['taxable'] =$total_taxable;
                                    $arr[$row['igst']]['igst'] = $row['igst'];
                                    $arr[$row['igst']]['cgst'] = $row['cgst'];
                                    $arr[$row['igst']]['sgst'] = $row['sgst'];
                            
                                    $arr[$row['igst']]['igst_amount'] =  (@$arr[$row['igst']]['igst_amount'] ? $arr[$row['igst']]['igst_amount'] : 0) + @$total_taxable * $arr[$row['igst']]['igst']/100;
                                    $arr[$row['igst']]['cgst_amount'] = (@$arr[$row['igst']]['cgst_amount'] ? $arr[$row['igst']]['cgst_amount'] : 0) + @$total_taxable * $arr[$row['igst']]['cgst']/100;
                                    $arr[$row['igst']]['sgst_amount'] = (@$arr[$row['igst']]['sgst_amount'] ? $arr[$row['igst']]['sgst_amount'] : 0) + @$total_taxable * $arr[$row['igst']]['sgst']/100;
                                
                                    $total_igst +=  $arr[$row['igst']]['igst_amount'];
                                    $total_cgst +=   $arr[$row['igst']]['cgst_amount'];
                                    $total_sgst +=   $arr[$row['igst']]['sgst_amount'];

                                    //$total_taxable = 0;
                                
                                    // $sub = $row['qty'] * $row['rate'] - $item_disc;
                                    $total_sub += $total_taxable;
                                }
                           // }

                        }
                        $total_igst =0;
                        $total_cgst =0;
                        $total_sgst =0;
                      // echo '<pre>';print_r($arr);exit;
                        foreach($arr as $key=>$value)
                        {
                            
                    ?>
        <tr>
            <td style="border-bottom: 1px solid black; border-top: 1px solid black;"><?=@$key;?></td>
            <td style="text-align: start; border-top: 1px solid black; border-bottom: 1px solid black;">
                <?=@$value['taxable'];?></td>

            <?php if($fin_igst > 0 ) {?>

            <td style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
                <?=@$value['igst'];?>%</td>

            <td
                style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
                <?=number_format(@$value['igst_amount'],2);?>
            </td>

            <?php 
            $total_igst +=@$value['igst_amount'];
            ?>

            <?php }else{ ?>

            <td style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black;">
                <?=@$value['cgst'];?>%</td>
            <td
                style="text-align: center; border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
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
            <td style="border-bottom: 1px solid black; border-right: 1px solid black;">&nbsp;</td>

        </tr>
        <?php } ?>
        <tr>
            <td style="border-bottom: 1px solid black; border-top: 1px solid black;">
                <b>Total</b>
            </td>
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
                <b><?=number_format((@$total_cgst + @$total_sgst) ,2);?></b>
            </td>

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
            <td colspan="5" rowspan="2" style="border-bottom:  1px solid black; border-top:  1px solid black;">
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