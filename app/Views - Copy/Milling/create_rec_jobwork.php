<?=$this->extend(THEME . 'templete')?>

<?=$this->section('content')?>
<style>
.product {
    width: 170%;
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

.select2_height .select2-container .select2-selection--single,
.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 28px;
}

.select2_height .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 25px !important;
}

</style>
<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5">Transaction </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>

    <div class="ml-auto pd-r-100">
        <h2 class="mb-1 font-weight-bold"><span>Jobwork Received Sr No :</span> <?=@$job['id'] ? @$job['id'] : @$current_id;?></h2>    
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <form action="<?=url('Milling/Add_rec_jobwork')?>" class="ajax-form-submit" method="POST"
                        id="challanform">
                        <div class="row">
                            
                            <input type="hidden" name="id" value="<?=@$job['id'] ? @$job['id'] : @$id;?>">

                            <input class="form-control col-md-9" type="hidden" name="srno" id="srno" readonly
                                    value="<?=@$job['id'] ? @$job['id'] : @$current_id;?>" required>

                            <div class="row col-md-12 form-group">
                                <label class="form-label col-md-3">Select Jobwork Challan: <span
                                        class="tx-danger">*</span></label>
                                <select class="form-control col-md-6" id="job_challan"  name='job_challan'>
                                    <?php if (!empty($job['challan_no'])) {?>
                                    <option value="<?=@$job['challan_no']?>">
                                        <?=@$job['challan_name']?>
                                    </option>
                                    <?php }?>
                                </select>
                            </div>

                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-3">Party Ac Name: <span
                                        class="tx-danger">*</span></label>
                                <select class="form-control col-md-9 account" id="account" name='account'>
                                    <?php if (@$job['account']) {?>
                                    <option value="<?=@$job['account']?>">
                                        <?=@$job['account_name']?>
                                    </option>
                                    <?php }?>
                                </select>
                                <input type="hidden" name="tds_per" id="tds_per" value="<?=@$job['tds_per'];?>">
                                <input type="hidden" name="tds_limit" id="tds_limit" value="<?=@$job['tds_limit'];?>">
                                <input type="hidden" name="acc_state" id="acc_state" value="<?=@$job['acc_state'];?>">
                            </div>

                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-3">Challan date.:</label>
                                <input class="form-control challan_no dateMask col-md-9" placeholder="DD/MM/YYYY"
                                    type="text" id="date" name="date"
                                    value="<?=@$job['date'] ? user_date(@$job['date']) : user_date(date('Y-m-d'));?>">
                            </div>

                            <div class="col-lg-5 form-group">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Delivery Ac: </label>
                                    </div>

                                    <div class="col-md-8 form-group">
                                        <select class="form-control" id="delivery" name='delivery_ac' >
                                            <option value=""> Not One</option>
                                            <?php if(@$challan['delivery_ac']) { ?>
                                            <option selected value="<?=@$challan['delivery_ac']?>">
                                                <?=@$challan['delivery_ac_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Delivery Address:</label>
                                    </div>

                                    <div class="col-md-8 form-group">
                                        <textarea class="form-control" type="text" name="delivery_add" placeholder="Enter Delivery Address" value="" ><?=@$job['delivery']?></textarea>
                                    </div>

                                    

                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Transport Mode: </label>
                                    </div>

                                    <div class="col-md-8 form-group">
                                        <select class="form-control transport_mode" id="transport_mode"
                                            name="trasport_mode">
                                            <option <?=(@$job['transport_mode'] == 'ROAD' ? 'selected' : '')?>
                                                value="ROAD">ROAD</option>

                                            <option <?=(@$job['transport_mode'] == 'AIR' ? 'selected' : '')?>
                                                value="AIR">AIR</option>

                                            <option <?=(@$job['transport_mode'] == 'RAIL' ? 'selected' : '')?>
                                                value="RAIL">RAIL</option>
                                            <option <?=(@$job['transport_mode'] == 'SHIP' ? 'selected' : '')?>
                                                value="SHIP">SHIP</option>
                                        </select>
                                    </div>   

                                    <div class="col-md-4 form-group">
                                        <label class="form-label"> Warehouse: </label>
                                    </div>

                                    <div class="col-md-8 form-group">
                                        <select class="form-control warehouse" id="warehouse" name='warehouse'>
                                            <?php if(@$job['warehouse']) { ?>
                                            <option value="<?=@$job['warehouse']?>">
                                                <?=@$job['warehouse_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7 form-group">
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR No: </label>
                                    </div>
                                    
                                    <div class="col-md-4 form-group">
                                        <input class="form-control lrno" name="lrno" value="<?=@$job['lr_no'];?>"
                                            placeholder="LR No." type="text">
                                    </div>
                                    
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR Date: </label>
                                    </div>
                                    
                                    <div class="col-md-4 form-group">
                                        <input class="form-control lr_date dateMask" placeholder="DD/MM/YYYY"
                                            type="text" id="lr_date" name="lr_date" value="<?=user_date(@$job['lr_date']);?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label ">Weight: </label>
                                    </div>
                                    
                                    <div class="col-md-4 form-group">
                                        <input class="form-control weight" name="weight" value="<?=@$job['weight'];?>"
                                            placeholder="0.00" placeholder="Enter Weight"  type="text">
                                    </div>
                                    
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Freight.: </label>
                                    </div>
                                    
                                    <div class="col-md-4 form-group">
                                        <input class="form-control freight" name="freight"
                                            value="<?=@$job['freight'];?>" placeholder="00" type="text">
                                    </div>

                                    

                                    <div class="col-md-2 form-group">
                                        <label class="form-label"> Broker </label>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <select class="form-control broker" id="broker" name='broker'>
                                            <?php if(@$job['broker']) { ?>
                                            <option selected value="<?=@$job['broker']?>">
                                                <?=@$job['broker_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label"> Transport </label>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <select class="form-control broker" id="transport" name='transport'>
                                            <?php if(@$job['transport']) { ?>
                                            <option value="<?=@$job['transport']?>">
                                                <?=@$job['transport_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>


                                    <div class="col-md-3 form-group">
                                        <label class="form-label"> <span class="tx-danger"></span></label>
                                    </div>

                                    <!-- <div class="col-md-10 form-group">
                                    </div> -->
                                    <!-- <a target="_blank" title="Add Item:<?=@$current_id?>" onclick="add_item(this)"  data-val="<?=@$current_id?>" data-pk="<?=@$current_id?>" tabindex="-1" class="btn btn-primary">Add Item</a> -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0 product" id="product">
                                    <thead>
                                        <tr>
                                            <th style="width:10px;">#</th>
                                            <th style="width:100px;">Item</th>
                                            <th style="width:100px;">HSN</th>
                                            <th style="width:100px;">Type</th>
                                            <th style="width:150px;">Screen</th>
                                            <th style="width:100px;">Rate</th>
                                            <th style="width:70px;">Gst</th>
                                            <th style="width:70px;">Send PCS</th>
                                            <th style="width:70px;">Send MTR</th>
                                            <th style="width:70px;">Send Taka</th>
                                            <th style="width:70px;">Cut</th>
                                            <th style="width:70px;">Remaining PCS</th>
                                            <th style="width:70px;">Remaining MTR</th>
                                            <th style="width:70px;">Received PCS</th>
                                            <th style="width:80px;">Received MTR</th>
                                            <th style="width:80px;">Pending PCS-MTR</th>
                                            <th style="width:70px;">Total</th>
                                            <th style="width:80px;">Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        <?php
                                        $total = 0;
                                        if (isset($item)) {
                                            foreach ($item as $row) {
                                        ?>
                                        <tr class="<?=$row['pid']?>">

                                            <td>#</td>
                                            <input type="hidden" name="pid[]" value="<?=$row['pid']?>">
                                            <input type="hidden" name="recJobItemId[]" value="<?=$row['id']?>">
                                            
                                            <td><?=$row['name']?> </td>
                                            <td><?=$row['hsn']?> </td>
                                            <td>
                                                <select class="form-control select-sm" id="type" name="type[]"
                                                    onchange="calculate()">
                                                    <?=$row["uom_opt"];?>
                                                </select>
                                            </td>

                                            <td>
                                                <div class="input-group select2_height">
                                                    <select class="form-control select-sm screen" name="screen[]">
                                                        <?php if(!empty($row['screen'])) { ?>
                                                        <option value="<?=@$row['screen']?>"><?=@$row['screen_name']?>
                                                        </option>
                                                        <?php  } ?>
                                                    </select>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text" style="padding:0.045rem 0.30rem;">
                                                            <a data-toggle="modal" data-target="#fm_model"
                                                                data-title="Add Finish Item"
                                                                href="<?=url('Milling/add_finishjob_screen')?>"><i
                                                                    style="font-size:20px;"
                                                                    class="fe fe-plus-circle"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$row['price']?>"
                                                    name="price[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required type="text">
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$row['gst']?>"
                                                    name="gst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['pcs']?>"
                                                    name="sendJOB_pcs[]" readonly onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" 
                                                    type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['meter']?>"
                                                    name="sendJOB_mtr[]" readonly onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" 
                                                    type="text"></td>
                                            
                                            <td><input class="form-control input-sm" value="<?=$row['unit']?>"
                                                    name="sendJOB_unit[]" readonly onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" 
                                                    type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['cut']?>"
                                                    name="sendJOB_cut[]" readonly onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" 
                                                    type="text"></td>
                                            
                                            <td><input class="form-control input-sm" value="<?=@$row['remaining_pcs']?>"
                                                    name="remaining_pcs[]" readonly onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" 
                                                    type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=@$row['remaining_mtr']?>"
                                                    name="remaining_mtr[]" readonly onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" 
                                                    type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['rec_pcs']?>"
                                                    name="recJOB_pcs[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" 
                                                    type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['rec_mtr']?>"
                                                    name="recJOB_mtr[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" 
                                                    type="text"></td>

                                            <td><input class="form-control input-sm"
                                                    value="<?=$row['pending']?>" name="pending[]"
                                                    onchange="calculate()" onkeypress="return isDesimalNumberKey(event)"
                                                    type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['subtotal']?>" name="subtotal[]"
                                                    onchange="calculate()" onkeypress="return isDesimalNumberKey(event)"
                                                    type="text">
                                            </td>
                                            <td><input class="form-control input-sm" value="<?=$row['remark']?>" name="remark[]"
                                                    onchange="calculate()" type="text">
                                            </td>
                                        </tr>
                                        <?php }
                                    }?>
                                    </tbody>
                                    <tfoot>
                                        <td colspan="2" class="text-right">Total</td>
                                        <td class=""></td>
                                        <td class=""></td>
                                        <td class=""></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="total"></td>
                                        <td></td>
                                        
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="row mt-3">
                                    <div class="table-responsive">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mt-3">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mg-b-0">
                                            <thead>
                                                <tr>
                                                    <th>(-)Discount</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control discount" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="discount" type="text"
                                                                value="<?=@$finish['discount'];?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 disc_type" name="disc_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?=(@$finish['disc_type'] == 'Fixed' ? 'selected' : '')?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?=(@$finish['disc_type'] == '%' ? 'selected' : '')?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="discount_amount wd-90"></th>
                                                </tr>

                                                <tr>
                                                    <th>(-)Less Amount</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control amtx" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="amtx" type="text" value="<?=@$finish['amtx'];?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 amtx_type" name="amtx_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?=(@$finish['amtx_type'] == 'Fixed' ? 'selected' : '')?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?=(@$finish['amtx_type'] == '%' ? 'selected' : '')?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="amtx_amount wd-90"></th>
                                                </tr>

                                                <tr>
                                                    <th>(+)Add Amount</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control amty" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="amty" type="text" value="<?=@$finish['amty'];?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 amty_type" name="amty_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?=(@$finish['amty_type'] == 'Fixed' ? 'selected' : '')?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?=(@$finish['amty_type'] == '%' ? 'selected' : '')?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="amty_amount wd-90"></th>
                                                </tr>
                                                <?php
                                                    $taxes = json_decode(@$job['taxes']);
                                                ?>
                                                <tr>
                                                    <th>Select Tax</th>
                                                    <th colspan="2" class="wd-300">
                                                        <div class="input-group-sm">
                                                            <select class="select2 tax" id="tax" name="taxes[]"
                                                                onchange="calculate()" multiple>
                                                                
                                                                <?php foreach ($tax as $row) {
                                                                    if ($row['name'] == 'igst' && session('state') != @$finish['acc_state']) {
                                                                ?>
                                                                
                                                                <option value="<?=$row['name']?>"
                                                                    <?php if (!empty($taxes)) {echo (in_array($row['name'], $taxes)) ? 'selected' : '';}?>>
                                                                    <?=$row['name'];?></option>

                                                                <?php } else if ($row['name'] == 'cgst' && session('state') == @$finish['acc_state']) {?>

                                                                <option value="<?=$row['name']?>"
                                                                    <?php if (!empty($taxes)) {echo (in_array($row['name'], $taxes)) ? 'selected' : '';}?>>
                                                                    <?=$row['name'];?></option>

                                                                <?php } else if ($row['name'] == 'sgst' && session('state') == @$finish['acc_state']) {?>

                                                                <option value="<?=$row['name']?>"
                                                                    <?php if (!empty($taxes)) {echo (in_array($row['name'], $taxes)) ? 'selected' : '';}?>>
                                                                    <?=$row['name'];?></option>

                                                                <?php } else if ($row['name'] == 'tds' || $row['name'] == 'cess') {?>

                                                                <option value="<?=$row['name']?>"
                                                                    <?php if (!empty($taxes)) {echo (in_array($row['name'], $taxes)) ? 'selected' : '';}?>>
                                                                    <?=$row['name'];?></option>

                                                                <?php } else {if (!@$finish) {?>
                                                                <option value="<?=$row['name']?>"
                                                                    <?php if (!empty($taxes)) {echo (in_array($row['name'], $taxes)) ? 'selected' : '';}?>>
                                                                    <?=$row['name'];?></option>
                                                                <?php }}}?>
                                                            </select>
                                                        </div>
                                                    </th>
                                                </tr>

                                                <tr id="igst"
                                                    style="display:<?php if (!empty($taxes)) {echo (in_array("igst", $taxes)) ? 'table-row;' : 'none;';} else {echo 'none;';}?>">
                                                    <th>(+)IGST</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control igst" readonly
                                                                onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_igst" type="text"
                                                                value="<?=@$finish['tot_igst'];?>">
                                                        </div>
                                                    </th>
                                                    <th class="igst_amount wd-90"></th>
                                                </tr>

                                                <tr id="sgst"
                                                    style="display:<?php if (!empty($taxes)) {echo in_array("sgst", $taxes) ? 'table-row;' : 'none;';} else {echo 'none;';}?>">
                                                    <th>(+)SGST</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control sgst" readonly
                                                                onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_sgst" type="text"
                                                                value="<?=@$finish['tot_sgst'];?>">

                                                        </div>
                                                    </th>
                                                    <th class="sgst_amount wd-90"></th>
                                                </tr>

                                                <tr id="cgst"
                                                    style="display:<?php if (!empty($taxes)) {echo in_array("cgst", $taxes) ? 'table-row;' : 'none;';} else {echo 'none;';}?>">
                                                    <th>(+)CGST</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control cgst" readonly
                                                                onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_cgst" type="text"
                                                                value="<?=@$finish['tot_cgst'];?>">

                                                        </div>
                                                    </th>
                                                    <th class="cgst_amount wd-90"></th>
                                                </tr>

                                                <tr id="tds"
                                                    style="display:<?php if (!empty($taxes)) {echo in_array("tds", $taxes) ? 'table-row;' : 'none;';} else {echo 'none;';}?>">
                                                    <th>(+)TDS</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control tds" readonly
                                                                onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tds_amt" type="text"
                                                                value="<?=@$finish['tds_amt'];?>">

                                                        </div>
                                                    </th>
                                                    <th class="tds_amount wd-90"></th>
                                                </tr>

                                                <tr id="cess"
                                                    style="display:<?php if (!empty($taxes)) {echo in_array("cess", $taxes) ? 'table-row;' : 'none;';} else {echo 'none;';}?> ">
                                                    <th>(+)Cess</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control cess" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="cess" type="text" value="<?=@$finish['cess'];?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 cess_type" name="cess_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?=(@$finish['cess_type'] == 'Fixed' ? 'selected' : '')?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?=(@$finish['cess_type'] == '%' ? 'selected' : '')?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="cess_amount wd-90"></th>
                                                </tr>
                                                <tr>
                                                    <td>Net Amount</td>
                                                    <td colspan="2"><input class="form-control net_amount input-sm"
                                                        name="net_amount" type="text" readonly></td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="form-group">
                    <div class="tx-danger error-msg"></div>
                    <div class="tx-success form_proccessing"></div>
                </div>
                <div class=" mt-3">
                    <input class="btn btn-space btn-primary btn-product-submit" id="save_data" type="submit"
                        value="Submit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?=$this->endSection()?>


<?=$this->section('scripts')?>
<script>
<?php

if (isset($id)) {?>

calculate();
<?php }?>


function validate_autocomplete(obj, val) {
    if ($('#' + val).val() == '') {
        $('.' + val).html('Option Select from dropdown list')
    } else {
        $('.' + val).html('')
    }
}

function enable_gst_option() {
    var tax = $("#tax :selected").map(function(i, el) {
        return $(el).val();
    }).get();

    var igst = document.getElementById("igst");
    var sgst = document.getElementById("sgst");
    var cgst = document.getElementById("cgst");
    var tds = document.getElementById("tds");
    var cess = document.getElementById("cess");

    $.each(tax, function() {
        if (this == 'igst') {
            igst.style.display = "table-row";
        } else if (this == 'sgst') {
            sgst.style.display = "table-row";
        } else if (this == 'cgst') {
            cgst.style.display = "table-row";
        } else if (this == 'tds') {
            tds.style.display = "table-row";
        } else if (this == 'cess') {
            cess.style.display = "table-row";
        } else {}
    });

    var tds = document.getElementById("tds");
    var cess = document.getElementById("cess");

    var tax_array = ['igst', 'sgst', 'cgst', 'cess', 'tds'];
    var diff = arr_diff(tax_array, tax);

    $.each(diff, function() {
        if (this == 'igst') {
            igst.style.display = "none";
        } else if (this == 'sgst') {
            sgst.style.display = "none";
        } else if (this == 'cgst') {
            cgst.style.display = "none";
        } else if (this == 'cess') {
            cess.style.display = "none";
        } else if (this == 'tds') {
            tds.style.display = "none";
        } else {
            // cgst.style.display="table-row";
        }
    });
}

$('.ajax-form-submit').on('submit', function(e) {

    $('#save_data').prop('disabled', true);
    $('.error-msg').html('');
    //$('.form_proccessing').html('Please wail...');
    e.preventDefault();
    var aurl = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: aurl,
        data: $(this).serialize(),
        success: function(response) {
            if (response.st == 'success') {
                //$('#fm_model').modal('toggle');
                swal("success!", "Your update successfully!", "success");
                // $('.form_proccessing').html('');
                $('#save_data').prop('disabled', false);
                window.location = "<?=url('Milling/Jobwork_rec')?>";
            } else {
                $('.form_proccessing').html('');
                $('#save_data').prop('disabled', false);
                $('.error-msg').html(response.msg);
            }
        },
        error: function() {
            $('#save_data').prop('disabled', false);
            alert('Error');
        }
    });
    return false;
});

function calculate() {

    var price = $('input[name="price[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var igst = $('input[name="gst[]"]').map(function() {
        return parseFloat(this.value);
    }).get();
    
    var sendJOB_cut = $('input[name="sendJOB_cut[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var sendJOB_pcs = $('input[name="sendJOB_pcs[]"]').map(function() {
        return parseFloat(this.value);
    }).get();
    
    var recJOB_pcs = $('input[name="recJOB_pcs[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var remaining_pcs = $('input[name="remaining_pcs[]"]').map(function() {
        return parseFloat(this.value);
    }).get();
    
    var remaining_mtr = $('input[name="remaining_mtr[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var sendJOB_mtr = $('input[name="sendJOB_mtr[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var type = $('select[name="type[]"] option:selected').map(function() {
        return this.text;
    }).get();
    
    var total = 0.0;
    var igst_amt = 0.0;
    var gst_amt = 0.0;
    var tot_item_brok = 0.0;
    var tot_fix_brok = 0.0;
    var mtr_total = 0;
    var recJOB_mtr = 0;
    var pending_pcs = 0;
    var pending_mtr = 0;

    for (var i = 0; i < sendJOB_pcs.length; i++) {
        if (recJOB_pcs[i] != 'undefine' && !isNaN(recJOB_pcs[i])){
            
            recJOB_mtr = recJOB_pcs[i] * sendJOB_cut[i];
            pending_pcs =  remaining_pcs[i] - recJOB_pcs[i];
            pending_mtr =  remaining_mtr[i] - recJOB_mtr;

            $('input[name="recJOB_mtr[]"]').eq(i).val(recJOB_mtr);
            $('input[name="pending[]"]').eq(i).val(pending_pcs+'-'+pending_mtr);
        }else{
            $('input[name="recJOB_mtr[]"]').eq(i).val('');
            $('input[name="pending[]"]').eq(i).val('');   
        }
    }

    var calcRecJOB_mtr = $('input[name="recJOB_mtr[]"]').map(function() {
        return parseFloat(this.value);
    }).get();
    
    for (var i = 0; i < recJOB_pcs.length; i++) {
        if(type[i] == "PCS"){
            var sub = price[i] * recJOB_pcs[i];
            var final_sub = sub;

            if(isNaN(final_sub)){
                final_sub = 0;
            }
            $('input[name="subtotal[]"]').eq(i).val(final_sub);
            gst_amt = final_sub * igst[i] / 100;
            
            if(isNaN(gst_amt)){
                gst_amt = 0;
            }
            igst_amt += gst_amt;
            total += final_sub;
        }else{
            var sub = price[i] * calcRecJOB_mtr[i];
            var final_sub = sub;
            
            gst_amt = final_sub * igst[i] / 100;
            if(isNaN(final_sub)){
                final_sub = 0;
            }
            if(isNaN(gst_amt)){
                gst_amt = 0;
            }
            igst_amt +=gst_amt;
            $('input[name="subtotal[]"]').eq(i).val(final_sub);
            total += final_sub;
        }
    }
    $('.total').html(total);

    var discount = $('input[name="discount"]').val();

    var amtx = parseFloat($('input[name="amtx"]').val());
    var amty = parseFloat($('input[name="amty"]').val());
    var cess = parseFloat($('input[name="cess"]').val());
    var tds_per = $('#tds_per').val();
    var tds_limit = $('#tds_limit').val();

    if (Number.isNaN(discount)) {
        discount = 0;
    }
    if (Number.isNaN(amtx)) {
        amtx = 0;
    }
    if (Number.isNaN(amty)) {
        amty = 0;
    }
    if (Number.isNaN(cess)) {
        cess = 0;
    }

    var discount_type = $('select[name=disc_type] option').filter(':selected').val();
    var amtx_type = $('select[name=amtx_type] option').filter(':selected').val();
    var amty_type = $('select[name=amty_type] option').filter(':selected').val();
    var cess_type = $('select[name=cess_type] option').filter(':selected').val();

    if(discount_type == '%') {
        discount_amount = (total * (discount / 100));
        $('.discount_amount').html('- ' + discount_amount);
        if (discount_amount > 0) {
            total -= discount_amount;
        }
    } else {
        $('.discount_amount').html('- ' + discount);
        if (discount > 0) {
            total -= discount;
        }
    }
    var grand_total = total;

    if (amtx_type == '%') {
        amtx_amount = (total * (amtx / 100));
        $('.amtx_amount').html('- ' + amtx_amount);
        grand_total -= amtx_amount;
    } else {
        $('.amtx_amount').html('- ' + amtx);
        grand_total -= amtx;
    }

    if (amty_type == '%') {
        amty_amount = (total * (amty / 100));
        $('.amty_amount').html('+ ' + amty_amount);
        grand_total += (total * (amty / 100));
    } else {
        $('.amty_amount').html('+ ' + amty);
        // amty_amount = amty;
        grand_total += amty
    }

    if (cess_type == '%') {
        cess_amt = (total * (cess / 100));
        $('.cess_amount').html('+ ' + cess_amount);
        // grand_total += (total * (cess / 100));
    } else {
        $('.cess_amount').html('+ ' + cess);
        // grand_total += cess;
        cess_amt = cess;
    }
    var tds_amount = 0;

    if (tds_per != '') {
        tds_amount = (total * (tds_per / 100));
        // grand_total += tds_amount;
    }

    var cgst = igst_amt / 2;
    var sgst = igst_amt / 2;

    var tax_option = $("#tax :selected").map(function(i, el) {
        return $(el).val();
    }).get();

    $.each(tax_option, function() {
        if(this == 'igst') {
            grand_total = grand_total + igst_amt;
        } else if (this == 'sgst') {
            grand_total = grand_total + sgst;
        } else if (this == 'cgst') {
            grand_total = grand_total + cgst;
        } else if (this == 'tds') {
            if (tds_per != '' && total > tds_limit) {

                tds_amount = (total * (tds_per / 100));
                grand_total += tds_amount;
            }
        } else {}
    });

    $('input[name="net_amount"]').val(grand_total.toFixed(2));
    $('input[name="tot_igst"]').val(igst_amt.toFixed(2));
    $('input[name="tot_cgst"]').val(cgst.toFixed(2));
    $('input[name="tot_sgst"]').val(sgst.toFixed(2));
    $('input[name="tds_amt"]').val(tds_amount.toFixed(2));
    $('.igst_amount').html('+ ' + igst_amt.toFixed(2));
    $('.cgst_amount').html('+ ' + cgst.toFixed(2));
    $('.sgst_amount').html('+ ' + sgst.toFixed(2));
    $('.cess_amount').html('+ ' + cess.toFixed(2));
    $('.tds_amount').html('+ ' + tds_amount.toFixed(2));
    $('.amty_amount').html('+ ' + amty.toFixed(2));
}

$(document).ready(function() {

    $('.select2').select2({
        placeholder: 'Choose one',
        width: '100%'
    });

    $('#transport_mode').select2({
        width: '65%'
    });

    var pids = $('input[name="pid[]"]').map(function() {
        return parseInt(this.value); // $(this).val()
    }).get();

    $("#product").on('click', '.btnDelete', function() {

        const index = pids.indexOf($(this).data('id'));
        if (index !== -1) {
            delete pids[index];
        }
        $(this).closest('tr').remove();
        $('#code').attr('disabled', false);
        $('#challan_btn').attr('disabled', true);
        calculate();
    });

    $("#code").select2({
        width: '100%',
        placeholder: 'Type Item Code ',
        ajax: {
            url: PATH + "Milling/Getdata/finish_Item",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });  

    $('.dateMask').mask('99-99-9999');


    $("#account").select2({
        width: '66.5%',
        placeholder: 'Type Account Name',
        ajax: {
            url: PATH + "Master/Getdata/search_account",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {

                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $('#account').on('select2:select', function(e) {
        var data = e.params.data;
        // console.log('enter in this gst vala')
        $('#gst').val(data.gsttin);
        $('#tds_per').val(data.tds);
        $('#tds_limit').val(data.tds_limit);
        $('#acc_state').val(data.state);

        var com_state = parseInt(<?=session('state')?>);

        var acc_state = parseInt($('#acc_state').val());

        if (com_state == acc_state) {

            $("#tax option[value='igst']").remove();
            if ($("#tax option[value='sgst']").length == 0) {
                $('#tax').append('<option value="sgst">sgst</option>');
            }
            if ($("#tax option[value='cgst']").length == 0) {
                $('#tax').append('<option value="cgst">cgst</option>');
            }
            $("#tax option[value='sgst']").attr("selected", "selected");
            $("#tax option[value='cgst']").attr("selected", "selected");

        } else {
            $("#tax option[value='sgst']").remove();
            $("#tax option[value='cgst']").remove();

            if ($("#tax option[value='igst']").length == 0) {
                $('#tax').append('<option value="igst">igst</option>');
            }
            $("#tax option[value='igst']").attr("selected", "selected");
        }

        enable_gst_option();
        calculate();

    });


    $("#broker").select2({
        width: '100%',
        placeholder: 'Type Broker Account',
        ajax: {
            url: PATH + "Master/Getdata/search_broker",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });


    $('#tax').on('select2:select', function(e) {
        var suggestion = e.params.data;
        var tax = $("#tax :selected").map(function(i, el) {
            return $(el).val();
        }).get();

        var igst = document.getElementById("igst");
        var sgst = document.getElementById("sgst");
        var cgst = document.getElementById("cgst");

        $.each(tax, function() {
            if (this == 'igst') {
                igst.style.display = "table-row";
            } else if (this == 'sgst') {
                sgst.style.display = "table-row";
            } else if (this == 'cgst') {
                cgst.style.display = "table-row";
            } else if (this == 'tds') {
                tds.style.display = "table-row";
            } else if (this == 'cess') {
                cess.style.display = "table-row";
            } else {}
        });
    });

    $('#tax').on('select2:unselect', function(e) {
        var suggestion = e.params.data;
        var tax = $("#tax :selected").map(function(i, el) {
            return $(el).val();
        }).get();

        var igst = document.getElementById("igst");
        var sgst = document.getElementById("sgst");
        var cgst = document.getElementById("cgst");
        var tds = document.getElementById("tds");
        var cess = document.getElementById("cess");
        // console.log(tax)
        var tax_array = ['igst', 'sgst', 'cgst', 'cess', 'tds'];
        var diff = arr_diff(tax_array, tax);
        // console.log(diff);

        $.each(diff, function() {
            if (this == 'igst') {
                igst.style.display = "none";
            } else if (this == 'sgst') {
                sgst.style.display = "none";
            } else if (this == 'cgst') {
                cgst.style.display = "none";
            } else if (this == 'cess') {
                cess.style.display = "none";
            } else if (this == 'tds') {
                tds.style.display = "none";
            } else {
                cgst.style.display = "table-row";
            }
            if (this == 'cess') {
                cess.style.display = "none";
            } else {
                cess.style.display = "table-row";
            }
        });
    });


    $("#delivery_address").select2({
        width: '100%',
        placeholder: 'Type Delivery Name',
        ajax: {
            url: PATH + "Master/Getdata/search_account",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $("#transport").select2({
        width: '100%',
        placeholder: 'Type Transport',
        ajax: {
            url: PATH + "Master/Getdata/search_transport",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $("#city").select2({
        width: '100%',
        placeholder: 'Type City',
        ajax: {
            url: PATH + "Master/Getdata/search_city",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });


    $("#vehicle").select2({
        width: '65%',
        placeholder: 'Type Vehicle',
        ajax: {
            url: PATH + "Master/Getdata/search_vehicle",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $("#mill_ac").select2({
        width: '100%',
        placeholder: 'Type Mill Ac Name',
        ajax: {
            url: PATH + "Master/Getdata/search_sun_credit",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $('select[name="screen[]"]').select2({
        
        width: 'resolve',
        placeholder: 'Select JobItem',
        ajax: {
            url: PATH + "Milling/Getdata/finishjob_item",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $("#broker").select2({
        width: '100%',
        placeholder: 'Type Broker Account',
        ajax: {
            url: PATH + "Master/Getdata/search_broker",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $("#transport").select2({
        width: '100%',
        placeholder: 'Type Transport',
        ajax: {
            url: PATH + "Master/Getdata/search_transport",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $("#delivery").select2({
        width: 'resolve',
        placeholder: {
		    id: '', // the value of the option
		    text: 'None Selected'
		},
		allowClear: true,
        ajax: {
            url: PATH + "Master/Getdata/search_accountSundry_cred_debt",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {

                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $('#delivery').on('select2:select', function(e) {
        var data = e.params.data;   
        console.log(data)
        $('textarea[name=delivery_add]').html(data.address);
    });


    $("#warehouse").select2({
        width: '100%',
        placeholder: 'Type Warehouse Account',
        ajax: {
            url: PATH + "Master/Getdata/search_warehouse",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    }); 

    $("#job_challan").select2({
        width: 'resolve',
        placeholder: 'Select Jobwork Challan',
        ajax: {
            url: PATH + "Milling/Getdata/jobwork",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    $('#job_challan').on('select2:select', function(e) {

        var suggesion = e.params.data;
        var item = suggesion.item;
        console.log(item);

        $(".tbody").empty();

        // var com_state = parseInt(<?=session('state')?>);
        // var acc_state = parseInt(suggesion.job.acc_state);

        // if (com_state == acc_state) {

        //     $("#tax option[value='igst']").remove();
        //     if ($("#tax option[value='sgst']").length == 0) {
        //         $('#tax').append('<option value="sgst">sgst</option>');
        //     }
        //     if ($("#tax option[value='cgst']").length == 0) {
        //         $('#tax').append('<option value="cgst">cgst</option>');
        //     }
        //     $("#tax option[value='sgst']").attr("selected", "selected");
        //     $("#tax option[value='cgst']").attr("selected", "selected");

        // } else {
        //     $("#tax option[value='sgst']").remove();
        //     $("#tax option[value='cgst']").remove();

        //     if ($("#tax option[value='igst']").length == 0) {
        //         $('#tax').append('<option value="igst">igst</option>');
        //     }
        //     $("#tax option[value='igst']").attr("selected", "selected");
        // }

        // enable_gst_option();

        for (i = 0; i < item.length; i++) {
            var uom = item[i].uom.split(',');
            var uom_option = '';
            
            for (j = 0; j < uom.length; j++) {
                uom_option += '<option value="' + uom[j] + '">' + uom[j] + '</option>';
            }

            var inp = '<input type="hidden" name="pid[]" value="' + item[i].item_id + '">';
            var tds = '<input type="hidden" name="recJob_ids[]" value="">';
            var tds = '<tr class="' + item[i].item_id + '">';
            
            tds += '<td>#</td>';

            tds += '<td>' + item[i].name  + inp + '</td>';

            tds += '<td>' + item[i].hsn + '</td>';

            tds +=
                '<td><select name="type[]" id="type" value="0" onchange="calculate()">'+ item[i].uom_opt +'</select></td>';

            tds +=
                '<td><div class="input-group select2_height"><select class="form-control select-sm screen" name="screen[]"></select><div class="input-group-prepend"><div class="input-group-text" style="padding:0.045rem 0.30rem;"><a data-toggle="modal" data-target="#fm_model" data-title="Add Finish Job" href="<?=url('Milling/add_finishjob_screen')?>"><i style="font-size:20px;" class="fe fe-plus-circle"></i></a></div></div></div></td>';
            
            tds +=
                '<td><input class="form-control input-sm"  value="0" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)"  required type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + item[i].igst +
                '" name="gst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" readonly value="' + item[i].pcs +
                '" name="sendJOB_pcs[]" onchange="calculate()" readonly onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" readonly name="sendJOB_mtr[]" onchange="calculate()" value="' +
                item[i].meter + '" type="text" ></td>';
            
            tds +=
                '<td><input class="form-control input-sm" readonly name="sendJOB_unit[]" onchange="calculate()" value="' +
                item[i].unit + '" type="text" ></td>';
            
            tds +=
                '<td><input class="form-control input-sm" readonly name="sendJOB_cut[]" onchange="calculate()" value="' +
                item[i].cut + '" type="text" ></td>';
            
            tds +=
                '<td><input class="form-control input-sm" readonly name="remaining_pcs[]" onchange="calculate()" value="' +
                item[i].remaining_pcs + '" type="text" ></td>';

            tds +=
                '<td><input class="form-control input-sm" readonly name="remaining_mtr[]" onchange="calculate()" value="' +
                item[i].remaining_mtr + '" type="text" ></td>';
            
            tds +=
                '<td><input class="form-control input-sm" id="recOB_pcs" value="" name="recJOB_pcs[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" value="" name="recJOB_mtr[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text"></td>';
            
            tds +=
                '<td><input class="form-control input-sm"  value="" name="pending[]" onchange="calculate()" value="" type="text"></td>';
            
            tds +=
                '<td><input class="form-control input-sm" value="" name="subtotal[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)"  type="text"></td>';
            
            tds +=
                '<td><input class="form-control input-sm" value="" name="remark[]" type="text"></td>';
                
            tds += '</tr>';

            $('.tbody').append(tds);
            $('#code').val('');

            $('select[name="screen[]"]').select2({
                width: 'resolve',
                placeholder: 'Select JobItem',
                ajax: {
                    url: PATH + "Milling/Getdata/finishjob_item",
                    type: "post",
                    allowClear: true,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
            calculate();
        }
    });

});


function add_item(data_edit) {
    var type = 'Status';
    var data_val = $(data_edit).data('val');
    var ot_title = $(data_edit).attr('title');
    var pkno = $(data_edit).data('pk');
    var select_input = {
        "mtr": "Mtr",
        "cut": "Cut",
        "pcs": "PCS"
    };
    swal({
        title: ot_title,
        confirmButtonText: "Save",
        input: "select",
        inputValue: data_val,
        inputOptions: select_input,
        showCancelButton: !0,
        inputValidator: function(e) {
            return !e && "You need to write something!"
        }
    }).then(function(result) {
        _data = $.param({
            pk: pkno
        }) + '&' + $.param({
            val: result.value
        }) + '&' + $.param({
            type: type
        }) + '&' + $.param({
            method: $("#table_list_data").data('id')
        });

        if (result.value != undefined && result.value != '') {
            $.post(PATH + "/" + $("#table_list_data").data('module') + "/Action/Update", _data, function(data) {

                if (data.st == 'success') {
                    var selectdata = result.value;
                    $(data_edit).data('val', selectdata);
                    $(data_edit).html(select_input[selectdata]);
                    swal("success!", "Your update successfully!", "success");

                }

            });
        }
    });
}
</script>
<?=$this->endSection()?>