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
                        <h3 style="margin: 0;padding: 0px;"><?=@session('address') ? @session('address') : '';?> <?= !empty(@session('city')) ? ' , '.session('city') : ''?><?= !empty(@session('pin')) ? ' - '.@session('pin') : '' ?> <?= !empty(@session('state_name')) ? ' , '.@session('state_name').'('.session('state_code').')' : '' ?><?=!empty(@session('country')) ? ' , '.@session('country') : '' ?>  </h3>
                        <br>
                        <h3 style="margin: 0;padding:0;">GSTIN : <?=@session('gst')?>&nbsp; / PAN : <?=@session('incomtax_pan')?></h3>
                        <h3 style="margin: 0;padding-bottom: 5px;"></h3>
                    </th>
                </tr>
            </thead>
        <tbody>
            <tr>
                <td rowspan="3" colspan="6"><center><h3>TAX INVOICE</h3></center> <br>

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
                    <br><?=@$salesinvoice['ship_city']?> &nbsp;<?=@$salesinvoice['ship_pin']?>
                    <br><?=@$salesinvoice['ship_state']?>
                    <br><?=@$salesinvoice['ship_country']?>
                    <br>GSTIN :<?=@$delivery['gst']?>
                    <br>State Code : <?=@$ship_state['state_code']?>
                    <br>State :<?=@$ship_state['name']?>

                </td>
                <td colspan="3">
                    <b>Invoice No : <?=@$salesinvoice['custom_inv_no']?></b><br>
                    <b>Voucher No : <?=@$salesinvoice['invoice_no']?></b>
                    <br><b>Invoice Date : <?=user_date(@$salesinvoice['invoice_date'])?></b>
                    <br>Place Of Supply : <?=@$ship_state['name']?>
                </td>
            </tr>

            <tr>
                <td colspan="4">Broker :<?=@$salesinvoice['broker_name']?></td>
                <td colspan="5">Transporter Name :<?=@$transport['name']?></td>
            </tr>
            <tr>
                <td colspan="3">
                    L.R.No :<?=@$salesinvoice['lr_no']?>
                    <br>Transport :
                </td>
                <td colspan="3">L.R.Date : <?=@$salesinvoice['lr_date']?>
                    <br>Transport Type : <?=@$salesinvoice['transport_mode']?>
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
              $i = 1;
              $total_qty = 0;
      
              $total = 0.0;
              $igst_amt = 0.0;
              $sub = 0;
      
                foreach($item as $row)
                {
                    $total_qty +=$row['qty'];
                    
                    
                    if($row['is_expence'] == 1){
                        $final_sub = $row['rate'];
                    }else{
                        $sub = $row['qty'] * $row['rate'];
                        $disc_amt = $sub * $row['item_disc'] / 100;
                        $final_sub = $sub - $disc_amt;                        
                    }
            
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
     
      if($salesinvoice['disc_type'] == '%')
      {
        $final_sub = 0;
        foreach($item as $row)
        {   
            if($row['is_expence'] == 0){
                $sub = $row['qty'] * $row['rate'];
                $final_sub += $sub ;                       
            }
            
            $total_discount = $final_sub * ($salesinvoice['discount'] / 100);


        }
      }
      else
      {
         $total_discount = $salesinvoice['discount'];
      }
      ?>
    <table class="T-border2" style="width:100%">
        <tr>
            <td colspan="6"
                style="border-right: 1px solid black; border-bottom: 1px solid black; border-top: 1px solid black; text-align: start;">
                Narration - <?=@$salesinvoice['other']?></td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">
                Less:Discount <br></td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
            <b>(-)<?=@$total_discount?></b><br></td>

        </tr>
        <tr>
            <td colspan="6"
                style="border-right: 1px solid black; border-bottom: 1px solid black; border-top: 1px solid black; text-align: start;">
               &nbsp;</td>
            <td colspan="2" style="border-right: 1px solid black; border-top: 1px solid black; text-align:end;">Add
                Amount: <br>

            </td>
            <td style="border-right: 1px solid black; border-top: 1px solid black;text-align: right;">
            <b>(+)<?=@$total_add?></b><br>

            </td>
        </tr>
    </table>

    
</body>

</html>