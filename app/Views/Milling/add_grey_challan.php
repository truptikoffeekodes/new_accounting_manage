<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.modal-dialog {
    max-width: 750px;
    margin: 1.75rem auto;
}
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"><?=$title?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Purchase</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>

    <div class="ml-auto pd-r-100">
        <h2 class="mb-1 font-weight-bold"><span>Gray/Finish Challan Sr No :</span>
            <?= @$challan['sr_no'] ? $challan['sr_no'] : $current_id; ?></h2>
    </div>

</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <form action="<?= url('Milling/Add_grey_challan') ?>" class="ajax-form-submit-challan" method="POST"
                        id="challanform">
                        <div class="row">

                            <input class="form-control col-md-9" type="hidden" name="id"
                                value="<?= @$challan['id'] ? $challan['id'] : $id; ?>" placeholder="Enter id">

                            <input class="form-control col-md-9" type="hidden" name="srno" id="srno" readonly
                                value="<?= @$challan['sr_no'] ? $challan['sr_no'] : $current_id; ?>">

                            <div class="col-md-4 form-group">
                                <label class="form-label">Voucher Type : </label>
                                <select class="form-control" id="voucher_type" name='voucher_type'>
                                    <?php if(@$challan['voucher_type']) { ?>
                                    <option value="<?=@$challan['voucher_type']?>">
                                        <?=@$challan['voucher_name']?>
                                    </option>
                                    <?php }else{ ?>
                                    <option value="53" selected>
                                        Purchase Taxable
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-4 form-group">
                                <label class="form-label">Weaver Challan No: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" placeholder = "Enter Weaver Challan No" name="challan_id"
                                    value="<?= @$challan['challan_no'];?>">
                            </div>

                            <?php 
                                if(!empty($challan)){
                                    $challan_date = user_date($challan['challan_date']);
                                }
                                $today = user_date(date('Y-m-d'));
                            ?>

                            <div class="col-md-4 form-group">
                                <label class="form-label">Challan Date: <span class="tx-danger">*</span></label>
                                <input class="form-control dateMask" placeholder="DD/MM/YYYY" type="text"
                                    id="challan_date" name="challan_date"
                                    value="<?= @$challan['challan_date'] ? $challan_date : $today; ?>">
                            </div>

                            <div class="col-lg-5 form-group">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Purchase Mode: </label>
                                    </div>
                                    <div class="row col-md-8">
                                        <div class="col-lg-6">
                                            <label class="rdiobox"><input name="purchase_type" value="Finish"
                                                    type="radio"
                                                    <?=@$challan['purchase_type'] == 'Finish' ? 'Checked' : ''?>><span>Finish</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="rdiobox"><input name="purchase_type" value="Gray" type="radio"
                                                    <?=@$challan['purchase_type'] == 'Gray' ? 'Checked' : ''?>>
                                                <span>Gray</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Transport Mode: </label>
                                    </div>

                                    <div class="col-md-8 form-group">
                                        <select class="form-control" id="transport_mode" name="trasport_mode">

                                            <option <?= ( @$challan['transport_mode'] == 'ROAD' ? 'selected' : '' ) ?>
                                                value="ROAD">ROAD</option>

                                            <option <?= ( @$challan['transport_mode'] == 'AIR' ? 'selected' : '' ) ?>
                                                value="AIR">AIR</option>

                                            <option <?= ( @$challan['transport_mode'] == 'RAIL' ? 'selected' : '' ) ?>
                                                value="RAIL">RAIL</option>

                                            <option <?= ( @$challan['transport_mode'] == 'SHIP' ? 'selected' : '' ) ?>
                                                value="SHIP">SHIP</option>
                                        </select>
                                    </div>


                                    <div class="col-md-4 form-group">
                                        <label class="form-label"> Issue From / Weaver: <span
                                                class="tx-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <div class="input-group" style="width:auto;">
                                            <select class="form-control" id="account" name='account' required>
                                                <?php if(@$challan['party_name']) { ?>
                                                <option value="<?=@$challan['party_name']?>">
                                                    <?=@$challan['account_name']?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_account') ?>"
                                                        data-target="#fm_model" data-title="Enter Account"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <input type="hidden" name="id" value="<?= @$challan['id']; ?>"> -->
                                        <input type="hidden" name="tds_per" id="tds_per"
                                            value="<?= @$challan['tds_per']; ?>">
                                        <input type="hidden" name="tds_limit" id="tds_limit"
                                            value="<?= @$challan['tds_limit']; ?>">
                                        <input type="hidden" name="acc_state" id="acc_state"
                                            value="<?= @$challan['acc_state']; ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Shiped to AC: </label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <div class="input-group" style="width:auto;">
                                            <select class="form-control" id="delivery" name='delivery_ac'>
                                                <option value=""> Not One</option>
                                                <?php if(@$challan['delivery_ac']) { ?>
                                                <option selected value="<?=@$challan['delivery_ac']?>">
                                                    <?=@$challan['delivery_ac_name']?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_account') ?>"
                                                        data-target="#fm_model" data-title="Enter Account"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Shiped to Address: </label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <textarea class="form-control" name="delivery_code" value=""
                                            placeholder="Delivery Address"
                                            type="text"><?= @$challan['delivery_code']; ?></textarea>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Add Gray Item: <span
                                                class="tx-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <div class="input-group">
                                            <select class="form-control" id="code"> </select>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <a data-toggle="modal" href="<?= url('Master/add_item/mill') ?>"
                                                        data-target="#fm_model" data-title="Enter Item"><i
                                                            style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-success" id="suggesion"></p>
                                    </div>


                                </div>
                            </div>

                            <div class="col-lg-7 form-group">
                                <div class="row">

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR No.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="lrno" value="<?= @$challan['lr_no']; ?>"
                                            placeholder="LR No." type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR Date.: </label>
                                    </div>
                                    <?php 
                                        $lr_date = user_date(@$challan['lr_date']);
                                    ?>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control dateMask" placeholder="DD-MM-YYYY" type="text"
                                            id="lr_date" name="lr_date" value="<?= @$lr_date; ?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Weight: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="weight" value="<?= @$challan['weight']; ?>"
                                            placeholder="0.00" placeholder="Enter Weight" type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Freight: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="freight" value="<?= @$challan['freight']; ?>"
                                            placeholder="00" type="text">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label class="form-label"> Warehouse: </label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="form-control" id="warehouse" name='warehouse'>
                                            <?php if(@$challan['warehouse_name']) { ?>
                                            <option value="<?=@$challan['warehouse']?>">
                                                <?=@$challan['warehouse_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label"> Broker: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <select class="form-control" id="broker" name='broker'>
                                            <?php if(@$challan['broker']) { ?>
                                            <option value="<?=@$challan['broker']?>">
                                                <?=@$challan['broker_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Transport</label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <select class="form-control transport" id="transport" name='transport'>
                                            <?php if(@$challan['transport_name']) { ?>
                                            <option value="<?=@$challan['transport']?>">
                                                <?=@$challan['transport_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label class="form-label"> <span class="tx-danger"></span></label>
                                    </div>

                                    <!-- <a target="_blank"   title="Add Item:<?=@$current_id?>" onclick="add_item(this)"  data-val="<?=@$current_id?>" data-pk="<?=@$current_id?>" tabindex="-1" class="btn btn-primary">Add Item</a> -->
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
                                            <th>HSN</th>
                                            <th>Type</th>
                                            <th>Grey Rate</th>
                                            <th>Gst Rate(%)</th>
                                            <th>PCS</th>
                                            <th>QTY</th>
                                            <th>Cut</th>
                                            <th>Amount</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">

                                        <?php 
                                        $total=0;
                                        if(isset($item))
                                        {  
                                            foreach($item as $row){

                                                $sub_total=$row['price'] * $row['pcs'] ;
                                                $total += $sub_total;
                                                //print_r($total);exit;
                                                //$uom=explode(',',$row['item_uom']);
                                                ?>
                                        <tr class="<?=$row['pid']?> item_row">
                                            <input type="hidden" name="takaTb_id[]" value="<?=@$row['takaTB_ids']?>">

                                            <td><a class="tx-danger btnDelete" data-id="<?=$row['id']?>" title="0"><i
                                                        class="fa fa-times tx-danger"></i></a></td>

                                            <!-- <td>#</td> -->
                                            <input type="hidden" name="pid[]" value="<?=$row['pid']?>">

                                            <td><?=$row['name']?></td>
                                            <td><?=$row['hsn']?></td>
                                            <td>
                                                <select id="type" name="type[]" onchange="calculate()">
                                                    <?=@$row['uom_opt']?>
                                                </select>
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$row['price']?>"
                                                    name="price[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"></td>
                                            <td><input class="form-control input-sm" value="<?=$row['igst']?>"
                                                    name="igst[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"></td>
                                            <td><input class="form-control input-sm" value="<?=$row['pcs']?>"
                                                    name="taka[]" readonly onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text">
                                                <a data-toggle="modal" type="button" id="add_taka"
                                                    href="<?= url("Milling/Add_Challantaka/").$row['pid'].'/'.$challan['id']?>"
                                                    data-target="#fm_model" data-title="Edit Taka" class=""><i
                                                        class="far fa-edit"></i></a><b class="pcs_uom"></b>
                                            </td>
                                            </td>
                                            <td>
                                                <input class="form-control input-sm" type="text" name="meter[]"
                                                    value="<?=@$row['meter']?>" id="meter" readonly
                                                    onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)"><b
                                                    class="other_uom"></b>
                                            </td>
                                            <td><input class="form-control input-sm" value="<?=$row['cut']?>"
                                                    name="cut[]" readonly onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$sub_total?>"
                                                    name="subtotal[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['extra']?>"
                                                    name="extra[]" type="text"></td>
                                        </tr>
                                        <?php 
                                            } 
                                        }?>
                                    </tbody>
                                    <tfoot>
                                        <td colspan="2" class="text-right">Total</td>

                                        <td class="amount_total"></td>
                                        <td class="amount_total"></td>
                                        <td class="IGST_total"></td>
                                        <td class="CGST_total"></td>
                                        <td class="SGST_total"></td>
                                        <td></td>
                                        <td></td>
                                        <td class="total"><?=@$total; ?></td>
                                        <td></td>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                </div>
                <div class="form-group">
                    <div class="tx-danger error-msg"></div>
                    <div class="tx-success form_proccessing"></div>
                </div>
                <div class=" mt-3">
                    <input class="btn btn-space btn-primary btn-product-submit" id="save_data" type="submit"
                        value="Submit">
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


$('.ajax-form-submit-challan').on('submit', function(e) {
    var meter = $('input[name="meter[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var test = false;
    $.each(meter, function(index, value) {
        if (value == 'NaN' || isNaN(value) || value == 'undefined') {
            console.log('value  ' + value);
            $('.error-msg').html('Please Add Meter..!!');
            test = true;
            return false;
        }
    });

    if (test) {
        return false;
    }
    $('#save_data').prop('disabled', true);
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
                //$('#fm_model').modal('toggle');
                swal("success!", "Your update successfully!", "success");
                // $('.form_proccessing').html('');
                $('#save_data').prop('disabled', false);
                window.location = "<?=url('Milling/Grey_challan')?>";
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



$(document).ready(function() {

    $('.select2').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });

    $('#fm_model').modal({
        backdrop: 'static',
        keyboard: false,
        show: false,
    });

    $('#transport_mode').select2({
        width: '65%'
    });

    $('input[type=radio][name=purchase_type]').on('change', function() {

        $('select[name=account]').val(null).trigger('change');
        $('select[name=trasport_mode]').val(null).trigger('change');
        $('select[name=warehouse]').val(null).trigger('change');
        $('select[name=broker]').val(null).trigger('change');
        $('select[name=transport]').val(null).trigger('change');
        $('select[name=code]').val(null).trigger('change');
        $('textarea[name=delivery_code]').html('');
        $('input[name=lrno]').val('');
        $('input[name=lr_datea]').val('');
        $('input[name=weight]').val('');
        $('input[name=freight]').val('');
        $('.tbody').html('');

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
        $('#code').attr('disabled', false);
        $('#challan_btn').attr('disabled', true);
        calculate();
    });

    $("#code").select2({
        width: 'resolve',
        placeholder: 'Type Item Code ',
        ajax: {
            url: PATH + "Milling/Getdata/Item",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                var purchase_type = $('input[name="purchase_type"]:checked').val();

                if (purchase_type == 'Finish') {
                    var type = 'Finish';
                } else {
                    var type = 'Grey';
                }
                return {
                    searchTerm: params.term, // search term
                    type: type // search term
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

            var tds = '<tr class="' + suggestion.id + ' item_row" >';
            tds += '<input type="hidden" name="takaTb_id[]" >';

            tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';

            tds += '<td>' + suggestion.text + inp + '</td>';
            tds += '<td>' + suggestion.hsn + '</td>';

            tds += '<td><select name="type[]" onchange="calculate()">' + suggestion.uom +
                '</select></td>';

            tds += '<td><input id="rate" class="form-control input-sm" value="' + suggestion.price
                .purchase_cost +
                '" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + suggestion.price.igst +
                '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" id="taka" value="" name="taka[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"><a data-toggle="modal" type="button" id="add_taka" href="<?= url("Milling/Add_Challantaka/");?>' +
                suggestion.id +
                '" data-target="#fm_model" data-title="Add Taka" class=""><i class="far fa-edit"></i></a><b class="pcs_uom"></b></td>';

            tds +=
                '<td><input class="form-control input-sm" type="text" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" id="meter" name="meter[]" required readonly><b class="other_uom"></b></td>';

            tds +=
                '<td><input class="form-control input-sm" id="cut" value="" name="cut[]" readonly onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" type="text"></td>';
            tds +=
                '<td><input class="form-control input-sm" id="subt" name="subtotal[]" onchange="calculate()" value="0" required="" type="text" readonly></td>';
            tds += '<td><input class="form-control input-sm" value="" name="extra[]" type="text"></td>';
            tds += '</tr>';

            $('.tbody').append(tds);
            $('#code').val('');
            $('#suggesion').html('Please Add Challan Detail..!!');

            calculate();
        } else {
            $('.product_error').html('Selected Product Already Added');
            $('#code').val('');
        }
    });

    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.dateMask').mask('99-99-9999');

    $("#account").select2({
        width: '70.5%',
        placeholder: 'Type Account Name',
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
        // $('#gst').val(data.gsttin);
        $('#tds_per').val(data.tds);
        $('#tds_limit').val(data.tds_limit);
        $('#acc_state').val(data.state);

        calculate();
    });

    $("#delivery").select2({
        width: 'resolve',
        placeholder: {
            id: '', // the value of the option
            text: 'None Selected'
        },
        allowClear: true,
        ajax: {
            url: PATH + "Master/Getdata/search_accountSundry_cred_debt",
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

    $('#delivery').on('select2:select', function(e) {
        var data = e.params.data;
        console.log(data)
        $('textarea[name=delivery_code]').html(data.address);
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

    $("#warehouse").select2({
        width: '100%',
        placeholder: 'Type Warehouse Account',
        ajax: {
            url: PATH + "Master/Getdata/search_warehouse",
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


    $("#mill_ac").select2({
        width: '100%',
        placeholder: 'Type Mill Ac Name',
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
});

function add_item(data_edit) {
    var type = 'Status';
    var data_val = $(data_edit).data('val');
    var ot_title = $(data_edit).attr('title');
    var pkno = $(data_edit).data('pk');
    var select_input = {
        "mtr": "Mtr",
        "cut": "Cut",
        "pcs": "PCS"
    };

    swal({
        title: ot_title,
        confirmButtonText: "Save",
        input: "select",
        inputValue: data_val,
        inputOptions: select_input,
        showCancelButton: !0,
        inputValidator: function(e) {
            return !e && "You need to write something!"
        }
    }).then(function(result) {

        _data = $.param({
            pk: pkno
        }) + '&' + $.param({
            val: result.value
        }) + '&' + $.param({
            type: type
        }) + '&' + $.param({
            method: $("#table_list_data").data('id')
        });

        if (result.value != undefined && result.value != '') {
            $.post(PATH + "/" + $("#table_list_data").data('module') + "/Action/Update", _data, function(data) {

                if (data.st == 'success') {
                    var selectdata = result.value;
                    $(data_edit).data('val', selectdata);
                    $(data_edit).html(select_input[selectdata]);
                    swal("success!", "Your update successfully!", "success");

                }

            });
        }
    });
}

function calculate() {


    var price = $('input[name="price[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var igst = $('input[name="igst[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var taka = $('input[name="taka[]"]').map(function() {
        return parseFloat(this.value);
    }).get();



    var meter = $('input[name="meter[]"]').map(function() {
        return parseFloat(this.value);
    }).get();



    var cut = $('input[name="cut[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var type = [];
    $('select[name="type[]"] option:selected').each(function() {
        var $this = $(this);
        if ($this.length) {
            type.push($this.text())

        }
    });
    var total = 0.0;
    var igst_amt = 0.0;
    var tot_item_brok = 0.0;
    var tot_fix_brok = 0.0;
    var mtr_total = 0;

    for (var i = 0; i < taka.length; i++) {
        if (type[i] == "PCS") {
            var sub = price[i] * taka[i];
            // var disc_amt = sub * item_disc[i] / 100;
            var final_sub = sub;
            $('input[name="subtotal[]"]').eq(i).val(final_sub);

            igst_amt += final_sub * igst[i] / 100;
            total += final_sub;

            uom_name = $('select[name="type[]"] :selected').eq(i).text();

            $('input[name="subtotal[]"]').eq(i).closest('.item_row').find('.other_uom').html('');
            $('input[name="subtotal[]"]').eq(i).closest('.item_row').find('.pcs_uom').html('/ ' + uom_name);

        } else {

            var sub = price[i] * (meter[i] - cut[i]);
            $('input[name="meter[]"]').eq(i).val(meter[i]);
            //var disc_amt = sub * item_disc[i] / 100;

            var final_sub = sub;
            igst_amt += final_sub * igst[i] / 100;

            $('input[name="subtotal[]"]').eq(i).val(final_sub);
            total += final_sub;

            uom_name = $('select[name="type[]"] :selected').eq(i).text();

            $('input[name="subtotal[]"]').eq(i).closest('.item_row').find('.pcs_uom').html('');
            $('input[name="subtotal[]"]').eq(i).closest('.item_row').find('.other_uom').html('/ ' + uom_name);

        }
    }

    $('.total').html(total);

}


function subtotal(type) {
    var pcs = $("#pcs").val();
    var mtr = $("#mtr").val();
    var cut = $("#cut").val();
    var rate = $("#rate").val();
    
    if (type == 'pcs') {
        stotal = pcs * rate;
        //amount=
    } else if (type == 'mtr') {
        stotal = mtr * rate;
    } else {
        subtotal = cut * rate;
    }
    $("#subt").val(stotal);
    //alert(type);
}
</script>
<?= $this->endSection() ?>