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
        <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Mill</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
    <div class="ml-auto pd-r-100">
        <h2 class="mb-1 font-weight-bold"><span>Mill Issue Sr No :</span>
            <?= @$challan['sr_no'] ? $challan['sr_no'] : $current_id; ?></h2>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <form action="<?= url('Milling/add_millSend') ?>" class="ajax-form-submit" method="POST"
                        id="challanform">
                        <div class="row">

                            <input class="form-control col-md-9" type="hidden" name="id"
                                value="<?= @$challan['id'] ? $challan['id'] : @$id; ?>" placeholder="Enter id">

                            <input class="form-control col-md-9" type="hidden" name="srno" id="srno" readonly
                                value="<?= @$challan['sr_no'] ? $challan['sr_no'] : @$current_id; ?>" required>


                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-3">Weaver Name/No: <span
                                        class="tx-danger">*</span></label>
                                <select class="form-control col-md-9" id="get_challan" name='challan'>
                                    <?php if(@$challan['challan_no']) { ?>
                                    <option value="<?=@$challan['challan_no']?>">
                                        <?=@$challan['challan_name']?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-4">Challan Date: <span
                                        class="tx-danger">*</span></label>
                                <div class="col-md-8 form-group">
                                    <input class="form-control dateMask" placeholder="MM/DD/YYYY" type="text"
                                        name="challan_date"
                                        value="<?=@$challan['challan_date'] ? user_date($challan['challan_date']) : user_date(date('y-m-d'))?>">
                                </div>
                            </div>

                            <div class="col-lg-5 form-group">
                                <div class="row">

                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Transport Mode: </label>
                                    </div>

                                    <div class="col-md-8 form-group">
                                        <select class="form-control transport_mode" id="transport_mode"
                                            name="trasport_mode">

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
                                        <label class="form-label">Mill AC: <span class="tx-danger">*</span></label>
                                    </div>

                                    <div class="col-md-8 form-group">
                                        <div class="input-group" style="width:auto;">
                                            <select class="form-control account" required id="account" name='account'>
                                                <?php if(@$challan['mill_ac']) { ?>
                                                <option value="<?=@$challan['mill_ac']?>">
                                                    <?=@$challan['millAc_name']?>
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
                                        <label class="form-label">Shiped to Ac: </label>
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
                                        <textarea class="form-control delivery" name="delivery_code" value=""
                                            placeholder="Delivery Address"
                                            type="text"><?= @$challan['delivery_code']; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7 form-group">
                                <div class="row">

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR No.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control lrno" name="lrno" value="<?= @$challan['lr_no']; ?>"
                                            placeholder="LR No." type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR Date.: </label>
                                    </div>
                                    <?php 
                                        $lr_date = user_date(@$challan['lr_date']);
                                    ?>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control dateMask lr_date" placeholder="DD/MM/YYYY"
                                            type="text" id="lr_date" name="lr_date" value="<?= @$lr_date; ?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Weight: </label>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <input class="form-control weight" name="weight"
                                            value="<?= @$challan['weight']; ?>" placeholder="0.00"
                                            placeholder="Enter Weight" type="text">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Freight: </label>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <input class="form-control freight" name="freight"
                                            value="<?= @$challan['freight']; ?>" placeholder="00" type="text">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label class="form-label"> Warehouse: </label>
                                    </div>

                                    <div class="col-md-8 form-group">
                                        <select class="form-control warehouse" id="warehouse" name='warehouse'>
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
                                        <select class="form-control broker" id="broker" name='broker'>
                                            <?php if(@$challan['broker']) { ?>
                                            <option value="<?=@$challan['broker']?>">
                                                <?=@$challan['broker_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>


                                    <div class="col-md-2 form-group">
                                        <label class="form-label"> Transport </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <select class="form-control broker" id="transport" name='transport'>
                                            <?php if(@$challan['transport']) { ?>
                                            <option value="<?=@$challan['transport']?>">
                                                <?=@$challan['transport_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
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
                                            <th>Hsn</th>
                                            <th>Rate</th>
                                            <th>TAKA</th>
                                            <th>MTR</th>
                                            <th>Remarks</th>
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
                                                if($row['pcs'] != 0){
                                                ?>

                                        <tr class="<?=$row['pid']?>">
                                            <!-- <td><a class="tx-danger btnDelete" data-id="<?=$row['id']?>" title="0"><i
                                                                class="fa fa-times tx-danger"></i></a></td>
                                                     -->
                                            <td>#</td>
                                            <input type="hidden" name="pid[]" value="<?=$row['pid']?>">
                                            <input type="hidden" name="mill_takaTb_ids[]" value="">
                                            <input type="hidden" name="need_toDelete[]" value="">
                                            <input type="hidden" name="greyTakaTb_ids[]">

                                            <?php if(isset($row['from_challan'])){ ?>
                                            <input type="hidden" name="all_greyTakaTb_ids[]"
                                                value="<?=$row['all_greyTakaTb_ids']?>">
                                            <?php } ?>
                                            <td><?=$row['name']?>
                                            </td>
                                            <td><?=$row['hsn']?>
                                            </td>


                                            <td><input class="form-control input-sm"
                                                    value="<?=isset($row['from_challan']) ? '' :$row['price']?>"
                                                    name="price[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"></td>

                                            <?php
                                                        if(isset($row['from_challan'])){
                                                            $url = url("Milling/Add_millingtaka/").$row['pid'].'/'.$challan['challan_no'];
                                                        }else{
                                                            $url = url("Milling/Add_millingtaka/").$row['pid'].'/'.$challan['challan_no'].'/'.$challan['id'];
                                                        }
                                                    ?>

                                            <td><input class="form-control input-sm"
                                                    value="<?=isset($row['from_challan']) ? '' : $row['pcs']?>"
                                                    name="taka[]" readonly onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"><a data-toggle="modal" type="button" id="add_taka"
                                                    href="<?=$url?>" data-target="#fm_model" data-title="View Taka"><i
                                                        class="far fa-edit"></i></a>
                                            </td>

                                            <td>
                                                <input class="form-control input-sm" type="text" name="meter[]"
                                                    value="<?=isset($row['from_challan']) ? '' : @$row['meter']?>"
                                                    id="meter" readonly onchange="cxalculate()"
                                                    onkeypress="return isDesimalNumberKey(event)">
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$row['remark']?>"
                                                    name="remark[]" onchange="calculate()" type="text"></td>
                                        </tr>
                                        <?php 
                                                }
                                            } 
                                        }?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="row mt-3">

                                </div>
                            </div>
                            <div class="col-md-6">

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
function validate_autocomplete(obj, val) {
    if ($('#' + val).val() == '') {
        $('.' + val).html('Option Select from dropdown list')
    } else {
        $('.' + val).html('')
    }
}


$('.ajax-form-submit').on('submit', function(e) {
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
                swal("success!", "Your update successfully!", "success");
                $('.form_proccessing').html('');
                $('#save_data').prop('disabled', false);
                window.location = "<?=url('Milling/mill_challan')?>";
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
    });

    $("#code").select2({
        width: '100%',
        placeholder: 'Type Item Code ',
        ajax: {
            url: PATH + "Milling/Getdata/Item",
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

    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.dateMask').mask('99-99-9999');

    $("#account").select2({
        width: '66.5%',
        placeholder: 'Type Account Name',
        ajax: {
            url: PATH + "Master/Getdata/search_account_mill",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {

                return {
                    searchTerm: params.term, // search term
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
        console.log(data);
        $('#gst').val(data.gsttin);
        $('#tds_per').val(data.tds);
        $('#tds_limit').val(data.tds_limit);
        $('#acc_state').val(data.acc_state);
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

    $('#broker').on('select2:select', function(e) {
        var data = e.params.data;

        $('#fix_brokrage').val(data.brokrage);

    });

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

    $("#delivery_address").select2({
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
                    searchTerm: params.term
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



    $("#get_challan").select2({
        width: 'resolve',
        placeholder: 'Type Challan No.',
        ajax: {
            url: PATH + "Milling/Getdata/get_challan",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term, // search term
                    type: 'mill'
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
        $('.error-msg').html('');
        $(".tbody").empty();

        var suggesion = e.params.data;
        // console.log(suggesion.item);
        var item = suggesion.item;

        var acc = '<option selected value="' + suggesion.challan.party_name + '">' + suggesion.challan
            .account_name + '</option>';

        var trans = '<option selected value="' + suggesion.challan.transport + '">' + suggesion.challan
            .transport_name + '</option>';

        var deli = '<option selected value="' + suggesion.challan.delivery_code + '">' + suggesion
            .challan.delivery_name + '</option>';

        var brok = '<option selected value="' + suggesion.challan.broker + '">' + suggesion.challan
            .broker_name + '</option>';

        var warehouse = '<option selected value="' + suggesion.challan.warehouse + '">' + suggesion
            .challan
            .warehouse_name + '</option>';


        var tran_mode = '<option selected value="' + suggesion.challan.transport_mode + '">' + suggesion
            .challan.transport_mode + '</option>';


        var mtr = 0;

        for (i = 0; i < item.length; i++) {

            mtr = parseInt(item[i].meter) - parseInt(item[i].cut);
           

            if (item[i].pcs != 0 && mtr != 0) {
                
                var inp = '<input type="hidden" name="pid[]" value="' + item[i].id + '">';
                var tds = '<tr class="' + item[i].pid + '">';
                tds += '<input type="hidden" name="mill_takaTb_ids[]"  >';
                tds += '<input type="hidden" name="greyTakaTb_ids[]" >';
                tds += '<input type="hidden" name="all_greyTakaTb_ids[]" value="' + item[i].takaTB_ids +
                    '" >';
                tds += '<td><a class="tx-danger btnDelete" data-id="' + item[i].id +
                    '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';

                tds += '<td>' + item[i].name + inp + '</td>';

                tds += '<td>' + item[i].hsn + '</td>';

                tds +=
                    '<td><input id="rate" class="form-control input-sm" value="" name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

                tds +=
                    '<td><input class="form-control input-sm" id="taka" name="taka[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" required type="text"><a data-toggle="modal" type="button" id="add_taka" href="<?=url("Milling/Add_millingtaka/")?>' +
                    item[i].pid + '/' + suggesion.id +
                    '" data-target="#fm_model" data-title="Edit Taka" class=""><i class="far fa-edit"></i></a><p style="text-align:center;color:red;margin:0;padding:0;">' +
                    item[i].pcs + '</p></td>';

                tds +=
                    '<td><input class="form-control input-sm" type="text" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" id="meter" name="meter[]" required ><p style="text-align:center;color:red;">' +
                    mtr + '</p></td>';

                tds +=
                    '<td><input class="form-control input-sm"  name="remark[]"  value="' + item[i]
                    .extra + '"  type="text" ></td>';

                tds += '</tr>';
                $('.tbody').append(tds);
                $('#code').val('');
            }else{
                $('.error-msg').html('ALL TAKA ISSUED OF THIS CHALLAN ..!!')
            }

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
</script>
<?= $this->endSection() ?>