<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.product {
    width: 140%;
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
        <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Mill</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>

    <div class="ml-auto pd-r-100">
        <h2 class="mb-1 font-weight-bold"><span>Mill Received Sr No :</span>
            <?= @$challan['sr_no'] ? $challan['sr_no'] : $current_id; ?></h2>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <form action="<?= url('Milling/add_rec_mill') ?>" class="ajax-form-submit" method="POST"
                        id="challanform">
                        <div class="row">

                            <input type="hidden" name="id" value="<?= @$challan['id'] ? $challan['id'] : $id; ?>">
                            <input type="hidden" name="srno" value="<?= @$challan['sr_no'] ? $challan['sr_no'] : $current_id; ?>">

                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-3">Mill Issue SR No: <span
                                        class="tx-danger">*</span></label>
                                <select class="form-control col-md-9" id="mill_challan" name="mill_challan">
                                    <?php if(!empty($challan['challan_no'])) { ?>
                                    <option selected value="<?=@$challan['challan_no']?>">
                                        <?=@$challan['challan_name']?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-3">Date.: <span class="tx-danger">*</span></label>
                                <input class="form-control lr_date dateMask col-md-9" placeholder="DD-MM-YYYY"
                                    type="text" id="date" name="date"
                                    value="<?= @$challan['date'] ? user_date($challan['date']) : user_date(date('Y-m-d')); ?>">
                                    
                            </div>

                            <div class="col-lg-5 form-group">
                                <div class="row">

                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Transport Mode: </label>
                                    </div>

                                    <div class="col-md-8 form-group">
                                        <select class="form-control transport_mode" id="transport_mode"
                                            name="trasport_mode">

                                            <option <?= ( @$challan['transport_mode'] == 'ROAD' ? 'selected' : '' ) ?>
                                                value="ROAD">ROAD</option>
                                            <option <?= ( @$challan['transport_mode'] == 'AIR' ? 'selected' : '' ) ?>
                                                value="AIR">AIR</option>
                                            <option <?= ( @$challan['transport_mode'] == 'RAIL' ? 'selected' : '' ) ?>
                                                value="RAIL">RAIL</option>
                                            <option <?= ( @$challan['transport_mode'] == 'SHIP' ? 'selected' : '' ) ?>
                                                value="SHIP">SHIP</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Mill AC: <span class="tx-danger">*</span></label>
                                    </div>

                                    <div class="col-md-8 form-group">
                                        <div class="input-group" style="width:auto;">

                                            <select class="form-control account" id="account" name='account'>
                                                <?php if(@$challan['mill_ac']) { ?>
                                                <option value="<?=@$challan['mill_ac']?>">
                                                    <?=@$challan['millAc_name']?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_account') ?>"
                                                        data-target="#fm_model" data-title="Enter Account"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <input type="hidden" name="id" value="<?= @$challan['id']; ?>"> -->
                                        <input type="hidden" name="tds_per" id="tds_per"
                                            value="<?= @$challan['tds_per']; ?>">
                                        <input type="hidden" name="tds_limit" id="tds_limit"
                                            value="<?= @$challan['tds_limit']; ?>">
                                        <input type="hidden" name="acc_state" id="acc_state"
                                            value="<?= @$challan['acc_state']; ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Shiped to Ac: </label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <div class="input-group" style="width:auto;">
                                            <select class="form-control" id="delivery" name='delivery_ac'>
                                                <option value=""> Not One</option>
                                                <?php if(@$challan['delivery_ac']) { ?>
                                                <option selected value="<?=@$challan['delivery_ac']?>">
                                                    <?=@$challan['delivery_ac_name']?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_account') ?>"
                                                        data-target="#fm_model" data-title="Enter Account"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label">shiped to Address: </label>
                                    </div>

                                    <div class="col-md-8 form-group">
                                        <textarea class="form-control delivery" name="delivery_code" value=""
                                            placeholder="Delivery Address"
                                            type="text"><?= @$challan['delivery_code']; ?></textarea>
                                    </div>


                                    <div class="col-md-3 form-group">
                                        <label class="form-label">Lot No: <span class="tx-danger">*</span> </label>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="lot_no" value="<?=@$challan['lot_no']?>"
                                            required placeholder="Lot No" type="text">
                                    </div>

                                    <div class="col-md-5 form-group">

                                        <label class="ckbox"><input type="checkbox"
                                                <?=@$challan['is_lot_complete'] == 1 ?'checked' : '' ?>
                                                name="is_LotComplete"><span>Lot
                                                Completed</span></label>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-7 form-group">
                                <div class="row">

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR No.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control lrno" name="lrno" value="<?= @$challan['lr_no']; ?>"
                                            placeholder="LR No." type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR Date.: </label>
                                    </div>
                                    <?php 
                                        $lr_date = user_date(@$challan['lr_date']);
                                    ?>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control dateMask lr_date" placeholder="DD/MM/YYYY"
                                            type="text" id="lr_date" name="lr_date" value="<?= @$lr_date; ?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Weight: </label>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <input class="form-control weight" name="weight"
                                            value="<?= @$challan['weight']; ?>" placeholder="0.00"
                                            placeholder="Enter Weight" type="text">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Freight: </label>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <input class="form-control freight" name="freight"
                                            value="<?= @$challan['freight']; ?>" placeholder="00" type="text">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label class="form-label"> Warehouse: </label>
                                    </div>

                                    <div class="col-md-8 form-group">
                                        <select class="form-control warehouse" id="warehouse" name='warehouse'>
                                            <?php if(@$challan['warehouse_name']) { ?>
                                            <option value="<?=@$challan['warehouse']?>">
                                                <?=@$challan['warehouse_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label"> Broker: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <select class="form-control broker" id="broker" name='broker'>
                                            <?php if(@$challan['broker']) { ?>
                                            <option value="<?=@$challan['broker']?>">
                                                <?=@$challan['broker_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>


                                    <div class="col-md-2 form-group">
                                        <label class="form-label"> Transport </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <select class="form-control broker" id="transport" name='transport'>
                                            <?php if(@$challan['transport']) { ?>
                                            <option value="<?=@$challan['transport']?>">
                                                <?=@$challan['transport_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- <a target="_blank"   title="Add Item:<?=@$current_id?>" onclick="add_item(this)"  data-val="<?=@$current_id?>" data-pk="<?=@$current_id?>" tabindex="-1" class="btn btn-primary">Add Item</a> -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0 product" border="1" id="product">
                                    <thead>
                                        <tr>
                                            <td style="width:30px; border:0px;"></td>
                                            <td style="width:100px; border:0px;"></td>
                                            <td style="width:70px; border:0px;"></td>
                                            <td style="width:110px; border:0px;"></td>
                                            <td style="width:170px; border:0px;"></td>
                                            <td style="width:70px; border:0px;"></td>
                                            <td style="width:70px; border:0px;"></td>
                                            <td colspan="2">
                                                <h6>
                                                    <center> MILL ISSUE</center>
                                                </h6>
                                            </td>
                                            <td colspan="2">
                                                <h6>
                                                    <center> MILL RETURN</center>
                                                </h6>
                                            </td>
                                            <th colspan="3">
                                                <h6>
                                                    <center> Received From MILL</center>
                                                </h6>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="width:10px;">#</th>
                                            <th style="width:100px;">Item</th>
                                            <th style="width:100px;">HSN</th>
                                            <th style="width:110px;">Type</th>
                                            <th style="width:150px;">Select Screen</th>
                                            <th style="width:100px;">Rate</th>
                                            <th style="width:70px;">Gst</th>
                                            <th style="width:70px;">PCS</th>
                                            <th style="width:80px;">Mtr</th>
                                            <th style="width:80px;">Ret PCS</th>
                                            <th style="width:80px;">Ret Mtr</th>
                                            <th style="width:80px;">RPCS</th>
                                            <th style="width:80px;">RMtr</th>
                                            <th style="width:100px;">RShortage</th>
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
                                        ?>
                                        <tr class="<?=$row['pid']?> item_row">

                                            <input type="hidden" name="MillRecTB_id[]"
                                                value="<?=$row['millRecTb_ids']?>">
                                            <input type="hidden" name="MillTakaTB_id[]" value="">
                                            <input type="hidden" name="taka_tp[]" value="<?=$row['taka_tp']?>">

                                            <td>#</td>
                                            <input type="hidden" name="pid[]" value="<?=$row['pid']?>">
                                            <input type="hidden" name="millItem_id[]" value="<?=@$row['millitem_id']?>">


                                            <td><?=$row['name']?></td>
                                            <td><?=$row['hsn']?> </td>

                                            <td>
                                                <select class="form-control select-sm" id="type" name="type[]"
                                                    onchange="calculate()">
                                                    <?=$row['uom_opt']?>
                                                </select>
                                            </td>

                                            <td>
                                                <div class="input-group select2_height">
                                                    <select required class="form-control select-sm screen"
                                                        name="screen[]">
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
                                                                    style="font-size:20px;"
                                                                    class="fe fe-plus-circle"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$row['price']?>"
                                                    name="price[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text">
                                            </td>

                                            <td><input class="form-control input-sm" readonly value="<?=$row['gst']?>"
                                                    name="igst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text">
                                            </td>

                                            <td><input class="form-control input-sm" readonly value="<?=$row['pcs']?>"
                                                    name="taka[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text">
                                            </td>

                                            <td><input class="form-control input-sm" readonly value="<?=$row['meter']?>"
                                                    name="meter[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text">
                                            </td>

                                            <td><input class="form-control input-sm" readonly
                                                    value="<?=$row['ret_pcs']?>" name="return_taka[]"
                                                    onchange="calculate()" onkeypress="return isDesimalNumberKey(event)"
                                                    type="text">
                                            </td>

                                            <td><input class="form-control input-sm" readonly
                                                    value="<?=$row['ret_meter']?>" name="return_meter[]"
                                                    onchange="calculate()" onkeypress="return isDesimalNumberKey(event)"
                                                    type="text">
                                            </td>

                                            <td><input class="form-control input-sm" id="rec_taka"
                                                    value="<?=@$row['rec_pcs']?>" name="rec_taka[]"
                                                    onchange="calculate()" onkeypress="return isDesimalNumberKey(event)"
                                                    required type="text"><a data-toggle="modal" type="button"
                                                    id="add_taka"
                                                    href="<?=url("Milling/Add_MillRecTaka/").$challan['challan_no'].'/'.$row['pid'].'/'.$challan['id']?>"
                                                    data-target="#fm_model" data-title="Edit Taka" class="modal-lg"><i
                                                        class="far fa-edit"></i></a><b class="pcs_uom"></b>
                                            </td>

                                            <td>
                                                <input class="form-control input-sm" value="<?=$row['rec_meter']?>"
                                                    name="rec_meter[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required type="text"><b class="other_uom"></b>
                                            </td>

                                            <td>
                                                <input class="form-control input-sm" value="<?=@$row['cut']?>"
                                                    name="cut[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text">
                                            </td>
                                            <td><input class="form-control input-sm" value="" name="subtotal[]"
                                                    onchange="calculate()" onkeypress="return isDesimalNumberKey(event)"
                                                    required="" type="text"></td>
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
                                                                value="<?= @$challan['discount']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 disc_type" name="disc_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?=(@$challan['disc_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?=(@$challan['disc_type'] == '%' ? 'selected' : '' ) ?>
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
                                                                value="<?= @$challan['amtx']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 amtx_type" name="amtx_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?= ( @$challan['amtx_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$challan['amtx_type'] == '%' ? 'selected' : '' ) ?>
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
                                                                value="<?= @$challan['amty']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 amty_type" name="amty_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?= ( @$challan['amty_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$challan['amty_type'] == '%' ? 'selected' : '' ) ?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="amty_amount wd-90"></th>
                                                </tr>

                                                <?php 
                                                    $taxes = json_decode(@$challan['taxes']);
                                                ?>

                                                <tr>
                                                    <th>Select Tax</th>
                                                    <th colspan="2" class="wd-300">
                                                        <div class="input-group-sm">
                                                            <select class="select2 tax" id="tax" name="taxes[]"
                                                                onchange="calculate()" multiple>
                                                                <?php foreach($tax as $row) { 
                                                                        if($row['name'] == 'igst' && session('state') != @$challan['acc_state']) {
                                                                ?>
                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else if($row['name'] == 'cgst'  && session('state') == @$challan['acc_state']){ ?>

                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else if($row['name'] == 'sgst'  && session('state') == @$challan['acc_state']){ ?>

                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else if($row['name'] == 'tds' || $row['name'] == 'cess' ) { ?>

                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else{ if(!@$challan)  { ?>
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
                                                                value="<?= @$challan['tot_igst']; ?>">
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
                                                                value="<?= @$challan['tot_sgst']; ?>">
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
                                                                value="<?= @$challan['tot_cgst']; ?>">

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
                                                                value="<?= @$challan['tds_amt']; ?>">

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
                                                                value="<?= @$challan['cess']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 cess_type" name="cess_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?= ( @$challan['cess_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$challan['cess_type'] == '%' ? 'selected' : '' ) ?>
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
                <div class="mt-3">
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

if(isset($id)){
    ?>
calculate();
<?php } ?>



$('.ajax-form-submit').on('submit', function(e) {
    $('#save_data').prop('disabled', true);
    $('.error-msg').html('');
    $('.form_proccessing').html('Please wail...');
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
                $('.form_proccessing').html('');
                $('#save_data').prop('disabled', false);
                $('.error-msg').html('');

                window.location = "<?=url('Milling/mill_rec')?>";
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



    var price = $('input[name="price[]"]').map(function() {
        if (isNaN(this.value)) {
            this.value = 0;
        }
        return parseFloat(this.value);
    }).get();

    var igst = $('input[name="igst[]"]').map(function() {
        if (isNaN(this.value)) {
            this.value = 0;
        }
        return parseFloat(this.value);
    }).get();

    var taka = $('input[name="rec_taka[]"]').map(function() {
        if (isNaN(this.value)) {
            this.value = 0;
        }
        return parseFloat(this.value);
    }).get();

    var cut = $('input[name="rec_cut[]"]').map(function() {
        if (isNaN(this.value)) {
            this.value = 0;
        }
        return parseFloat(this.value);
    }).get();

    var meter = $('input[name="rec_meter[]"]').map(function() {
        if (isNaN(this.value)) {
            this.value = 0;
        }
        return parseFloat(this.value);
    }).get();

    var type = [];

    $('select[name="type[]"] option:selected').each(function() {
        var $this = $(this);
        if ($this.length) {
            type.push($this.text())
        }
    });

    for (var i = 0; i < taka.length; i++) {
        if (type[i] == "PCS") {

            uom_name = $('select[name="type[]"] :selected').eq(i).text();
            $('select[name="type[]"').eq(i).closest('.item_row').find('.other_uom').html('');
            $('select[name="type[]"]').eq(i).closest('.item_row').find('.pcs_uom').html('/ ' + uom_name);

        }else{

            uom_name = $('select[name="type[]"] :selected').eq(i).text();
            $('select[name="type[]"').eq(i).closest('.item_row').find('.pcs_uom').html('');
            $('select[name="type[]"]').eq(i).closest('.item_row').find('.other_uom').html('/ ' + uom_name);

        }
    }

    var total = 0.0;
    var igst_amt = 0.0;
    var tot_item_brok = 0.0;
    var tot_fix_brok = 0.0;
    var mtr_total = 0;

    for (var i = 0; i < taka.length; i++) {
        if (type[i] == "PCS") {
            var sub = price[i] * taka[i];
            // var disc_amt = sub * item_disc[i] / 100;
            var final_sub = sub;
            $('input[name="subtotal[]"]').eq(i).val(final_sub);
            // igst_amt += final_sub * igst[i] / 100;
            total += final_sub;

        } else {

            var sub = price[i] * meter[i];
            //var disc_amt = sub * item_disc[i] / 100;

            var final_sub = sub;
            // igst_amt += final_sub * igst[i] / 100;

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
    if (Number.isNaN(tds_per)) {
        tds_per = 0;
    }

    var discount_type = $('select[name=disc_type] option').filter(':selected').val();
    var amtx_type = $('select[name=amtx_type] option').filter(':selected').val();
    var amty_type = $('select[name=amty_type] option').filter(':selected').val();
    var cess_type = $('select[name=cess_type] option').filter(':selected').val();


    if (discount_type == '%') {
        discount_amount = (total * (discount / 100));
        $('.discount_amount').html('- ' + discount_amount);
        if (discount_amount > 0) {
            var total = 0;
            var divide_disc = discount_amount / taka.length;
            var igst_amt = 0;
            for (var i = 0; i < taka.length; i++) {
                if (type[i] == "PCS") {
                    var sub = taka[i] * price[i];
                } else {
                    var sub = price[i] * meter[i];
                }
                var final_sub = sub;
                var abc = final_sub - divide_disc;
                igst_amt += abc * igst[i] / 100;
                total += abc;
            }
        } else {
            var total = 0;
            var igst_amt = 0;
            for (var i = 0; i < taka.length; i++) {
                if (type[i] == "PCS") {
                    var sub = taka[i] * price[i];
                } else {
                    var sub = price[i] * meter[i];
                }
                var final_sub = sub;
                igst_amt += final_sub * igst[i] / 100;
                total += final_sub;
            }
        }
    } else {
        $('.discount_amount').html('- ' + discount);
        if (discount > 0) {
            var total = 0;
            var divide_disc = discount / taka.length;
            var igst_amt = 0;
            for (var i = 0; i < taka.length; i++) {
                if (type[i] == "PCS") {
                    var sub = taka[i] * price[i];
                } else {
                    var sub = price[i] * meter[i];
                }
                var final_sub = sub;

                var abc = final_sub - divide_disc;
                igst_amt += abc * igst[i] / 100;
                total += abc;
            }
        } else {
            var total = 0;
            var igst_amt = 0;
            for (var i = 0; i < taka.length; i++) {

                if (type[i] == "PCS") {
                    var sub = taka[i] * price[i];
                } else {
                    var sub = price[i] * meter[i];
                }
                var final_sub = sub;

                igst_amt += final_sub * igst[i] / 100;

                total += final_sub;
                console.log('total ' + total)

            }
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
    var tds_per = 0;

    if (tds_per != '') {
        tds_amount = (total * (tds_per / 100));
        grand_total += tds_amount;
    }

    var cgst = igst_amt / 2;
    var sgst = igst_amt / 2;



    var tax_option = $("#tax :selected").map(function(i, el) {
        return $(el).val();
    }).get();

    $.each(tax_option, function() {
        if (this == 'igst') {
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

function afterload() {}

$(document).ready(function() {

    $('.select2').select2({
        placeholder: 'Choose one',
        width: '100%'
    });


    
    $('#fm_model').modal({
        backdrop: 'static',
        keyboard: false,
        show: false,
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

    $('.dateMask').mask('99-99-9999');

    $("#account").select2({
        width: '70.0%',
        placeholder: 'Type Account Name',
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

    $('#account').on('select2:select', function(e) {
        var data = e.params.data;
        console.log('jenith');
        $('#gst').val(data.gsttin);
        $('#tds_per').val(data.tds);
        $('#tds_limit').val(data.tds_limit);
        $('#acc_state').val(data.state);

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
        $('textarea[name=delivery_code]').html(data.address);
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

    $("#mill_challan").select2({
        width: 'resolve',
        placeholder: 'Type Mill Ac Name',
        ajax: {
            url: PATH + "Milling/Getdata/search_mill_challan",
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


    $('#mill_challan').on('select2:select', function(e) {

        $(".tbody").html("");
        var suggesion = e.params.data;
        console.log(suggesion)
        var item = suggesion.item;

        var igst = document.getElementById("igst");
        var sgst = document.getElementById("sgst");
        var cgst = document.getElementById("cgst");
        var tds = document.getElementById("tds");
        var cess = document.getElementById("cess");

        var acc = '<option selected value="' + suggesion.challan.mill_ac + '">' + suggesion.challan
            .account_name + '</option>';
        // var deli = '<option selected value="' + suggesion.finish.delivery_code + '">' + suggesion
        //     .finish.delivery_name + '</option>';

        // var tran_mode = '<option selected value="' + suggesion.finish.transport_mode + '">' + suggesion
        //     .finish.transport_mode + '</option>';
        // var disc_type = '<option selected value="' + suggesion.finish.disc_type + '">' + suggesion
        //     .finish.disc_type + '</option>';
        // var amtx_mode = '<option selected value="' + suggesion.finish.amtx_type + '">' + suggesion
        //     .finish.amtx_type + '</option>';
        // var amty_mode = '<option selected value="' + suggesion.finish.amty_type + '">' + suggesion
        //     .finish.amty_type + '</option>';
        // var cess_mode = '<option selected value="' + suggesion.finish.cess_type + '">' + suggesion
        //     .finish.cess_type + '</option>';

        // var tax_option = [
        //     {
        //         id:1,
        //         text:"test"
        //     }
        // ]
        // cno = suggesion.finish.challan_no;s
        //console.log(cno);
        // $('#addinvoice').attr('href', 'Add_finishitem/' + cno);
        $('.account').append(acc);
        // $('.delivery_code').append(deli);
        // $('.transport_mode').append(tran_mode);
        // $('.disc_type').append(disc_type);
        // $('.amtx_type').append(amtx_mode);
        // $('.amty_type').append(amty_mode);
        // $('.cess_type').append(cess_mode);
        // $('.challan_no').val(suggesion.finish.challan_no)
        // $('.challan_date').val(suggesion.finish.challan_date)
        // $('.gst_no').val(suggesion.finish.gst);
        // $('.lrno').val(suggesion.finish.lr_no);
        // $('.lr_date').val(suggesion.finish.lr_date);
        // $('.igst').val(suggesion.finish.tot_igst);
        // $('.cgst').val(suggesion.finish.tot_cgst);
        // $('.sgst').val(suggesion.finish.tot_sgst);
        // $('.amtx').val(suggesion.finish.amtx);
        // $('.amty').val(suggesion.finish.amty);
        // $('.cess').val(suggesion.finish.cess);
        // $('.tds_per').val(suggesion.finish.tds_per);
        // $('.tds_amount').val(suggesion.finish.tds_amt);
        // $('.discount').val(suggesion.finish.discount);
        // $('.net_amount').val(suggesion.finish.net_amount);
        // $('.weight').val(suggesion.finish.weight);
        // $('.freight').val(suggesion.finish.freight);
        $('#acc_state').val(suggesion.challan.acc_state);
        $('#tds_per').val(suggesion.challan.tds_per);
        $('#tds_limit').val(suggesion.challan.tds_limit);

        //$('#subt').val(suggesion.finish.amount);
        //console.log(suggesion.finish.amount);

        // var taxes_str = suggesion.finish.taxes;
        // var taxes_arr = JSON.parse(taxes_str);

        // for (i = 0; i < taxes_arr.length; i++) {
        //     var newOption = new Option(taxes_arr[i], taxes_arr[i], true, true);
        //     $('#tax').append(newOption).trigger('change');
        // }

        // $.each(taxes_arr, function() {
        //     if (this == 'igst') {
        //         igst.style.display = "table-row";
        //     } else if (this == 'sgst') {
        //         sgst.style.display = "table-row";
        //     } else if (this == 'cgst') {
        //         cgst.style.display = "table-row";
        //     } else if (this == 'tds') {
        //         tds.style.display = "table-row";
        //     } else if (this == 'cess') {
        //         cess.style.display = "table-row";
        //     } else {}
        // });

        var com_state = parseInt(<?= session('state') ?>);

        var acc_state = suggesion.challan.acc_state;

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

        for (i = 0; i < item.length; i++) {

            // var uom = item[i].mitype.split(',');

            // var uom_option = '';
            // for (j = 0; j < uom.length; j++) {
            //     uom_option += '<option value="' + uom[j] + '">' + uom[j] + '</option>';
            // }

            var inp = '<input type="hidden" name="pid[]" value="' + item[i].pid + '">';

            var tds = '<tr class="' + item[i].pid + ' item_row">';

            tds += '<input type="hidden" name="taka_tp[]" value="">';
            tds += '<input type="hidden" name="MillRecTB_id[]" value="">';
            tds += '<input type="hidden" name="MillTakaTB_id[]" value="">';

            tds += '<td>#</td>';
            tds += '<td>' + item[i].name + inp + '</td>';
            tds += '<td>' + item[i].hsn + '</td>';
            tds += '<td><select name="type[]" onchange="calculate()">' + item[i].uom_opt +
                '</select></td>';

            tds +=
                '<td><div class="input-group select2_height"><select required class="form-control select-sm screen" name="screen[]"></select><div class="input-group-prepend"><div class="input-group-text" style="padding:0.045rem 0.30rem;"><a data-toggle="modal" data-target="#fm_model" data-title="Add Finish Item" href="<?=url('Milling/add_finish_screen')?>"><i style="font-size:20px;" class="fe fe-plus-circle"></i></a></div></div></div></td>';

            tds += '<td><input id="rate" class="form-control input-sm" value="' + item[i].price +
                '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + item[i].igst +
                '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" readonly required="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" id="taka" value="' + item[i].pcs +
                '" name="taka[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" required="" readonly type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" type="text" value="' + item[i].meter +
                '"onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" id="meter" name="meter[]" required readonly></td>';

            tds +=
                '<td><input class="form-control input-sm"  value="' + item[i].return_pcs +
                '" name="return_taka[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)"  readonly type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" type="text" value="' + item[i].return_meter +
                '"onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" id="meter" name="return_meter[]"  readonly></td>';


            tds +=
                '<td><input class="form-control input-sm"  value="" name="rec_taka[]" onchange="calculate()"  type="text"><a data-toggle="modal" type="button" id="add_taka" href="<?=url("Milling/Add_MillRecTaka/")?>' +
                item[i].voucher_id + '/' + item[i].pid +
                '" data-target="#fm_model" data-title="Edit Taka" class="modal-lg"><i class="far fa-edit"></i></a><b class="pcs_uom"></b></td>';

            tds +=
                '<td><input class="form-control input-sm" type="text" value="" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="rec_meter[]" ><b class="other_uom"></b></td>';

            tds +=
                '<td><input class="form-control input-sm"  name="rec_cut[]" onchange="calculate()" value="0" required="" type="text" readonly></td>';

            tds +=
                '<td><input class="form-control input-sm"  name="subtotal[]"  value="" type="text" readonly></td>';

            tds += '</tr>';
            // console.log(item[i].amount);
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
    });
});
</script>
<?= $this->endSection() ?>