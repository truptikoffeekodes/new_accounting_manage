<?= $this->extend(THEME . 'form') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('master/add_item') ?>" class="ajax-form-submit" method="post">

            <div class="form-group">
                <label class="form-label"><b>Item Name: </b><span class="tx-danger">*</span></label>
                <input class="form-control" name="name" onkeyup="itm_code_generate(this.value)" value=""
                    placeholder="Enter Item Name" required="" type="text">
            </div>

            <div class="form-group">
                <label class="form-label"><b>Code:</b><span class="tx-danger">*</span></label>
                <input class="form-control" id="itm_code" name="code" value="" placeholder="Enter Code" required=""
                    type="text">
                <input class="form-control" name="id" value="" type="hidden">
            </div>

            <div class="form-group">
                <label class="form-label"><b>SKU :</b> </label>
                <input class="form-control" name="sku" value="" placeholder="Enter Part Number" type="text">
            </div>

            <div class="form-group">
                <label class="form-label"><b>Item Type :</b> </label>
                <?php if(@$type == 'general'){?>

                <label class="rdiobox"><input name="item_mode" type="radio" required
                        <?=($type=='general') ? 'checked' : '' ?> value="general">
                    <span>General Item</span></label>

                <?php }else if(@$type == 'mill'){ ?>

                <label class="rdiobox"><input name="item_mode" type="radio" required
                        <?=($type=='mill') ? 'checked' : '' ?> value="milling">
                    <span>Milling Item</span></label>

                <?php }else{?>
                    <label class="rdiobox"><input name="item_mode" type="radio" required
                       value="general">
                    <span>General Item</span></label>
                    <label class="rdiobox"><input name="item_mode" type="radio" required
                       value="milling">
                    <span>Milling Item</span></label>

                <?php } ?>

            </div>
            <?php if(@$type == 'general'){?>

            <div class="form-group">
                <label class="form-label"><b>Type:</b> <span class="tx-danger">*</span></label>
                <select class="form-control select2" required value="" name="item_type">

                    <option value="Inventory">Inventory</option>
                    <option value="Service">Service</option>
                    <option value="NonInventory">NonInventory</option>
                    <option value="Group">Group</option>
                </select>
            </div>
            <?php }else if(@$type == 'mill'){ ?>

            <div class="form-group">
                <label class="form-label"><b>Type:</b> <span class="tx-danger">*</span></label>
                <select class="form-control select2" required value="" name="item_type">
                    <option value="Grey">Grey</option>
                    <option value="Finish">Finish</option>
                    <option value="Jobwork">Jobwork</option>
                </select>
            </div>
            <?php }else{?>
            <div class="form-group" id="general">
                <label class="form-label"><b>Type:</b> <span class="tx-danger">*</span></label>
                <select class="form-control select2" required value="" name="item_type" id="type">
                </select>
            </div>
            <?php } ?>

            <div class="form-group">
                <label class="form-label"><b>Item Group:</b> </label>
                <div class="input-group">
                    <select class="form-control" id="item_grp" name='item_grp'>

                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label"><b>HSN:</b> </label>
                <input class="form-control" name="hsn" value="<?=@$item['vat_hsn']?>" placeholder="Enter HSN"
                    type="text">
            </div>

            <div class="row">
                <div class="col-lg-4 form-group">
                    <label class="form-label"><b>IGST:</b> </label>
                    <input class="form-control" name="igst" onkeyup="calc_gst(this.value)" id="item_igst" value=""
                        placeholder="Enter IGST" required="" type="text">
                </div>
                <div class="col-lg-4 form-group">
                    <label class="form-label"><b>CGST:</b> </label>
                    <input class="form-control" name="cgst" id="item_cgst" value="" placeholder="Enter CGST" required=""
                        type="text">
                </div>
                <div class="col-lg-4 form-group">
                    <label class="form-label"><b>SGST:</b> </label>
                    <input class="form-control" name="sgst" id="item_sgst" value="" placeholder="Enter SGST" required=""
                        type="text">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label"><b>Default cut:</b> </label>
                <input class="form-control" name="default_cut" value="" onkeypress="return isDesimalNumberKey(event)"
                    placeholder="Enter Default Cut" type="text">
            </div>

            <div class="form-group">
                <label class="form-label"><b>UOM:</b> <span class="tx-danger">*</span></label>
                <div class="input-group">
                    <select class="form-control" id="uom" name='uom[]' multiple required>

                    </select>

                </div>
            </div>

            <div class="form-group">
                <div class="tx-danger error-msg"></div>
                <div class="tx-success form_proccessing"></div>
            </div>

            <div class="row pt-3">
                <div class="col-sm-6">
                    <p class="text-left">
                        <button class="btn btn-space btn-primary" id="itemsave_data" type="submit">Submit</button>
                        <button class="btn btn-space btn-secondary" data-dismiss="modal">Cancel</button>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Page Header -->


<script>
$('.ajax-form-submit').on('submit', function(e) {
    $('#itemsave_data').prop('disabled', true);
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
                $('#fm_model').modal('toggle');
                $('#code').append('<option selected value="'+response.id+'">'+response.data.name+'</option>');
                get_item_data(response.id,response.data);
                $('#itemsave_data').prop('disabled', false);
            } else {
                $('.form_proccessing').html('');
                $('#itemsave_data').prop('disabled', false);
                $('.error-msg').html(response.msg);
            }
        },
        error: function() {
            $('#itemsave_data').prop('disabled', false);
            alert('Error');
        }
    });
    return false;
});

function itm_code_generate(name) {

    var year = new Date().getFullYear();
    var substr = name.substring(0, 3)

    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var charactersLength = characters.length;
    var random = '';

    for (var i = 0; i < 4; i++) {
        random += characters.charAt(Math.floor(Math.random() * charactersLength));
    }

    var join = substr.concat(year);
    var finalstr = join.concat(random);

    var code = finalstr.toUpperCase();

    $('#itm_code').val(code);
}

function calc_gst(igst) {
    var gst = igst / 2;
    $('#item_cgst').val(gst);
    $('#item_sgst').val(gst);
}

function afterload() {

    $('select[name="item_type"]').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        width: '100%'
    });

    $('#fm_model').on('shown.bs.modal', function() {
        $('.fc-datepicker').datepicker({
            format: "dd/mm/yyyy",
            startDate: "01-01-2015",
            endDate: "01-01-2020",
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            container: '#fm_model modal-body'
        });

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
}
</script>
<?= $this->endSection() ?>