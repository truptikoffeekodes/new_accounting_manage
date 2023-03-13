<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<style>
.error {
    color: red;
}
</style>

<!--colorpicker css-->
<link href="<?=ASSETS?>/plugins/spectrum-colorpicker/spectrum.css" rel="stylesheet">
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('Items/CreateItem') ?>" class="ajax-form-submit" method="post" id="itemform"
            enctype="multipart/form-data">
            <!-- Row -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div id="wizard1">
                                <h3>General info</h3>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Item Name: </b><span
                                                    class="tx-danger">*</span></label>
                                            <input class="form-control" name="name" onkeyup="code_generate(this.value)"
                                                value="<?=@$item['name']?>" style="text-transform: capitalize;"
                                                placeholder="Enter Item Name" required="" type="text">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Code:</b></label>
                                            <input class="form-control" id="code" name="code"
                                                value="<?=@$item['code']?>" placeholder="Enter Code" 
                                                type="text">
                                            <input class="form-control" name="id" value="<?=@$item['id']?>"
                                                type="hidden">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>SKU :</b> </label>
                                            <input class="form-control" name="sku" value="<?=@$item['sku']?>"
                                                placeholder="Enter Part Number" type="text"
                                                onkeypress="return isDesimalNumberKey(event)">
                                        </div>
                                        <div class="col-lg-4 form-group">

                                            <label class="form-label"><b>Item Type :</b> </label>
                                            <label class="rdiobox"><input
                                                    <?= (@$item['item_mode'] == "general" ? 'checked' : '' ) ?>
                                                    name="item_mode" type="radio" required value="general"
                                                    <?= !isset($item['item_mode']) ? 'checked' : '' ?>>
                                                <span>General Item</span></label>
                                            <label class="rdiobox"><input
                                                    <?= (@$item['item_mode'] == "milling" ? 'checked' : '' ) ?>
                                                    name="item_mode" type="radio" required value="milling">
                                                <span>Milling Item</span></label>
                                        </div>

                                        <div class="col-lg-4 form-group" id="general">
                                            <label class="form-label"><b>Type:</b> <span
                                                    class="tx-danger">*</span></label>
                                            <select class="form-control select2" required value="<?=@$item['type']?>"
                                                name="item_type" tabindex="-1" aria-hidden="true" required id="type">
                                                <option label="Select type">
                                                </option>
                                                <?php 
                                                    if(!empty($item['id']))
                                                    {
                                                ?>
                                                <option <?= (@$item['type'] == "Inventory" ? 'selected' : '' ) ?>
                                                    value="Inventory">
                                                    Inventory
                                                </option>
                                                <option <?= (@$item['type'] == "Service" ? 'selected' : '' ) ?>
                                                    value="Service">
                                                    Service
                                                </option>
                                                <option value="NonInventory"
                                                    <?= (@$item['type'] == "Non-Inventory" ? 'selected' : '' ) ?>>
                                                    Non-Inventory
                                                </option>
                                                <option value="Group"
                                                    <?= (@$item['type'] == "Group" ? 'selected' : '' ) ?>>
                                                    Group
                                                </option>
                                                <option <?= (@$item['type'] == "Grey" ? 'selected' : '' ) ?>
                                                    value="Grey">
                                                    Grey
                                                </option>
                                                <option <?= (@$item['type'] == "Jobwork" ? 'selected' : '' ) ?>
                                                    value="Jobwork">
                                                    Jobwork
                                                </option>

                                                <option value="Finish"
                                                    <?= (@$item['type'] == "Finish" ? 'selected' : '' ) ?>>
                                                    Finish
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Item Group:</b> </label>
                                            <div class="input-group">

                                                <select class="form-control" id="item_grp" name='item_grp'>
                                                    <?php if(@$item['item_grp_name']) { ?>
                                                    <option value="<?=@$item['item_grp']?>">
                                                        <?=@$item['item_grp_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>

                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <a data-toggle="modal" data-target="#fm_model"
                                                            data-title="Add Item Group"
                                                            href="<?=url('Master/add_itemgrp')?>"><i
                                                                style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Default cut:</b> </label>
                                            <input class="form-control" name="default_cut"
                                                value="<?=@$item['default_cut']?>"
                                                onkeypress="return isDesimalNumberKey(event)"
                                                placeholder="Enter Default Cut" type="text">
                                        </div>

                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>UOM:</b> <span
                                                    class="tx-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control" id="uom" name='uom[]' multiple required>
                                                    <?php 
                                                    if(@$item['uom']) { 
                                                        $item_uom = explode(',',@$item['uom']);
                                                        $uom_name = explode(',',@$item['uom_name']);
                                                        for($i=0;$i<count($item_uom);$i++){
                                                    ?>
                                                    <option value="<?=$item_uom[$i]?>" selected>
                                                        <?= @$uom_name[$i]?>
                                                    </option>
                                                    <?php } 
                                                    } ?>
                                                </select>

                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <a data-toggle="modal" data-target="#fm_model"
                                                            data-title="Add UOM Group"
                                                            href="<?=url('Master/Createuom')?>"><i
                                                                style="font-size:20px;" class="fe fe-plus-circle"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <h3>Purchase/Sales Info</h3>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Purchase Rate:</b> </label>
                                            <input class="form-control" name="purchase_cost"
                                                value="<?=@$item['purchase_cost']?>" placeholder="Enter Purchase Cost"
                                                type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Purchase Minimum Qty :</b> </label>
                                            <input class="form-control" name="purchase_min_qty"
                                                value="<?=@$item['purchase_min_qty']?>" placeholder="Enter Minimum Qty"
                                                type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Purchase Maximum Qty:</b> </label>
                                            <input class="form-control" name="purchase_max_qty"
                                                value="<?=@$item['purchase_max_qty']?>" placeholder="Enter Maximum Qty"
                                                type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div>

                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Sales Rate:</b> </label>
                                            <input class="form-control" name="sales_price"
                                                value="<?=@$item['sales_price']?>" placeholder="Enter Sales Price"
                                                type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Sales Minium Qty:</b> </label>
                                            <input class="form-control" name="sale_min_qty"
                                                value="<?=@$item['sale_min_qty']?>" placeholder="Enter Minimum Qty "
                                                type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Sales Maxmum Qty:</b> </label>
                                            <input class="form-control" name="sale_max_qty"
                                                value="<?=@$item['sale_max_qty']?>" placeholder="Enter Maxmum Qty "
                                                type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Brokrage(%):</b> </label>
                                            <input class="form-control" name="brokrage" value="<?=@$item['brokrage']?>"
                                                placeholder="Enter Brokrage " type="text"
                                                onkeypress="return isDesimalNumberKey(event)">
                                        </div>

                                    </div>
                                </section>
                                <h3>Inventory Info</h3>
                                <section>
                                    <div class="row">

                                        <div class="col-lg-3 form-group meter" id="meter">
                                            <label class="form-label"><b>Opening Stock :</b> </label>
                                            <input class="form-control" name="opening_stock"
                                                value="<?=@$item['opening_stock']?>" placeholder="Enter Opening Stock"
                                                type="text">
                                            <p class="tx-danger" id="opening_error"></p>
                                        </div>

                                        <div class="col-lg-3 form-group">
                                            <label class="form-label"><b>Opening Rate:</b> </label>
                                            <input class="form-control" name="opening_rate"
                                                value="<?=@$item['opening_rate']?>"
                                                placeholder="Enter Opening Stock Rate" onkeyup = "calc_opening_total(this.value)" type="text">
                                        </div>

                                        <div class="col-lg-3 form-group">
                                            <label class="form-label"><b>Opening Total Balance:</b> </label>
                                            <input class="form-control" name="opening_total"
                                                value="<?=@$item['opening_total']?>" placeholder="Enter Opening Total"
                                                type="text">
                                        </div>

                                        <div class="col-lg-3 form-group">
                                            <label class="form-label"><b>Opening Stock UOM:</b> </label>
                                            <select class="form-control" id="opening_uom" name='opening_uom'>
                                                <?php if(@$item['opening_uom']) { ?>
                                                    <option value="<?=@$item['opening_uom']?>">
                                                        <?=@$item['opening_uom_name']?>
                                                    </option>
                                                    <?php } ?>
                                            </select>
                                        </div>


                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Is non-GST Goods:</b> </label>
                                            <select class="form-control select2" name="non_gst" aria-hidden="true">
                                                <option label="Select one">
                                                </option>
                                                <option value="yes"
                                                    <?= (@$item['non_gst'] == "yes" ? 'selected' : '' ) ?>>
                                                    Yes
                                                </option>
                                                <option value="no" <?= (@$item['non_gst'] == "no" ? 'selected' : '' ) ?>
                                                    <?=!isset($item['non_gst']) ? 'selected' : '' ?>>
                                                    No
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>HSN:</b> </label>
                                            <input class="form-control" name="hsn" value="<?=@$item['hsn']?>"
                                                placeholder="Enter HSN" type="text">
                                        </div>

                                        <div class="col-lg-4 form-group taxability" id="taxability_div"
                                            style="display:<?=@$display?>">
                                            <label class="form-label"><b>Taxability:</b></label>
                                            <select class="form-control select2" id="taxability" name="taxability">
                                                <option <?=(@$item['taxability'] == "N/A" ? 'selected' : 'selecetd')?>
                                                    value="N/A">N/A</option>
                                                <option <?=(@$item['taxability'] == "Nill" ? 'selected' : 'selecetd')?>
                                                    value="Nill">Nill</option>
                                                <option <?=(@$item['taxability'] == "Taxable" ? 'selected' : '')?>
                                                    value="Taxable">Taxable</option>
                                                <option <?=(@$item['taxability'] == "Exempt" ? 'selected' : '')?>
                                                    value="Exempt">Exempt</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="gst_div"
                                        style="<?=@$item['taxability'] == 'Taxable' ? 'display:block;' : 'display:none;' ?>">
                                        <div class="row">
                                            <div class="col-lg-4 form-group">
                                                <label class="form-label"><b>Is Reverse Charge
                                                        Applicable:</b></label>
                                                <select class="form-control select2" name="rev_charge" required>
                                                    <option <?=(@$item['rev_charge'] == "0" ? 'selected' : 'selected')?>
                                                        value="0">
                                                        No</option>
                                                    <option <?=(@$item['rev_charge'] == "1" ? 'selected' : '')?>
                                                        value="1">
                                                        Yes</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-4 form-group">
                                                <label class="form-label"><b>Is ineligible For input
                                                        Credit:</b></label>
                                                <select class="form-control select2" name="ineligible" required>
                                                    <option <?=(@$item['ineligible'] == "0" ? 'selected' : 'selected')?>
                                                        value="0">
                                                        No</option>
                                                    <option <?=(@$item['ineligible'] == "1" ? 'selected' : '')?>
                                                        value="1">
                                                        Yes</option>

                                                </select>
                                            </div>

                                            <div class="col-lg-4 form-group">
                                                <label class="form-label"><b>IGST:</b> </label>
                                                <input class="form-control" name="igst" onkeyup="calc_gst(this.value)"
                                                    id="igst" value="<?=@$item['igst']?>" placeholder="Enter IGST"
                                                    required="" type="text">
                                            </div>

                                            <div class="col-lg-4 form-group">
                                                <label class="form-label"><b>CGST:</b> </label>
                                                <input class="form-control" name="cgst" id="cgst"
                                                    value="<?=@$item['cgst']?>" placeholder="Enter CGST" required=""
                                                    type="text">
                                            </div>

                                            <div class="col-lg-4 form-group">
                                                <label class="form-label"><b>SGST:</b> </label>
                                                <input class="form-control" name="sgst" id="sgst"
                                                    value="<?=@$item['sgst']?>" placeholder="Enter SGST" required=""
                                                    type="text">
                                            </div>

                                        </div>
                                    </div>

                                </section>
                                <div class="tx-danger">
                                    <p class="description_error"></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>



<?=$this->section('scripts')?>
<!-- Specturm-colorpicker js-->
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

function calc_gst(igst) {
    var gst = igst / 2;
    $('#cgst').val(gst);
    $('#sgst').val(gst);
}

function calc_opening_total(obj) {
    var rate = obj;
    var opening_stock = $('input[name="opening_stock"]').val();
    var opening_total = 0;


    if(opening_stock == '' || opening_stock =='NaN' || opening_stock =='undefined'){
        $('#opening_error').html("Please Enter Stock First");
    }else{
        $('#opening_error').html('');
        opening_total = parseFloat(rate) * parseFloat(opening_stock);
    }

    if(opening_total == '' || opening_total == 'NaN' || opening_total =='undefined'){
        opening_total = 0;
    }
    $('input[name="opening_total"]').val(opening_total);
    
}

$(document).ready(function() {

    var form = $("#itemform");
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
    // var finishButton = $('.wizard').find('a[href="#finish"]');
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
        onFinished: function(event, currentIndex) {
            var data = form.serialize();
            finishButton.html("<i class='sl sl-icon-reload'></i> Please wait...");
            //form_loading = false;
            $('.description_error').html('');
            var aurl = $('#itemform').attr('action');
            $.post(aurl, data, function(response) {
                console.log(response)
                if (response.st == 'success') {
                    window.location = "<?= url('Items') ?>"
                    //window.location.replace(response.location);
                } else {
                    finishButton.html("Create Item");
                    form_loading = true;
                    $('.description_error').html(response.msg);
                }
            }).fail(function(response) {
                finishButton.html("Create Item");
                form_loading = true;
                alert('Error');
            });
        }
    });

    $(':input').attr('autocomplete', 'false');
    $('.select2').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        width: '100%'
    });

    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $('#showAlpha').spectrum({
        color: 'rgba(23,162,184,0.5)',
        showAlpha: true
    });

   

    $('#taxability').on('select2:select', function(e) {
        var data = e.params.data;
        var gstDiv = document.getElementById("gst_div");
        if (data.id == 'Nill' || data.id == 'Exempt') {
            gstDiv.style.display = "none";
            $('input[name="igst"]').val('');
            $('input[name="cgst"]').val('');
            $('input[name="sgst"]').val('');
        } else {
            gstDiv.style.display = "block";
        }
    });

    $('input[type=radio][name=item_mode]').change(function() {
        var taka = document.getElementById('taka');
        var pcs = document.getElementById('pcs');

        if (this.value == 'general') {
            var general =
                "<option value='Inventory'>Inventory</option><option value='Service'>Service</option><option value='NonInventory' >Non-Inventory</option><option value='Group'>Group</option>";

            $("#type").append(general);
            $("#type option[value='Grey']").remove();
            $("#type option[value='Finish']").remove();
            $("#type option[value='Jobwork']").remove();



            taka.style.display = 'none';
            pcs.style.display = 'block';


        } else {

            var milling =
                "<option value='Grey'>Grey</option><option value='Finish'>Finish</option><option value='Jobwork' >Jobwork</option>";

            $("#type").append(milling);
            $("#type option[value='Inventory']").remove();
            $("#type option[value='Service']").remove();
            $("#type option[value='NonInventory']").remove();
            $("#type option[value='Group']").remove();


            taka.style.display = 'block';
            pcs.style.display = 'none';

        }

    });


    var item_mode = $('input[name="item_mode"]:checked').val();

    var taka = document.getElementById('taka');
    var pcs = document.getElementById('pcs');

    if (item_mode == 'general') {
        var general =
            "<option value='Inventory'>Inventory</option><option value='Service'>Service</option><option value='NonInventory' >Non-Inventory</option><option value='Group'>Group</option>";

        $("#type").append(general);
        $("#type option[value='Grey']").remove();
        $("#type option[value='Finish']").remove();
        $("#type option[value='Jobwork']").remove();



        // taka.style.display = 'none';
        // pcs.style.display = 'block';


    } else {

        var milling =
            "<option value='Grey'>Grey</option><option value='Finish'>Finish</option><option value='Jobwork' >Jobwork</option>";

        $("#type").append(milling);
        $("#type option[value='Inventory']").remove();
        $("#type option[value='Service']").remove();
        $("#type option[value='NonInventory']").remove();
        $("#type option[value='Group']").remove();


        // taka.style.display = 'block';
        // pcs.style.display = 'none';

    }


    $("#item_grp").select2({
        width: 'resolve',
        placeholder: 'Type Item Group',
        ajax: {
            url: PATH + "Master/Getdata/search_itemgrp",
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

    $("#uom").select2({

        width: 'resolve',
        placeholder: 'Type UOM',
        ajax: {
            url: PATH + "Master/Getdata/search_uom_data",
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

    $("#opening_uom").select2({

        width: '100%',
        placeholder: 'Type UOM',
        ajax: {
            url: PATH + "Master/Getdata/search_uom_data",
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