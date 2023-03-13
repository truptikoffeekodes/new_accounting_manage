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
                            <form action="<?= url('Sales/add_ACinvoice') ?>" class="ajax-form-submit"
                                method="post" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Invoice No: <span class="tx-danger">*</span></label>
                                        <input class="form-control" readonly type="text" 
                                            value="<?= @$challan['id'] ? $challan['id'] : @$current_id; ?>">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-labestl">Invoice Date: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control fc-datepicker" name="challan_date"
                                            value="<?=@$challan['challan_date'] ? $challan['challan_date'] : date('Y-m-d'); ?>"
                                            placeholder="MM/DD/YYYY" type="text" id="" required>
                                        <input name="id" value="<?=@$challan['id'] ?>" type="hidden">
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label class="form-label">Party Account: <span
                                                class="tx-danger">*</span></label>
                                        <select class="form-control" id="party_account" name='party_account'>
                                            <?php if(@$challan['party_account']) { ?>
                                            <option value="<?=@$challan['party_account']?>">
                                                <?=@$challan['party_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <input name="id" value="<?=@$challan['id']?>" type="hidden">
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label class="form-label">Supplier invoice No.: <span
                                                class="tx-danger"></span></label>
                                        <input class="form-control" type="text" placeholder="Enter Supplier Invoice No."
                                            name="supp_inv" id="supp_inv"
                                            value="<?= @$challan['supp_inv']; ?>">
                                    </div>
                                    
                                    <!-- <div class="col-lg-6 form-group">
                                        <label class="form-label">Particular: <span class="tx-danger"></span></label>
                                        <select class="form-control" id="particular" name='particular'>
                                            <?php if(@$challan['particular_name']) { ?>
                                            <option value="<?=@$challan['particular']?>">
                                                <?=@$challan['particular_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div> -->
                                    
                                    <div class="col-lg-7 form-group">
                                        <div class="row">
                                            <div class="row col-md-12 form-group">
                                                <label class="form-label col-md-4">Particular Name: <span
                                                        class="tx-danger">*</span></label>
                                            </div>
                                            <div class="form-label col-md-12">
                                                <select class="form-control" id="code" name='code'> </select>
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

                                                        <th>(-)Discount</th>
                                                        <th class="wd-300">
                                                            <div class="input-group">
                                                                <input class="form-control" onchange="calculate()"
                                                                    onkeypress="return isDesimalNumberKey(event)"
                                                                    name="discount"
                                                                    value="<?=@$challan['discount']?>"
                                                                    type="text">
                                                                <div class="input-group-prepend">
                                                                    <select class="select2" name="disc_type"
                                                                        onchange="calculate()">
                                                                        <option
                                                                            <?= @$challan['disc_type'] == 'Fixed' ? 'Selected' : '' ?>
                                                                            value="Fixed">Fixed Amount</option>
                                                                        <option
                                                                            <?= @$challan['disc_type'] == '%' ? 'Selected' : '' ?>
                                                                            value="%">Per(%) Amount</option>
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th class="discount_amount"></th>
                                                        </tr>
                                                        <tr>
                                                            <th>(-)Less Amount</th>
                                                            <th class="wd-300">
                                                                <div class="input-group">
                                                                    <input class="form-control" onchange="calculate()"
                                                                        onkeypress="return isDesimalNumberKey(event)"
                                                                        name="amtx"
                                                                        value="<?= @$challan['amtx']?>"
                                                                        type="text">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2" name="amtx_type"
                                                                            onchange="calculate()">
                                                                            <option
                                                                                <?= @$challan['amtx_type'] == 'Fixed' ? 'Selected' : '' ?>
                                                                                value="Fixed">Fixed Amount</option>
                                                                            <option
                                                                                <?= @$challan['amtx_type'] == '%' ? 'Selected' : '' ?>
                                                                                value="%">Per(%) Amount</option>
                                                                           
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th class="amtx_amount"></th>
                                                        </tr>
                                                        <tr>
                                                            <th>(+)Add Amount</th>
                                                            <th class="wd-300">
                                                                <div class="input-group">
                                                                    <input class="form-control" onchange="calculate()"
                                                                        onkeypress="return isDesimalNumberKey(event)"
                                                                        name="amty"
                                                                        value="<?= @$challan['amty']?>"
                                                                        type="text">
                                                                    <div class="input-group-prepend">
                                                                        <select class="select2" name="amty_type"
                                                                            onchange="calculate()">
                                                                            <option
                                                                                <?= @$challan['amty_type'] == 'Fixed' ? 'Selected' : '' ?>
                                                                                value="Fixed">Fixed Amount</option>
                                                                            <option
                                                                                <?= @$challan['amty_type'] == '%'  ? 'Selected' : '' ?>
                                                                                value="%">Per(%) Amount</option>
                                                                            
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th class="amty_amount"></th>
                                                        </tr>

                                                        <tr>
                                                            <th>(+)IGST</th>
                                                            <th class="wd-300">
                                                                <div class="input-group">
                                                                    <input class="form-control" readonly
                                                                        onchange="calculate()"
                                                                        onkeypress="return isDesimalNumberKey(event)"
                                                                        name="tot_igst" type="text"
                                                                        value="<?= @$challan['tot_igst']; ?>">

                                                                </div>
                                                            </th>
                                                            <th class="igst_amount wd-90"></th>
                                                        </tr>

                                                        <tr>
                                                            <th>(+)SGST</th>
                                                            <th class="wd-300">
                                                                <div class="input-group">
                                                                    <input class="form-control" readonly
                                                                        onchange="calculate()"
                                                                        onkeypress="return isDesimalNumberKey(event)"
                                                                        name="tot_sgst" type="text"
                                                                        value="<?= @$challan['tot_sgst']; ?>">
                                                                </div>
                                                            </th>
                                                            <th class="sgst_amount wd-90"></th>
                                                        </tr>
                                                        <tr>
                                                            <th>(+)CGST</th>
                                                            <th class="wd-300">
                                                                <div class="input-group">
                                                                    <input class="form-control" readonly
                                                                        onchange="calculate()"
                                                                        onkeypress="return isDesimalNumberKey(event)"
                                                                        name="tot_cgst" type="text"
                                                                        value="<?= @$challan['tot_cgst']; ?>">
                                                                </div>
                                                            </th>
                                                            <th class="cgst_amount wd-90"></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Net Amount</td>
                                                            <td colspan="2"><input class="form-control input-sm"
                                                                    name="net_amount" type="text"
                                                                    value="<?= @$challan['net_amount']; ?>"
                                                                    readonly></td>
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

function validate_autocomplete(obj, val) {
    if ($('#' + val).val() == '') {
        $('.' + val).html('Option Select from dropdown list')
    } else {
        $('.' + val).html('')
    }
}

function calculate() {
    

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

    // var item_disc = $('input[name="item_disc[]"]').map(function() {
    //     return parseFloat(this.value); // $(this).val()
    // }).get();

    var price = $('input[name="price[]"]').map(function() {
        return parseFloat(this.value); // $(this).val()
    }).get();

    var igst = $('input[name="igst[]"]').map(function() {
        return parseFloat(this.value); // $(this).val()
    }).get();
    
    var igst_amt = 0.0;
    var total = 0.0;
    for (var i = 0; i < price.length; i++) {
        
        var final_sub = price[i];
        igst_amt += final_sub * igst[i] / 100;
        
        $('input[name="subtotal[]"]').eq(i).val(final_sub);

        total += final_sub;
       
    }
    $('.total').html(total);


    var discount = $('input[name="discount"]').val();

    var amtx = parseFloat($('input[name="amtx"]').val());
    var amty = parseFloat($('input[name="amty"]').val());

    if (Number.isNaN(discount)) {
        discount = 0;
    }
    if (Number.isNaN(amtx)) {
        amtx = 0;
    }
    if (Number.isNaN(amty)) {
        amty = 0;
    }
    

    var discount_type = $('select[name=disc_type] option').filter(':selected').val();
    var amtx_type = $('select[name=amtx_type] option').filter(':selected').val();
    var amty_type = $('select[name=amty_type] option').filter(':selected').val();

    if (discount_type == '%') {
        discount_amount = (total * (discount / 100));
        $('.discount_amount').html('- ' + discount_amount);
        
        if(discount_amount > 0){
            total = 0;
            var divide_disc = discount_amount / price.length;
            var igst_amt = 0;

            for(var i = 0; i < price.length; i++) {
                
                var final_sub = price[i];
                var abc = final_sub - divide_disc;

                igst_amt += abc * igst[i] / 100;   
                total += abc;
            }
        }
    } else {
        $('.discount_amount').html('- ' + discount);
        if(discount > 0){
            var total = 0;
            var divide_disc = discount/price.length;
            var igst_amt =0;
            for(var i = 0; i < price.length; i++) {
                
                var final_sub = price[i];
                var abc = final_sub - divide_disc;

                igst_amt += abc * igst[i] / 100;   
                total += abc;
            }
        } 
    }

        var grand_total = total;
        grand_total = grand_total + igst_amt;
        
        
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

    
    var cgst = igst_amt / 2;
    var sgst = igst_amt / 2;

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
    $('.igst_amount').html('+ ' + igst_amt.toFixed(2));
    $('.cgst_amount').html('+ ' + cgst.toFixed(2));
    $('.sgst_amount').html('+ ' + sgst.toFixed(2));
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

                    window.location = "<?=url('Sales/ac_challan')?>"
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

    $("#party_account").select2({

        width: '100%',
        placeholder: 'Type Party Account',
        ajax: {
            url: PATH + "Master/Getdata/search_party",
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