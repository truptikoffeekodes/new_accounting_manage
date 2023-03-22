<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<style>
.sale_gst {
    width: 150%;
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
</style>
<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5">Purchase Invoice</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>

    <div class="ml-auto pd-r-100">
        <h2 class="mb-1 font-weight-bold"><span>Purchase Invoice Sr No :</span>
            <?= isset($purchaseinvoice['invoice_no']) ? @$purchaseinvoice['invoice_no'] : $current_id; ?></h2>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <form action="<?= url('Purchase/add_purchaseinvoice') ?>" class="ajax-form-submit-invoice"
                        method="POST" id="Purchaseinvoiceform">
                        <div class="row">
                            
                            <div class="col-lg-3 form-group">
                                <label class="form-label">Ledger Type : </label>
                                <select class="form-control" id="ledger_type" name='ledger_type'>
                                    <option value="<?= @$purchaseinvoice['ledger'] ?>">
                                        <?= @$purchaseinvoice['ledger_name'] ?>
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-3 form-group">
                                <label class="form-label">Voucher Type : </label>
                                <select class="form-control select2"  name='voucher_type'>
                                    <?php foreach($voucher_list as $row){ ?>
                                    <option value="<?= @$row['id'] ?>"  <?=(@$purchaseinvoice['voucher_type'] == $row['id']) ? 'selected' : (($row['set_as'] == 1) ? 'selected' : '') ?>>
                                        <?= @$row['name'] ?>
                                    </option>
                                    <?php } ?>

                                </select>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label class="form-label">Invoice No.: </label>
                                <input class="form-control" readonly type="text" name="invoice_no"
                                    value="<?= isset($purchaseinvoice['invoice_no']) ? @$purchaseinvoice['invoice_no'] : $current_id ?>">
                            </div>

                            <div class="col-lg-6 form-group">
                                <label class="form-label">Invoice Date: </label>
                                <input class="form-control fc-datepicker" placeholder="YYYY-MM-DD" type="text"
                                    name="invoice_date"
                                    value="<?= @$purchaseinvoice['invoice_date'] ? $purchaseinvoice['invoice_date'] : date('Y-m-d') ?>">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label class="form-label">Challan No: </label>
                                <select class="form-control" id="get_challan" name='challan'>
                                    <?php if (@$purchaseinvoice['challan_no']) { ?>
                                    <option value="<?= @$purchaseinvoice['challan_no'] ?>">
                                        <?= @$purchaseinvoice['challan_name'] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-5 form-group">
                                <div class="row">
                                    <div class="row col-lg-12 form-group">

                                        <label class="form-label col-md-4">Account: <span
                                                class="tx-danger">*</span></label>
                                        <div class="input-group col-md-8" style="padding:0px;">
                                            <select class="form-control account" required id="account" name='account'>
                                                <?php if (@$purchaseinvoice['account_name']) { ?>
                                                <option value="<?= @$purchaseinvoice['account'] ?>">
                                                    <?= @$purchaseinvoice['account_name'] ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal"
                                                        href="<?= url('Master/add_account/sundry_creditor') ?>"
                                                        data-target="#fm_model" data-title="Enter Account"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="<?= @$purchaseinvoice['id'] ?>">
                                        <input type="hidden" name="tds_per" id="tds_per"
                                            value="<?= @$purchaseinvoice['tds_per']; ?>">
                                        <input type="hidden" name="tds_limit" id="tds_limit"
                                            value="<?= @$purchaseinvoice['tds_limit']; ?>">
                                        <input type="hidden" name="acc_state" id="acc_state"
                                            value="<?= @$purchaseinvoice['acc_state']; ?>">
                                        <input type="hidden" name="gl_group"
                                            value="<?= @$purchaseinvoice['gl_group']; ?>">

                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">GST No.: <span
                                                class="tx-danger">*</span></label>
                                        <input readonly class="form-control col-md-8 gst_no" type="text" name="gst_no"
                                            id="gst_no" value="<?= @$purchaseinvoice['gst_no']; ?>">
                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Suppl. invoice : <span
                                                class="tx-danger"></span></label>
                                        <input class="form-control col-md-8 supply_inv" name="supply_inv"
                                            value="<?= @$purchaseinvoice['supply_inv']; ?>" type="text" formnovalidate>
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Due Days: </label>
                                        <input class="form-control col-md-8" name="due_days" id="due_days"
                                            value="<?= @$purchaseinvoice['due_days'] ?>" placeholder="Enter Due Days"
                                            onkeypress="return isNumberKey(event)" onkeyup="getduedate(this.value)"
                                            type="text" formnovalidate>
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Due Date: </label>
                                        <input class="form-control fc-datepicker col-md-8" placeholder="YYYY-MM-DD"
                                            type="text" id="due_date" onchange="getduedays(this.value)" name="due_date"
                                            value="<?= @$purchaseinvoice['due_date'] ?>">
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Add Item: <span
                                                class="tx-danger"></span></label>
                                        <div class="input-group col-md-8" style="padding:0px;">
                                            <select class="form-control" id="code" name='code'> </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_item/general') ?>"
                                                        data-target="#fm_model" data-title="Enter Item"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="dz-error-message tx-danger product_error"></div>
                                        </div>
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Particular Name: <span
                                                class="tx-danger"></span></label>
                                        <div class="input-group col-md-8" style="padding:0px;">
                                            <select class="form-control" id="code_new" name='code_new'>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal"
                                                        href="<?= url('Master/add_account_inc_exp') ?>"
                                                        data-target="#fm_model" data-title="Enter Account"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="dz-error-message tx-danger product_error_new"></div>
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
                                        <div class="input-group">

                                            <select class="form-control broker" id="broker" name='broker'>
                                                <?php if (@$purchaseinvoice['broker_name']) { ?>
                                                <option value="<?= @$purchaseinvoice['broker'] ?>">
                                                    <?= @$purchaseinvoice['broker_name'] ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal"
                                                        href="<?= url('Master/add_account/broker') ?>"
                                                        data-target="#fm_model" data-title="Enter Account"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <input type="hidden" value="<?= @$purchaseinvoice['fix_brokrage'] ?>"
                                                id="fix_brokrage" name="brokrage">
                                        </div>
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Brokrage Type: <span
                                                class="tx-danger">*</span></label>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label class="rdiobox"><input checked name="brokerage_type"
                                                <?= @$purchaseinvoice['brokrage_type'] == "fix" ? 'checked' : ''  ?>
                                                value="fix" type="radio" onchange="calculate()">
                                            <span>Fix</span></label>

                                        <label class="rdiobox"><input name="brokerage_type"
                                                <?= @$purchaseinvoice['brokrage_type'] == "item_wise" ? 'checked' : ''  ?>
                                                value="item_wise" type="radio" onchange="calculate()"> <span>Item
                                                Wise</span></label>
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Narration: </label>
                                    </div>
                                    <div class="col-lg-10 form-group">
                                        <div class="input-group">
                                            <input class="form-control other" name="other"
                                                value="<?= @$purchaseinvoice['other'] ?>"
                                                placeholder="Enter Other Detail" type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Suppl. Challan No.: </label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input class="form-control party_bill" name="party_bill"
                                            placeholder="Enter Supplier Challan no."
                                            onkeypress="return isDesimalNumberKey(event)"
                                            value="<?= @$purchaseinvoice['sup_chl_no'] ?>" type="text">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label ">LR No.: <span class="tx-danger"></span></label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control lr" name="lr_no"
                                            onkeypress="return isDesimalNumberKey(event)"
                                            value="<?= @$purchaseinvoice['lr_no'] ?>" placeholder="" type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label ">LR Date.: <span class="tx-danger"></span></label>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <input class="form-control fc-datepicker lr_data" placeholder="YYYY-MM-DD"
                                            type="text" name="lr_date" value="<?= @$purchaseinvoice['lr_date'] ?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Transport.: <span class="tx-danger"></span></label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="input-group">
                                            <select class="form-control transport" id="transport" name='transport'>
                                                <?php if (@$purchaseinvoice['transport_name']) { ?>
                                                <option value="<?= @$purchaseinvoice['transport'] ?>">
                                                    <?= @$purchaseinvoice['transport_name'] ?>
                                                </option>
                                                <?php } ?>
                                            </select>

                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-target="#fm_model" data-toggle="modal"
                                                        data-title="Add Transport"
                                                        href="<?= url('master/add_transport') ?>"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">City.: <span class="tx-danger"></span></label>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="input-group">
                                            <select class="form-control city" id="city" name='city'>
                                                <?php if (@$purchaseinvoice['city_name']) { ?>
                                                <option value="<?= @$purchaseinvoice['city'] ?>">
                                                    <?= @$purchaseinvoice['city_name'] ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Transport Mode: <span
                                                class="tx-danger"></span></label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="input-group">

                                            <select class="select2 trans_mode" id="transport_mode"
                                                name="transport_mode">
                                                <option value="">None</option>
                                                <option
                                                    <?= (@$purchaseinvoice['transport_mode'] == "Rail") ? 'selected' : '' ?>
                                                    value="Rail">Rail</option>
                                                <option
                                                    <?= (@$purchaseinvoice['transport_mode'] == "Road") ? 'selected' : ''  ?>
                                                    value="Road">Road</option>
                                                <option
                                                    <?= (@$purchaseinvoice['transport_mode'] == "Air") ? 'selected' : ''  ?>
                                                    value="Air">Air</option>
                                                <option
                                                    <?= (@$purchaseinvoice['transport_mode'] == "Ship") ? 'selected' : ''  ?>
                                                    value="Air">Ship</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Vehicle: <span class="tx-danger"></span></label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="input-group">
                                            <select class="form-control vehicle" id="vehicle" name='vehicle'>
                                                <?php if (@$purchaseinvoice['vehicle_name']) { ?>
                                                <option value="<?= @$purchaseinvoice['vehicle'] ?>">
                                                    <?= @$purchaseinvoice['vehicle_name'] ?>
                                                </option>
                                                <?php } ?>
                                            </select>

                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_vehicle') ?>"
                                                        data-target="#fm_model" data-title="Enter vehicle"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-fw-widget sale_gst" id="product">
                                    <thead>
                                        <tr>
                                            <th style="width:3%">#</th>
                                            <th style="width:20%">Item</th>
                                            <th>HSN</th>
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
                                        $total = 0.0;
                                        //echo '<pre>';print_r($item);exit;
                                        if (isset($item)) {

                                            foreach ($item as $row) {
                                                if ($row['is_expence'] == 0) {
                                                    $sub_total = $row['rate'] * $row['qty'] - (float)$row['item_disc'];
                                                    $uom = explode(',', $row['item_uom']);
                                                } else {
                                                    $sub_total = $row['rate'];
                                                }
                                                $total += $sub_total;


                                        ?>
                                        <tr class="item_row">
                                            <td><a class="tx-danger btnDelete" data-id="<?= $row['item_id'] ?>"
                                                    title="0"><i class="fa fa-times tx-danger"></i></a></td>

                                            <?php
                                                    if ($row['is_expence'] == 0) {
                                                    ?>

                                            <td><?= $row['name'] ?>(<?= $row['hsn'] ?>)
                                                <input type="hidden" name="pid[]" value="<?= $row['item_id'] ?>">
                                                <input name="taxability[]" value="<?= @$row['taxability'] ?>"
                                                    type="hidden">
                                                <input name="expence[]" value="<?= $row['is_expence'] ?>" type="hidden">

                                            </td>
                                            <td><input class="form-control input-sm" value="<?= $row['hsn'] ?>" readonly
                                                    name="hsn[]" onchange="calculate()" type="text"></td>
                                            <td><select name="uom[]" onchange="calculate()">
                                                    <?php
                                                                foreach ($uom as $uom_row) {

                                                                ?>
                                                    <option <?= (@$uom_row == $row['uom'] ? 'selected' : '') ?>
                                                        value="<?= @$uom_row ?>"><?= @$uom_row ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td><input class="form-control input-sm" value="<?= $row['qty'] ?>"
                                                    name="qty[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" type="text">
                                            </td>
                                            <?php
                                                    } else {
                                                    ?>
                                            <td colspan="4"><?= $row['name'] ?>(<?= $row['code'] ?>)
                                                <input type="hidden" name="pid[]" value="<?= $row['item_id'] ?>">

                                                <input name="expence[]" value="<?= $row['is_expence'] ?>" type="hidden">
                                                <input name="taxability[]" value="<?= @$row['taxability'] ?>"
                                                    type="hidden">
                                                <input name="qty[]" value="" type="hidden">
                                                <input name="uom[]" value="" type="hidden">
                                                <input name="hsn[]" value="" type="hidden">

                                            </td>
                                            <?php
                                                    }
                                                    ?>
                                            <td><input class="form-control input-sm" value="<?= $row['rate'] ?>"
                                                    name="price[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" type="text"></td>

                                            <?php
                                                    if ($row['taxability'] == "N/A") {
                                                    ?>
                                            <td><input class="form-control input-sm" value="<?= $row['igst'] ?>"
                                                    name="igst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)"
                                                    onkeyup="calc_gst_per(this)" type="text">
                                                <input name="igst_amt[]" value="<?= $row['igst_amt'] ?>" type="hidden">
                                                <b class="igst_amt"></b>
                                            </td>

                                            <td><input class="form-control input-sm" value="<?= $row['cgst'] ?>"
                                                    name="cgst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" type="text">
                                                <input name="cgst_amt[]" value="<?= $row['cgst_amt'] ?>" type="hidden">
                                                <b class="cgst_amt"></b>
                                            </td>

                                            <td><input class="form-control input-sm" value="<?= $row['sgst'] ?>"
                                                    name="sgst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" type="text">
                                                <input name="sgst_amt[]" value="<?= $row['sgst_amt'] ?>" type="hidden">
                                                <b class="sgst_amt"></b>
                                            </td>
                                            <?php
                                                    } else {
                                                    ?>
                                            <td><input class="form-control input-sm" value="<?= $row['igst'] ?>"
                                                    name="igst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)"
                                                    onkeyup="calc_gst_per(this)" type="text">
                                                <input name="igst_amt[]" value="<?= $row['igst_amt'] ?>" type="hidden">
                                                <b class="igst_amt"></b>
                                            </td>

                                            <td><input class="form-control input-sm" value="<?= $row['cgst'] ?>"
                                                    name="cgst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" type="text">
                                                <input name="cgst_amt[]" value="<?= $row['cgst_amt'] ?>" type="hidden">
                                                <b class="cgst_amt"></b>
                                            </td>

                                            <td><input class="form-control input-sm" value="<?= $row['sgst'] ?>"
                                                    name="sgst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" type="text">
                                                <input name="sgst_amt[]" value="<?= $row['sgst_amt'] ?>" type="hidden">
                                                <b class="sgst_amt"></b>
                                            </td>
                                            <?php
                                                    }
                                                    ?>
                                            <?php
                                                    if ($row['is_expence'] == 0) {
                                                    ?>
                                            <td><input class="form-control input-sm" value="<?= $row['item_disc'] ?>"
                                                    name="item_disc[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" type="text">
                                                <b class="itm_disc_amt"></b>
                                                <input type="hidden" name="item_discount_hidden[]"
                                                    class="hidden_itm_disc_amt" value="<?= @$row['discount'] ?>">
                                                <input type="hidden" name="item_added_amt_hidden[]"
                                                    class="hidden_added_amt" value="<?= $row['added_amt'] ?>">
                                                <input type="hidden" name="item_per[]" class="item_per"
                                                    value="<?= $row['divide_disc_item_per'] ?>">
                                                <input type="hidden" name="divide_disc_amt[]" class="divide_disc_amt"
                                                    value="<?= $row['divide_disc_item_amt'] ?>">
                                            </td>
                                            </td>
                                            <?php
                                                    } else {
                                                    ?>
                                            <td><input class="form-control input-sm" value="0" name="item_disc[]"
                                                    type="hidden">
                                                <input type="hidden" name="item_discount_hidden[]"
                                                    class="hidden_itm_disc_amt" value="<?= @$row['discount'] ?>">
                                                <input type="hidden" name="item_added_amt_hidden[]"
                                                    class="hidden_added_amt" value="<?= $row['added_amt'] ?>">
                                                <input type="hidden" name="item_per[]" class="item_per"
                                                    value="<?= $row['divide_disc_item_per'] ?>">
                                                <input type="hidden" name="divide_disc_amt[]" class="divide_disc_amt"
                                                    value="<?= $row['divide_disc_item_amt'] ?>">

                                            </td>
                                            <?php
                                                    }
                                                    ?>
                                            <td><input class="form-control input-sm" name="subtotal[]"
                                                    onchange="calculate()" value="<?= $sub_total ?>" type="text"
                                                    readonly=""></td>
                                            <td><input class="form-control input-sm" name="remark[]"
                                                    value="<?= $row['remark'] ?>" placeholder="Remark" type="text">

                                            </td>
                                        </tr>
                                        <?php }
                                        }

                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <td colspan="2" class="text-right">Total</td>
                                        <td></td>
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
                                        <!-- <table class="table table-bordered mg-b-0" id="selling_case">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <label
                                                            id="brok_name"><?= @$purchaseinvoice['broker_name']; ?></label>
                                                        <div class="tx-danger broker-error">
                                                        </div>
                                                    </th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="brokrage" id="brokrage" type="text"
                                                                placeholder="Brokrage Amount"
                                                                value="<?= @$purchaseinvoice['brokrage']; ?>">
                                                        </div>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <div class="input-group-sm">
                                                            <select class="form-control" id="broker_ledger"
                                                                name='broker_ledger'>
                                                                <?php if (@$purchaseinvoice['broker_ledger_name']) { ?>
                                                                <option value="<?= @$purchaseinvoice['broker_ledger'] ?>">
                                                                    <?= @$purchaseinvoice['broker_ledger_name'] ?>
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
                                                                value="<?= @$purchaseinvoice['broker_led_amt']; ?>">
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mt-3">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mg-b-0">
                                            <thead>

                                                <tr>
                                                    <!-- <th>(-)Discount</th> -->
                                                    <td class="wd-100">
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="discount_acc"
                                                                name="discount_acc">
                                                                <?php if (@$purchaseinvoice['discount_acc']) { ?>
                                                                <option
                                                                    value="<?= @$purchaseinvoice['discount_acc'] ?>">
                                                                    <?= @$purchaseinvoice['discount_acc_name'] ?>
                                                                </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </td>

                                                    <td class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control discount" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="discount" type="text"
                                                                value="<?= @$purchaseinvoice['discount']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 disc_type" name="disc_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?= (@$purchaseinvoice['disc_type'] == 'Fixed' ? 'selected' : '') ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= (@$purchaseinvoice['disc_type'] == '%' ? 'selected' : '') ?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="discount_amount wd-90">

                                                    </td>
                                                    <input type="hidden" name="discount_amount_new"
                                                        class="discount_amount_new" value="">
                                                </tr>
                                                <tr>
                                                    <td>Taxable Amount</td>
                                                    <td colspan="2"><input name="taxable"
                                                            value="<?= @$purchaseinvoice['taxable'] ?>"
                                                            class="form-control input-sm" type="text" readonly>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>Select Tax</th>
                                                    <th colspan="2" class="wd-300">
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="tax" name="taxes[]"
                                                                onchange="calculate()" multiple>
                                                                <?php
                                                                $taxes = json_decode(@$purchaseinvoice['taxes']);
                                                                // print_r($tax);
                                                                // echo in_array($tax[0]['name'], $taxes);exit;
                                                                if (!empty($purchaseinvoice)) {
                                                                    //$new_tax = json_decode($salesinvoice['taxes']);
                                                                ?>
                                                                <option value="igst" <?php if (!empty($taxes)) {
                                                                                                echo (in_array("igst", $taxes)) ? 'selected' : '';
                                                                                            } ?>>
                                                                    IGST</option>
                                                                <option value="cgst" <?php if (!empty($taxes)) {
                                                                                                echo (in_array("cgst", $taxes)) ? 'selected' : '';
                                                                                            } ?>>
                                                                    CGST</option>
                                                                <option value="sgst" <?php if (!empty($taxes)) {
                                                                                                echo (in_array("sgst", $taxes)) ? 'selected' : '';
                                                                                            } ?>>
                                                                    SGST</option>

                                                                <?php

                                                                }

                                                                ?>
                                                            </select>
                                                        </div>
                                                    </th>
                                                </tr>

                                                <tr id="igst" style="display:<?php if (!empty($taxes)) {
                                                                                    echo (in_array("igst", $taxes)) ? 'table-row;' : 'none;';
                                                                                } else {
                                                                                    echo 'none;';
                                                                                } ?>none; ">
                                                    <th>
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="igst_acc" name="igst_acc">
                                                                <?php if (@$purchaseinvoice['igst_acc']) { ?>
                                                                <option value="<?= @$purchaseinvoice['igst_acc'] ?>">
                                                                    <?= @$purchaseinvoice['igst_acc_name'] ?>
                                                                </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_igst" type="text"
                                                                value="<?= @$purchaseinvoice['tot_igst']; ?>">
                                                        </div>
                                                    </th>
                                                    <th class="igst_amount wd-90"></th>
                                                </tr>

                                                <tr id="sgst" style="display:<?php if (!empty($taxes)) {
                                                                                    echo (in_array("sgst", $taxes)) ? 'table-row;' : 'none;';
                                                                                } else {
                                                                                    echo 'none;';
                                                                                } ?> none;">
                                                    <th>
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="sgst_acc" name="sgst_acc">
                                                                <?php if (@$purchaseinvoice['sgst_acc']) { ?>
                                                                <option value="<?= @$purchaseinvoice['sgst_acc'] ?>">
                                                                    <?= @$purchaseinvoice['sgst_acc_name'] ?>
                                                                </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_sgst" type="text"
                                                                value="<?= @$purchaseinvoice['tot_sgst']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="sgst_amount wd-90"></th>
                                                </tr>

                                                <tr id="cgst" style="display:<?php if (!empty($taxes)) {
                                                                                    echo (in_array("cgst", $taxes)) ? 'table-row;' : 'none;';
                                                                                } else {
                                                                                    echo 'none;';
                                                                                } ?>none; ">
                                                    <th>
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="cgst_acc" name="cgst_acc">
                                                                <?php if (@$purchaseinvoice['cgst_acc']) { ?>
                                                                <option value="<?= @$purchaseinvoice['cgst_acc'] ?>">
                                                                    <?= @$purchaseinvoice['cgst_acc_name'] ?>
                                                                </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_cgst" type="text"
                                                                value="<?= @$purchaseinvoice['tot_cgst']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="cgst_amount wd-90"></th>
                                                </tr>

                                                <tr id="tds" style="display:<?php if (!empty($taxes)) {
                                                                                echo (in_array("tds", $taxes)) ? 'table-row;' : 'none;';
                                                                            } else {
                                                                                echo 'none;';
                                                                            } ?>none; ">
                                                    <th>(+)TDS</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control tds_amt" readonly
                                                                onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tds_amt" type="text"
                                                                value="<?= @$purchaseinvoice['tds_amt']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="tds_amount wd-90"></th>
                                                </tr>

                                                <tr id="cess" style="display:<?php if (!empty($taxes)) {
                                                                                    echo (in_array("cess", $taxes)) ? 'table-row;' : 'none;';
                                                                                } else {
                                                                                    echo 'none;';
                                                                                } ?>none;">
                                                    <th>(+)Cess</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control cess" onchange="calculate()"
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="cess" type="text"
                                                                value="<?= @$purchaseinvoice['cess']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 cess_mode" name="cess_type"
                                                                    onchange="calculate()">
                                                                    <option
                                                                        <?= (@$purchaseinvoice['cess_type'] == 'Fixed' ? 'selected' : '') ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= (@$purchaseinvoice['cess_type'] == '%' ? 'selected' : '') ?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="cess_amount wd-90"></th>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="round_acc" name="round">
                                                                <?php if (@$purchaseinvoice['round_acc']) { ?>
                                                                <option value="<?= @$purchaseinvoice['round_acc'] ?>">
                                                                    <?= @$purchaseinvoice['round_acc_name'] ?>
                                                                </option>

                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </td>
                                                    <td><input class="form-control input-sm" onchange="calculate()"
                                                            value="<?= @$purchaseinvoice['round_diff'] ?>"
                                                            name="round_diff" type="text"></td>
                                                    <td class="wd-90 cr_dr_round"></td>


                                                </tr>
                                                <tr>
                                                    <td>Net Amount</td>
                                                    <td colspan="2"><input class="form-control input-sm net_amt"
                                                            name="net_amount" type="text"
                                                            value="<?= @$purchaseinvoice['net_amount']; ?>" readonly>
                                                    </td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="tx-danger error-msg-invoice"></div>
                            <div class="tx-success form_proccessing_invoice"></div>
                        </div>
                        <div class="row mt-3">
                            <input class="btn btn-space btn-primary btn-product-submit" id="save_data_invoice"
                                type="submit" value="Submit">
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
    if (isset($id)) { ?>
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

function calc_gst_per(obj) {
    var igst = $(obj).val();
    if (igst == '' || igst == 'undefined' || isNaN(igst)) {
        igst = 0;
    }

    $(obj).closest('.item_row').find('input[name="cgst[]"]').val(parseFloat(igst) / 2);
    $(obj).closest('.item_row').find('input[name="sgst[]"]').val(parseFloat(igst) / 2);
}
var pids_item = [];
var pids_exp = [];

function calculate() {
    var qty = $('input[name="qty[]"]').map(function() {
        return parseFloat(this.value); // $(this).val()
    }).get();
    //console.log(qty);


    var item_disc = $('input[name="item_disc[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var price = $('input[name="price[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var igst = $('input[name="igst[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var expence = $('input[name="expence[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var pid = $('input[name="pid[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var total = 0.0;
    var igst_amt = 0.0;
    var tot_item_brok = 0.0;
    var tot_fix_brok = 0.0;
    var uom_name = '';
    var discount_disable = 0;
    //console.log(price);
    for (var i = 0; i < pid.length; i++) {

        if (expence[i] == 0) {
            if (isNaN(item_disc[i])) {
                item_disc[i] = 0;
            }

            if (price[i] == '' || price[i] == 'undefined' || isNaN(price[i])) {
                price[i] = 0;
            }

            if (item_disc[i] == '' || item_disc[i] == 'undefined' || isNaN(item_disc[i])) {
                item_disc[i] = 0;
            }

            if (igst[i] == '' || igst[i] == 'undefined' || isNaN(igst[i])) {
                igst[i] = 0;
            }

            if (item_disc[i] > 0) {
                discount_disable = 1;

            } else {
                if (discount_disable != 1) {
                    discount_disable = 0;
                } else {
                    discount_disable = 1;
                }
            }

            var sub = qty[i] * price[i];
            //console.log(qty[i]);

            var disc_amt = sub * item_disc[i] / 100;
            var final_sub = sub - disc_amt;

            igst_amt += final_sub * igst[i] / 100;

            var item_igst_amt = final_sub * igst[i] / 100;

            item_igst_amt = Number(item_igst_amt).toFixed(2);
            $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
            $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
            $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);
            $('.igst_amt').eq(i).text(item_igst_amt);
            $('.cgst_amt').eq(i).text(item_igst_amt / 2);
            $('.sgst_amt').eq(i).text(item_igst_amt / 2);

            $('input[name="subtotal[]"]').eq(i).val(final_sub.toFixed(2));
            uom_name = $('select[name="uom[]"] :selected').eq(i).text();

            // update item column modification 18-01-2023
            $('input[name="subtotal[]"]').eq(i).closest('.item_row').find('.uom_name').html('/ ' + uom_name);
            $('input[name="subtotal[]"]').eq(i).closest('.item_row').find('.itm_disc_amt').html(parseFloat(disc_amt
                .toFixed(2)));
            $('input[name="subtotal[]"]').eq(i).closest('.item_row').find(".hidden_itm_disc_amt").val(disc_amt.toFixed(
                2));
        }
        if (expence[i] == 1) {

            if (price[i] == '' || price[i] == 'undefined' || isNaN(price[i])) {
                price[i] = 0;
            }

            if (igst[i] == '' || igst[i] == 'undefined' || isNaN(igst[i])) {
                igst[i] = 0;
            }

            var final_sub = price[i];

            igst_amt += final_sub * igst[i] / 100;
            item_igst_amt = final_sub * igst[i] / 100;

            item_igst_amt = Number(item_igst_amt).toFixed(2);
            $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
            $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
            $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);
            $('.igst_amt').eq(i).text(item_igst_amt);
            $('.cgst_amt').eq(i).text(item_igst_amt / 2);
            $('.sgst_amt').eq(i).text(item_igst_amt / 2);


            $('input[name="subtotal[]"]').eq(i).val(final_sub);
        }
        //console.log("final_sub " + final_sub);

        total += final_sub;
    }


    $('.total').html(total.toFixed(2));

    var discount = $('input[name="discount"]').val();

    //--- Start Disable Discount on item discount added ---//

    if (discount_disable == 1) {
        $('input[name="discount"]').val("0");
        $('input[name="discount"]').attr('readonly', 'readonly');
    } else {
        $('input[name="discount"]').removeAttr('readonly');
    }

    //--- End Disable Discount on item discount added ---//

    //--- Start Disable Item discount on discount added ---//
    for (var i = 0; i < pid.length; i++) {
        if (discount > 0) {
            $('input[name="item_disc[]"]').eq(i).val("0")
            $('input[name="item_disc[]"]').eq(i).attr('readonly', 'readonly');
        } else {
            $('input[name="item_disc[]"]').eq(i).removeAttr('readonly');
        }
    }
    //--- End Disable Item discount on discount added ---//

    var amty = parseFloat($('input[name="amty"]').val());
    var cess = parseFloat($('input[name="cess"]').val());
    var tds_per = $('#tds_per').val();
    var tds_limit = parseInt($('#tds_limit').val());

    var com_state = parseInt(<?= session('state') ?>);
    var acc_state = parseInt($('#acc_state').val());

    if (total < tds_limit) {
        $("#tax option[value='tds']").remove();
    } else {
        if ($("#tax option[value='tds").length == 0) {
            $('#tax').append('<option value="tds">tds</option>');
        }
    }

    if (Number.isNaN(discount)) {
        discount = 0;
    }

    if (Number.isNaN(amty)) {
        amty = 0;
    }
    if (Number.isNaN(cess)) {
        cess = 0;
    }

    var discount_type = $('select[name=disc_type] option').filter(':selected').val();
    // var amtx_type = $('select[name=amtx_type] option').filter(':selected').val();
    var amty_type = $('select[name=amty_type] option').filter(':selected').val();
    var cess_type = $('select[name=cess_type] option').filter(':selected').val();
    // discount calculation modification update 16-01-2023
    if (discount_type == '%') {
        var disc = 0;
        var item_total = 0;
        for (var i = 0; i < pid.length; i++) {
            if (expence[i] == 0) {
                disc++;
                var item_price = price[i] * qty[i];
                item_total += item_price;
            }
        }
        discount_amount = (item_total * (discount / 100));
        $('.discount_amount').html('- ' + parseFloat(discount_amount).toFixed(2));
        $('.discount_amount_new').val(discount_amount);
        if (discount_amount > 0) {
            var total = 0;
            //var divide_disc = discount_amount / disc;
            var igst_amt = 0;

            for (var i = 0; i < pid.length; i++) {
                if (expence[i] == 0) {
                    if (price[i] == '' || price[i] == 'undefined' || isNaN(price[i])) {
                        price[i] = 0;
                    }

                    if (item_disc[i] == '' || item_disc[i] == 'undefined' || isNaN(item_disc[i])) {
                        item_disc[i] = 0;
                    }

                    if (igst[i] == '' || igst[i] == 'undefined' || isNaN(igst[i])) {
                        igst[i] = 0;
                    }
                    //update item column 17-01-2023
                    var sub = qty[i] * price[i];
                    // var disc_amt = sub * item_disc[i] / 100;
                    var item_per = (sub * 100) / item_total;
                    var divide_disc = (item_per / 100) * discount_amount;
                    // append discount amount here ......
                    //var indexx = $(".hidden_discount").eq(i).val(divide_disc.toFixed(2));
                    $(".item_per").eq(i).val(item_per.toFixed(2));
                    $(".divide_disc_amt").eq(i).val(divide_disc.toFixed(2));

                    //var final_sub = sub - disc_amt;

                    var abc = sub - divide_disc;
                    igst_amt += abc * igst[i] / 100;

                    item_igst_amt = abc * igst[i] / 100;
                    item_igst_amt = Number(item_igst_amt).toFixed(2);
                    $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                    $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    $('.igst_amt').eq(i).text(item_igst_amt);
                    $('.cgst_amt').eq(i).text(item_igst_amt / 2);
                    $('.sgst_amt').eq(i).text(item_igst_amt / 2);

                } else {
                    if (price[i] == '' || price[i] == 'undefined' || isNaN(price[i])) {
                        price[i] = 0;
                    }

                    if (item_disc[i] == '' || item_disc[i] == 'undefined' || isNaN(item_disc[i])) {
                        item_disc[i] = 0;
                    }

                    if (igst[i] == '' || igst[i] == 'undefined' || isNaN(igst[i])) {
                        igst[i] = 0;
                    }


                    var abc = price[i];
                    igst_amt += abc * igst[i] / 100;
                    item_igst_amt = final_sub * igst[i] / 100;
                    item_igst_amt = Number(item_igst_amt).toFixed(2);
                    $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                    $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    $('.igst_amt').eq(i).text(item_igst_amt);
                    $('.cgst_amt').eq(i).text(item_igst_amt / 2);
                    $('.sgst_amt').eq(i).text(item_igst_amt / 2);
                }

                total += abc;
            }
        }
    } else {
        $('.discount_amount').html('- ' + parseFloat(discount).toFixed(2));
        $('.discount_amount_new').val(discount);
        if (discount > 0) {
            var total = 0;
            var item_total = 0;
            var disc = 0;
            for (var i = 0; i < pid.length; i++) {
                if (expence[i] == 0) {
                    disc++;
                    var item_price = price[i] * qty[i];
                    item_total += item_price;
                }
            }
            //var divide_disc = discount/ disc;

            var igst_amt = 0;
            for (var i = 0; i < pid.length; i++) {
                if (expence[i] == 0) {

                    if (price[i] == '' || price[i] == 'undefined' || isNaN(price[i])) {
                        price[i] = 0;
                    }

                    if (item_disc[i] == '' || item_disc[i] == 'undefined' || isNaN(item_disc[i])) {
                        item_disc[i] = 0;
                    }

                    if (igst[i] == '' || igst[i] == 'undefined' || isNaN(igst[i])) {
                        igst[i] = 0;
                    }
                    //update item column 17-01-2023
                    var sub = qty[i] * price[i];
                    //var disc_amt = sub * item_disc[i] / 100;
                    var item_per = (sub * 100) / item_total;
                    var divide_disc = (item_per / 100) * discount;

                    // var indexx = $(".hidden_discount").eq(i).val(divide_disc.toFixed(2));
                    $(".item_per").eq(i).val(item_per.toFixed(2));
                    $(".divide_disc_amt").eq(i).val(divide_disc.toFixed(2));

                    var abc = sub - divide_disc;
                    igst_amt += abc * igst[i] / 100;
                    item_igst_amt = abc * igst[i] / 100;
                    item_igst_amt = Number(item_igst_amt).toFixed(2);
                    $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                    $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    $('.igst_amt').eq(i).text(item_igst_amt);
                    $('.cgst_amt').eq(i).text(item_igst_amt / 2);
                    $('.sgst_amt').eq(i).text(item_igst_amt / 2);

                } else {
                    if (price[i] == '' || price[i] == 'undefined' || isNaN(price[i])) {
                        price[i] = 0;
                    }

                    if (item_disc[i] == '' || item_disc[i] == 'undefined' || isNaN(item_disc[i])) {
                        item_disc[i] = 0;
                    }

                    if (igst[i] == '' || igst[i] == 'undefined' || isNaN(igst[i])) {
                        igst[i] = 0;
                    }

                    var sub = price[i];
                    var final_sub = price[i];

                    var abc = price[i];
                    igst_amt += abc * igst[i] / 100;

                    item_igst_amt = final_sub * igst[i] / 100;
                    item_igst_amt = Number(item_igst_amt).toFixed(2);
                    $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                    $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    $('.igst_amt').eq(i).text(item_igst_amt);
                    $('.cgst_amt').eq(i).text(item_igst_amt / 2);
                    $('.sgst_amt').eq(i).text(item_igst_amt / 2);
                }
                total += abc;
            }
        }
    }


    var grand_total = total;

    if (amty_type == '%') {
        amty_amount = (total * (amty / 100));
        var divide_amt = amty_amount / qty.length;

        $('.amty_amount').html('+ ' + parseFloat(amty_amount).toFixed(2));
        grand_total += (total * (amty / 100));
    } else {
        $('.amty_amount').html('+ ' + parseFloat(amty).toFixed(2));
        grand_total += amty;

        var divide_amt = amty / qty.length;

    }

    // added amount
    for (var i = 0; i < pid.length; i++) {
        // append add amount here ......
        var indexx = $(".item_row").find(".hidden_added_amt").val(divide_amt.toFixed(2));

    }

    $('input[name="taxable"]').val(grand_total.toFixed(2));


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
            if (tds_per != '' && total > tds_limit) {
                tds_amount = (total * (tds_per / 100));
                grand_total += tds_amount;
            }
        } else {}
    });

    var round_amt = Math.round(parseFloat(grand_total)).toFixed(2);
    var round_diff = parseFloat($('input[name="round_diff"]').val());
    if (isNaN(round_diff)) {
        round_diff = 0;
    }
    var cr_dr = '';

    if (round_diff < 0) {
        cr_dr = 'DR';
    } else {
        cr_dr = 'CR';
    }

    var final_amt = grand_total + round_diff;

    $('input[name="net_amount"]').val(final_amt.toFixed(2));
    $('input[name="round_diff"]').val(round_diff.toFixed(2));
    $('.cr_dr_round').html(((cr_dr == 'CR') ? '+' : '') + round_diff.toFixed(2) + ' ' + cr_dr);
    $('input[name="tot_igst"]').val(igst_amt.toFixed(2));
    $('input[name="tot_cgst"]').val(cgst.toFixed(2));
    $('input[name="tot_sgst"]').val(sgst.toFixed(2));
    $('input[name="tds_amt"]').val(tds_amount.toFixed(2));
    $('.igst_amount').html('+ ' + igst_amt.toFixed(2));
    $('.cgst_amount').html('+ ' + cgst.toFixed(2));
    $('.sgst_amount').html('+ ' + sgst.toFixed(2));
    $('.cess_amount').html('+ ' + cess.toFixed(2));
    $('.tds_amount').html('+ ' + tds_amount.toFixed(2));


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

    var expence = $('input[name="expence[]"]').map(function() {
        return parseInt(this.value); // $(this).val()
    }).get();
    // console.log(pids);
    // console.log(expence);
    // var pids_item = [];
    // var pids_exp = [];
    for (var pt = 0; pt < pids.length; pt++) {
        //console.log(expence[0]);
        if (expence[pt] == 0) {
            pids_item.push(pids[pt]);
        } else {
            pids_exp.push(pids[pt]);
        }
    }
    $("#product").on('click', '.btnDelete', function() {

            var id = $(this).data('id');
            var expence = $(this).data('expence');

            if (expence == 0) {
                const index = pids_item.indexOf($(this).data('id'));
                if (index !== -1) {
                    delete pids_item[index];
                }
            } else {
                const index = pids_exp.indexOf($(this).data('id'));
                if (index !== -1) {
                    delete pids_exp[index];
                }
            }

            $(this).closest('tr').remove();
            calculate();
    });

    $("#round").select2({
        width: 'resolve',
        placeholder: 'Select Ledger ',
        ajax: {
            url: PATH + "Master/Getdata/round_off",
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
        width: 'resolve',
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
        $('.product_error_new').html('');
        // console.log(suggestion);
        if (pids_item.includes(parseInt(suggestion.id)) == false) {
            $('.product_error').html('');
            pids_item.push(parseInt(suggestion.id));

            var inp = '<input type="hidden" name="pid[]" value="' + suggestion.id + '">';
            var expence = '<input type="hidden" name="expence[]" value="0">';

            var taxability = '<input name="taxability[]" value="' + suggestion.price.taxability +
                '" type="hidden">';

            var tds = '<tr class="item_row">';
            tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
            tds += '<td>' + suggestion.text + inp + expence + taxability + '</td>';
            tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                .hsn +
                '" name="hsn[]" readonly onchange="calculate()"  type="text"></td>';
            tds += '<td><select name="uom[]" onchange="calculate()">' + suggestion.uom +
                '</select></td>';
            tds +=
                '<td><input class="form-control input-sm" value="0" name="qty[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';
            tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                .purchase_price +
                '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            if (suggestion.price.taxability == 'N/A') {
                tds +=
                    '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"><b class="igst_amt"></b></td>';
                tds +=
                    '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"><b class="cgst_amt"></b></td>';
                tds +=
                    '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"><b class="sgst_amt"></b>></td>';

            } else {
                var igst_amt = '<input name="igst_amt[]" value="" type="hidden">';
                var cgst_amt = '<input name="cgst_amt[]" value="" type="hidden">';
                var sgst_amt = '<input name="sgst_amt[]" value="" type="hidden">';
                tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                    .igst +
                    '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text">' +
                    igst_amt + '<b class="igst_amt"></b></td>';

                tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                    .cgst +
                    '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                    cgst_amt + '<b class="cgst_amt"></b></td>';


                tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                    .sgst +
                    '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                    sgst_amt + '<b class="sgst_amt"></b></td>';

            }

            tds +=
                '<td><input class="form-control input-sm" name="item_disc[]" onchange="calculate()" value="0" type="text"><b class="itm_disc_amt"></b><input type="hidden" name="item_discount_hidden[]" class="hidden_itm_disc_amt"><input type="hidden" name="item_per[]" class="item_per"><input type="hidden" name="divide_disc_amt[]" class="divide_disc_amt"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt"></td>';
            tds +=
                '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="0" type="text" readonly></td>';
            tds +=
                '<td><input class="form-control input-sm" name="remark[]" placeholder="Remark" type="text"></td>';
            tds += '</tr>';

            $('.tbody').append(tds);
            $('#code').val('');
            calculate();
        } else {
            $('.product_error').html('Selected Item Already Added');
            $('#code').val('');
        }
    });
    $("#code_new").select2({
        width: 'resolve',
        placeholder: 'Type Particular Name ',
        ajax: {
            url: PATH + "Master/Getdata/particular",
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

    $('#code_new').on('select2:select', function(e) {
        var suggestion = e.params.data;
        //console.log(itemid.toString().indexOf(suggestion.id));
        $('.product_error').html('');


        if (pids_exp.includes(parseInt(suggestion.id)) == false) {
            $('.product_error_new').html('');

            pids_exp.push(parseInt(suggestion.id));

            var inp = '<input type="hidden" name="pid[]" value="' + suggestion.id + '">';
            var expence = '<input type="hidden" name="expence[]" value="1">';
            var qty = '<input value="" name="qty[]" type="hidden">';
            var item_disc = '<input value="" name="item_disc[]" type="hidden">';
            var item_per = '<input value="" name="item_per[]" type="hidden">';
            var divide_disc_amt = '<input value="" name="divide_disc_amt[]" type="hidden">';
            var uom = '<input value="" name="uom[]" type="hidden">';
            var hsn = '<input value="" name="hsn[]" type="hidden">';

            var taxability = '<input type="hidden" name="taxability[]" value="' + suggestion.paticular
                .taxability + '">';


            var tds = '<tr class="item_row">';
            tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
            tds += '<td colspan="4">' + suggestion.text + inp + expence + qty + item_disc +
                uom + hsn + taxability + item_per + divide_disc_amt + '</td>';

            tds +=
                '<td><input class="form-control input-sm" value="0" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            if (suggestion.paticular.taxability == 'N/A') {
                tds +=
                    '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"><b class="igst_amt"></b></td>';
                tds +=
                    '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"><b class="cgst_amt"></b></td>';
                tds +=
                    '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"><b class="sgst_amt"></b></td>';

            } else {
                var igst_amt = '<input name="igst_amt[]" value="" type="hidden">';
                var cgst_amt = '<input name="cgst_amt[]" value="" type="hidden">';
                var sgst_amt = '<input name="sgst_amt[]" value="" type="hidden">';
                tds += '<td><input class="form-control input-sm" value="' + suggestion.paticular
                    .igst +
                    '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text">' +
                    igst_amt + '<b class="igst_amt"></b></td>';

                tds += '<td><input class="form-control input-sm" value="' + suggestion.paticular
                    .cgst +
                    '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                    cgst_amt + '<b class="cgst_amt"></b></td>';


                tds += '<td><input class="form-control input-sm" value="' + suggestion.paticular
                    .sgst +
                    '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                    sgst_amt + '<b class="sgst_amt"></b></td>';

            }
            tds +=
                '<td><input type="hidden" name="item_discount_hidden[]" class="hidden_itm_disc_amt"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt"></td>';
            tds +=
                '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="0" required="" type="text" readonly></td>';

            tds +=
                '<td><input class="form-control input-sm" name="remark[]" placeholder="Remark" type="text"></td>';
            tds += '</tr>';

            $('.tbody').append(tds);
            $('#code_new').val('');
            calculate();
        } else {
            $('.product_error_new').html('Selected Expence Already Added');
            $('#code_new').val('');
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
            } else {}

        });

    });

    $('.ajax-form-submit-invoice').on('submit', function(e) {
        $('#save_data_invoice').prop('disabled', true);
        $('.error-msg-invoice').html('');
        $('.form_proccessing_invoice').html('Please wait...');
        e.preventDefault();
        var aurl = $(this).attr('action');
        var form = $(this);
        var formdata = false;

        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            type: "POST",
            url: aurl,
            cache: false,
            contentType: false,
            processData: false,
            data: formdata ? formdata : form.serialize(),

            success: function(response) {
                if (response.st == 'success') {
                    window.location = "<?= url('purchase/purchaseinvoice') ?>"
                } else {
                    $('.form_proccessing_invoice').html('');
                    $('#save_data_invoice').prop('disabled', false);
                    $('.error-msg-invoice').html(response.msg);
                }
            },
            error: function() {
                $('#save_data_invoice').prop('disabled', false);
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

    $("#account").select2({
        width: 'resolve',
        placeholder: 'Type Account',
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

    $('#account').on('select2:select', function(e) {

        var data = e.params.data;
        //console.log(data.gsttin);
        $('#gst_no').val(data.gsttin);
        $('#tds_per').val(data.tds);
        $('#tds_limit').val(data.tds_limit);
        $('#acc_state').val(data.state);
        $('input[name="due_days"]').val(data.due_day);
        $('input[name="gl_group"]').val(data.data.gl_group);

        var com_state = parseInt(<?= @session('state') ?>);
        var acc_state = parseInt($('#acc_state').val());

        if (com_state == acc_state) {

            $("#tax option[value='igst']").remove();
            $('#igst_acc').attr('required', false);
            if ($("#tax option[value='sgst']").length == 0) {
                $('#tax').append('<option value="sgst">sgst</option>');
                //$('#sgst_acc').attr('required', required); 

            }
            if ($("#tax option[value='cgst']").length == 0) {
                $('#tax').append('<option value="cgst">cgst</option>');
                //$('#cgst_acc').attr('required', required); 
            }
            $("#tax option[value='sgst']").attr("selected", "selected");
            $("#tax option[value='cgst']").attr("selected", "selected");
            $('#sgst_acc').attr('required', true);
            $('#cgst_acc').attr('required', true);

        } else {
            $('#sgst_acc').attr('required', false);
            $('#cgst_acc').attr('required', false);
            $("#tax option[value='sgst']").remove();
            $("#tax option[value='cgst']").remove();

            if ($("#tax option[value='igst']").length == 0) {
                $('#tax').append('<option value="igst">igst</option>');
                //$('#igst_acc').prop('required', ); 
            }
            $("#tax option[value='igst']").attr("selected", "selected");
            $('#igst_acc').attr('required', true);
        }
        enable_gst_option();
        calculate();
    });
    $("#igst_acc").select2({
        width: '100%',
        placeholder: 'Type Account Name',
        ajax: {
            url: PATH + "Master/Getdata/search_igst_account",
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
    $("#cgst_acc").select2({
        width: '100%',
        placeholder: 'Type Account Name',
        ajax: {
            url: PATH + "Master/Getdata/search_cgst_account",
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
    $("#sgst_acc").select2({
        width: '100%',
        placeholder: 'Type Account Name',
        ajax: {
            url: PATH + "Master/Getdata/search_sgst_account",
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
    $("#discount_acc").select2({
            width: '100%',
            placeholder: 'Type Account Name',
            ajax: {
                url: PATH + "Master/Getdata/search_discount_account",
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
        $("#round_acc").select2({
            width: '100%',
            placeholder: 'Type Account Name',
            ajax: {
                url: PATH + "Master/Getdata/search_round_account",
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


    $('#broker').on('select2:select', function(e) {
        var data = e.params.data;

        $('#fix_brokrage').val(data.brokrage);
        $('#brok_name').text(data.text);
        $('.broker-error').text('');
    });




    $("#get_challan").select2({
        width: 'resolve',
        placeholder: {
            id: '',
            text: 'None Selected'
        },
        allowClear: true,
        ajax: {
            url: PATH + "Purchase/Getdata/get_purchase_challan",
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


        if (suggesion.purchasechallan.amty_type == '%') {
            $(".amty_type option[value='%']").attr("selected", "selected");
        } else {
            $(".amty_type option[value='Fixed']").attr("selected", "selected");
        }

        if (suggesion.purchasechallan.disc_type == '%') {
            $(".disc_type option[value='%']").attr("selected", "selected");
        } else {
            $(".disc_type option[value='Fixed']").attr("selected", "selected");
        }

        var acc = '<option selected value="' + suggesion.purchasechallan.account + '">' + suggesion
            .purchasechallan
            .account_name + '</option>';

        var brok = '<option selected value="' + suggesion.purchasechallan.broker + '">' + suggesion
            .purchasechallan.broker_name + '</option>';

        // var disc_type = '<option selected value="' + suggesion.purchasechallan.disc_type + '">' +
        //     disc_text + '</option>';

        // var amty_mode = '<option selected value="' + suggesion.purchasechallan.amty_type + '">' +
        //    amty_text + '</option>';
        var cess_mode = '<option selected value="' + suggesion.purchasechallan.cess_type + '">' +
            suggesion
            .purchasechallan.cess_type + '</option>';
        var vehi = '<option selected value="' + suggesion.purchasechallan.vehicle_modeno + '">' +
            suggesion
            .purchasechallan.vehicle_name + '</option>';
        var trans = '<option selected value="' + suggesion.purchasechallan.transport + '">' +
            (suggesion.purchasechallan.transport_name) ? suggesion.purchasechallan.transport_name : '' +
            '</option>';

        var tran_mode = '<option selected value="' + suggesion.purchasechallan.transport_mode + '">' +
            suggesion.purchasechallan.transport_mode + '</option>';

        var city = '<option selected value="' + suggesion.purchasechallan.city + '">' +
            (suggesion.purchasechallan.city_name) ? suggesion.purchasechallan.city_name : '' +
            '</option>';
        var igst_acc = '<option selected value="' + suggesion.purchasechallan.igst_acc + '">' +
            suggesion
            .purchasechallan.igst_acc_name + '</option>';
        var cgst_acc = '<option selected value="' + suggesion.purchasechallan.cgst_acc + '">' +
            suggesion
            .purchasechallan.cgst_acc_name + '</option>';
        var sgst_acc = '<option selected value="' + suggesion.purchasechallan.sgst_acc + '">' +
            suggesion
            .purchasechallan.sgst_acc_name + '</option>';

        $('.account').append(acc);
        $('#broker').append(brok);
        $('.city').append(city);
        $('.cess_mode').append(cess_mode);
        $('.vehicle').append(vehi);
        $('.transport').append(trans);
        $('.trans_mode').append(tran_mode);
        $('#igst_acc').append(igst_acc);
        $('#cgst_acc').append(cgst_acc);
        $('#sgst_acc').append(sgst_acc);

        $('#tds_limit').val(suggesion.purchasechallan.tds_limit);
        $('#acc_state').val(suggesion.purchasechallan.acc_state);
        $('#tds_per').val(suggesion.purchasechallan.tds_per);
        $('.lr').val(suggesion.purchasechallan.lr_no);
        $('.lr_data').val(suggesion.purchasechallan.lr_date);
        $('.other').val(suggesion.purchasechallan.other);
        $('.supply_inv').val(suggesion.purchasechallan.supply_inv);
        $('.supply_date').val(suggesion.purchasechallan.supply_date);
        $('.gst_no').val(suggesion.purchasechallan.gst_no);
        $('.party_bill').val(suggesion.purchasechallan.sup_chl_no);
        $('.date').val(suggesion.purchasechallan.date);
        $('.igst').val(suggesion.purchasechallan.tot_igst);
        $('.cgst').val(suggesion.purchasechallan.tot_cgst);
        $('.sgst').val(suggesion.purchasechallan.tot_sgst);
        $('.amty').val(suggesion.purchasechallan.amty);
        $('.cess').val(suggesion.purchasechallan.cess);
        $('.tds_per').val(suggesion.purchasechallan.tds_per);
        $('.tds_amt').val(suggesion.purchasechallan.tds_amt);
        $('.discount').val(suggesion.purchasechallan.discount);
        $('.net_amt').val(suggesion.purchasechallan.net_amount);
        $('#brok_name').text(suggesion.purchasechallan.broker_name);
        $('#fix_brokrage').val(suggesion.purchasechallan.fix_brokrage);
        $('input[name="due_days"]').val(suggesion.purchasechallan.default_due_days);
        $('input[name="gl_group"]').val(suggesion.purchasechallan.gl_group);

        //console.log(item);
        for (i = 0; i < item.length; i++) {
            // console.log(item);
            if (item[i]['is_expence'] == 0) {
                var uom = item[i].item_uom.split(',');
                // console.log(item[i].id);
                var uom_option = '';
                for (j = 0; j < uom.length; j++) {
                    var slec = item[i].uom == uom[j] ? 'selected' : '';
                    uom_option += '<option value="' + uom[j] + '" ' + slec + ' >' + uom[j] +
                        '</option>';
                    slec = '';
                }
                pids_item.push(parseInt(item[i].id));
                var inp = '<input type="hidden" name="pid[]" value="' + item[i].id + '">';

                var igst_amt = '<input type="hidden" name="igst_amt[]" value="">';
                var cgst_amt = '<input type="hidden" name="cgst_amt[]" value="">';
                var sgst_amt = '<input type="hidden" name="sgst_amt[]" value="">';
                var expence = '<input type="hidden" name="expence[]" value="' + item[i].is_expence +
                    '">';
                var taxability = '<input name="taxability[]" value="' + item[i].taxability +
                    '" type="hidden">';
                var tds = '<tr class="item_row">';
                tds += '<td><a class="tx-danger btnDelete" data-id="' + item[i].id +
                    '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
                tds += '<td>' + item[i].name + '(' + item[i].hsn + ')' + inp + expence + taxability +
                    '</td>';
                tds += '<td><input class="form-control input-sm" value="' + item[i].hsn +
                    '" name="hsn[]" readonly onchange="calculate()"  type="text"></td>';
                tds += '<td><select name="uom[]">' + uom_option + '</select></td>';
                tds += '<td><input class="form-control input-sm" value="' + item[i].qty +
                    '" name="qty[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';
                tds += '<td><input class="form-control input-sm" value="' + item[i].rate +
                    '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';

                if (item[i].taxability == 'N/A') {
                    tds +=
                        '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"><b class="igst_amt"></b></td>';
                    tds +=
                        '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"><b class="cgst_amt"></b></td>';
                    tds +=
                        '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"><b class="sgst_amt"></b></td>';

                } else {
                    var igst_amt = '<input name="igst_amt[]" value="" type="hidden">';
                    var cgst_amt = '<input name="cgst_amt[]" value="" type="hidden">';
                    var sgst_amt = '<input name="sgst_amt[]" value="" type="hidden">';
                    tds += '<td><input class="form-control input-sm" value="' + item[i]
                        .igst +
                        '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text">' +
                        igst_amt + '<b class="igst_amt"></b></td>';

                    tds += '<td><input class="form-control input-sm" value="' + item[i].cgst +
                        '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text">' +
                        cgst_amt + '<b class="cgst_amt"></b></td>';

                    tds += '<td><input class="form-control input-sm" value="' + item[i].sgst +
                        '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text">' +
                        sgst_amt + '<b class="sgst_amt"></b></td>';
                }

                tds +=
                    '<td><input class="form-control input-sm" name="item_disc[]" onchange="calculate()" value="' +
                    item[i].item_disc +
                    '" type="text" ><b class="itm_disc_amt"></b><input type="hidden" name="item_discount_hidden[]" class="hidden_itm_disc_amt" value="' +
                    item[i].discount + '"><input type="hidden" value="' + item[i].divide_disc +
                    '" name="item_per[]" class="item_per"><input type="hidden" name="divide_disc_amt[]" value="' +
                    item[i].divide_disc_amt +
                    '" class="divide_disc_amt"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt" value="' +
                    item[i].added_amt + '"></td>';

                tds +=
                    '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="' +
                    item[i].sub_total + '"   ="" type="text" readonly></td>';
                tds +=
                    '<td><input class="form-control input-sm" name="remark[]" value="' + item[i]
                    .remark +
                    '" placeholder="Remark" type="text"></td>';
                tds += '</tr>';
            } else {
                pids_exp.push(parseInt(item[i].id));
                var inp = '<input type="hidden" name="pid[]" value="' + item[i].id + '">';

                var igst_amt = '<input type="hidden" name="igst_amt[]" value="">';
                var cgst_amt = '<input type="hidden" name="cgst_amt[]" value="">';
                var sgst_amt = '<input type="hidden" name="sgst_amt[]" value="">';

                var qty = '<input value="" name="qty[]" type="hidden">';
                var item_disc = '<input value="" name="item_disc[]" type="hidden">';
                var uom = '<input value="" name="uom[]" type="hidden">';
                var item_per = '<input value="" name="item_per[]" type="hidden">';
                var divide_disc_amt = '<input value="" name="divide_disc_amt[]" type="hidden">';
                var hsn = '<input value="" name="hsn[]" type="hidden">';
                var expence = '<input type="hidden" name="expence[]" value="' + item[i].is_expence +
                    '">';
                var taxability = '<input type="hidden" name="taxability[]" value="' + item[i]
                    .taxability + '">';
                var tds = '<tr class="item_row">';
                tds += '<td><a class="tx-danger btnDelete" data-id="' + item[i].id +
                    '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
                tds += '<td colspan="4">' + item[i].name + '(' + item[i].code + ')' + inp + expence +
                    hsn + qty + item_disc + uom + taxability + +item_per + divide_disc_amt + '</td>';
                tds += '<td><input class="form-control input-sm" value="' + item[i].rate +
                    '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';

                if (item[i].taxability == 'N/A') {
                    tds +=
                        '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"><b class="igst_amt"></b></td>';
                    tds +=
                        '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"><b class="cgst_amt"></b></td>';
                    tds +=
                        '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"><b class="sgst_amt"></b></td>';

                } else {
                    var igst_amt = '<input name="igst_amt[]" value="" type="hidden">';
                    var cgst_amt = '<input name="cgst_amt[]" value="" type="hidden">';
                    var sgst_amt = '<input name="sgst_amt[]" value="" type="hidden">';
                    tds += '<td><input class="form-control input-sm" value="' + item[i]
                        .igst +
                        '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text">' +
                        igst_amt + '<b class="igst_amt"></b></td>';

                    tds += '<td><input class="form-control input-sm" value="' + item[i].cgst +
                        '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text">' +
                        cgst_amt + '<b class="cgst_amt"></b></td>';

                    tds += '<td><input class="form-control input-sm" value="' + item[i].sgst +
                        '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text">' +
                        sgst_amt + '<b class="sgst_amt"></b></td>';
                }
                tds +=
                    '<td><input type="hidden" name="item_discount_hidden[]" class="hidden_discount" value="' +
                    item[i].discount +
                    '"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt" value="' +
                    item[i].added_amt + '"></td>';

                tds +=
                    '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="' +
                    item[i].item_disc + '"   ="" type="text" readonly></td>';
                tds +=
                    '<td><input class="form-control input-sm" name="remark[]" value="' + item[i]
                    .remark +
                    '" placeholder="Remark" type="text"></td>';
                tds += '</tr>';
            }

            $('.tbody').append(tds);
            $('#code').val('');

            var igst = document.getElementById("igst");
            var sgst = document.getElementById("sgst");
            var cgst = document.getElementById("cgst");
            var tds = document.getElementById("tds");
            var cess = document.getElementById("cess");

            var taxes_str = suggesion.purchasechallan.taxes;
            var taxes_arr = JSON.parse(taxes_str);

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

    $("#other_exp_ac").select2({
        width: 'resolve',
        placeholder: 'Type Transport',
        ajax: {
            url: PATH + "Master/Getdata/search_exp_ac",
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
    $("#voucher_type").select2({
        width: '100%',
        placeholder: 'Voucher Type',
        ajax: {
            url: PATH + "Master/Getdata/search_purchasevouchertype",
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
    $("#ledger_type").select2({
            width: '100%',
            placeholder: 'Ledger Type',
            ajax: {
                url: PATH + "Master/Getdata/search_purchase_ledger_type",
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

function getduedate(days) {
    const date = new Date();
    var d = parseInt(days);
    date.setDate(date.getDate() + d);
    var new_date = formatDate(date);
    $("#due_date").val(new_date);
}

function getduedays(date) {
    //alert("efherj");
    const date1 = new Date();
    var date2 = new Date(date);
    // alert(date2);
    var Difference_In_Time = date2.getDate() - date1.getDate();
    $("#due_days").val(Difference_In_Time);
    //var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
    //console.log(Difference_In_Time);
}
// start add plus button create option append data 
function get_account_data(id, data) {
    $('#gst_no').val(data.gst);
    $('#acc_state').val(data.state);
    $('input[name="gl_group"]').val(data.gl_group);

    $('input[name="due_day"]').val(data.due_day);
    $('input[name="bank_name"]').val(data.trans_bank_name);
    $('input[name="bank_ac"]').val(data.trans_bank_ac);
    $('input[name="bank_ifsc"]').val(data.trans_bank_ifsc);
    $('input[name="bank_holder"]').val(data.trans_bank_holder);

    $("input[name='ship_city']").val(data.city_name);
    $("input[name='ship_state']").val(data.state_name);
    $("input[name='ship_country']").val(data.country_name);
    $("input[name='ship_address']").val(data.gst_add);
    $("input[name='ship_pin']").val(data.ship_pin);

    var html = '<option selected value="' + id + '"> ' + data.name + '</option>';
    $('select[name="delivery_code"]').append(html)
    var com_state = parseInt(<?= session('state') ?>);
    var acc_state = parseInt($('#acc_state').val());
    if (com_state == acc_state) {

        $("#tax option[value='igst']").remove();
        $('#igst_acc').attr('required', false);
        if ($("#tax option[value='sgst']").length == 0) {
            $('#tax').append('<option value="sgst">sgst</option>');
        }
        if ($("#tax option[value='cgst']").length == 0) {
            $('#tax').append('<option value="cgst">cgst</option>');
        }
        $("#tax option[value='sgst']").attr("selected", "selected");
        $("#tax option[value='cgst']").attr("selected", "selected");
        $('#sgst_acc').attr('required', true);
        $('#cgst_acc').attr('required', true);

    } else {
        $('#sgst_acc').attr('required', false);
        $('#cgst_acc').attr('required', false);
        $("#tax option[value='sgst']").remove();
        $("#tax option[value='cgst']").remove();
        if ($("#tax option[value='igst']").length == 0) {
            $('#tax').append('<option value="igst">igst</option>');
        }
        $("#tax option[value='igst']").attr("selected", "selected");
        $('#igst_acc').attr('required', true);
    }

    enable_gst_option();
    calculate();

}

function get_item_data(id, data) {
    // var pids_item = [];
    // var pids_exp = [];

    var suggestion = data;
    //console.log(JSON.stringify(data.uom[0]));
    $('.product_error_new').html('');
    if (pids_item.includes(parseInt(id)) == false) {
        $('.product_error').html('');


        pids_item.push(parseInt(id));
        var inp = '<input type="hidden" name="pid[]" value="' + id + '">';
        var taxability = '<input name="taxability[]" value="' + suggestion.taxability +
            '" type="hidden">';

        var expence = '<input type="hidden" name="expence[]" value="0">';
        var tds = '<tr class="item_row">';
        tds += '<td><a class="tx-danger btnDelete" data-id="' + id +
            '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
        tds += '<td>' + suggestion.name + inp + taxability + expence + '</td>';
        tds += '<td><input class="form-control input-sm" value="' + suggestion
            .hsn +
            '" name="hsn[]" onchange="calculate()"  type="text" readonly></td>';
        tds += '<td><select name="uom[]" onchange="calculate()"> <option value="' + JSON.stringify(data.uom[0]) + '">' +
            suggestion.uom_name + '</option>' +
            '</select></td>';
        tds +=
            '<td><input class="form-control input-sm" value="0" name="qty[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text"></td>';
        tds += '<td><input class="form-control input-sm" value="' + suggestion
            .sales_price +
            '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text"></td>';
        if (suggestion.taxability == 'N/A') {
            tds +=
                '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"><b class="igst_amt"></b></td>';
            tds +=
                '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"><b class="cgst_amt"></b></td>';
            tds +=
                '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"><b class="sgst_amt"></b></td>';

        } else {
            var igst_amt = '<input name="igst_amt[]" value="" type="hidden">';
            var cgst_amt = '<input name="cgst_amt[]" value="" type="hidden">';
            var sgst_amt = '<input name="sgst_amt[]" value="" type="hidden">';
            tds += '<td><input class="form-control input-sm" value="' + suggestion
                .igst +
                '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text">' +
                igst_amt + '<b class="igst_amt"></b></td>';

            tds += '<td><input class="form-control input-sm" value="' + suggestion
                .cgst +
                '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                cgst_amt + '<b class="cgst_amt"></b></td>';


            tds += '<td><input class="form-control input-sm" value="' + suggestion
                .sgst +
                '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                sgst_amt + '<b class="sgst_amt"></b></td>';

        }
        tds +=
            '<td><input class="form-control input-sm" name="item_disc[]" onchange="calculate()" value="0" type="text"><b class="itm_disc_amt"></b><input type="hidden" name="item_discount_hidden[]" class="hidden_itm_disc_amt"><input type="hidden" name="item_per[]" class="item_per"><input type="hidden" name="divide_disc_amt[]" class="divide_disc_amt"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt"></td>';
        tds +=
            '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="0" type="text" readonly></td>';
        tds +=
            '<td><input class="form-control input-sm" name="remark[]" placeholder="Remark" type="text"></td>';
        tds += '</tr>';



        $('.tbody').append(tds);
        $('#code').val('');
        calculate();
    } else {
        $('.product_error').html('Selected Item Already Added');
        $('#code').val('');
    }
}

function get_expence_data(id, data) {
    //console.log(data);
    var suggestion = data;
    $('.product_error').html('');
    if (pids_exp.includes(parseInt(id)) == false) {
        $('.product_error_new').html('');

        pids_exp.push(parseInt(id));

        var inp = '<input type="hidden" name="pid[]" value="' + id + '">';

        var taxability = '<input type="hidden" name="taxability[]" value="' + suggestion
            .taxability + '">';
        var expence = '<input type="hidden" name="expence[]" value="1">';
        var qty = '<input value="0" name="qty[]" type="hidden">';
        var item_disc = '<input value="0" name="item_disc[]" type="hidden">';
        var item_per = '<input value="" name="item_per[]" type="hidden">';
        var divide_disc_amt = '<input value="" name="divide_disc_amt[]" type="hidden">';
        var uom = '<input value="" name="uom[]" type="hidden">';
        var hsn = '<input value="" name="hsn[]" type="hidden">';

        var tds = '<tr class="item_row">';
        tds += '<td><a class="tx-danger btnDelete" data-id="' + id +
            '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
        tds += '<td colspan="4">' + suggestion.name + inp + taxability + expence + qty + item_disc +
            uom + hsn + item_per + divide_disc_amt + '</td>';

        tds +=
            '<td><input class="form-control input-sm" value="" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" required="" type="text"></td>';

        if (suggestion.taxability == 'N/A') {
            tds +=
                '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"><b class="igst_amt"></b></td>';
            tds +=
                '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"><b class="cgst_amt"></b></td>';
            tds +=
                '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"><b class="sgst_amt"></b></td>';

        } else {
            var igst_amt = '<input name="igst_amt[]" value="" type="hidden">';
            var cgst_amt = '<input name="cgst_amt[]" value="" type="hidden">';
            var sgst_amt = '<input name="sgst_amt[]" value="" type="hidden">';
            tds += '<td><input class="form-control input-sm" value="' + suggestion
                .igst +
                '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text">' +
                igst_amt + '<b class="igst_amt"></b></td>';

            tds += '<td><input class="form-control input-sm" value="' + suggestion
                .cgst +
                '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                cgst_amt + '<b class="cgst_amt"></b></td>';


            tds += '<td><input class="form-control input-sm" value="' + suggestion
                .sgst +
                '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                sgst_amt + '<b class="sgst_amt"></b></td>';

        }
        tds +=
            '<td><input type="hidden" name="item_discount_hidden[]" class="hidden_itm_disc_amt"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt"></td>';
        tds +=
            '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="0" required="" type="text" readonly></td>';

        tds +=
            '<td><input class="form-control input-sm" name="remark[]" placeholder="Remark" type="text"></td>';
        tds += '</tr>';

        $('.tbody').append(tds);
        $('#code_new').val('');
        calculate();
    } else {
        $('.product_error_new').html('Selected Expence Already Added');
        $('#code_new').val('');
    }
}
// end add plus button create option append data 
</script>

<?= $this->endSection() ?>