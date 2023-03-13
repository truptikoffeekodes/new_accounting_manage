<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.error {
    color: red;
}
</style>
<!--colorpicker css-->
<link href="<?=ASSETS?>/plugins/spectrum-colorpicker/spectrum.css" rel="stylesheet">


<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5"><?= $title ?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>
    <!-- End Page Header -->
    <!-- Row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <form action="<?=url('Account/add_daybook')?>" id="daybook" method="post">
                        <div id="wizard1">
                            <h3>General</h3>
                            <section>
                                <div class="row">
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Code: <span class="tx-danger">*</span></label>
                                        <input class="form-control" name="code" value="<?= @$daybook['code']; ?>"
                                            placeholder="Enter Code" required="" type="text">
                                        <input class="form-control" name="id" value="<?= @$daybook['id']; ?>"
                                            placeholder="id" type="hidden">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Name: <span class="tx-danger">*</span></label>
                                        <input class="form-control" name="name" value="<?= @$daybook['name']; ?>"
                                            placeholder="Enter Inventory Name" required="" type="text">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Short Name:</label>
                                        <input class="form-control" name="srtname"
                                            value="<?= @$daybook['short_name']; ?>" placeholder="Enter Short Name"
                                            type="text">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Print Name:</label>
                                        <input class="form-control" name="prtname"
                                            value="<?= @$daybook['print_name']; ?>" placeholder="Enter Print Name"
                                            type="text">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Default A/c: </label>
                                        <input class="form-control" name="default_ac"
                                            value="<?= @$daybook['default_ac']; ?>" placeholder="Enter Default A/C"
                                            type="text">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Freeze Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fe fe-calendar lh--9 op-6"></i>
                                                </div>
                                            </div>
                                            <input class="form-control fc-datepicker" placeholder="MM/DD/YYYY"
                                                type="text" id="freeze_date" name="freeze_date"
                                                value="<?= @$daybook['freeze_date']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Type: <span class="tx-danger">*</span></label>
                                        <div class="form-group">
                                            <select name="type" class="form-control select22" required>
                                                <option label="Select type"></option>
                                                <option
                                                    <?= ( @$daybook['type'] == "sales_inquery" ? 'selected' : '' ) ?>
                                                    value="sales_inquery">Sales Enquiry</option>
                                                <option <?= ( @$daybook['type'] == "sales_quote" ? 'selected' : '' ) ?>
                                                    value="sales_quote">Sales Quote</option>
                                                <option
                                                    <?= ( @$daybook['type'] == "sales_challan" ? 'selected' : '' ) ?>
                                                    value="sales_challan">Sales Challan</option>
                                                <option
                                                    <?= ( @$daybook['type'] == "sales_invoice" ? 'selected' : '' ) ?>
                                                    value="sales_invoice">Sales Invoice</option>
                                                <option <?= ( @$daybook['type'] == "sales_return" ? 'selected' : '' ) ?>
                                                    value="sales_return">Sales Return</option>
                                                <option
                                                    <?= ( @$daybook['type'] == "purchase_enquiry" ? 'selected' : '' ) ?>
                                                    value="purchase_enquiry">Purchase Enquiry</option>
                                                <option
                                                    <?= ( @$daybook['type'] == "purchase_quote" ? 'selected' : '' ) ?>
                                                    value="purchase_quote">Purchase Quote</option>
                                                <option
                                                    <?= ( @$daybook['type'] == "purchase_challan" ? 'selected' : '' ) ?>
                                                    value="purchase_challan">Purchase Challan</option>
                                                <option
                                                    <?= ( @$daybook['type'] == "purchase_invoice" ? 'selected' : '' ) ?>
                                                    value="purchase_invoice">Purchase Invoice</option>
                                                <option
                                                    <?= ( @$daybook['type'] == "purchase_order" ? 'selected' : '' ) ?>
                                                    value="purchase_order">Purchase Order</option>
                                                <option
                                                    <?= ( @$daybook['type'] == "purchase_return" ? 'selected' : '' ) ?>
                                                    value="purchase_return">Purchase Return</option>
                                                <option <?= ( @$daybook['type'] == "cash" ? 'selected' : '' ) ?>
                                                    value="cash">Cash</option>
                                                <option <?= ( @$daybook['type'] == "bank" ? 'selected' : '' ) ?>
                                                    value="bank">Bank</option>
                                                <option <?= ( @$daybook['type'] == "petty_cash" ? 'selected' : '' ) ?>
                                                    value="petty_cash">Petty Cash</option>
                                                <option <?= ( @$daybook['type'] == "debit_note" ? 'selected' : '' ) ?>
                                                    value="debit_note">Debit Note</option>
                                                <option <?= ( @$daybook['type'] == "credit_note" ? 'selected' : '' ) ?>
                                                    value="credit_note">Credit Note</option>
                                                <option
                                                    <?= ( @$daybook['type'] == "journal_voucher" ? 'selected' : '' ) ?>
                                                    value="journal_voucher">Journal Voucher</option>
                                                <option
                                                    <?= ( @$daybook['type'] == "inventory_issue" ? 'selected' : '' ) ?>
                                                    value="inventory_issue">Inventory Issue</option>
                                                <option
                                                    <?= ( @$daybook['type'] == "inventory_receipt" ? 'selected' : '' ) ?>
                                                    value="inventory_receipt">Inventory Receipt</option>
                                                <option <?= ( @$daybook['type'] == "other_book" ? 'selected' : '' ) ?>
                                                    value="other_book">Other Book</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Default Txn Date: </label>
                                        <div class="form-group">
                                            <select name="defaultdate" class="form-control select22">
                                                <option value="">None</option>
                                                <option
                                                    <?= ( @$daybook['default_txn_date'] == "current_date" ? 'selected' : '' ) ?>
                                                    value="current_date">Current Date</option>
                                                <option
                                                    <?= ( @$daybook['default_txn_date'] == "last_document_date" ? 'selected' : '' ) ?>
                                                    value="last_document_date">Last Document Date</option>
                                                <option
                                                    <?= ( @$daybook['default_txn_date'] == "blank" ? 'selected' : '' ) ?>
                                                    value="blank">Blank</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Purchase Invoice: </label>
                                        <div class="custom-file">
                                            <input class="custom-file-input" value="" name="rel_attach" id="customFile"
                                                type="file"> <label class="custom-file-label" for="customFile">Choose
                                                file</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Force Odered Date:</label>
                                        <select name="force_odered_date" class="form-control select22">
                                            <option value="">None</option>
                                            <option <?= ( @$daybook['force_order_date'] == "yes" ? 'selected' : '' ) ?>
                                                value="yes">Yes</option>
                                            <option <?= ( @$daybook['force_order_date'] == "no" ? 'selected' : '' ) ?>
                                                value="no">No</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Prompt brfore calling:</label>
                                        <select name="prompt_before_calling" class="form-control select22">
                                            <option value="">None</option>
                                            <option
                                                <?= ( @$daybook['prompt_before_calling'] == "yes" ? 'selected' : '' ) ?>
                                                value="yes">Yes</option>
                                            <option
                                                <?= ( @$daybook['prompt_before_calling'] == "no" ? 'selected' : '' ) ?>
                                                value="no">No</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Auto Call O/s Adj.:</label>
                                        <select name="Auto_call_osadj" class="form-control select22">
                                            <option value="">None</option>
                                            <option <?= ( @$daybook['auto_call_osadj'] == "yes" ? 'selected' : '' ) ?>
                                                value="yes">Yes</option>
                                            <option <?= ( @$daybook['auto_call_osadj'] == "no" ? 'selected' : '' ) ?>
                                                value="no">No</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Order Bk Code:</label>
                                        <input class="form-control" type="text" name="order_bkcode"
                                            placeholder="Enter Order Bk Code"
                                            value="<?= @$daybook['order_bk_code']; ?>">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Copy From Company:</label>
                                        <input class="form-control" type="text" name="copy_from_company"
                                            placeholder="Enter Copy From Company"
                                            value="<?= @$daybook['copy_from_company']; ?>">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Copy Daybook From: </label>
                                        <div class="form-group">
                                            <select name="copy_daybook_from" class="form-control select22">
                                                <option value="None">None</option>
                                                <option
                                                    <?= ( @$daybook['copy_daybook_from'] == "sales_other" ? 'selected' : '' ) ?>
                                                    value="sales_other">Sales Other</option>
                                                <option
                                                    <?= ( @$daybook['copy_daybook_from'] == "sales_invoice" ? 'selected' : '' ) ?>
                                                    value="sales_invoice">Sales Invoice</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Sub Type: <span class="tx-danger">*</span></label>
                                        <div class="form-group">
                                            <select name="subtype" class="form-control select22">
                                                <option <?= ( @$daybook['sub_type'] == "normal" ? 'selected' : '' ) ?>
                                                    value="normal">Normal</option>
                                                <option <?= ( @$daybook['sub_type'] == "job_work" ? 'selected' : '' ) ?>
                                                    value="job_work">Job Work</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Entry From Type: </label>
                                        <div class="form-group">
                                            <select name="entry_from_type" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['entry_from_type'] == "normal" ? 'selected' : '' ) ?>
                                                    value="normal">Normal</option>
                                                <option
                                                    <?= ( @$daybook['entry_from_type'] == "job_work" ? 'selected' : '' ) ?>
                                                    value="job_work">Job Work</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Account: <span class="tx-danger">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="account" id="account"
                                                onchange="validate_autocomplete(this,'account_id')"
                                                placeholder="Select Account Name" value="<?= @$daybook['account']; ?>"
                                                required>
                                        </div>
                                        <input type="hidden" name="account_id" id="account_id"
                                            value="<?= @$daybook['account_id']; ?>">
                                        <div class="dz-error-message tx-danger account_id"></div>
                                    </div>


                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Status <span class="tx-danger">*</span></label>
                                        <select name="status" class="form-control select22" required>
                                            <option label="Select type">
                                            </option>
                                            <option <?= ( @$daybook['status'] == "1" ? 'selected' : '' ) ?> value="1">
                                                Active</option>
                                            <option <?= ( @$daybook['status'] == "0" ? 'selected' : '' ) ?> value="0">
                                                Deactivate</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Notes:</label>
                                        <input class="form-control form-control-sm" type="text"
                                            value="<?= @$daybook['notes']; ?>" placeholder="Enter Notes" name="notes"
                                            id="notes">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Challan Bk code:</label>
                                        <input class="form-control form-control-sm" type="text"
                                            value="<?= @$daybook['challan_bk_code']; ?>" placeholder="Challan Book Code"
                                            name="chalan_bk_code" id="chalan_bk_code" formnovalidate>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Get Online Payment Detail:</label>
                                        <select name="get_onlinepayment_detail" class="form-control select22">
                                            <option value="">None</option>
                                            <option
                                                <?= ( @$daybook['get_onlinepayment_detail'] == "yes" ? 'selected' : '' ) ?>
                                                value="yes">Yes</option>
                                            <option
                                                <?= ( @$daybook['get_onlinepayment_detail'] == "no" ? 'selected' : '' ) ?>
                                                value="no">No</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Get Online Cash Back:</label>
                                        <select name="get_online_caseback" class="form-control select22">
                                            <option value="">None</option>
                                            <option
                                                <?= ( @$daybook['get_online_caseback'] == "yes" ? 'selected' : '' ) ?>
                                                value="yes">Yes</option>
                                            <option
                                                <?= ( @$daybook['get_online_caseback'] == "no" ? 'selected' : '' ) ?>
                                                value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                            </section>
                            <h3>Inventory</h3>
                            <section>
                                <div class="row">
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Allow Zero Amount : </label>
                                        <div class="form-group">
                                            <select name="zero_amount" class="form-control select2">
                                                <option
                                                    <?= ( @$daybook['allow_zero_amount'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option
                                                    <?= ( @$daybook['allow_zero_amount'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Allow Edit Line-Item Amount : </label>
                                        <div class="form-group">
                                            <select name="lineitem_amt" class="form-control select22 ">
                                                <option
                                                    <?= ( @$daybook['allow_edit_lineitemamount'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option
                                                    <?= ( @$daybook['allow_edit_lineitemamount'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Allow Zero Quantity : </label>
                                        <div class="form-group">
                                            <select name="zero_qty" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['allow_zero_quentity'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option
                                                    <?= ( @$daybook['allow_zero_quentity'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Get Transport Detail : </label>
                                        <div class="form-group">
                                            <select name="transport_detail" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['get_transpoert_detail'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option
                                                    <?= ( @$daybook['get_transpoert_detail'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Get Payment Detail : </label>
                                        <div class="form-group">
                                            <select name="payment_detail" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['get_payment_detail'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option
                                                    <?= ( @$daybook['get_payment_detail'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Get Line Item Detail : </label>
                                        <div class="form-group">
                                            <select name="lineitem_detail" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['get_lineitem_detail'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option
                                                    <?= ( @$daybook['get_lineitem_detail'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Maximum Line Item: </label>
                                        <input class="form-control form-control-sm" type="text"
                                            value="<?= @$daybook['maximum_line_item']; ?>"
                                            placeholder="Maximum Line item" name="maxlineitem">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">If Max Line Item Reached : </label>
                                        <div class="form-group">
                                            <select name="lineitem_reach" class="form-control select22 "
                                                data-select22-id="1" tabindex="-1" aria-hidden="true">
                                                <option
                                                    <?= ( @$daybook['maximum_lineitem_reached'] == "warn_and_allow_more_lines" ? 'selected' : '' ) ?>
                                                    value="warn_and_allow_more_lines">Warn and ALlow More Lines</option>
                                                <option
                                                    <?= ( @$daybook['maximum_lineitem_reached'] == "block" ? 'selected' : '' ) ?>
                                                    value="block">Block</option>
                                                <option
                                                    <?= ( @$daybook['maximum_lineitem_reached'] == "block_and_jump_to_footer" ? 'selected' : '' ) ?>
                                                    value="block_and_jump_to_footer">Block And Jump To Footer</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Auto Round Of at : </label>
                                        <div class="form-group">
                                            <select name="round_at" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['auto_round_ofat'] == "warn_and_allow_more_lines" ? 'selected' : '' ) ?>
                                                    value="warn_and_allow_more_lines">None</option>
                                                <option
                                                    <?= ( @$daybook['auto_round_ofat'] == "line_item" ? 'selected' : '' ) ?>
                                                    value="line_item">Line-Item</option>
                                                <option
                                                    <?= ( @$daybook['auto_round_ofat'] == "total" ? 'selected' : '' ) ?>
                                                    value="total">Total</option>
                                                <option
                                                    <?= ( @$daybook['auto_round_ofat'] == "both" ? 'selected' : '' ) ?>
                                                    value="both">Both</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Round Off Line Item : </label>
                                        <div class="form-group">
                                            <select name="round_lineitem" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['roundoff_line_item'] == "truncate" ? 'selected' : '' ) ?>
                                                    value="truncate">Truncate</option>
                                                <option
                                                    <?= ( @$daybook['roundoff_line_item'] == "none" ? 'selected' : '' ) ?>
                                                    value="none">None</option>
                                                <option
                                                    <?= ( @$daybook['roundoff_line_item'] == "normal" ? 'selected' : '' ) ?>
                                                    value="normal">Normal</option>
                                                <option
                                                    <?= ( @$daybook['roundoff_line_item'] == "plus" ? 'selected' : '' ) ?>
                                                    value="plus">Plus</option>
                                                <option
                                                    <?= ( @$daybook['roundoff_line_item'] == "minus" ? 'selected' : '' ) ?>
                                                    value="minus">Minus</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Round Off total : </label>
                                        <div class="form-group">
                                            <select name="round_total" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['roundoff_total'] == "truncate" ? 'selected' : '' ) ?>
                                                    value="truncate">Truncate</option>
                                                <option
                                                    <?= ( @$daybook['roundoff_total'] == "none" ? 'selected' : '' ) ?>
                                                    value="none">None</option>
                                                <option
                                                    <?= ( @$daybook['roundoff_total'] == "normal" ? 'selected' : '' ) ?>
                                                    value="normal">Normal</option>
                                                <option
                                                    <?= ( @$daybook['roundoff_total'] == "plus" ? 'selected' : '' ) ?>
                                                    value="plus">Plus</option>
                                                <option
                                                    <?= ( @$daybook['roundoff_total'] == "minus" ? 'selected' : '' ) ?>
                                                    value="minus">Minus</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Item Group: </label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="itemgrp" id="item_grp"
                                                onchange="validate_autocomplete(this,'item_grp_id')" autocomplete="off"
                                                value="<?=@$daybook['item_grp']?>" placeholder="Select Item Group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-title="Add Item Group" href="<?=url('master/add_itemgrp')?>"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="item_grp_id" id="item_grp_id"
                                            value="<?=@$daybook['item_grp_id']?>">
                                        <div class="dz-error-message tx-danger item_grp_id"></div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Holidays : </label>
                                        <div class="form-group">
                                            <select name="holiydays" class="form-control select22">
                                                <option <?= ( @$daybook['holiydays'] == "monday" ? 'selected' : '' ) ?>
                                                    value="monday">Monday</option>
                                                <option <?= ( @$daybook['holiydays'] == "tuesday" ? 'selected' : '' ) ?>
                                                    value="tuesday">Tuesday</option>
                                                <option
                                                    <?= ( @$daybook['holiydays'] == "wednesday" ? 'selected' : '' ) ?>
                                                    value="wednesday">Wednesday</option>
                                                <option
                                                    <?= ( @$daybook['holiydays'] == "thursday" ? 'selected' : '' ) ?>
                                                    value="thursday">Thursday</option>
                                                <option <?= ( @$daybook['holiydays'] == "friday" ? 'selected' : '' ) ?>
                                                    value="friday">Friday</option>
                                                <option
                                                    <?= ( @$daybook['holiydays'] == "saturday" ? 'selected' : '' ) ?>
                                                    value="saturday">Saturday</option>
                                                <option <?= ( @$daybook['holiydays'] == "sunday" ? 'selected' : '' ) ?>
                                                    value="sunday">Sunday</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">If Txn on Holiday : </label>
                                        <div class="form-group" >
                                            <select name="holiday_txn" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['if_txnon_holiyday'] == "ignore" ? 'selected' : '' ) ?>
                                                    value="ignore">Ignore</option>
                                                <option
                                                    <?= ( @$daybook['if_txnon_holiyday'] == "warn" ? 'selected' : '' ) ?>
                                                    value="warn">Warn</option>
                                                <option
                                                    <?= ( @$daybook['if_txnon_holiyday'] == "confirm" ? 'selected' : '' ) ?>
                                                    value="confirm">Confirm</option>
                                                <option
                                                    <?= ( @$daybook['if_txnon_holiyday'] == "block" ? 'selected' : '' ) ?>
                                                    value="block">Block</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Get Challan In : </label>
                                        <label class="rdiobox"><input
                                                <?= ( @$daybook['get_challan_in'] == "header" ? 'checked' : '' ) ?>
                                                value="header" name="rdio" type="radio"><span>Header</span></label>
                                        <label class="rdiobox"><input
                                                <?= ( @$daybook['get_challan_in'] == "line_item" ? 'checked' : '' ) ?>
                                                value="line_item" name="rdio"
                                                type="radio"><span>Line-Item</span></label>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Allow Increase Quantity : </label>
                                        <div class="form-group">
                                            <select name="increase_qty" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['allow_increase_quentity'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option
                                                    <?= ( @$daybook['allow_increase_quentity'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Allow Change Price : </label>
                                        <div class="form-group">
                                            <select name="change_price" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['allow_change_price'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option
                                                    <?= ( @$daybook['allow_change_price'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Restrict Change Price on Add : </label>
                                        <div class="form-group">
                                            <select name="restrict_price_add" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['restict_changeprice_onadd'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option
                                                    <?= ( @$daybook['restict_changeprice_onadd'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Restrict Change Price on Modify : </label>
                                        <div class="form-group">
                                            <select name="restrict_price_modify" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['restict_changeprice_onmodify'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option
                                                    <?= ( @$daybook['restict_changeprice_onmodify'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Send SMS : </label>
                                        <div class="form-group">
                                            <select name="send_sms" class="form-control select22 ">
                                                <option <?= ( @$daybook['send_sms'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option <?= ( @$daybook['send_sms'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Auto Consumption raw item : </label>
                                        <div class="form-group">
                                            <select name="auto_consumption" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['auto_consuption_rawitem'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option
                                                    <?= ( @$daybook['auto_consuption_rawitem'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Party Item wise Last Rate : </label>
                                        <div class="form-group">
                                            <select name="last_rate" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['party_itemwise_lastrate'] == "yes" ? 'selected' : '' ) ?>
                                                    value="yes">Yes</option>
                                                <option
                                                    <?= ( @$daybook['party_itemwise_lastrate'] == "no" ? 'selected' : '' ) ?>
                                                    value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <h3>Li Bill Terms</h3>
                            <section>
                                <div class="row">
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Disc. Round Method : </label>
                                        <div class="form-group">
                                            <select name="disc_round_method" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['disc_round_method'] == "truncate" ? 'selected' : '' ) ?>
                                                    value="truncate">Truncate </option>
                                                <option
                                                    <?= ( @$daybook['disc_round_method'] == "none" ? 'selected' : '' ) ?>
                                                    value="none">None</option>
                                                <option
                                                    <?= ( @$daybook['disc_round_method'] == "normal" ? 'selected' : '' ) ?>
                                                    value="normal">Normal</option>
                                                <option
                                                    <?= ( @$daybook['disc_round_method'] == "plus" ? 'selected' : '' ) ?>
                                                    value="plus">Plus</option>
                                                <option
                                                    <?= ( @$daybook['disc_round_method'] == "minus" ? 'selected' : '' ) ?>
                                                    value="minus">Minus</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Disc. Calc. At : </label>
                                        <div class="form-group">
                                            <select name="calc_at" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['disc_calc_at'] == "line_item" ? 'selected' : '' ) ?>
                                                    value="line_item">Line Item </option>
                                                <option
                                                    <?= ( @$daybook['disc_calc_at'] == "bill_term" ? 'selected' : '' ) ?>
                                                    value="bill_term">Bill Term</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Disc. Calc. Method : </label>
                                        <div class="form-group">
                                            <select name="calc_mehod" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['disc_calc_method'] == "line_item" ? 'selected' : '' ) ?>
                                                    value="line_item">Line Item </option>
                                                <option
                                                    <?= ( @$daybook['disc_calc_method'] == "bill_term" ? 'selected' : '' ) ?>
                                                    value="bill_term">Bill Term</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Disc. Sign : </label>
                                        <div class="form-group">
                                            <select name="calc_sign" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['disc_sign'] == "subtraction" ? 'selected' : '' ) ?>
                                                    value="subtraction">Subtraction </option>
                                                <option <?= ( @$daybook['disc_sign'] == "both" ? 'selected' : '' ) ?>
                                                    value="both">Both</option>
                                                <option
                                                    <?= ( @$daybook['disc_sign'] == "addition" ? 'selected' : '' ) ?>
                                                    value="addition">Addition</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Address 1 Round Method : </label>
                                        <div class="form-group">
                                            <select name="address1_round_method" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['address1_round_method'] == "truncate" ? 'selected' : '' ) ?>
                                                    value="truncate">Truncate</option>
                                                <option
                                                    <?= ( @$daybook['address1_round_method'] == "none" ? 'selected' : '' ) ?>
                                                    value="none">None</option>
                                                <option
                                                    <?= ( @$daybook['address1_round_method'] == "normal" ? 'selected' : '' ) ?>
                                                    value="normal">Normal</option>
                                                <option
                                                    <?= ( @$daybook['address1_round_method'] == "plus" ? 'selected' : '' ) ?>
                                                    value="plus">Plus</option>
                                                <option
                                                    <?= ( @$daybook['address1_round_method'] == "minus" ? 'selected' : '' ) ?>
                                                    value="minus">Minus</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Address 1 Calc. At : </label>
                                        <div class="form-group">
                                            <select name="address1_cal_at" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['address1_calc_at'] == "line_item" ? 'selected' : '' ) ?>
                                                    value="line_item">Line Item </option>
                                                <option
                                                    <?= ( @$daybook['address1_calc_at'] == "bill_term" ? 'selected' : '' ) ?>
                                                    value="bill_term">Bill Term</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Address 1 Calc. Method : </label>
                                        <div class="form-group">
                                            <select name="address1_cal_method" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['address1_calc_method'] == "runnig" ? 'selected' : '' ) ?>
                                                    value="runnig">Runnig </option>
                                                <option
                                                    <?= ( @$daybook['address1_calc_method'] == "gross" ? 'selected' : '' ) ?>
                                                    value="gross">Gross</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Address 1 . Sign : </label>
                                        <div class="form-group">
                                            <select name="address1_sign" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['address1_sign'] == "subtraction" ? 'selected' : '' ) ?>
                                                    value="subtraction">Subtraction </option>
                                                <option
                                                    <?= ( @$daybook['address1_sign'] == "both" ? 'selected' : '' ) ?>
                                                    value="both">Both</option>
                                                <option
                                                    <?= ( @$daybook['address1_sign'] == "addition" ? 'selected' : '' ) ?>
                                                    value="addition">Addition</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Address 2 Round Method : </label>
                                        <div class="form-group">
                                            <select name="address2_round_method" class="form-control select22">
                                                <option value="truncate">Truncate</option>
                                                <option value="none">None</option>
                                                <optio
                                                    <?= ( @$daybook['address2_round_method'] == "normal" ? 'selected' : '' ) ?>n
                                                    value="normal">Normal</option>
                                                    <option
                                                        <?= ( @$daybook['address2_round_method'] == "plus" ? 'selected' : '' ) ?>
                                                        value="plus">Plus</option>
                                                    <option
                                                        <?= ( @$daybook['address2_round_method'] == "minus" ? 'selected' : '' ) ?>
                                                        value="minus">Minus</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Address 2 Calc. At : </label>
                                        <div class="form-group">
                                            <select name="address2_calc_at" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['address2_calc_at'] == "line_item" ? 'selected' : '' ) ?>
                                                    value="line_item">Line Item </option>
                                                <option
                                                    <?= ( @$daybook['address2_calc_at'] == "bill_term" ? 'selected' : '' ) ?>
                                                    value="bill_term">Bill Term</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Address 2 Calc. Method : </label>
                                        <div class="form-group">
                                            <select name="address2_calc_method" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['address2_calc_method'] == "runnig" ? 'selected' : '' ) ?>
                                                    value="runnig">Runnig </option>
                                                <option
                                                    <?= ( @$daybook['address2_calc_method'] == "gross" ? 'selected' : '' ) ?>
                                                    value="gross">Gross</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Address 2 . Sign : </label>
                                        <div class="form-group">
                                            <select name="address2_sign" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['address2_sign'] == "subtraction" ? 'selected' : '' ) ?>
                                                    value="subtraction">Subtraction </option>
                                                <option
                                                    <?= ( @$daybook['address2_sign'] == "both" ? 'selected' : '' ) ?>
                                                    value="both">Both</option>
                                                <option
                                                    <?= ( @$daybook['address2_sign'] == "addition" ? 'selected' : '' ) ?>
                                                    value="addition">Addition</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Address 3 Round Method : </label>
                                        <div class="form-group">
                                            <select name="address3_round_method" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['address1_round_method'] == "truncate" ? 'selected' : '' ) ?>
                                                    value="truncate">Truncate</option>
                                                <option
                                                    <?= ( @$daybook['address3_round_method'] == "none" ? 'selected' : '' ) ?>
                                                    value="none">None</option>
                                                <option
                                                    <?= ( @$daybook['address3_round_method'] == "normal" ? 'selected' : '' ) ?>
                                                    value="normal">Normal</option>
                                                <option
                                                    <?= ( @$daybook['address3_round_method'] == "plus" ? 'selected' : '' ) ?>
                                                    value="plus">Plus</option>
                                                <option
                                                    <?= ( @$daybook['address3_round_method'] == "minus" ? 'selected' : '' ) ?>
                                                    value="minus">Minus</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Address 3 Calc. At : </label>
                                        <div class="form-group">
                                            <select name="address3_calc_at" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['address3_calc_at'] == "line_item" ? 'selected' : '' ) ?>
                                                    value="line_item">Line Item </option>
                                                <option
                                                    <?= ( @$daybook['address3_calc_at'] == "bill_term" ? 'selected' : '' ) ?>
                                                    value="bill_term">Bill Term</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Address 3 Calc. Method : </label>
                                        <div class="form-group">
                                            <select name="adreess3_calc_method" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['address3_calc_method'] == "running" ? 'selected' : '' ) ?>
                                                    value="runnig">Runnig </option>
                                                <option
                                                    <?= ( @$daybook['address3_calc_method'] == "gross" ? 'selected' : '' ) ?>
                                                    value="gross">Gross</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Address 3 . Sign : </label>
                                        <div class="form-group">
                                            <select name="address3_sign" class="form-control select22">
                                                <option
                                                    <?= ( @$daybook['address3_sign'] == "subtraction" ? 'selected' : '' ) ?>
                                                    value="subtraction">Subtraction </option>
                                                <option
                                                    <?= ( @$daybook['address3_sign'] == "both" ? 'selected' : '' ) ?>
                                                    value="both">Both</option>
                                                <option
                                                    <?= ( @$daybook['address3_sign'] == "addition" ? 'selected' : '' ) ?>
                                                    value="addition">Addition</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <!-- <label class="ckbox">
                                    <input type="checkbox"><span class="tx-13">I agree terms & Conditions</span>
                                </label> -->
                            </section>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="<?=ASSETS;?>/plugins/spectrum-colorpicker/spectrum.js"></script>
<script src="<?=ASSETS;?>js/jquery.validate.js"></script>
<script>
var form_loading = true;

function validate_autocomplete(obj, val) {
    if ($('#' + val).val() == '') {
        $('.' + val).html('Option Select from dropdown list')
    } else {
        $('.' + val).html('')
    }
}
$(document).ready(function() {

    var form = $("#daybook");
    form.validate({
        ignore: "",
        validateHiddenInputs: true,
        errorPlacement: function errorPlacement(error, element) {
            error.insertAfter(element);
            error.insertAfter(element.parent('.input-group'));
        },
        rules: {},
        messages: {}
    });
    var finishButton = $('.wizard').find('a[href="#finish"]');
    $('#wizard1').steps({
        headerTag: 'h3',
        bodyTag: 'section',
        autoFocus: true,
        titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>',
        onStepChanging: function(event, currentIndex, newIndex) {
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinishing: function(event, currentIndex) {
            if (form_loading) {
                return true;
            } else {
                return false;
            }
        },
        onFinishing: function(event, currentIndex) {
            if (form_loading) {
                return true;
            } else {
                return false;
            }
        },
        onFinished: function(event, currentIndex) {
            var data = form.serialize();
            finishButton.html("<i class='sl sl-icon-reload'></i> Please wait...");
            //form_loading = false;
            $('.description_error').html('');
            var aurl = $('#daybook').attr('action');
            $.post(aurl, data, function(response) {
                if (response.st == 'success') {
                    window.location = "<?=url('Account/daybook')?>"
                } else {
                    finishButton.html("Create Daybook");
                    form_loading = true;
                    $('.description_error').html(response.msg);
                }
            }).fail(function(response) {
                finishButton.html("Create daybook");
                form_loading = true;
                alert('Error');
            });
        }
    });    
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $('.select2').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        width: '100%',
    });
    $('.select22').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        width: '100%',
    });

    $('#showAlpha').spectrum({
        color: 'rgba(23,162,184,0.5)',
        showAlpha: true
    });
    $('#account').autocomplete({
        serviceUrl: '<?= url('Master/Getdata/search_account') ?>',
        type: 'POST',
        showNoSuggestionNotice: true,
        onSelect: function(suggestion) {
            $('#account').val(suggestion.value);
            $('#account_id').val(suggestion.data);

        }
    });
    $('#item_grp').autocomplete({
        serviceUrl: '<?= url('Master/Getdata/search_itemgrp') ?>',
        type: 'POST',
        showNoSuggestionNotice: true,
        onSelect: function(suggestion) {
            //alert(suggestion.data);
            $('#item_grp').val(suggestion.value);
            $('#item_grp_id').val(suggestion.data);
        }
    });

});
</script>
<?= $this->endSection() ?>