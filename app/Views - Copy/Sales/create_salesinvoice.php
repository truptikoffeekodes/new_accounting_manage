<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

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
                    <form action="<?= url('Sales/add_salesinvoice') ?>" class="ajax-form-submit" method="POST"
                        id="Salesinvoiceform">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <label class="form-label">Inovice No.: </label>
                                <input class="form-control"  type="text" name="invoice_no"
                                    value="<?= isset($salesinvoice['invoice_no']) ? @$salesinvoice['invoice_no'] : $current_id ?>">
                            </div>
                            <?php 

                            if(!empty($salesinvoice) && isset($salesinvoice)){
                                if(@$salesinvoice['invoice_date'] != '0000-00-00'){
                                    $dt = date_create($salesinvoice['invoice_date']);
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
                                <label class="form-label">Invoice Date: </label>
                                <input class="form-control dateMask" placeholder="DD-MM-YYYY" type="text"
                                    name="invoice_date"
                                    value="<?=@$salesinvoice['invoice_date'] ?@$date :$today ?>">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="form-label">Challan No: </label>
                                <select class="form-control" <?=@$salesinvoice['challan_no'] ? 'readonly' :''?> id="get_challan" name='challan'> 
                                <?php if(@$salesinvoice['challan_no']) { ?>
                                            <option value="<?=@$salesinvoice['challan_no']?>">
                                                <?=@$salesinvoice['challan_name']?>
                                            </option>
                                            <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-5 form-group">
                                <div class="row">
                                    <div class="row col-lg-12 form-group">
                                        <label class="form-label col-md-4">Account: <span
                                                class="tx-danger">*</span></label>
                                        <select class="form-control account" id="account" name='account'>
                                            <?php if(@$salesinvoice['account_name']) { ?>
                                            <option value="<?=@$salesinvoice['account']?>">
                                                <?=@$salesinvoice['account_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" name="id" value="<?=@$salesinvoice['id']?>">
                                        <input type="hidden" name="tds_per" id="tds_per" class="tds_per"
                                            value="<?= @$salesinvoice['tds_per']; ?>">
                                        <input type="hidden" name="tds_limit" id="tds_limit"
                                            value="<?= @$salesinvoice['tds_limit']; ?>">
                                        <input type="hidden" name="acc_state" id="acc_state"
                                            value="<?= @$salesinvoice['acc_state']; ?>">
                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">GST No.: <span
                                                class="tx-danger">*</span></label>
                                        <input readonly class="form-control col-md-8 gst_no" type="text" name="gst"
                                            id="gsttin" value="<?= @$salesinvoice['gst']; ?>">
                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Delivery Ac: <span
                                                class="tx-danger">*</span></label>
                                        <select class="form-control delivery" id="delivery_code" name='delivery_code'>
                                            <?php if(@$salesinvoice['delivery_name']) { ?>
                                            <option value="<?=@$salesinvoice['delivery_code']?>">
                                                <?=@$salesinvoice['delivery_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Vehicle No: </label>
                                        <div class="input-group col-md-8" style="padding:0px;">
                                            <select class="form-control vehicle" id="vehicle" name='vehicle'>
                                                <?php if(@$salesinvoice['vehicle_name']) { ?>
                                                <option value="<?=@$salesinvoice['vhicle_no']?>">
                                                    <?=@$salesinvoice['vehicle_name']?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_vehicle') ?>"
                                                        data-target="#fm_model" data-title="Enter vhicle"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Due Days: </label>
                                        <input class="form-control col-md-8" name="due_day"
                                            value="<?=@$salesinvoice['due_days']?>" placeholder="Enter Due Days"
                                            onkeypress="return isNumberKey(event)" type="text">
                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Due Date: </label>
                                        <input class="form-control dateMask col-md-8" placeholder="DD-MM-YYYY"
                                            type="text" id="due_date" name="due_date"
                                            value="<?=@$salesinvoice['due_date']?>">
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label">Add Item: <span class="tx-danger"></span></label>
                                        <div class="input-group">
                                            <select class="form-control" id="code" name='code'> </select>
                                            <div class="dz-error-message tx-danger product_error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7 form-group">
                                <div class="row">

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Broker: </label>
                                    </div>

                                    <div class="col-md-5 form-group">
                                        <select class="form-control broker" id="broker" name='broker'>
                                            <?php if(@$salesinvoice['broker_name']) { ?>
                                            <option value="<?=@$salesinvoice['broker']?>">
                                                <?=@$salesinvoice['broker_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" value="<?=@$salesinvoice['broker']?>" id="fix_brokrage"
                                            name="brokrage">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Brokrage Type: <span
                                                class="tx-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3 form-group">

                                        <label class="rdiobox"><input checked name="brokerage_type"
                                                <?=@$salesinvoice['brokrage_type'] == "fix" ? 'checked' : ''  ?>
                                                value="fix" type="radio" onchange="calculate()">
                                            <span>Fix</span></label>

                                        <label class="rdiobox"><input name="brokerage_type"
                                                <?=@$salesinvoice['brokrage_type'] == "item_wise" ? 'checked' : ''  ?>
                                                value="item_wise" type="radio" onchange="calculate()"> <span>Item
                                                Wise</span></label>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Other: </label>
                                    </div>

                                    <div class="col-lg-10 form-group">
                                        <input class="form-control other" name="other" value="<?=@$salesinvoice['other']?>"
                                            placeholder="Enter Other Detail" type="text">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR No.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control lr" name="lrno" placeholder="Enter Lr No."
                                            onkeypress="return isDesimalNumberKey(event)"
                                            value="<?=@$salesinvoice['lr_no']?>" type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR Date.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control dateMask lr_data" placeholder="DD/MM/YYYY"
                                            type="text" id="lr_date" name="lr_date"
                                            value="<?=@$salesinvoice['lr_date']?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Transport.: </label>
                                    </div>

                                    <div class="col-md-10 form-group">
                                        <div class="input-group">
                                            <select class="form-control transport" id="transport" name='transport'>
                                                <?php if(@$salesinvoice['transport_name']) { ?>
                                                <option value="<?=@$salesinvoice['transport']?>">
                                                    <?=@$salesinvoice['transport_name']?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-target="#fm_model" data-toggle="modal"
                                                        data-title="Add Transport"
                                                        href="<?=url('master/add_transport')?>"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Transport Mod e: </label>
                                    </div>
                                    <div class="col-md-10 form-group">
                                        <div class="input-group">
                                            <select class="select2 trans_mode" id="transport_mode" name="trasport_mode">
                                                <option
                                                    <?= ( @$salesinvoice['transport_mode'] == 'AIR' ? 'selected' : '' ) ?>
                                                    value="AIR">AIR</option>
                                                <option
                                                    <?= ( @$salesinvoice['transport_mode'] == 'ROAD' ? 'selected' : '' ) ?>
                                                    value="ROAD">ROAD</option>
                                                <option
                                                    <?= ( @$salesinvoice['transport_mode'] == 'RAIL' ? 'selected' : '' ) ?>
                                                    value="RAIL">RAIL</option>
                                                <option
                                                    <?= ( @$salesinvoice['transport_mode'] == 'SHIP' ? 'selected' : '' ) ?>
                                                    value="SHIP">SHIP</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0" id="product">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>UOM</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>IGST(%)</th>
                                            <th>CGST(%)</th>
                                            <th>SGST(%)</th>
                                            <th>Discount(%)</th>
                                            <th>Amount</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        <?php 
                                        if(isset($item))
                                        {
                                            $total=0.0;
                                            foreach($item as $row){
                                                $sub_total=$row['rate'] * $row['qty'];
                                                $total += $sub_total;
                                                $uom=explode(',',$row['item_uom']);
                                        ?>
                                        <tr>
                                            <td><a class="tx-danger btnDelete" data-id="<?=$row['item_id']?>"
                                                    title="0"><i class="fa fa-times tx-danger"></i></a></td>
                                            <td><?=$row['name'] ?>(<?=$row['code'] ?>)
                                                <input type="hidden" name="pid[]" value="<?=$row['item_id']?>">
                                            </td>
                                            <td><select name="uom[]">
                                                    <?php 
                                                    foreach($uom as $uom_row){
                                                    ?>
                                                    <option <?= ( @$uom_row == $row['uom'] ? 'selected' : '' ) ?>
                                                        value="<?= @$uom_row ?>"><?= @$uom_row ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td><input class="form-control input-sm" value="<?=$row['qty']?>"
                                                    name="qty[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)"="" type="text"></td>
                                            <td><input class="form-control input-sm" value="<?=$row['rate']?>"
                                                    name="price[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)"="" type="text"></td>

                                            <td><input type="hidden" name="item_brokrage[]"
                                                    value="<?=$row['brokrage']?>">
                                                <input class="form-control input-sm" value="<?=$row['igst']?>"
                                                    name="igst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)"="" type="text">
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$row['cgst']?>"
                                                    name="cgst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)"="" type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['sgst']?>"
                                                    name="sgst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)"="" type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['item_disc']?>"
                                                    name="item_disc[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)"="" type="text"></td>
                                            <td><input class="form-control input-sm" name="subtotal[]"
                                                    onchange="calculate()" value="<?= $sub_total ?>"="" type="text"
                                                    readonly=""></td>
                                            <td><input class="form-control input-sm" name="remark[]"
                                                    value="<?=$row['remark']?>" placeholder="Remark" type="text"></td>
                                        </tr>
                                        <?php } }?>
                                    </tbody>
                                    <tfoot>
                                        <td colspan="2" class="text-right">Total</td>
                                        <td></td>
                                        <td class="qty_total"></td>
                                        <td class="rate_total"></td>
                                        <td class="IGST_total"></td>
                                        <td class="CGST_total"></td>
                                        <td class="SGST_total"></td>
                                        <td class="discount_total"></td>
                                        <td class="total"></td>
                                        <td></td>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="row mt-3">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mg-b-0" id="selling_case">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <label
                                                            id="brok_name"><?= @$salesinvoice['broker_name']; ?></label>
                                                        <div class="tx-danger broker-error">
                                                        </div>
                                                    </th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="brokrage" id="brokrage" type="text"
                                                                placeholder="Brokrage Amount"
                                                                value="<?= @$salesinvoice['brokrage']; ?>">
                                                        </div>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <div class="input-group-sm">
                                                            <select class="form-control" id="broker_ledger"
                                                                name='broker_ledger'>
                                                                <?php if(@$salesinvoice['broker_ledger']) { ?>
                                                                <option value="<?=@$salesinvoice['broker_ledger']?>">
                                                                    <?=@$salesinvoice['broker_ledger_name']?>
                                                                </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="broker_led_amt" id="broker_led" type="text"
                                                                value="<?= @$salesinvoice['broker_led_amt']; ?>">
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12 form-group">
                                        <label class="custom-switch">
                                            <input type="checkbox" name="stat_adj" onchange="check_stat()"
                                                class="custom-switch-input"
                                                <?= ( @$salesinvoice['stat_adj'] == "1" ? 'checked' : '' ) ?>
                                                value="<?=@$salesinvoice['stat_adj'] ?>">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Stat Adjustment</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row stat_div" style="display:<?=(@$salesinvoice['stat_adj']== 1) ? 'flex;' : 'none;'?>">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Type of Reffrence: <span class="tx-danger"></span></label>
                                        <div class="input-group">
                                            <select class="form-control select2" id="ref_type" name="ref_type">
                                              
                                                <option
                                                    <?= (@$salesinvoice['ref_type'] == "Agst Ref" ? 'selected' : '' ) ?>
                                                    value="Agst Ref">Agst Ref</option>
                                                <option
                                                    <?= (@$salesinvoice['ref_type'] == "Advance" ? 'selected' : '' ) ?>
                                                    value="Advance">Advance</option>
                                                <option
                                                    <?= (@$salesinvoice['ref_type'] == "New Ref" ? 'selected' : '' ) ?>
                                                    value="New Ref">New Ref</option>
                                                <option
                                                    <?= (@$salesinvoice['ref_type'] == "On Account" ? 'selected' : '' ) ?>
                                                    value="On Account">On Account</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group voucher_list" style="display:<?=!empty(@$salesinvoice['voucher']) ? 'block;' : 'none;'?>">
                                        <label class="form-label" >Select Voucher: <span class="tx-danger"></span></label>
                                        <div class="input-group">
                                            <select class="form-control" id="voucher" name="voucher">
                                                <?php if(@$salesinvoice['voucher']) { ?>
                                                <option value="<?=@$salesinvoice['voucher']?>">
                                                    <?=@$salesinvoice['voucher']?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Amount: <span class="tx-danger"></span></label>
                                        <div class="input-group">
                                        <input type="text" name="voucher_amt" 
                                                class="form-control" placeholder = "Type Amount"  onkeypress="return isDesimalNumberKey(event)" value="<?=@$salesinvoice['voucher_amt'] ?>">
                                        </div>
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
                                                                value="<?= @$salesinvoice['discount']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 disc_type" name="disc_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?= ( @$salesinvoice['disc_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$salesinvoice['disc_type'] == '%' ? 'selected' : '' ) ?>
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
                                                                value="<?= @$salesinvoice['amtx']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 amtx_type" name="amtx_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?= ( @$salesinvoice['amtx_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$salesinvoice['amtx_type'] == '%' ? 'selected' : '' ) ?>
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
                                                                value="<?= @$salesinvoice['amty']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 amty_type" name="amty_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?= ( @$salesinvoice['amty_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$salesinvoice['amty_type'] == '%' ? 'selected' : '' ) ?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="amty_amount wd-90"></th>
                                                </tr>
                                                <?php
                                                    $taxes = json_decode(@$salesinvoice['taxes']);
                                                    
                                                ?>
                                                <tr>
                                                    <th>Select Tax</th>
                                                    <th colspan="2" class="wd-300">
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="tax" name="taxes[]"
                                                                onchange="calculate()" multiple>
                                                                <?php foreach($tax as $row) { 
                                                                        if($row['name'] == 'igst' && session('state') != @$salesinvoice['acc_state']) {
                                                                ?>
                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else if($row['name'] == 'cgst'  && session('state') == @$salesinvoice['acc_state']){ ?>

                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else if($row['name'] == 'sgst'  && session('state') == @$salesinvoice['acc_state']){ ?>

                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else if($row['name'] == 'tds' || $row['name'] == 'cess' ) { ?>

                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else{ if(!@$salesinvoice)  { ?>
                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>
                                                                <?php } } } ?>

                                                            </select>
                                                        </div>
                                                    </th>
                                                </tr>

                                                <tr id="igst"
                                                    style="display:<?php if( !empty($taxes)) { echo   (in_array("igst", $taxes))  ? 'table-row;' : 'none;'; } else { echo 'none;'; } ?> ">
                                                    <th>(+)IGST</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_igst" type="text"
                                                                value="<?= @$salesinvoice['tot_igst']; ?>">
                                                        </div>
                                                    </th>
                                                    <th class="igst_amount wd-90"></th>
                                                </tr>

                                                <tr id="sgst"
                                                    style="display:<?php if(!empty($taxes)) {  echo (in_array("sgst", $taxes)) ? 'table-row;' : 'none;';  } else{ echo 'none;' ; } ?> ">
                                                    <th>(+)SGST</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_sgst" type="text"
                                                                value="<?= @$salesinvoice['tot_sgst']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="sgst_amount wd-90"></th>
                                                </tr>

                                                <tr id="cgst"
                                                    style="display:<?php if(!empty($taxes)) { echo  (in_array("cgst", $taxes)) ? 'table-row;' : 'none;'; }else{ echo 'none;' ; } ?> ">
                                                    <th>(+)CGST</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_cgst" type="text"
                                                                value="<?= @$salesinvoice['tot_cgst']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="cgst_amount wd-90"></th>
                                                </tr>

                                                <tr id="tds"
                                                    style="display:<?php if(!empty($taxes)) { echo  (in_array("tds", $taxes)) ? 'table-row;' : 'none;'; } else{ echo 'none;' ; } ?> ">
                                                    <th>(+)TDS</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control tds_amt" readonly
                                                                onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tds_amt" type="text"
                                                                value="<?= @$salesinvoice['tds_amt']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="tds_amount wd-90"></th>
                                                </tr>

                                                <tr id="cess"
                                                    style="display:<?php if(!empty($taxes)) { echo  (in_array("cess", $taxes)) ? 'table-row;' : 'none;'; }else{ echo 'none;' ; } ?>">
                                                    <th>(+)Cess</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control cess" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="cess" type="text"
                                                                value="<?= @$salesinvoice['cess']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 cess_mode" name="cess_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?= ( @$salesinvoice['cess_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$salesinvoice['cess_type'] == '%' ? 'selected' : '' ) ?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="cess_amount wd-90"></th>
                                                </tr>

                                                <tr>
                                                    <td>Net Amount</td>
                                                    <td colspan="2"><input class="form-control input-sm net_amt"
                                                            name="net_amount" type="text"
                                                            value="<?= @$salesinvoice['net_amount']; ?>" readonly></td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="tx-danger error-msg"></div>
                            <div class="tx-success form_proccessing"></div>
                        </div>
                        <div class="row mt-3">
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
if(isset($id))
{?>
calculate();
<?php } ?>

function check_stat() {
   
    if ($('input[name="stat_adj"]').is(':checked')) {
        $('input[name="stat_adj"]').val('1');
        $('.stat_div').css('display', 'flex');
        //var ref_type  = $('select[name="ref_type"]').val();
        
    } else {
            $('.stat_div').css('display', 'none');
    }
}

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

function calculate() {

    var qty = $('input[name="qty[]"]').map(function() {
        return parseFloat(this.value); // $(this).val()
    }).get();

    var brok_type = $('input[name="brokerage_type"]:checked').val();

    var fix_brokrage = $("#fix_brokrage").val();

    if ($('#fix_brokrage').val() == "" && qty != "") {
        $('.broker-error').text('Please Select Broker..!!');
    } else {
        $('.broker-error').text('');
    }

    var item_brokrage = $('input[name="item_brokrage[]"]').map(function() {
        return parseFloat(this.value); // $(this).val()
    }).get();


    var item_disc = $('input[name="item_disc[]"]').map(function() {
        return parseFloat(this.value); // $(this).val()
    }).get();

    var price = $('input[name="price[]"]').map(function() {
        return parseFloat(this.value); // $(this).val()
    }).get();

    var igst = $('input[name="igst[]"]').map(function() {
        return parseFloat(this.value); // $(this).val()
    }).get();

    var total = 0.0;
    var igst_amt = 0.0;
    var tot_item_brok = 0.0;
    var tot_fix_brok = 0.0;

    for (var i = 0; i < qty.length; i++) {

        var sub = qty[i] * price[i];
        var disc_amt = sub * item_disc[i] / 100;

        var final_sub = sub - disc_amt;
        igst_amt += final_sub * igst[i] / 100;

        var brok_amt = sub * item_brokrage[i] / 100;
        tot_item_brok += brok_amt;

        $('input[name="subtotal[]"]').eq(i).val(final_sub);
        total += final_sub;
    }
    $('.total').html(total);

    tot_fix_brok = total * fix_brokrage / 100;

    var discount = $('input[name="discount"]').val();
    var amtx = parseFloat($('input[name="amtx"]').val());
    var amty = parseFloat($('input[name="amty"]').val());
    var cess = parseFloat($('input[name="cess"]').val());
    var tds_per = $('#tds_per').val();
    var tds_limit = parseInt($('#tds_limit').val());

    var com_state = parseInt(<?= session('state') ?>);
    var acc_state = parseInt($('#acc_state').val());

    if (total < tds_limit) {
        $("#tax option[value='tds']").remove();
    } else {
        if ($("#tax option[value='tds']").length == 0) {
            $('#tax').append('<option value="tds">tds</option>');
        }
    }

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


    var disc_type = $('select[name=disc_type] option').filter(':selected').val();
    var amtx_type = $('select[name=amtx_type] option').filter(':selected').val();
    var amty_type = $('select[name=amty_type] option').filter(':selected').val();
    var cess_type = $('select[name=cess_type] option').filter(':selected').val();

    var grand_total = total;

    if (disc_type == '%') {
        discount_amount = (total * (discount / 100));
        $('.discount_amount').html('- ' + discount_amount);
        if (discount_amount > 0) {
            var total = 0;
            var divide_disc = discount_amount / qty.length;
            var igst_amt = 0;
            for (var i = 0; i < qty.length; i++) {

                var sub = qty[i] * price[i];
                var disc_amt = sub * item_disc[i] / 100;

                var final_sub = sub - disc_amt;

                var abc = final_sub - divide_disc;

                igst_amt += abc * igst[i] / 100;
                total += abc;
            }
        }
    } else {
        $('.discount_amount').html('- ' + discount);

        if (discount > 0) {
            var total = 0;
            var divide_disc = discount / qty.length;
            var igst_amt = 0;
            for (var i = 0; i < qty.length; i++) {

                var sub = qty[i] * price[i];
                var disc_amt = sub * item_disc[i] / 100;
                var final_sub = sub - disc_amt;

                var abc = final_sub - divide_disc;

                igst_amt += abc * igst[i] / 100;
                total += abc;

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

    // if(tds_amount != ''){
    //     var tds_amount = 0;
    // }
    var tds_amount = 0;
    var cgst = igst_amt / 2;
    var sgst = igst_amt / 2;

    var tax_option = $("#tax :selected").map(function(i, el) {
        return $(el).val();
    }).get();
    // console.log('tax_option =' + tax_option);
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


    if (brok_type == "item_wise") {
        $('#brokrage').val('+' + tot_item_brok);
        $('#broker_led').val('-' + tot_item_brok);
    } else {
        $('#brokrage').val('+' + tot_fix_brok);
        $('#broker_led').val('-' + tot_fix_brok);
    }

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
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });

    $('#ref_type').on('select2:select', function(e) {
        var ref_type = $('#ref_type').val();
        if(ref_type == 'Advance'){
            var acc = $('#account').val(); 
            if(acc == '' || acc == 'undefined' || acc == 'NaN'){
                $('.error-msg').html('Please Select Account');
            }else{
                $('.error-msg').html('');
            }
            
            $('.voucher_list').css('display', 'block');
            $("#voucher").select2({    
                width: '100%',
                placeholder: 'Select Advance',
                ajax: {
                    url: PATH + "Sales/Getdata/bank_cashAdvance",
                    type: "post",
                    allowClear: true,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            searchTerm: params.term, // search term
                            account : acc 
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
            
        }else{
            $('.voucher_list').css('display', 'none');
        }

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
        calculate();
    });

    $("#code").select2({
        width: '100%',
        placeholder: 'Type Item Name ',
        ajax: {
            url: PATH + "Sales/Getdata/Item",
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

            pids.push(parseInt(suggestion.data));

            var inp = '<input type="hidden" name="pid[]" value="' + suggestion.id + '">';
            var tds = '<tr>';
            tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
            tds += '<td>' + suggestion.text + inp + '</td>';
            tds += '<td><select name="uom[]">' + suggestion.uom + '</select></td>';
            tds +=
                '<td><input class="form-control input-sm" value="0" name="qty[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';
            tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                .sales_price +
                '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';

            tds += '<td><input type="hidden" value="' + suggestion.price
                .brokrage + '" name ="item_brokrage[]"><input class="form-control input-sm" value="' +
                suggestion.price
                .igst +
                '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                .cgst +
                '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                .sgst +
                '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" name="item_disc[]" onchange="calculate()" value="0" type="text" ></td>';

            tds +=
                '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="0"   ="" type="text" readonly></td>';
            tds +=
                '<td><input class="form-control input-sm" name="remark[]" placeholder="Remark" type="text"></td>';
            tds += '</tr>';

            $('.tbody').append(tds);
            $('#code').val('');
            calculate();
        } else {
            $('.product_error').html('Selected Product Already Added');
            $('#code').val('');
        }

    });

    $('.ajax-form-submit').on('submit', function(e) {
        $('#save_data').prop('disabled', true);
        $('.error-msg').html('');
        $('.form_proccessing').html('Please wait...');
        e.preventDefault();
        var aurl = $(this).attr('action');
        $.ajax({
            type: "POST",
            url: aurl,
            data: $(this).serialize(),
            success: function(response) {
                if (response.st == 'success') {
                    // $('#fm_model').modal('toggle');
                    //swal("success!", "Your update successfully!", "success");
                    //datatable_load('');
                    //$('#save_data').prop('disabled', false);
                    window.location = "<?=url('sales/salesinvoice')?>"
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

    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $('.dateMask').mask('99-99-9999');

    $("#account").select2({
        width: '66.5%',
        placeholder: 'Type Account',
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
        $('#gsttin').val(data.gsttin);
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
    });


    $("#delivery_code").select2({
        width: '66.5%',
        placeholder: 'Type Delivery Account',
        ajax: {
            url: PATH + "Master/Getdata/search_sale_delivery",
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

    // $('#broker').autocomplete({
    //     serviceUrl: '<?= url('Master/Getdata/search_account') ?>',
    //     type: 'POST',
    //     showNoSuggestionNotice: true,
    //     onSelect: function(suggestion) {
    //         $('#broker').val(suggestion.value);
    //         $('#broker_id').val(suggestion.data);
    //     }
    // });

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

    $('#broker').on('select2:select', function(e) {
        var data = e.params.data;

        $('#fix_brokrage').val(data.brokrage);
        $('#brok_name').text(data.text);
        $('.broker-error').text('');

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
            } else {}

        });

    });


    $("#class").select2({
        width: 'resolve',
        placeholder: 'Type Classification ',
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


    // $('#transport').autocomplete({
    //     serviceUrl: '<?= url('Master/Getdata/search_transport') ?>',
    //     type: 'POST',
    //     showNoSuggestionNotice: true,
    //     onSelect: function(suggestion) {
    //         $('#transport').val(suggestion.value);
    //         $('#transport_id').val(suggestion.data);
    //         $('#transportmode').val(suggestion.mode);
    //     }
    // });

    $("#transport").select2({
        width: 'resolve',
        placeholder: 'Type Transport ',
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



    $("#vehicle").select2({
        width: 'resolve',
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

    $("#broker_ledger").select2({
        width: '100%',
        placeholder: 'Type Broker Account',
        ajax: {
            url: PATH + "Master/Getdata/search_broker_ledger",
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

    $("#get_challan").select2({
        width: '100%',
        placeholder: 'Type Challan NO.',
        minimumInputLength: 1,
        ajax: {
            url: PATH + "Master/Getdata/get_challan",
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

    $('#get_challan').on('select2:select', function(e) {
        $(".tbody").empty();
        var suggesion = e.params.data;
        var item = suggesion.item;
        

        var acc = '<option selected value="' + suggesion.challan.account + '">' + suggesion.challan
            .account_name + '</option>';
        var deli = '<option selected value="' + suggesion.challan.delivery_code + '">' + suggesion
            .challan.delivery_name + '</option>';
        var brok = '<option selected value="' + suggesion.challan.broker + '">' + suggesion.challan
            .broker_name + '</option>';
        var clas = '<option selected value="' + suggesion.challan.class + '">' + suggesion.challan
            .class_name + '</option>';
        var vehi = '<option selected value="' + suggesion.challan.vehicle_modeno + '">' + suggesion
            .challan.vehicle_name + '</option>';
        var trans = '<option selected value="' + suggesion.challan.transport + '">' + suggesion.challan
            .transport_name + '</option>';
        var tran_mode = '<option selected value="' + suggesion.challan.transport_mode + '">' + suggesion
            .challan.transport_mode + '</option>';
        var disc_type = '<option selected value="' + suggesion.challan.disc_type + '">' + suggesion
            .challan.disc_type + '</option>';
        var amtx_mode = '<option selected value="' + suggesion.challan.amtx_type + '">' + suggesion
            .challan.amtx_type + '</option>';
        var amty_mode = '<option selected value="' + suggesion.challan.amty_type + '">' + suggesion
            .challan.amty_type + '</option>';
        var cess_mode = '<option selected value="' + suggesion.challan.cess_type + '">' + suggesion
            .challan.cess_type + '</option>';

        // var tax_option = [
        //     {
        //         id:1,
        //         text:"test"
        //     }
        // ]

        $('.account').append(acc);
        $('.delivery').append(deli);
        $('.broker').append(brok);
        $('.class').append(clas);
        $('.vehicle').append(vehi);
        $('.transport').append(trans);
        $('.trans_mode').append(tran_mode);
        $('.disc_type').append(disc_type);
        $('.amtx_mode').append(amtx_mode);
        $('.amty_mode').append(amty_mode);
        $('.cess_mode').append(cess_mode);

        $('#tds_limit').val(suggesion.challan.tds_limit);
        $('#acc_state').val(suggesion.challan.acc_state);
        $('.gst_no').val(suggesion.challan.gst);
        $('.other').val(suggesion.challan.other);
        $('.lr').val(suggesion.challan.lr_no);
        $('.lr_data').val(suggesion.challan.lr_date);
        $('.igst').val(suggesion.challan.tot_igst);
        $('.cgst').val(suggesion.challan.tot_cgst);
        $('.sgst').val(suggesion.challan.tot_sgst);
        $('.amtx').val(suggesion.challan.amtx);
        $('.amty').val(suggesion.challan.amty);
        $('.cess').val(suggesion.challan.cess);
        $('.tds_per').val(suggesion.challan.tds_per);
        $('.tds_amt').val(suggesion.challan.tds_amt);
        $('.discount').val(suggesion.challan.discount);
        $('.net_amt').val(suggesion.challan.net_amount);
        $('#brok_name').text(suggesion.challan.broker_name);
        $('#fix_brokrage').val(suggesion.challan.fix_brokrage);


        for (i = 0; i < item.length; i++) {
            //  console.log(item[i].brokrage);
            var uom = item[i].item_uom.split(',');
            // console.log(uom);
            var uom_option = '';
            for(j = 0; j < uom.length; j++) {
                var slec = item[i].uom == uom[j] ? 'selected' : '';
                        uom_option += '<option value="' + uom[j] + '" '+ slec +' >' + uom[j] + '</option>';
                        slec = '';
            }
            
            var inp = '<input type="hidden" name="pid[]" value="' + item[i].id + '">';
            var tds = '<tr>';
            tds += '<td><a class="tx-danger btnDelete" data-id="' + item[i].id +
                '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
            tds += '<td>' + item[i].name + '(' + item[i].code + ')' + inp + '</td>';
            tds += '<td><select name="uom[]">' + uom_option + '</select></td>';
            tds += '<td><input class="form-control input-sm" value="' + item[i].qty +
                '" name="qty[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';
            tds += '<td><input class="form-control input-sm" value="' + item[i].rate +
                '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';

            tds += '<td><input type="hidden" value="' + item[i].brokrage +
                '" name ="item_brokrage[]"><input class="form-control input-sm" value="' + item[i]
                .igst +
                '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + item[i].cgst +
                '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + item[i].sgst +
                '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" name="item_disc[]" onchange="calculate()" value="' +
                item[i].item_disc + '" type="text" ></td>';

            tds +=
                '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="' +
                item[i].item_disc + '"   ="" type="text" readonly></td>';
            tds +=
                '<td><input class="form-control input-sm" name="remark[]" value="' + item[i].remark +
                '" placeholder="Remark" type="text"></td>';
            tds += '</tr>';

            $('.tbody').append(tds);
            $('#code').val('');

            var igst = document.getElementById("igst");
            var sgst = document.getElementById("sgst");
            var cgst = document.getElementById("cgst");
            var tds = document.getElementById("tds");
            var cess = document.getElementById("cess");

            var taxes_str = suggesion.challan.taxes;
            var taxes_arr = JSON.parse(taxes_str);
            // console.log('taxes_arr = ' + taxes_arr);
            // console.log('taxes_str = ' + taxes_str);
            var selectedValues = new Array();
            for (k = 0; k < taxes_arr.length; k++) {
                selectedValues[k] = taxes_arr[k]
            }
            $("#tax").val(selectedValues).trigger('change');

            $.each(taxes_arr, function() {
                if (this == 'igst') {
                    igst.style.display = "table-row";
                } else if (this == 'sgst') {
                    sgst.style.display = "table-row";
                } else if (this == 'cgst') {
                    cgst.style.display = "table-row";
                } else if (this == 'tds') {
                    $("#tax option[value='tds']").attr("selected", "selected");
                    tds.style.display = "table-row";
                } else if (this == 'cess') {
                    cess.style.display = "table-row";
                } else {}
            });
            calculate();
        }
        //console.log(suggesion);
        // $('.account').val();
    });


});
</script>
<?= $this->endSection() ?>