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
        <h2 class="mb-1 font-weight-bold"><span>Sale Return Sr No :</span>
            <?= isset($s_return['return_no']) ? @$s_return['return_no'] : $current_id; ?></h2>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <form action="<?= url('Sales/add_salesreturn') ?>" class="ajax-form-submit-return" method="POST" id="returnform">
                        <div class="row">

                            <div class="col-lg-3 form-group">
                                <label class="form-label">Voucher Type : </label>
                                <select class="form-control" id="voucher_type" name='voucher_type'>
                                    <?php if (@$s_return['voucher_type']) { ?>
                                        <option value="<?= @$s_return['voucher_type'] ?>">
                                            <?= @$s_return['voucher_name'] ?>
                                        </option>
                                    <?php } else { ?>
                                        <option value="52" selected>
                                            Sales Taxable Return
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-lg-3 form-group">
                                <label class="form-label">Return No: <span class="tx-danger">*</span></label>
                                <input class="form-control" readonly type="text" name="return_no" value="<?= @$s_return['return_no'] ? $s_return['return_no'] : @$current_id; ?>">
                            </div>

                           
                            <?php
                            if(session('DataSource')=='ACE20227T93')
                            {
                            ?>

                            <div class="col-lg-3 form-group">
                                <label class="form-label">Return Date: <span class="tx-danger">*</span></label>
                                <input class="form-control fc-datepicker" placeholder="YYYY-MM-DD" type="text"  name="return_date" value="<?= @$s_return['return_date'] ? $s_return['return_date'] : date('Y-m-d'); ?>"  onchange="get_max_customInvno(this.value)" onkeyup="get_max_customInvno(this.value)">
                            </div>
                            <?php
                            }
                            else if(session('DataSource')=='KLA2022ZFDH')
                            
                            {
                            ?>
                             <div class="col-lg-3 form-group">
                                <label class="form-label">Return Date: <span class="tx-danger">*</span></label>
                                <input class="form-control fc-datepicker" placeholder="YYYY-MM-DD" type="text" id ="return_date" name="return_date" value="<?= @$s_return['return_date'] ? $s_return['return_date'] : date('Y-m-d'); ?>"  onchange="ecom_get_max_customInvno(this.value)" onkeyup="ecom_get_max_customInvno(this.value)">
                            </div>
                            <?php
                            }
                            else
                            {
                            ?>
                             <div class="col-lg-3 form-group">
                                <label class="form-label">Return Date: <span class="tx-danger">*</span></label>
                                <input class="form-control fc-datepicker" placeholder="YYYY-MM-DD" type="text" name="return_date" value="<?= @$s_return['return_date'] ? $s_return['return_date'] : date('Y-m-d'); ?>">
                            </div>
                            <?php
                            }
                            ?>
                            <div class="col-lg-3 form-group">
                                <label class="form-label">Supplier Invoice No:</label>
                                <input class="form-control" type="text" placeholder="Enter Supplier Invoice" id="supp_inv" name="supp_inv" value="<?= @$s_return['supp_inv'] ? $s_return['supp_inv'] : @$supp_inv_no; ?>">
                            </div>


                            <div class="col-lg-5 form-group">
                                <div class="row">
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Account: <span class="tx-danger">*</span></label>
                                        <div class="input-group" style="width:auto;">
                                            <select class="form-control" id="account" name='account'>
                                                <?php if (@$s_return['account_name']) { ?>
                                                    <option value="<?= @$s_return['account'] ?>">
                                                        <?= @$s_return['account_name'] ?>
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
                                        <input type="hidden" id="database_name" value="<?= session('DataSource') ?>">
                                        <input type="hidden" name="id" value="<?= @$s_return['id']; ?>">
                                        <input type="hidden" name="tds_per" id="tds_per" class="tds_per" value="<?= @$s_return['tds_per']; ?>">
                                        <input type="hidden" name="tds_limit" id="tds_limit" value="<?= @$s_return['tds_limit']; ?>">
                                        <input type="hidden" name="acc_state" id="acc_state" value="<?= @$s_return['acc_state']; ?>">
                                        <input type="hidden" name="bank_name" value="<?= @$s_return['bank_name']; ?>">
                                        <input type="hidden" name="bank_ac" value="<?= @$s_return['bank_ac']; ?>">
                                        <input type="hidden" name="bank_ifsc" value="<?= @$s_return['bank_ifsc']; ?>">
                                        <input type="hidden" name="bank_holder" value="<?= @$s_return['bank_holder']; ?>">
                                        <input type="hidden" name="gl_group" value="<?= @$s_return['gl_group']; ?>">


                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">GST No.: <span class="tx-danger">*</span></label>
                                        <input readonly class="form-control col-md-8" type="text" name="gst" id="gst" value="<?= @$s_return['gst']; ?>">
                                        <input type="hidden" name="newgsttin" id="_newgsttin" value="">
                                    </div>
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Transport Mode </label>
                                        <select class="select2" id="transport_mode" name="trasport_mode">
                                            <option <?= (@$s_return['transport_mode'] == 'AIR' ? 'selected' : '') ?> value="AIR">AIR</option>
                                            <option <?= (@$s_return['transport_mode'] == 'ROAD' ? 'selected' : '') ?> value="ROAD">ROAD</option>
                                            <option <?= (@$s_return['transport_mode'] == 'RAIL' ? 'selected' : '') ?> value="RAIL">RAIL</option>
                                            <option <?= (@$s_return['transport_mode'] == 'SHIP' ? 'selected' : '') ?> value="SHIP">SHIP</option>
                                        </select>
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Vehicle No : </label>
                                        <select class="form-control" id="vehicle" name='vhicle_modeno'>
                                            <?php if (@$s_return['vehicle_name']) { ?>
                                                <option value="<?= @$s_return['vehicle_no'] ?>">
                                                    <?= @$s_return['vehicle_name'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <a data-toggle="modal" href="<?= url('Master/add_vehicle') ?>" data-target="#fm_model" data-title="Enter Account"><i style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Broker: </label>
                                        <div class="input-group">
                                            <select class="form-control" id="broker" name='broker'>
                                                <?php if (@$s_return['broker_name']) { ?>
                                                    <option value="<?= @$s_return['broker'] ?>">
                                                        <?= @$s_return['broker_name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_account/broker') ?>" data-target="#fm_model" data-title="Enter Account"><i style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-10 form-group">
                                        <label class="form-label">Add Item: </label>
                                        <div class="input-group">
                                            <select class="form-control" id="code"> </select>
                                            <div class="dz-error-message tx-danger product_error"></div>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_item/general') ?>" data-target="#fm_model" data-title="Enter Item"><i style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 form-group">
                                        <label class="form-label">Particular Name: <span class="tx-danger">*</span></label>
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

                            <div class="col-lg-7 form-group">
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Select Invoice : <span class="tx-danger"></span></label>
                                    </div>
                                    <div class="col-md-10 form-group">
                                        <div class="input-group">
                                            <select class="form-control select2" id="invoices" name="invoice">
                                                <?php if (@$s_return['invoice_name']) { ?>
                                                    <option selected value="<?= @$s_return['invoice'] ?>">
                                                        <?= @$s_return['invoice_name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Narration: </label>
                                    </div>
                                    <div class="col-md-10 form-group">
                                        <div class="input-group">
                                            <input class="form-control" name="other" value="<?= @$s_return['other'] ?>" placeholder="Enter Other Detail" type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR No: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="lrno" value="<?= @$s_return['lr_no']; ?>" placeholder="LR No." type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR Date: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control fc-datepicker" placeholder="YYYY-MM-DD" type="text" id="lr_date" name="lr_date" value="<?= @$s_return['lr_date']; ?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Weigh: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="weight" value="<?= @$s_return['weight']; ?>" placeholder="0.00" placeholder="Enter Weight" type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Freight: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="freight" value="<?= @$s_return['freight']; ?>" placeholder="00" type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Transport:</label>
                                    </div>
                                    <div class="col-md-10 form-group">
                                        <div class="input-group">
                                            <select class="form-control" id="transport" name='transport'>
                                                <?php if (@$s_return['transport_name']) { ?>
                                                    <option value="<?= @$s_return['transport'] ?>">
                                                        <?= @$s_return['transport_name'] ?>
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
                                        <label class="form-label">City: </label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="form-control" id="city" name='city'>
                                            <?php if (@$s_return['city_name']) { ?>
                                                <option value="<?= @$s_return['city'] ?>"><?= @$s_return['city_name'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 form-group">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Shiped to AC: </label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="form-control" id="delivery_code" name='delivery_code'>
                                            <?php if (@$s_return['delivery_name']) { ?>
                                                <option value="<?= @$s_return['delivery_code'] ?>">
                                                    <?= @$s_return['delivery_name'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" name="ship_country" value="<?= @$s_return['ship_country'] ?>">
                                        <input type="hidden" name="ship_state" value="<?= @$s_return['ship_state'] ?>">
                                        <input type="hidden" name="ship_city" value="<?= @$s_return['ship_city'] ?>">
                                        <input type="hidden" name="ship_pin" value="<?= @$s_return['ship_pin'] ?>">
                                        <input type="hidden" name="ship_address" value="<?= @$s_return['ship_address'] ?>">

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
                                                        <td><input class="form-control input-sm" value="<?= $row['hsn'] ?>" readonly name="hsn[]" onchange="calculate()" type="text"></td>
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
                                                    if ($row['taxability'] == "N/A") {
                                                    ?>
                                                         <td><input class="form-control input-sm" value="<?= $row['igst'] ?>" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly>
                                                            <input name="igst_amt[]" value="<?= $row['igst_amt'] ?>" type="hidden">
                                                            <b class="igst_amt"></b>
                                                        </td>

                                                        <td><input class="form-control input-sm" value="<?= $row['cgst'] ?>" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text" readonly>
                                                            <input name="cgst_amt[]" value="<?= $row['cgst_amt'] ?>" type="hidden">
                                                            <b class="cgst_amt"></b>
                                                        </td>

                                                        <td><input class="form-control input-sm" value="<?= $row['sgst'] ?>" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text" readonly>
                                                            <input name="sgst_amt[]" value="<?= $row['sgst_amt'] ?>" type="hidden">
                                                            <b class="sgst_amt"></b>
                                                        </td>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <td><input class="form-control input-sm" value="<?= $row['igst'] ?>" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text">
                                                            <input name="igst_amt[]" value="<?= $row['igst_amt'] ?>" type="hidden">
                                                            <b class="igst_amt"></b>
                                                        </td>

                                                        <td><input class="form-control input-sm" value="<?= $row['cgst'] ?>" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">
                                                            <input name="cgst_amt[]" value="<?= $row['cgst_amt'] ?>" type="hidden">
                                                            <b class="cgst_amt"></b>
                                                        </td>

                                                        <td><input class="form-control input-sm" value="<?= $row['sgst'] ?>" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">
                                                            <input name="sgst_amt[]" value="<?= $row['sgst_amt'] ?>" type="hidden">
                                                            <b class="sgst_amt"></b>
                                                        </td>
                                                    <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['is_expence'] == 0) {
                                                    ?>
                                                        <td><input class="form-control input-sm" value="<?= $row['item_disc'] ?>" name="item_disc[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">
                                                            <b class="itm_disc_amt"></b>
                                                            <input type="hidden" name="item_discount_hidden[]" class="hidden_itm_disc_amt" value="<?= @$row['discount'] ?>">
                                                            <input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt" value="<?= $row['added_amt'] ?>">
                                                            <input type="hidden" name="item_per[]" class="item_per" value="<?= $row['divide_disc_item_per'] ?>">
                                                            <input type="hidden" name="divide_disc_amt[]" class="divide_disc_amt" value="<?= $row['divide_disc_item_amt'] ?>">
                                                        </td>
                                                        </td>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <td><input class="form-control input-sm" value="0" name="item_disc[]" type="hidden">
                                                            <input type="hidden" name="item_discount_hidden[]" class="hidden_itm_disc_amt" value="<?= @$row['discount'] ?>">
                                                            <input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt" value="<?= $row['added_amt'] ?>">
                                                            <input type="hidden" name="item_per[]" class="item_per" value="<?= $row['divide_disc_item_per'] ?>">
                                                            <input type="hidden" name="divide_disc_amt[]" class="divide_disc_amt" value="<?= $row['divide_disc_item_amt'] ?>">

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
                                                    <!-- <th>(-)Discount</th> -->
                                                    <td class="wd-100">
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="discount_acc" name="discount_acc">
                                                                <?php if (@$s_return['discount_acc']) { ?>
                                                                    <option value="<?= @$s_return['discount_acc'] ?>">
                                                                        <?= @$s_return['discount_acc_name'] ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </td>

                                                    <td class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control discount" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="discount" type="text" value="<?= @$s_return['discount']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 disc_type" name="disc_type" onchange="calculate()">
                                                                    <option <?= (@$s_return['disc_type'] == 'Fixed' ? 'selected' : '') ?> value="Fixed">Fixed Amount</option>
                                                                    <option <?= (@$s_return['disc_type'] == '%' ? 'selected' : '') ?> value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="discount_amount wd-90">
                                                       
                                                    </td>
                                                    <input type="hidden" name="discount_amount_new" class="discount_amount_new" value="">
                                                </tr>
                                               
                                                <tr>
                                                    <td>Taxable Amount</td>
                                                    <td colspan="2"><input name="taxable" value="<?= @$s_return['taxable'] ?>" class="form-control input-sm" type="text" readonly></td>
                                                </tr>


                                                <tr>
                                                    <th>Select Tax</th>
                                                    <th colspan="2" class="wd-300">
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="tax" name="taxes[]" onchange="calculate()" multiple>
                                                                <?php
                                                                $taxes = json_decode(@$s_return['taxes']);
                                                                // print_r($tax);
                                                                // echo in_array($tax[0]['name'], $taxes);exit;
                                                                if (!empty($s_return)) {
                                                                    //$new_tax = json_decode($s_return['taxes']);
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
                                                                                } ?> ">
                                                    <th>
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="igst_acc" name="igst_acc">
                                                                <?php if (@$s_return['igst_acc']) { ?>
                                                                    <option value="<?= @$s_return['igst_acc'] ?>">
                                                                        <?= @$s_return['igst_acc_name'] ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="tot_igst" type="text" value="<?= @$s_return['tot_igst']; ?>">
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
                                                                <?php if (@$s_return['sgst_acc']) { ?>
                                                                    <option value="<?= @$s_return['sgst_acc'] ?>">
                                                                        <?= @$s_return['sgst_acc_name'] ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="tot_sgst" type="text" value="<?= @$s_return['tot_sgst']; ?>">

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
                                                                <?php if (@$s_return['cgst_acc']) { ?>
                                                                    <option value="<?= @$s_return['cgst_acc'] ?>">
                                                                        <?= @$s_return['cgst_acc_name'] ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="tot_cgst" type="text" value="<?= @$s_return['tot_cgst']; ?>">

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
                                                            <input class="form-control tds_amt" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="tds_amt" type="text" value="<?= @$s_return['tds_amt']; ?>">

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
                                                            <input class="form-control cess" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="cess" type="text" value="<?= @$s_return['cess']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2 cess_mode" name="cess_type" onchange="calculate()">
                                                                    <option <?= (@$s_return['cess_type'] == 'Fixed' ? 'selected' : '') ?> value="Fixed">Fixed Amount</option>
                                                                    <option <?= (@$s_return['cess_type'] == '%' ? 'selected' : '') ?> value="%">Per(%) Amount</option>
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
                                                                <?php if (@$s_return['round_acc']) { ?>
                                                                    <option value="<?= @$s_return['round_acc'] ?>">
                                                                        <?= @$s_return['round_acc_name'] ?>
                                                                    </option>
                                                            
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </td>
                                                    <td><input class="form-control input-sm" onchange="calculate()" value="<?= @$s_return['round_diff'] ?>" name="round_diff" type="text"></td>
                                                    <td class="wd-90 cr_dr_round"></td>


                                                </tr>
                                                <tr>
                                                    <td>Net Amount</td>
                                                    <td colspan="2"><input class="form-control input-sm" name="net_amount" type="text" readonly></td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="tx-danger error-msg-return"></div>
                            <div class="tx-success form_proccessing_return"></div>
                        </div>
                        <div class="row mt-3">
                            <input class="btn btn-space btn-primary btn-product-submit" id="save_data_return" type="submit" value="Submit">
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



        var item_disc = $('input[name="item_disc[]"]').map(function() {
            return parseFloat(this.value); // $(this).val()
        }).get();

        var price = $('input[name="price[]"]').map(function() {
            return parseFloat(this.value); // $(this).val()
        }).get();

        var igst = $('input[name="igst[]"]').map(function() {
            return parseFloat(this.value); // $(this).val()
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

                item_igst_amt = Number(item_igst_amt).toFixed(2);
                $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                $('.igst_amt').eq(i).text(item_igst_amt);
                $('.cgst_amt').eq(i).text(item_igst_amt / 2);
                $('.sgst_amt').eq(i).text(item_igst_amt / 2);

                $('input[name="subtotal[]"]').eq(i).val(final_sub.toFixed(2));
                uom_name = $('select[name="uom[]"] :selected').eq(i).text();

                $('input[name="subtotal[]"]').eq(i).closest('.item_row').find('.uom_name').html('/ ' + uom_name);
                $('input[name="subtotal[]"]').eq(i).closest('.item_row').find('.itm_disc_amt').html(parseFloat(disc_amt
                    .toFixed(2)));
                $('input[name="subtotal[]"]').eq(i).closest('.item_row').find(".hidden_itm_disc_amt").val(disc_amt.toFixed(2));
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
            total += final_sub;
        }

        $('.total').html(total.toFixed(2));

        // tot_fix_brok = total * fix_brokrage/100;

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

                        var abc =price[i];
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
            console.log(amty_amount);
            var divide_amt = amty_amount / qty.length;

            $('.amty_amount').html('+ ' + parseFloat(amty_amount).toFixed(2));
            grand_total += (total * (amty / 100));
        } else {
            $('.amty_amount').html('+ ' + parseFloat(amty).toFixed(2));
            grand_total += amty;
            console.log(amty);

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
        //console.log(round_amt);
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

        $('#transport_mode').select2({
            width: '65%'
        });


        var pids = $('input[name="pid[]"]').map(function() {
            return parseInt(this.value); // $(this).val()
        }).get();
        var expence = $('input[name="expence[]"]').map(function() {
            return parseInt(this.value); // $(this).val()
        }).get();

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

            if (pids_item.includes(parseInt(suggestion.id)) == false) {
                $('.product_error').html('');


                pids_item.push(parseInt(suggestion.id));
                var inp = '<input type="hidden" name="pid[]" value="' + suggestion.id + '">';
                var taxability = '<input name="taxability[]" value="' + suggestion.price.taxability +
                    '" type="hidden">';

                var expence = '<input type="hidden" name="expence[]" value="0">';
                var tds = '<tr class="item_row">';
                tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                    '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
                tds += '<td>' + suggestion.text + inp + taxability + expence + '</td>';
                tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                    .hsn +
                    '" name="hsn[]" readonly onchange="calculate()"  type="text"></td>';
                tds += '<td><select name="uom[]" onchange="calculate()">' + suggestion.uom +
                    '</select></td>';
                tds +=
                    '<td><input class="form-control input-sm" value="0" name="qty[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text"></td>';
                tds += '<td><input class="form-control input-sm" value="' + suggestion.price
                    .sales_price +
                    '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text"></td>';

                if (suggestion.price.taxability == 'N/A') {
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
                var item_per = '<input value="" name="item_per[]" type="hidden">';
                var divide_disc_amt = '<input value="" name="divide_disc_amt[]" type="hidden">';
                var uom = '<input value="" name="uom[]" type="hidden">';
                var hsn = '<input value="" name="hsn[]" type="hidden">';

                var tds = '<tr class="item_row">';
                tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                    '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
                tds += '<td colspan="4">' + suggestion.text + inp + taxability + expence + qty + item_disc +
                    uom + hsn + item_per + divide_disc_amt + '</td>';

                tds +=
                    '<td><input class="form-control input-sm" value="0" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" required="" type="text"></td>';

                if (suggestion.paticular.taxability == 'N/A') {
                    tds += '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"> <b class="igst_amt"></b></td>';
                    tds += '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"> <b class="cgst_amt"></b></td>';
                    tds += '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"> <b class="sgst_amt"></b></td>';

                } else {
                    var igst_amt = '<input name="igst_amt[]" value="" type="hidden">';
                    var cgst_amt = '<input name="cgst_amt[]" value="" type="hidden">';
                    var sgst_amt = '<input name="sgst_amt[]" value="" type="hidden">';
                    tds += '<td><input class="form-control input-sm" value="' + suggestion.paticular
                        .igst +
                        '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text">' +
                        igst_amt + ' <b class="igst_amt"></b></td>';

                    tds += '<td><input class="form-control input-sm" value="' + suggestion.paticular
                        .cgst +
                        '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                        cgst_amt + ' <b class="cgst_amt"></b></td>';


                    tds += '<td><input class="form-control input-sm" value="' + suggestion.paticular
                        .sgst +
                        '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                        sgst_amt + ' <b class="sgst_amt"></b></td>';

                }
                tds += '<td><input type="hidden" name="item_discount_hidden[]" class="hidden_itm_disc_amt"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt"></td>';
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


        $('.ajax-form-submit-return').on('submit', function(e) {
            $('#save_data_return').prop('disabled', true);
            $('.error-msg-return').html('');
            $('.form_proccessing_return').html('Please wail...');
            e.preventDefault();
            var aurl = $(this).attr('action');
            $.ajax({
                type: "POST",
                url: aurl,
                data: $(this).serialize(),
                success: function(response) {
                    if (response.st == 'success') {

                        $('#save_data_return').prop('disabled', false);
                        window.location = "<?= url('sales/salesreturn') ?>";
                    } else {
                        $('.form_proccessing_return').html('');
                        $('#save_data_return').prop('disaabled', false);
                        $('.error-msg-return').html(response.msg);
                    }
                },
                error: function() {
                    $('#save_data_return').prop('disabled', false);
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
            placeholder: 'Type Account Name',
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

        $('#account').on('select2:select', function(e) {
            var data = e.params.data;
            // console.log(data);
            $('#gst').val(data.gsttin);
            $("input[name='newgsttin']").val(data.gsttin);
            $('#tds_per').val(data.tds);
            $('#tds_limit').val(data.tds_limit);
            $('#acc_state').val(data.state);
            $('input[name="gl_group"]').val(data.data.gl_group);

            $('input[name="due_day"]').val(data.due_day);
            $('input[name="bank_name"]').val(data.data.trans_bank_name);
            $('input[name="bank_ac"]').val(data.data.trans_bank_ac);
            $('input[name="bank_ifsc"]').val(data.data.trans_bank_ifsc);
            $('input[name="bank_holder"]').val(data.data.trans_bank_holder);

            var ac_id = data.id;
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

            if (ac_id != undefined && ac_id != '') {

                $("#invoices").select2({
                    width: '100%',
                    placeholder: 'Choose Invoice',
                    ajax: {
                        url: PATH + "sales/getdata/search_sales_invoice",
                        type: "post",
                        allowClear: true,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                id: ac_id,
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
            enable_gst_option();
            calculate();
            var database_name = $('#database_name').val()
            if(database_name == 'KLA2022ZFDH')
            {
                
                ecom_get_max_customInvno();
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



        $("#delivery_code").select2({
            width: '100%',
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

        $("#haste").select2({
            width: '100%',
            placeholder: 'Type Haste Acount name',
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

        $("#voucher_type").select2({
            width: '100%',
            placeholder: 'Voucher Type',
            ajax: {
                url: PATH + "Master/Getdata/search_saleReturnVoucher",
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
            width: '45%',
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
    // start add plus button create option append data 
    function get_account_data(id, data) {
        //console.log(data);
        $('#gst').val(data.gst);
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
            tds += '<td><select name="uom[]" onchange="calculate()"> <option value="' + JSON.stringify(data.uom[0]) + '">' + suggestion.uom_name + '</option>' +
                '</select></td>';
            tds +=
                '<td><input class="form-control input-sm" value="0" name="qty[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text"></td>';
            tds += '<td><input class="form-control input-sm" value="' + suggestion
                .sales_price +
                '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text"></td>';
            if (suggestion.taxability == 'N/A') {
                tds += '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"><b class="igst_amt"></b></td>';
                tds += '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"><b class="cgst_amt"></b></td>';
                tds += '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"><b class="sgst_amt"></b></td>';

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
                tds += '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"><b class="igst_amt"></b></td>';
                tds += '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"><b class="cgst_amt"></b></td>';
                tds += '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"><b class="sgst_amt"></b></td>';

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
            tds += '<td><input type="hidden" name="item_discount_hidden[]" class="hidden_itm_disc_amt"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt"></td>';
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
    function get_max_customInvno(date)
    {
       
        $.ajax({
                url: PATH + "Sales/Getdata/get_max_customInvno",
                type: "post",
                data: {date:date,type:'invoice'},
                success: function(data){
                    $("#supp_inv").val(data.invoice);
                },
                error: function(err){
                    console.log("error");
                    console.log(err);
                },
            });
    }
    function ecom_get_max_customInvno()
    {
        newgst =  document.getElementById('_newgsttin').value;
        inv_date = $("#return_date").val();
        ac_id = $("#account").val();
       
        $.ajax({
                url: PATH + "Sales/Getdata/ecom_ret_get_max_customInvno",
                type: "post",
                data: {date:inv_date,gst:newgst,ac_id:ac_id},
                success: function(data){
                    $("#supp_inv").val(data.invoice);
                },
                error: function(err){
                    console.log("error");
                    console.log(err);
                },
            });
    }
   
</script>
<?= $this->endSection() ?>