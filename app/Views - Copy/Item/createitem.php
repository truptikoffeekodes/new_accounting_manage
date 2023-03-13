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
                                                value="<?=@$item['name']?>" placeholder="Enter Item Name" required=""
                                                type="text">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Code:</b><span
                                                    class="tx-danger">*</span></label>
                                            <input class="form-control" id="code" name="code"
                                                value="<?=@$item['code']?>" placeholder="Enter Code" required=""
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
                                                    name="item_mode" type="radio" required value="general">
                                                <span>General
                                                    Item</span></label>
                                            <label class="rdiobox"><input
                                                    <?= (@$item['item_mode'] == "milling" ? 'checked' : '' ) ?>
                                                    name="item_mode" type="radio" required value="milling">
                                                <span>Milling
                                                    Item</span></label>
                                        </div>
                                        <!-- <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Type:</b> <span
                                                    class="tx-danger">*</span></label> -->
                                        <!-- <select class="form-control select2" value="<?=@$item['type']?>" name="item_type"
                                                tabindex="-1" aria-hidden="true" required>
                                                <option label="Select type">
                                                </option>
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
                                            </select> -->

                                        <!-- </div> -->
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
                                            <label class="form-label"><b>UOM:</b> <span class="tx-danger">*</span></label>
                                            <div class="input-group"> 
                                                <select class="form-control" id="uom" name='uom[]' multiple>
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
                                        <!-- <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Brokerage Type:</b> </label>
                                            <select class="form-control select2" name="bro_typ" tabindex="-1"
                                                aria-hidden="true">

                                                <option value="none"
                                                    <?= (@$item['brokerge_type'] == "None" ? 'selected' : '' ) ?>>
                                                    None
                                                </option>
                                                <option value="percentage"
                                                    <?= (@$item['brokerge_type'] == "percentage" ? 'selected' : '' ) ?>>
                                                    Percentage
                                                </option>
                                                <option value="quentity_base"
                                                    <?= (@$item['brokerge_type'] == "Quentity Base" ? 'selected' : '' ) ?>>
                                                    Quentity Base
                                                </option>
                                            </select>
                                        </div> -->
                                        <!-- <div class="col-lg-4 form-group">
                                            <label class="form-label">Action on Min/Max: </label>
                                            <select class="form-control select2" name="min_max_action" tabindex="-1"
                                                aria-hidden="true">
                                                <option label="Select Action">
                                                </option>
                                                <option <?= (@$item['action_on_min'] == "ignore" ? 'selected' : '' ) ?>
                                                    value="ignore">
                                                    Ignore
                                                </option>
                                                <option <?= (@$item['action_on_min'] == "warn" ? 'selected' : '' ) ?>
                                                    value="warn">
                                                    Warn
                                                </option>
                                                <option <?= (@$item['action_on_min'] == "confirm" ? 'selected' : '' ) ?>
                                                    value="confirm">
                                                    Confirm
                                                </option>
                                                <option <?= (@$item['action_on_min'] == "block" ? 'selected' : '' ) ?>
                                                    value="block">
                                                    Block
                                                </option>
                                            </select>
                                        </div> -->
                                        <!-- <div class="col-lg-4 form-group">
                                            <label class="form-label">Sales Price Per Qty : </label>
                                            <input class="form-control" name="sales_price_qty"
                                                value="<?=@$item['sales_price_per_qty']?>"
                                                placeholder="Enter Sales Price Per Qty" type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div> -->
                                        <!-- <div class="col-lg-4 form-group">
                                            <label class="form-label">Path Of Image : </label>
                                            <input class="form-control" name="image" value="<?=@$item['']?>"
                                                placeholder=""  type="file">
                                        </div> -->
                                        <!-- <div class="media d-block d-sm-flex">
                                            <img alt="img" class="wd-100p wd-sm-200  mg-sm-r-20 mg-b-20 mg-sm-b-0"
                                                src="../../assets/img/media/1.jpg">

                                            <div class="col-lg-6 form-group">
                                                <button class="btn btn-danger">Image Clear</button>
                                            </div>
                                        </div> -->
                                    </div>
                                </section>
                                <h3>Inventory Info</h3>
                                <section>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Stock:</b> </label>
                                            <input class="form-control" name="stock_rate"
                                                value="<?=@$item['stock_rate']?>" placeholder="Enter Stock Rate"
                                                type="text">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Minimum Level:</b> </label>
                                            <input class="form-control" name="min_level"
                                                value="<?=@$item['minmun_level']?>" placeholder="Enter Minimum Level"
                                                type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Warn If Below Min Level:</b> </label>
                                            <select class="form-control select2" name="warn_below" tabindex="-1"
                                                aria-hidden="true">
                                                <option label="Select one">
                                                </option>
                                                <option value="yes"
                                                    <?= (@$item['warn_min_level'] == "yes" ? 'selected' : '' ) ?>>
                                                    Yes
                                                </option>
                                                <option value="no"
                                                    <?= (@$item['warn_min_level'] == "no" ? 'selected' : '' ) ?>>
                                                    No
                                                </option>
                                            </select>
                                        </div>
                                        <!-- <div class="col-lg-4 form-group">
                                            <label class="form-label">Maximum Level : </label>
                                            <input class="form-control" name="max_levell"
                                                value="<?=@$item['max_level']?>" placeholder="Enter Maximum Level"
                                                 type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label">Warn If Below Max Level: </label>
                                            <select class="form-control select2" name="warn_max" tabindex="-1"
                                                aria-hidden="true">
                                                <option label="Select one" data-select2-id="15">
                                                </option>
                                                <option value="yes"
                                                    <?= (@$item['warn_max_level'] == "yes" ? 'selected' : '' ) ?>>
                                                    Yes
                                                </option>
                                                <option value="no"
                                                    <?= (@$item['warn_max_level'] == "no" ? 'selected' : '' ) ?>>
                                                    No
                                                </option>
                                            </select>
                                        </div> -->
                                        <!-- <div class="col-lg-4 form-group">
                                            <label class="form-label">Recoder Level : </label>
                                            <input class="form-control" name="rec_level"
                                                value="<?=@$item['recoder_level']?>" placeholder="Enter Recoder Level"
                                                type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label">Recoder Quentity : </label>
                                            <input class="form-control" name="rec_qual"
                                                value="<?=@$item['recoder_qty']?>" placeholder="Enter Recoder Quentity"
                                             type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label">Warn If Below Recorder Level: </label>
                                            <select class="form-control select2" name="blw_rec_wrn" tabindex="-1"
                                                aria-hidden="true">
                                                <option label="Select one" data-select2-id="15">
                                                </option>
                                                <option value="yes"
                                                    <?= (@$item['warn_recoder_level'] == "yes" ? 'selected' : '' ) ?>>
                                                    Yes
                                                </option>
                                                <option value="no"
                                                    <?= (@$item['warn_recoder_level'] == "no" ? 'selected' : '' ) ?>>
                                                    No
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label">Action On -ve Stock: </label>
                                            <select class="form-control select2" name="neg_action" tabindex="-1"
                                                aria-hidden="true">
                                                <option label="Select Action" data-select2-id="15">
                                                </option>
                                                <option value="ignore"
                                                    <?= (@$item['action_on_stock'] == "ignore" ? 'selected' : '' ) ?>>
                                                    Ignore
                                                </option>
                                                <option value="warn"
                                                    <?= (@$item['action_on_stock'] == "warn" ? 'selected' : '' ) ?>>
                                                    Warn
                                                </option>
                                                <option value="confirm"
                                                    <?= (@$item['action_on_stock'] == "confirm" ? 'selected' : '' ) ?>>
                                                    Confirm
                                                </option>
                                                <option value="block"
                                                    <?= (@$item['action_on_stock'] == "block" ? 'selected' : '' ) ?>>
                                                    Block
                                                </option>
                                            </select>
                                        </div> -->
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>HSN:</b> </label>
                                            <input class="form-control" name="hsn" value="<?=@$item['vat_hsn']?>"
                                                placeholder="Enter HSN" type="text">
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
                                        <!-- <div class="col-lg-4 form-group">
                                            <label class="form-label">Whole Sale Rate : </label>
                                            <input class="form-control" name="whole_sale_rate"
                                                value="<?=@$item['whole_sale']?>" placeholder="Enter Whole Sale Rate"
                                                required="" type="text">
                                        </div> -->
                                        <!-- <div class="col-lg-4 form-group">
                                            <label class="form-label">Fold(%) : </label>
                                            <input class="form-control" name="fold" value="<?=@$item['fold']?>"
                                                placeholder="Enter Fold" type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div> -->
                                        <!-- <div class="col-lg-4 form-group">
                                            <label class="form-label">Discount(%) : </label>
                                            <input class="form-control" name="discount" value="<?=@$item['discount']?>"
                                                placeholder="Enter Discount" required="" type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div> -->
                                        <!-- <div class="col-lg-4 form-group">
                                            <label class="form-label">AddLess1(%) : </label>
                                            <input class="form-control" name="add_less" value="<?=@$item['addless']?>"
                                                placeholder="Enter AddLess1" type="text" onkeypress="return isDesimalNumberKey(event)">
                                        </div> -->
                                        <!-- <div class="col-lg-4 form-group">
                                            <label class="form-label">Finish Item : </label>
                                            <input class="form-control" name="finish_item"
                                                value="<?=@$item['finish_item']?>" placeholder="Enter Finish Item"
                                                required="" type="text">
                                        </div> -->
                                    </div>
                                </section>
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
function datatable_load(){

}
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

    // $('#uomgrp').autocomplete({
    //     serviceUrl: '<?= url('Master/Getdata/search_uom') ?>',
    //     type: 'POST',
    //     showNoSuggestionNotice: true,
    //     onSelect: function(suggestion) {
    //         $('#uomgrp').val(suggestion.value);
    //         $('#uomgrp_id').val(suggestion.data);
    //         $('#uomformat').val(suggestion.format);
    //     }
    // });

    $('input[type=radio][name=item_mode]').change(function() {

        if (this.value == 'general') {
            var general =
                "<option value='Inventory'>Inventory</option><option value='Service'>Service</option><option value='NonInventory' >Non-Inventory</option><option value='Group'>Group</option>";

            $("#type").append(general);
            $("#type option[value='Grey']").remove();
            $("#type option[value='Finish']").remove();
            $("#type option[value='Jobwork']").remove();
        } else {

            var milling =
                "<option value='Grey'>Grey</option><option value='Finish'>Finish</option><option value='Jobwork' >Jobwork</option>";

            $("#type").append(milling);
            $("#type option[value='Inventory']").remove();
            $("#type option[value='Service']").remove();
            $("#type option[value='NonInventory']").remove();
            $("#type option[value='Group']").remove();

        }

    });


    // $('#item_grp').autocomplete({
    //     serviceUrl: '<?= url('Master/Getdata/search_itemgrp') ?>',
    //     type: 'POST',
    //     showNoSuggestionNotice: true,
    //     onSelect: function(suggestion) {
    //         //alert(suggestion.data);
    //         $('#item_grp').val(suggestion.value);
    //         $('#item_grp_id').val(suggestion.data);
    //     }
    // });

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
});
</script>
<?= $this->endSection() ?>