<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.product {
    width: 100%;
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

.select2_height .select2-container .select2-selection--single,
.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 28px;
}

.select2_height .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 25px !important;
}
</style>
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
                    <form action="<?= url('Milling/add_Mill_SaleChallan') ?>" class="ajax-form-submit" method="POST"
                        id="challanform">

                        <div class="row">

                            <div class="col-lg-4 form-group">
                                <!-- <label class="form-label">Challan No.: </label> -->
                                <label class="form-label">Mill Sale No.: <b><?=@$challan['id'] ? @$challan['id'] : $current_id; ?></b></label>
                                <!-- <input class="form-control" type="text" name="challan_no"
                                    value="<?= isset($challan['id']) ? @$challan['id'] : $current_id; ?>"> -->
                            </div>

                            <?php 
                            $dt = date_create(date('d-m-Y'));
                            $today = date_format($dt,'d-m-Y');
                            if(!empty($challan) && isset($challan)){
                                if(@$challan['date'] != '0000-00-00'){
                                    $dt = date_create($challan['date']);
                                    $date = date_format($dt,'d-m-Y');
                                }else{
                                    $dt = date('d-m-Y');
                                    $today = date_format($dt,'d-m-Y');
                                }
                            }else{
                                $dt = date_create(date('d-m-Y'));
                                $today = date_format($dt,'d-m-Y');
                            }
                            ?>

                            <div class="col-lg-4 form-group">
                                <label class="form-label">Challan Date: </label>
                                <input class="form-control dateMask" placeholder="MM/DD/YYYY" type="text"
                                    id="challan_date" name="challan_date"
                                    value="<?= @$challan['date'] ? $date : $today; ?>">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="form-label">Item Type : </label>
                                <label class="rdiobox"><input  <?=@$challan['item_type'] == 'gray' ? 'checked' : 'checked' ?> name="item_type" type="radio" required="" value="gray"
                                        aria-required="true" autocomplete="false">
                                    <span>Gray</span></label>
                                <label class="rdiobox"><input <?=@$challan['item_type'] == 'finish' ? 'checked' : '' ?> name="item_type" type="radio" required=""
                                        value="finish" aria-required="true" autocomplete="false">
                                    <span>Finish</span></label>
                            </div>

                            <div class="col-lg-5 form-group">
                                <div class="row">
                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Account: <span
                                                class="tx-danger">*</span></label>
                                        <select class="form-control" id="account" name='account'>
                                            <?php if(@$challan['account_name']) { ?>
                                            <option value="<?=@$challan['account']?>"><?=@$challan['account_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>

                                        <input type="hidden" name="id" value="<?= @$challan['id']; ?>">
                                        <input type="hidden" name="tds_per" id="tds_per"
                                            value="<?= @$challan['tds_per']; ?>">
                                        <input type="hidden" name="tds_limit" id="tds_limit"
                                            value="<?= @$challan['tds_limit']; ?>">
                                        <input type="hidden" name="acc_state" id="acc_state"
                                            value="<?= @$challan['acc_state']; ?>">
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">GST No.: <span
                                                class="tx-danger">*</span></label>
                                        <input readonly class="form-control col-md-8" type="text" name="gst" id="gst"
                                            value="<?= @$challan['gst']; ?>">
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Transport Mode </label>
                                        <select class="select2" id="transport_mode" name="trasport_mode">
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

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Vehicle No : </label>
                                        <select class="form-control" id="vehicle" name='vehicle'>
                                            <?php if(@$challan['vehicle_name']) { ?>
                                            <option value="<?=@$challan['vehicle_modeno']?>">
                                                <?=@$challan['vehicle_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="row col-md-12 form-group">
                                        <label class="form-label col-md-4">Broker : </label>
                                        <select class="form-control col-md-8" id="broker" name='broker'>
                                            <?php if(@$challan['broker_name']) { ?>
                                            <option value="<?=@$challan['broker']?>"><?=@$challan['broker_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-10 form-group">
                                        <label class="form-label">Add Item: </label>
                                        <select class="form-control" id="code" name='code'>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7 form-group">
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Other: </label>
                                    </div>
                                    <div class="col-md-10 form-group">
                                        <div class="input-group">
                                            <input class="form-control" name="other" value="<?=@$challan['other']?>"
                                                placeholder="Enter Other Detail" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR No.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="lrno" value="<?= @$challan['lr_no']; ?>"
                                            placeholder="LR No." type="text">
                                    </div>
                                    <?php 
                                        if(!empty($challan) && isset($challan)){
                                            if(@$challan['lr_date'] != '0000-00-00'){
                                                $lr_dt = date_create($challan['lr_date']);
                                                $lr_date = date_format($lr_dt,'d-m-Y');
                                            }
                                        }
                                    ?>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR Date.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control dateMask" placeholder="DD-MM-YYYY" type="text"
                                            id="lr_date" name="lr_date" value="<?= @$lr_date; ?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Weight.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="weight" value="<?= @$challan['weight']; ?>"
                                            placeholder="0.00" placeholder="Enter Weight" type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Freight.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control" name="freight" value="<?= @$challan['freight']; ?>"
                                            placeholder="00" type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Transport:</label>
                                    </div>
                                    <div class="col-md-10 form-group">
                                        <select class="form-control" id="transport" name='transport'>
                                            <?php if(@$challan['transport_name']) { ?>
                                            <option value="<?=@$challan['transport']?>"><?=@$challan['transport_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label class="form-label">Warehouse.: </label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <select class="form-control" id="warehouse" name='warehouse'>
                                            <?php if(@$challan['warehouse_name']) { ?>
                                            <option value="<?=@$challan['warehouse']?>"><?=@$challan['warehouse_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label class="form-label">Delivery Address: </label>
                                    </div>
                                    <div class="col-md-9 form-group">
                                        <input class="form-control" name="delivery_code"
                                            value="<?= @$challan['delivery_code']; ?>"
                                            placeholder="Enter Delivery Address" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0 product" id="product">
                                    <thead>
                                        <tr>
                                            <th style="width:10px;">#</th>
                                            <th style="width:80px;">Item</th>
                                            <th style="width:70px;">HSN</th>
                                            <th style="width:70px;">GST</th>
                                            <th style="width:80px;">Type</th>
                                            <th style="width:70px;">Select Taka</th>
                                            <th style="width:80px;">QTY</th>
                                            <th style="width:80px;">Price</th>
                                            <th style="width:80px;">Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        <?php 
                                            $total=0;
                                            if(isset($item)) {
                                                foreach($item as $row){
                                            
                                            ?>
                                        <tr class="<?=$row['pid']?>">

                                            <td><a class="tx-danger btnDelete" data-id="<?=$row['id']?>" title="0"><i
                                                        class="fa fa-times tx-danger"></i></a></td>
                                            <input type="hidden" name="pid[]" value="<?=$row['pid']?>">
                                            <input type="hidden" name="saleMillTaka_ids[]"
                                                value="<?=$row['sale_TakaId']?>">
                                            <td><?=$row['name']?> </td>
                                            <td><?=$row['hsn']?> </td>
                                            <td><input class="form-control input-sm" value="<?=$row['gst']?>"
                                                    name="gst[]" onchange="calculate()" type="text"> </td>

                                            <td>
                                                <select class="form-control select-sm" id="type" name="type[]"
                                                    onchange="calculate()">
                                                    <?=@$row['uom_opt']?>
                                                </select>
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$row['taka']?>"
                                                    name="total_taka[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"><a data-toggle="modal" type="button" id="add_taka"
                                                    href="<?=url("Milling/Add_SaleTaka/").$row['pid'].'/'.$row['id']?> "
                                                    data-target="#fm_model" data-title="Edit Taka" class="modal-lg"><i
                                                        class="far fa-edit"></i></a>
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=@$row['meter']?>"
                                                    name="total_qty[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text">
                                            </td>


                                            <td><input class="form-control input-sm" value="<?=$row['price']?>"
                                                    name="price[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" type="text">
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$row['remark']?>"
                                                    name="remark[]" onchange="calculate()"
                                                    onkeypress="return isDesimalNumberKey(event)" type="text">
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <td colspan="2" class="text-right">Total</td>
                                        <td class=""></td>
                                        <td class=""></td>
                                        <td class=""></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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
if(isset($id)){ ?>
calculate();
<?php } ?>


function validate_autocomplete(obj, val) {
    if ($('#' + val).val() == '') {
        $('.' + val).html('Option Select from dropdown list')
    } else {
        $('.' + val).html('')
    }
}

$('.ajax-form-submit').on('submit', function(e) {

    $('#save_data').prop('disabled', true);
    $('.error-msg').html('');
    // $('.form_proccessing').html('Please wail...');
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
                window.location = "<?=url('Milling/mill_sale_challan')?>";
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


function calculate() {

    var cut = $('input[name="cut[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var qty = $('input[name="total_qty[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var pcs = 0;
    for (var i = 0; i < cut.length; i++) {

        if (cut[i] != 'undefine' && !isNaN(cut[i])) {
            pcs = parseInt(qty[i] / cut[i]);
            sortage = qty[i] % cut[i];

            $('input[name="pcs[]"]').eq(i).val(pcs);
            $('input[name="sortage[]"]').eq(i).val(sortage);
        }
    }

}

$(document).ready(function() {

    $('.select2').select2({
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
        $('#code').attr('disabled', false);
        $('#challan_btn').attr('disabled', true);
        calculate();
    });
    $("#code").select2({
        width: '100%',
        placeholder: 'Type Item Code ',
        ajax: {
            url: PATH + "Milling/Getdata/GrayFinish_sale_Item",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                var item_type = $('input[type=radio][name=item_type]:checked').val();
                var type ='';
                if(item_type == 'finish'){
                    type ='Finish';
                }else{
                    type ='Grey';
                }
                return {
                    searchTerm: params.term,
                    type: type

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

            var tds = '<tr class="' + suggestion.id + '">';

            tds += '<input type="hidden" name="saleMillTaka_ids[]" value="">';

            tds += '<td><a class="tx-danger btnDelete" data-id="' + suggestion.id +
                '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';

            tds += '<td>' + suggestion.text + inp + '</td>';
            tds += '<td>' + suggestion.hsn + '</td>';
            tds +=
                '<td><input class="form-control input-sm"  name="gst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" type="text" value="' +
                suggestion.gst + '"></td>';

            tds +=
                '<td><select name="type[]" id="type"  onchange="calculate()">' + suggestion.uom +
                '</select></td>';

            tds +=
                '<td><input class="form-control input-sm"  name="total_taka[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required type="text"><a data-toggle="modal" type="button" id="add_taka" href="<?=url("Milling/Add_SaleTaka/")?>' +
                suggestion.id +
                '"  data-target="#fm_model" data-title="Edit Taka" class="modal-lg"><i class="far fa-edit"></i></a></td>';

            tds +=
                '<td><input class="form-control input-sm"  value="" name="total_qty[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required type="text"></td>';


            tds +=
                '<td><input class="form-control input-sm"  name="price[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm"  name="remark[]" onchange="calculate()"  value=""  type="text"></td>';

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
    $('input[type=radio][name=item_type]').change(function() {
        $('.tbody').empty();
    });

    $('.dateMask').mask('99-99-9999');


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
        $('#tds_limit').val(data.tds_limit);
        $('#acc_state').val(data.state);
        // $('.igst').val(suggestion.price.igst);
        // $('.cgst').val(suggestion.price.cgst);
        // $('.sgst').val(suggestion.price.sgst);

        // var com_state = parseInt(<?= session('state') ?>);

        // var acc_state = parseInt($('#acc_state').val());

        // if (com_state == acc_state) {

        //     $("#tax option[value='igst']").remove();
        //     if ($("#tax option[value='sgst']").length == 0) {
        //         $('#tax').append('<option value="sgst">sgst</option>');
        //     }
        //     if ($("#tax option[value='cgst']").length == 0) {
        //         $('#tax').append('<option value="cgst">cgst</option>');
        //     }
        //     $("#tax option[value='sgst']").attr("selected", "selected");
        //     $("#tax option[value='cgst']").attr("selected", "selected");

        // } else {
        //     $("#tax option[value='sgst']").remove();
        //     $("#tax option[value='cgst']").remove();

        //     if ($("#tax option[value='igst']").length == 0) {
        //         $('#tax').append('<option value="igst">igst</option>');
        //     }
        //     $("#tax option[value='igst']").attr("selected", "selected");
        // }

        // enable_gst_option();
        // calculate();

    });


    // $("#search_class").select2({
    //     width: 'resolve',
    //     placeholder: 'Type class Name',
    //     ajax: {
    //         url: PATH + "Master/Getdata/search_class",
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


    // $('#tax').on('select2:select', function(e) {
    //     var suggestion = e.params.data;
    //     var tax = $("#tax :selected").map(function(i, el) {
    //         return $(el).val();
    //     }).get();

    //     var igst = document.getElementById("igst");
    //     var sgst = document.getElementById("sgst");
    //     var cgst = document.getElementById("cgst");

    //     $.each(tax, function() {
    //         if (this == 'igst') {
    //             igst.style.display = "table-row";
    //         } else if (this == 'sgst') {
    //             sgst.style.display = "table-row";
    //         } else if (this == 'cgst') {
    //             cgst.style.display = "table-row";
    //         } else if (this == 'tds') {
    //             tds.style.display = "table-row";
    //         } else if (this == 'cess') {
    //             cess.style.display = "table-row";
    //         } else {}
    //     });
    // });

    // $('#tax').on('select2:unselect', function(e) {
    //     var suggestion = e.params.data;
    //     var tax = $("#tax :selected").map(function(i, el) {
    //         return $(el).val();
    //     }).get();

    //     var igst = document.getElementById("igst");
    //     var sgst = document.getElementById("sgst");
    //     var cgst = document.getElementById("cgst");
    //     var tds = document.getElementById("tds");
    //     var cess = document.getElementById("cess");
    //     // console.log(tax)
    //     var tax_array = ['igst', 'sgst', 'cgst', 'cess', 'tds'];
    //     var diff = arr_diff(tax_array, tax);
    //     // console.log(diff);

    //     $.each(diff, function() {
    //         if (this == 'igst') {
    //             igst.style.display = "none";
    //         } else if (this == 'sgst') {
    //             sgst.style.display = "none";
    //         } else if (this == 'cgst') {
    //             cgst.style.display = "none";
    //         } else if (this == 'cess') {
    //             cess.style.display = "none";
    //         } else if (this == 'tds') {
    //             tds.style.display = "none";
    //         } else {
    //             // cgst.style.display="table-row";
    //         }
    //         // if(this == 'cess'){
    //         //     cess.style.display="none";
    //         // }else{
    //         //     cess.style.display="table-row";
    //         // }
    //     });
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


    $("#warehouse").select2({
        width: 'resolve',
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

    // $("#finish_mill").select2({
    //     width: 'resolve',
    //     placeholder: 'Type Mill Ac Name',
    //     ajax: {
    //         url: PATH + "Milling/Getdata/finish_mill",
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

    // $('#finish_mill').on('select2:select', function(e) {
    //     $(".tbody").empty();
    //     var suggesion = e.params.data;

    //     var item = suggesion.item;

    //     // var igst = document.getElementById("igst");
    //     // var sgst = document.getElementById("sgst");
    //     // var cgst = document.getElementById("cgst");
    //     // var tds = document.getElementById("tds");
    //     // var cess = document.getElementById("cess");

    //     var acc = '<option selected value="' + suggesion.job.party_name + '">' + suggesion.job
    //         .account_name + '</option>';
    //     var deli = '<option selected value="' + suggesion.job.delivery_code + '">' + suggesion
    //         .job.delivery_name + '</option>';

    //     // var tran_mode = '<option selected value="' + suggesion.job.transport_mode + '">' + suggesion
    //     //     .job.transport_mode + '</option>';
    //     // var disc_type = '<option selected value="' + suggesion.job.disc_type + '">' + suggesion
    //     //     .job.disc_type + '</option>';
    //     // var amtx_mode = '<option selected value="' + suggesion.job.amtx_type + '">' + suggesion
    //     //     .job.amtx_type + '</option>';
    //     // var amty_mode = '<option selected value="' + suggesion.job.amty_type + '">' + suggesion
    //     //     .job.amty_type + '</option>';
    //     // var cess_mode = '<option selected value="' + suggesion.job.cess_type + '">' + suggesion
    //     //     .job.cess_type + '</option>';

    //     // var tax_option = [
    //     //     {
    //     //         id:1,
    //     //         text:"test"
    //     //     }
    //     // ]
    //     cno = suggesion.job.challan_no;
    //     //console.log(cno);
    //     $('#addinvoice').attr('href', 'Add_finishitem/' + cno);
    //     $('.account').append(acc);
    //     $('.delivery_code').append(deli);
    //     // $('.transport_mode').append(tran_mode);
    //     // $('.disc_type').append(disc_type);
    //     // $('.amtx_type').append(amtx_mode);
    //     // $('.amty_type').append(amty_mode);
    //     // $('.cess_type').append(cess_mode);
    //     $('.challan_no').val(suggesion.job.challan_no)
    //     $('.challan_date').val(suggesion.job.challan_date)
    //     $('.gst_no').val(suggesion.job.gst);
    //     $('.lrno').val(suggesion.job.lr_no);
    //     $('.lr_date').val(suggesion.job.lr_date);
    //     // $('.igst').val(suggesion.job.tot_igst);
    //     // $('.cgst').val(suggesion.job.tot_cgst);
    //     // $('.sgst').val(suggesion.job.tot_sgst);
    //     // $('.amtx').val(suggesion.job.amtx);
    //     // $('.amty').val(suggesion.job.amty);
    //     // $('.cess').val(suggesion.job.cess);
    //     // $('.tds_per').val(suggesion.job.tds_per);
    //     // $('.tds_amount').val(suggesion.job.tds_amt);
    //     // $('.discount').val(suggesion.job.discount);
    //     // $('.net_amount').val(suggesion.job.net_amount);
    //     $('.weight').val(suggesion.job.weight);
    //     $('.freight').val(suggesion.job.freight);
    //     $('#acc_state').val(suggesion.job.acc_state);
    //     // $('#tds_per').val(suggesion.job.tds_per);
    //     // $('#tds_limit').val(suggesion.job.tds_limit);

    //     //$('#subt').val(suggesion.job.amount);
    //     //console.log(suggesion.job.amount);

    //     // var taxes_str = suggesion.job.taxes;
    //     // var taxes_arr = JSON.parse(taxes_str);

    //     // for (i = 0; i < taxes_arr.length; i++) {

    //     //     var newOption = new Option(taxes_arr[i], taxes_arr[i], true, true);
    //     //     $('#tax').append(newOption).trigger('change');
    //     // }
    //     // $.each(taxes_arr, function() {
    //     //     if (this == 'igst') {
    //     //         igst.style.display = "table-row";
    //     //     } else if (this == 'sgst') {
    //     //         sgst.style.display = "table-row";
    //     //     } else if (this == 'cgst') {
    //     //         cgst.style.display = "table-row";
    //     //     } else if (this == 'tds') {
    //     //         tds.style.display = "table-row";
    //     //     } else if (this == 'cess') {
    //     //         cess.style.display = "table-row";
    //     //     } else {}
    //     // });

    //     for (i = 0; i < item.length; i++) {
    //         //  console.log(item[i].brokrage);
    //         var uom = item[i].mitype.split(',');

    //         var uom_option = '';
    //         for (j = 0; j < uom.length; j++) {
    //             uom_option += '<option value="' + uom[j] + '">' + uom[j] + '</option>';
    //         }

    //         var inp = '<input type="hidden" name="pid[]" value="' + item[i].id + '">';

    //         var tds = '<tr class="' + item[i].id + '">';
    //         tds += '<input type="hidden" name="tot_send_job[]" >';
    //         tds += '<input type="hidden" name="tot_pending_job[]" >';

    //         tds += '<td><a class="tx-danger btnDelete" data-id="' + item[i].id +
    //             '" title="0"><i class="fa fa-times tx-danger"></i></a></td>';
    //         tds +=
    //             '<td><a data-toggle="modal"  type="button" id="challan_btn" href="<?= url("Milling/add_jobwork_item/");?>' +
    //             item[i].id + "/" + item[i].Mitem_id +
    //             '" data-target="#fm_model" data-title="Add Item" class="">Add Jobwork</a></td>';

    //         tds += '<td>' + item[i].name + '(' + item[i].code + ')' + inp + '</td>';

    //         // tds += '<td><select name="type[]" class="form-control select-sm" id="type">' + uom_option + '</select></td>';

    //         // tds += '<td><input class="form-control input-sm" value="' + item[i]
    //         //     .igst +
    //         //     '" name="igst[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

    //         tds += '<td><input class="form-control input-sm" readonly value="' + item[i].finish_pcs +
    //             '" name="pcs[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

    //         tds +=
    //             '<td><input type="hidden" name="meter[]"  value="' + item[i].finish_mtr +
    //             '" id="meter"><input class="form-control input-sm" readonly name="mtr[]" onchange="calculate()" value="' +
    //             item[i].finish_mtr + '" type="text" ></td>';

    //         tds +=
    //             '<td><input class="form-control input-sm" id="sendJOB_pcs" value="" name="sendJOB_pcs[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" value="" required="" type="text"></td>';

    //         tds +=
    //             '<td><input class="form-control input-sm" value="" name="sendJOB_mtr[]" onchange="calculate()" onkeypress="return isDesimalNumberKey(event)" required="" type="text"></td>';


    //         tds += '</tr>';

    //         $('.tbody').append(tds);
    //         $('#code').val('');
    //         calculate();

    //     }

    // });    

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