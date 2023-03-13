<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5">PurchaseChallan</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Purchase</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
            </ol>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Row -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card custom-card">
                    <div class="card-header card-header-divider">
                        <div class="card-body">
                            <form action="<?= url('purchase/add_purchasechallan') ?>" class="ajax-form-submit"
                                method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-5 form-group">
                                        <label class="form-label">Challan No.: <span class="tx-danger">*</span></label>
                                        <input class="form-control" readonly type="text"
                                            value="<?= @$challan['id'] ? $challan['id'] : $current_id; ?>">
                                    </div>
                                    <?php 
                                        if(!empty($purchasechallan) && isset($purchasechallan)){
                                            if(@$purchasechallan['challan_date'] != '0000-00-00'){
                                                $dt = date_create($purchasechallan['challan_date']);
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
                                    <div class="col-lg-5 form-group">
                                        <label class="form-labestl">Challan Date: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control dateMask" name="challan_date"
                                            value="<?=@$purchasechallan['challan_date'] ? $date : $today; ?>"
                                            placeholder="MM/DD/YYYY" type="text" id="" required>
                                        <input name="id" value="<?=@$purchasechallan['id']?>" type="hidden">
                                    </div>


                                    <div class="col-lg-5 form-group">
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <label class="form-label">Account: <span
                                                        class="tx-danger">*</span></label>
                                                <div class="input-group">
                                                    <select class="form-control" id="account" name='account'>
                                                        <?php if(@$purchasechallan['account_name']) { ?>
                                                        <option value="<?=@$purchasechallan['account']?>">
                                                            <?=@$purchasechallan['account_name']?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>

                                                    <input type="hidden" name="tds_per" id="tds_per"
                                                        value="<?= @$purchasechallan['tds_per']; ?>">
                                                    <input type="hidden" name="tds_limit" id="tds_limit" value="<?= @$purchasechallan['tds_limit']; ?>">
                                                    <input type="hidden" name="acc_state" id="acc_state" value="<?= @$purchasechallan['acc_state']; ?>">
                                                
                                                </div>
                                            </div>

                                            <div class="row col-md-12 form-group">
                                                <label class="form-label col-md-4">GST No.: <span
                                                        class="tx-danger"></span></label>
                                                <input readonly class="form-control col-md-8" type="text" name="gst_no"
                                                    id="gst_no" value="<?= @$purchasechallan['gst_no']; ?>">
                                            </div>

                                            <!-- <div class="row col-md-12 form-group">
                                                <label class="form-label col-md-4">To Account: <span
                                                        class="tx-danger"></span></label>

                                                <select class="form-control" id="to_account" required name='to_account'>
                                                    <?php if(@$p_return['to_account_name']) { ?>
                                                    <option value="<?=@$p_return['to_account']?>">
                                                        <?=@$p_return['to_account_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>

                                            </div> -->


                                            <div class="row col-md-12 form-group">
                                                <label class="form-label col-md-4">Suppl. invoice : <span
                                                        class="tx-danger"></span></label>
                                                <input class="form-control col-md-8" name="supply_inv"
                                                    value="<?= @$purchasechallan['supply_inv']; ?>" type="text"
                                                    formnovalidate>
                                            </div>
                                            <div class="row col-md-12 form-group">
                                                <label class="form-label col-md-4">Item Name or Code: <span
                                                        class="tx-danger">*</span></label>

                                            </div>
                                            <div class="form-label col-md-12">
                                                <select class="form-control" id="code" name='code'> </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-7 form-group">
                                        <div class="row">

                                            <div class="col-md-4 form-group">
                                                <label class="form-label">Suppl. Challan No.: <span
                                                        class="tx-danger"></span></label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input class="form-control" name="sup_chl_no"
                                                    value="<?=@$purchasechallan['sup_chl_no']?>" placeholder="Supplier Challan No" type="text">
                                            </div>

                                            <!-- <div class="col-md-2 form-group">
                                                <label class="form-label">Date.: <span class="tx-danger"></span></label>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <input class="form-control fc-datepicker" name="supply_date"
                                                    value="<?=@$purchasechallan['date']?>" placeholder="MM/DD/YYYY"
                                                    type="text" id="">
                                            </div> -->

                                            <div class="col-md-2 form-group">
                                                <label class="form-label">Broker: <span
                                                        class="tx-danger">*</span></label>
                                            </div>
                                            <div class="col-md-10 form-group">
                                                <div class="input-group">
                                                    <select class="form-control" id="broker" name='broker'>
                                                        <?php if(@$purchasechallan['broker_name']) { ?>
                                                        <option value="<?=@$purchasechallan['broker']?>">
                                                            <?=@$purchasechallan['broker_name']?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                    <input type="hidden" value="" id="fix_brokrage" name="brokrage">
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-2 form-group">
                                                <label class="form-label">Brokerage Type: <span
                                                        class="tx-danger">*</span></label>
                                            </div>
                                            <div class="col-md-10 form-group">
                                                <div class="input-group">
                                                    <div class="col-md-3">
                                                    <label class="rdiobox"><input type="radio" name="brokerage_type" value="fix"
                                                            <?= ( @$purchasechallan['brokerage_type'] == "fix" ? 'checked' : '' ) ?>
                                                            value="fix">Fix</option>

                                                    </div>
                                                    <div class="col-md-3">
                                                    <label class="rdiobox"><input type="radio" name="brokerage_type" value="item_wise"
                                                            <?= (@$purchasechallan['brokerage_type'] == "item_wise" ? 'checked' : '' ) ?>
                                                            value="item_wise">Item Wise</option>
                                                    </div>
                                                </div>
                                            </div> -->

                                            <div class="col-md-2 form-group">
                                                <label class="form-label">Other: <span class="tx-danger"></span></label>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <div class="input-group">
                                                    <input class="form-control" name="other"
                                                        value="<?=@$purchasechallan['other']?>" placeholder ="Enter Other Detail" type="text">
                                                    <!-- <select class="form-control" id="class" name='class'>
                                                        <?php if(@$purchasechallan['class_name']) { ?>
                                                        <option value="<?=@$purchasechallan['class']?>">
                                                            <?=@$purchasechallan['class_name']?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>

                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <a data-toggle="modal" href="<?=url('Master/add_class')?>"
                                                                data-target="#fm_model" data-title="Add New Class "><i
                                                                    style="font-size:20px;"
                                                                    class="fe fe-plus-circle"></i></a>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label class="form-label">City: <span class="tx-danger"></span></label>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <div class="input-group">
                                                    <select class="form-control" id="city" name='city'>
                                                        <?php if(@$purchasechallan['city_name']) { ?>
                                                        <option value="<?=@$purchasechallan['city']?>">
                                                            <?=@$purchasechallan['city_name']?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label class="form-label">LR No.: <span
                                                        class="tx-danger"></span></label>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <input class="form-control" name="lr_no"
                                                    onkeypress="return isDesimalNumberKey(event)"
                                                    value="<?=@$purchasechallan['lr_no']?>"  type="text">
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label class="form-label">LR Date.: <span
                                                        class="tx-danger"></span></label>
                                            </div>
                                            <?php $lr_date = user_date(@$purchasechallan['lr_date']); ?>
                                            <div class="col-md-4 form-group">
                                                <input class="form-control dateMask" placeholder="MM/DD/YYYY"
                                                    type="text" name="lr_date"
                                                    value="<?=@$lr_date?>">
                                            </div>

                                            <div class="col-md-2 form-group">
                                                <label class="form-label">Transport.: <span
                                                        class="tx-danger"></span></label>
                                            </div>
                                            <div class="col-md-10 form-group">
                                                <div class="input-group">
                                                    <select class="form-control" id="transport" name='transport'>
                                                        <?php if(@$purchasechallan['transport_name']) { ?>
                                                        <option value="<?=@$purchasechallan['transport']?>">
                                                            <?=@$purchasechallan['transport_name']?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>

                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <a data-target="#fm_model" data-toggle="modal"
                                                                data-title="Add Transport"
                                                                href="<?=url('master/add_transport')?>"><i
                                                                    style="font-size:20px;"
                                                                    class="fe fe-plus-circle"></i></a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label class="form-label">Transport Mode: </label>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <div class="input-group">
                                                    <select class="form-control select2" id="transport_mode"
                                                        name="transport_mode" >
                                                        <option value="">None</option>
                                                        <option
                                                            <?= ( @$purchasechallan['transport_mode'] == "Rail" ? 'selected' : '' ) ?>
                                                            value="Rail">Rail</option>
                                                        <option
                                                            <?= ( @$purchasechallan['transport_mode'] == "Road" ? 'selected' : '' ) ?>
                                                            value="Road">Road</option>
                                                        <option
                                                            <?= ( @$purchasechallan['transport_mode'] == "Air" ? 'selected' : '' ) ?>
                                                            value="Air">Air</option>
                                                        <option
                                                            <?= ( @$purchasechallan['transport_mode'] == "Ship" ? 'selected' : '' ) ?>
                                                            value="Air">Ship</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2 form-group">
                                                <label class="form-label">Vehicle: <span
                                                        class="tx-danger"></span></label>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <div class="input-group">
                                                    <select class="form-control" id="vehicle" name='vehicle'>
                                                        <?php if(@$purchasechallan['vehicle_name']) { ?>
                                                        <option value="<?=@$purchasechallan['vehicle']?>">
                                                            <?=@$purchasechallan['vehicle_name']?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>

                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <a data-toggle="modal"
                                                                href="<?= url('Master/add_vehicle') ?>"
                                                                data-target="#fm_model" data-title="Enter vehicle"><i
                                                                    style="font-size:20px;"
                                                                    class="fe fe-plus-circle"></i></a>
                                                        </div>
                                                    </div>
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
                                                    <th>IGST</th>
                                                    <th>CGST</th>
                                                    <th>SGST</th>
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

                                                $sub_total=$row['rate'] * $row['qty'] - $row['item_disc'] ;
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
                                                            <option
                                                                <?= ( @$uom_row == $row['uom'] ? 'selected' : '' ) ?>
                                                                value="<?= @$uom_row ?>"><?= @$uom_row ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td><input class="form-control input-sm" value="<?=$row['qty']?>"
                                                            name="qty[]" onchange="calculate()"
                                                            onkeypress="return isDesimalNumberKey(event)" required=""
                                                            type="text"></td>

                                                    <td><input class="form-control input-sm" value="<?=$row['rate']?>"
                                                            name="price[]" onchange="calculate()"
                                                            onkeypress="return isDesimalNumberKey(event)" required=""
                                                            type="text"></td>

                                                    <td><input class="form-control input-sm" value="<?=$row['igst']?>"
                                                            name="igst[]" onchange="calculate()"
                                                            onkeypress="return isDesimalNumberKey(event)" required=""
                                                            type="text"></td>

                                                    <td><input class="form-control input-sm" value="<?=$row['cgst']?>"
                                                            name="cgst[]" onchange="calculate()"
                                                            onkeypress="return isDesimalNumberKey(event)" required=""
                                                            type="text"></td>

                                                    <td><input class="form-control input-sm" value="<?=$row['sgst']?>"
                                                            name="sgst[]" onchange="calculate()"
                                                            onkeypress="return isDesimalNumberKey(event)" required=""
                                                            type="text"></td>

                                                    <td><input class="form-control input-sm"
                                                            value="<?=$row['item_disc']?>" name="item_disc[]"
                                                            onchange="calculate()"
                                                            onkeypress="return isDesimalNumberKey(event)" required=""
                                                            type="text"></td>
                                                    <td><input class="form-control input-sm" name="subtotal[]"
                                                            onchange="calculate()" value="<?= $sub_total ?>" required=""
                                                            type="text" readonly=""></td>
                                                    <td><input class="form-control input-sm" name="remark[]"
                                                            value="<?=$row['remark']?>" placeholder="Remark"
                                                            type="text"></td>
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
                                                <td class="disc_total"></td>
                                                <td class="total"><?= @$total ?></td>
                                                <td></td>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mt-3">
                                            <div class="table-responsive">
                                                <table class="table table-bordered mg-b-0" id="selling_case">
                                                    <!-- <thead>
                                                <tr>
                                                    <th>
                                                        <label id="brok_name"></label>
                                                        <div class="tx-danger broker-error">
                                                        </div>
                                                    </th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control"  
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="brokrage"  id="brokrage" type="text" placeholder="Brokrage Amount"
                                                                value="<?= @$challan['brokrage']; ?>">
                                                        </div>
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th>
                                                        <div class="input-group-sm">
                                                            <select class="form-control" id="broker_ledger"
                                                                name='broker_led'>
                                                                <?php if(@$challan['broker_ledger']) { ?>
                                                                <option value="<?=@$challan['broker_ledger']?>">
                                                                    <?=@$challan['broker_ledger_name']?>
                                                                </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control"  onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="broker_led" id="broker_led" type="text"
                                                                value="<?= @$challan['broker_led']; ?>">
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead> -->
                                                </table>
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
                                                                    <input class="form-control" onchange="calculate()"
                                                                        onkeypress="return isDesimalNumberKey(event)"
                                                                        name="discount" type="text"
                                                                        value="<?= @$purchasechallan['discount']; ?>">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2" name="disc_type"
                                                                            onchange="calculate()">
                                                                            <option
                                                                                <?= ( @$purchasechallan['disc_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                                value="Fixed">Fixed Amount</option>
                                                                            <option
                                                                                <?= ( @$purchasechallan['disc_type'] == '%' ? 'selected' : '' ) ?>
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
                                                                        value="<?= @$purchasechallan['amtx']; ?>">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2" name="amtx_type"
                                                                            onchange="calculate()">
                                                                            <option
                                                                                <?= ( @$purchasechallan['amtx_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                                value="Fixed">Fixed Amount</option>
                                                                            <option
                                                                                <?= ( @$purchasechallan['amtx_type'] == '%' ? 'selected' : '' ) ?>
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
                                                                        value="<?= @$purchasechallan['amty']; ?>">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2" name="amty_type"
                                                                            onchange="calculate()">
                                                                            <option
                                                                                <?= ( @$purchasechallan['amty_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                                value="Fixed">Fixed Amount</option>
                                                                            <option
                                                                                <?= ( @$purchasechallan['amty_type'] == '%' ? 'selected' : '' ) ?>
                                                                                value="%">Per(%) Amount</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th class="amty_amount wd-90"></th>
                                                        </tr>
                                                        <?php 
                                                            $taxes = json_decode(@$purchasechallan['taxes']);
                                                        
                                                        ?>
                                                        <tr>
                                                            <th>Select Tax</th>
                                                            <th colspan="2" class="wd-300">
                                                                <div class="input-group-sm">
                                                                    <select class="select2" id="tax" name="taxes[]"
                                                                        onchange="calculate()" multiple>
                                                                        <?php foreach($tax as $row) { 
                                                                        if($row['name'] == 'igst' && session('state') != @$purchasechallan['acc_state']) {
                                                                        ?>
                                                                        <option value="<?=$row['name'] ?>"
                                                                            <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                            <?=$row['name']; ?></option>

                                                                        <?php }else if($row['name'] == 'cgst'  && session('state') == @$purchasechallan['acc_state']){ ?>

                                                                        <option value="<?=$row['name'] ?>"
                                                                            <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                            <?=$row['name']; ?></option>

                                                                        <?php }else if($row['name'] == 'sgst'  && session('state') == @$purchasechallan['acc_state']){ ?>

                                                                        <option value="<?=$row['name'] ?>"
                                                                            <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                            <?=$row['name']; ?></option>

                                                                        <?php }else if($row['name'] == 'tds' || $row['name'] == 'cess' ) { ?>

                                                                        <option value="<?=$row['name'] ?>"
                                                                            <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                            <?=$row['name']; ?></option>

                                                                        <?php }else{ if(!@$purchasechallan)  { ?>
                                                                        
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
                                                                    <input class="form-control" readonly
                                                                        onchange="calculate()"
                                                                        onkeypress="return isDesimalNumberKey(event)"
                                                                        name="tot_igst" type="text"
                                                                        value="<?= @$purchasechallan['tot_igst']; ?>">
                                                                </div>
                                                            </th>
                                                            <th class="igst_amount wd-90"></th>
                                                        </tr>

                                                        <tr id="sgst"
                                                            style="display:<?php if(!empty($taxes)) { echo in_array("sgst", $taxes) ? 'table-row;' : 'none;'; } else{ echo 'none;'; } ?>">
                                                            <th>(+)SGST</th>
                                                            <th class="wd-300">
                                                                <div class="input-group-sm">
                                                                    <input class="form-control" readonly
                                                                        onchange="calculate()"
                                                                        onkeypress="return isDesimalNumberKey(event)"
                                                                        name="tot_sgst" type="text"
                                                                        value="<?= @$purchasechallan['tot_sgst']; ?>">

                                                                </div>
                                                            </th>
                                                            <th class="sgst_amount wd-90"></th>
                                                        </tr>

                                                        <tr id="cgst"
                                                            style="display:<?php if(!empty($taxes)) { echo in_array("cgst", $taxes) ? 'table-row;' : 'none;'; } else{ echo 'none;'; } ?>">
                                                            <th>(+)CGST</th>
                                                            <th class="wd-300">
                                                                <div class="input-group-sm">
                                                                    <input class="form-control" readonly
                                                                        onchange="calculate()"
                                                                        onkeypress="return isDesimalNumberKey(event)"
                                                                        name="tot_cgst" type="text"
                                                                        value="<?= @$purchasechallan['tot_cgst']; ?>">

                                                                </div>
                                                            </th>
                                                            <th class="cgst_amount wd-90"></th>
                                                        </tr>

                                                        <tr id="tds"
                                                            style="display:<?php if(!empty($taxes)) { echo in_array("tds", $taxes) ? 'table-row;' : 'none;'; }else{ echo 'none;'; } ?>">
                                                            <th>(+)TDS</th>
                                                            <th class="wd-300">
                                                                <div class="input-group-sm">
                                                                    <input class="form-control" readonly
                                                                        onchange="calculate()"
                                                                        onkeypress="return isDesimalNumberKey(event)"
                                                                        name="tds_amt" type="text"
                                                                        value="<?= @$purchasechallan['tds_amt']; ?>">

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
                                                                        value="<?= @$purchasechallan['cess']; ?>">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2" name="cess_type"
                                                                            onchange="calculate()">
                                                                            <option
                                                                                <?= ( @$purchasechallan['cess_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                                value="Fixed">Fixed Amount</option>
                                                                            <option
                                                                                <?= ( @$purchasechallan['cess_type'] == '%' ? 'selected' : '' ) ?>
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
                                <div class="form-group">
                                    <div class="tx-danger error-msg"></div>
                                    <div class="tx-success form_proccessing"></div>
                                </div>
                                <div class="row mt-3">
                                    <input class="btn btn-space btn-primary btn-product-submit" id="save_data"
                                        type="submit">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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

    // var brok_type = $('input[name="brokerage_type"]:checked').val();

    // var fix_brokrage = $( "#fix_brokrage" ).val();

    // if($('#fix_brokrage').html() == "" && qty != "") {
    //     $('.broker-error').text('Please Select Broker..!!');
    // }
    // else{
    //     $('.broker-error').text(' ');
    // }

    // var item_brokrage = $('input[name="item_brokrage[]"]').map(function() {
    //     return parseFloat(this.value); // $(this).val()
    // }).get();

    // console.log('item_brokrage' + item_brokrage);

    var item_disc = $('input[name="item_disc[]"]').map(function() {
        return parseFloat(this.value);
    }).get();



    var price = $('input[name="price[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var igst = $('input[name="igst[]"]').map(function() {
        return parseFloat(this.value);
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

        // var brok_amt = sub * item_brokrage[i] / 100;
        // tot_item_brok += brok_amt;

        $('input[name="subtotal[]"]').eq(i).val(final_sub);

        total += final_sub;
    }
    $('.total').html(total);

    // tot_fix_brok = total * fix_brokrage/100;

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
    }else{
        if ($("#tax option[value='tds").length == 0) {
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
    if (Number.isNaN(tds_per)) {
        tds_per = 0;
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

    var tds_amount = 0;
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
            if(tds_per != ''  && total > tds_limit){
                tds_amount = (total * (tds_per / 100));
                grand_total += tds_amount;
            }
        }
        else {}
    });  
  

    // if(brok_type == "item_wise"){
    //     $('#brokrage').val('+' + tot_item_brok);        
    //     $('#broker_led').val('-' +tot_item_brok);        
    // }else{
    //     $('#brokrage').val('+' +tot_fix_brok);
    //     $('#broker_led').val('-' +tot_fix_brok);
    // }

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

            var inp = '<input type="hidden" name="pid[]" value="' + suggestion.id + '">';
            var tds = '<tr>';
            tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
            tds += '<td>' + suggestion.text + inp + '</td>';
            tds += '<td><select name="uom[]">' + suggestion.uom + '</select></td>';
            tds +=
                '<td><input class="form-control input-sm" value="0" name="qty[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';
            tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                .sales_price +
                '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                .igst +
                '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                .cgst +
                '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';


            tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                .sgst +
                '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" name="item_disc[]" onchange="calculate()" value="0" type="text" ></td>';

            tds +=
                '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="0" required="" type="text" readonly></td>';
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
        $('#save_data').prop('disable', true);
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

                    window.location = "<?=url('Purchase/purchasechallan')?>"
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

    // $('.fc-datepicker').datepicker({
    //     dateFormat: 'yy-mm-dd',
    //     showOtherMonths: true,
    //     selectOtherMonths: true
    // });
    $('.dateMask').mask('99-99-9999');

    $("#account").select2({
        width: '100%',
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
        $('#gst_no').val(data.gsttin);
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

    $("#broker").select2({
        width: '100%',
        placeholder: 'Type Broker',
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
        var tds = document.getElementById("tds");
        var cess = document.getElementById("cess");

        // console.log(igst)

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
        //console.log(tax)
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
    $("#class").select2({
        width: 'resolve',
        placeholder: 'Type Class',
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

    $("#delivery_code").select2({
        width: '100%',
        placeholder: 'Type Delivery Account',
        ajax: {
            url: PATH + "Master/Getdata/search_D_Account",
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
        width: 'resolve',
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

});
</script>
<?= $this->endSection() ?>