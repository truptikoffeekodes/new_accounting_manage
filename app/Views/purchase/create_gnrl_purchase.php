<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5">Transacrion</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Sales</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <!-- Row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card custom-card">
                    <div class="card-header card-header-divider">
                        <div class="card-body">
                            <form action="<?= url('purchase/add_general_pur/') . $type ?>" class="ajax-form-submit-purchase" method="post" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-lg-4 form-group" id="sales_taxable_div" style="display:<?= (@$invoice['v_type'] != "general") ? 'none;' : 'inline-grid;' ?>">
                                        <label class="form-label">Voucher Type : </label>
                                        <select class="form-control" id="purchase_taxable" name='voucher_type'>
                                            <?php if (@$invoice['voucher_type']) { ?>
                                                <option value="<?= @$invoice['voucher_type'] ?>">
                                                    <?= @$invoice['voucher_name'] ?>
                                                </option>
                                            <?php } else { ?>
                                                <option value="53" selected>
                                                    Purchase Taxable
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-4 form-group" id="sales_return_div" style="display:<?= (@$general['v_type'] == "return") ? 'inline-grid;' : 'none;' ?>">
                                        <label class="form-label">Voucher Type : </label>
                                        <select class="form-control" id="purchase_return" name='voucher_type'>
                                            <?php if (@$general['voucher_type']) { ?>
                                                <option value="<?= @$general['voucher_name'] ?>">
                                                    <?= @$general['voucher_name'] ?>
                                                </option>
                                            <?php } else { ?>
                                                <option value="54" selected>
                                                    Purchase Taxable Return
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Invoice No: <span class="tx-danger">*</span></label>
                                        <input class="form-control" readonly type="text" name="invoice_no" value="<?= @$general['invoice_no'] ? $general['invoice_no'] : @$current_id; ?>">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-labestl">Doc Date: <span class="tx-danger">*</span></label>
                                        <input class="form-control fc-datepicker" name="doc_date" value="<?= @$general['doc_date'] ? $general['doc_date'] : date('Y-m-d') ?>" placeholder="YYYY-MM-DD" type="text" id="" required>
                                    </div>


                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Party Account: <span class="tx-danger">*</span></label>
                                        <div class="input-group" style="width:auto;">
                                            <select class="form-control" id="party_account" name='party_account'>
                                                <?php if (@$general['party_name']) { ?>
                                                    <option selected value="<?= @$general['party_account'] ?>">
                                                        <?= @$general['party_name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_account') ?>" data-target="#fm_model" data-title="Enter Account"><i style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tx-danger error-msg return-error"> </div>
                                        <input name="id" value="<?= @$general['id'] ?>" type="hidden">
                                        <input type="hidden" name="tds_per" id="tds_per" value="<?= @$general['tds_per']; ?>">
                                        <input type="hidden" name="tds_limit" id="tds_limit" value="<?= @$general['tds_limit']; ?>">
                                        <input type="hidden" name="acc_state" id="acc_state" value="<?= @$general['acc_state']; ?>">
                                        <input type="hidden" name="bank_name" value="<?= @$general['bank_name']; ?>">
                                        <input type="hidden" name="bank_ac" value="<?= @$general['bank_ac']; ?>">
                                        <input type="hidden" name="bank_ifsc" value="<?= @$general['bank_ifsc']; ?>">
                                        <input type="hidden" name="bank_holder" value="<?= @$general['bank_holder']; ?>">
                                        <input type="hidden" name="gst_no" id="gst_no" value="<?= @$general['gst_no']; ?>">
                                        <input type="hidden" name="gl_group" value="<?= @$general['gl_group']; ?>">

                                    </div>


                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Voucher Type: <span class="tx-danger">*</span></label>
                                        <?php if ($type == 'general') { ?>
                                            <label class="rdiobox"><input name="v_type" required checked value="general" type="radio" onchange="calculate()">
                                                <span>General</span></label>
                                        <?php } ?>
                                        <?php if ($type == 'return') { ?>

                                            <label class="rdiobox"><input name="v_type" required checked value="return" type="radio" onchange="calculate()"> <span>Return</span></label>
                                        <?php } ?>
                                    </div>


                                    <div class="col-md-5 form-group" id="invoice_div" style="display:<?= !empty(@$general['return_purchase']) ? 'block;' : 'none;' ?>">

                                        <label class="form-label">Select Invoice : <span class="tx-danger"></span></label>

                                        <div class="input-group">
                                            <select class="form-control select2" id="invoices" name="invoice">
                                                <?php if (@$general['return_pur_name']) { ?>
                                                    <option selected value="<?= @$general['return_purchase'] ?>">
                                                        <?= @$general['return_pur_name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 form-group">
                                        <label class="form-label">Supplier invoice No.: <span class="tx-danger"></span></label>
                                        <input class="form-control" type="text" placeholder="Enter Supplier Invoice No." name="supp_inv" id="supp_inv" value="<?= @$general['supp_inv']; ?>">
                                    </div>

                                    <div class="col-lg-3 form-group">
                                        <label class="form-label">Supplier invoice Date.: <span class="tx-danger"></span></label>
                                        <input class="form-control fc-datepicker" name="supp_inv_date" value="<?= @$general['supp_inv_date'] ?>" placeholder="YYYY-MM-DD" type="text" id="">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-7 form-group">
                                        <label class="form-label">Particular Name: <span class="tx-danger">*</span></label>
                                        <div class="input-group" style="width:auto;">
                                            <select class="form-control" id="code" name='code'> </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_account_inc_exp') ?>" data-target="#fm_model" data-title="Enter Account"><i style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="dz-error-message tx-danger product_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label class="form-label">Narration: <span class="tx-danger"></span></label>
                                        <input class="form-control" type="text" placeholder="Enter Other Detail." name="other" value="<?= @$general['other']; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mg-b-0" id="product">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Particular</th>
                                                    <th>Amount</th>
                                                    <th>IGST</th>
                                                    <th>CGST</th>
                                                    <th>SGST</th>
                                                    <th>Total Amount</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tbody">
                                                <?php
                                                if (isset($acc)) {
                                                    $total = 0.0;
                                                    foreach ($acc as $row) {

                                                        $sub_total = $row['amount'];
                                                        $total += $sub_total;
                                                        //  $uom=explode(',',$row['item_uom']);
                                                ?>
                                                        <tr class="item_row">
                                                            <td><a class="tx-danger btnDelete" data-id="<?= $row['account'] ?>" title="0"><i class="fa fa-times tx-danger"></i></a></td>
                                                            <td><?= $row['account_name'] ?>(<?= $row['code'] ?>)
                                                                <input type="hidden" name="pid[]" value="<?= $row['account'] ?>">
                                                                <input name="taxability[]" value="<?= @$row['taxability'] ?>" type="hidden">
                                                            </td>


                                                            <td><input class="form-control input-sm" value="<?= $row['amount'] ?>" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" required="" type="text"></td>
                                                            <?php
                                                            if ($row['taxability'] == "N/A") {
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
                                                            } else {
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
                                                            <td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="<?= $sub_total ?>" type="text" readonly=""></td>
                                                            <td><input class="form-control input-sm" name="remark[]" value="<?= $row['remark'] ?>" placeholder="Remark" type="text">
                                                                <input type="hidden" name="item_discount_hidden[]" class="hidden_discount" value="<?= @$row['discount'] ?>">
                                                                <input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt" value="<?= $row['added_amt'] ?>">
                                                            </td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <td colspan="2" class="text-right">Total</td>

                                                <td class="amount_total"></td>
                                                <td class="IGST_total"></td>
                                                <td class="CGST_total"></td>
                                                <td class="SGST_total"></td>
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
                                                            <th>(-)Discount</th>
                                                            <th class="wd-300">
                                                                <div class="input-group">
                                                                    <input class="form-control" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="discount" type="text" value="<?= @$invoice['discount']; ?>">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2" name="disc_type" onchange="calculate()">
                                                                            <option <?= (@$general['disc_type'] == 'Fixed' ? 'selected' : '') ?> value="Fixed">Fixed Amount</option>
                                                                            <option <?= (@$general['disc_type'] == '%' ? 'selected' : '') ?> value="%">Per(%) Amount</option>

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
                                                                    <input class="form-control" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="amty" type="text" value="<?= @$general['amty']; ?>">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2" name="amty_type" onchange="calculate()">
                                                                            <option <?= (@$general['amty_type'] == 'Fixed' ? 'selected' : '') ?> value="Fixed">Fixed Amount</option>
                                                                            <option <?= (@$general['amty_type'] == '%' ? 'selected' : '') ?> value="%">Per(%) Amount</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th class="amty_amount wd-90"></th>
                                                        </tr>

                                                        <tr>
                                                            <td>Taxable Amount</td>
                                                            <td colspan="2"><input name="taxable" value="<?= @$general['taxable'] ?>" class="form-control input-sm" type="text" readonly></td>
                                                        </tr>

                                                        <tr>
                                                            <th>Select Tax</th>
                                                            <th colspan="2" class="wd-300">
                                                                <div class="input-group-sm">
                                                                    <select class="select2" id="tax" name="taxes[]" onchange="calculate()" multiple>
                                                                        <?php
                                                                        $taxes = json_decode(@$general['taxes']);
                                                                        // print_r($tax);
                                                                        // echo in_array($tax[0]['name'], $taxes);exit;
                                                                        if (!empty($general)) {
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
                                                                                        } ?> ">
                                                            <th>
                                                                <div class="input-group-sm">
                                                                    <select class="select2" id="igst_acc" name="igst_acc">
                                                                        <?php if (@$general['igst_acc']) { ?>
                                                                            <option value="<?= @$general['igst_acc'] ?>">
                                                                                <?= @$general['igst_acc_name'] ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>

                                                                </div>
                                                            </th>
                                                            <th class="wd-300">
                                                                <div class="input-group-sm">
                                                                    <input class="form-control" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="tot_igst" type="text" value="<?= @$general['tot_igst']; ?>">
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
                                                                        <?php if (@$general['sgst_acc']) { ?>
                                                                            <option value="<?= @$general['sgst_acc'] ?>">
                                                                                <?= @$general['sgst_acc_name'] ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>

                                                                </div>
                                                            </th>
                                                            <th class="wd-300">
                                                                <div class="input-group-sm">
                                                                    <input class="form-control" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="tot_sgst" type="text" value="<?= @$general['tot_sgst']; ?>">

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
                                                                        <?php if (@$general['cgst_acc']) { ?>
                                                                            <option value="<?= @$general['cgst_acc'] ?>">
                                                                                <?= @$general['cgst_acc_name'] ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>

                                                                </div>
                                                            </th>
                                                            <th class="wd-300">
                                                                <div class="input-group-sm">
                                                                    <input class="form-control" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="tot_cgst" type="text" value="<?= @$general['tot_cgst']; ?>">

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
                                                                    <input class="form-control tds_amt" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="tds_amt" type="text" value="<?= @$general['tds_amt']; ?>">

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
                                                                    <input class="form-control cess" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" name="cess" type="text" value="<?= @$general['cess']; ?>">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2 cess_mode" name="cess_type" onchange="calculate()">
                                                                            <option <?= (@$general['cess_type'] == 'Fixed' ? 'selected' : '') ?> value="Fixed">Fixed Amount</option>
                                                                            <option <?= (@$general['cess_type'] == '%' ? 'selected' : '') ?> value="%">Per(%) Amount</option>
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
                                                                        <?php if (@$general['round']) { ?>
                                                                            <option value="<?= @$general['round'] ?>">
                                                                                <?= @$general['round_name'] ?>
                                                                            </option>
                                                                        <?php } else { ?>
                                                                            <option value="6" selected>
                                                                                Round Off (Default)
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>

                                                                </div>
                                                            </th>
                                                            <th><input class="form-control input-sm" onchange="calculate()" value="<?= @$general['round_diff'] ?>" name="round_diff" type="text"></th>
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
                                    <div class="tx-danger error-msg-purchase"></div>
                                    <div class="tx-success form_proccessing_purchase"></div>
                                </div>
                                <div class="row mt-3">
                                    <input class="btn btn-space btn-primary btn-product-submit" id="save_data_purchase" type="submit">
                                </div>
                            </form>
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
    if (isset($id)) { ?>
        calculate();
    <?php } ?>

    function calc_gst_per(obj) {
        console.log($(obj).val())
        var igst = $(obj).val();
        if (igst == '' || igst == 'undefined' || isNaN(igst)) {
            igst = 0;
        }

        $(obj).closest('.item_row').find('input[name="cgst[]"]').val(parseFloat(igst) / 2);
        $(obj).closest('.item_row').find('input[name="sgst[]"]').val(parseFloat(igst) / 2);
    }


    function enable_gst_option() {

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
    var pids = [];

    function calculate() {

        var qty = $('input[name="qty[]"]').map(function() {
            return parseFloat(this.value); // $(this).val()
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

        var total = 0.0;
        var igst_amt = 0.0;
        var tot_item_brok = 0.0;
        var tot_fix_brok = 0.0;

        for (var i = 0; i < price.length; i++) {
            if (price[i] == '' || price[i] == 'undefined' || isNaN(price[i])) {
                price[i] = 0;
            }

            if (igst[i] == '' || igst[i] == 'undefined' || isNaN(igst[i])) {
                igst[i] = 0;
            }

            var final_sub = price[i];
            igst_amt += final_sub * igst[i] / 100;
            item_igst_amt = final_sub * igst[i] / 100;
            //console.log(item_igst_amt);
            $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
            $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
            $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);


            $('input[name="subtotal[]"]').eq(i).val(final_sub);

            total += final_sub;

        }
        $('.total').html(total.toFixed(2));

        //tot_fix_brok = total * fix_brokrage/100;

        var discount = $('input[name="discount"]').val();

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


        if (discount_type == '%') {
            discount_amount = (total * (discount / 100));
            $('.discount_amount').html('- ' + discount_amount.toFixed(2));
            if (discount_amount > 0) {
                var total = 0;
                var divide_disc = discount_amount / price.length;
                var igst_amt = 0;
                for (var i = 0; i < price.length; i++) {

                    if (price[i] == '' || price[i] == 'undefined' || isNaN(price[i])) {
                        price[i] = 0;
                    }

                    if (igst[i] == '' || igst[i] == 'undefined' || isNaN(igst[i])) {
                        igst[i] = 0;
                    }

                    var sub = price[i];

                    // append discount amount here ......
                    var indexx = $(".item_row").find(".hidden_discount").val(divide_disc.toFixed(2));

                    var final_sub = sub;

                    var abc = final_sub - divide_disc;
                    igst_amt += abc * igst[i] / 100;
                    item_igst_amt = final_sub * igst[i] / 100;

                    $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                    $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    total += abc;
                }
            }
        } else {
            $('.discount_amount').html('- ' + discount);
            if (discount > 0) {
                var total = 0;
                var divide_disc = discount / price.length;
                var igst_amt = 0;
                for (var i = 0; i < price.length; i++) {

                    if (price[i] == '' || price[i] == 'undefined' || isNaN(price[i])) {
                        price[i] = 0;
                    }

                    if (igst[i] == '' || igst[i] == 'undefined' || isNaN(igst[i])) {
                        igst[i] = 0;
                    }

                    var sub = price[i];

                    // append discount amount here ......
                    var indexx = $(".item_row").find(".hidden_discount").val(divide_disc.toFixed(2));
                    var final_sub = price[i];

                    var abc = final_sub - divide_disc;
                    igst_amt += abc * igst[i] / 100;
                    item_igst_amt = final_sub * igst[i] / 100;
                    $('input[name="igst_amt[]"]').eq(i).val(item_igst_amt);
                    $('input[name="sgst_amt[]"]').eq(i).val(item_igst_amt / 2);
                    $('input[name="cgst_amt[]"]').eq(i).val(item_igst_amt / 2);

                    total += abc;
                }
            }
        }

        var grand_total = total;


        if (amty_type == '%') {
            amty_amount = (total * (amty / 100));
            console.log(amty_amount);
            var divide_amt = amty_amount / price.length;

            $('.amty_amount').html('+ ' + parseFloat(amty_amount).toFixed(2));
            grand_total += (total * (amty / 100));
        } else {
            $('.amty_amount').html('+ ' + parseFloat(amty).toFixed(2));
            grand_total += amty;
            console.log(amty);

            var divide_amt = amty / price.length;

        }


        // added amount
        for (var i = 0; i < price.length; i++) {
            // append add amount here ......
            var indexx = $(".item_row").find(".hidden_added_amt").val(divide_amt.toFixed(2));

        }
        $('input[name="taxable"]').val(grand_total.toFixed(2));

        if (cess_type == '%') {
            cess_amount = (total * (cess / 100));

            $('.cess_amount').html('+ ' + cess_amount.toFixed(2));
            grand_total += (total * (cess / 100));
        } else {

            $('.cess_amount').html('+ ' + cess.toFixed(2));
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

        // var round_fig = $('input[name="round_diff"]').val();
        // if (round_fig == '' || round_fig == 'undefined' || isNaN(round_fig)) {
        //     round_fig = 0;
        // }

        // var round_amt = parseFloat(grand_total) + parseFloat(round_fig);

        // if (round_amt == '' || round_amt == 'undefined' || isNaN(round_amt)) {
        //     round_amt = grand_total;
        // }
        // var round_diff = 0;
        // var cr_dr = '';
        // round_diff = parseFloat(round_amt) - parseFloat(grand_total);

        // if (round_diff < 0) {
        //     cr_dr = 'CR';
        // } else {
        //     cr_dr = 'DR';
        // }

        // $('input[name="net_amount"]').val(round_amt.toFixed(2));
        // $('input[name="round_diff"]').val(round_diff.toFixed(2));
        // $('.cr_dr_round').html(((cr_dr == 'CR') ? '+' : '') + round_diff.toFixed(2) + ' ' + cr_dr);
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
        $('.tds_amount').html('+ ' + tds_amount.toFixed(2));
    }


    $(document).ready(function() {

        var ac_id = $('#party_account').val();

        var invoice_div = document.getElementById("invoice_div");

        var sales_taxable = document.getElementById("sales_taxable_div");
        var sales_return = document.getElementById("sales_return_div");
        var v_type = $("input[name='v_type']:checked").val();

        if (v_type == 'return') {
            invoice_div.style.display = "block";
            sales_taxable.style.display = "none";
            sales_return.style.display = "inline-grid";
        } else {
            invoice_div.style.display = "none";
            sales_taxable.style.display = "inline-grid";
            sales_return.style.display = "none";
        }


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

        $('#code').on('select2:select', function(e) {
            var suggestion = e.params.data;

            if (pids.includes(parseInt(suggestion.id)) == false) {
                $('.product_error').html('');

                pids.push(parseInt(suggestion.id));

                var inp = '<input type="hidden" name="pid[]" value="' + suggestion.id + '">';
                var taxability = '<input type="hidden" name="taxability[]" value="' + suggestion
                    .paticular
                    .taxability + '">';

                var tds = '<tr class="item_row">';
                tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                    '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
                tds += '<td>' + suggestion.text + inp + taxability + '</td>';
                tds +=
                    '<td><input class="form-control input-sm" value="0" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

                if (suggestion.paticular.taxability == 'N/A') {
                    tds += '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"></td>';
                    tds += '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"></td>';
                    tds += '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"></td>';

                } else {
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
                tds +=
                    '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="0" required="" type="text" readonly></td>';
                tds +=
                    '<td><input class="form-control input-sm" name="remark[]" placeholder="Remark" type="text"><input type="hidden" name="item_discount_hidden[]" class="hidden_discount"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt"></td>';
                tds += '</tr>';

                $('.tbody').append(tds);
                $('#code').val('');

                calculate();
            } else {
                $('.product_error').html('Selected Product Already Added');
                $('#code').val('');
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

            });

        });

        $('.ajax-form-submit-purchase').on('submit', function(e) {
            $('#save_data_purchase').prop('disable', true);
            $('.error-msg-purchase').html('');
            $('.form_proccessing_purchase').html('Please wail...');
            e.preventDefault();
            var aurl = $(this).attr('action');
            $.ajax({
                type: "POST",
                url: aurl,
                data: $(this).serialize(),
                success: function(response) {
                    if (response.st == 'success') {

                        window.location = "<?= url('purchase/general_purchase') ?>"
                    } else {
                        $('.form_proccessing_purchase').html('');
                        $('#save_data_purchase').prop('disabled', false);
                        $('.error-msg-purchase').html(response.msg);
                    }
                },
                error: function() {
                    $('#save_data_purchase').prop('disabled', false);
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

        $("#party_account").select2({

            width: 'resolve',
            placeholder: 'Type Party Account',
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

        $('#party_account').on('select2:select', function(e) {

            var data = e.params.data;
            var ac_id = data.id;
            $('input[name="gl_group"]').val(data.data.gl_group);

            $('.return-error').html('');


            $('#gst_no').val(data.gsttin);
            $('#tds_per').val(data.tds);
            $('#tds_limit').val(data.tds_limit);
            $('#acc_state').val(data.state);

            $('input[name="due_day"]').val(data.due_day);
            $('input[name="bank_name"]').val(data.data.trans_bank_name);
            $('input[name="bank_ac"]').val(data.data.trans_bank_ac);
            $('input[name="bank_ifsc"]').val(data.data.trans_bank_ifsc);
            $('input[name="bank_holder"]').val(data.data.trans_bank_holder);

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
                    // minimumInputLength: 1,
                    ajax: {
                        url: PATH + "purchase/getdata/search_purchase_general",
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
            } else {
                $('.return-error').html('Please Select Party..!');
            }

            enable_gst_option();

        });

        $("input[name='v_type']").change(function() {

            var ac_id = $('#party_account').val();
            var invoice_div = document.getElementById("invoice_div");

            var sales_taxable = document.getElementById("sales_taxable_div");
            var sales_return = document.getElementById("sales_return_div");


            if ($(this).val() == 'return') {

                invoice_div.style.display = "block";
                sales_taxable.style.display = "none";
                sales_return.style.display = "inline-grid";

                if (ac_id != undefined && ac_id != '') {

                    $("#invoices").select2({

                        width: '100%',
                        placeholder: 'Choose Invoice',
                        // minimumInputLength: 1,
                        ajax: {
                            url: PATH + "purchase/getdata/search_purchase_general",
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
                } else {
                    $('.return-error').html('Please Select Party..!');
                }
            } else {
                invoice_div.style.display = "none";

                sales_taxable.style.display = "inline-grid";
                sales_return.style.display = "none";
            }
        });




        $("#particular").select2({

            width: 'resolve',
            placeholder: 'Type Account',
            ajax: {
                url: PATH + "Master/Getdata/search_particular_item",
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

        $("#purchase_taxable").select2({
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

        $("#purchase_return").select2({
            width: '100%',
            placeholder: 'Voucher Type',
            ajax: {
                url: PATH + "Master/Getdata/search_purchaseRetvoucher",
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


    });
    // start add plus button create option append data 
    function get_account_data(ac_id, data) {
        //console.log(data);
        $('input[name="gl_group"]').val(data.gl_group);

        $('.return-error').html('');


        $('#gst_no').val(data.gst);
        $('#tds_per').val(data.tds);
        $('#tds_limit').val(data.tds_limit);
        $('#acc_state').val(data.state);

        $('input[name="due_day"]').val(data.due_day);
        $('input[name="bank_name"]').val(data.trans_bank_name);
        $('input[name="bank_ac"]').val(data.trans_bank_ac);
        $('input[name="bank_ifsc"]').val(data.trans_bank_ifsc);
        $('input[name="bank_holder"]').val(data.trans_bank_holder);

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
                // minimumInputLength: 1,
                ajax: {
                    url: PATH + "purchase/getdata/search_purchase_general",
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
        } else {
            $('.return-error').html('Please Select Party..!');
        }

        enable_gst_option();

    }
   function get_expence_data(id,suggestion) {
            //var suggestion = e.params.data;

            if (pids.includes(parseInt(id)) == false) {
                $('.product_error').html('');

                pids.push(parseInt(id));

                var inp = '<input type="hidden" name="pid[]" value="' + id + '">';
                var taxability = '<input type="hidden" name="taxability[]" value="' + suggestion
                    .taxability + '">';

                var tds = '<tr class="item_row">';
                tds += '<td><a class="tx-danger btnDelete" data-id="' + id +
                    '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
                tds += '<td>' + suggestion.name + inp + taxability + '</td>';
                tds +=
                    '<td><input class="form-control input-sm" value="0" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

                if (suggestion.taxability == 'N/A') {
                    tds += '<td><input class="form-control input-sm" value="0" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="igst_amt[]" value="0"></td>';
                    tds += '<td><input class="form-control input-sm" value="0" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="cgst_amt[]" value="0"></td>';
                    tds += '<td><input class="form-control input-sm" value="0" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text" readonly><input type="hidden" name="sgst_amt[]" value="0"></td>';

                } else {
                    var igst_amt = '<input name="igst_amt[]" value="" type="hidden">';
                    var cgst_amt = '<input name="cgst_amt[]" value="" type="hidden">';
                    var sgst_amt = '<input name="sgst_amt[]" value="" type="hidden">';
                    tds += '<td><input class="form-control input-sm" value="' + suggestion
                        .igst +
                        '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" onkeyup="calc_gst_per(this)" type="text">' +
                        igst_amt + '</td>';

                    tds += '<td><input class="form-control input-sm" value="' + suggestion
                        .cgst +
                        '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                        cgst_amt + '</td>';


                    tds += '<td><input class="form-control input-sm" value="' + suggestion
                        .sgst +
                        '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text">' +
                        sgst_amt + '</td>';

                }
                tds +=
                    '<td><input class="form-control input-sm" name="subtotal[]" onchange="calculate()" value="0" required="" type="text" readonly></td>';
                tds +=
                    '<td><input class="form-control input-sm" name="remark[]" placeholder="Remark" type="text"><input type="hidden" name="item_discount_hidden[]" class="hidden_discount"><input type="hidden" name="item_added_amt_hidden[]" class="hidden_added_amt"></td>';
                tds += '</tr>';

                $('.tbody').append(tds);
                $('#code').val('');

                calculate();
            } else {
                $('.product_error').html('Selected Product Already Added');
                $('#code').val('');
            }
        }
    // end add plus button create option append data 
</script>
<?= $this->endSection() ?>