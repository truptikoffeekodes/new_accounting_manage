<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> <?=$title?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">GSTR3</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
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
<!--Start Navbar -->
<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">

            <form method="get" action="<?=url('Gst/Gstr3_detail')?>">

                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <label class="">From :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" name="from" placeholder="YYYY-MM-DD"
                                            type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <label class="">To :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" name="to" placeholder="YYYY-MM-DD"
                                            type="text">
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
        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-fw-widget">
                        <tbody>
                            <tr>
                                <td>
                                    <span style="size:20px;"><b><?=$title?></b></span>
                                    <br>
                                    <b id="start_date"><?=user_date($start_date)?></b> to
                                    <b id="end_date"><?=user_date($end_date,2)?></b>

                                </td>
                            </tr>
                            <tr colspan="4">
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div aria-multiselectable="true" class="accordion" id="accordion" role="tablist">
                    <div class="card">

                        <div class="card-header" id="headingOne" role="tab">
                            <a aria-controls="collapseOne" aria-expanded="false" data-toggle="collapse"
                                href="#collapseOne" class="collapsed">Total Voucher<label
                                    style="float:right;"><?=@$sale['count']?></label>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-hover table-fw-widget" id="table_list_data" data-id=""
                        data-module="" data-filter_data=''>
                        <thead>
                            <tr>
                                <th>SI NO.</th>
                                <th>Accounts</th>
                                <th>Taxable Amount</th>
                                <th>Integrated Tax Amount</th>
                                <th>Central Tax Amount</th>
                                <th>State Tax Amount</th>
                                <th>Cess Amount</th>
                                <th>Tax Amount</th>
                                <th>Invoice Amount</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php 
                          
                               // $i=0;
                                 foreach($gstr3_detail['outward']['data'] as $row ) { 
                                    
                                  if($type == 'outward')
                                 {
                            ?>
                            <tr>
                                <th><?=$row['invoice_no']?></th>
                                <?php 
                                if(isset($row['v_type']))
                                { 
                                    ?>
                                <td><a href="<?=url('sales/add_ACinvoice/general/'.$row['id'])?>"><?=$row['name']?></a>
                                </td>
                                <?php }else{ ?>
                                <td><a href="<?=url('sales/add_salesinvoice/'.$row['id'])?>"><?=$row['name']?></a></td>
                                <?php } ?>
                                <td><?=isset($row['return_no']) || @$row['v_type']=='return' ? '-' : ''?><?=number_format(@$row['taxable'],2)?>
                                </td>

                                <?php 
                                    $taxes = json_decode($row['taxes']);

                                    if(in_array('igst',$taxes)){
                                ?>
                                <td><?=number_format(@$row['tot_igst'],2)?></td>
                                <td></td>
                                <td></td>
                                <?php }else{ ?>
                                <td></td>
                                <td><?=number_format(@$row['tot_cgst'],2)?></td>
                                <td><?=number_format(@$row['tot_sgst'],2)?></td>
                                <?php } ?>
                                <td><?=number_format(@$row['tot_cess'],2)?></td>
                                <td><?=number_format(@$row['tot_igst'],2)?></td>
                                <td><?=number_format(@$row['net_amount'],2)?></td>
                            </tr>
                            <?php }
                                if($type=='unregister')
                                {
                                    if($row['gst_type'] == "Unregister"){
                                    
                            ?>
                            <tr>
                                <th><?=$row['invoice_no']?></th>
                                <?php 
                                if(isset($row['v_type']))
                                { 
                                    ?>
                                <td><a href="<?=url('sales/add_ACinvoice/general/'.$row['id'])?>"><?=$row['name']?></a>
                                </td>
                                <?php }else{ ?>
                                <td><a href="<?=url('sales/add_salesinvoice/'.$row['id'])?>"><?=$row['name']?></a></td>
                                <?php } ?>
                                <td><?=isset($row['return_no']) || @$row['v_type']=='return' ? '-' : ''?><?=number_format(@$row['taxable'],2)?>
                                </td>

                                <?php 
                                    $taxes = json_decode($row['taxes']);

                                    if(in_array('igst',$taxes)){
                                ?>
                                <td><?=number_format(@$row['tot_igst'],2)?></td>
                                <td></td>
                                <td></td>
                                <?php }else{ ?>
                                <td></td>
                                <td><?=number_format(@$row['tot_cgst'],2)?></td>
                                <td><?=number_format(@$row['tot_sgst'],2)?></td>
                                <?php } ?>
                                <td><?=number_format(@$row['tot_cess'],2)?></td>
                                <td><?=number_format(@$row['tot_igst'],2)?></td>
                                <td><?=number_format(@$row['net_amount'],2)?></td>
                            </tr>
                            <?php
                            }
                        }
                        
                            
                            
                            ?>
                            <?php
                                 }
                                
                                    $total_cgst =0;
                                    $total_igst = 0;
                                    $total_sgst =0;
                                    $total_taxable =0;
                                    foreach($gstr3_detail['eligable_itc']['new_data'] as $row) { 
                                        if($type=='eligable_itc')
                                        {
                                        if(isset($row['taxable_total']))
                                        {
                                            if($row['taxable_total'] != 0)
                                            {
                                                //echo '<pre>';print_r($row);
                                 ?>
                            <tr>
                                <th><?=$row['invoice_no']?></th>
                                <?php 
                                if(isset($row['v_type']))
                                { 
                                    ?>
                                <td><a
                                        href="<?=url('purchase/add_general_pur/general/'.$row['id'])?>"><?=$row['name']?></a>
                                </td>
                                <?php }else{ ?>
                                <td><a href="<?=url('purchase/add_purchaseinvoice/'.$row['id'])?>"><?=$row['name']?></a>
                                </td>
                                <?php } ?>
                                <td>
                               
                                </td>

                                <?php 
                                    $taxes = json_decode($row['taxes']);

                                    if(in_array('igst',$taxes)){
                                ?>
                                <td><?=number_format(@$row['igst_total'],2)?></td>
                                <td></td>
                                <td></td>
                                <?php }else{ ?>
                                <td></td>
                                <td><?=number_format(@$row['cgst_total'],2)?></td>
                                <td><?=number_format(@$row['sgst_total'],2)?></td>
                                <?php } ?>
                                <td><?=number_format(@$row['tot_cess'],2)?></td>
                                <td><?=number_format(@$row['tot_igst'],2)?></td>
                                <td><?=number_format(@$row['net_amount'],2)?></td>
                            </tr>
                            <?php
                            $total_igst += $row['igst_total'];
                            $total_cgst += $row['cgst_total'];
                            $total_sgst += $row['sgst_total'];
                                    }
                                }
                            }
                                    if($type=='nill')
                                    {
                                       if(isset($row['exempt_total']))
                                        {
                                            if($row['exempt_total'] != 0)
                                            {
                                    ?>
                            <tr>
                                <th><?=$row['invoice_no']?></th>
                                <?php 
                                   if(isset($row['v_type']))
                                   { 
                                       ?>
                                <td><a
                                        href="<?=url('purchase/add_general_pur/general/'.$row['id'])?>"><?=$row['name']?></a>
                                </td>
                                <?php }else{ ?>
                                <td><a href="<?=url('purchase/add_purchaseinvoice/'.$row['id'])?>"><?=$row['name']?></a>
                                </td>
                                <?php } ?>
                                <td><?=isset($row['return_no']) || @$row['v_type']=='return' ? '-' : ''?><?=number_format(@$row['exempt_total'],2)?>
                                </td>

                                <?php 
                                       $taxes = json_decode($row['taxes']);
   
                                       if(in_array('igst',$taxes)){
                                   ?>
                                <td><?=number_format(@$row['tot_igst'],2)?></td>
                                <td></td>
                                <td></td>
                                <?php }else{ ?>
                                <td></td>
                                <td><?=number_format(@$row['tot_cgst'],2)?></td>
                                <td><?=number_format(@$row['tot_sgst'],2)?></td>
                                <?php } ?>
                                <td><?=number_format(@$row['tot_cess'],2)?></td>
                                <td><?=number_format(@$row['tot_igst'],2)?></td>
                                <td><?=number_format(@$row['net_amount'],2)?></td>
                            </tr>
                            <?php
                             $total_taxable += $row['exempt_total'];
                                       }
                                 }
                                 
                                }
                               
                            }
                            //exit;
                                 ?>

                        </tbody>
                        <hr>

                        <tfooter>
                            <?php 
                            if($type == 'outward')
                            {
                                ?>

                            <th>Total</th>
                            <th><?=@$i;?></th>

                            <th><?=number_format($gstr3_detail['outward']['taxable_amount'],2)?></th>
                            <th><?=number_format(@$gstr3_detail['outward']['igst'],2)?></th>
                            <th><?=number_format(@$gstr3_detail['outward']['cgst'],2)?></th>
                            <th><?=number_format(@$gstr3_detail['outward']['sgst'],2)?></th>

                            <th><?=number_format(@$gstr3_detail['outward']['cess'],2)?></th>
                            <th><?=number_format(@$gstr3_detail['outward']['igst'] + $gstr3_detail['outward']['cess'] + $gstr3_detail['outward']['sgst'] + $gstr3_detail['outward']['cgst'],2)?>
                            <th><?=number_format(@$gstr3_detail['outward']['net_amount'],2)?></th>
                            <?php
                            }
                            if($type == 'unregister')
                            {
                            ?>
                            <th>Total</th>
                            <th></th>

                            <th><?=number_format(@$gstr3_detail['gst_type_wise']['unregister']['taxable_amount'] +  @$gstr3_detail['gst_type_wise']['composition']['taxable_amount'],2)?>
                            </th>
                            <th><?=number_format(@$gstr3_detail['gst_type_wise']['unregister']['igst'] +  @$gstr3_detail['gst_type_wise']['composition']['igst'],2)?>
                            </th>
                            <th><?=number_format(@$gstr3_detail['gst_type_wise']['unregister']['cgst'] +  @$gstr3_detail['gst_type_wise']['composition']['cgst'],2)?>
                            </th>
                            <th><?=number_format(@$gstr3_detail['gst_type_wise']['unregister']['sgst'] +  @$gstr3_detail['gst_type_wise']['composition']['sgst'],2)?>
                            </th>
                            <th><?=number_format(@$gstr3_detail['gst_type_wise']['unregister']['cess'] +  @$gstr3_detail['gst_type_wise']['composition']['cess'],2)?>
                            </th>
                            <th><?=number_format(@$gstr3_detail['gst_type_wise']['unregister']['cess'] + @$gstr3_detail['gst_type_wise']['unregister']['sgst'] + @$gstr3_detail['gst_type_wise']['unregister']['cgst'] + @$gstr3_detail['gst_type_wise']['unregister']['igst'] +  @$gstr3_detail['gst_type_wise']['composition']['cess'] + @$gstr3_detail['gst_type_wise']['composition']['igst'] + @$gstr3_detail['gst_type_wise']['composition']['cgst'] +@$gstr3_detail['gst_type_wise']['composition']['sgst'],2)?>

                                <?php
                            }
                            
                            if($type == 'eligable_itc')
                            {
                            ?>
                            <th>Total</th>
                            <th></th>

                            <th></th>
                            <th><?=number_format(@$total_igst,2)?>
                            </th>
                            <th><?=number_format(@$total_cgst,2)?></th>
                            <th><?=number_format(@$total_sgst,2)?></th>
                            <th></th>
                            <th></th>

                            <?php
                            }
                            if($type == 'nill')
                            {
                            ?>
                            <th>Total</th>
                            <th></th>

                            
                            <th><?=number_format(@$total_taxable,2)?>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>

                            <?php
                            }
                            ?>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endsection() ?>
<?= $this->section('scripts') ?>
<script type="text/javascript">
$(document).ready(function() {
    $('#table_list_data').DataTable();
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.dateMask').mask('99-99-9999');
});
</script>
<?= $this->endSection() ?>