<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.item {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
    margin-bottom: 5px;
}

.table-responsive item::-webkit-scrollbar {
    width: 3px;
    height: 12px;
    transition: .3s background;
}

.table-responsive item::-webkit-scrollbar-thumb {
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

</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <form action="<?= url('Milling/add_Mill_SaleReturn') ?>" class="ajax-form-submit" method="POST"
                    id="challanform">

                    <div class="card-body">

                        <div class="row">

                            <div class="col-lg-4 form-group">
                                <label class="form-label">Return No.: </label>
                                <input class="form-control" type="text" 
                                    value="<?= isset($challan['id']) ? @$challan['id'] : $current_id; ?>">
                            </div>

                            <?php 
                            $dt = date_create(date('d-m-Y'));
                            $today = date_format($dt,'d-m-Y');
                            if(!empty($challan) && isset($challan)){
                                if(@$challan['date'] != '0000-00-00'){
                                    $dt = date_create($challan['date']);
                                    $date = date_format($dt,'d-m-Y');
                                }else{
                                    $dt = date('d-m-Y');
                                    $today = date_format($dt,'d-m-Y');
                                }
                            }else{
                                $dt = date_create(date('d-m-Y'));
                                $today = date_format($dt,'d-m-Y');
                            }
                            ?>

                            <div class="col-lg-4 form-group">
                                <label class="form-label">Return Date: </label>
                                <input class="form-control dateMask" placeholder="DD/MM/YYYY" type="text"
                                     name="date"
                                    value="<?= @$challan['date'] ? $date : $today; ?>">
                            </div>

                            <div class="col-lg-4 form-group">
                                <label class="form-label">Item Type : </label>
                                <label class="rdiobox"><input
                                        <?=@$challan['item_type'] == 'gray' ? 'checked' : 'checked' ?> name="item_type"
                                        type="radio" required="" value="gray" aria-required="true" autocomplete="false">
                                    <span>Gray</span></label>
                                <label class="rdiobox"><input <?=@$challan['item_type'] == 'finish' ? 'checked' : '' ?>
                                        name="item_type" type="radio" required="" value="finish" aria-required="true"
                                        autocomplete="false">
                                    <span>Finish</span></label>
                            </div>

                            <div class="row col-md-12 form-group">
                                <label class="form-label col-md-3">Select Invoice: <span
                                        class="tx-danger">*</span></label>
                                <select class="form-control col-md-9" id="get_invoice" name='invoice_no'>

                                    <?php if(@$challan['invoice_no']) { ?>
                                    <option value="<?=@$challan['invoice_no']?>">
                                        <?=@$challan['invoice_name']?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-lg-5 form-group">
                                <div class="row">
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Account: <span
                                                class="tx-danger">*</span></label>
                                        <select class="form-control account" id="account" name='account'>
                                            <?php if(@$challan['account_name']) { ?>
                                            <option value="<?=@$challan['account']?>"><?=@$challan['account_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>

                                        <input type="hidden" name="id" value="<?= @$challan['id']; ?>">
                                        <input type="hidden" name="tds_per" id="tds_per"
                                            value="<?= @$challan['tds_per']; ?>">
                                        <input type="hidden" name="tds_limit" id="tds_limit"
                                            value="<?= @$challan['tds_limit']; ?>">
                                        <input type="hidden" name="acc_state" id="acc_state"
                                            value="<?= @$challan['acc_state']; ?>">
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">GST No.: <span
                                                class="tx-danger">*</span></label>
                                        <input readonly class="form-control col-md-8 gst_no" type="text" name="gst" id="gst"
                                            value="<?= @$challan['gst']; ?>">
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Transport Mode </label>
                                        <select class="select2" id="transport_mode" name="trasport_mode">
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

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Vehicle No : </label>
                                        <select class="form-control" id="vehicle" name='vehicle'>
                                            <?php if(@$challan['vehicle']) { ?>
                                            <option value="<?=@$challan['vehicle']?>">
                                                <?=@$challan['vehicle_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Broker : </label>
                                        <select class="form-control col-md-8" id="broker" name='broker'>
                                            <?php if(@$challan['broker_name']) { ?>
                                            <option value="<?=@$challan['broker']?>"><?=@$challan['broker_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    
                                </div>
                            </div>

                            <div class="col-lg-7 form-group">
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Other: </label>
                                    </div>
                                    <div class="col-md-10 form-group">
                                        <div class="input-group">
                                            <input class="form-control" name="other" value="<?=@$challan['other']?>"
                                                placeholder="Enter Other Detail" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR No.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="lrno" value="<?= @$challan['lr_no']; ?>"
                                            placeholder="LR No." type="text">
                                    </div>
                                    <?php 
                                        if(!empty($challan) && isset($challan)){
                                            if(@$challan['lr_date'] != '0000-00-00'){
                                                $lr_dt = date_create($challan['lr_date']);
                                                $lr_date = date_format($lr_dt,'d-m-Y');
                                            }
                                        }
                                    ?>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR Date.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control dateMask" placeholder="DD-MM-YYYY" type="text"
                                            id="lr_date" name="lr_date" value="<?= @$lr_date; ?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Weight.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="weight" value="<?= @$challan['weight']; ?>"
                                            placeholder="0.00" placeholder="Enter Weight" type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Freight.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="freight" value="<?= @$challan['freight']; ?>"
                                            placeholder="00" type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Transport:</label>
                                    </div>
                                    <div class="col-md-10 form-group">
                                        <select class="form-control" id="transport" name='transport'>
                                            <?php if(@$challan['transport_name']) { ?>
                                            <option value="<?=@$challan['transport']?>"><?=@$challan['transport_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label class="form-label">Warehouse: </label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <select class="form-control" id="warehouse" name='warehouse'>
                                            <?php if(@$challan['warehouse_name']) { ?>
                                            <option value="<?=@$challan['warehouse']?>"><?=@$challan['warehouse_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label class="form-label">Delivery Address: </label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input class="form-control" name="delivery_code"
                                            value="<?= @$challan['delivery_code']; ?>"
                                            placeholder="Enter Delivery Address" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="table-responsive item">
                                <table class="table table-bordered mg-b-0 product" id="product">
                                    <thead>
                                        <tr>
                                            <th style="width:10px;">#</th>
                                            <th style="width:80px;">Item</th>
                                            <th style="width:70px;">HSN</th>
                                            <th style="width:70px;">GST</th>
                                            <th style="width:80px;">Type</th>
                                            <th style="width:70px;">Total Sale Taka</th>
                                            <th style="width:80px;">Total Sale QTY(MTR)</th>
                                            <th style="width:70px;">Ret Taka</th>
                                            <th style="width:80px;">Ret QTY(MTR)</th>
                                            <th style="width:80px;">Price</th>
                                            <th style="width:80px;">Subtotal</th>
                                            <th style="width:80px;">Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        <?php 
                                            $total=0;
                                            if(isset($item)) {
                                                foreach($item as $row){
                                            
                                            ?>
                                        <tr class="<?=$row['pid']?>">

                                            <td>#</td>
                                                                        
                                            <input type="hidden" name="ret_takaTb_ids[]" value="<?=@$row['saleTakatbID']?>">
                                            <input type="hidden" name="saleTakaTb_ids[]" value="">
                                            <input type="hidden" name="need_toDelete[]" value="">
                                            <input type="hidden" name="pid[]" value="<?=@$row['pid']?>">

                                            <td><?=$row['name']?> </td>
                                            <td><?=$row['hsn']?> </td>
                                            <td><input class="form-control input-sm" value="<?=$row['gst']?>"
                                                    name="gst[]" onchange="calculate()" type="text"> </td>

                                            <td>
                                                <select class="form-control select-sm" id="type" name="type[]"
                                                    onchange="calculate()">
                                                    <?=@$row['uom_opt']?>
                                                </select>
                                            </td>

                                            <td><input class="form-control input-sm" readonly value="<?=$row['taka']?>"
                                                    name="taka[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text">
                                            </td>

                                            <td><input class="form-control input-sm" readonly value="<?=@$row['meter']?>"
                                                    name="meter[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text">
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=@$row['ret_taka']?>"
                                                    name="ret_taka[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required
                                                    type="text"><a data-toggle="modal" type="button" id="add_taka"
                                                    href="<?=url("Milling/Add_MillSaleReturntaka/").$challan['challan'].'/'.$row['pid'].'/'.$challan['id']?> "
                                                    data-target="#fm_model" data-title="Edit Taka" class="modal-lg"><i
                                                        class="far fa-edit"></i></a>
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=@$row['ret_meter']?>"
                                                    name="ret_meter[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required
                                                    type="text">
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$row['price']?>"
                                                    name="price[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" type="text">
                                            </td>
                                            
                                            <td><input class="form-control input-sm" value="<?=$row['subtotal']?>"
                                                    name="subtotal[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" type="text">
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$row['remark']?>"
                                                    name="remark[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" type="text">
                                            </td>
                                        </tr>
                                        <?php } } ?>
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
                                        <td class="total" id="total"></td>
                                        <td></td>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <div class="row mt-3">

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
                                                            <input class="form-control" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="discount" type="text"
                                                                value="<?= @$challan['discount']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2" name="disc_type"
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
                                                            <input class="form-control" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="amtx" type="text"
                                                                value="<?= @$challan['amtx']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2" name="amtx_type"
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
                                                            <input class="form-control" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="amty" type="text"
                                                                value="<?= @$challan['amty']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2" name="amty_type"
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
                                                            <select class="select2" id="tax" name="taxes[]"
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
                                                            <input class="form-control" readonly onchange="calculate()"
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
                                                            <input class="form-control" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="cess" type="text"
                                                                value="<?= @$challan['cess']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2" name="cess_type"
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
                                                    <td colspan="2"><input class="form-control input-sm"
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
if(isset($id)){ ?>
calculate();
// enable_gst_option();
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
    // $('.form_proccessing').html('Please wail...');
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
                window.location = "<?=url('milling/mill_sale_return')?>";
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

    // var qty = $('input[name="qty[]"]').map(function() {
    //     return parseFloat(this.value);
    // }).get();

    // var item_disc = $('input[name="item_disc[]"]').map(function() {
    //     return parseFloat(this.value);
    // }).get();

    var price = $('input[name="price[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var igst = $('input[name="gst[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var taka = $('input[name="ret_taka[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var meter = $('input[name="ret_meter[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var type = [];
    $('select[name="type[]"] option:selected').each(function() {
        var $this = $(this);
        if ($this.length) {
            type.push($this.text())
        }
    });
    
    var total = 0.0;
    var igst_amt = 0.0;
    var tot_item_brok = 0.0;
    var tot_fix_brok = 0.0;
    var mtr_total = 0;
    // console.log('meter = ' + meter[i]);
    // console.log('price = ' + price[i]);

    for (var i = 0; i < taka.length; i++) {
        if (type[i] == "PCS") {
            var sub = price[i] * taka[i];
            // var disc_amt = sub * item_disc[i] / 100;
            var final_sub = sub;
            $('input[name="subtotal[]"]').eq(i).val(final_sub);
            igst_amt += final_sub * igst[i] / 100;
            total += final_sub;

        } else {

            var sub = price[i] * meter[i];
            // $('input[name="meter[]"]').eq(i).val(meter[i]);
            //var disc_amt = sub * item_disc[i] / 100;

            var final_sub = sub;
            igst_amt += final_sub * igst[i] / 100;

            $('input[name="subtotal[]"]').eq(i).val(final_sub);
            total += final_sub;

        }
    }

    $('.total').html(total);
    // console.log('total = ' + total)
    // tot_fix_brok = total * fix_brokrage/100;

    var discount = $('input[name="discount"]').val();

    var amtx = parseFloat($('input[name="amtx"]').val());
    var amty = parseFloat($('input[name="amty"]').val());
    var cess = parseFloat($('input[name="cess"]').val());
    var tds_per = $('#tds_per').val();
    var tds_limit = $('#tds_limit').val();
    // console.log('tds_per ' + tds_per)
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

    // console.log(cess)

    var discount_type = $('select[name=disc_type] option').filter(':selected').val();
    var amtx_type = $('select[name=amtx_type] option').filter(':selected').val();
    var amty_type = $('select[name=amty_type] option').filter(':selected').val();
    var cess_type = $('select[name=cess_type] option').filter(':selected').val();

    if (discount_type == '%') {
        discount_amount = (total * (discount / 100));
        $('.discount_amount').html('- ' + discount_amount);
        if (discount_amount > 0) {
            
            total -= discount_amount;
           
            $('.total').html(total);
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

    $('input[type=radio][name=item_type]').change(function() {
        $('.tbody').empty();
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

        $('#gst').val(data.gsttin);
        $('#tds_per').val(data.tds);
        $('#tds_limit').val(data.tds_limit);
        $('#acc_state').val(data.state);
        $('.igst').val(suggestion.price.igst);
        $('.cgst').val(suggestion.price.cgst);
        $('.sgst').val(suggestion.price.sgst);

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
        width: 'resolve',
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

    $("#get_invoice").select2({
        width: 'resolve',
        placeholder: 'Type Invoice No.',
        // minimumInputLength: 1,
        ajax: {
            url: PATH + "Milling/Getdata/get_MillSaleInvoice_Return",
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

    $('#get_invoice').on('select2:select', function(e) {

        $(".tbody").empty();

        var suggesion = e.params.data;
        
        var item = suggesion.item;

        var acc = '<option selected value="' + suggesion.challan.account + '">' + suggesion.challan
            .account_name + '</option>';

        var trans = '<option selected value="' + suggesion.challan.transport + '">' + suggesion.challan
            .transport_name + '</option>';

        var daybk = '<option selected value="' + suggesion.challan.daybook_id + '">' + suggesion.challan
            .daybook_name + '</option>';

        var brok = '<option selected value="' + suggesion.challan.broker + '">' + suggesion.challan
            .broker_name + '</option>';

        var warehouse = '<option selected value="' + suggesion.challan.warehouse + '">' + suggesion
            .challan
            .warehouse_name + '</option>';

        var vehi = '<option selected value="' + suggesion.challan.vehicle_modeno + '">' + suggesion
            .challan.vehicle_name + '</option>';

        var trans = '<option selected value="' + suggesion.challan.transport + '">' + suggesion.challan
            .transport_name + '</option>';

        var tran_mode = '<option selected value="' + suggesion.challan.transport_mode + '">' + suggesion
            .challan.transport_mode + '</option>';

        $('.account').append(acc);
        $('.broker').append(brok);
        $('.warehouse').append(warehouse);
        $('.daybook').append(daybk);
        $('#transport').append(trans);

        $('.vehicle').append(vehi);
        $('.transport').append(trans);
        $('.trans_mode').append(tran_mode);


        $('#acc_state').val(suggesion.challan.acc_state);
        $('.gst_no').val(suggesion.challan.gst);
        $('.delivery').val(suggesion.challan.delivery_code);
        $('.lrno').val(suggesion.challan.lr_no);
        $('.lr_date').val(suggesion.challan.lr_date);
        $('.weight').val(suggesion.challan.weight);
        $('.freight').val(suggesion.challan.freight);

        if(suggesion.challan.item_type == 'gray'){
            $('input[value="gray"]').attr('checked',true);
        }else{
            $('input[value="finish"]').attr('checked',true);
        }

        for (i = 0; i < item.length; i++) {

            var inp = '<input type="hidden" name="pid[]" value="' + item[i].id + '">';
            var tds = '<tr class ="'+ item[i].id +'">';
            tds += '<td>#</td>';

            tds += '<input type="hidden" name="ret_takaTb_ids[]" value="">';
            tds += '<input type="hidden" name="saleTakaTb_ids[]" value="">';
            tds += '<input type="hidden" name="need_toDelete[]" value="">';

            tds += '<td>' + item[i].name + inp + '</td>';
            tds += '<td>' + item[i].hsn + '</td>';
            tds += '<td><input id="rate" class="form-control input-sm" value="' + item[i].gst +
                '" name="gst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" required type="text"></td>';
            tds += '<td><select name="type[]">' + item[i].uom_opt + '</select></td>';

            tds +=
                '<td><input class="form-control input-sm" id="taka" value="' + item[i].taka +
                '" name="taka[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" required="" readonly type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" type="text" value="' + item[i].meter +
                '"onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" id="meter" name="meter[]" required readonly></td>';

            tds +=
                '<td><input class="form-control input-sm" type="text" value=""onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" id="meter" name="ret_taka[]" required ><a data-toggle="modal" type="button" id="add_taka" href="<?=url('Milling/Add_MillSaleReturntaka/')?>' + suggesion.challan.challan + '/' + item[i].id +'" data-target="#fm_model" data-title="Add Taka" class=""><i class="far fa-edit"></i></a></td>';

            tds +=
                '<td><input class="form-control input-sm" type="text" value=""onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" id="meter" name="ret_meter[]" required ></td>';

            tds += '<td><input id="rate" class="form-control input-sm" value="' + item[i].price +
                '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm"  name="subtotal[]"  value=""  type="text" readonly></td>';

            tds +=
                '<td><input class="form-control input-sm"  name="remark[]"  value="' + item[i].remark +
                '" required="" type="text" ></td>';
            tds += '</tr>';

            $('.tbody').append(tds);
            $('#code').val('');


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
        }
    });


    $("#warehouse").select2({
        width: 'resolve',
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