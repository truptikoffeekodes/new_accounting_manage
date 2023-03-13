<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5">Transacrion</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Sales</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
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
                            <form action="<?= url('purchase/add_general_pur') ?>" class="ajax-form-submit" method="post"
                                enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Invoice No: <span class="tx-danger">*</span></label>
                                        <input class="form-control" readonly type="text"
                                            value="<?= @$general['id'] ? $general['id'] : @$current_id; ?>">
                                    </div>
                                    <?php 
                                      $today = user_date(date('Y-m-d'));
                                    if(!empty($general)){
                                        $date = user_date($general['doc_date']);
                                      
                                    }
                                    ?>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-labestl">Doc Date: <span class="tx-danger">*</span></label>
                                        <input class="form-control dateMask" name="doc_date"
                                            value="<?=@$general['doc_date'] ? $date : $today ?>"
                                            placeholder="DD-MM-YYYY" type="text" id="" required>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                    </div>
                                    
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Party Account: <span
                                                class="tx-danger">*</span></label>
                                        <select class="form-control" id="party_account" name='party_account'>
                                            <?php if(@$general['party_name']) { ?>
                                            <option selected value="<?=@$general['party_account']?>">
                                                <?=@$general['party_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        
                                        <div class="tx-danger error-msg return-error"> </div>
                                        <input name="id" value="<?=@$general['id']?>" type="hidden">
                                        <input type="hidden" name="tds_per" id="tds_per"
                                            value="<?= @$general['tds_per']; ?>">
                                        <input type="hidden" name="tds_limit" id="tds_limit"
                                            value="<?= @$general['tds_limit']; ?>">
                                        <input type="hidden" name="acc_state" id="acc_state"
                                            value="<?= @$general['acc_state']; ?>">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Voucher Type: <span
                                                class="tx-danger">*</span></label>
                                                
                                        <label class="rdiobox"><input  name="v_type" required
                                                <?=@$general['v_type'] == "general" ? 'checked' : ''  ?>
                                                value="general" type="radio" onchange="calculate()">
                                            <span>General</span></label>

                                        <label class="rdiobox"><input name="v_type" required
                                                <?=@$general['v_type'] == "return" ? 'checked' : ''  ?>
                                                value="return" type="radio" onchange="calculate()"> <span>Return</span></label>
                                    </div>
                                    
                                    <div class="col-md-3 form-group" id="invoice_div" style="display:<?=!empty(@$general['return_purchase']) ? 'block;': 'none;'?>">
                                        
                                        <label class="form-label">Select Invoice : <span
                                            class="tx-danger"></span></label>

                                        <div class="input-group">
                                            <select class="form-control select2" id="invoices" name="invoice">
                                                <?php if(@$general['return_pur_name']) { ?>
                                                <option selected value="<?=@$general['return_purchase']?>">
                                                    <?=@$general['return_pur_name']?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 form-group">
                                        <label class="form-label">Supplier invoice No.: <span
                                                class="tx-danger"></span></label>
                                        <input class="form-control" type="text" placeholder="Enter Supplier Invoice No."
                                            name="supp_inv" id="supp_inv" value="<?= @$general['supp_inv']; ?>">
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label class="form-label">Other: <span class="tx-danger"></span></label>
                                        <input class="form-control" type="text" placeholder="Enter Other Detail."
                                            name="other" value="<?= @$general['other']; ?>">
                                    </div>

                                    <!-- <div class="col-lg-6 form-group">
                                        <label class="form-label">Particular: <span class="tx-danger"></span></label>
                                        <select class="form-control" id="particular" name='particular'>
                                            <?php if(@$general['particular_name']) { ?>
                                            <option value="<?=@$general['particular']?>">
                                                <?=@$general['particular_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="col-lg-7 form-group">
                                        <label class="form-label">Particular Name: <span
                                                class="tx-danger">*</span></label>
                                        <select class="form-control" id="code" name='code'> </select>
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
                                        if(isset($acc))
                                        {
                                            $total=0.0;
                                            foreach($acc as $row){
                                                
                                                $sub_total=$row['amount'];
                                                $total += $sub_total;
                                              //  $uom=explode(',',$row['item_uom']);
                                        ?>
                                                <tr>
                                                    <td><a class="tx-danger btnDelete" data-id="<?=$row['account']?>"
                                                            title="0"><i class="fa fa-times tx-danger"></i></a></td>
                                                    <td><?=$row['account_name'] ?>(<?=$row['code'] ?>)
                                                        <input type="hidden" name="pid[]" value="<?=$row['account']?>">
                                                    </td>


                                                    <td><input class="form-control input-sm" value="<?=$row['amount']?>"
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
                                                                        value="<?= @$general['discount']; ?>">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2" name="disc_type"
                                                                            onchange="calculate()">
                                                                            <option
                                                                                <?= ( @$general['disc_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                                value="Fixed">Fixed Amount</option>
                                                                            <option
                                                                                <?= ( @$general['disc_type'] == '%' ? 'selected' : '' ) ?>
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
                                                                        value="<?= @$general['amtx']; ?>">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2" name="amtx_type"
                                                                            onchange="calculate()">
                                                                            <option
                                                                                <?= ( @$general['amtx_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                                value="Fixed">Fixed Amount</option>
                                                                            <option
                                                                                <?= ( @$general['amtx_type'] == '%' ? 'selected' : '' ) ?>
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
                                                                        value="<?= @$general['amty']; ?>">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2" name="amty_type"
                                                                            onchange="calculate()">
                                                                            <option
                                                                                <?= ( @$general['amty_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                                value="Fixed">Fixed Amount</option>
                                                                            <option
                                                                                <?= ( @$general['amty_type'] == '%' ? 'selected' : '' ) ?>
                                                                                value="%">Per(%) Amount</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th class="amty_amount wd-90"></th>
                                                        </tr>
                                                        <?php 
                                                            $taxes = json_decode(@$general['taxes']);
                                                        ?>

                                                        <tr>
                                                            <th>Select Tax</th>
                                                            <th colspan="2" class="wd-300">
                                                                <div class="input-group-sm">
                                                                    <select class="select2" id="tax" name="taxes[]"
                                                                        onchange="calculate()" multiple>
                                                                        <?php 
                                                                        foreach($tax as $row) { 
                                                                            if($row['name'] == 'igst' && session('state') != @$general['acc_state']) {
                                                                        ?>
                                                                        <option value="<?=$row['name'] ?>"
                                                                            <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                            <?=$row['name']; ?></option>

                                                                        <?php }else if($row['name'] == 'cgst'  && session('state') == @$general['acc_state']){ ?>

                                                                        <option value="<?=$row['name'] ?>"
                                                                            <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                            <?=$row['name']; ?></option>

                                                                        <?php }else if($row['name'] == 'sgst'  && session('state') == @$general['acc_state']){ ?>

                                                                        <option value="<?=$row['name'] ?>"
                                                                            <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                            <?=$row['name']; ?></option>

                                                                        <?php }else if($row['name'] == 'tds' || $row['name'] == 'cess' ) { ?>

                                                                        <option value="<?=$row['name'] ?>"
                                                                            <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                            <?=$row['name']; ?></option>

                                                                        <?php }else{ if(!@$general)  { ?>
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
                                                                        value="<?= @$invoice['tot_igst']; ?>">
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
                                                                        value="<?= @$general['tot_sgst']; ?>">

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
                                                                        value="<?= @$general['tot_cgst']; ?>">

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
                                                                        value="<?= @$general['tds_amt']; ?>">

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
                                                                        value="<?= @$general['cess']; ?>">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2" name="cess_type"
                                                                            onchange="calculate()">
                                                                            <option
                                                                                <?= ( @$general['cess_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                                value="Fixed">Fixed Amount</option>
                                                                            <option
                                                                                <?= ( @$general['cess_type'] == '%' ? 'selected' : '' ) ?>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
<?php 
if(isset($id))
{?>
calculate();
<?php } ?>

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

    for (var i = 0; i < price.length; i++) {

        var final_sub = price[i];
        igst_amt += final_sub * igst[i] / 100;

        $('input[name="subtotal[]"]').eq(i).val(final_sub);

        total += final_sub;

    }
    $('.total').html(total);

    //tot_fix_brok = total * fix_brokrage/100;

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
            var divide_disc = discount_amount / price.length;
            var igst_amt = 0;
            for (var i = 0; i < price.length; i++) {

                var sub = price[i];
                //  var disc_amt = sub;    
                var final_sub = sub;

                var abc = final_sub - divide_disc;
                igst_amt += abc * igst[i] / 100;
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

                var sub = price[i];
                var final_sub = price[i];

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
       
        $('.cess_amount').html('+ ' + cess);
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

        if (pids.toString().indexOf(suggestion.data) == -1) {

            var inp = '<input type="hidden" name="pid[]" value="' + suggestion.id + '">';
            var tds = '<tr>';
            tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
            tds += '<td>' + suggestion.text + inp + '</td>';
            tds +=
                '<td><input class="form-control input-sm" value="0" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + suggestion.paticular
                .igst +
                '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + suggestion.paticular
                .cgst +
                '" name="cgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';


            tds += '<td><input class="form-control input-sm" value="' + suggestion.paticular
                .sgst +
                '" name="sgst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

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

                    window.location = "<?=url('purchase/general_purchase')?>"
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
    $("#party_account").select2({

        width: '100%',
        placeholder: 'Type Party Account',
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

    $("input[name='v_type']").change(function() {
        var ac_id = $('#party_account').val();
        var invoice_div = document.getElementById("invoice_div");

        if ($(this).val() == 'return') {

            invoice_div.style.display = "block";

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
        }
    });

    $('#party_account').on('select2:select', function(e) {
        var data = e.params.data;
        
        $('.return-error').html('');
        var invoice_div = document.getElementById("invoice_div");
        invoice_div.style.display = "none";
        $("input[name='v_type']").prop("checked", false);


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

    $("#particular").select2({

        width: '100%',
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


});
</script>
<?= $this->endSection() ?>