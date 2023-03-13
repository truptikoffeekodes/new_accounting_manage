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
        <h2 class="main-content-title tx-24 mg-b-5">Transaction </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
    <div class="ml-auto pd-r-100">
        <h2 class="mb-1 font-weight-bold"><span>Sale Invoice Sr No :</span>
            <?= isset($salesinvoice['invoice_no']) ? @$salesinvoice['invoice_no'] : $current_id; ?></h2>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <form action="<?= url('Sales/add_salesinvoice') ?>" class="ajax-form-submit-invoice" method="POST" id="Salesinvoiceform">
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label class="form-label">Voucher Type : </label>
                                <select class="form-control" id="voucher_type" name='voucher_type'>
                                    <?php if (@$salesinvoice['voucher_type']) { ?>
                                        <option value="<?= @$salesinvoice['voucher_type'] ?>">
                                            <?= @$salesinvoice['voucher_name'] ?>
                                        </option>
                                    <?php } else { ?>
                                        <option value="51" selected>
                                            Sales Taxable
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-3 form-group">
                                <label class="form-label">Voucher No.: </label>
                                <input class="form-control" type="text" readonly name="invoice_no" value="<?= isset($salesinvoice['invoice_no']) ? @$salesinvoice['invoice_no'] : $current_id ?>">
                            </div>
                            <div class="col-lg-3 form-group">
                                <label class="form-label">Invoice ID.: </label>
                                <input class="form-control" type="text" name="custom_inv_no" value="<?= isset($salesinvoice['custom_inv_no']) ? @$salesinvoice['custom_inv_no'] : '' ?>">
                            </div>

                            <div class="col-lg-6 form-group">
                                <label class="form-label">Invoice Date: </label>
                                <input class="form-control fc-datepicker" placeholder="YYYY-MM-DD" type="text" name="invoice_date" value="<?= @$salesinvoice['invoice_date'] ? $salesinvoice['invoice_date'] : date('Y-m-d') ?>">
                            </div>

                            <div class="col-lg-6 form-group">
                                <label class="form-label">Challan No: </label>
                                <select class="form-control" id="get_challan" name='challan'>
                                    <?php if (@$salesinvoice['challan_no']) { ?>
                                        <option value="<?= @$salesinvoice['challan_no'] ?>">
                                            <?= @$salesinvoice['challan_name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-5 form-group">
                                <div class="row">
                                    <div class="row col-lg-12 form-group">
                                        <label class="form-label col-md-4">Account: <span class="tx-danger">*</span></label>
                                        <div class="input-group col-md-8" style="padding:0px;">
                                            <select class="form-control account" id="account" name='account'>
                                                <?php if (@$salesinvoice['account_name']) { ?>
                                                    <option value="<?= @$salesinvoice['account'] ?>">
                                                        <?= @$salesinvoice['account_name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_account/sundry_debtor') ?>" data-target="#fm_model" data-title="Enter Account"><i style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="<?= @$salesinvoice['id'] ?>">
                                        <input type="hidden" name="tds_per" id="tds_per" class="tds_per" value="<?= @$salesinvoice['tds_per']; ?>">
                                        <input type="hidden" name="tds_limit" id="tds_limit" value="<?= @$salesinvoice['tds_limit']; ?>">
                                        <input type="hidden" name="acc_state" id="acc_state" value="<?= @$salesinvoice['acc_state']; ?>">
                                        <input type="hidden" name="bank_name" value="<?= @$salesinvoice['bank_name']; ?>">
                                        <input type="hidden" name="bank_ac" value="<?= @$salesinvoice['bank_ac']; ?>">
                                        <input type="hidden" name="bank_ifsc" value="<?= @$salesinvoice['bank_ifsc']; ?>">
                                        <input type="hidden" name="bank_holder" value="<?= @$salesinvoice['bank_holder']; ?>">
                                        <input type="hidden" name="gl_group" value="<?= @$salesinvoice['gl_group']; ?>">

                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">GST No.: <span class="tx-danger">*</span></label>
                                        <input readonly class="form-control col-md-8 gst_no" type="text" name="gst" id="gsttin" value="<?= @$salesinvoice['gst']; ?>">
                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Shipped to AC: <span class="tx-danger">*</span></label>
                                        <select class="form-control delivery" id="delivery_code" name='delivery_code'>
                                            <?php if (@$salesinvoice['delivery_name']) { ?>
                                                <option value="<?= @$salesinvoice['delivery_code'] ?>">
                                                    <?= @$salesinvoice['delivery_name'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" name="ship_country" value="<?= @$salesinvoice['ship_country'] ?>">
                                        <input type="hidden" name="ship_state" value="<?= @$salesinvoice['ship_state'] ?>">
                                        <input type="hidden" name="ship_city" value="<?= @$salesinvoice['ship_city'] ?>">
                                        <input type="hidden" name="ship_pin" value="<?= @$salesinvoice['ship_pin'] ?>">
                                        <input type="hidden" name="ship_address" value="<?= @$salesinvoice['ship_address'] ?>">
                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Vehicle No: </label>
                                        <div class="input-group col-md-8" style="padding:0px;">
                                            <select class="form-control vehicle" id="vehicle" name='vehicle'>
                                                <?php if (@$salesinvoice['vehicle_name']) { ?>
                                                    <option value="<?= @$salesinvoice['vhicle_no'] ?>">
                                                        <?= @$salesinvoice['vehicle_name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_vehicle') ?>" data-target="#fm_model" data-title="Enter vhicle"><i style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Due Days: </label>
                                        <input class="form-control col-md-8" name="due_day" id="due_days" value="<?= @$salesinvoice['due_days'] ?>" onkeyup="getduedate(this.value)" placeholder="Enter Due Days" onkeypress="return isNumberKey(event)" type="text">
                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Due Date: </label>
                                        <input class="form-control fc-datepicker col-md-8" placeholder="YYYY-MM-DD" type="text" id="due_date" onchange="getduedays(this.value)" name="due_date" value="<?= @$salesinvoice['due_date'] ?>">
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Add Item: <span class="tx-danger"></span></label>
                                        <div class="row input-group col-md-8" style="padding:0px;">
                                            <select class="form-control" id="code" name='code'> </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_item/general') ?>" data-target="#fm_model" data-title="Enter Item"><i style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
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
                                            <?php if (@$salesinvoice['broker_name']) { ?>
                                                <option value="<?= @$salesinvoice['broker'] ?>">
                                                    <?= @$salesinvoice['broker_name'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <a data-toggle="modal" href="<?= url('Master/add_account/broker') ?>" data-target="#fm_model" data-title="Enter Account"><i style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <input type="hidden" value="<?= @$salesinvoice['broker'] ?>" id="fix_brokrage" name="brokrage">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Brokrage Type: <span class="tx-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3 form-group">

                                        <label class="rdiobox"><input checked name="brokerage_type" <?= @$salesinvoice['brokrage_type'] == "fix" ? 'checked' : ''  ?> value="fix" type="radio" onchange="calculate()">
                                            <span>Fix</span></label>

                                        <label class="rdiobox"><input name="brokerage_type" <?= @$salesinvoice['brokrage_type'] == "item_wise" ? 'checked' : ''  ?> value="item_wise" type="radio" onchange="calculate()"> <span>Item
                                                Wise</span></label>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Narration: </label>
                                    </div>

                                    <div class="col-lg-10 form-group">
                                        <input class="form-control other" name="other" value="<?= @$salesinvoice['other'] ?>" placeholder="Enter Other Detail" type="text">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR No.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control lr" name="lrno" placeholder="Enter Lr No." onkeypress="return isDesimalNumberKey(event)" value="<?= @$salesinvoice['lr_no'] ?>" type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR Date.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control fc-datepicker lr_data" placeholder="YYYY-MM-DD" type="text" id="lr_date" name="lr_date" value="<?= @$salesinvoice['lr_date'] ?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Transport.: </label>
                                    </div>

                                    <div class="col-md-10 form-group">
                                        <div class="input-group">
                                            <select class="form-control transport" id="transport" name='transport'>
                                                <?php if (@$salesinvoice['transport_name']) { ?>
                                                    <option value="<?= @$salesinvoice['transport'] ?>">
                                                        <?= @$salesinvoice['transport_name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-target="#fm_model" data-toggle="modal" data-title="Add Transport" href="<?= url('master/add_transport') ?>"><i style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Transport Mode: </label>
                                    </div>
                                    <div class="col-md-10 form-group">
                                        <div class="input-group">
                                            <select class="select2 trans_mode" id="transport_mode" name="trasport_mode">
                                                <option <?= (@$salesinvoice['transport_mode'] == 'AIR' ? 'selected' : '') ?> value="AIR">AIR</option>
                                                <option <?= (@$salesinvoice['transport_mode'] == 'ROAD' ? 'selected' : '') ?> value="ROAD">ROAD</option>
                                                <option <?= (@$salesinvoice['transport_mode'] == 'RAIL' ? 'selected' : '') ?> value="RAIL">RAIL</option>
                                                <option <?= (@$salesinvoice['transport_mode'] == 'SHIP' ? 'selected' : '') ?> value="SHIP">SHIP</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Particular Name: </label>
                                    </div>
                                    <div class="col-md-10 form-group">
                                        <div class="input-group" style="width:auto;">
                                            <select class="form-control" id="code_new" name='code_new'> </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_account_inc_exp') ?>" data-target="#fm_model" data-title="Enter Account"><i style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="dz-error-message tx-danger product_error_new"></div>
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
                                            <th>#</th>
                                            <th>Item</th>
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
                                        if (isset($item)) {
                                            $total = 0.0;
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
                                                    <td><a class="tx-danger btnDelete" data-id="<?= $row['item_id'] ?>" title="0"><i class="fa fa-times tx-danger"></i></a></td>

                                                    <?php
                                                    if ($row['is_expence'] == 0) {
                                                    ?>
                                                        <td><?= $row['name'] ?>(<?= $row['hsn'] ?>)
                                                            <input type="hidden" name="pid[]" value="<?= $row['item_id'] ?>">
                                                            <input name="taxability[]" value="<?= $row['taxability'] ?>" type="hidden">
                                                            <input name="expence[]" value="<?= $row['is_expence'] ?>" type="hidden">
                                                            
                                                        </td>
                                                        <td><input class="form-control input-sm" value="<?= $row['hsn'] ?>" readonly name="hsn[]" style="width:80px;" onchange="calculate()" type="text"></td>
                                                        <td><select name="uom[]" onchange="calculate()">
                                                                <?php
                                                                foreach ($uom as $uom_row) {

                                                                ?>
                                                                    <option <?= (@$uom_row == $row['uom'] ? 'selected' : '') ?> value="<?= @$uom_row ?>"><?= @$uom_row ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td><input class="form-control input-sm" value="<?= $row['qty'] ?>" name="qty[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">
                                                        </td>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <td colspan="4"><?= $row['name'] ?>(<?= $row['code'] ?>)
                                                            <input type="hidden" name="pid[]" value="<?= $row['item_id'] ?>">
                                                            <input name="taxability[]" value="<?= $row['taxability'] ?>" type="hidden">
                                                            <input name="expence[]" value="<?= $row['is_expence'] ?>" type="hidden">
                                                            <input name="qty[]" value="" type="hidden">
                                                            <input name="uom[]" value="" type="hidden">
                                                            <input name="hsn[]" value="" type="hidden">
                                                           
                                                        </td>
                                                    <?php
                                                    }
                                                    ?>
                                                    <td><input class="form-control input-sm" value="<?= $row['rate'] ?>" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text"></td>

                                                    <?php
                                                    if($row['taxability'] == "N/A")
                                                    {
                                                    ?>
                                                    <td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly>
                                                        <input name="igst_amt[]" value="0" type="hidden">
                                                    </td>

                                                    <td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text" readonly>
                                                        <input name="cgst_amt[]" value="0" type="hidden">
                                                    </td>

                                                    <td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text" readonly>
                                                        <input name="sgst_amt[]" value="0" type="hidden">
                                                    </td>
                                                    <?php
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                     <td><input class="form-control input-sm" value="<?= $row['igst'] ?>" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text">
                                                        <input name="igst_amt[]" value="<?= $row['igst_amt'] ?>" type="hidden">
                                                    </td>

                                                    <td><input class="form-control input-sm" value="<?= $row['cgst'] ?>" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">
                                                        <input name="cgst_amt[]" value="<?= $row['cgst_amt'] ?>" type="hidden">
                                                    </td>

                                                    <td><input class="form-control input-sm" value="<?= $row['sgst'] ?>" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">
                                                        <input name="sgst_amt[]" value="<?= $row['sgst_amt'] ?>" type="hidden">
                                                    </td>
                                                    <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['is_expence'] == 0) {
                                                    ?>
                                                        <td><input class="form-control input-sm" value="<?= $row['item_disc'] ?>" name="item_disc[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text"><b class="itm_disc_amt"></b> <input type="hidden" name="item_discount_hidden[]" class="hidden_discount" value="<?= @$row['discount'] ?>">
                                                            <input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt" value="<?= $row['added_amt'] ?>">
                                                        </td>
                                                        </td>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <td><input class="form-control input-sm" value="0" name="item_disc[]" type="hidden">
                                                            <input type="hidden" name="item_discount_hidden[]" class="hidden_discount" value="<?= @$row['discount'] ?>">
                                                            <input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt" value="<?= $row['added_amt'] ?>">
                                                        </td>
                                                    <?php
                                                    }
                                                    ?>
                                                    <td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="<?= $sub_total ?>" type="text" readonly=""></td>
                                                    <td><input class="form-control input-sm" name="remark[]" value="<?= $row['remark'] ?>" placeholder="Remark" type="text">

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
                                                                <?php if (@$salesinvoice['broker_ledger']) { ?>
                                                                <option value="<?= @$salesinvoice['broker_ledger'] ?>">
                                                                    <?= @$salesinvoice['broker_ledger_name'] ?>
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
                                        </table> -->
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12 form-group">
                                        <label class="custom-switch">
                                            <input type="checkbox" name="stat_adj" onchange="check_stat()" class="custom-switch-input" <?= (@$salesinvoice['stat_adj'] == "1" ? 'checked' : '') ?> value="<?= @$salesinvoice['stat_adj'] ?>">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Stat Adjustment</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row stat_div" style="display:<?= (@$salesinvoice['stat_adj'] == 1) ? 'flex;' : 'none;' ?>">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Type of Reffrence: <span class="tx-danger"></span></label>
                                        <div class="input-group">
                                            <select class="form-control select2" id="ref_type" name="ref_type">

                                                <option <?= (@$salesinvoice['ref_type'] == "Agst Ref" ? 'selected' : '') ?> value="Agst Ref">Agst Ref</option>
                                                <option <?= (@$salesinvoice['ref_type'] == "Advance" ? 'selected' : '') ?> value="Advance">Advance</option>
                                                <option <?= (@$salesinvoice['ref_type'] == "New Ref" ? 'selected' : '') ?> value="New Ref">New Ref</option>
                                                <option <?= (@$salesinvoice['ref_type'] == "On Account" ? 'selected' : '') ?> value="On Account">On Account</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group voucher_list" style="display:<?= !empty(@$salesinvoice['voucher']) ? 'block;' : 'none;' ?>">
                                        <label class="form-label">Select Voucher: <span class="tx-danger"></span></label>
                                        <div class="input-group">
                                            <select class="form-control" id="voucher" name="voucher">
                                                <?php if (@$salesinvoice['voucher']) { ?>
                                                    <option value="<?= @$salesinvoice['voucher'] ?>">
                                                        <?= @$salesinvoice['voucher'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Amount: <span class="tx-danger"></span></label>
                                        <div class="input-group">
                                            <input type="text" name="voucher_amt" class="form-control" placeholder="Type Amount" onkeypress="return isDesimalNumberKey(event)" value="<?= @$salesinvoice['voucher_amt'] ?>">
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
                                                            <input class="form-control discount" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="discount" type="text" value="<?= @$salesinvoice['discount']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 disc_type" name="disc_type" onchange="calculate()">
                                                                    <option <?= (@$salesinvoice['disc_type'] == 'Fixed' ? 'selected' : '') ?> value="Fixed">Fixed Amount</option>
                                                                    <option <?= (@$salesinvoice['disc_type'] == '%' ? 'selected' : '') ?> value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="discount_amount wd-90"></th>
                                                </tr>

                                                <tr>
                                                    <th>(+)Add Amount</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control amty" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="amty" type="text" value="<?= @$salesinvoice['amty']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 amty_type" name="amty_type" onchange="calculate()">
                                                                    <option <?= (@$salesinvoice['amty_type'] == 'Fixed' ? 'selected' : '') ?> value="Fixed">Fixed Amount</option>
                                                                    <option <?= (@$salesinvoice['amty_type'] == '%' ? 'selected' : '') ?> value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="amty_amount wd-90"></th>
                                                </tr>

                                                <tr>
                                                    <td>Taxable Amount</td>
                                                    <td colspan="2"><input name="taxable" value="<?= @$salesinvoice['taxable'] ?>" class="form-control input-sm" type="text" readonly></td>
                                                </tr>

                                                <?php
                                                $taxes = json_decode(@$salesinvoice['taxes']);
                                                // print_r($tax);
                                                // echo in_array($tax[0]['name'], $taxes);exit;

                                                ?>

                                                <tr>
                                                    <th>Select Tax</th>
                                                    <th colspan="2" class="wd-300">
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="tax" name="taxes[]" onchange="calculate()" multiple>
                                                                <?php foreach ($tax as $row) {
                                                                   if($row['name'] == 'igst') {

                                                                    if(session('state') == @$challan['acc_state'])
                                                                    {
                                                                    }
                                                                    else
                                                                    {
                                                                ?>
                                                                        <option value="<?= $row['name'] ?>" <?php if (!empty($taxes)) {
                                                                                                                echo (in_array($row['name'], $taxes)) ? 'selected' : '';
                                                                                                            } ?>>
                                                                            <?= $row['name']; ?></option>

                                                                    <?php }} else if ($row['name'] == 'cgst'  && session('state') == @$salesinvoice['acc_state']) { ?>

                                                                        <option value="<?= $row['name'] ?>" <?php if (!empty($taxes)) {
                                                                                                                echo (in_array($row['name'], $taxes)) ? 'selected' : '';
                                                                                                            } ?>>
                                                                            <?= $row['name']; ?></option>

                                                                    <?php } else if ($row['name'] == 'sgst'  && session('state') == @$salesinvoice['acc_state']) { ?>

                                                                        <option value="<?= $row['name'] ?>" <?php if (!empty($taxes)) {
                                                                                                                echo (in_array($row['name'], $taxes)) ? 'selected' : '';
                                                                                                            } ?>>
                                                                            <?= $row['name']; ?></option>

                                                                    <?php } else if ($row['name'] == 'tds' || $row['name'] == 'cess') { ?>

                                                                        <option value="<?= $row['name'] ?>" <?php if (!empty($taxes)) {
                                                                                                                echo (in_array($row['name'], $taxes)) ? 'selected' : '';
                                                                                                            } ?>>
                                                                            <?= $row['name']; ?></option>

                                                                        <?php } else {
                                                                        if (!@$salesinvoice) { ?>
                                                                            <option value="<?= $row['name'] ?>" <?php if (!empty($taxes)) {
                                                                                                                    echo (in_array($row['name'], $taxes)) ? 'selected' : '';
                                                                                                                } ?>>
                                                                                <?= $row['name']; ?></option>
                                                                <?php }
                                                                    }
                                                                } ?>

                                                            </select>
                                                        </div>
                                                    </th>
                                                </tr>

                                                <tr id="igst" style="display:<?php if (!empty($taxes)) {
                                                                                    echo (in_array("igst", $taxes))  ? 'table-row;' : 'none;';
                                                                                } else {
                                                                                    echo 'none;';
                                                                                } ?> ">
                                                    <th>
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="igst_acc" name="igst_acc">
                                                                <?php if (@$salesinvoice['igst_acc']) { ?>
                                                                    <option value="<?= @$salesinvoice['igst_acc'] ?>">
                                                                        <?= @$salesinvoice['igst_acc_name'] ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </th>

                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="tot_igst" type="text" value="<?= @$salesinvoice['tot_igst']; ?>">
                                                        </div>
                                                    </th>
                                                    <th class="igst_amount wd-90"></th>
                                                </tr>

                                                <tr id="sgst" style="display:<?php if (!empty($taxes)) {
                                                                                    echo (in_array("sgst", $taxes)) ? 'table-row;' : 'none;';
                                                                                } else {
                                                                                    echo 'none;';
                                                                                } ?> ">
                                                    <th>
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="sgst_acc" name="sgst_acc">
                                                                <?php if (@$salesinvoice['sgst_acc']) { ?>
                                                                    <option value="<?= @$salesinvoice['sgst_acc'] ?>">
                                                                        <?= @$salesinvoice['sgst_acc_name'] ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </th>

                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="tot_sgst" type="text" value="<?= @$salesinvoice['tot_sgst']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="sgst_amount wd-90"></th>
                                                </tr>

                                                <tr id="cgst" style="display:<?php if (!empty($taxes)) {
                                                                                    echo (in_array("cgst", $taxes)) ? 'table-row;' : 'none;';
                                                                                } else {
                                                                                    echo 'none;';
                                                                                } ?> ">
                                                    <th>
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="cgst_acc" name="cgst_acc">
                                                                <?php if (@$salesinvoice['cgst_acc']) { ?>
                                                                    <option value="<?= @$salesinvoice['cgst_acc'] ?>">
                                                                        <?= @$salesinvoice['cgst_acc_name'] ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </th>

                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="tot_cgst" type="text" value="<?= @$salesinvoice['tot_cgst']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="cgst_amount wd-90"></th>
                                                </tr>

                                                <tr id="tds" style="display:<?php if (!empty($taxes)) {
                                                                                echo (in_array("tds", $taxes)) ? 'table-row;' : 'none;';
                                                                            } else {
                                                                                echo 'none;';
                                                                            } ?> ">
                                                    <th>(+)TDS</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control tds_amt" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="tds_amt" type="text" value="<?= @$salesinvoice['tds_amt']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="tds_amount wd-90"></th>
                                                </tr>

                                                <tr id="cess" style="display:<?php if (!empty($taxes)) {
                                                                                    echo (in_array("cess", $taxes)) ? 'table-row;' : 'none;';
                                                                                } else {
                                                                                    echo 'none;';
                                                                                } ?>">
                                                    <th>(+)Cess</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control cess" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="cess" type="text" value="<?= @$salesinvoice['cess']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 cess_mode" name="cess_type" onchange="calculate()">
                                                                    <option <?= (@$salesinvoice['cess_type'] == 'Fixed' ? 'selected' : '') ?> value="Fixed">Fixed Amount</option>
                                                                    <option <?= (@$salesinvoice['cess_type'] == '%' ? 'selected' : '') ?> value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="cess_amount wd-90"></th>
                                                </tr>

                                                <tr>
                                                    <th>
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="round" name="round">
                                                                <?php if (@$salesinvoice['round']) { ?>
                                                                    <option value="<?= @$salesinvoice['round'] ?>">
                                                                        <?= @$salesinvoice['round_name'] ?>
                                                                    </option>
                                                                <?php } else { ?>
                                                                    <option value="6" selected>
                                                                        Round Off (Default)
                                                                    </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </th>
                                                    <th><input class="form-control input-sm" onchange="calculate()" value="<?= @$salesinvoice['round_diff'] ?>" name="round_diff" type="text"></th>
                                                    <td class="wd-90 cr_dr_round"></td>


                                                </tr>


                                                <tr>
                                                    <td>Net Amount</td>
                                                    <td colspan="2"><input class="form-control input-sm net_amt" name="net_amount" type="text" value="<?= @$salesinvoice['net_amount']; ?>" readonly></td>
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
                            <input class="btn btn-space btn-primary btn-product-submit" id="save_data_invoice" type="submit" value="Submit">
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

    function check_stat() {
        if ($('input[name="stat_adj"]').is(':checked')) {
            $('input[name="stat_adj"]').val('1');
            $('.stat_div').css('display', 'flex');
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

    function calc_gst_per(obj) {
        var igst = $(obj).val();
        if (igst == '' || igst == 'undefined' || isNaN(igst)) {
            igst = 0;
        }

        $(obj).closest('.item_row').find('input[name="cgst[]"]').val(parseFloat(igst) / 2);
        $(obj).closest('.item_row').find('input[name="sgst[]"]').val(parseFloat(igst) / 2);
    }

    function due_date_calc(obj) {

        var days = $(obj).val();

        var date = ($("input[name='invoice_date']").val());
        days = parseInt(days, 10);
        //console.log(date);

        if (!isNaN(date.getTime())) {
            date.setDate(date.getDate() + days);

            $("input[name='due_date']").val(date.toInputFormat());
        } else {

            $("input[name='due_date']").val('');
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

                var disc_amt = sub * item_disc[i] / 100;
                var final_sub = sub - disc_amt;

                igst_amt += final_sub * igst[i] / 100;

                var item_igst_amt = final_sub * igst[i] / 100;

                $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);

                $('input[name="subtotal[]"]').eq(i).val(final_sub.toFixed(2));
                uom_name = $('select[name="uom[]"] :selected').eq(i).text();

                $('input[name="subtotal[]"]').eq(i).closest('.item_row').find('.uom_name').html('/ ' + uom_name);
                $('input[name="subtotal[]"]').eq(i).closest('.item_row').find('.itm_disc_amt').html(parseFloat(disc_amt
                    .toFixed(2)));
                    $('input[name="subtotal[]"]').eq(i).closest('.item_row').find(".hidden_discount").val(disc_amt.toFixed(2));
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

                $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);


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

        if (discount_type == '%') {
            var disc = 0;
            var item_total =0;
            for (var i = 0; i < pid.length; i++) {
                if (expence[i] == 0) {
                    disc++;
                    var item_price = price[i] * qty[i];
                    item_total += item_price;
                }
            }
            discount_amount = (item_total * (discount / 100));
            $('.discount_amount').html('- ' + parseFloat(discount_amount).toFixed(2));
            if (discount_amount > 0) {
                var total = 0;
                var divide_disc = discount_amount / disc;
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

                        var sub = qty[i] * price[i];
                        var disc_amt = sub * item_disc[i] / 100;
                        // append discount amount here ......
                        var indexx = $(".item_row").find(".hidden_discount").val(divide_disc.toFixed(2));

                        var final_sub = sub - disc_amt;

                        var abc = final_sub - divide_disc;
                        igst_amt += abc * igst[i] / 100;

                        item_igst_amt = abc * igst[i] / 100;

                        $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                        $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                        $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);
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
                        //  var disc_amt = sub;

                        var final_sub = sub;

                        var abc = final_sub - divide_disc;
                        igst_amt += abc * igst[i] / 100;
                        item_igst_amt = final_sub * igst[i] / 100;

                        $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                        $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                        $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    }

                    total += abc;
                }
            }
        } else {
            $('.discount_amount').html('- ' + parseFloat(discount).toFixed(2));
            if (discount > 0) {
                var total = 0;
                var disc = 0;
                for (var i = 0; i < pid.length; i++) {
                    if (expence[i] == 0) {
                        disc++;
                    }
                }
                var divide_disc = discount/ disc;
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

                        var sub = qty[i] * price[i];
                        var disc_amt = sub * item_disc[i] / 100;
                        // append discount amount here ......
                        var indexx = $(".item_row").find(".hidden_discount").val(divide_disc.toFixed(2));

                        var final_sub = sub - disc_amt;

                        var abc = final_sub - divide_disc;
                        igst_amt += abc * igst[i] / 100;
                        item_igst_amt = abc * igst[i] / 100;

                        $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                        $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                        $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);
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

                        var abc = final_sub - divide_disc;
                        igst_amt += abc * igst[i] / 100;

                        item_igst_amt = final_sub * igst[i] / 100;

                        $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                        $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                        $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    }
                    total += abc;
                }
            }
        }

        var grand_total = total;


        if (amty_type == '%') {
            amty_amount = (total * (amty / 100));
            var divide_amt = amty_amount / pid.length;

            $('.amty_amount').html('+ ' + parseFloat(amty_amount).toFixed(2));
            grand_total += (total * (amty / 100));
        } else {
            $('.amty_amount').html('+ ' + parseFloat(amty).toFixed(2));
            grand_total += amty;

            var divide_amt = amty / pid.length;

        }


        // console.log("after calc " + grand_total);


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
        //console.log(round_amt);
        if(isNaN(round_diff)){
            round_diff = 0;
        }
        var cr_dr = '';

        if (round_diff < 0) {
            cr_dr = 'DR';
        } else {
            cr_dr = 'CR';
        }
        var final_amt = grand_total + round_diff;
        //console.log(final_amt);

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

        $('#ref_type').on('select2:select', function(e) {
            var ref_type = $('#ref_type').val();
            if (ref_type == 'Advance') {
                var acc = $('#account').val();
                if (acc == '' || acc == 'undefined' || acc == 'NaN') {
                    $('.error-msg').html('Please Select Account');
                } else {
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
                                account: acc
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

            } else {
                $('.voucher_list').css('display', 'none');
            }

        });

        var pids = $('input[name="pid[]"]').map(function() {
            return parseInt(this.value); // $(this).val()
        }).get();
        var expence = $('input[name="expence[]"]').map(function() {
            return parseInt(this.value); // $(this).val()
        }).get();

        var pids_item = [];
        var pids_exp = [];
        for (var pt = 0; pt < pids.length; pt++) {
            //console.log(expence[0]);
            if (expence[pt] == 0) {
                pids_item.push(pids[pt]);
            } else {
                pids_exp.push(pids[pt]);
            }
        }

        $("#product").on('click', '.btnDelete', function() {

            const index = pids.indexOf($(this).data('id'));
            if (index !== -1) {
                delete pids[index];
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

            if (pids_item.includes(parseInt(suggestion.id)) == false) {
                $('.product_error').html('');


                pids_item.push(parseInt(suggestion.id));

                var inp = '<input type="hidden" name="pid[]" value="' + suggestion.id + '">';
                var taxability = '<input type="hidden" name="taxability[]" value="' + suggestion.price
                    .taxability + '">';
                var expence = '<input type="hidden" name="expence[]" value="0">';

                var tds = '<tr class="item_row">';
                tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                    '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
                tds += '<td>' + suggestion.text + inp + taxability + expence + '</td>';
                tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                    .hsn +
                    '" name="hsn[]" readonly onchange="calculate()" style="width:80px;"  type="text"></td>';
                tds += '<td><select name="uom[]" onchange="calculate()">' + suggestion.uom +
                    '</select></td>';
                tds += ''
                tds +=
                    '<td><input class="form-control input-sm" value="0" name="qty[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text"></td>';
                tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                    .sales_price +
                    '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text"></td>';

                if(suggestion.price.taxability == 'N/A')
                {
                    tds += '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"></td>';
                    tds += '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"></td>';
                    tds += '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"></td>';

                }
                else
                {
                    var igst_amt = '<input name="igst_amt[]" value="" type="hidden">';
                    var cgst_amt = '<input name="cgst_amt[]" value="" type="hidden">';
                    var sgst_amt = '<input name="sgst_amt[]" value="" type="hidden">';
                    tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                        .igst +
                        '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text">' +
                        igst_amt + '</td>';

                    tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                        .cgst +
                        '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                        cgst_amt + '</td>';


                    tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                        .sgst +
                        '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                        sgst_amt + '</td>';

                }

                tds +=
                    '<td><input class="form-control input-sm" name="item_disc[]" onchange="calculate()" value="0" type="text" ><b class="itm_disc_amt"></b><input type="hidden" name="item_discount_hidden[]" class="hidden_discount"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt"></td>';

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
            $('.product_error').html('');
            //console.log(itemid.toString().indexOf(suggestion.id));
            if (pids_exp.includes(parseInt(suggestion.id)) == false) {
                $('.product_error_new').html('');


                pids_exp.push(parseInt(suggestion.id));

                var inp = '<input type="hidden" name="pid[]" value="' + suggestion.id + '">';

                var taxability = '<input type="hidden" name="taxability[]" value="' + suggestion.paticular
                    .taxability + '">';
                var igst_amt = '<input type="hidden" name="igst_amt[]" value="">';
                var cgst_amt = '<input type="hidden" name="cgst_amt[]" value="">';
                var sgst_amt = '<input type="hidden" name="sgst_amt[]" value="">';
                var expence = '<input type="hidden" name="expence[]" value="1">';
                var qty = '<input value="" name="qty[]" type="hidden">';
                var item_disc = '<input value="" name="item_disc[]" type="hidden">';
                var uom = '<input value="" name="uom[]" type="hidden">';
                var hsn = '<input value="" name="hsn[]" type="hidden">';
                
                var tds = '<tr class="item_row">';
                tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                    '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
                tds += '<td colspan="4">' + suggestion.text + inp + taxability + expence + qty + item_disc +
                    uom + hsn + '</td>';

                tds +=
                    '<td><input class="form-control input-sm" value="0" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" required="" type="text"></td>';

                    if(suggestion.paticular.taxability == 'N/A')
                {
                    tds += '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"></td>';
                    tds += '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"></td>';
                    tds += '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"></td>';

                }
                else
                {
                    var igst_amt = '<input name="igst_amt[]" value="" type="hidden">';
                    var cgst_amt = '<input name="cgst_amt[]" value="" type="hidden">';
                    var sgst_amt = '<input name="sgst_amt[]" value="" type="hidden">';
                    tds += '<td><input class="form-control input-sm" value="' + suggestion.paticular
                        .igst +
                        '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text">' +
                        igst_amt + '</td>';

                    tds += '<td><input class="form-control input-sm" value="' + suggestion.paticular
                        .cgst +
                        '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                        cgst_amt + '</td>';


                    tds += '<td><input class="form-control input-sm" value="' + suggestion.paticular
                        .sgst +
                        '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                        sgst_amt + '</td>';

                }
                tds += '<td><input type="hidden" name="item_discount_hidden[]" class="hidden_discount"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt"></td>';
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
            // console.log(form.serialize());
            $.ajax({
                type: "POST",
                url: aurl,
                cache: false,
                contentType: false,
                processData: false,
                data: formdata ? formdata : form.serialize(),
                success: function(response) {
                    if (response.st == 'success') {

                        window.location = "<?= url('sales/salesinvoice') ?>"
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
            width: '66.5%',
            placeholder: 'Type Account',
            ajax: {
                url: PATH + "Master/Getdata/search_sun_debtor",
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

        $('#account').on('select2:select', function(e) {

            var data = e.params.data;

            $('#gsttin').val(data.gsttin);
            $('#tds_per').val(data.tds);
            $('#tds_limit').val(data.tds_limit);
            $('#acc_state').val(data.state);
            $('input[name="gl_group"]').val(data.data.gl_group);

            $('input[name="due_day"]').val(data.due_day);
            $('input[name="bank_name"]').val(data.data.trans_bank_name);
            $('input[name="bank_ac"]').val(data.data.trans_bank_ac);
            $('input[name="bank_ifsc"]').val(data.data.trans_bank_ifsc);
            $('input[name="bank_holder"]').val(data.data.trans_bank_holder);

            $("input[name='ship_city']").val(data.data.city_name);
            $("input[name='ship_state']").val(data.data.state_name);
            $("input[name='ship_country']").val(data.data.country_name);
            $("input[name='ship_address']").val(data.data.gst_add);
            $("input[name='ship_pin']").val(data.data.ship_pin);

            var html = '<option selected value="' + data.data.id + '"> ' + data.data.name + '</option>'

            $('select[name="delivery_code"]').append(html);

            var com_state = parseInt(<?= session('state') ?>);
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


        $("#delivery_code").select2({
            width: '66.5%',
            placeholder: 'Type Shiped to AC',
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

        $('#delivery_code').on('select2:select', function(e) {
            var data = e.params.data.data;

            $("input[name='ship_city']").val(data.city_name);
            $("input[name='ship_state']").val(data.state_name);
            $("input[name='ship_country']").val(data.country_name);
            $("input[name='ship_address']").val(data.gst_add);
            $("input[name='ship_pin']").val(data.ship_pin);
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


        $("#voucher_type").select2({
            width: '100%',
            placeholder: 'Voucher Type',
            ajax: {
                url: PATH + "Master/Getdata/search_salevouchertype",
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
            width: 'resolve',
            placeholder: {
                id: '', // the value of the option
                text: 'None Selected'
            },
            allowClear: true,
            ajax: {
                url: PATH + "Sales/Getdata/get_challan",
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
            //console.log(suggesion)
            var item = suggesion.item;
            //console.log(item);

            if (suggesion.challan.amty_type == '%') {
                $(".amty_type").val("%").change();
            } else {
                $(".amty_type").val("Fixed").change();
            }

            if (suggesion.challan.disc_type == '%') {
                $(".disc_type").val("%").change();

            } else {
                $(".disc_type").val("Fixed").change();
            }

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
            // var disc_type = '<option selected value="' + suggesion.challan.disc_type + '">' + suggesion
            //     .challan.disc_type + '</option>';
            var amtx_mode = '<option selected value="' + suggesion.challan.amtx_type + '">' + suggesion
                .challan.amtx_type + '</option>';
            // var amty_mode = '<option selected value="' + suggesion.challan.amty_type + '">' + suggesion
            //     .challan.amty_type + '</option>';
            var cess_mode = '<option selected value="' + suggesion.challan.cess_type + '">' + suggesion
                .challan.cess_type + '</option>';
            var igst_acc = '<option selected value="' + suggesion.challan.igst_acc + '">' + suggesion
                .challan.igst_acc_name + '</option>';
            var cgst_acc = '<option selected value="' + suggesion.challan.cgst_acc + '">' + suggesion
                .challan.cgst_acc_name + '</option>';
            var sgst_acc = '<option selected value="' + suggesion.challan.sgst_acc + '">' + suggesion
                .challan.sgst_acc_name + '</option>';

            // var tax_option = [
            //     {
            //         id:1,
            //         text:"test"
            //     }
            // ]
                //console.log(brok);
            $('.account').append(acc);
            $('.delivery').append(deli);
            $('.broker').append(brok);
            $('.class').append(clas);
            $('.vehicle').append(vehi);
            $('.transport').append(trans);
            $('.trans_mode').append(tran_mode);
            // $('.disc_type').append(disc_type);
            // $('.amty_mode').append(amty_mode);
            $('.cess_mode').append(cess_mode);
            $('#igst_acc').append(igst_acc);
            $('#cgst_acc').append(cgst_acc);
            $('#sgst_acc').append(sgst_acc);



            $('input[name="due_day"]').val(suggesion.challan.default_due_days);
            $('input[name="ship_country"]').val(suggesion.challan.ship_country);
            $('input[name="ship_state"]').val(suggesion.challan.ship_state);
            $('input[name="ship_city"]').val(suggesion.challan.ship_city);
            $('input[name="ship_pin"]').val(suggesion.challan.ship_pin);
            $('input[name="ship_pship_addressin"]').val(suggesion.challan.ship_address);


            $('input[name="bank_name"]').val(suggesion.challan.bank_name);
            $('input[name="bank_ac"]').val(suggesion.challan.bank_ac);
            $('input[name="bank_ifsc"]').val(suggesion.challan.bank_ifsc);
            $('input[name="bank_holder"]').val(suggesion.challan.bank_holder);

            $('#tds_limit').val(suggesion.challan.tds_limit);
            $('#acc_state').val(suggesion.challan.acc_state);
            $('.gst_no').val(suggesion.challan.gst);
            $('.other').val(suggesion.challan.other);
            $('.lr').val(suggesion.challan.lr_no);
            $('.lr_data').val(suggesion.challan.lr_date);
            $('.igst').val(suggesion.challan.tot_igst);
            $('.cgst').val(suggesion.challan.tot_cgst);
            $('.sgst').val(suggesion.challan.tot_sgst);
            $('.amty').val(suggesion.challan.amty);
            $('.cess').val(suggesion.challan.cess);
            $('.tds_per').val(suggesion.challan.tds_per);
            $('.tds_amt').val(suggesion.challan.tds_amt);
            $('.discount').val(suggesion.challan.discount);
            $('.net_amt').val(suggesion.challan.net_amount);
            $('#brok_name').text(suggesion.challan.broker_name);
            $('#fix_brokrage').val(suggesion.challan.fix_brokrage);
            // $('#igst_acc').val(suggesion.challan.igst_acc);
            // $('#cgst_acc').val(suggesion.challan.cgst_acc);
            // $('#sgst_acc').val(suggesion.challan.sgst_acc);
            // $('#igst_acc').val(suggesion.challan.igst_acc);
            // $('#cgst_acc').val(suggesion.challan.cgst_acc);
            // $('#sgst_acc').val(suggesion.challan.sgst_acc);
            $('input[name="gl_group"]').val(suggesion.challan.gl_group);
            //console.log(item);

            for (i = 0; i < item.length; i++) {
                //console.log(item);
                if (item[i]['is_expence'] == 0) {
                    pids_item.push(item[i].id);
                    var uom = item[i].item_uom.split(',');
                    // console.log(item[i].id);
                    var uom_option = '';
                    for (j = 0; j < uom.length; j++) {
                        var slec = item[i].uom == uom[j] ? 'selected' : '';
                        uom_option += '<option value="' + uom[j] + '" ' + slec + ' >' + uom[j] +
                            '</option>';
                        slec = '';
                    }
                    // if (item[i].is_expence == 0) {
                    //     pids_item.push(item[i].id);
                    // } else {
                    //     pids_exp.push(item[i].id);
                    // }
                    //pids.push(parseInt(item[i].id));
                    var inp = '<input type="hidden" name="pid[]" value="' + item[i].id + '">';
                    var taxability = '<input type="hidden" name="taxability[]" value="' + item[i]
                        .taxability +
                        '">';
                    
                    var expence = '<input type="hidden" name="expence[]" value="' + item[i].is_expence +
                        '">';
                    var tds = '<tr class="item_row">';
                    tds += '<td><a class="tx-danger btnDelete" data-id="' + item[i].id +
                        '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
                    tds += '<td>' + item[i].name + '(' + item[i].hsn + ')' + inp + taxability + expence +
                        '</td>';
                    tds += '<td><input class="form-control input-sm" value="' + item[i].hsn +
                        '" name="hsn[]" onchange="calculate()" readonly style="width:80px;" type="text"></td>'; 
                    tds += '<td><select name="uom[]">' + uom_option + '</select></td>';
                    tds += '<td><input class="form-control input-sm" value="' + item[i].qty +
                        '" name="qty[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';
                    tds += '<td><input class="form-control input-sm" value="' + item[i].rate +
                        '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';
                    if(item[i].taxability == 'N/A')
                    {
                        tds += '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"></td>';
                        tds += '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"></td>';
                        tds += '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"></td>';

                    }
                    else
                    {
                        var igst_amt = '<input name="igst_amt[]" value="" type="hidden">';
                        var cgst_amt = '<input name="cgst_amt[]" value="" type="hidden">';
                        var sgst_amt = '<input name="sgst_amt[]" value="" type="hidden">';
                        tds += '<td><input class="form-control input-sm" value="' + item[i]
                            .igst +
                            '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text">' +
                            igst_amt + '</td>';

                        tds += '<td><input class="form-control input-sm" value="' + item[i].cgst +
                            '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text">' +
                            cgst_amt + '</td>';

                        tds += '<td><input class="form-control input-sm" value="' + item[i].sgst +
                            '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text">' +
                            sgst_amt + '</td>';
                    }
                    tds +=
                        '<td><input class="form-control input-sm" name="item_disc[]" onchange="calculate()" value="' +
                        item[i].item_disc + '" type="text" ><b class="itm_disc_amt"></b><input type="hidden" name="item_discount_hidden[]" class="hidden_discount" value="' + item[i].discount + '"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt" value="' + item[i].added_amt + '"></td>';

                    tds +=
                        '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="' +
                        item[i].sub_total + '"   ="" type="text" readonly></td>';
                    tds +=
                        '<td><input class="form-control input-sm" name="remark[]" value="' + item[i]
                        .remark +
                        '" placeholder="Remark" type="text"></td>';
                    tds += '</tr>';
                } else {
                    pids_exp.push(item[i].id);
                    // if (item[i].is_expence == 0) {
                    //     pids_item.push(item[i].id);
                    // } else {
                    //     pids_exp.push(item[i].id);
                    // }
                    var inp = '<input type="hidden" name="pid[]" value="' + item[i].id + '">';
                    var taxability = '<input type="hidden" name="taxability[]" value="' + item[i]
                        .taxability +
                        '">';
                 
                    var expence = '<input type="hidden" name="expence[]" value="' + item[i].is_expence +
                        '">';
                    var qty = '<input value="1" name="qty[]" type="hidden">';
                    var item_disc = '<input value="0" name="item_disc[]" type="hidden">';
                    var uom = '<input value="" name="uom[]" type="hidden">';
                    var hsn = '<input value="" name="hsn[]" type="hidden">';
                    var tds = '<tr class="item_row">';
                    tds += '<td><a class="tx-danger btnDelete" data-id="' + item[i].id +
                        '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
                    tds += '<td colspan="4">' + item[i].name + '(' + item[i].code + ')' + inp + taxability +
                        expence + qty + item_disc + uom + hsn +
                        '</td>';
                    tds += '<td><input class="form-control input-sm" value="' + item[i].rate +
                        '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text"></td>';

                    if(item[i].taxability == 'N/A')
                    {
                        tds += '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"></td>';
                        tds += '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"></td>';
                        tds += '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"></td>';

                    }
                    else
                    {
                        var igst_amt = '<input name="igst_amt[]" value="" type="hidden">';
                        var cgst_amt = '<input name="cgst_amt[]" value="" type="hidden">';
                        var sgst_amt = '<input name="sgst_amt[]" value="" type="hidden">';
                        tds += '<td><input class="form-control input-sm" value="' + item[i]
                            .igst +
                            '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text">' +
                            igst_amt + '</td>';

                        tds += '<td><input class="form-control input-sm" value="' + item[i].cgst +
                            '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text">' +
                            cgst_amt + '</td>';

                        tds += '<td><input class="form-control input-sm" value="' + item[i].sgst +
                            '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0"   ="" type="text">' +
                            sgst_amt + '</td>';
                    }

                    tds +=
                        '<td><input type="hidden" name="item_discount_hidden[]" class="hidden_discount" value="' + item[i].discount + '"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt" value="' + item[i].added_amt + '"></td>';

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

                var taxes_str = suggesion.challan.taxes;
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

        });


    });
    function getduedate(days)
    {
        const date = new Date();
        var d = parseInt(days);
        date.setDate(date.getDate() + d);
        var new_date = formatDate(date);
        $("#due_date").val(new_date);
    }
    function getduedays(date)
    {
        //alert("efherj");
        const date1 = new Date();
        var date2 = new Date(date);
        // alert(date2);
        var Difference_In_Time = date2.getDate() - date1.getDate();
        $("#due_days").val(Difference_In_Time);
        //var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
        //console.log(Difference_In_Time);
    }
</script>
<?= $this->endSection() ?>