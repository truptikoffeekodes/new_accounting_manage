<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.product{
    width: 140%;
    table-layout: fixed;
    border-collapse: collapse;
    margin-bottom:5px;
}

.table-responsive::-webkit-scrollbar{
    width: 3px;
    height: 12px;
    transition: .3s background;
}
.table-responsive::-webkit-scrollbar-thumb{
    background: #e1e6f1;
}


.select2_height .select2-container .select2-selection--single, .select2-container--default .select2-selection--single .select2-selection__rendered, .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 28px;
}
.select2_height .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 25px!important;
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
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <form action="<?= url('Milling/Add_finish') ?>" class="ajax-form-submit" method="POST"
                        id="challanform">
                        <div class="row">
                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-3">DayBook: <span class="tx-danger">*</span></label>
                                <select class="form-control" id="daybook" required name='daybook'>
                                    <?php if(@$finish['daybook_name']) { ?>
                                    <option value="<?=@$finish['daybook_id']?>">
                                        <?=@$finish['daybook_name']?>(<?=@$finish['daybook_type']?>)
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" name="id" value="<?= @$finish['id'] ? $finish['id'] : $id; ?>">

                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-3">SR No: <span class="tx-danger">*</span></label>
                                <input class="form-control col-md-9" type="text" name="srno" id="srno" readonly
                                    value="<?= @$finish['sr_no'] ? $finish['sr_no'] : $current_id; ?>"
                                    placeholder="Enter SR No" required>
                            </div>

                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-3">Select Challan No.: <span
                                        class="tx-danger">*</span></label>
                                <select class="form-control col-md-9" id="gray_challan" name='gray_challan'>
                                    <?php if(!empty($finish['gray_no'])) { ?>
                                    <option selected value="<?=@$finish['gray_no']?>">
                                        <?=@$finish['challan_detail']?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-3">Invoice Date.: <span
                                        class="tx-danger">*</span></label>
                                <input class="form-control lr_date dateMask col-md-9" placeholder="DD-MM-YYYY"
                                    type="text" id="date" name="date"
                                    value="<?= @$finish['date'] ? user_date($finish['date']) : user_date(date('Y-m-d')); ?>">
                            </div>

                            <div class="col-lg-5 form-group">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Transport Mode: </label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="form-control transport_mode" id="transport_mode"
                                            name="trasport_mode">
                                            <option <?= ( @$finish['transport_mode'] == 'AIR' ? 'selected' : '' ) ?>
                                                value="AIR">AIR</option>
                                            <option <?= ( @$finish['transport_mode'] == 'ROAD' ? 'selected' : '' ) ?>
                                                value="ROAD">ROAD</option>
                                            <option <?= ( @$finish['transport_mode'] == 'RAIL' ? 'selected' : '' ) ?>
                                                value="RAIL">RAIL</option>
                                            <option <?= ( @$finish['transport_mode'] == 'SHIP' ? 'selected' : '' ) ?>
                                                value="SHIP">SHIP</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Party Ac Name: <span
                                                class="tx-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="form-control account" id="account" name='account'>
                                            <?php if(@$finish['party_name']) { ?>
                                            <option value="<?=@$finish['party_name']?>">
                                                <?=@$finish['account_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <!-- <input type="hidden" name="id" value="<?= @$finish['id']; ?>"> -->
                                        <input type="hidden" name="tds_per" id="tds_per"
                                            value="<?= @$finish['tds_per']; ?>">
                                        <input type="hidden" name="tds_limit" id="tds_limit"
                                            value="<?= @$finish['tds_limit']; ?>">
                                        <input type="hidden" name="acc_state" id="acc_state"
                                            value="<?= @$finish['acc_state']; ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Add Item: <span class="tx-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="form-control" id="code" name='code'>

                                        </select>
                                        <p class="text-success" id="suggesion"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7 form-group">
                                <div class="row">

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR No: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control lrno" name="lrno" value="<?= @$finish['lr_no']; ?>"
                                            placeholder="LR No." type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR Date: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control lr_date dateMask" placeholder="DD-MM-YYYY"
                                            type="text" id="lr_date" name="lr_date" value="<?= @user_date($finish['lr_date']); ?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label ">Weight: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control weight" name="weight"
                                            value="<?= @$finish['weight']; ?>" 
                                            placeholder="Enter Weight"  type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Freight: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control freight" name="freight"
                                            value="<?= @$finish['freight']; ?>" placeholder="00" type="text">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <!-- <button data-toggle="modal" type="button" id="challan_btn" href="<?= url('Milling/Add_item/'). @$id; ?>" data-target="#fm_model" 
                                            data-title="Add Item" class="btn btn-primary">Add Challan</button> -->
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label class="form-label">Delivery Account: </label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <select class="form-control" id="delivery_code" name='delivery_code'>
                                            <?php if(@$finish['delivery_name']) { ?>
                                            <option value="<?=@$finish['delivery_code']?>">
                                                <?=@$finish['delivery_name']?>
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
                                            <td style="width:10px; border:0px;" ></td>
                                            <td style="width:80px; border:0px;" ></td>
                                            <td style="width:100px; border:0px;" ></td>
                                            <td style="width:100px; border:0px;" ></td>
                                            <td style="width:170px; border:0px;" ></td>
                                            <td style="width:100px; border:0px;" ></td>
                                            <td style="width:70px; border:0px;" ></td>
                                            <td colspan="3"><h6><center> Send To MILL</center></h6></td>
                                            <th colspan="3"><h6><center> Return From MILL</center></h6></th>
                                        </tr>
                                        <tr>
                                            <th style="width:10px;">#</th>
                                            <th style="width:80px;">Challan</th>
                                            <th style="width:100px;">Item</th>
                                            <th style="width:100px;">Type</th>
                                            <th style="width:150px;">Select Screen</th>
                                            <th style="width:100px;">Service Rate</th>
                                            <th style="width:70px;">Gst</th>
                                            <th style="width:70px;">PCS</th>
                                            <th style="width:90px;">Cut</th>
                                            <th style="width:80px;">Mtr</th>
                                            <th style="width:80px;">RPCS</th>
                                            <th style="width:80px;">RCut</th>
                                            <th style="width:100px;">RMtr</th>
                                            <th style="width:120px;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        <?php 
                                        $total=0;
                                        if(isset($item)) {  
                                            foreach($item as $row){
                                                $sub_total=$row['price'] * $row['pcs'] ;
                                                $total += $sub_total;
                                                // echo '<pre>';print_r($row);exit;
                                                //$uom=explode(',',$row['item_uom']);
                                        ?>
                                        <tr class="<?=$row['pid']?>">
                                            <input type="hidden" name="tot_challan_cut[]" value="<?=@$row['tot_cut']?>">
                                            <input type="hidden" name="all_gray[]" id="all_gray"
                                                value="<?=@$row['whole_gray']?>">
                                            <input type="hidden" name="tot_grey[]" id="add_item"
                                                value="<?=$row['tot_grey']?>">
                                            <input type="hidden" name="challan_uom[]" value="<?=$row['challan_uom']?>">
                                            <input type="hidden" name="tot_mill[]" value="<?=@$row['send_mill']?>">
                                            <input type="hidden" name="def_cut[]" id="def_cut"
                                                value="<?=@$row['default_cut']?>">
                                            <input type="hidden" name="tot_recMill[]" id="tot_recMill"
                                                value="<?=@$row['tot_rec']?>">
                                            <input type="hidden" name="tot_finish_cut[]" id="tot_finish_cut"
                                                value="<?=@$row['tot_finish_cut']?>">

                                            <td><a class="tx-danger btnDelete" data-id="<?=$row['id']?>" title="0"><i
                                                        class="fa fa-times tx-danger"></i></a></td>
                                            <input type="hidden" name="pid[]" value="<?=$row['pid']?>">
                                            <input type="hidden" name="millItem_id[]" value="<?=$row['millitem_id']?>">
                                            <td><a data-toggle="modal" type="button" id="challan_btn"
                                                    href="<?= url("Milling/add_finish_item/").$row['pid'].'/'.$row['millitem_id']?> "
                                                    data-target="#fm_model" data-title="Add Item" class="">Add
                                                    Challan</a></td>
                                            
                                            <td><?=$row['name']?>(<?=$row['code']?>) </td>
                                            
                                            <td>
                                                <select class ="form-control select-sm" id="type" name="type[]" onchange="calculate()">
                                                    <option value="pcs"
                                                        <?= ( @$row['mitype'] == "pcs" ? 'selected' : '' ) ?>>PCS
                                                    </option>
                                                    <option value="mtr"
                                                        <?= ( @$row['mitype'] == "mtr" ? 'selected' : '' ) ?>>Mtr
                                                    </option>
                                                    <option value="cut"
                                                        <?= ( @$row['mitype'] == "cut" ? 'selected' : '' ) ?>>Cut
                                                    </option>
                                                </select>
                                            </td>

                                            <td>
                                                <div class="input-group select2_height">
                                                    <select class="form-control select-sm screen" name="screen[]">
                                                        <?php if(!empty(@$row['screen'])) {?>
                                                            <option value="<?= @$row['screen']?>">
                                                                <?= @$row['screen_name'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>

                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text" style="padding:0.045rem 0.30rem;">
                                                            <a data-toggle="modal" data-target="#fm_model"
                                                                data-title="Add Item Group"
                                                                href="<?=url('Milling/add_finish_screen')?>"><i
                                                                    style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$row['finish_price']?>"
                                                    name="price[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"></td>
                                            <td><input class="form-control input-sm" value="<?=$row['igst']?>"
                                                    name="igst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"></td>
                                            <td><input class="form-control input-sm" value="<?=$row['send_pcs']?>"
                                                    name="pcs[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['cut']?>"
                                                    name="cut[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"></td>

                                            <td><input type="hidden" name="meter[]" value="<?=@$row['meter']?>"
                                                    id="meter">
                                                <input class="form-control input-sm" value="<?=$row['mtr']?>"
                                                    name="mtr[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text">
                                            </td>

                                            <td><input class="form-control input-sm" id="finish_pcs"
                                                    value="<?=@$row['finish_pcs']?>" name="finish_pcs[]"
                                                    onchange="calculate()" onkeypress="return isDesimalNumberKey(event)"
                                                    required type="text"></td>
                                                    
                                            <td><input class="form-control input-sm" value="<?=@$row['finish_cut']?>"
                                                    name="tot_finishcut[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"></td>

                                            <td><input type="hidden" name="rec_meter[]" value="<?=@$row['finish_mtr']?>"
                                                    id="rec_meter">
                                                <input class="form-control input-sm" value="<?=@$row['finish_mtr']?>"
                                                    name="rec_mtr[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text">
                                            </td>
                                            <td><input class="form-control input-sm" value="<?=$sub_total?>"
                                                    name="subtotal[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"></td>
                                        </tr>
                                        <?php } }?>
                                    </tbody>
                                    <tfoot>
                                        <td colspan="2" class="text-right">Total</td>
                                        <td class="amount_total"></td>
                                        <td class="amount_total"></td>
                                        <td class="IGST_total"></td>
                                        <td class="CGST_total"></td>
                                        <td class="SGST_total"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>   
                                        <td></td>
                                        <td class="total"><?=@$total; ?></td>
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
                                                                value="<?= @$finish['discount']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 disc_type" name="disc_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?=(@$finish['disc_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?=(@$finish['disc_type'] == '%' ? 'selected' : '' ) ?>
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
                                                                name="amtx" type="text"
                                                                value="<?= @$finish['amtx']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 amtx_type" name="amtx_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?= ( @$finish['amtx_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$finish['amtx_type'] == '%' ? 'selected' : '' ) ?>
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
                                                                name="amty" type="text"
                                                                value="<?= @$finish['amty']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 amty_type" name="amty_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?= ( @$finish['amty_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$finish['amty_type'] == '%' ? 'selected' : '' ) ?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="amty_amount wd-90"></th>
                                                </tr>
                                                <?php 
                                                    $taxes = json_decode(@$finish['taxes']);
                                                ?>
                                                <tr>
                                                    <th>Select Tax</th>
                                                    <th colspan="2" class="wd-300">
                                                        <div class="input-group-sm">
                                                            <select class="select2 tax" id="tax" name="taxes[]"
                                                                onchange="calculate()" multiple>
                                                                <?php foreach($tax as $row) { 
                                                                        if($row['name'] == 'igst' && session('state') != @$finish['acc_state']) {
                                                                ?>
                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else if($row['name'] == 'cgst'  && session('state') == @$finish['acc_state']){ ?>

                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else if($row['name'] == 'sgst'  && session('state') == @$finish['acc_state']){ ?>

                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else if($row['name'] == 'tds' || $row['name'] == 'cess' ) { ?>

                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else{ if(!@$finish)  { ?>
                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>
                                                                <?php } } } ?>

                                                            </select>
                                                        </div>
                                                    </th>
                                                </tr>

                                                <tr id="igst"
                                                    style="display:<?php if(!empty($taxes)) {  echo  (in_array("igst", $taxes)) ? 'table-row;' : 'none;' ; }else{ echo 'none;'; }  ?>">
                                                    <th>(+)IGST</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control igst" readonly
                                                                onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_igst" type="text"
                                                                value="<?= @$finish['tot_igst']; ?>">
                                                        </div>
                                                    </th>
                                                    <th class="igst_amount wd-90"></th>
                                                </tr>

                                                <tr id="sgst"
                                                    style="display:<?php if(!empty($taxes)) { echo in_array("sgst", $taxes) ? 'table-row;' : 'none;'; } else{ echo 'none;'; } ?>">
                                                    <th>(+)SGST</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control sgst" readonly
                                                                onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_sgst" type="text"
                                                                value="<?= @$finish['tot_sgst']; ?>">
                                                        </div>
                                                    </th>
                                                    <th class="sgst_amount wd-90"></th>
                                                </tr>

                                                <tr id="cgst"
                                                    style="display:<?php if(!empty($taxes)) { echo in_array("cgst", $taxes) ? 'table-row;' : 'none;'; } else{ echo 'none;'; } ?>">
                                                    <th>(+)CGST</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control cgst" readonly
                                                                onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_cgst" type="text"
                                                                value="<?= @$finish['tot_cgst']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="cgst_amount wd-90"></th>
                                                </tr>

                                                <tr id="tds"
                                                    style="display:<?php if(!empty($taxes)) { echo in_array("tds", $taxes) ? 'table-row;' : 'none;'; }else{ echo 'none;'; } ?>">
                                                    <th>(+)TDS</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control tds" readonly
                                                                onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tds_amt" type="text"
                                                                value="<?= @$finish['tds_amt']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="tds_amount wd-90"></th>
                                                </tr>

                                                <tr id="cess"
                                                    style="display:<?php if(!empty($taxes)) { echo in_array("cess", $taxes) ? 'table-row;' : 'none;'; }else{echo 'none;';} ?> ">
                                                    <th>(+)Cess</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control cess" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="cess" type="text"
                                                                value="<?= @$finish['cess']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 cess_type" name="cess_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?= ( @$finish['cess_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$finish['cess_type'] == '%' ? 'selected' : '' ) ?>
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
</div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
<?php 

if(isset($id)){?>

calculate();
<?php } ?>


function validate_autocomplete(obj, val) {
    if ($('#' + val).val() == '') {
        $('.' + val).html('Option Select from dropdown list')
    } else {
        $('.' + val).html('')
    }
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
                window.location = "<?=url('Milling/Finish')?>";
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



function calculate() {

    var qty = $('input[name="qty[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var item_disc = $('input[name="item_disc[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var price = $('input[name="price[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var igst = $('input[name="igst[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var pcs = $('input[name="pcs[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var meter = $('input[name="meter[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var mtr = $('input[name="mtr[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var rec_mtr = $('input[name="rec_mtr[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var cut = $('input[name="cut[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var type = $('select[name="type[]"]').map(function() {
        return this.value;
    }).get();

    var total = 0.0;
    var igst_amt = 0.0;
    var tot_item_brok = 0.0;
    var tot_fix_brok = 0.0;
    var mtr_total = 0;
    // for (var i = 0; i < pcs.length; i++) {

    //     if (cut[i] != 'undefine' && !isNaN(cut[i])) {
    //         var mtr_total = meter[i] - cut[i];
    //         // var sub = price[i] * mtr_total;
    //         $('input[name="mtr[]"]').eq(i).val(mtr_total);
    //     }
    // }
    // var mtr = $('input[name="mtr[]"]').map(function() {
    //     return parseFloat(this.value);
    // }).get();


    for (var i = 0; i < pcs.length; i++) {
        if (type[i] == "pcs") {
            var sub = price[i] * pcs[i];
            // var disc_amt = sub * item_disc[i] / 100;
            var final_sub = sub;
            $('input[name="subtotal[]"]').eq(i).val(final_sub);
            igst_amt += final_sub * igst[i] / 100;
            total += final_sub;

        } else {
            var sub = price[i] * rec_mtr[i];
            $('input[name="mtr[]"]').eq(i).val(mtr[i]);
            // var disc_amt = sub * item_disc[i] / 100;
            var final_sub = sub;
            igst_amt += final_sub * igst[i] / 100;
            $('input[name="subtotal[]"]').eq(i).val(final_sub);
            total += final_sub;
        }
    }

    $('.total').html(total);

    // tot_fix_brok = total * fix_brokrage/100;

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

    // console.log(cess)

    var discount_type = $('select[name=disc_type] option').filter(':selected').val();
    var amtx_type = $('select[name=amtx_type] option').filter(':selected').val();
    var amty_type = $('select[name=amty_type] option').filter(':selected').val();
    var cess_type = $('select[name=cess_type] option').filter(':selected').val();


    if (discount_type == '%') {
        discount_amount = (total * (discount / 100));
        $('.discount_amount').html('- ' + discount_amount);
        if (discount_amount > 0) {
            var total = 0;
            var divide_disc = discount_amount / pcs.length;
            var igst_amt = 0;
            for (var i = 0; i < pcs.length; i++) {

                var sub = pcs[i] * price[i];
                //var disc_amt = sub * item_disc[i] / 100;
                var final_sub = sub;

                var abc = final_sub - divide_disc;
                igst_amt += abc * igst[i] / 100;
                total += abc;
            }


            $('.total').html(total);

        }
    } else {
        $('.discount_amount').html('- ' + discount);
        if (discount > 0) {
            var total = 0;
            var divide_disc = discount / pcs.length;
            var igst_amt = 0;
            for (var i = 0; i < pcs.length; i++) {

                var sub = pcs[i] * price[i];
                // var disc_amt = sub * item_disc[i] / 100;
                var final_sub = sub;

                var abc = final_sub - divide_disc;
                igst_amt += abc * igst[i] / 100;
                // console.log('final_sub :'+final_sub);
                // console.log('item_disc :'+item_disc[i]);
                // console.log('abc :'+abc);
                // console.log('igst :'+igst[i]);
                // console.log('igst_amt :'+igst_amt);
                total += abc;
            }
        }
    }
    var grand_total = total;
    // grand_total = grand_total + igst_amt;


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
        grand_total += amty;
    }

    if (cess_type == '%') {
        cess_amount = (total * (cess / 100));
        $('.cess_amount').html('+ ' + cess_amount);
        grand_total += (total * (cess / 100));
    } else {
        $('.cess_amount').html('+ ' + amty);
        grand_total += cess;
    }
    var tds_amount = 0;

    if (tds_per != '') {
        tds_amount = (total * (tds_per / 100));
        grand_total += tds_amount;
    }

    var cgst = igst_amt / 2;
    var sgst = igst_amt / 2;

    // if(brok_type == "item_wise"){
    //     $('#brokrage').val('+' + tot_item_brok);        
    //     $('#broker_led').val('-' +tot_item_brok);        
    // }else{
    //     $('#brokrage').val('+' +tot_fix_brok);
    //     $('#broker_led').val('-' +tot_fix_brok);
    // }

    var tax_option = $("#tax :selected").map(function(i, el) {
        return $(el).val();
    }).get();

    $.each(tax_option, function() {
        if (this == 'igst') {
            console.log('igst_amt' + igst_amt)
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


function subtotal(type) {
    var pcs = $("#pcs").val();
    var mtr = $("#mtr").val();
    var cut = $("#cut").val();
    var rate = $("#rate").val();
    //console.log(pcs);
    //console.log(mtr);
    //console.log(cut);
    //console.log(rate);
    //alert(pcs);
    if (type == 'pcs') {
        stotal = pcs * rate;
        //amount=
    } else if (type == 'mtr') {
        stotal = mtr * rate;
    } else {
        subtotal = cut * rate;
    }
    $("#subt").val(stotal);
    //alert(type);
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

    $('select[name="screen[]"]').select2({
        width: '75%',
        placeholder: 'Type finish',
        ajax: {
            url: PATH + "Master/Getdata/finish_item",
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

    $("#code").select2({
        width: '100%',
        placeholder: 'Type Item Code ',
        ajax: {
            url: PATH + "Milling/Getdata/Item",
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

    $('#code').on('select2:select', function(e) {
        var suggestion = e.params.data;
        if (pids.toString().indexOf(suggestion.data) == -1) {

            var inp = '<input type="hidden" name="pid[]" value="' + suggestion.id + '">';

            var tds = '<tr class="' + suggestion.id + '" >';
            tds += '<input type="hidden" name="tot_challan_cut[]" >';
            tds += '<input type="hidden" name="def_cut[]" id="def_cut">';
            tds += '<input type="hidden" name="all_gray[]" id="all_gray">';
            tds += '<input type="hidden" name="tot_grey[]" id="add_item">';
            tds += '<input type="hidden" name="challan_uom[]" >';
            tds += '<input type="hidden" name="tot_mill[]" >';
            tds += '<input type="hidden" name="tot_finish_cut[]" id="tot_finish_cut" value="">';
            tds += '<input type="hidden" name="tot_recMill[]" id="tot_recMill" >';
            tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
            tds +=
                '<td><a data-toggle="modal" type="button" id="challan_btn" href="<?= url("Milling/add_finish_item/");?>' +
                suggestion.id +
                '" data-target="#fm_model" data-title="Add Item" class="">Add Challan</a></td>';
            tds += '<td>' + suggestion.text + inp + '</td>';
            tds +=
                '<td><select name="type[]" id="type" value="0" onchange="calculate()"><option selected value="pcs">PCS</option><option value="cut">Cut</option><option value="mtr">Mtr</option></select></td>';

            tds += '<td><input id="rate" class="form-control input-sm" value="' + suggestion.price
                .sales_price +
                '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';
            
            tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                .igst +
                '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" id="pcs" value="" name="pcs[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" id="cut" value="" name="cut[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';


            tds +=
                '<td><input type="hidden" id="meter" name="meter[]"><input class="form-control input-sm" id="mtr" value="" name="mtr[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" id="subt" name="subtotal[]" onchange="calculate()" value="0" required="" type="text" readonly></td>';
            tds += '</tr>';

            $('.tbody').append(tds);
            $('#code').val('');
            $('#suggesion').html('Please Add Challan Detail..!!');

            calculate();
        } else {
            $('.product_error').html('Selected Product Already Added');
            $('#code').val('');
        }
    });

;

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

        $('#gst').val(data.gsttin);
        $('#tds_per').val(data.tds);
        $('#tds_limit').val(data.tds_limit);
        $('#acc_state').val(data.state);
        // $('.igst').val(suggestion.price.igst);
        // $('.cgst').val(suggestion.price.cgst);
        // $('.sgst').val(suggestion.price.sgst);

        var com_state = parseInt(<?= session('state') ?>);

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


    $("#search_class").select2({
        width: 'resolve',
        placeholder: 'Type class Name',
        ajax: {
            url: PATH + "Master/Getdata/search_class",
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
                // cgst.style.display="table-row";
            }
            // if(this == 'cess'){
            //     cess.style.display="none";
            // }else{
            //     cess.style.display="table-row";
            // } 

        });

    });



    $("#delivery_code").select2({
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

    $("#daybook").select2({
        width: '65%',
        placeholder: 'Daybook',
        ajax: {
            url: PATH + "Master/Getdata/search_daybook",
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

    $("#gray_challan").select2({
        width: 'resolve',
        placeholder: 'Type Mill Ac Name',
        ajax: {
            url: PATH + "Milling/Getdata/get_challan",
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

    $('#gray_challan').on('select2:select', function(e) {
        
        $(".tbody").empty();
        var suggesion = e.params.data;
        console.log(suggesion)
        var item = suggesion.item;

        var igst = document.getElementById("igst");
        var sgst = document.getElementById("sgst");
        var cgst = document.getElementById("cgst");
        var tds = document.getElementById("tds");
        var cess = document.getElementById("cess");

        var acc = '<option selected value="' + suggesion.finish.party_name + '">' + suggesion.finish
            .account_name + '</option>';
        var deli = '<option selected value="' + suggesion.finish.delivery_code + '">' + suggesion
            .finish.delivery_name + '</option>';

        var tran_mode = '<option selected value="' + suggesion.finish.transport_mode + '">' + suggesion
            .finish.transport_mode + '</option>';
        var disc_type = '<option selected value="' + suggesion.finish.disc_type + '">' + suggesion
            .finish.disc_type + '</option>';
        var amtx_mode = '<option selected value="' + suggesion.finish.amtx_type + '">' + suggesion
            .finish.amtx_type + '</option>';
        var amty_mode = '<option selected value="' + suggesion.finish.amty_type + '">' + suggesion
            .finish.amty_type + '</option>';
        var cess_mode = '<option selected value="' + suggesion.finish.cess_type + '">' + suggesion
            .finish.cess_type + '</option>';

        // var tax_option = [
        //     {
        //         id:1,
        //         text:"test"
        //     }
        // ]
        cno = suggesion.finish.challan_no;
        //console.log(cno);
        $('#addinvoice').attr('href', 'Add_finishitem/' + cno);
        $('.account').append(acc);
        $('.delivery_code').append(deli);
        $('.transport_mode').append(tran_mode);
        $('.disc_type').append(disc_type);
        $('.amtx_type').append(amtx_mode);
        $('.amty_type').append(amty_mode);
        $('.cess_type').append(cess_mode);
        $('.challan_no').val(suggesion.finish.challan_no)
        $('.challan_date').val(suggesion.finish.challan_date)
        $('.gst_no').val(suggesion.finish.gst);
        $('.lrno').val(suggesion.finish.lr_no);
        $('.lr_date').val(suggesion.finish.lr_date);
        $('.igst').val(suggesion.finish.tot_igst);
        $('.cgst').val(suggesion.finish.tot_cgst);
        $('.sgst').val(suggesion.finish.tot_sgst);
        $('.amtx').val(suggesion.finish.amtx);
        $('.amty').val(suggesion.finish.amty);
        $('.cess').val(suggesion.finish.cess);
        $('.tds_per').val(suggesion.finish.tds_per);
        $('.tds_amount').val(suggesion.finish.tds_amt);
        $('.discount').val(suggesion.finish.discount);
        $('.net_amount').val(suggesion.finish.net_amount);
        $('.weight').val(suggesion.finish.weight);
        $('.freight').val(suggesion.finish.freight);
        $('#acc_state').val(suggesion.finish.acc_state);
        $('#tds_per').val(suggesion.finish.tds_per);
        $('#tds_limit').val(suggesion.finish.tds_limit);

        //$('#subt').val(suggesion.finish.amount);
        //console.log(suggesion.finish.amount);

        var taxes_str = suggesion.finish.taxes;
        var taxes_arr = JSON.parse(taxes_str);

        for (i = 0; i < taxes_arr.length; i++) {

            var newOption = new Option(taxes_arr[i], taxes_arr[i], true, true);
            $('#tax').append(newOption).trigger('change');
        }
        $.each(taxes_arr, function() {
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

        for (i = 0; i < item.length; i++) {
            //  console.log(item[i].brokrage);
            var uom = item[i].mitype.split(',');

            var uom_option = '';
            for (j = 0; j < uom.length; j++) {
                uom_option += '<option value="' + uom[j] + '">' + uom[j] + '</option>';
            }

            var inp = '<input type="hidden" name="pid[]" value="' + item[i].id + '">';

            var tds = '<tr class="' + item[i].id + '">';
            tds += '<input type="hidden" name="tot_challan_cut[]" value="' + item[i].tot_cut + '">';
            tds += '<input type="hidden" name="def_cut[]" id="def_cut" value="' + item[i].default_cut +
                '">';
            tds += '<input type="hidden" name="all_gray[]" id="all_gray" value="' + item[i].whole_gray +
                '">';
            tds += '<input type="hidden" name="tot_grey[]" id="add_item" value="' + item[i].tot_grey +
                '">';
            tds += '<input type="hidden" name="tot_finish_cut[]" id="tot_finish_cut" value="">';
            tds += '<input type="hidden" name="tot_recMill[]" id="tot_recMill" >';
            tds += '<input type="hidden" name="millItem_id[]" value="' + item[i].Mitem_id + '" >';

            tds += '<input type="hidden" name="challan_uom[]" value="' + item[i].challan_uom + '" >';
            tds += '<input type="hidden" name="tot_mill[]" value="' + item[i].send_mill + '">';
            tds += '<td><a class="tx-danger btnDelete" data-id="' + item[i].id +
                '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
            tds +=
                '<td><a data-toggle="modal"  type="button" id="challan_btn" href="<?= url("Milling/add_finish_item/");?>' +
                item[i].id + "/" + item[i].Mitem_id +
                '" data-target="#fm_model" data-title="Add Item" class="">Add Challan</a></td>';
            tds += '<td>' + item[i].name + '(' + item[i].code + ')' + inp + '</td>';
            tds += '<td><select name="type[]" class="form-control select-sm" id="type">' + uom_option + '</select></td>';
            
            tds += '<td><div class="input-group select2_height"><select class="form-control select-sm screen" name="screen[]"></select><div class="input-group-prepend"><div class="input-group-text" style="padding:0.045rem 0.30rem;"><a data-toggle="modal" data-target="#fm_model" data-title="Add Finish Item" href="<?=url('Milling/add_finish_screen')?>"><i style="font-size:20px;" class="fe fe-plus-circle"></i></a></div></div></div></td>';
            
            tds += '<td><input class="form-control input-sm" value="' + item[i].sales_price +
                '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';
            // tds += '<td><input class="form-control input-sm" value="' + item[i].rate +
            //     '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds += '<td><input type="hidden" value="' + item[i].igst +
                '" name ="item_brokrage[]"><input class="form-control input-sm" value="' + item[i]
                .igst +
                '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + item[i].pcs +
                '" name="pcs[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + item[i].cut +
                '" name="cut[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds +=
                '<td><input type="hidden" name="meter[]" value="' + item[i].meter +
                '" id="meter"><input class="form-control input-sm" name="mtr[]" onchange="calculate()" value="' +
                item[i].mtr + '" type="text" ></td>';

            tds +=
                '<td><input class="form-control input-sm" id="finish_pcs" value="" name="finish_pcs[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="" required="" type="text"></td>';
            tds +=
                '<td><input class="form-control input-sm" value="" name="tot_finishcut[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" required="" type="text"></td>';

            tds +=
                '<td><input type="hidden" name="rec_meter[]" value="<?=@$row['rec_meter']?>" id="rec_meter"> <input class="form-control input-sm" value="" name="rec_mtr[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" required="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="' +
                item[i].amount + '" type="text" readonly></td>';
            // tds +=
            //     '<td><input class="form-control input-sm" name="remark[]" value="' + item[i].remark +
            //     '" placeholder="Remark" type="text"></td>';
            tds += '</tr>';
            //console.log(item[i].amount);
            $('.tbody').append(tds);
            $('#code').val('');
            calculate();

            $('select[name="screen[]"]').select2({
        width: '75%',
        placeholder: 'Type finish',
        ajax: {
            url: PATH + "Master/Getdata/finish_item",
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
        }
        //console.log(suggesion);
        // $('.account').val();
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
<?= $this->endSection() ?>