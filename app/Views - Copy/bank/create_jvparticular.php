<?=$this->extend(THEME . 'templete')?>

<?=$this->section('content')?>
<style>
.remove {
    width: 22px;
    padding-top: 32px;
    padding-left: 11px;

}
</style>
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Bank </a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="<?=url('bank/add_jvparticular')?>" class="ajax-form-submit" method="post"
                            enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label">Voucher No.: <span
                                                    class="tx-danger">*</span></label>
                                            <input class="form-control" readonly type="text"
                                                value="<?=@$jvparticular[0]['jv_id'] ? $jvparticular[0]['jv_id'] : $current_id;?>">
                                            <input name="jv_id"
                                                value="<?=@$jvparticular[0]['jv_id'] ? $jvparticular[0]['jv_id'] : $current_id;?>"
                                                type="hidden">
                                        </div>

                                        <div class="col-lg-4  form-group">
                                            <label class="form-label">Date<span class="tx-danger">*</span></label>
                                            <input class="form-control fc-datepicker" name="date"
                                                value="<?=@$jvparticular['date'] ? $jvparticular['date'] : date('Y-m-d');?>"
                                                placeholder="MM/DD/YYYY" type="text" required>
                                            <input name="id" value="<?=@$jvparticular['id']?>" type="hidden">
                                        </div>
                                    </div>
                                    <div class="tbody">
                                        <?php
                                        if (isset($jvparticular)) {
                                            for ($i = 0; $i < count($jvparticular); $i++) { ?>
                                        <div class="row jv_parti">
                                            <?php if ($i > 0) {?>
                                            <div class="remove">
                                                <a class="tx-danger btnDelete" onclick="delete_row(this)" data-id="2"
                                                    title="0"><i class="fa fa-times tx-danger"></i></a>
                                            </div>
                                            <?php } else {?>
                                            <div class="remove">

                                            </div>
                                            <?php }?>
                                            <div class="col-lg-2 form-group">

                                                <label class="form-label">DR/CR: <span class="tx-danger"></span></label>
                                                <div class="input-group">
                                                    <select class="form-control select2" name="dr_cr[]" required>
                                                        <option value="">None</option>
                                                        <option
                                                            <?=(@$jvparticular[$i]['dr_cr'] == "dr" ? 'selected' : '')?>
                                                            value="dr">DR</option>
                                                        <option
                                                            <?=(@$jvparticular[$i]['dr_cr'] == "cr" ? 'selected' : '')?>
                                                            value="cr">CR</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-2 form-group">
                                                <label class="form-label">Particular: <span
                                                        class="tx-danger">*</span></label>

                                                <div class="input-group">
                                                    <select class="form-control particular" name="particular[]">
                                                        <?php if (@$jvparticular[$i]['particular_name']) {?>
                                                        <option selected value="<?=@$jvparticular[$i]['particular']?>">
                                                            <?=@$jvparticular[$i]['particular_name']?>
                                                        </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-2 form-group">
                                                <label class="form-label">Amount: <span
                                                        class="tx-danger">*</span></label>
                                                <input class="form-control" name="amount[]" type="text" required
                                                    placeholder="Enter Amount"
                                                    value="<?=@$jvparticular[$i]['amount']?>">
                                            </div>


                                            <div class="col-lg-2 form-group">
                                                <label class="form-label">Method: <span
                                                        class="tx-danger"></span></label>
                                                <div class="input-group">
                                                    <select class="form-control adjustment" onchange="afterload(this)"
                                                        name="adj_method[]" required>
                                                        <option value="">None</option>
                                                        <option
                                                            <?=(@$jvparticular[$i]['method'] == "Advanced" ? 'selected' : '')?>
                                                            value="Advanced">Advanced</option>
                                                        <option
                                                            <?=(@$jvparticular[$i]['method'] == "agains_reference" ? 'selected' : '')?>
                                                            value="agains_reference">Agains Reference</option>
                                                        <option
                                                            <?=(@$jvparticular[$i]['method'] == "new_reference" ? 'selected' : '')?>
                                                            value="new_reference">New References</option>
                                                        <option
                                                            <?=(@$jvparticular[$i]['method'] == "on_account" ? 'selected' : '')?>
                                                            value="on_account">On Account</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 form-group invoice_div"
                                                style=<?php echo !empty($jvparticular[$i]['invoice']) ? '"display:block;"' : '"display:none;" disabled'; ?>>
                                                <label class=" form-label">Select Invoice : <span
                                                        class="tx-danger"></span></label>
                                                <div class="input-group">
                                                    <select class="form-control invoices"
                                                        <?=!empty($jvparticular[$i]['invoice']) ? '' : 'disabled ';?>
                                                        name="invoice[]" >
                                                        <?php if (@$jvparticular[$i]['invoice_name']) {?>
                                                        <option selected value="<?=@$jvparticular[$i]['invoice']?>">
                                                            <?=@$jvparticular[$i]['invoice_name']?>
                                                        </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <input type="hidden" name="invoice_tb[]" id="invoice_tb"
                                                    value="<?=@$jvparticular[$i]['invoice_tb']?>">
                                            </div>

                                            <div class="col-lg-2 form-group advance_for"
                                                style="display:<?php echo (@$jvparticular[$i]['advance_for'] != 0) ? 'block;' : 'none;'; ?>">
                                                <label class=" form-label">Advance For : <span
                                                        class="tx-danger"></span></label>
                                                <div class="input-group">
                                                    <select class="form-control advance_ac" name="advance_for[]">
                                                        <?php if (@$jvparticular[$i]['advance_for']) {?>
                                                        <option selected value="<?=@$jvparticular[$i]['advance_for']?>">
                                                            <?=@$jvparticular[$i]['advance_name']?>
                                                        </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <?php }
                                        } else {?>
                                        <div class="row jv_parti">
                                            <div class="remove">

                                            </div>
                                            <div class="col-lg-2 form-group">
                                                <label class="form-label">DR/CR: <span class="tx-danger"></span></label>
                                                <div class="input-group">
                                                    <select class="form-control select2" onchange="calculate()"
                                                        name="dr_cr[]" required>
                                                        <option value="">None</option>
                                                        <option selected
                                                            <?=(@$jvparticular['dr_cr'] == "dr" ? 'selected' : '')?>
                                                            value="dr">DR</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-2 form-group">
                                                <label class="form-label">Particular: <span
                                                        class="tx-danger">*</span></label>
                                                <div class="input-group">
                                                    <select class="form-control particular" name="particular[]">
                                                        <?php if (@$jvparticular['particular_name']) {?>
                                                        <option value="<?=@$jvparticular['particular']?>">
                                                            <?=@$jvparticular['particular_name']?>
                                                        </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-2 form-group">
                                                <label class="form-label">Amount: <span
                                                        class="tx-danger">*</span></label>
                                                <input class="form-control" name="amount[]" onchange="calculate()"
                                                    type="text" required placeholder="Enter Amount"
                                                    value="<?=@$jvparticular['amount']?>">
                                            </div>


                                            <div class="col-lg-2 form-group">
                                                <label class="form-label">Method: <span
                                                        class="tx-danger"></span></label>
                                                <div class="input-group">
                                                    <select class="form-control adjustment" onchange="afterload(this)"
                                                        name="adj_method[]" required>
                                                        <option value="">None</option>
                                                        <option
                                                            <?=(@$jvparticular['method'] == "Advanced" ? 'selected' : '')?>
                                                            value="Advanced">Advanced</option>
                                                        <option
                                                            <?=(@$jvparticular['method'] == "agains_reference" ? 'selected' : '')?>
                                                            value="agains_reference">Agains Reference</option>
                                                        <option
                                                            <?=(@$jvparticular['method'] == "new_reference" ? 'selected' : '')?>
                                                            value="new_reference">New References</option>
                                                        <option
                                                            <?=(@$jvparticular['method'] == "on_account" ? 'selected' : '')?>
                                                            value="on_account">On Account</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 form-group invoice_div"
                                                style="display:<?php echo isset($jvparticular['invoice']) ? 'block;' : 'none;'; ?>">
                                                <label class=" form-label">Select Invoice : <span
                                                        class="tx-danger"></span></label>
                                                <div class="input-group">
                                                    <select class="form-control invoices" name="invoice[]">
                                                        <?php if (@$jvparticular['invoice_name']) {?>
                                                        <option selected value="<?=@$jvparticular['invoice']?>">
                                                            <?=@$jvparticular['invoice_name']?>
                                                        </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-2 form-group advance_for"
                                                style="display:<?php echo isset($jvparticular['advance_for']) ? 'block;' : 'none;'; ?>">
                                                <label class=" form-label">Advance For : <span
                                                        class="tx-danger"></span></label>
                                                <div class="input-group">
                                                    <select class="form-control advance_ac" name="advance_for[]">
                                                        <?php if (@$jvparticular['advance_for']) {?>
                                                        <option selected value="<?=@$jvparticular['advance_for']?>">
                                                            <?=@$jvparticular['advance_name']?>
                                                        </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-1 form-group tax"
                                                style="display:<?php echo isset($jvparticular['tax']) ? 'block;' : 'none;'; ?>">
                                                <label class=" form-label">Tax (%) : <span
                                                        class="tx-danger"></span></label>
                                                <div class="input-group">
                                                    <input class="form-control" name="tax[]" type="text"
                                                        placeholder="Enter TAX" value="<?=@$jvparticular['tax']?>">
                                                </div>
                                            </div>
                                        </div>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-sm-6">
                                    <p class="text-left">
                                        <button class="btn btn-space btn-primary" id="save_data"
                                            type="submit">Submit</button>
                                        <button class="btn btn-space btn-primary" type="button"
                                            onclick="addinput()">Add</button>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label class="custom-switch">
                                                <input type="checkbox" name="stat_adj" onchange="check_stat()"
                                                    class="custom-switch-input"
                                                    <?=(@$jvparticular[0]['stat_adj'] == "1" ? 'checked' : '')?>
                                                    value="<?=@$jvparticular[0]['stat_adj']?>">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Stat Adjustment</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row stat_div"
                                        style="display:<?=(@$jvparticular[0]['stat_adj'] == 1) ? 'flex;' : 'none;'?>">
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Type of Duty Tax label: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="duty_tax">
                                                    <option
                                                        <?=(@$jvparticular[0]['duty_tax'] == "Gst" ? 'selected' : '')?>
                                                        value="Gst">Gst</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Nature Of Adjustment: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="adjust">
                                                    <option <?=(@$jvparticular[0]['adjust'] == "1" ? 'selected' : '')?>
                                                        value="1">Decrease of Tax Liability</option>
                                                    <option <?=(@$jvparticular[0]['adjust'] == "2" ? 'selected' : '')?>
                                                        value="2">Increase of Tax Liability</option>
                                                    <option <?=(@$jvparticular[0]['adjust'] == "3" ? 'selected' : '')?>
                                                        value="3">Increase of Input Tax Credit</option>
                                                    <option <?=(@$jvparticular[0]['adjust'] == "4" ? 'selected' : '')?>
                                                        value="4">Increase of Tax Liability & Input Tax Credit</option>
                                                    <option <?=(@$jvparticular[0]['adjust'] == "5" ? 'selected' : '')?>
                                                        value="5">Refund</option>
                                                    <option <?=(@$jvparticular[0]['adjust'] == "6" ? 'selected' : '')?>
                                                        value="6">Reversal Of Input Tax Credit</option>
                                                    <option <?=(@$jvparticular[0]['adjust'] == "7" ? 'selected' : '')?>
                                                        value="7">Reversal Of Input Tax Liability</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group decrease_tax" style="display:<?=(@$jvparticular[0]['adjust'] == 1) ? 'flex;' : 'none;'?>">
                                            <label class="form-label">Additional Detail: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="addi_detail">
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "1" ? 'selected' : '')?>
                                                        value="1">Adjustment Againts Credit</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "2" ? 'selected' : '')?>
                                                        value="2">Cancellation Of Advance Payment Under Reverse charge</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "3" ? 'selected' : '')?>
                                                        value="3">Cancellation Of Advance Receipt</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "4" ? 'selected' : '')?>
                                                        value="4">Cancellation Of Advance Receipt for Export/SEZ Sales</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "5" ? 'selected' : '')?>
                                                        value="5">Purchase Againts Advance Receipt</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "5" ? 'selected' : '')?>
                                                        value="5">Purchase Againts Advance Receipt</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "6" ? 'selected' : '')?>
                                                        value="6">Sales Againts Advance Receipt</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "6" ? 'selected' : '')?>
                                                        value="6">Sales Againts Advance Receipt For Export/SEZ Sales</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group increase_credit" style="display:<?=(@$jvparticular[0]['adjust'] == 3) ? 'flex;' : 'none;'?>>
                                            <label class="form-label">Additional Detail: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="addi_detail">
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "1" ? 'selected' : '')?>
                                                        value="1">Import Of Capital Goods</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "2" ? 'selected' : '')?>
                                                        value="2">Import Of Goods</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "3" ? 'selected' : '')?>
                                                        value="3">Import  Of Service </option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "4" ? 'selected' : '')?>
                                                        value="4">ISD Transfer</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "5" ? 'selected' : '')?>
                                                        value="5">Other</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "6" ? 'selected' : '')?>
                                                        value="6">Purchase From SEZ </option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "7" ? 'selected' : '')?>
                                                        value="7">Purchase Under Reverse Charge</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "8" ? 'selected' : '')?>
                                                        value="8">Re-Claim Of Reversal ITC </option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "8" ? 'selected' : '')?>
                                                        value="8">Re-Claim Of Reversal ITC -Rule 42(2)(b) </option>    
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "9" ? 'selected' : '')?>
                                                        value="9">TCS Adjustment</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "10" ? 'selected' : '')?>
                                                        value="10">TDS Adjustment</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "11" ? 'selected' : '')?>
                                                        value="11">Transitional Credit</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group increase_liability" style="display:<?=(@$jvparticular[0]['adjust'] == 2) ? 'flex;' : 'none ;'?>">
                                            <label class="form-label">Additional Detail: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="addi_detail">
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "1" ? 'selected' : '')?>
                                                        value="1">Advance Receipt For Export/SEZ sales</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "2" ? 'selected' : '')?>
                                                        value="2">Advance Paid Under Reverse Charge</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "3" ? 'selected' : '')?>
                                                        value="3">Import of Capital Goods</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "4" ? 'selected' : '')?>
                                                        value="4">Import Of Service</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "5" ? 'selected' : '')?>
                                                        value="5">Interest</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "6" ? 'selected' : '')?>
                                                        value="6">Late Fees</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "7" ? 'selected' : '')?>
                                                        value="7">On Account Of Advance Receipts</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "8" ? 'selected' : '')?>
                                                        value="8">Other</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "9" ? 'selected' : '')?>
                                                        value="9">Penalty</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "10" ? 'selected' : '')?>
                                                        value="10">Purchase Under Reverse Charge</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group tax_credit" style="display:<?=(@$jvparticular[0]['adjust'] == 8) ? 'flex;' : 'none ;'?>">
                                            <label class="form-label">Additional Detail: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="addi_detail">
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "1" ? 'selected' : '')?>
                                                        value="1">Import Of Service</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "2" ? 'selected' : '')?>
                                                        value="2">Purchase Under Reverse Charge</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group refund" style="display:<?=(@$jvparticular[0]['adjust'] == 5) ? 'flex;' : 'none ;'?>">
                                            <label class="form-label">Additional Detail: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="addi_detail">
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "1" ? 'selected' : '')?>
                                                        value="1">Not Applicable</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "2" ? 'selected' : '')?>
                                                        value="2">Interest</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "3" ? 'selected' : '')?>
                                                        value="3">Late Fees</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "4" ? 'selected' : '')?>
                                                        value="4">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group reversal_credit" style="display:<?=(@$jvparticular[0]['adjust'] == 6) ? 'flex;' : 'none ;'?>">
                                            <label class="form-label">Additional Detail: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="addi_detail">
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "1" ? 'selected' : '')?>
                                                        value="1">Not Applicable</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "2" ? 'selected' : '')?>
                                                        value="2">Capital Credit Due to exempted supplies -Rule 43(1)(h)</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "3" ? 'selected' : '')?>
                                                        value="3">Exempt and non-business supplies -Rule 42(1)(m)</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "4" ? 'selected' : '')?>
                                                        value="4">Import Of Service</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "5" ? 'selected' : '')?>
                                                        value="5">Ineligible Credit</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "6" ? 'selected' : '')?>
                                                        value="6">ISD Credit Note -Rule (39) </option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "7" ? 'selected' : '')?>
                                                        value="7">Non Payment To The Supplier</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "8" ? 'selected' : '')?>
                                                        value="8">On Account Of Claiming More</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "9" ? 'selected' : '')?>
                                                        value="9">Others</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "10" ? 'selected' : '')?>
                                                        value="10">Purchase Under Reverse Charge</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group reversal_liability" style="display:<?=(@$jvparticular[0]['adjust'] == 7) ? 'flex;' : 'none ;'?>">
                                            <label class="form-label">Additional Detail: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="addi_detail">
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "1" ? 'selected' : '')?>
                                                        value="1">Import Of Service</option>
                                                    <option <?=(@$jvparticular[0]['addi_detail'] == "2" ? 'selected' : '')?>
                                                        value="2">Purchase Under Reverse charge</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="custom-switch">
                                                <input type="checkbox" name="gst_detail" onchange="check_gst_detail()"
                                                    class="custom-switch-input"
                                                    <?=(@$jvparticular[0]['gst_detail'] == "1" ? 'checked' : '')?>
                                                    value="<?=@$jvparticular[0]['gst_detail']?>">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Provide Gst Detail</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row gst_detail_div"
                                        style="display:<?=(@$jvparticular[0]['gst_detail'] == 1) ? 'flex;' : 'none;'?>">
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Select Account : <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control gst_parti" name="gst_parti">
                                                    <?php if (@$jvparticular[0]['gst_parti']) {?>
                                                    <option selected value="<?=@$jvparticular[0]['gst_parti']?>">
                                                        <?=@$jvparticular[0]['gst_parti_name']?>
                                                    </option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group bank_tras_div" style="display:<?=!empty(@$jvparticular[0]['gst_parti']) ? 'block;' : 'none;' ?>">
                                            <label class="form-label">Select Bank Trans : <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control bank_tras" name="bank_tras">
                                                    <?php if (@$jvparticular[0]['bank_tras']) {?>
                                                    <option selected value="<?=@$jvparticular[0]['bank_tras']?>">
                                                        <?=@$jvparticular[0]['bank_tras_name']?>
                                                    </option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Place of Supply: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <input class="form-control" name="supply" value="<?=@$jvparticular[0]['supply']?>"  placeholder = "Place of Supply">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Registration type: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="registration">
                                                    <option <?=(@$jvparticular[0]['registration'] == "Regular" ? 'selected' : '')?>
                                                        value="Regular">Regular</option>
                                                    <option <?=(@$jvparticular[0]['registration'] == "Unregister" ? 'selected' : '')?>
                                                        value="Unregister">Unregister</option>
                                                    <option <?=(@$jvparticular[0]['registration'] == "Composition" ? 'selected' : '')?>
                                                        value="Composition">Composition</option>
                                                    <option <?=(@$jvparticular[0]['registration'] == "Unknown" ? 'selected' : '')?>
                                                        value="Unknown">Unknown</option>
                                                    <option <?=(@$jvparticular[0]['registration'] == "Consumer" ? 'selected' : '')?>
                                                        value="Consumer">Consumer</option>
                                                    <option <?=(@$jvparticular[0]['registration'] == "Other" ? 'selected' : '')?>
                                                        value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Party Type: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <select class="form-control select2" name="party_type">
                                                    <option <?=(@$jvparticular[0]['party_type'] == "1" ? 'selected' : '')?>
                                                        value="1">Not Applicable</option>
                                                    <option <?=(@$jvparticular[0]['party_type'] == "2" ? 'selected' : '')?>
                                                        value="2">Deemed Export</option>
                                                    <option <?=(@$jvparticular[0]['party_type'] == "3" ? 'selected' : '')?>
                                                        value="3">Embassy/UN Body</option>
                                                    <option <?=(@$jvparticular[0]['party_type'] == "4" ? 'selected' : '')?>
                                                        value="3">SEZ</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">GST: <span
                                                    class="tx-danger"></span></label>
                                            <div class="input-group">
                                                <input class="form-control" name="gst" value="<?=@$jvparticular[0]['gst']?>"  placeholder = "Place of Supply">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive col-md-6 mt-25">
                                    <table class="table table-hover table-bordered table-fw-widget">
                                        <tr>
                                            <th>DIFF</th>
                                            <th>DR</th>
                                            <th>CR</th>
                                        </tr>
                                        <tr>
                                            <td id="diff"></td>
                                            <td id="tot_dr"></td>
                                            <td id="tot_cr"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?=$this->endSection()?>

<?=$this->section('scripts')?>
<script>
<?php
if (isset($jvparticular)) {?>
calculate();
<?php }?>


function check_stat() {
    if ($('input[name="stat_adj"]').is(':checked')) {
        $('input[name="stat_adj"]').val('1');
        $('.stat_div').css('display', 'flex');
  
    } else {
        $('.stat_div').css('display', 'none');
    }
}

function check_gst_detail() {
    if ($('input[name="gst_detail"]').is(':checked')) {
        $('input[name="gst_detail"]').val('1');
        $('.gst_detail_div').css('display', 'flex');
        
    } else {
        $('.gst_detail_div').css('display', 'none');
    }
}


function calculate() {

    var dr_cr = $('select[name="dr_cr[]"]').map(function() {
        return (this.value); // $(this).val()
    }).get();

    var amount = $('input[name="amount[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var dr_total = 0.0;
    var cr_total = 0.0;
    var tot_diff = 0.0;

    for (var i = 0; i < amount.length; i++) {
        if (dr_cr[i] == 'dr') {
            dr_total += amount[i];
        }
        if (dr_cr[i] == 'cr') {
            cr_total += amount[i];
        }
    }

    tot_diff = dr_total - cr_total;

    $('#tot_dr').html(dr_total);
    $('#diff').html(tot_diff);
    $('#tot_cr').html(cr_total);

    if (tot_diff == 0) {
        $('#save_data').prop('disabled', false);
    } else {
        $('#save_data').prop('disabled', true);
    }

}


function afterload(obj) {
    $(obj).on('select2:select', function(e) {
        var adjust = $(this).val();
        var particular_id = $(this).closest('.jv_parti').find('select[name="particular[]"]').val();
        var dr_cr = $(this).closest('.jv_parti').find('select[name="dr_cr[]"]').val();
        var particular_name = $(this).closest('.jv_parti').find('select[name="particular[]"]').text();

        var invoice_div = $(this).closest('.jv_parti').find('.invoice_div');
        var advance_for = $(this).closest('.jv_parti').find('.advance_for');
        var tax = $(this).closest('.jv_parti').find('.tax');
        if (adjust == 'agains_reference') {
            invoice_div.css('display', 'block');
            advance_for.css('display', 'none');
            $(this).closest('.jv_parti').find('select[name="invoice[]"]').attr('disabled', false);
            // _data = $.param({
            //     id: particular_id
            // });

            if (particular_id != undefined && particular_id != '') {
                $(this).closest('.jv_parti').find('select[name="invoice[]"]').select2({
                    width: '100%',
                    placeholder: 'Choose Invoice',
                    // minimumInputLength: 1,
                    ajax: {
                        url: PATH + "bank/getdata/search_invoice",
                        type: "post",
                        allowClear: true,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                id: particular_id,
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
        } else if (adjust == 'Advanced') {
            advance_for.css('display', 'block');
            invoice_div.css('display', 'none');

            $(this).closest('.jv_parti').find('select[name="advance_for[]"]').attr('disabled', false);

            $(this).closest('.jv_parti').find('select[name="advance_for[]"]').select2({
                width: '100%',
                placeholder: 'Choose Account',
                // minimumInputLength: 1,
                ajax: {
                    url: PATH + "Master/Getdata/search_bank_particular",
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
            particular_name = $.trim(particular_name);

            if (dr_cr == 'cr' && particular_name == 'igst' || particular_name == 'cgst' || particular_name ==
                'sgst' || particular_name == 'cess') {
                tax.css('display', 'block');
            }

        } else {

            invoice_div.css('display', 'none');
            $(this).closest('.jv_parti').find('select[name="invoice[]"]').attr('disabled', true);
            advance_for.css('display', 'none');
            tax.css('display', 'none');
            $(this).closest('.jv_parti').find('select[name="advance_for[]"]').attr('disabled', true);

        }
    });
}

function repeat() {

    $('select[name="dr_cr[]"]').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });
    $('select[name="adj_method[]"]').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });
    $('select[name="invoice[]"]').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });

    
    $('select[name="particular[]"]').select2({
        width: '100%',
        placeholder: 'Type Particular',
        ajax: {
            url: PATH + "Master/Getdata/search_bank_particular",
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


    $('select[name="invoice[]"]').on('select2:select', function(e) {
        var data = e.params.data;
        console.log(data.table)
        $(this).closest('.jv_parti').find('input[name="invoice_tb[]"]').val(data.table);
    });

}

function addinput() {
    var html = '<div class="row jv_parti">'
    // html += '<div class="col-lg-2 form-group"> '
    html +=
        '<div class= "remove"><a class="tx-danger btnDelete" data-id="2" onclick="delete_row(this)" title="0"><i class="fa fa-times tx-danger"></i></a></div>';
    html +=
        '<div class="col-lg-2 form-group"><label class="form-label">DR/CR: <span class="tx-danger"></span></label><div class="input-group"><select class="form-control select2" onchange="calculate()" name="dr_cr[]" required><option value="">None</option><option <?=(@$jvparticular['dr_cr'] == "dr" ? 'selected' : '')?> value="dr" >DR</option><option <?=(@$jvparticular['dr_cr'] == "cr" ? 'selected' : '')?> value="cr">CR</option></select></div></div><br>';
    html +=
        '<div class="col-lg-2 form-group"> <label class="form-label">Particular: <span class="tx-danger">*</span></label> <div class="input-group"> <select class="form-control particular" name="particular[]"> <?php if (@$jvparticular['particular_name']) {?> <option value="<?=@$jvparticular['particular']?>"> <?=@$jvparticular['particular_name']?> </option> <?php }?> </select> </div> </div>';
    html +=
        '<div class="col-lg-2 form-group"><label class="form-label">Amount: <span class="tx-danger">*</span></label> <input class="form-control" onchange="calculate()" name="amount[]" type="text" required placeholder="Enter Amount" value="<?=@$jvparticular['amount']?>"> </div>';
    html +=
        '<div class="col-lg-2 form-group"><label class="form-label">Method: <span class="tx-danger"></span></label> <div class="input-group"> <select class="form-control adjustment"  onchange="afterload(this)" name="adj_method[]" required><option value="">None</option> <option <?=(@$jvparticular['method'] == "Advanced" ? 'selected' : '')?> value="Advanced">Advanced</option> <option <?=(@$jvparticular['method'] == "agains_reference" ? 'selected' : '')?> value="agains_reference">Agains Reference</option> <option <?=(@$jvparticular['method'] == "new_reference" ? 'selected' : '')?> value="new_reference">New References</option> <option <?=(@$jvparticular['method'] == "on_account" ? 'selected' : '')?>value="on_account">On Account</option> </select> </div></div>';
    html +=
        '<div class="col-lg-3 form-group invoice_div"  style="display:<?php !empty($jvparticular['invoice']) ? 'block;' : 'none;'?>""> <label class=" form-label">Select Invoice : <span class="tx-danger"></span></label> <div class="input-group"> <select class="form-control invoices"  name="invoice[]" > <?php if (@$jvparticular['invoice_name']) {?> <option selected value="<?=@$jvparticular['invoice']?>"> <?=@$jvparticular['invoice_name']?> </option> <?php }?> </select> <input type="hidden" name="invoice_tb[]" id="invoice_tb"></div></div>';
    html +=
        '<div class="col-lg-2 form-group advance_for" style="display:<?php echo isset($jvparticular['advance_for']) ? 'block;' : 'none;'; ?>"> <label class=" form-label">Advance For : <span class="tx-danger"></span></label><div class="input-group"><select class="form-control advance_ac" name="advance_for[]" > <?php if (@$jvparticular['advance_for']) {?><option selected value="<?=@$jvparticular['advance_for']?>"><?=@$jvparticular['advance_name']?></option><?php }?></select></div></div>';
    html +=
        '<div class="col-lg-1 form-group tax" style="display:<?php echo isset($jvparticular['tax']) ? 'block;' : 'none;'; ?>"> <label class=" form-label">Tax (%) : <span class="tx-danger"></span></label> <div class="input-group"><input class="form-control" name="tax[]" type="text"  placeholder="Enter TAX" value="<?=@$jvparticular['tax']?>"></div></div>';
    // html += '<div class="col-lg-2 form-group"></div>';
    html += '</div>';
    $('.tbody').append(html);
    repeat();
}

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
                window.location = "<?=url('Bank/jv_particular')?>"
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

function delete_row(obj) {
    $(obj).closest('.jv_parti').remove();
    calculate();
}
$(document).ready(function() {
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    repeat();


    $('select[name="addi_detail"]').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });
    
    $('select[name="adjust"]').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });
    
    $('select[name="duty_tax"]').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });

    $('select[name="registration"]').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });

    $('select[name="party_type"]').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });

    $('select[name="particular[]"]').select2({
        width: '100%',
        placeholder: 'Type Particular',
        ajax: {
            url: PATH + "Master/Getdata/search_bank_particular",
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
    $('select[name="bank_tras"]').select2({
        
        width: '100%',
        placeholder: 'Select Advance Voucher',
        ajax: {
            url: PATH + "Master/Getdata/advance_liability",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term,
                    id: $('select[name="gst_parti"]').val() 
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
    $('select[name="gst_parti"]').select2({
        width: '100%',
        placeholder: 'Type Particular',
        ajax: {
            url: PATH + "Master/Getdata/gst_parti",
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
    $('select[name="gst_parti"]').on('select2:select', function(e) {
        $('.bank_tras_div').css('display','block');
        var data = e.params.data;

        $('input[name="supply"]').val(data.state);
        $('input[name="gst"]').val(data.gsttin);
        $('select[name="supply"]').val(data.state);
        
        if(data.gst_type  == 'Regular'){
            $("select[name='registration'] option[value='Regular']").attr("selected", "selected");
        }else if(data.gst_type == 'Composition'){
            $("select[name='registration'] option[value='Composition']").attr("selected", "selected");
        }else if(data.gst_type == 'Unknown'){
            $("select[name='registration'] option[value='Unknown']").attr("selected", "selected");
        }else if(data.gst_type == 'Consumer'){
            $("select[name='registration'] option[value='Consumer']").attr("selected", "selected");
        }else if(data.gst_type == 'Other'){
            $("select[name='registration'] option[value='Other']").attr("selected", "selected");
        }else{

        }
    
    });

    $('select[name="adjust"]').on('select2:select', function(e) {
        
        var adjust = $('select[name="adjust"]').val();
        if(adjust == 1){
            $('.decrease_tax').css('display','flex');
            $('.increase_credit').css('display','none');
            $('.increase_liability').css('display','none');
            $('.tax_credit').css('display','none');
            $('.refund').css('display','none');
            $('.reversal_credit').css('display','none');
            $('.reversal_liability').css('display','none');
        }else if(adjust == 2){
            $('.decrease_tax').css('display','none');
            $('.increase_credit').css('display','none');
            $('.increase_liability').css('display','flex');
            $('.tax_credit').css('display','none');
            $('.refund').css('display','none');
            $('.reversal_credit').css('display','none');
            $('.reversal_liability').css('display','none');
        }else if(adjust == 3){
            $('.decrease_tax').css('display','none');
            $('.increase_credit').css('display','flex');
            $('.increase_liability').css('display','none');
            $('.tax_credit').css('display','none');
            $('.refund').css('display','none');
            $('.reversal_credit').css('display','none');
            $('.reversal_liability').css('display','none');
        }else if(adjust == 4){
            $('.decrease_tax').css('display','none');
            $('.increase_credit').css('display','none');
            $('.increase_liability').css('display','none');
            $('.tax_credit').css('display','flex');
            $('.refund').css('display','none');
            $('.reversal_credit').css('display','none');
            $('.reversal_liability').css('display','none');
        }else if(adjust == 5){
            $('.decrease_tax').css('display','none');
            $('.increase_credit').css('display','none');
            $('.increase_liability').css('display','none');
            $('.tax_credit').css('display','none');
            $('.refund').css('display','flex');
            $('.reversal_credit').css('display','none');
            $('.reversal_liability').css('display','none');
        }else if(adjust == 6){
            $('.decrease_tax').css('display','none');
            $('.increase_credit').css('display','none');
            $('.increase_liability').css('display','none');
            $('.tax_credit').css('display','none');
            $('.refund').css('display','none');
            $('.reversal_credit').css('display','flex');
            $('.reversal_liability').css('display','none');
        }else if(adjust == 7){
            $('.decrease_tax').css('display','none');
            $('.increase_credit').css('display','none');
            $('.increase_liability').css('display','none');
            $('.tax_credit').css('display','none');
            $('.refund').css('display','none');
            $('.reversal_credit').css('display','none');
            $('.reversal_liability').css('display','flex');
        }else{
            $('.decrease_tax').css('display','flex');
            $('.increase_credit').css('display','none');
            $('.increase_liability').css('display','none');
            $('.tax_credit').css('display','none');
            $('.refund').css('display','none');
            $('.reversal_credit').css('display','none');
            $('.reversal_liability').css('display','none');
        }
    });


});
</script>

<?=$this->endSection()?>