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
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label class="form-label"><h5>SR No: <b><?= @$challan['id']?></b> </label>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="form-label"><h5>Daybook: <b><?= @$challan['daybook_name']?></b> </label>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="form-label"><h5>Challn No: <b><?= @$challan['challan_no']?></b> </label>
                        </div>
                        
                        <div class="col-lg-6 form-group">
                            <label class="form-label"><h5>Challan Date: <b><?= @$challan['challan_date']?></b></label>

                        </div>
                       
                        
                        <div class="col-lg-4 form-group">
                            <label class="form-label">Transport Mode: <b><?= @$challan['transport_mode']?></b></label>

                        </div>
                        <div class="col-lg-4 form-group">
                            <label class="form-label">LR No: <b><?= @$challan['lr_no']?></b></label>

                        </div>
                        <div class="col-lg-4 form-group">
                            <label class="form-label">LR Date: <b><?= @$challan['lr_date']?></b></label>

                        </div>
                        <div class="col-lg-4 form-group">
                            <label class="form-label">Account : <b><?=@$challan['account_name']?></b> </label>

                        </div>
                        <div class="col-lg-4 form-group">
                            <label class="form-label">Weight: <b><?= @$challan['weight']?></b></label>

                        </div>
                        <div class="col-lg-4 form-group">
                            <label class="form-label">Freight: <b><?= @$challan['freight']?></b></label>

                        </div>
                        <div class="col-lg-4 form-group">
                            <label class="form-label">Delivery Address: <b><?= @$challan['delivery_name']?></b></label>
                        </div>

                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered mg-b-0" id="product">
                                <thead>
                                    <tr>
                                    <th>#</th>
                                            <th>Item</th>
                                            <th>Type</th>
                                            <th>Rate</th>
                                            <th>Gst</th>
                                            <th>PCS</th>
                                            <th>Cut</th>
                                            <th>Mtr</th>

                                            <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                                    <?php 
                                    
                                        $taxes = json_decode(@$challan['taxes']);
                                    
                                        if(isset($item))
                                        {
                                            $total=0.0;
                                            $i = 0;
                                            foreach($item as $row){
                                                $i++;
                                                $sub_total=$row['price'] * $row['pcs'] ;
                                                $total += $sub_total;
                                               // $uom=explode(',',$row['item_uom']);


                                                //$sub_total=$row['price'] * $row['pcs'] ;
                                                //$total += $sub_total;
                                                //print_r($total);exit;
                                                //$uom=explode(',',$row['item_uom']);
                                        ?>
                                        
                                    <tr>
                                        <td><?=$i;?></td>
                                        <td><?=$row['name'] ?>(<?=$row['code'] ?>)</td>
                                        <td><?=$row['GiType']?></td>
                                        <td><?=$row['price']?></td>
                                        <td><?=$row['igst']?></td>
                                        <td><?=$row['pcs']?></td>
                                        <td><?=$row['cut']?></td>
                                        <td><?=$row['meter']?></td>
                                        <td><?=$sub_total?></td>
                                       
                                        
                                    </tr>
                                    <?php } }?>
                                </tbody>
                                <tfoot>
                                    <td colspan="2" class="text-right">Total</td>
                                  
                                    <td class="qty_total"></td>
                                    <td class="rate_total"></td>
                                    <td class="IGST_total"></td>
                                    <td class="CGST_total"></td>
                                    <td class="SGST_total"></td>
                                    
                                    <td class="total"><?= @$total ?></td>
                                    <td class="disc_total"></td>
                                    
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
                                                <th>

                                                    <div class="input-group">
                                                        <?= @$challan['discount'] == '' ? '0' : @$challan['discount'] ; ?>
                                                    </div>
                                                </th>
                                                <th><?=@$challan['disc_type']?></th>
                                            </tr>

                                            <tr>
                                                <th>(-)Less Amount</th>
                                                <th>
                                                    <?= @$challan['amtx'] == '' ? '0' : @$challan['amtx'] ; ?>
                                                </th>
                                                <th><?=@$challan['amtx_type']?></th>
                                            </tr>

                                            <tr>
                                                <th>(+)Add Amount</th>
                                                <th>
                                                    <?= @$challan['amty'] == '' ? '0' : @$challan['amty'] ; ?>
                                                </th>
                                                <th><?=@$challan['amtx_type']?></th>
                                            </tr>

                                            <tr id="igst"
                                                style="display:<?php if(!empty($taxes)) {  echo  (in_array("igst", $taxes)) ? 'table-row;' : 'none;' ; }else{ echo 'none;'; }  ?>">
                                                <th>(+)IGST</th>
                                                <th>
                                                    <?= @$challan['tot_igst'] == '' ? '0' : @$challan['tot_igst'] ; ?>
                                                </th>
                                                <th></th>
                                            </tr>

                                            <tr id="sgst"
                                                style="display:<?php if(!empty($taxes)) { echo in_array("sgst", $taxes) ? 'table-row;' : 'none;'; } else{ echo 'none;'; } ?>">
                                                <th>(+)SGST</th>
                                                <th>
                                                    <?= @$challan['tot_sgst'] == '' ? '0' : @$challan['tot_igst'] ; ?>
                                                </th>
                                                <th></th>
                                            </tr>

                                            <tr id="cgst"
                                                style="display:<?php if(!empty($taxes)) { echo in_array("cgst", $taxes) ? 'table-row;' : 'none;'; } else{ echo 'none;'; } ?>">
                                                <th>(+)CGST</th>
                                                <th >
                                                    <?= @$challan['tot_cgst'] == '' ? '0' : @$challan['tot_cgst'] ; ?>
                                                </th>
                                                <th></th>
                                            </tr>

                                            <tr id="tds"
                                                style="display:<?php if(!empty($taxes)) { echo in_array("tds", $taxes) ? 'table-row;' : 'none;'; }else{ echo 'none;'; } ?>">
                                                <th>(+)TDS</th>
                                                <th>
                                                    <?= @$challan['tds_amt'] == '' ? '0' : @$challan['tds_amt'] ; ?>
                                                </th>
                                                <th></th>
                                            </tr>

                                            <tr id="cess"
                                                style="display:<?php if(!empty($taxes)) { echo in_array("cess", $taxes) ? 'table-row;' : 'none;'; }else{echo 'none;';} ?> ">
                                                <th>(+)Cess</th>
                                                <th>
                                                    <?= @$challan['cess'] == '' ? '0' : @$challan['cess'] ; ?>
                                                </th>
                                                <th><?=@$challan['cess_type']?></th>

                                            </tr>
                                            <tr>
                                                <td><h5 >Net Amount </h5></td>
                                                <td colspan="2"><h5 ><?=@$challan['net_amount']?></h5></td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
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
if(isset($id)){?>
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

    if (cess_type == '%') {
        cess_amount = (total * (cess / 100));
        $('.cess_amount').html('+ ' + cess_amount);
        grand_total += (total * (cess / 100));
    } else {
        $('.cess_amount').html('+ ' + amty);
        grand_total += cess;
    }
    var tds_amount = 0;

    if (tds_per != '') {
        tds_amount = (total * (tds_per / 100));
        grand_total += tds_amount;
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

    $('#transport_mode').select2({
        width: '65%'
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
        $('#save_data').prop('disabled', true);
        $('.error-msg').html('');
        $('.form_proccessing').html('Please wail...');
        e.preventDefault();
        var aurl = $(this).attr('action');
        $.ajax({
            type: "POST",
            url: aurl,
            data: $(this).serialize(),
            success: function(response) {
                if (response.st == 'success') {
                    //$('#fm_model').modal('toggle');
                    //swal("success!", "Your update successfully!", "success");
                    // $('.form_proccessing').html('');
                    $('#save_data').prop('disabled', false);
                    window.location = "<?=url('sales/challan')?>";
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

    $("#account").select2({
        width: '66.5%',
        placeholder: 'Type Account Name',
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
        $('#gst').val(data.gsttin);
        $('#tds_per').val(data.tds);
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


    $('#broker').on('select2:select', function(e) {
        var data = e.params.data;

        $('#fix_brokrage').val(data.brokrage);
        // $('#brok_name').text(data.text);
        // $('.broker-error').text('');
    });

    // $("#broker_ledger").select2({
    //     width: '100%',
    //     placeholder: 'Type Broker Account',
    //     ajax: {
    //         url: PATH + "Master/Getdata/search_broker_ledger",
    //         type: "post",
    //         allowClear: true,
    //         dataType: 'json',
    //         delay: 250,
    //         data: function(params) {
    //             return {
    //                 searchTerm: params.term // search term
    //             };
    //         },
    //         processResults: function(response) {
    //             return {
    //                 results: response
    //             };
    //         },
    //         cache: true
    //     }
    // });

    $("#delivery_code").select2({
        width: '100%',
        placeholder: 'Type Delivery Name',
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
        width: '100%',
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
        width: '65%',
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